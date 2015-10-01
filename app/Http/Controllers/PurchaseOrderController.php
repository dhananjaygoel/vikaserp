<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeliveryLocation;
use App\Units;
use App\Customer;
use App\Http\Requests\PurchaseOrderRequest;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\PurchaseOrder;
use App\PurchaseProducts;
use Auth;
use DB;
use App;
use Hash;
use Mail;
use Config;
use App\PurchaseOrderCanceled;
use App\PurchaseAdvise;
use DateTime;
use App\ProductSubCategory;
use Session;

class PurchaseOrderController extends Controller {

    public function __construct() {
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /*
     * Show purchase order list
     *
     */

    public function index() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $q = PurchaseOrder::query();

        if ((isset($_GET['pending_purchase_order'])) && $_GET['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $_GET['pending_purchase_order'])->get();
        }
        if ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }


        if (Auth::user()->role_id > 1) {
            if ((isset($_GET['purchase_order_filter'])) && $_GET['purchase_order_filter'] != '') {
                $q = $q->where('order_status', '=', $_GET['purchase_order_filter'])
                        ->where('is_view_all', '=', 0);
            } else {
                $q = $q->where('order_status', '=', 'pending')->where('is_view_all', '=', 0);
            }
        }

//        $session_sort_type_order = Session::get('order-sort-type');
//        $qstring_sort_type_order = $_GET['purchase_order_filter'];

        $session_sort_type_order = Session::get('order-sort-type');
        if (isset($_GET['purchase_order_filter']))
            $qstring_sort_type_order = $_GET['purchase_order_filter'];
        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            $qstring_sort_type_order = $qstring_sort_type_order;
        } else {
            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
                $qstring_sort_type_order = $session_sort_type_order;
            } else {
                $qstring_sort_type_order = "";
            }
        }

        if (Auth::user()->role_id < 1) {
            if ((isset($qstring_sort_type_order)) && $qstring_sort_type_order != '') {
                $q = $q->where('order_status', '=', $qstring_sort_type_order);
            } else {
                $q = $q->where('order_status', '=', 'pending');
            }
        }

        $purchase_orders = $q->orderBy('created_at', 'desc')
                ->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')
                ->Paginate(20);

        $purchase_orders = $this->quantity_calculation($purchase_orders);

        $all_customers = Customer::where('customer_status', '=', 'permanent')->orderBy('tally_name', 'ASC')->get();
        $purchase_orders->setPath('purchase_orders');

        return view('purchase_order', compact('purchase_orders', 'all_customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::where('customer_status', '=', 'permanent')->orderBy('tally_name', 'ASC')->get();
        return view('add_purchase_order', compact('units', 'delivery_locations', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseOrderRequest $request) {
        $input_data = Input::all();
        $rules = array(
            'purchase_order_location' => 'required',
        );
        $validator = Validator::make($input_data, $rules);

        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }

        if ($i == $j) {
            return Redirect::back()->withInput()->with('flash_message', 'Please insert product details');
        }

        if ($input_data['supplier_status'] == "new_supplier") {
            $validator = Validator::make($input_data, Customer::$new_supplier_inquiry_rules);
            if ($validator->passes()) {
                $customers = new Customer();
                $customers->owner_name = $input_data['supplier_name'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
                $customers->save();
                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['supplier_status'] == "existing_supplier") {

            $validate = Validator::make($input_data, array('autocomplete_supplier_id' => 'required'));

            if ($validate->passes()) {
                $customer_id = $input_data['autocomplete_supplier_id'];
            } else {
                $error_msg = $validate->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validate);
            }
        }

        $expected_delivery_date = explode('-', $input_data['expected_delivery_date']);
        $expected_delivery_date = $expected_delivery_date[2] . '-' . $expected_delivery_date[0] . '-' . $expected_delivery_date[1];
        $expected_delivery_date = date("Y-m-d", strtotime($expected_delivery_date));

        $add_purchase_order_array = [
            'supplier_id' => $customer_id,
            'created_by' => Auth::id(),
            'order_for' => $input_data['order_for'],
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $expected_delivery_date,
            'remarks' => $input_data['purchase_order_remark'],
            'order_status' => "pending",
        ];

        if ($input_data['purchase_order_location'] > 0) {
            $add_purchase_order_array['delivery_location_id'] = $input_data['purchase_order_location'];
        } else {
            $add_purchase_order_array['other_location'] = $input_data['other_location_name'];
            $add_purchase_order_array['other_location_difference'] = $input_data['other_location_difference'];
        }

        /*
         * ------------------- -----------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE ORDER
         * -------------------------------------------
         */

        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour purchase order has been logged for following ";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ', ';
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }

                $str .= " meterial will be desp by " . date("jS F, Y", strtotime($expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }

        $add_purchase_order = PurchaseOrder::create($add_purchase_order_array);
        $purchase_order_id = DB::getPdo()->lastInsertId();
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $add_delivery_location = PurchaseOrder::where('id', $purchase_order_id)->update([
                'delivery_location_id' => 0,
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference'],
            ]);
        } else {
            $add_delivery_location = PurchaseOrder::where('id', $purchase_order_id)->update([
                'delivery_location_id' => $input_data['purchase_order_location'],
                'other_location' => '',
                'other_location_difference' => '',
            ]);
        }
        $purchase_order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $purchase_order_products = [
                    'purchase_order_id' => $purchase_order_id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_purchase_order_products = PurchaseProducts::create($purchase_order_products);
            }
        }

        /*
          | ------------------------------------------------------
          | SEND EMAIL TO SUPPLIER ON CREATE OF NEW PURCHASE ORDER
          | ------------------------------------------------------
         */
        if ($input_data['supplier_status'] != "new_supplier") {

            if (isset($input_data['send_email'])) {
                $customers = Customer::find($customer_id);
                if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
                    $purchase_order = PurchaseOrder::where('id', '=', $purchase_order_id)->with('purchase_products.purchase_product_details', 'delivery_location')->first();

                    if (count($purchase_order) > 0) {
                        if (count($purchase_order['delivery_location']) > 0) {
                            $delivery_location = $purchase_order['delivery_location']->area_name;
                        } else {
                            $delivery_location = $purchase_order->other_location;
                        }
                        $mail_array = array(
                            'customer_name' => $customers->owner_name,
                            'expected_delivery_date' => $purchase_order->expected_delivery_date,
                            'created_date' => $purchase_order->updated_at,
                            'delivery_location' => $delivery_location,
                            'order_product' => $purchase_order['purchase_products'],
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

                        Mail::send('emails.new_purchase_order_mail', ['purchase_order' => $mail_array], function($message) use($receipent) {
                            $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: New Purchase Order');
                        });
                    }
                }
            }
        }
        return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $purchase_orders = PurchaseOrder::where('id', '=', $id)->with('purchase_products.unit', 'delivery_location', 'purchase_products.purchase_product_details', 'customer')->first();
        if (count($purchase_orders) < 1) {
            return redirect('purchase_orders')->with('flash_message', 'Purchase order not found');
        }

        return view('purchase_order_details', compact('purchase_orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $purchase_order = PurchaseOrder::where('id', '=', $id)->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')->first();
        if (count($purchase_order) < 1) {
            return redirect('purchase_orders')->with('flash_message', 'Purchase order not found');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::where('customer_status', '=', 'permanent')->get();
        return view('edit_purchase_order', compact('purchase_order', 'delivery_locations', 'units', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $input_data = Input::all();

        $customer_id = 0;
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }

        if ($i == $j) {
            return Redirect::back()->with('flash_message', 'Please insert product details');
        }
        $customers = Customer::find($input_data['supplier_id']);

        if ($input_data['supplier_status'] == "new_supplier") {
            $validator = Validator::make($input_data, Customer::$new_supplier_inquiry_rules);
            if ($validator->passes()) {



                if (isset($input_data['pending_user_id']) && $input_data['pending_user_id'] > 0) {

                    $pending_cust = array(
                        'owner_name' => $input_data['supplier_name'],
                        'phone_number1' => $input_data['mobile_number'],
                        'credit_period' => $input_data['credit_period'],
                        'customer_status' => 'pending'
                    );

                    Customer::where('id', $input_data['pending_user_id'])
                            ->update($pending_cust);

                    $customer_id = $input_data['pending_user_id'];
                } else {

                    $customers = new Customer();
                    $customers->owner_name = $input_data['supplier_name'];
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
        } elseif ($input_data['supplier_status'] == "existing_supplier") {

            $validator = Validator::make($input_data, Customer::$existing_supplier_inquiry_rules);
            if ($validator->passes()) {

                $customer_id = $input_data['autocomplete_supplier_id'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }

        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_delivery_date']);
        $date = date("Y-m-d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);


        $purchase_order = PurchaseOrder::find($id);
        if ($input_data['vat_status'] == 'include_vat') {
            $vat_percentage = '';
        } else {
            $vat_percentage = $input_data['vat_percentage'];
        }

        $add_purchase_order_array = [
            'is_view_all' => $input_data['viewable_by'],
            'supplier_id' => $customer_id,
            'order_for' => $input_data['order_for'],
            'created_by' => Auth::id(),
            'vat_percentage' => $vat_percentage,
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['purchase_order_remark'],
            'order_status' => "pending"
        ];
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
                $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour purchase order has been edited and changed as follows ";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ', ';
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }
                $str .= " meterial will be desp by " . date("jS F, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
        $update_purchase_order = $purchase_order->update($add_purchase_order_array);

        if (isset($input_data['purchase_order_location']) && ($input_data['purchase_order_location'] == -1)) {

            $purchase_order->update([
                'delivery_location_id' => 0,
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference'],
            ]);
        } else {
            $purchase_order->update([
                'delivery_location_id' => $input_data['purchase_order_location'],
                'other_location' => '',
                'other_location_difference' => '',
            ]);
        }

        $purchase_order_products = array();
        $delete_old_purchase_products = PurchaseProducts::where('purchase_order_id', '=', $id)->delete();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $purchase_order_products = [
                    'purchase_order_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_purchase_order_products = PurchaseProducts::create($purchase_order_products);
            }
        }
        /*
          | ------------------------------------------------------
          | SEND EMAIL TO SUPPLIER ON UPDATE OF NEW PURCHASE ORDER
          | ------------------------------------------------------
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);
            if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
                $purchase_order = PurchaseOrder::where('id', '=', $id)->with('purchase_products.purchase_product_details', 'delivery_location')->first();
                if (count($purchase_order) > 0) {
                    if (count($purchase_order['delivery_location']) > 0) {
                        $delivery_location = $purchase_order['delivery_location']->area_name;
                    } else {
                        $delivery_location = $purchase_order->other_location;
                    }
                    $mail_array = array(
                        'customer_name' => $customers->owner_name,
                        'expected_delivery_date' => $purchase_order->expected_delivery_date,
                        'created_date' => $purchase_order->updated_at,
                        'delivery_location' => $delivery_location,
                        'order_product' => $purchase_order['purchase_products'],
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

                    Mail::send('emails.new_purchase_order_mail', ['purchase_order' => $mail_array], function($message) use($receipent) {
                        $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: Purchase Order Updated');
                    });
                }
            }
        }
        return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

        $order_sort_type = Input::get('order_sort_type');

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_order = PurchaseOrder::find($id)->delete();
            $delete_purchase_products = PurchaseProducts::where('purchase_order_id', '=', $id)->delete();

            Session::put('order-sort-type', $order_sort_type);
            return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully deleted.');
        } else {
            return redirect('purchase_orders')->with('flash_message', 'Please enter a correct password.');
        }
    }

    public function create_purchase_advice($order_id) {

        $purchase_orders = PurchaseOrder::where('id', '=', $order_id)->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer', 'purchase_advice.purchase_products')->first();
        foreach ($purchase_orders['purchase_products'] as $key => $value) {
            $purchase_advise_products = PurchaseProducts::where('parent', '=', $value->id)->get();
            $total_advise_product_quantity = $purchase_advise_products->sum('quantity');
            $purchase_orders['purchase_products'][$key]['pending_quantity'] = ($value->quantity - $total_advise_product_quantity);
        }
        if (count($purchase_orders) < 1) {
            return redirect('purchase_orders')->with('flash_message', 'Purchase order not found');
        }
        return view('create_purchase_advice', compact('purchase_orders'));
    }

    /*
     * complete the purchase order mannually
     */

    public function manual_complete() {
        $input_data = Input::all();
        $purchase_order_id = $input_data['purchase_order_id'];
        $purchase_order = PurchaseOrder::where('id', '=', $purchase_order_id)->with('purchase_products.purchase_product_details', 'purchase_products.unit', 'customer')->first();

        /*
          | ------------------- -----------------------------------------
          | SEND SMS TO CUSTOMER FOR MANUALLY COMPLETING A PURCHASE ORDER
          | -------------------------------------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::where('id', '=', $purchase_order['customer']->id)->with('manager')->first();
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear '" . $customer->owner_name . "'\n your purchase order has been completed for following ";
                foreach ($purchase_order['purchase_products'] as $product_data) {
                    $str .= $product_data['purchase_product_details']->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ', ';
                }
                $str .= ". Vikas Associates, 9673000068";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }

        /*
          | ----------------------------------------------------------------
          | SEND EMAIL TO CUSTOMER WHEN PURCHASE ORDER IS COMPLETED MANUALLY
          | ----------------------------------------------------------------
         */
        if (isset($input_data['send_email']) && $input_data['send_email'] == 'true' && $purchase_order['customer']->email != "") {
            $customers = $purchase_order['customer'];
            $purchase_order = PurchaseOrder::where('id', '=', $purchase_order_id)->with('purchase_products.purchase_product_details', 'purchase_products.unit', 'customer')->first();
            if (count($purchase_order) > 0) {
                if (count($purchase_order['delivery_location']) > 0) {
                    $delivery_location = $purchase_order['delivery_location']->area_name;
                } else {
                    $delivery_location = $purchase_order->other_location;
                }
                $mail_array = array(
                    'customer_name' => $customers->owner_name,
                    'expected_delivery_date' => $purchase_order->expected_delivery_date,
                    'created_date' => $purchase_order->updated_at,
                    'delivery_location' => $delivery_location,
                    'order_product' => $purchase_order['purchase_products']
                );

                $receipent = array();
                if (App::environment('development')) {
                    $receipent['email'] = Config::get('smsdata.emailData.email');
                    $receipent['name'] = Config::get('smsdata.emailData.name');
                } else {
                    $receipent['email'] = $customers->email;
                    $receipent['name'] = $customers->owner_name;
                }

                Mail::send('emails.complete_purchase_order_mail', ['order' => $mail_array], function($message) use($receipent) {
                    $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: Order Completed');
                });
            }
        }

        $purchase_order_canceled = PurchaseOrderCanceled::create([
                    'purchase_order_id' => $input_data['purchase_order_id'],
                    'purchase_type' => $input_data['module_name'],
                    'reason' => $input_data['reason']
        ]);
        $change_status = PurchaseOrder::where('id', '=', $input_data['purchase_order_id'])
                ->update(array(
            'order_status' => 'canceled'
        ));

        return redirect('purchase_orders')->with('flash_message', 'Successfully completed purchase order');
    }

    public function purchase_order_report() {
        $q = PurchaseOrder::query();
        $q->where('order_status', '=', 'pending')
                ->orderBy('created_at', 'desc')
                ->with('customer', 'delivery_location', 'user', 'purchase_products');
        if ((isset($_GET['pending_purchase_order'])) && $_GET['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $_GET['pending_purchase_order'])->get();
        }
        if ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }
        if (Auth::user()->role_id > 1) {
            $q->where('is_view_all', '=', 0);
        }
        $purchase_orders = $q->Paginate(20);
        $purchase_orders->setPath('purchase_order_report');
        $all_customers = Customer::all();
        return view('purchase_order_report', compact('purchase_orders', 'all_customers'));
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

    function quantity_calculation($purchase_orders) {

        foreach ($purchase_orders as $key => $order) {
            $purchase_order_quantity = 0;
            $purchase_order_advise_quantity = 0;
            $purchase_order_advise_products = PurchaseProducts::where('from', '=', $order->id)->get();

            if (count($purchase_order_advise_products) > 0) {
                foreach ($purchase_order_advise_products as $poapk => $poapv) {
                    $product_size = ProductSubCategory::find($poapv->product_category_id);
                    if ($poapv->unit_id == 1) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity;
                    }
                    if ($poapv->unit_id == 2) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity * $product_size->weight;
                    }
                    if ($poapv->unit_id == 3) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + ($poapv->quantity / $product_size->standard_length ) * $product_size->weight;
                    }
                }
            }

            if (count($order['purchase_products']) > 0) {
                foreach ($order['purchase_products'] as $popk => $popv) {
                    $product_size = ProductSubCategory::find($popv->product_category_id);
                    if ($popv->unit_id == 1) {
                        $purchase_order_quantity = $purchase_order_quantity + $popv->quantity;
                    }
                    if ($popv->unit_id == 2) {
                        $purchase_order_quantity = $purchase_order_quantity + ($popv->quantity * $product_size->weight);
                    }
                    if ($popv->unit_id == 3) {
                        $purchase_order_quantity = $purchase_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                    }
                }
            }

            if ($purchase_order_advise_quantity >= $purchase_order_quantity) {
                $purchase_orders[$key]['pending_quantity'] = 0;
            } else {
                $purchase_orders[$key]['pending_quantity'] = ($purchase_order_quantity - $purchase_order_advise_quantity);
            }
            $purchase_orders[$key]['total_quantity'] = $purchase_order_quantity;
        }
        return $purchase_orders;
    }

}
