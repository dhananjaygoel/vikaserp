<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Customer;
use App\Units;
use App\DeliveryLocation;
use App\Order;
use App\AllOrderProducts;
use App\Http\Requests\PlaceOrderRequest;
use Input;
use DB;
use Auth;
use App\User;
use Hash;
use Mail;
use App;
use Config;
use App\OrderCancelled;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\DeliveryOrder;
use App\ProductSubCategory;
use DateTime;
use Session;

class OrderController extends Controller {

    public function __construct() {
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP', ['except' => ['create', 'store']]);
    }

    /**
     * Functioanlity: Display order details
     */
    public function index() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
            return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
        }

        $order_sorttype = Session::get('order-sort-type');
        if (isset($order_sorttype) && ($order_sorttype != "")) {
            $_GET['order_filter'] = $order_sorttype;
        }

        $q = Order::query();

        if (isset($_GET['order_filter']) && $_GET['order_filter'] != '') {
            $q->where('order_status', '=', $_GET['order_filter']);
        } else {
            $q->where('order_status', '=', 'pending');
        }
        if (isset($_GET['party_filter']) && $_GET['party_filter'] != '') {
            $q->where('customer_id', '=', $_GET['party_filter']);
        }
        if (isset($_GET['fulfilled_filter']) && $_GET['fulfilled_filter'] != '') {

            if ($_GET['fulfilled_filter'] == '0') {
                $q->where('order_source', '=', 'warehouse');
            }
            if ($_GET['fulfilled_filter'] == 'all') {
                $q->where('order_source', '=', 'supplier');
            }
        }
        if ((isset($_GET['location_filter'])) && $_GET['location_filter'] != '') {
            $q->where('delivery_location_id', '=', $_GET['location_filter']);
        }
        $product_category_id = 0;
        if (isset($_GET['size_filter']) && $_GET['size_filter'] != '') {
            $size = $_GET['size_filter'];
            $subquerytest = ProductSubCategory::select('id')->where('size', '=', $size)->first();
            if (isset($subquerytest)) {
                $product_category_id = $subquerytest->id;
                $q->whereHas('all_order_products.product_sub_category', function($query) use ($product_category_id) {
                    $query->where('id', '=', $product_category_id);
                });
            } else {
                return Redirect::back()->withInput()->with('flash_message', 'Please Enter Valid Size Name');
            }
        } else {
            $q->with('all_order_products');
        }

        $allorders = $q->with('all_order_products')
                        ->with('customer', 'delivery_location', 'order_cancelled')
                        ->orderBy('created_at', 'desc')->paginate(20);

        $users = User::all();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
//        $delivery_order = DeliveryOrder::all();
        $delivery_order = AllOrderProducts::where('order_type', '=', 'delivery_order')->where('product_category_id', '=', $product_category_id)->get();
        $product_size = ProductSubCategory::all();

        $users = User::all();
        $pending_orders = $this->checkpending_quantity($allorders);
        $allorders->setPath('orders');

        return View::make('orders', compact('delivery_location', 'delivery_order', 'customers', 'allorders', 'users', 'cancelledorders', 'pending_orders', 'product_size', 'product_category_id'));
    }

    /**
     * Functioanlity: Add new order page display
     */
    public function create() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return View::make('add_orders', compact('customers', 'units', 'delivery_locations'));
    }

    /**
     * Functioanlity: Save order details
     */
    public function store(PlaceOrderRequest $request) {
        $input_data = Input::all();
        if (Session::has('forms_order')) {
            $session_array = Session::get('forms_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This order is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_order', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_order', $forms_array);
        }
        $rules = array(
            'status' => 'required',
        );
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                $customers = new Customer();
                $customers->owner_name = $input_data['customer_name'];
                $customers->contact_person = $input_data['contact_person'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withErrors($validator)->withInput();
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withErrors($validator)->withInput();
            }
        }
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if (($product_data['name'] == "") || ($product_data['quantity'] == "")) {
                $i++;
            }
        }
        foreach ($input_data['product'] as $product_data) {
            if (($product_data['name'] != "") && ($product_data['quantity'] != "")) {
                if (($product_data['id'] == "") || ($product_data['id'] == 0) || ($product_data['id'] == '0')) {
                    return Redirect::back()->withInput()->with('flash_message', 'Please select product name again from autocomplete');
                }
            }
        }
        if ($i == $j) {
            return Redirect::back()->withInput()->with('flash_message', 'Please insert product details');
        }
        if ($input_data['add_order_location'] == '') {
            return Redirect::back()->withInput()->with('flash_message', 'Please select Delivery Location.');
        }
        if ($input_data['expected_date'] == '') {
            return Redirect::back()->withInput()->with('flash_message', 'Please select Expected Delivery date.');
        }
        if ($input_data['status'] == 'warehouse') {
            $order_status = 'warehouse';
            $supplier_id = 0;
        }
        if ($input_data['status'] == 'supplier') {
            $other_location_difference;
            $order_status = 'supplier';
            $supplier_id = $input_data['supplier_id'];
        }
        if ($input_data['status1'] == 'include_vat') {
            $vat_price = '';
        }
        if ($input_data['status1'] == 'exclude_vat') {
            $vat_price = $input_data['vat_price'];
        }
        if ($input_data['customer_status'] == "new_customer") {
            $customers->save();
            $customer_id = $customers->id;
        }
        $order = new Order();
        $order->order_source = $order_status;
        $order->supplier_id = $supplier_id;
        $order->customer_id = $customer_id;
        $order->created_by = Auth::id();
        $order->vat_percentage = $vat_price;
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
        $order->expected_delivery_date = $datetime->format('Y-m-d');
        $order->remarks = $input_data['order_remark'];
        $order->order_status = "Pending";
        if (isset($input_data['location']) && ($input_data['location'] != "")) {
            $order->delivery_location_id = 0;
            $order->other_location = $input_data['location'];
            $order->location_difference = $input_data['location_difference'];
        } else {
            $order->delivery_location_id = $input_data['add_order_location'];
            $order->location_difference = $input_data['location_difference'];
        }

        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR NEW ORDER
         * ----------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
            if ($customer->phone_number1 != "") {
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\n your order has been logged as following \n";
                    foreach ($input_data['product'] as $product_data) {
                        if ($product_data['name'] != "") {
                            $product = ProductSubCategory::find($product_data['id']);
                            $str .= $product->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ", \n";
                            if ($product_data['units'] == 1) {
                                $total_quantity = $total_quantity + $product_data['quantity'];
                            }
                            if ($product_data['units'] == 2) {
                                $total_quantity = $total_quantity + $product_data['quantity'] * $product->weight;
                            }
                            if ($product_data['units'] == 3) {
                                $total_quantity = $total_quantity + ($product_data['quantity'] / $product->standard_length ) * $product->weight;
                            }
                        }
                    }
                    $str .= " meterial will be desp by " . date("jS F, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer->phone_number1;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                    if (count($customer['manager']) > 0) {
//                        $str = "Dear '" . $customer['manager']->first_name . "'\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has logged an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk.\nVIKAS ASSOCIATES";
                        $str = urlencode($str);
                        if (App::environment('development')) {
                            $phone_number = Config::get('smsdata.send_sms_to');
                        } else {
                            $phone_number = $customer['manager']->mobile_number;
                        }
                        $msg = urlencode($str);
                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                        if (SEND_SMS === true) {
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $curl_scraped_page = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }
            }
        }
        $order->save();
        $order_id = DB::getPdo()->lastInsertId();
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if (($product_data['name'] != "") && ($product_data['id'] != "") && ($product_data['id'] > 0)) {
                $order_products = [
                    'order_id' => $order_id,
                    'order_type' => 'order',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_order_products = AllOrderProducts::create($order_products);
            }
        }

        /*
          | ---------------------------------------------
          | SEND EMAIL TO CUSTOMER ON CREATE OF NEW ORDER
          | ---------------------------------------------
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);
//            if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
            $order = Order::where('id', '=', $order_id)->with('all_order_products.order_product_details', 'delivery_location')->first();
            if (count($order) > 0) {
                if (count($order['delivery_location']) > 0) {
                    $delivery_location = $order['delivery_location']->area_name;
                } else {
                    $delivery_location = $order->other_location;
                }
                $mail_array = array(
                    'customer_name' => $customers->owner_name,
                    'expected_delivery_date' => $order->expected_delivery_date,
                    'created_date' => $order->updated_at,
                    'delivery_location' => $delivery_location,
                    'order_product' => $order['all_order_products'],
                    'source' => 'create_order'
                );
                $receipent = array();
                if (App::environment('development')) {
                    $receipent['email'] = Config::get('smsdata.emailData.email');
                    $receipent['name'] = Config::get('smsdata.emailData.name');
                } else {
                    $receipent['email'] = $customers->email;
                    $receipent['name'] = $customers->owner_name;
                }
                Mail::send('emails.new_order_mail', ['order' => $mail_array], function($message) use($receipent) {
                    $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: New Order');
                });
            }
//            }
        }
        return redirect('orders')->with('flash_message', 'Order details successfully added.');
    }

    /**
     * Functioanlity: Display order details of particulat order
     */
    public function show($id) {
        $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer')->find($id);
        if (count($order) < 1) {
            return redirect('orders')->with('flash_message', 'Order does not exist.');
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return View::make('order_detail', compact('order', 'delivery_location', 'units', 'customers'));
    }

    /**
     * Functioanlity: Show edit order details page
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer')->find($id);
        if (count($order) < 1) {
            return redirect('orders')->with('flash_message', 'Order does not exist.');
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::where('customer_status', 'permanent')->get();
        return View::make('edit_order', compact('order', 'delivery_location', 'units', 'customers'));
    }

    /**
     * Functioanlity: Update order details
     */
    public function update($id, PlaceOrderRequest $request) {

        $input_data = Input::all();
        if (Session::has('forms_edit_order')) {
            $session_array = Session::get('forms_edit_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This order is already updated. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_edit_order', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_order', $forms_array);
        }
        $rules = array(
            'status' => 'required',
        );
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $i = 0;
        $customer_id = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->with('flash_message', 'Please insert product details');
        }
        if (isset($input_data['customer_status']) && $input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                if (isset($input_data['pending_user_id']) && $input_data['pending_user_id'] > 0) {
                    $pending_cust = array(
                        'owner_name' => $input_data['customer_name'],
                        'contact_person' => $input_data['contact_person'],
                        'phone_number1' => $input_data['mobile_number'],
                        'credit_period' => $input_data['credit_period']
                    );
                    Customer::where('id', $input_data['pending_user_id'])->update($pending_cust);
                    $customer_id = $input_data['pending_user_id'];
                } else {
                    $customers = new Customer();
                    $customers->owner_name = $input_data['customer_name'];
                    $customers->contact_person = $input_data['contact_person'];
                    $customers->phone_number1 = $input_data['mobile_number'];
                    $customers->credit_period = $input_data['credit_period'];
                    $customers->customer_status = 'pending';
                    $customers->save();
                    $customer_id = $customers->id;
                }
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif (isset($input_data['customer_status']) && $input_data['customer_status'] == "existing_customer") {
            //mail
            $validator = Validator::make($input_data, Customer::$existing_customer_order_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        if ($input_data['status'] == 'warehouse') {
            $order_status = 'warehouse';
            $supplier_id = 0;
        }
        if ($input_data['status'] == 'supplier') {
            $order_status = 'supplier';
            $supplier_id = $input_data['supplier_id'];
        }
        if ($input_data['vat_status'] == 'include_vat') {
            $vat_price = '';
        }
        if ($input_data['vat_status'] == 'exclude_vat') {
            $vat_price = $input_data['vat_percentage'];
        }
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
        $order = Order::find($id);
        $update_order = $order->update([
            'order_source' => $order_status,
            'supplier_id' => $supplier_id,
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_inquiry_location'],
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['order_remark']
        ]);
        if ($input_data['add_inquiry_location'] == 'other') {
            $update_order = $order->update([
                'other_location' => $input_data['other_location_name'],
                'location_difference' => $input_data['location_difference']
            ]);
        } else {
            $update_order = $order->update([
                'other_location' => '',
                'location_difference' => $input_data['location_difference']
            ]);
        }
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if (($product_data['name'] != "") && ($product_data['order'] != '') && ($product_data['id'] != '') && ($product_data['id'] != 0)) {
                $order_products = [
                    'order_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $edit_order_products = AllOrderProducts::find($product_data['order']);
                $edit_order_products->update($order_products);
            }
            if ($product_data['name'] != "" && $product_data['order'] == '') {
                $order_products = [
                    'order_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_order_products = AllOrderProducts::create($order_products);
            }
        }


        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR NEW ORDER
         * ----------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nYour order has been edited and changed as following \n";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $product = ProductSubCategory::find($product_data['id']);
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                        if ($product_data['units'] == 1) {
                            $total_quantity = $total_quantity + $product_data['quantity'];
                        }
                        if ($product_data['units'] == 2) {
                            $total_quantity = $total_quantity + $product_data['quantity'] * $product->weight;
                        }
                        if ($product_data['units'] == 3) {
                            $total_quantity = $total_quantity + ($product_data['quantity'] / $product->standard_length ) * $product->weight;
                        }
                    }
                }
                $str .= " meterial will be desp by " . date("jS F, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
                if (count($customer['manager']) > 0) {
//                    $str = "Dear '" . $customer['manager']->first_name . "'\n'" . Auth::user()->first_name . "' has edited and changed an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk. \nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer['manager']->mobile_number;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }
        }

        /*
          | ---------------------------------------------
          | SEND EMAIL TO CUSTOMER ON UPDATE OF NEW ORDER
          | ---------------------------------------------
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);

            $order = Order::where('id', '=', $id)->with('all_order_products.order_product_details', 'delivery_location')->first();
            if (count($order) > 0) {
                if (count($order['delivery_location']) > 0) {
                    $delivery_location = $order['delivery_location']->area_name;
                } else {
                    $delivery_location = $order->other_location;
                }
                $mail_array = array(
                    'customer_name' => $customers->owner_name,
                    'expected_delivery_date' => $order->expected_delivery_date,
                    'created_date' => $order->created_at,
                    'delivery_location' => $delivery_location,
                    'order_product' => $order['all_order_products'],
                    'source' => 'update_order'
                );
                $receipent = array();
                if (App::environment('development')) {
                    $receipent['email'] = Config::get('smsdata.emailData.email');
                    $receipent['name'] = Config::get('smsdata.emailData.name');
                } else {
                    $receipent['email'] = $customers->email;
                    $receipent['name'] = $customers->owner_name;
                }

                Mail::send('emails.new_order_mail', ['order' => $mail_array], function($message) use($receipent) {
                    $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: Order Updated');
                });
            }
//            }
        }
        return redirect('orders')->with('flash_message', 'Order details successfully modified.');
    }

    /**
     * Functioanlity: Delete individual order details
     */
    public function destroy($id) {

        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        $order_sort_type = $formFields['order_sort_type'];

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if ($password == '') {
            return Redirect::to('orders')->with('error', 'Please enter your password');
        }
        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            $order = Order::find($id);
            AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'order')->delete();
            $order->delete();
            Session::put('order-sort-type', $order_sort_type);
            return array('message' => 'success');
        } else {
            return array('message' => 'failed');
        }
    }

    /*
     * Functioanlity: Manual Complete individual order
     */

    public function manual_complete_order() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $formFields = Input::get('formData');
        parse_str($formFields, $input);
        $order_id = $input['order_id'];
        $reason_type = $input['reason_type'];
        $reason = $input['reason'];
        $order = Order::where('id', '=', $order_id)->with('all_order_products.order_product_details', 'all_order_products.unit', 'customer')->first();

        /*
          | ------------------- ---------------------------------
          | SEND SMS TO CUSTOMER FOR MANUALLY COMPLETING AN ORDER
          | -----------------------------------------------------
         */

        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::where('id', '=', $order['customer']->id)->with('manager')->first();
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear '" . $customer->owner_name . "'\n your order has been completed for following \n";
                foreach ($order['all_order_products'] as $product_data) {
                    $str .= $product_data['order_product_details']->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                }
                $str .= ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }

        /*
          | -------------------------------------------------------
          | SEND EMAIL TO CUSTOMER WHEN ORDER IS COMPLETED MANUALLY
          | -------------------------------------------------------
         */
        if (isset($input_data['send_email']) && $input_data['send_email'] == 'true' && $order['customer']->email != "") {
            $customers = $order['customer'];
//            if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
            $order = Order::where('id', '=', $order_id)->with('all_order_products.order_product_details', 'delivery_location')->first();
            if (count($order) > 0) {
                if (count($order['delivery_location']) > 0) {
                    $delivery_location = $order['delivery_location']->area_name;
                } else {
                    $delivery_location = $order->other_location;
                }
                $mail_array = array(
                    'customer_name' => $customers->owner_name,
                    'expected_delivery_date' => $order->expected_delivery_date,
                    'created_date' => $order->updated_at,
                    'delivery_location' => $delivery_location,
                    'order_product' => $order['all_order_products']
                );
                $receipent = array();
                if (App::environment('development')) {
                    $receipent['email'] = Config::get('smsdata.emailData.email');
                    $receipent['name'] = Config::get('smsdata.emailData.name');
                } else {
                    $receipent['email'] = $customers->email;
                    $receipent['name'] = $customers->owner_name;
                }

                Mail::send('emails.complete_order_mail', ['order' => $mail_array], function($message) use($receipent) {
                    $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: Order Completed');
                });
            }
//            }
        }
        $update_order = $order->update([
            'order_status' => "Cancelled"
        ]);
        $cancel_order = OrderCancelled::create([
                    'order_id' => $order_id,
                    'order_type' => 'Order',
                    'reason_type' => $reason_type,
                    'reason' => $reason,
                    'cancelled_by' => Auth::id()
        ]);
        return array('message' => 'success');
    }

    /*
     * Functioanlity: Create New Delivery Order
     */

    public function create_delivery_order($id) {

        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer')->first();
        if (count($order) < 1) {
            return redirect('orders')->with('flash_message', 'Order does not exist.');
        }
        foreach ($order['all_order_products'] as $key => $value) {
            $delivery_order_products = AllOrderProducts::where('parent', '=', $value->id)->get();
            $total_delivery_order_product_quantity = $delivery_order_products->sum('quantity');
            $order['all_order_products'][$key]['pending_quantity'] = ($value->quantity - $total_delivery_order_product_quantity);
        }

        $units = Units::all();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return View::make('create_delivery_order', compact('order', 'delivery_location', 'units', 'customers'));
    }

    /*
     * Individual order pending details
     */

    public function pending_quantity_order($id) {
        $pending_orders = array();

        $delivery_orders = DeliveryOrder::where('order_id', $id)->get();
        $order_products = AllOrderProducts::where('order_id', $id)
                        ->where('order_type', 'order')->get();

        $pending_qty = 0;
        $total_qty = 0;
        $temp_array = array();

        foreach ($delivery_orders as $del_order) {
            $all_order_products = AllOrderProducts::where('order_id', $del_order->id)
                            ->where('from', '!=', '')->where('order_type', 'delivery_order')->get();

            foreach ($all_order_products as $products) {

                $temp = array();
                $temp['order_id'] = $id;
                $temp['from'] = $products['from'];
                $temp['product_id'] = $products['product_category_id'];
                $temp['quantity'] = $products['quantity'];
                $temp['total_quantity'] = $products['quantity'];
                $temp['unit'] = $products['unit_id'];
                $add_pendings = 0;
                if (count($temp_array) > 0) {
                    foreach ($temp_array as $key => $t) {
                        if ($t['from'] == $products->from && $t['product_id'] == $products->product_category_id && $products->unit_id == $t['unit']) {
                            $total_qty = $t['total_quantity'] + $products['quantity'];
                            $temp_array[$key]['total_pending_quantity'] = $total_qty;
                            $temp_array[$key]['total_quantity'] = $total_qty;
                            $add_pendings = 1;
                        }
                    }
                }
                if ($add_pendings == 0) {
                    array_push($temp_array, $temp);
                }
            }
        }
        $order_all_order_products = AllOrderProducts::where('order_id', $id)->where('order_type', 'order')->get();

        $total_quantity_ord = 0;
        $tot_pend_qty = 0;
        foreach ($order_all_order_products as $ordes_products) {
            $list_id = $ordes_products->id;
            $quantity = $ordes_products->quantity;
            foreach ($temp_array as $array1) {
                if ($array1['from'] == $list_id) {
                    $temp = array();
                    $temp['id'] = $id;
                    $temp['from'] = $list_id;
                    $temp['product_id'] = $array1['product_id'];
                    $temp['total_pending_quantity'] = ($quantity - $array1['total_quantity']);
                    $temp['unit'] = $array1['unit'];
                    $temp['order_quantity'] = $quantity;
                    $temp['total_quantity'] = $array1['quantity'];
                    $add_pendings = 0;
                    array_push($pending_orders, $temp);
                }
            }
        }
        return $pending_orders;
    }

    /*
     * Functioanlity: Store Delivery Order
     */

    public function store_delivery_order($id) {

        $input_data = Input::all();

        $order_details = Order::find($input_data['order_id']);
        if (!empty($order_details)) {
            if ($order_details->order_status == 'completed') {
                return Redirect::back()->with('flash_message', 'This delivry order is already saved. Please refresh the page');
            }
        }
        if (in_array($input_data['form_key'], $session_array)) {
            return Redirect::back()->with('flash_message', 'This delivry order is already saved. Please refresh the page');
        }

        if (Session::has('forms_delivery_order')) {
            $session_array = Session::get('forms_delivery_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This delivry order is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_delivery_order', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_delivery_order', $forms_array);
        }
        $validator = Validator::make($input_data, Order::$order_to_delivery_order_rules);
        if ($validator->passes()) {

            $user = Auth::user();
            $order = Order::where('id', '=', $id)->with('all_order_products')->first();
            $delivery_order = new DeliveryOrder();
            $delivery_order->order_id = $id;
            $delivery_order->customer_id = $input_data['customer_id'];
            $delivery_order->order_source = $order->order_source;
            $delivery_order->supplier_id = $order->supplier_id;
            $delivery_order->created_by = $user->id;
            $delivery_order->vat_percentage = $order->vat_percentage;
            $delivery_order->expected_delivery_date = $order->expected_delivery_date;
            $delivery_order->remarks = $input_data['remarks'];
            $delivery_order->vehicle_number = $input_data['vehicle_number'];
            $delivery_order->driver_contact_no = $input_data['driver_contact'];
            $delivery_order->order_status = 'Pending';
            if ($order->other_location == '') {
                $delivery_order->delivery_location_id = $order->delivery_location_id;
                $delivery_order->other_location = '';
                $delivery_order->location_difference = $order->location_difference;
            } else {
                $delivery_order->other_location = $order->other_location;
                $delivery_order->location_difference = $order->location_difference;
            }
            $delivery_order->save();

            $order_products = array();
            $order_id = DB::getPdo()->lastInsertId();
            $total_qty = 0;
            $present_shipping = 0;
            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] != "" && $product_data['order'] != '') {
                    $order_products = [
                        'order_id' => $order_id,
                        'order_type' => 'delivery_order',
                        'from' => $id,
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'quantity' => $product_data['present_shipping'],
                        'present_shipping' => $product_data['present_shipping'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark'],
                        'parent' => $product_data['order']
                    ];
                    $total_qty = $total_qty + $product_data['quantity'];
                    $present_shipping = $present_shipping + $product_data['present_shipping'];

                    $add_order_products = AllOrderProducts::create($order_products);
                }
                if ($product_data['name'] != "" && $product_data['order'] == '') {
                    $order_products = [
                        'order_id' => $order_id,
                        'order_type' => 'delivery_order',
                        'product_category_id' => $product_data['product_category_id'],
                        'unit_id' => $product_data['units'],
                        'quantity' => $product_data['present_shipping'],
                        'present_shipping' => $product_data['present_shipping'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark']
                    ];
                    $add_order_products = AllOrderProducts::create($order_products);
                }
            }
            //If pending quantity is Zero complete the order
            if ($present_shipping == $total_qty || $present_shipping >= $total_qty) {
                Order::where('id', '=', $id)->update(array('order_status' => 'completed'));
            }

            return redirect('orders')->with('flash_message', 'One order converted to Delivery order.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /*
     * First get all orders
     * 1 if delevery order is generated from order then only calculate
     * pending order from delivery order
     * else take order details in pending order
     * 2 if delivery order is generated then take those products only
     * which has there in order rest skip
     *
     *
     */

    function checkpending_quantity($allorders) {

        foreach ($allorders as $key => $order) {
            $order_quantity = 0;
            $delivery_order_quantity = 0;
            $delievry_order_details = DeliveryOrder::where('order_id', '=', $order->id)->first();
            if (!empty($delievry_order_details)) {
                $delivery_order_products = AllOrderProducts::where('order_id', '=', $delievry_order_details->id)->where('order_type', '=', 'delivery_order')->get();
            } else {
                $delivery_order_products = NULL;
            }

            if (count($delivery_order_products) > 0) {

                foreach ($delivery_order_products as $dopk => $dopv) {
                    $product_size = ProductSubCategory::find($dopv->product_category_id);
                    if ($dopv->unit_id == 1) {
                        $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity;
                    }
                    if ($dopv->unit_id == 2) {
                        $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * $product_size->weight;
                    }
                    if ($dopv->unit_id == 3) {
                        $delivery_order_quantity = $delivery_order_quantity + ($dopv->quantity / $product_size->standard_length ) * $product_size->weight;
                    }
                }
            }

            if (count($order['all_order_products']) > 0) {

                foreach ($order['all_order_products'] as $opk => $opv) {
                    $product_size = ProductSubCategory::find($opv->product_category_id);
                    if ($opv->unit_id == 1) {
                        $order_quantity = $order_quantity + $opv->quantity;
                    }
                    if ($opv->unit_id == 2) {
                        $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                    }
                    if ($opv->unit_id == 3) {
                        $order_quantity = $order_quantity + (($opv->quantity / $product_size->standard_length ) * $product_size->weight);
                    }
                }
            }
            if ($delivery_order_quantity >= $order_quantity) {
                $allorders[$key]['pending_quantity'] = 0;
            } else {
                $allorders[$key]['pending_quantity'] = ($order_quantity - $delivery_order_quantity);
            }
            $allorders[$key]['total_quantity'] = $order_quantity;
        }
        return $allorders;
    }

    /*
     * Functioanlity: Get size from product name
     */

    public function fetch_order_size() {
        $term = '%' . Input::get('term') . '%';
        $product = ProductSubCategory::where('size', 'like', $term)->get();

        if (count($product) > 0) {
            foreach ($product as $prod) {
                $data_array[] = [
                    'value' => $prod->size
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No size found',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

}
