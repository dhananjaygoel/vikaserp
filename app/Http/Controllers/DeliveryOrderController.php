<?php

namespace App\Http\Controllers;

use App\Exports\DOExport;
use App\Labour;
use View;
use Carbon;
use App\LoadedBy;
use App\DeliveryChallanLoadedBy;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryLocation;
use App\LoadTrucks;
use App\LoadDelboy;
use App\LoadLabour;
use App\AllOrderProducts;
use App\Customer;
use App\Units;
use Input;
use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;
use Config;
use App\ProductSubCategory;
use App\DeliveryChallan;
use App\CustomerProductDifference;
use App\ProductCategory;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use App\Repositories\DropboxStorageRepository;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Storage;
use Twilio\Rest\Client;
use App\User;

class DeliveryOrderController extends Controller {
    /*
     * sms construction
     */

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        define('TWILIO_SID', Config::get('smsdata.twilio_sid'));
        define('TWILIO_TOKEN', Config::get('smsdata.twilio_token'));
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        // echo 'sadfjs';
        // exit;
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        gc_disable();
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4 && Auth::user()->role_id != 8 && Auth::user()->role_id != 9) {
            return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
        }
        $session_sort_type_order = Session::get('order-sort-type');
        if (Input::get('order_status') != "") {
            $qstring_sort_type_order = Input::get('order_status');
        } elseif (Input::get('delivery_order_status') != "") {
            $qstring_sort_type_order = Input::get('delivery_order_status');
        }
        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            $qstring_sort_type_order = $qstring_sort_type_order;
        } else {
            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
                $qstring_sort_type_order = $session_sort_type_order;
            } else {
                $qstring_sort_type_order = "";
            }
        }
        $delivery_data = 0;
        $q = DeliveryOrder::query();
        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            if ($qstring_sort_type_order == 'Inprocess') {
                $q->where('order_status', 'pending');
            } elseif ($qstring_sort_type_order == 'Delivered') {
                $q->where('order_status', 'completed');
            }
        } else {
            $q->where('order_status', 'pending');
        }
        if (Auth::user()->role_id == 9){
             $q->where('del_boy', Auth::user()->id);
        }
         if (Auth::user()->role_id == 8){
             $q->where('del_supervisor', Auth::user()->id);
        }
        $search_dates = [];
        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {

            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');

            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $q->where('created_at', 'like', $date1 . '%');

                 // $q->where('updated_at', '<=', $date1);
            } else {
                $q->where('created_at', '>=', $date1);
                $q->where('created_at', '<=', $date2 . ' 23:59:59');
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        }

        if (Input::has('flag') && Input::get('flag') == 'true') {
            $q->orderBy('flaged', 'desc')->orderBy('created_at', 'desc');
        } else {
            $q->orderBy('created_at', 'desc');
        }
        // print_r(date("Y-m-d h:i:s a", time()));
    //    $delivery_data = $q->with('track_do_product', 'track_order_product', 'delivery_product', 'order_details', 'customer', 'location')->paginate(20);
        $delivery_data = $q
                ->whereHas('delivery_product',function($query){
                    $query->where('present_shipping','>', '0');
                })->with('track_do_product', 'track_order_product', 'delivery_product', 'order_details', 'customer', 'location')
                ->paginate(20);

        // print(date("Y-m-d h:i:s a", time()));
        // print($delivery_data['track_do_product']);
        // dd($delivery_data->toArray());

        $delivery_data = $this->checkpending_quantity($delivery_data);
        //$delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $delivery_data->setPath('delivery_order');

        $parameters = parse_url($request->fullUrl());
        $parameters = isset($parameters['query']) ? $parameters['query'] : '';
        Session::put('parameters', $parameters);
        return view('delivery_order', compact('delivery_data', 'search_dates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return Redirect::to('delivery_order');
        $units = Units::all();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return view('add_delivery_order', compact('units', 'delivery_locations', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        $input_data = Input::all();
        if (Session::has('forms_delivery_order')) {
            $session_array = Session::get('forms_delivery_order');
            if (count((array)$session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('success') == 'Delivery order details successfully added.'){
                        return redirect('delivery_order')->with('success', 'Delivery order details successfully added.');
                    }else{
                        return Redirect::back()->with('validation_message', 'This delivery order is already saved. Please refresh the page');
                    }
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
        $i = 0;
        $customer_id = 0;
        $j = count((array)$input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->withInput()->with('validation_message', 'Please enter at least one product details');
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
                $customers->save();
                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
//                return Redirect::back()->withErrors($validator)->withInput()->with('flash_data', $input_data);
                return Redirect::back()->withErrors($validator)->withInput();
            }
        } elseif ($input_data['customer_status'] == "exist_customer") {
            $validator = Validator::make($input_data, array('autocomplete_customer_id' => 'required'));
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
//        $vat_price = 0;
//        if ($input_data['status1'] == 'include_vat') {
//            $vat_price = '';
//        }
        if (isset($input_data['vat_price'])) {
            $vat_price = $input_data['vat_price'];
        } else {
            $vat_price = 0;
        }
        $delivery_order = new DeliveryOrder();
        $delivery_order->order_id = 0;
        $delivery_order->order_source = 'warehouse';
        $delivery_order->customer_id = $customer_id;
        $delivery_order->created_by = Auth::id();
        $delivery_order->vat_percentage = $vat_price;
        $delivery_order->estimate_price = 0;
        $delivery_order->expected_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
        $delivery_order->remarks = $input_data['order_remark'];
        $delivery_order->vehicle_number = $input_data['vehicle_number'];
        $delivery_order->driver_contact_no = $input_data['driver_contact'];
        $delivery_order->order_status = "Pending";
        if (isset($input_data['add_order_location']) && ($input_data['add_order_location'] == "other")) {
            $delivery_order->other_location = $input_data['other_location_name'];
            $delivery_order->location_difference = $input_data['location_difference'];
        } else {
            $delivery_order->delivery_location_id = $input_data['add_order_location'];
            $delivery_order->location_difference = $input_data['location_difference'];
        }
        $delivery_order->save();
        $delivery_order_id = $delivery_order->id;
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $order_products = [
                    'order_id' => $delivery_order_id,
                    'order_type' => 'delivery_order',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark']
                ];
                AllOrderProducts::create($order_products);
            }
        }
        //         update sync table
        $tables = ['delivery_order', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        return redirect('delivery_order')->with('success', 'Delivery order details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')->find($id);
        if (count((array)$delivery_data) < 1) {
            return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
        }

        $order_data = Order::with('all_order_products')->find($delivery_data->order_id);

        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::all();
        return view('view_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers', 'order_data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id = "") {
        if (Auth::user()->role_id == 5 | $id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }


        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->find($id);
        if (count((array)$delivery_data) < 1) {
            return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
        }


        $customers = Customer::all();
        $pending_orders = $this->pending_quantity_order($id);
        // echo '<pre>';
        // print_r($pending_orders);
        // exit;
        return view('edit_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers', 'pending_orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {


        $input_data = Input::all();
        $whatsapp_error = '';
        $sms_flag = 1;
        if (Session::has('forms_edit_delivery_order')) {
            $session_array = Session::get('forms_edit_delivery_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('success') == 'Delivery order details successfully updated.'){
                        return redirect('delivery_order')->with('success', 'Delivery order details successfully updated.');
                    }else{
                        return Redirect::back()->with('validation_message', 'This delivery order is already updated. Please refresh the page');
                    }
                    //   $parameter = Session::get('parameters');
                    //  $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';
                    // return redirect('delivery_order' . $parameters)->with('success', 'Delivery order details successfully updated.');

                } 
                // else {
                //     array_push($session_array, $input_data['form_key']);
                //     Session::put('forms_edit_delivery_order', $session_array);
                // }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_delivery_order', $forms_array);
        }
        $customer_id = 0;
        if (isset($input_data['customer_status']) && $input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_edit_inquiry_rules);
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
            }
             else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif (isset($input_data['customer_status']) && $input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, array('autocomplete_customer_id' => 'required'));
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $vat_price = 0;
//        if ($input_data['status1'] == 'include_vat') {
//            $vat_price = '';
//        }
//        if ($input_data['status1'] == 'exclude_vat') {
//            $vat_price = $input_data['vat_price'];
//        }
        if (isset($input_data['vat_price'])) {
            $vat_price = $input_data['vat_price'];
        } else {
            $vat_price = 0;
        }

        $delivery_location = 0;
        $location = "";
        $other_location_difference = "";
        if ($input_data['add_order_location'] == 'other') {
            $delivery_location = 0;
            $location = $input_data['location'];
            $location_difference = $input_data['location_difference'];
        } else {
            $delivery_location = $input_data['add_order_location'];
            $location = '';
            $location_difference = $input_data['location_difference'];
        }
        DeliveryOrder::where('id', $id)->update(array(
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $delivery_location,
            'other_location' => $location,
            'location_difference' => $location_difference,
            'vat_percentage' => $vat_price,
            'estimate_price' => 0,
            'estimated_delivery_date' => date_format(date_create(date("Y-m-d")), 'Y-m-d'),
            'expected_delivery_date' => date_format(date_create(date("Y-m-d")), 'Y-m-d'),
            'remarks' => isset($input_data['order_remark']) ? $input_data['order_remark'] : '',
            'vehicle_number' => $input_data['vehicle_number'],
            'driver_contact_no' => $input_data['driver_contact'],
            'discount_type' => $input_data['discount_type'],
            'discount_unit' => $input_data['discount_unit'],
            'discount' => $input_data['discount'],
        ));
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            $order_id = $input_data['order_id'];
            if ($product_data['order'] != '' || $product_data['id'] != '') {
                $order_products = [
                    'order_id' => $id,
                    'order_type' => 'delivery_order',
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => isset($product_data['units'])? $product_data['units'] :'1' ,
                   'from'=> $order_id,
                     'quantity' => isset($product_data['quantity'])? $product_data['quantity']:'50.00',
                     'length' => isset($product_data['length'])? $product_data['length']:'',
                     'present_shipping' => isset($product_data['present_shipping'])? $product_data['present_shipping']:'50.00' ,
                     'price' => isset($product_data['price']) ?$product_data['price'] :'' ,
                     'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark'],
                ];
                $add_order_products = AllOrderProducts::where('id', '=', $product_data['id'])->update($order_products);
            }
            else if ($product_data['name'] != "" && $product_data['order'] == '') {
                $order_products = [
                    'order_id' => $id,
                    'order_type' => 'delivery_order',
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => isset($product_data['units'])? $product_data['units'] :'1',
                   'from'=> $order_id,
                    'quantity' => isset($product_data['quantity'])? $product_data['quantity']:'50.00',
                     'length' => isset($product_data['length'])? $product_data['length']:'',
                     'present_shipping' => isset($product_data['present_shipping'])? $product_data['present_shipping']:'50.00' ,
                     'price' => isset($product_data['price']) ?$product_data['price'] :'' ,
                     'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark'],
                ];
                $add_order_products = AllOrderProducts::create($order_products);
                $do = DeliveryOrder::find($id);
                $do->final_truck_weight = 0;
                $do->save();
            }
            /* check for vat/gst items */
            if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') {
                $sms_flag = 1;
            }
            /**/
        }
        $delivery_order = DeliveryOrder::find($id);
        $delivery_order_prod = AllOrderProducts::where('order_type', '=', 'delivery_order')->where('order_id', '=', $id)->first();
        $delivery_order->updated_at = $delivery_order_prod->updated_at;
        $delivery_order->save();

        /* inventory code */
        $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'delivery_order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }
        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
          |------------------- -----------------------
          | SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
          | -------------------------------------------
         */
//        $input_data = $delivery_order;

        $delivery_order = DeliveryOrder::with('delivery_product')->find($delivery_order->id);

        $total_quantity = 0;
        $customer_id = $delivery_order->customer_id;
        $customer = Customer::with('manager')->find($customer_id);
        $cust_count = Customer::with('manager')->where('id',$customer_id)->count();
        if ($sms_flag == 1) {
            if ($cust_count > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour DO has been edited as follows\n";
                foreach ($delivery_order['delivery_product'] as $product_data) {
                    $prod = AllOrderProducts::with('product_sub_category')->find($product_data->id);


                    $str .= $prod['product_sub_category']->alias_name . ' - ' . $prod->quantity . ' - ' . $prod['price'] . ",\n";
                    $total_quantity = (float)$total_quantity + (float)$product_data->quantity;
                }

                $str .= "Vehicle No. " . (!empty($delivery_order->vehicle_number) ? $delivery_order->vehicle_number : 'N/A') . ", Drv No. " . (!empty($delivery_order->driver_contact_no) ? $delivery_order->driver_contact_no : 'N/A') . ". \nVIKAS ASSOCIATES";


                if (App::environment('local')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true && isset($input_data['send_msg']) && $input_data['send_msg'] == "yes") {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
                // whatsapp code starts here
                if(isset($input_data['send_whatsapp']) && $input_data['send_whatsapp'] == "yes"){
                    $twilio = new Client(TWILIO_SID, TWILIO_TOKEN);
                    try{
                        $message = $twilio->messages
                        ->create("whatsapp:".$phone_number,
                            [
                                "body" => $str,
                                "from" => "whatsapp:+14155238886"
                            ]
                        );
                    }catch(\Exception $e){
                        $whatsapp_error = ':: Whatsapp Error: Invalid Number';
                    }
                }
                // whatsapp testing code endse here
            }
            if (count((array)$customer['manager']) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited DO for " . $customer->owner_name . " as follows\n";
                foreach ($delivery_order['delivery_product'] as $product_data) {
                    $prod = AllOrderProducts::with('product_sub_category')->find($product_data->id);


                    $str .= $prod['product_sub_category']->alias_name . ' - ' . $prod->quantity . ' - ' . $prod['price'] . ",\n";
                    $total_quantity = (float)$total_quantity + (float)$product_data->quantity;
                }

                $str .= "Vehicle No. " . (!empty($delivery_order->vehicle_number) ? $delivery_order->vehicle_number : 'N/A') . ", Drv No. " . (!empty($delivery_order->driver_contact_no) ? $delivery_order->driver_contact_no : 'N/A') . ". \nVIKAS ASSOCIATES";


                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true && isset($input_data['send_msg']) && $input_data['send_msg'] == "yes") {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }

        //         update sync table
        $tables = ['delivery_order', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        $parameter = Session::get('parameters');
        $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';

        DeliveryOrder::where('id',$id)->update(['is_editable'=>1]);
        return redirect('delivery_order' . $parameters)->with('success', 'Delivery order details successfully updated'.$whatsapp_error);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('delivery_order')->with('error', 'You do not have permission.');
        }

        $data = Input::all();
        if (isset($data['password']) && ($data['password'] != '')) {
            $password = $data['password'];
        } else {
            return Redirect::to('delivery_order')->with('error', 'Please enter password.');
        }

//        $inputData = Input::get('formData');
//        parse_str($inputData, $formFields);
//        $password = $formFields['password'];
//        $order_sort_type = $formFields['order_sort_type'];
        if (Hash::check($password, Auth::user()->password)) {
            /* inventory code */
            $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'delivery_order')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }

            DeliveryOrder::find($id)->delete();
            AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_order')->delete();

            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);
//            Session::put('order-sort-type', $order_sort_type);
//            return array('message' => 'success');
            //         update sync table
            $tables = ['delivery_order', 'all_order_products'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */
            $parameter = Session::get('parameters');
            $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';

            return Redirect::to('delivery_order' . $parameters)->with('success', 'Record deleted successfully.');
        } else {
//            return array('message' => 'failed');
            return Redirect::to('delivery_order')->with('error', 'Please enter correct password.');
        }
    }

    /*
     * Load the list view of pending delivery order.
     */

    public function pending_delivery_order() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $filteron = "";
        $filterby = "";
        $filteron = Input::get('filteron');
        $filterby = Input::get('filterby');
        $delivery_data = 0;
        if (Input::get('order_status')) {
            if (Input::get('order_status') == 'Inprocess') {
                if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
                    $delivery_data = DeliveryOrder::where('order_status', 'pending')->orderby($filteron, $filterby)->with('user', 'customer')->paginate(20);
                } else {
                    $delivery_data = DeliveryOrder::with('user', 'customer')->where('order_status', 'pending')->paginate(20);
                }
            } elseif (Input::get('order_status') == 'Delivered') {
                if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
                    $delivery_data = DeliveryOrder::where('order_status', 'completed')->orderby($filteron, $filterby)->with('user', 'customer')->paginate(20);
                } else {
                    $delivery_data = DeliveryOrder::with('user', 'customer')->where('order_status', 'completed')->paginate(20);
                }
            }
        } else {
            if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
                $delivery_data = DeliveryOrder::where('order_status', 'pending')->orderby($filteron, $filterby)->with('user', 'customer')->paginate(20);
            } else {
                $delivery_data = DeliveryOrder::with('user', 'customer')->where('order_status', 'pending')->paginate(20);
            }
        }
        $delivery_data->setPath('pending_delivery_order');
        return view('pending_delivery_order', compact('delivery_data'));
    }

    public function check_product_type($delivery_data) {
        $produc_type['pipe'] = "0";
        $produc_type['structure'] = "0";
        $produc_type['sheet'] = "0";

        foreach ($delivery_data['delivery_product'] as $key => $value) {
            if (isset($value['order_product_details']['product_category']->product_type_id) && $value['order_product_details']['product_category']->product_type_id == 1) {
                $produc_type['pipe'] = "1";
            }
            if (isset($value['order_product_details']['product_category']->product_type_id) && $value['order_product_details']['product_category']->product_type_id == 2) {
                $produc_type['structure'] = "1";
            }
            if (isset($value['order_product_details']['product_category']->product_type_id) && $value['order_product_details']['product_category']->product_type_id == 3) {
                $produc_type['sheet'] = "1";
            }
        }
        return $produc_type;
    }

    /*
     * displey the create delivery challan form
     */

    public function create_delivery_challan($id = "") {

        if (Auth::user()->role_id == 5 | $id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->find($id);


        if (count((array)$delivery_data) < 1) {
            return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
        }
        if (empty($delivery_data['customer'])) {
            return redirect('delivery_order')->with('error', 'Inavalid delivery order- User not present.');
        }
        $produc_type = $this->check_product_type($delivery_data);
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::all();
        $labours = Labour::where('type', '<>', 'purchase')->get();
        $loaders = LoadedBy::where('type', '<>', 'purchase')->get();
        $delivery_chellan = DeliveryChallan::where('id', '=', $id)->first();
        return view('create_delivery_challan', compact('delivery_data', 'units', 'delivery_locations', 'customers', 'labours', 'loaders', 'produc_type','delivery_chellan'));
    }
      /*
     * displey the create Load truck form
     */

    public function create_load_truck($id = "") {

        if (Auth::user()->role_id == 5 | $id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->find($id);


        if (count((array)$delivery_data) < 1) {
            return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
        }
        if (empty($delivery_data['customer'])) {
            return redirect('delivery_order')->with('error', 'Inavalid delivery order- User not present.');
        }
        $produc_type = $this->check_product_type($delivery_data);
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::all();
        $load_labours = LoadLabour::where('delivery_id',$id)->get();
        // $labours = Labour::where('type', '<>', 'sale')->get();
        $labours = Labour::all();
        $loaders = LoadedBy::where('type', '<>', 'sale')->get();
        $truckdetails = LoadTrucks::where('deliver_id', '=', $id)->get();
        $delboys = LoadDelboy::with('users')->where('delivery_id', '=', $id)->get();
        $truck_load_prodcut_id = LoadTrucks::where('deliver_id', '=', $id)
                         ->where('userid', '=', Auth::id())->get();
        return view('create_load_truck', compact('delivery_data', 'units', 'delivery_locations', 'customers', 'labours', 'loaders','load_labours', 'produc_type','truckdetails','delboys','truck_load_prodcut_id'));
    }

    /*
     * save create delivery challan form details for the challan
     */

    public function store_delivery_challan_vat_wise($input_data, $id = "", $refid = NULL) {

        $delivery_challan = new DeliveryChallan();
        $delivery_challan->order_id = $input_data['order_id'];
        $delivery_challan->delivery_order_id = $id;
        $delivery_challan->customer_id = $input_data['customer_id'];
        $delivery_challan->created_by = Auth::id();
        if (isset($input_data['billno'])) {
            $delivery_challan->bill_number = $input_data['billno'];
        }
        $delivery_challan->discount = $input_data['discount'];
        $delivery_challan->freight = $input_data['freight'];
        $delivery_challan->loading_charge = $input_data['loading'];
        $delivery_challan->round_off = $input_data['round_off'];


        //$delivery_challan->loaded_by = $input_data['loadedby'];
        //$delivery_challan->labours = $input_data['labour'];

        if (isset($input_data['vat_percentage'])) {
            $delivery_challan->vat_percentage = $input_data['vat_percentage'];
        } else {
            $delivery_challan->vat_percentage = 0;
        }

        if (isset($input_data['loading_vat_percentage'])) {
            $delivery_challan->loading_vat_percentage = $input_data['loading_vat_percentage'];
        } else {
            $delivery_challan->loading_vat_percentage = 0;
        }
        if (isset($input_data['freight_vat_percentage'])) {
            $delivery_challan->freight_vat_percentage = $input_data['freight_vat_percentage'];
        } else {
            $delivery_challan->freight_vat_percentage = 0;
        }
        if (isset($input_data['discount_vat_percentage'])) {
            $delivery_challan->discount_vat_percentage = $input_data['discount_vat_percentage'];
        } else {
            $delivery_challan->discount_vat_percentage = 0;
        }
        $delivery_challan->grand_price = $input_data['grand_price_gst'];
        $delivery_challan->remarks = trim($input_data['challan_remark']);
        $delivery_challan->challan_status = "Pending";
        if ($refid != NULL) {
            $delivery_challan->ref_delivery_challan_id = $refid;
        }


        $delivery_challan->save();

        $delivery_challan_id = $delivery_challan->id;
        $created_at = $delivery_challan->created_at;
        $updated_at = $delivery_challan->updated_at;

        $order_products = [];
//        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "" && $product_data['order'] != "") {
//                $order_products = [
                $order_products[] = [
                    'order_id' => $delivery_challan_id,
                    'order_type' => 'delivery_challan',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'length' => isset($product_data['length'])? $product_data['length']:'',
                    'actual_pieces' => $product_data['actual_pieces'],
                    'actual_quantity' => $product_data['actual_quantity'],
                    'quantity' => $product_data['actual_quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price'],
                    'vat_percentage' => ((isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') || (isset($input_data['vat_percentage']) && $input_data['vat_percentage'] != 0)) ? 1 : 0,
                    'from' => $input_data['order_id'],
                    'parent' => $product_data['order'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
//                $add_order_products = AllOrderProducts::create($order_products);
            } else if ($product_data['name'] != "" && $product_data['order'] == "") {
//                $order_products = [
                $order_products[] = [
                    'order_id' => $delivery_challan_id,
                    'order_type' => 'delivery_challan',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'length' => isset($product_data['length'])? $product_data['length']:'',
                    'actual_pieces' => $product_data['actual_pieces'],
                    'actual_quantity' => $product_data['quantity'],
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price'],
                    'vat_percentage' => ((isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') || (isset($input_data['vat_percentage']) && $input_data['vat_percentage'] != 0)) ? 1 : 0,
                    'from' => '',
                    'parent' => '',
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
//                $add_order_products = AllOrderProducts::create($order_products);
            }
        }
        $add_order_products = AllOrderProducts::insert($order_products);

        $actual_qty = $this->calc_actual_qty($delivery_challan_id, $input_data);

        if (isset($input_data['loaded_by_pipe'])) {
            $loaders = $input_data['loaded_by_pipe'];
            $loaders_info = [];
            foreach ($loaders as $loader) {
                $loaders_info[] = [
                    'delivery_challan_id' => $delivery_challan_id,
                    'loaded_by_id' => $loader,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'sale',
                    'product_type_id' => '1',
                    'total_qty' => $actual_qty['loaded_by_pipe'],
                ];
            }
            $add_loaders_info = DeliveryChallanLoadedBy::insert($loaders_info);
        }
        if (isset($input_data['loaded_by_structure'])) {
            $loaders = $input_data['loaded_by_structure'];
            $loaders_info = [];
            foreach ($loaders as $loader) {
                $loaders_info[] = [
                    'delivery_challan_id' => $delivery_challan_id,
                    'loaded_by_id' => $loader,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'sale',
                    'product_type_id' => '2',
                    'total_qty' => $actual_qty['loaded_by_structure'],
                ];
            }
            $add_loaders_info = DeliveryChallanLoadedBy::insert($loaders_info);
        }
        if (isset($input_data['loaded_by_profile'])) {
            $loaders = $input_data['loaded_by_profile'];
            $loaders_info = [];
            foreach ($loaders as $loader) {
                $loaders_info[] = [
                    'delivery_challan_id' => $delivery_challan_id,
                    'loaded_by_id' => $loader,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'sale',
                    'product_type_id' => '3',
                    'total_qty' => $actual_qty['loaded_by_profile'],
                ];
            }
            $add_loaders_info = DeliveryChallanLoadedBy::insert($loaders_info);
        }
        if (isset($input_data['labour_pipe'])) {
            $labours = $input_data['labour_pipe'];
            $labours_info = [];
            foreach ($labours as $labour) {
                $labours_info[] = [
                    'delivery_challan_id' => $delivery_challan_id,
                    'labours_id' => $labour,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'sale',
                    'product_type_id' => '1',
                    'total_qty' => $actual_qty['labour_pipe'],
                ];
            }
            $add_loaders_info = App\DeliveryChallanLabours::insert($labours_info);
        }
        if (isset($input_data['labour_structure'])) {
            $labours = $input_data['labour_structure'];
            $labours_info = [];
            foreach ($labours as $labour) {
                $labours_info[] = [
                    'delivery_challan_id' => $delivery_challan_id,
                    'labours_id' => $labour,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'sale',
                    'product_type_id' => '2',
                    'total_qty' => $actual_qty['labour_structure'],
                ];
            }
            $add_loaders_info = App\DeliveryChallanLabours::insert($labours_info);
        }
        if (isset($input_data['labour_profile'])) {
            $labours = $input_data['labour_profile'];
            $labours_info = [];
            foreach ($labours as $labour) {
                $labours_info[] = [
                    'delivery_challan_id' => $delivery_challan_id,
                    'labours_id' => $labour,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'sale',
                    'product_type_id' => '3',
                    'total_qty' => $actual_qty['labour_profile'],
                ];
            }
            $add_loaders_info = App\DeliveryChallanLabours::insert($labours_info);
        }

        return $delivery_challan_id;
    }

    public function calc_actual_qty($dc_id = 0, $input_data = []) {
        $actual_qty['pipe'] = "0";
        $actual_qty['structure'] = "0";
        $actual_qty['sheet'] = "0";
        $actual_qty['loaded_by_pipe'] = "0";
        $actual_qty['loaded_by_structure'] = "0";
        $actual_qty['loaded_by_profile'] = "0";
        $actual_qty['labour_pipe'] = "0";
        $actual_qty['labour_structure'] = "0";
        $actual_qty['labour_profile'] = "0";

        if ($dc_id != 0 && $input_data != []) {
            $allorder = DeliveryChallan::with('delivery_challan_products.order_product_details.product_category')->find($dc_id);

            foreach ($allorder['delivery_challan_products'] as $key => $value) {
                if ($value['order_product_details']['product_category']->product_type_id == 1) {
                    $actual_qty['pipe'] += $value->actual_quantity;
                } else if ($value['order_product_details']['product_category']->product_type_id == 2) {
                    $actual_qty['structure'] += $value->actual_quantity;
                }else if ($value['order_product_details']['product_category']->product_type_id == 3) {
                    $actual_qty['sheet'] += $value->actual_quantity;
                }
            }

            if (isset($input_data['loaded_by_pipe'])) {
                $actual_qty['loaded_by_pipe'] = $actual_qty['pipe'] / count((array)$input_data['loaded_by_pipe']);
            }
            if (isset($input_data['loaded_by_structure'])) {
                $actual_qty['loaded_by_structure'] = $actual_qty['structure'] / count((array)$input_data['loaded_by_structure']);
            }
            if (isset($input_data['loaded_by_profile'])) {
                $actual_qty['loaded_by_profile'] = $actual_qty['sheet'] / count((array)$input_data['loaded_by_profile']);
            }
            if (isset($input_data['labour_pipe'])) {
                $actual_qty['labour_pipe'] = $actual_qty['pipe'] / count((array)$input_data['labour_pipe']);
            }
            if (isset($input_data['labour_structure'])) {
                $actual_qty['labour_structure'] = $actual_qty['structure'] / count((array)$input_data['labour_structure']);
            }
            if (isset($input_data['labour_profile'])) {
                $actual_qty['labour_profile'] = $actual_qty['sheet'] / count((array)$input_data['labour_profile']);
            }
        }

        return $actual_qty;
    }

    /*
     * save create load truck form details for the challan
     */
       public function store_load_truck($id) {
         $input_data = Input::all();
        //  dd($input_data);
        $del = LoadDelboy::where('delivery_id',$id)->where('del_boy', '=', Auth::id())->where('assigned_status', 1)->count();
        if((isset($del) && $del == 1) || Auth::user()->role_id == 0 || Auth::user()->role_id == 8) {
        $inputprodut = (Input::has('product')) ? Input::get('product') : 'array()';
        $truck_product_ids = "";
        if(!empty($inputprodut)){
            $productids = array();
            foreach($inputprodut as $truckprod){
                if($truckprod['actual_pieces'] >0){
                    $productids[] = $truckprod['id'];
                }
            }
            if(!empty($productids)){
                $truck_product_ids = implode(',',$productids);

            }
            else{
                $truck_product_ids = "";
            }
        }
        if(!empty($truck_product_ids)){
            $serialize = serialize($truck_product_ids);
        }
        else{
            $serialize = "";
        }
         $empty_truck_weight = (Input::has('empty_truck_weight')) ? Input::get('empty_truck_weight') : '0';
         $total_avg = (Input::has('total_avg_qty')) ? Input::get('total_avg_qty') : '0';
         //$final_truck_weight = (Input::has('final_truck_weight_load')) ? Input::get('final_truck_weight_load') : '0';
         $delivery_order_details = DeliveryOrder::find($id);

        if ($delivery_order_details->del_boy != ""){
             foreach(explode(',', $delivery_order_details->del_boy) as $key => $info){
                $variable = 'truck_weight'.$info;
                 $truck_weight = (Input::has($variable)) ? Input::get($variable) : '0';
                  $delboy = Auth::id();
                  $delivery_truckdata = LoadTrucks::where('deliver_id',$id)
                     ->where('userid', '=', $delboy)
                     ->first();
                 if(empty($delivery_truckdata) ){
                    if($truck_weight !=0){
                         $delivery_anothertruckdata = LoadTrucks::where('deliver_id',$id)->first();
                         if(empty($delivery_anothertruckdata)){
                             $loadetrucks[] = [
                                'deliver_id' => $id,
                                'empty_truck_weight' =>  $empty_truck_weight,
                                'final_truck_weight' => $truck_weight,
                                'product_id'  =>$serialize,
                                'userid' => $delboy,
                                'updated_at' => date("Y-m-d H:i:s"),

                           ];

                          LoadTrucks::insert($loadetrucks);

                          LoadDelboy::where('delivery_id', '=', $id)
                                    ->where('del_boy', '=', $delboy)
                                    ->where('assigned_status', 1)
                                    ->update(array(
                                    'updated_at' => date("Y-m-d H:i:s")));
                         }
                         else{
                                $delivery_productdata = LoadTrucks::where('deliver_id',$id)->first();
                                $truck_procudcts = unserialize($delivery_productdata->product_id);
                                $explodetruck_prodcuts = explode(',',$truck_procudcts);
                                $secproductids = array();
                                foreach($inputprodut as $truckprod){
                                    $product_id = $truckprod['id'];
                                    $actual_pieces = $truckprod['actual_pieces'];
                                    if($actual_pieces >0){
                                        if(!(in_array($product_id,$explodetruck_prodcuts))){
                                            $secproductids[] = $product_id;
                                         }
                                    }
                                }
                                if(!empty($secproductids)){
                                    $truck_secproduct_ids = implode(',',$secproductids);
                                    $serialize = serialize($truck_secproduct_ids);
                                }
                                else{
                                        $serialize = "";
                                }
                                $loadetrucks[] = [
                                      'deliver_id' => $id,
                                      'empty_truck_weight' =>  $empty_truck_weight,
                                      'final_truck_weight' => $truck_weight,
                                      'product_id'  =>$serialize,
                                      'userid' => $delboy,
                                      'updated_at' => date("Y-m-d H:i:s"),

                                ];
                                LoadTrucks::insert($loadetrucks);
                                LoadDelboy::where('delivery_id', '=', $id)
                                    ->where('del_boy', '=', $delboy)
                                    ->where('assigned_status', 1)
                                    ->update(array(
                                    'updated_at' => date("Y-m-d H:i:s")));
                         }
                    }

                }
                else{
                      if($truck_weight !=0){
                         $delivery_productdata = LoadTrucks::where('deliver_id',$id)
                                       ->where('userid', '=', $delboy)
                                      ->first();
                         $truck_procudcts = unserialize($delivery_productdata->product_id);
                         $explodetruck_prodcuts = explode(',',$truck_procudcts);
                         $secproductids = array();
                         foreach($inputprodut as $truckprod){
                            $product_id = $truckprod['id'];
                            if(in_array($product_id,$explodetruck_prodcuts)){
                                 $secproductids[] = $product_id;
                            }

                         }
                         if(!empty($secproductids)){
                            $truck_secproduct_ids = implode(',',$secproductids);
                            $serialize = serialize($truck_secproduct_ids);
                         }
                        else{
                                $serialize = "";
                         }
                    LoadTrucks:: where('deliver_id', '=', $id)
                        ->where('userid', '=', $delboy)
                        ->update(array(
                         'empty_truck_weight' => $empty_truck_weight,
                         'final_truck_weight' => $truck_weight,
                         'product_id'  =>$serialize,
                        'userid' => $delboy,
                        'updated_at' => date("Y-m-d H:i:s"),
                    ));
                    LoadDelboy::where('delivery_id', '=', $id)
                    ->where('del_boy', '=', $delboy)
                    ->where('assigned_status', 1)
                    ->update(array(
                    'updated_at' => date("Y-m-d H:i:s"),
                ));
                  }
                }
                $labour = (Input::has('labour')) ? Input::get('labour') : '';
                if(!empty($labour)){


                    $truck_load = LoadTrucks::where('deliver_id', '=', $id)
                                    ->where('userid', '=', $delboy)
                                    ->first();
                    // dd($truck_load);
                    if(!empty($truck_load)){

                        foreach($labour as $key => $val){
                            $labour_count = LoadLabour::where('delivery_id',$id)->where('del_boy_id',$key)->first();
                                if(!empty($labour_count)){
                                    LoadLabour::where('delivery_id',$id)->where('del_boy_id',$key)->delete();
                                }
                            foreach($val as $load_val){
                                // print_r($key);

                                    $load_loabour = [
                                        'del_boy_id' => $key,
                                        'labour_id' => $load_val,
                                        'delivery_id' => $id,
                                    ];
                                    LoadLabour::insert($load_loabour);

                            }

                        }

                    }
                }
             }
         }

        if($empty_truck_weight != '' || $empty_truck_weight != '0') {
            $update_delivery = DeliveryOrder::where('id',$id)->update([
                'empty_truck_weight'=>$empty_truck_weight,
            ]);
        }
          $products_data = $_POST['product'];
        foreach($products_data as $pkey =>$product_info){
            $actual_pieces = $product_info['actual_pieces'];
            $average_weight = $product_info['average_weight'];
            $vat_percentage = isset($product_info['vat_percentage'])?$product_info['vat_percentage']:'';
            $productid =$product_info['order'];
            if(!empty($actual_pieces)&& !empty($average_weight)){
                $update_product_details = AllOrderProducts::where('id',$productid)->update([
                 'actual_pieces'=>$actual_pieces,
                 'actual_quantity'=>$average_weight,
              ]);
            }
            if(!empty($vat_percentage) && $vat_percentage == 'yes'){
                $update_product_details = AllOrderProducts::where('id',$productid)->update([
                    'vat_percentage'=> 1 ,
                ]);
            }
        }
         $count = count((array)$products_data);
         $productlist = AllOrderProducts::where('order_id', '=', $id)
                      ->where('actual_pieces', '>', 0)
                      ->where('order_type', '=', 'delivery_order')
              ->get();
         $productlistcount = $productlist->count();
         $trucklist = LoadTrucks::where('deliver_id', '=', $id)->get();

         if($productlistcount ==$count){

             $sum =0;
              foreach($trucklist as $truck){

                  $sum = (float)$sum + (float)$truck->final_truck_weight;
              }
              $final_weight = (float)$total_avg +(float)$empty_truck_weight;
               $update_delivery = DeliveryOrder::where('id',$id)->update([
                 'final_truck_weight'=>$final_weight,
              ]);

         }
         if ($delivery_order_details->del_boy == ""){
         $truck_weight = (Input::has('truck_weight')) ? Input::get('truck_weight') : '0';
         $delboy = Auth::id();
         $delivery_anothertruckdata = LoadTrucks::where('deliver_id',$id)->first();
                         if(empty($delivery_anothertruckdata)){
                             $loadetrucks[] = [
                                'deliver_id' => $id,
                                'empty_truck_weight' =>  $empty_truck_weight,
                                'final_truck_weight' => $truck_weight,
                                'product_id'  =>$serialize,
                                'userid' => $delboy,
                                'updated_at' => date("Y-m-d H:i:s"),

                           ];

                          LoadTrucks::insert($loadetrucks);

                          LoadDelboy::where('delivery_id', '=', $id)
                                    ->where('del_boy', '=', $delboy)
                                    ->where('assigned_status', 1)
                                    ->update(array(
                                    'updated_at' => date("Y-m-d H:i:s")));
                         }
                         if($truck_weight != 0 ) {
                            $delivery_productdata = LoadTrucks::where('deliver_id',$id)
                                                    ->where('userid', '=', $delboy)
                                                    ->first();

                            LoadTrucks:: where('deliver_id', '=', $id)
                                        ->where('userid', '=', $delboy)
                                        ->update(array(
                                            'empty_truck_weight' => $empty_truck_weight,
                                            'final_truck_weight' => $truck_weight,
                                            'product_id'  =>$serialize,
                                        'userid' => $delboy,
                                        'updated_at' => date("Y-m-d H:i:s"),
                                        ));
                            LoadDelboy::where('delivery_id', '=', $id)
                                        ->where('del_boy', '=', $delboy)
                                        ->where('assigned_status', 1)
                                        ->update(array(
                                        'updated_at' => date("Y-m-d H:i:s"),
                                        ));
                         }

                         $labour = (Input::has('labour')) ? Input::get('labour') : '';
                         if(!empty($labour)){


                             $truck_load = LoadTrucks::where('deliver_id', '=', $id)
                                             ->where('userid', '=', $delboy)
                                             ->first();
                             // dd($truck_load);
                             if(!empty($truck_load)){
                                 foreach($labour as $key => $val){
                                     $labour_count = LoadLabour::where('delivery_id',$id)->where('del_boy_id',$key)->first();
                                         if(!empty($labour_count)){
                                             LoadLabour::where('delivery_id',$id)->where('del_boy_id',$key)->delete();
                                         }
                                     foreach($val as $load_val){
                                         // print_r($key);

                                             $load_loabour = [
                                                 'del_boy_id' => $key,
                                                 'labour_id' => $load_val,
                                                 'delivery_id' => $id,
                                             ];
                                             LoadLabour::insert($load_loabour);
                                     }
                                 }
                             }
                         }

         }
         $do_det = DeliveryOrder::where('id',$id)->first();
         if(isset($do_det) && !empty($do_det->final_truck_weight)){
            $user = User::find($do_det->created_by);
            //  Confirmation msg to Admin who created the delivery order
            if($user){
                if (App::environment('local')) {
                    $mobile_number = Config::get('smsdata.send_sms_to');
                } else {
                    $mobile_number = $user->mobile_number;
                }
                $str = "Order No #".$do_det->serial_no." has been loaded successfully.\nVIKAS ASSOCIATES";
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $mobile_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
            if(isset($do_det->del_supervisor) && !empty($do_det->del_supervisor)){
                $del_user = User::find($do_det->del_supervisor);
                //  Confirmation msg to Supervisor who assigned the order to del_boy
                if($del_user){
                    if (App::environment('local')) {
                        $mobile_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $mobile_number = $del_user->mobile_number;
                    }
                    $str = "Order No #".$do_det->serial_no." has been loaded successfully.\nVIKAS ASSOCIATES";
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $mobile_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }
         }
         $parameter = Session::get('parameters');
         $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';
         $action = Input::get('action');
         $del = LoadDelboy::where('delivery_id',$id)->where('del_boy', '=', $delboy)->where('assigned_status', 1)->count();
         if((isset($del) && $del == 1) || Auth::user()->role_id == 0 || Auth::user()->role_id == 8) {
            if(isset($empty_truck_weight) && $empty_truck_weight != 0 && isset($truck_weight) && $truck_weight != 0) {
                if(!($truck_weight<$empty_truck_weight)) {
                    // if($action ==''){
                        return redirect('delivery_order' . $parameters)->with('success', 'Truck loaded.');
                    // }
                    // elseif($action == 'Save'){
                    //     return Redirect::back()->with('validation_message', 'Product loaded.');
                    // }
                    // else{
                    //     return Redirect::back()->with('validation_message', 'Truck loaded. Please refresh the page');
                    // }
                }
                else{
                    return Redirect::back()->with('validation_message', 'Please fill valid truck weight.');
                }
            }
            elseif(isset($empty_truck_weight) && $empty_truck_weight != 0 && isset($truck_weight) && $truck_weight == 0) {
                return Redirect::back()->with('validation_message', 'Please fill truck weight.');
            }
            else{
                return Redirect::back()->with('validation_message', 'Please fill empty truck weight.');
            }
        }else {
            return redirect('delivery_order' . $parameters)->with('success','You are not authorised to this order now.');
        }
        }else {
            return redirect('delivery_order')->with('success','You are not authorised to this order now.');
        }
    }

      public function save_product(Request $request) {

        $actual_pieces = Input::get('actual_pieces');
        $average_weight = Input::get('average_weight');
        $delivery_id = Input::get('delivery_id');
        $product_id = Input::get('product_id');
        $del = LoadDelboy::where('delivery_id',$delivery_id)->where('del_boy', '=', Auth::id())->where('assigned_status', 1)->count();
        if((isset($del) && $del == 1) || Auth::user()->role_id == 0 || Auth::user()->role_id == 8) {
        AllOrderProducts::where('id',$product_id)->where('order_id',$delivery_id)->update([
                'actual_pieces'=>$actual_pieces,
                'actual_quantity'=>$average_weight,
             ]);
             echo "success";
        } else{
            echo "failed";
        }
    }

    public function save_empty_truck(Request $request) {

        $empty_truck_value = (Input::has('empty_truck_value')) ? Input::get('empty_truck_value') : '0';
        $delivery_id = Input::get('delivery_id');
        $del = LoadDelboy::where('delivery_id',$delivery_id)->where('del_boy', '=', Auth::id())->where('assigned_status', 1)->count();
        if((isset($del) && $del == 1) || Auth::user()->role_id == 0 || Auth::user()->role_id == 8) {
            if($empty_truck_value != '' || $empty_truck_value != '0') {
                $update_delivery = DeliveryOrder::where('id',$delivery_id)->update([
                    'empty_truck_weight'=>$empty_truck_value,
                ]);

                echo "success";
            }

        } else{
            echo "failed";
        }
    }
    public function save_truck_weight(Request $request) {

        $truck_weight = (Input::has('truck_weight')) ? Input::get('truck_weight') : '0';
        // dd($truck_weight);
        $delivery_id = Input::get('delivery_id');
        $delboy_id = Input::get('delboy_id');
        $del = LoadDelboy::where('delivery_id',$delivery_id)->where('del_boy', '=', Auth::id())->where('assigned_status', 1)->count();
        if((isset($del) && $del == 1) || Auth::user()->role_id == 0 || Auth::user()->role_id == 8) {
            $delivery_anothertruckdata = LoadTrucks::where('deliver_id',$delivery_id)->where('userid', '=', $delboy_id)->first();
            if(empty($delivery_anothertruckdata)){
                $loadetrucks[] = [
                'deliver_id' => $delivery_id,
                'final_truck_weight' => $truck_weight,
                'userid' => $delboy_id,
                'updated_at' => date("Y-m-d H:i:s"),

            ];

            LoadTrucks::insert($loadetrucks);

            LoadDelboy::where('delivery_id', '=', $delivery_id)
                    ->where('del_boy', '=', Auth::id())
                    ->where('assigned_status', 1)
                    ->update(array(
                    'updated_at' => date("Y-m-d H:i:s")));

            }
            if($truck_weight != 0 ) {
            LoadTrucks::where('deliver_id', '=', $delivery_id)
                        ->where('userid', '=', $delboy_id)
                        ->update(array(
                            'final_truck_weight' => $truck_weight,
                        'userid' => $delboy_id,
                        'updated_at' => date("Y-m-d H:i:s"),
                        ));
            LoadDelboy::where('delivery_id', '=', $delivery_id)
                        ->where('del_boy', '=', Auth::id())
                        ->where('assigned_status', 1)
                        ->update(array(
                        'updated_at' => date("Y-m-d H:i:s"),
                        ));

            }
            echo "success";
        } else{
            echo "failed";
        }
    }


    /*
     * save create delivery challan form details for the challan
     */

    public function store_delivery_challan($id) {

        $input_data = Input::all();
        $empty_truck_weight = (Input::has('empty_truck_weight')) ? Input::get('empty_truck_weight') : '0';
        $final_truck_weight = (Input::has('final_truck_weight')) ? Input::get('final_truck_weight') : '0';
        $delivery_order_details = DeliveryOrder::find($id);
        if (!empty($delivery_order_details)) {
            if ($delivery_order_details->order_status == 'completed') {
                if(Session::has('success') == 'One Delivery Challan is successfully created.'){
                    return redirect('delivery_order')->with('success', 'One Delivery Challan is successfully created.');
                }else{
                    return Redirect::back()->with('validation_message', 'This delivery order is already converted to delivry challan. Please refresh the page');
                }
            }
        }

//        if ($empty_truck_weight == '0' | $final_truck_weight == '0') {
//            return Redirect::back()->with('validation_message', 'Please Add Truck Weight');
//        }


        if (Session::has('forms_delivery_challan')) {
            $session_array = Session::get('forms_delivery_challan');
            if (count((array)$session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('success') == 'One Delivery Challan is successfully created.'){
                        return redirect('delivery_order' . $parameters)->with('success', 'One Delivery Challan is successfully created.');
                    }else{
                        return Redirect::back()->with('validation_message', 'This delivery challan is already saved. Please refresh the page');
                    }
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_delivery_challan', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_delivery_challan', $forms_array);
        }

        if (isset($input_data['challan_driver_contact'])) {
            $delivery_order_details->driver_contact_no = $input_data['challan_driver_contact'];
        }

        if (isset($input_data['challan_vehicle_number'])) {
            $delivery_order_details->vehicle_number = $input_data['challan_vehicle_number'];
        }

        $delivery_order_details->save();
        if (isset($input_data['product']))
            $total_product_count = count((array)$input_data['product']);
        else
            $total_product_count = 0;

        $total_vat_items = 0;
        $total_vat_price = 0;
        $total_without_vat_items = 0;
        $total_without_vat_price = 0;
        $total_profile_items = 0;
        $total_profile_price = 0;
        $counter_vat = 0;
        $counter_without_vat = 0;
        $counter_profile = 0;
        $vat_product;
        $without_vat_product;
        $profile_product;
        $total_actual_quantity_vat = 0;
        $total_actual_quantity_without_vat = 0;
        $total_actual_quantity_profile = 0;
        $profile_vat_amount = 0;
        $case = array();
        $final_vat_amount = 0;
        $profile_sgst = 0;
        $profile_cgst = 0;
        $profile_igst = 0;
        $profile_vat = 0;
        $loading_vat = 0;
        $loading_vat_amount = 0;
        $freight_vat_amount = 0;
        $discount_vat_amount = 0;

        foreach ($input_data['product'] as $product) {
            $product_id = $product['id'];
            $product_sub = ProductSubCategory::with('product_category')->where('id','=',$product_id)->get();
            if(isset($product_sub) && isset($product_sub[0]['product_category'])){
                $product_type_id = $product_sub[0]['product_category']->product_type_id;
            }

            if (isset($product['actual_quantity']) && isset($product['price'])) {
                if (isset($product_type_id)) {


                    $total_actual_quantity_profile = $total_actual_quantity_profile + $product['actual_quantity'];
//                  $total_profile_price = $total_vat_price + ($product['price'] * $product['actual_quantity']);
                    if (isset($product['vat_percentage']) && $product['vat_percentage'] == 'yes'){

                        //dd($product);
                        //die;
                        $cust_id = $delivery_order_details->customer_id;
                        $state = Customer::where('id',$cust_id)->first()->state;
                        if(!empty($state)){
                            $local_state = App\States::where('id',$state)->first()->local_state;
                        }
                        else{
                            $local_state = "";
                        }
                        $productsub = ProductSubCategory::where('id',$product['id'])->first();
                        $product_cat = ProductCategory::where('id',$productsub->product_category_id)->first();

                        $product_price = (float)$product['price'] * (float)$product['actual_quantity'];

                        if($product_cat->hsn_code && $delivery_order_details->vat_percentage == 0 ){
                            $hsn_det = \App\Hsn::where('hsn_code',$product_cat->hsn_code)->first();
                            $gst_det = \App\Gst::where('gst',isset($hsn_det->gst)?$hsn_det->gst:'')->first();
                            if($local_state){
                                $profile_sgst = $product_price * (isset($gst_det->sgst)?$gst_det->sgst:'')/100;
                                $profile_cgst = $product_price * (isset($gst_det->cgst)?$gst_det->cgst:'')/100;
                                $profile_vat = round($profile_sgst,2) + round($profile_cgst,2);
                                $profile_vat_amount = (isset($gst_det->cgst)?$gst_det->cgst:'') + (isset($gst_det->sgst)?$gst_det->sgst:'');
                            }
                            else{
                                $profile_igst = $product_price * (isset($gst_det->igst)?$gst_det->igst:'')/100;
                                $profile_vat = round($profile_igst,2);
                                $profile_vat_amount = isset($gst_det->igst)?$gst_det->igst:'';
                            }
                        }
                        else{
                            $profile_vat_amount = $delivery_order_details->vat_percentage;
                        }

                        //$profile_vat_amount = $input_data['vat_percentage'];

                       

                        if(isset($input_data['vat_percentage'])){
                            $prod_vat_price = ((float)$product_price * (float)$profile_vat_amount)/100;
                        }
                        else{
                            $prod_vat_price = ((float)$product_price * 0)/100;
                        }

                        $final_vat_amount += $profile_vat;
                        $loading_vat = 18;

                        $product_price = (float)$product_price + (float)$prod_vat_price;
                        $total_profile_price = (float)$total_profile_price + (float)$product_price;

                        $total_profile_items ++;
                        $profile_product[$counter_profile++] = $product;

                    }else{

                        $product_price = (float)$product['price'] * (float)$product['actual_quantity'];
//                        $total_profile_price = $total_profile_price + $product_price;

                        $total_without_vat_items ++;
                        $without_vat_product[$counter_without_vat++] = $product;
                        $total_actual_quantity_without_vat = (float)$total_actual_quantity_without_vat + (float)$product['actual_quantity'];
                        $total_without_vat_price = (float)$total_without_vat_price + ((float)$product['price'] * (float)$product['actual_quantity']);
                    }
                }
                else if (isset($product['vat_percentage']) && $product['vat_percentage'] == 'yes') {
                    $total_vat_items ++;
                    $vat_product[$counter_vat++] = $product;
                    $total_actual_quantity_vat = (float)$total_actual_quantity_vat + (float)$product['actual_quantity'];
                    $total_vat_price = (float)$total_vat_price + ((float)$product['price'] * (float)$product['actual_quantity']);
                } else {
                    $total_without_vat_items ++;
                    $without_vat_product[$counter_without_vat++] = $product;
                    $total_actual_quantity_without_vat = (float)$total_actual_quantity_without_vat + (float)$product['actual_quantity'];
                    $total_without_vat_price = (float)$total_without_vat_price + ((float)$product['price'] * (float)$product['actual_quantity']);
                }
            }
        }

        if(isset($input_data['loading']) || isset($input_data['discount']) || isset($input_data['freight'])){
            $loading_vat_amount = ((float)$input_data['loading'] * (float)$loading_vat) / 100;
            $freight_vat_amount = ((float)$input_data['freight'] * (float)$loading_vat) / 100;
            $discount_vat_amount = ((float)$input_data['discount'] * (float)$loading_vat) / 100;
        }

        $input_data['grand_price_gst'] = $input_data['vat_total'] + $final_vat_amount + $loading_vat_amount + $freight_vat_amount + $discount_vat_amount;
        // dd($input_data['grand_price_gst']);
// dd($input_data);
        //dd($total_product_count. " ". $total_profile_items);
        //dd("asdsa");



        if ($total_product_count == $total_profile_items) {
            $case = 'all_profile';
            $input_data['freight_vat_percentage'] = $input_data['loading_vat_percentage'] = $input_data['discount_vat_percentage'] = $profile_vat_amount;
            $all_vat_share_overhead = number_format((float)$total_profile_price + (float)$input_data['loading'] + (float)$input_data['discount'] + (float)$input_data['freight'], 2, '.', '');
//            $all_vat_on_overhead_count = ($all_vat_share_overhead * $input_data['vat_percentage']) / 100;
            $all_vat_on_overhead_count = 0;

            $input_data['grand_total'] = $input_data['vat_total'] = round((float)$all_vat_share_overhead + (float)$all_vat_on_overhead_count + (float)$input_data['round_off'], 2);

            if($delivery_order_details->vat_percentage != 0){
                $input_data['vat_percentage'] = $delivery_order_details->vat_percentage;
            }
           $savedid = $this->store_delivery_challan_vat_wise($input_data, $id);
        }
        /* all items with puls VAT */
        elseif ($total_product_count == $total_vat_items) {
            $case = 'all_vat';

            //$input_data['freight_vat_percentage'] = $input_data['loading_vat_percentage'] = $input_data['discount_vat_percentage'] = $input_data['vat_percentage'];


            $all_vat_share_overhead = number_format((float)$input_data['total_price'] + (float)$input_data['loading'] + (float)$input_data['discount'] + (float)$input_data['freight'], 2, '.', '');
           // $all_vat_on_overhead_count = ($all_vat_share_overhead * $input_data['vat_percentage']) / 100;
            $all_vat_on_overhead_count = ($all_vat_share_overhead) / 100;

            $input_data['grand_total'] = $input_data['vat_total'] = round((float)$all_vat_share_overhead + (float)$all_vat_on_overhead_count + (float)$input_data['round_off'], 2);

            if($delivery_order_details->vat_percentage != 0){
                $input_data['vat_percentage'] = $delivery_order_details->vat_percentage;
            }
            $savedid = $this->store_delivery_challan_vat_wise($input_data, $id);
        }
        /* all items without VAT */
        elseif ($total_product_count == $total_without_vat_items) {

            $input_data['grand_total'] = number_format((float)$input_data['total_price'] + (float)$input_data['loading'] + (float)$input_data['discount'] + (float)$input_data['freight'], 2, '.', '');
            // exit;
            $input_data['freight_vat_percentage'] = $input_data['loading_vat_percentage'] = $input_data['discount_vat_percentage'] = $input_data['vat_percentage'] = $input_data['vat_total'] = number_format((float) 0.00, 2, '.', '');
            $case = 'all_without_vat';
            $savedid =  $this->store_delivery_challan_vat_wise($input_data, $id);
        }
        /* all items with and without VAT */
        else {
            $case = 'all_mixed';
            $vat_input_data = $without_vat_input_data = $profile_input_data = $input_data;
            if ($input_data['total_price'] <> 0) {
                $ratio_with_vat = number_format( ((((float)$total_vat_price) * 100) / (float)$input_data['total_price']), 2, '.', '');
                $ratio_without_vat = number_format( ((((float)$total_without_vat_price) * 100) / (float)$input_data['total_price']), 2, '.', '');
                $ratio_profile = number_format( ((((float)$total_profile_price) * 100) / (float)$input_data['total_price']), 2, '.', '');
            }

            $total_overhead = (float)$input_data['loading'] + (float)$input_data['freight'] + (float)$input_data['discount'];

            //$vat_share_overhead = number_format((float) (($ratio_with_vat * $total_overhead) / 100), 2, '.', '');
            $without_vat_share_overhead = number_format( (((float)$ratio_without_vat * (float)$total_overhead) / 100), 2, '.', '');
            $profile_share_overhead = number_format( (((float)$ratio_profile * (float)$total_overhead) / 100), 2, '.', '');


//            $vat_on_price_count = number_format((float) (($total_vat_price * $input_data['vat_percentage']) / 100), 2, '.', '');
//            $vat_on_overhead_count = number_format((float) (($ratio_profile * $input_data['vat_percentage']) / 100), 2, '.', '');

            if($delivery_order_details->vat_percentage != 0){
                $input_data['vat_percentage'] = $delivery_order_details->vat_percentage;
            }

            if(isset($total_profile_items) && $total_profile_items > 0){
                $profile_input_data['product'] = $profile_product;
                $profile_input_data['total_actual_quantity'] = $total_actual_quantity_profile;
                $profile_input_data['total_price'] = number_format((float) $total_profile_price, 2, '.', '');
                $profile_input_data['discount'] = number_format((float) ((float)$ratio_profile * (float)$input_data['discount']) / 100, 2, '.', '');
                $profile_input_data['freight'] = number_format((float) ((float)$ratio_profile * (float)$input_data['freight']) / 100, 2, '.', '');
                $profile_input_data['loading'] = number_format((float) ((float)$ratio_profile * (float)$input_data['loading']) / 100, 2, '.', '');
                $profile_input_data['round_off'] = number_format((float) ((float)$ratio_profile * (float)$input_data['round_off']) / 100, 2, '.', '');
                $profile_input_data['freight_vat_percentage'] = $profile_input_data['loading_vat_percentage'] = $profile_input_data['discount_vat_percentage'] = $profile_input_data['vat_percentage'] = number_format((float) $profile_vat_amount, 2, '.', '');
                $profile_input_data['grand_total'] = number_format((float) ((float)$total_profile_price + (float)$profile_share_overhead + (float)$profile_input_data['round_off']), 2, '.', '');
            }
            if(isset($total_vat_items) && $total_vat_items > 0) {
                $vat_input_data['product'] = $vat_product;
                $vat_input_data['total_actual_quantity'] = $total_actual_quantity_vat;
                $vat_input_data['total_price'] = number_format((float) $total_vat_price, 2, '.', '');
                $vat_input_data['discount'] = number_format((float) ((float)$ratio_with_vat * (float)$input_data['discount']) / 100, 2, '.', '');
                $vat_input_data['freight'] = number_format((float) ((float)$ratio_with_vat * (float)$input_data['freight']) / 100, 2, '.', '');
                $vat_input_data['loading'] = number_format((float) ((float)$ratio_with_vat * (float)$input_data['loading']) / 100, 2, '.', '');
                $vat_input_data['round_off'] = number_format((float) ((float)$ratio_with_vat * (float)$input_data['round_off']) / 100, 2, '.', '');
                //$vat_input_data['freight_vat_percentage'] = $vat_input_data['loading_vat_percentage'] = $vat_input_data['discount_vat_percentage'] = number_format((float) $vat_input_data['vat_percentage'], 2, '.', '');
//                $vat_input_data['grand_total'] = number_format((float) ($total_vat_price + $vat_on_price_count + $vat_share_overhead + $vat_on_overhead_count + $vat_input_data['round_off']), 2, '.', '');
                $total_amnt= (float)$total_vat_price + (float)$vat_input_data['freight'] + (float)$vat_input_data['discount']+ (float)$vat_input_data['loading'];
               // $gst_amount = $total_amnt * $vat_input_data['vat_percentage']/100;
                $gst_amount = $total_amnt * 100;
                $vat_input_data['grand_total'] = (float)$total_amnt + (float)$gst_amount + (float)$vat_input_data['round_off'];
            }
            if(isset($total_without_vat_items) && $total_without_vat_items > 0) {
                $without_vat_input_data['product'] = $without_vat_product;
                $without_vat_input_data['total_actual_quantity'] = $total_actual_quantity_without_vat;
                $without_vat_input_data['total_price'] = number_format((float) $total_without_vat_price, 2, '.', '');
                $without_vat_input_data['discount'] = number_format((float) ((float)$ratio_without_vat * (float)$input_data['discount']) / 100, 2, '.', '');
                $without_vat_input_data['freight'] = number_format((float) ((float)$ratio_without_vat * (float)$input_data['freight']) / 100, 2, '.', '');
                $without_vat_input_data['loading'] = number_format((float) ((float)$ratio_without_vat * (float)$input_data['loading']) / 100, 2, '.', '');
                $without_vat_input_data['round_off'] = number_format((float) ((float)$ratio_without_vat * (float)$input_data['round_off']) / 100, 2, '.', '');
                //$without_vat_input_data['freight_vat_percentage'] = $without_vat_input_data['loading_vat_percentage'] = $without_vat_input_data['discount_vat_percentage'] = $without_vat_input_data['vat_percentage'] = 0.00;
                $without_vat_input_data['grand_total'] = number_format((float) $total_without_vat_price + (float)$without_vat_share_overhead + (float)$without_vat_input_data['round_off'], 2, '.', '');
            }



            if($total_profile_items > 0 && $total_vat_items > 0 && $total_without_vat_items>0){
                $savedid = $this->store_delivery_challan_vat_wise($profile_input_data, $id);
                $savedid = $this->store_delivery_challan_vat_wise($vat_input_data, $id, $savedid);
                $this->store_delivery_challan_vat_wise($without_vat_input_data, $id, $savedid);
            }
            elseif($total_profile_items > 0 && $total_vat_items > 0) {
                $savedid = $this->store_delivery_challan_vat_wise($profile_input_data, $id);
                $this->store_delivery_challan_vat_wise($vat_input_data, $id, $savedid);
            }
            elseif($total_profile_items > 0 && $total_without_vat_items > 0) {
                $savedid = $this->store_delivery_challan_vat_wise($profile_input_data, $id);
                $this->store_delivery_challan_vat_wise($without_vat_input_data, $id, $savedid);
            }
            elseif($total_vat_items > 0 && $total_without_vat_items > 0) {
                $savedid = $this->store_delivery_challan_vat_wise($vat_input_data, $id);
                $this->store_delivery_challan_vat_wise($without_vat_input_data, $id, $savedid);
            }

        }
        DeliveryOrder:: where('id', '=', $id)->update(array('order_status' => 'completed',
            'empty_truck_weight' => $empty_truck_weight,
            'final_truck_weight' => $final_truck_weight,
        ));
        /* inventory code */
        $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'delivery_order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);


        //         update sync table
        $tables = ['delivery_order', 'all_order_products', 'delivery_challan'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */

        $parameter = Session::get('parameters');
        $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';
        if($delivery_order_details->del_supervisor){
            App\User::where('id',$delivery_order_details->del_supervisor)->update(['status'=>0]);
        }
        if($delivery_order_details->del_boy){
            App\User::where('id',$delivery_order_details->del_boy)->update(['status'=>0]);
        }

        DeliveryChallan::where('id',$savedid)->update(['is_editable'=>$delivery_order_details->is_editable]);

        return redirect('delivery_order' . $parameters)->with('success', 'One Delivery Challan is successfully created.');
    }

    /*
     * Generate Serial number and print Delivery order
     * as well as send the sms to the customer
     */

    public function print_delivery_order($id, DropboxStorageRepository $connection) {

        if (Input::has('empty_truck_weight')) {
            $empty_truck_weight = Input::get('empty_truck_weight');
            if ($empty_truck_weight != "0" | $empty_truck_weight != "") {
                DeliveryOrder::where('id', $id)->update(['empty_truck_weight' => $empty_truck_weight]);
            }
        }
        if (Input::has('vehicle_number')) {
            $vehicle_number = Input::get('vehicle_number');
            if ($vehicle_number!= "") {
                DeliveryOrder::where('id', $id)->update(['vehicle_number' => $vehicle_number]);
            }
        }
        if (Input::has('customer_type')) {
            $customer_type = Input::get('customer_type');
        }
        $whatsapp_error = '';
        $current_date = date("m/d/");
        $sms_flag = 1;
        set_time_limit(0);
        $date_letter = 'DO/' . $current_date . "" . $id;
        $do = DeliveryOrder::where('updated_at', 'like', date('Y-m-d') . '%')->withTrashed()->get();

        if (count((array)$do) <= 0) {
            $number = '1';
        } else {
            $serial_numbers = [];
            foreach ($do as $temp) {
                $list = explode("/", $temp->serial_no);
                $serial_numbers[] = $list[count((array)$list) - 1];
                $pri_id = max($serial_numbers);
                $number = $pri_id + 1;
            }
        }

        // $date_letter = 'DO/' . $current_date . "" . $number;
        // DeliveryOrder:: where('id', $id)->where('serial_no', '=', "")->update(array('serial_no' => $date_letter));
//        DeliveryOrder:: where('id', $id)->update(array('serial_no' => $date_letter));
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->find($id);
        $order_qty = 0;
        if (isset($delivery_data['delivery_product'])) {
            foreach ($delivery_data['delivery_product'] as $key => $do_product_details) {
                if ($do_product_details->unit_id == 1) {
                    $order_qty = (float)$order_qty + (float)$do_product_details->quantity;
                }
                if ($do_product_details->unit_id == 2) {
                    $order_qty = (float)$order_qty + ((float)$do_product_details->quantity * (float)$do_product_details->product_sub_category->weight);
                }
                if ($do_product_details->unit_id == 3) {
                    $order_qty = (float)$order_qty + ((float)($do_product_details->quantity / $do_product_details->product_sub_category->standard_length ) * (float)$do_product_details->product_sub_category->weight);
                }
                if ($do_product_details->unit_id == 4) {
                    $order_qty = (float)$order_qty + ((float)$do_product_details->quantity * (float)$do_product_details->product_sub_category->weight * (float)$do_product_details->length);
                }
                if ($do_product_details->unit_id == 5) {
                    $order_qty = (float)$order_qty + ((float)$do_product_details->quantity * (float)$do_product_details->product_sub_category->weight * (float)($do_product_details->length/305));
                }
            }
            $delivery_data->total_quantity = round($order_qty / 1000, 2);
        }
        $units = Units::all();
        //['product_sub_category']['product_category']
        //dd($delivery_data->toArray());
//        $delivery_locations = DeliveryLocation::all();
//        $customers = Customer::all();
        $pdf = App::make('dompdf.wrapper');
        $viewhtml = View::make('print_delivery_order', [
            'delivery_data' => $delivery_data,
            'units' => $units,
            'customer_type' => $customer_type
            ])->render();
        $pdf->loadHTML($viewhtml);
//         $pdf->loadHTML('print_delivery_order', [
//             'delivery_data' => $delivery_data,
//             'units' => $units,
//             'customer_type' => $customer_type
// //            'delivery_locations' => $delivery_locations,
// //            'customers' => $customers
//         ]);

        /* inventory code */
        $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'delivery_order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }
        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
          |------------------- -----------------------
          | SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
          | -------------------------------------------
         */
        $input_data = $delivery_data['delivery_product'];
        /* check for vat/gst items */
        foreach ($input_data as $product_data) {
            if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] != '0.00') {
                $sms_flag = 1;
            }
        }
        /**/

        $send_sms = Input::get('send_sms');
        $send_whatsapp = Input::get('send_whatsapp');
        if ($sms_flag == 1) {
            // if ($send_sms == 'true' ) {
                $customer_id = $delivery_data->customer_id;
                $customer = Customer::with('manager')->find($customer_id);
                $cust_count = Customer::with('manager')->where('id',$customer_id)->count();
                if ($cust_count > 0) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour DO has been printed as follows\n";
                    foreach ($input_data as $product_data) {
                        $str .= $product_data['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                        $total_quantity = (float)$total_quantity + (float)$product_data->quantity;
                    }
                    $str .= "Vehicle No. " . $delivery_data->vehicle_number . ", Drv No. " . $delivery_data->driver_contact_no . ". \nVIKAS ASSOCIATES";
                    if (App::environment('local')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer->phone_number1;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true && $send_sms == 'true') {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                    // whatsapp code starts here
                    if($send_whatsapp == "true"){
                        // $sid = env('TWILIO_SID');
                        $twilio = new Client(TWILIO_SID, TWILIO_TOKEN);
                        try{
                            $message = $twilio->messages
                            ->create("whatsapp:".$phone_number,
                                [
                                    "body" => $str,
                                    "from" => "whatsapp:+14155238886"
                                ]
                                );
                        }catch(\Exception $e){
                            // $whatsapp_error = ':: Whatsapp Error: Invalid Number';
                            // return redirect()->back()->withError("The phone number is not valid,can't print and send whatsapp message");
                        }
                    }
                    // whatsapp testing code endse here
                }
                if (count((array)$customer['manager']) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created DO for " . $customer->owner_name . " as follows\n";
                    foreach ($input_data as $product_data) {
                        $str .= $product_data['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                        $total_quantity = (float)$total_quantity + (float)$product_data->quantity;
                    }
                    $str .= "Vehicle No. " . $delivery_data->vehicle_number . ", Drv No. " . $delivery_data->driver_contact_no . ". \nVIKAS ASSOCIATES";
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
        // }
        }
// dd(getcwd());
        // Storage::put("/upload/invoices/do/" . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());
        // $pdf->save( "/upload/invoices/do/" . str_replace('/', '-', $date_letter) . '.pdf');
        // chmod( "/upload/invoices/do/" . str_replace('/', '-', $date_letter) . '.pdf', 0777);

        Storage::put(getcwd() . "/upload/invoices/do/" . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());
        $pdf->save(getcwd() . "/upload/invoices/do/" . str_replace('/', '-', $date_letter) . '.pdf');
        chmod(getcwd() . "/upload/invoices/do/" . str_replace('/', '-', $date_letter) . '.pdf', 0777);

        $connection->getConnection()->put('Delivery Order/' . date('d-m-Y') . '/' . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());

//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//        curl_close($ch);
//        $connection->getConnection()->put('Delivery Order/' . date('d-m-Y') . '/' . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());

        //         update sync table
        $tables = ['delivery_order', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        return view('print_delivery_order', compact('delivery_data', 'units','customer_type'));
    }

    /*
     * calculate the pending quantity of the order.
     */



    public function pending_quantity_order($id) {

        $pending_orders = array();
        $all_order_products = AllOrderProducts::where('order_id', $id)->where('order_type', 'delivery_order')->get();
        $pending_quantity = 0;
        $total_quantity = 0;
        foreach ($all_order_products as $products) {
            $p_qty = $products['quantity'] - $products['present_shipping'];
            $temp = array();
            $temp['order_id'] = $id;
            $temp['id'] = $products['id'];
            $temp['quantity'] = $products['quantity'];
            $temp['product_id'] = $products['product_category_id'];
            $temp['total_pending_quantity'] = $p_qty;
            array_push($pending_orders, $temp);
        }
        return $pending_orders;
    }

    /*
     * find product price
     * may be unused methods
     */

    function product_price() {

        $input_data = Input::get("product_id");
        $customer_id = Input::get("customer_id");
        $delivery_location_id = Input::get("delivery_location_id");
        $product_category = ProductCategory::find($input_data);
        $product_price = $product_category->price;
        $product_sub_category = ProductSubCategory::where('product_category_id', $input_data)->first();
        $product_difference = $product_sub_category['difference'];
        $customer_product = CustomerProductDifference::where('customer_id', $customer_id)->where('product_category_id', $input_data)->first();
        $customer_difference = 0;
        if (count((array)$customer_product) > 0) {
            $customer_difference = $customer_product->difference_amount;
        }
        $data_array[] = [
            'product_id' => $input_data,
            'product_price' => $product_price,
            'product_difference' => $product_difference,
            'customer_difference' => $customer_difference
        ];
        echo json_encode(array('data_array' => $data_array));
    }

    /*
     * calculate the pending quantity and total quantity
     */

    function checkpending_quantity($delivery_orders) {
// print_r(date("Y-m-d h:i:s a", time()));
        if (count($delivery_orders) > 0) {
            foreach ($delivery_orders as $key => $del_order) {
                $delivery_order_quantity = 0;
                $delivery_order_present_shipping = 0;
                $pending_order_temp = 0;
                $pending_order = 0;
//                dd($del_order['track_order_product']);
                if (count($del_order['delivery_product']) > 0) {
                    foreach ($del_order['delivery_product'] as $popk => $popv) {

                        if (isset($popv)) {
                            $product_size = $popv['product_sub_category'];
                            //$product_size = ProductSubCategory::find($popv->product_category_id);

                            if ($popv->unit_id == 1) {
                                $delivery_order_quantity = (float)$delivery_order_quantity + (float)$popv->quantity;
                                $delivery_order_present_shipping = (float)$delivery_order_present_shipping + (float)$popv->present_shipping;
                                foreach ($del_order['track_order_product'] as $track_order_product) {
                                    if ($popv->parent == $track_order_product->id) {
                                        $prd_details = $track_order_product;
                                    }
                                }
                                $is_slice = 0;
                                $total_old_shipping = 0;
                                foreach ($del_order['track_do_product'] as $track_do_product) {

                                        if ($track_do_product->parent == $popv->parent && $popv->created_at > $track_do_product->created_at) {
                                            $is_slice++;
                                            $total_old_shipping += $track_do_product->present_shipping;
                                        }
                                }
                                if (isset($prd_details) && $popv->parent>0) {
                                    if ($is_slice == 0)
                                        $pending_order_temp = (float)$prd_details->quantity - (float)$popv->quantity;
                                    else
                                        $pending_order_temp = (float)$prd_details->quantity - (float)$popv->quantity - (float)$total_old_shipping;

                                    if ($pending_order == 0) {
                                        $pending_order = $pending_order_temp;
                                    } else {
                                        $pending_order = (float)$pending_order + (float)$pending_order_temp;
                                    }
                                }
                            } elseif ($popv->unit_id == 2) {
                                $delivery_order_quantity = (float)$delivery_order_quantity + ((float)$popv->quantity * (float)$product_size->weight);
                                $delivery_order_present_shipping = (float)$delivery_order_present_shipping + ((float)$popv->present_shipping * (float)$product_size->weight);
                                foreach ($del_order['track_order_product'] as $track_order_product) {
                                    if ($popv->parent == $track_order_product->id) {
                                        $prd_details = $track_order_product;
                                    }
                                }
                                $is_slice = 0;
                                $total_old_shipping = 0;
                                foreach ($del_order['track_do_product'] as $track_do_product) {

                                    if ($track_do_product->parent == $popv->parent && $popv->created_at > $track_do_product->created_at) {
                                        $is_slice++;
                                        $total_old_shipping += $track_do_product->present_shipping;
                                    }
                                }
                                if (isset($prd_details) && $popv->parent>0) {
                                    $remaining = 0;
                                    if ($prd_details->quantity > $popv->quantity) {
                                        if ($is_slice == 0)
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity;
                                        else
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity - $total_old_shipping;
                                    }
                                    $pending_order_temp = ((float)$remaining * (float)$product_size->weight);
                                    if ($pending_order == 0) {
                                        $pending_order = $pending_order_temp;
                                    } else {
                                        $pending_order = (float)$pending_order + (float)$pending_order_temp;
                                    }
                                }
                            } elseif ($popv->unit_id == 3) {
                                if ($product_size->standard_length == 0)
                                    $product_size->standard_length = 1;
                                    $delivery_order_quantity = (float)$delivery_order_quantity + ((float)($popv->quantity / $product_size->standard_length ) * (float)$product_size->weight);
                                    $delivery_order_present_shipping = (float)$delivery_order_present_shipping + ((float)($popv->present_shipping / $product_size->standard_length ) * (float)$product_size->weight);

                                foreach ($del_order['track_order_product'] as $track_order_product) {
                                    if ($popv->parent == $track_order_product->id) {
                                        $prd_details = $track_order_product;
                                    }
                                }
                                $is_slice = 0;
                                $total_old_shipping = 0;
                                foreach ($del_order['track_do_product'] as $track_do_product) {

                                    if ($track_do_product->parent == $popv->parent && $popv->created_at > $track_do_product->created_at) {
                                        $is_slice++;
                                        $total_old_shipping += $track_do_product->present_shipping;
                                    }
                                }

                                if (isset($prd_details) && $popv->parent>0) {
                                    $remaining = 0;
                                    if ($prd_details->quantity > $popv->quantity) {
                                        if ($is_slice == 0)
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity;
                                        else
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity - (float)$total_old_shipping;
                                    }


                                    $pending_order_temp = ((float)($remaining / $product_size->standard_length ) * (float)$product_size->weight);

                                    if ($pending_order == 0) {
                                        $pending_order = (float)$pending_order_temp;
                                    } else {
                                        $pending_order = (float)$pending_order + (float)$pending_order_temp;
                                    }
                                }
                            } elseif ($popv->unit_id == 4) {
                                $delivery_order_quantity = (float)$delivery_order_quantity + ((float)$popv->quantity * (float)$product_size->weight * (float)$popv->length);
                                $delivery_order_present_shipping = (float)$delivery_order_present_shipping + ((float)$popv->present_shipping * (float)$product_size->weight * (float)$popv->length);
                                foreach ($del_order['track_order_product'] as $track_order_product) {
                                    if ($popv->parent == $track_order_product->id) {
                                        $prd_details = $track_order_product;
                                    }
                                }

                                $is_slice = 0;
                                $total_old_shipping = 0;
                                foreach ($del_order['track_do_product'] as $track_do_product) {

                                    if ($track_do_product->parent == $popv->parent && $popv->created_at > $track_do_product->created_at) {
                                        $is_slice++;
                                        $total_old_shipping += $track_do_product->present_shipping;
                                    }
                                }
                                if (isset($prd_details) && $popv->parent>0) {
                                    $remaining = 0;
                                    if ($prd_details->quantity > $popv->quantity) {
                                        if ($is_slice == 0)
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity;
                                        else
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity - (float)$total_old_shipping;
                                    }
                                    $pending_order_temp = ((float)$remaining * (float)$product_size->weight * (float)$popv->length);
                                    if ($pending_order == 0) {
                                        $pending_order = (float)$pending_order_temp;
                                    } else {
                                        $pending_order = (float)$pending_order + (float)$pending_order_temp;
                                    }
                                }
                            }
                            elseif ($popv->unit_id == 5){
                                if ($product_size->standard_length == 0)
                                    $product_size->standard_length = 1;
                                $delivery_order_quantity = (float)$delivery_order_quantity + ((float)$product_size->weight * (float)$popv->quantity * (float)($popv->length/305));
                                $delivery_order_present_shipping = (float)$delivery_order_present_shipping + ((float)$popv->present_shipping * (float)$product_size->weight *  (float)($popv->length/305));
                                foreach ($del_order['track_order_product'] as $track_order_product) {
                                    if ($popv->parent == $track_order_product->id) {
                                        $prd_details = $track_order_product;
                                    }
                                }
                                $is_slice = 0;
                                $total_old_shipping = 0;
                                foreach ($del_order['track_do_product'] as $track_do_product) {

                                    if ($track_do_product->parent == $popv->parent && $popv->created_at > $track_do_product->created_at) {
                                        $is_slice++;
                                        $total_old_shipping += $track_do_product->present_shipping;
                                    }
                                }

                                if (isset($prd_details) && $popv->parent>0) {
                                    $remaining = 0;
                                    if ($prd_details->quantity > $popv->quantity) {
                                        if ($is_slice == 0)
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity;
                                        else
                                            $remaining = (float)$prd_details->quantity - (float)$popv->quantity - (float)$total_old_shipping;
                                    }

                                    $pending_order_temp = ((float)$remaining * (float)$product_size->weight * (float)($popv->length/305));

                                    if ($pending_order == 0) {
                                        $pending_order = $pending_order_temp;
                                    } else {
                                        $pending_order = (float)$pending_order + (float)$pending_order_temp;
                                    }
                                }
                            }

                        } else {
                            $delivery_order_quantity = 0;
                            $delivery_order_present_shipping = 0;
                            $pending_order = 0;
                        }
                    }
                }
               // dd($pending_order);

                $delivery_orders[$key]['total_quantity'] = $delivery_order_quantity;
                $delivery_orders[$key]['present_shipping'] = $delivery_order_present_shipping;
                $delivery_orders[$key]['pending_order'] = ($pending_order < 0 ? 0 : $pending_order);
            }
        }
        // dd(date("Y-m-d h:i:s a", time()));
        return $delivery_orders;
    }

//    function checkpending_quantity($delivery_orders) {
//
//        if (count((array)$delivery_orders) > 0) {
//            foreach ($delivery_orders as $key => $del_order) {
//                $delivery_order_quantity = 0;
//                $delivery_order_present_shipping = 0;
//                $pending_order_temp = 0;
//                $pending_order = 0;
//                if (count((array)$del_order['delivery_product']) > 0) {
//                    foreach ($del_order['delivery_product'] as $popk => $popv) {
//
//                        if (isset($popv)) {
//                            $product_size = ProductSubCategory::find($popv->product_category_id);
//
////                        $delivery_order_quantity = $delivery_order_quantity + $popv->quantity;
////                            $delivery_order_present_shipping = $delivery_order_present_shipping + $popv->present_shipping;
//
//                            $do = DeliveryOrder::find($popv->order_id);
//                            $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();
////                            if(isset($prd_details[0]))
////                            $pending_order_temp = $prd_details[0]->quantity - $popv->quantity;
////                            else
////                              $pending_order_temp =0;
////
////                            if($pending_order ==0){
////                                $pending_order = $pending_order_temp;
////                            }
////                            else{
////                                $pending_order = $pending_order + $pending_order_temp;
////                            }
//
//
//                            if ($popv->unit_id == 1) {
//                                $delivery_order_quantity = $delivery_order_quantity + $popv->quantity;
//                                $delivery_order_present_shipping = $delivery_order_present_shipping + $popv->present_shipping;
//
//                                $do = DeliveryOrder::find($popv->order_id);
//                                $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();
//
//                                if (isset($prd_details[0])) {
//                                    $pending_order_temp = $prd_details[0]->quantity - $popv->quantity;
//                                    if ($pending_order == 0) {
//                                        $pending_order = $pending_order_temp;
//                                    } else {
//                                        $pending_order = $pending_order + $pending_order_temp;
//                                    }
//                                }
//                            } elseif ($popv->unit_id == 2) {
//                                $delivery_order_quantity = $delivery_order_quantity + ($popv->quantity * $product_size->weight);
//                                $delivery_order_present_shipping = $delivery_order_present_shipping + ($popv->present_shipping * $product_size->weight);
//
//                                $do = DeliveryOrder::find($popv->order_id);
//                                $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();
//
//                                if (isset($prd_details[0])) {
//                                    if ($prd_details[0]->quantity > $popv->quantity)
//                                        $remaining = $prd_details[0]->quantity - $popv->quantity;
//                                    else
//                                        $remaining = 0;
//
//                                    $pending_order_temp = ($remaining * $product_size->weight);
//
//                                    if ($pending_order == 0) {
//                                        $pending_order = $pending_order_temp;
//                                    } else {
//                                        $pending_order = $pending_order + $pending_order_temp;
//                                    }
//                                }
//                            } elseif ($popv->unit_id == 3) {
//
//                                $delivery_order_quantity = $delivery_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
//                                $delivery_order_present_shipping = $delivery_order_present_shipping + (($popv->present_shipping / $product_size->standard_length ) * $product_size->weight);
//
//                                $do = DeliveryOrder::find($popv->order_id);
//
//                                $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();
//
//                                if (isset($prd_details[0])) {
//                                    if ($prd_details[0]->quantity > $popv->quantity)
//                                        $remaining = $prd_details[0]->quantity - $popv->quantity;
//                                    else
//                                        $remaining = 0;
//                                    $pending_order_temp = (($remaining / $product_size->standard_length ) * $product_size->weight);
//
//                                    if ($pending_order == 0) {
//                                        $pending_order = $pending_order_temp;
//                                    } else {
//                                        $pending_order = $pending_order + $pending_order_temp;
//                                    }
//                                }
//                            }
//                        } else {
//                            $delivery_order_quantity = 0;
//                            $delivery_order_present_shipping = 0;
//                            $pending_order = 0;
//                        }
//                    }
//                }
//
//
//
//                $delivery_orders[$key]['total_quantity'] = $delivery_order_quantity;
//                $delivery_orders[$key]['present_shipping'] = $delivery_order_present_shipping;
//                $delivery_orders[$key]['pending_order'] = ($pending_order < 0 ? 0 : $pending_order);
//            }
//        }
//        return $delivery_orders;
//    }

    /* Function used to export dilivery order list based on order status */
    
    public function exportDeliveryOrderBasedOnStatus() {
        $data = Input::all();
        if ($data['delivery_order_status'] == 'Inprocess') {
            $excel_name = '-InProcess-' . date('dmyhis');
        } elseif ($data['delivery_order_status'] == 'Delivered') {
            $excel_name = '-Delivered-' . date('dmyhis');
        }
        return Excel::download(new DOExport, 'DeliveryOrder'.$excel_name.'.xls');
    }

    public function exportDeliveryOrderBasedOnStatus_old() {
        $data = Input::all();
        set_time_limit(0);
        ini_set('max_execution_time', 1000);
        if ($data['delivery_order_status'] == 'Inprocess') {
            $delivery_order_status = 'pending';
            $excel_sheet_name = 'Inprocess';
            $excel_name = 'DeliveryOrder-InProcess-' . date('dmyhis');
        } elseif ($data['delivery_order_status'] == 'Delivered') {
            $delivery_order_status = 'completed';
            $excel_sheet_name = 'Delivered';
            $excel_name = 'DeliveryOrder-Delivered-' . date('dmyhis');
        }
        if (Auth::user()->role_id == 9){
            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->where('del_boy', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('del_boy', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            } else {
                $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                        ->where('del_boy', Auth::user()->id)
                        ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        }
        elseif (Auth::user()->role_id == 8){
            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->where('del_supervisor', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('del_supervisor', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            } else {
                $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                        ->where('del_supervisor', Auth::user()->id)
                        ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        }else {
            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            } else {
                $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                        ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        }
        if (count((array)$delivery_order_objects) == 0) {
            return redirect::back()->with('error', 'No data found');
        } else {
            $units = Units::all();
            $delivery_locations = DeliveryLocation::all();
            $customers = Customer::all();

            Excel::create($excel_name, function($excel) use($delivery_order_objects, $units, $delivery_locations, $customers, $excel_sheet_name) {
                $excel->sheet('DeliveryOrder-' . $excel_sheet_name, function($sheet) use($delivery_order_objects, $units, $delivery_locations, $customers) {
                    $sheet->loadView('excelView.delivery_order', array('delivery_order_objects' => $delivery_order_objects, 'units' => $units, 'delivery_locations' => $delivery_locations, 'customers' => $customers));
                });
            })->export('xls');
        }
    }

    public function del_boy_reload(Request $request){

        $roleid = $request->role_id;
        $delivery_boy = $request->delivery_boy;
        $date = new Carbon\Carbon;
        // $date->modify('-115 minutes');
        $formatted_date = $date->format('Y-m-d 00:00:00');
        // dd($formatted_date);

        if($roleid == 0 || $roleid == 8 ){
            if($roleid == 0) {
                $type = "del_boy";
                $all = \App\User::where('role_id',9)
                            ->orderBy('id', 'DESC')
                            ->get();
                $new = \App\User::where('role_id',9)->where('is_active',"1")->where('updated_at','>',$formatted_date)
                            ->orderBy('id', 'DESC')
                            ->get();
            } 
        }
        if($roleid == 8) {
            $type = "del_boy";
            $all = \App\User::where('role_id',9)
                            ->orderBy('id', 'DESC')
                            ->get();
            $new = \App\User::where('role_id',9)->where('is_active',"1")->where('updated_at','>',$formatted_date)
                        ->orderBy('id', 'DESC')
                        ->get();
        }
        echo json_encode(array($all,$new));
    }

    public function supervisor_reload(Request $request){

        $roleid = $request->role_id;
        $supervisor_id = $request->supervisor_id;
        $date = new Carbon\Carbon;
        // $date->modify('-115 minutes');
        $formatted_date = $date->format('Y-m-d 00:00:00');
        if($roleid == 0 || $roleid == 2 ){
            if($roleid == 0) {
                $type = "del_supervisor";
                $all = \App\User::where('role_id',8)
                            ->orderBy('id', 'DESC')
                            ->get();
                $new = \App\User::where('role_id',8)->where('is_active',"1")->where('updated_at','>',$formatted_date)
                            ->orderBy('id', 'DESC')
                            ->get();
            } 
           
        }
        if($roleid == 2) {
            $type = "del_supervisor";
            $all = \App\User::where('role_id',8)
                            ->orderBy('id', 'DESC')
                            ->get();
            $new = \App\User::where('role_id',8)->where('is_active',"1")->where('updated_at','>',$formatted_date)
                        ->orderBy('id', 'DESC')
                        ->get();
        }
        echo json_encode(array($all,$new));
    }

    public function get_data() {
        $product_sub_category = ProductSubCategory::with('product_category')->get();
        $customer_product_difference = CustomerProductDifference::all();
        //$customers = Customer::with('delivery_location')->where('tally_name', '<>', '')->orderBy('owner_name', 'ASC')->get();
//         foreach ($customers as $customer) {
//                $customer_array[] = [
//                    'value' =>$customer->tally_name,
//                    'id' => $customer->id,
//                    'delivery_location_id' => $customer->delivery_location_id,
//                    'location_difference' =>  $customer['deliverylocation']->difference,
//                ];
//            }
        echo json_encode(array(
            'product_sub_category' => $product_sub_category,
            'customer_product_difference' => $customer_product_difference,
                //'customers' => $customers,
        ));
    }

}
