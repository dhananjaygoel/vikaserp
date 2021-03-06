<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseAdviseExport;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Customer;
use Input;
use Auth;
use App\PurchaseAdvise;
use App\PurchaseProducts;
use App\DeliveryLocation;
use DB;
use App\User;
use Hash;
use Config;
use App;
use App\Units;
use App\Http\Requests\StorePurchaseAdvise;
use Redirect;
use Validator;
use DateTime;
use App\ProductSubCategory;
use App\PurchaseOrder;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Labour;
use App\LoadedBy;
use Twilio\Rest\Client;

class PurchaseAdviseController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        define('TWILIO_SID', Config::get('smsdata.twilio_sid'));
        define('TWILIO_TOKEN', Config::get('smsdata.twilio_token'));
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the Purchase Advices.
     */
    public function index(StorePurchaseAdvise $request) {
        // echo 'hfdkjhdf';
        // exit;
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');
        $session_sort_type_order = Session::get('order-sort-type');
        // $qstring_sort_type_order = Input::get('purchaseaAdviseFilter');

        if (Input::get('advice_status') != "") {
            $qstring_sort_type_order = Input::get('advice_status');
        } elseif (Input::get('purchaseaAdviseFilter') != "") {
            $qstring_sort_type_order = Input::get('purchaseaAdviseFilter');
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

        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            if ($qstring_sort_type_order == 'in_process' || $qstring_sort_type_order == 'In_process') {
                $q->where('advice_status', 'in_process');
            } elseif ($qstring_sort_type_order == 'delivered' || $qstring_sort_type_order == 'Delivered') {
                $q->where('advice_status', 'delivered');
            }
        } else {
            $q->where('advice_status', 'in_process');
        }

        $search_dates = [];
        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $q->where('updated_at', 'like', $date1 . '%');
            } else {
                $q->where('updated_at', '>=', $date1);
                $q->where('updated_at', '<=', $date2 . ' 23:59:59');
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        }


        $purchase_advise = $q->orderBy('created_at', 'desc')->paginate(20);

        $pending_orders = $this->checkpending_quantity($purchase_advise);
        $purchase_advise->setPath('purchaseorder_advise');

        $parameters = parse_url($request->fullUrl());
        $parameters = isset($parameters['query']) ? $parameters['query'] : '';
        Session::put('parameters', $parameters);

        return View::make('purchase_advise', array('purchase_advise' => $purchase_advise, 'pending_orders' => $pending_orders, 'search_dates' => $search_dates));
    }

    /* Function used to export order details in excel */

    public function exportPurchaseAdviseBasedOnStatus() {
        $data = Input::all();
        if ($data['purchaseaAdviseFilter'] == 'In_process' || $data['purchaseaAdviseFilter'] == 'In_process') {
            $excel_name = '-Pending-' . date('dmyhis');
        } elseif ($data['purchaseaAdviseFilter'] == 'Delivered' || $data['purchaseaAdviseFilter'] == 'delivered') {
            $excel_name = '-Completed-' . date('dmyhis');
        }

        return Excel::download(new PurchaseAdviseExport, 'Purchase-Advise'.$excel_name.'.xls');

            // Excel::create($excel_name, function($excel) use($order_objects, $units, $delivery_location, $customers, $excel_sheet_name) {
            //     $excel->sheet('Purchase-Order-' . $excel_sheet_name, function($sheet) use($order_objects, $units, $delivery_location, $customers) {
            //         $sheet->loadView('excelView.purchase_advise', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
            //     });
            // })->export('xls');
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }


        if (Auth::user()->role_id == 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $customers = Customer::where('customer_status', '=', 'permanent')->orderBy('tally_name', 'ASC')->get();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $units = Units::all();
        return View::make('add_purchase_advise', array('customers' => $customers, 'delivery_locations' => $delivery_locations, 'units' => $units));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseAdvise $request) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $input_data = Input::all();
        if (Session::has('forms_purchase_advise')) {
            $session_array = Session::get('forms_purchase_advise');
            if (count((array)$session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('success') == 'Purchase advise added successfully'){
                        return redirect('purchaseorder_advise')->with('success', 'Purchase advise added successfully');
                    }else{
                        return Redirect::back()->with('flash_message_error', 'This purchase advise is already saved. Please refresh the page');
                    }
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_purchase_advise', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_purchase_advise', $forms_array);
        }
        $rules = array(
            'bill_date' => 'required',
            'vehicle_number' => 'required',
        );
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $i = 0;
        $j = count((array)$input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->withInput()->with('error', 'Please insert product details');
        }
        if ($input_data['supplier_status'] == "new") {
            $validator = Validator::make($input_data, Customer::$new_supplier_rules);
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
        } elseif ($input_data['supplier_status'] == "existing") {
            $validator = Validator::make($input_data, Customer::$existing_supplier_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['supplier_id'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['bill_date']);
        $date = date("Y/m/d", strtotime(str_replace('/', '-', $date_string)));
        $datetime = new DateTime($date);
        $date_string2 = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_delivery_date']);
        $date2 = date("Y/m/d", strtotime(str_replace('-', '/', $date_string2)));
        $datetime2 = new DateTime($date2);
        $purchase_advise_array = array();
        $purchase_advise_array['purchase_advice_date'] = $datetime->format('Y-m-d');
        $purchase_advise_array['supplier_id'] = $customer_id;
        $purchase_advise_array['created_by'] = Auth::id();
        $purchase_advise_array['expected_delivery_date'] = $datetime2->format('Y-m-d');
        $purchase_advise_array['total_price'] = $input_data['total_price'];
        $purchase_advise_array['remarks'] = $input_data['remarks'];
        $purchase_advise_array['vehicle_number'] = $input_data['vehicle_number'];
        $purchase_advise_array['order_for'] = $input_data['order_for'];
        $purchase_advise_array['advice_status'] = 'in_process';
        if (isset($input_data['is_vat']) && $input_data['is_vat'] == "exclude_vat") {
            $purchase_advise_array['vat_percentage'] = $input_data['vat_percentage'];
        }
        if (isset($input_data['delivery_location_id']) && $input_data['delivery_location_id'] == "-1") {
            $purchase_advise_array['delivery_location_id'] = 0;
            $purchase_advise_array['other_location'] = $input_data['other_location_name'];
            $purchase_advise_array['other_location_difference'] = $input_data['other_location_difference'];
        } else {
            $purchase_advise_array['delivery_location_id'] = $input_data['delivery_location_id'];
            $purchase_advise_array['other_location'] = '';
            $purchase_advise_array['other_location_difference'] = '';
        }
        $purchase_advise = PurchaseAdvise::create($purchase_advise_array);
        $purchase_advise_id = $purchase_advise->id;
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $purchase_advise_products = [
                    'purchase_order_id' => $purchase_advise_id,
                    'order_type' => 'purchase_advice',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                    'present_shipping' => $product_data['quantity'],
                    'from' => isset($product_data['purchase']) ? $product_data['purchase'] : ''
                ];
                PurchaseProducts::create($purchase_advise_products);
            }
        }
        //         update sync table
        $tables = ['customers', 'purchase_order', 'all_purchase_products', 'purchase_advice'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        return redirect('purchaseorder_advise')->with('success', 'Purchase advise added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id = "") {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id == 5 | $id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.purchase_product_details','purchase_order')->find($id);
        if (count((array)$purchase_advise) < 1) {
            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advise not found');
        }
        return View::make('view_purchase_advice', array('purchase_advise' => $purchase_advise,'customers'=> $customers));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (Auth::user()->role_id == 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.purchase_product_details','purchase_order')->find($id);
        if (count((array)$purchase_advise) < 1) {
            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advise not found');
        }
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $units = Units::all();
        return View::make('edit_purchase_advise', array('locations' => $locations, 'units' => $units, 'purchase_advise' => $purchase_advise));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $input_data = Input::all();
        $sms_flag = 1;
        
        if (Session::has('forms_edit_purchase_advise')) {
            $session_array = Session::get('forms_edit_purchase_advise');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    if(Session::has('success') == 'Purchase advise updated successfully'){
                        return redirect('purchaseorder_advise')->with('success', 'Purchase advise updated successfully');
                    }else{
                        return Redirect::back()->with('flash_message_error', 'This purchase advise is already updated. Please refresh the page');
                    }
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_edit_purchase_advise', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_purchase_advise', $forms_array);
        }
        $rules = array(
            'vehicle_number' => 'required',
        );
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $purchase_advise = PurchaseAdvise::find($id);
        $purchase_advise->update(
                array(
                    'remarks' => $input_data['remarks'],
                    'vehicle_number' => $input_data['vehicle_number']
        ));
        if (isset($input_data['tcs_applicable'])){
            $update_purchase_advice = PurchaseAdvise::where('id', $id)->update([
                'tcs_applicable' => $input_data['tcs_applicable'] == 'yes' ? 1 : 0,
                'tcs_percentage' => isset($input_data['tcs_percentage']) ? $input_data['tcs_percentage'] : '0.1',
            ]);
        }
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                if (isset($product_data['purchase_product_id']) && $product_data['purchase_product_id'] != '') {
                    $purchase_product = PurchaseProducts::find($product_data['purchase_product_id']);
                    $purchase_product->update(
                            [
                                'present_shipping' => $product_data['present_shipping'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'actual_pieces' => $product_data['actual_pieces'],
                            ]
                    );
                } else {
                    $purchase_advise_products = [
                        'purchase_order_id' => $id,
                        'order_type' => 'purchase_advice',
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'length' => isset($product_data['length']) ? $product_data['length'] : 0,
                        'actual_pieces' => $product_data['actual_pieces'],
                        'quantity' => $product_data['present_shipping'],
                        'present_shipping' => $product_data['present_shipping'],
                        'actual_pieces' => $product_data['actual_pieces'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark'],
                        'present_shipping' => $product_data['present_shipping'],
                        'from' => $product_data['purchase']
                    ];
                    PurchaseProducts::create($purchase_advise_products);
                }
            }
        }
//        $purchase_advice_prod = PurchaseProducts::where('order_type', '=', 'purchase_advice')->where('purchase_order_id', '=', $id)->first();
//        $purchase_advise->updated_at = $purchase_advice_prod->updated_at;
        $purchase_advise->save();

        /* inventory code */
        $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_advice')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
         * ------------------- ------------------------
         * SEND SMS TO Customer FOR edit PURCHASE ADVISE
         * --------------------------------------------
         */
        $purchase_advise = PurchaseAdvise::with('supplier', 'purchase_products.purchase_product_details', 'purchase_products.unit', 'location')->find($id);
        $input_data = $purchase_advise['purchase_products'];

        $send_sms = Input::get('send_msg');
        $send_whatsapp = Input::get('send_whatsapp');
        $customer_id = $purchase_advise->supplier_id;
        $customer = Customer::with('manager')->find($customer_id);
        $cust_count = Customer::with('manager')->where('id',$customer_id)->count();
        $total_quantity = 0;
        $product_string = '';
        $i = 1;
        if ($sms_flag == 1) {
            $send_msg = new WelcomeController();
            foreach ($purchase_advise['purchase_products'] as $product_data) {
                if ($product_data['purchase_product_details']->alias_name != "") {
                    if ($product_data['unit_id'] == 1) {
                        $total_quantity = (float)$product_data['present_shipping'];
                    }
                    if ($product_data['unit_id'] == 2) {
                        $total_quantity = (float)$product_data['present_shipping'] * (float)$product_data['purchase_product_details']->weight;
                    }
                    if ($product_data['unit_id'] == 3) {
                        $total_quantity = ((float)$product_data['present_shipping'] / (float)isset($product_data['purchase_product_details']->standard_length)?$product_data['purchase_product_details']->standard_length:1 ) * (float)$product_data['purchase_product_details']->weight;
                    }
                    if ($product_data['unit_id'] == 4) {
                        $total_quantity = ((float)$product_data['present_shipping'] * (float)$product_data['purchase_product_details']->weight * (float)$product_data['length']);
                    }
                    if ($product_data['unit_id'] == 5) {
                        $total_quantity = ((float)$product_data['present_shipping'] * (float)$product_data['purchase_product_details']->weight * ((float)$product_data['length'] / 305));
                    }
                    $product_string .= $i++ . ") " . $product_data['purchase_product_details']->alias_name . ", " . round((float)$total_quantity,2) . "KG, ₹". $product_data['price'] . " ";
                }
            }
            if ($cust_count > 0) {
                $str = "Dear Customer,\n\nYour purchase advice has been updated.\n\nCustomer Name: ".ucwords($customer->owner_name)."\nPurchase Advice No: #".$id."\nOrder Date: ".date("j F, Y")."\n\nUpdated Products:\n".$product_string."\nVehicle No: ". (isset($purchase_advise->vehicle_number)?$purchase_advise->vehicle_number:'N/A') . "\n\nVIKAS ASSOCIATES.";   
                if (App::environment('local')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                   $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "yes") {
                    $send_msg->send_sms($phone_number,$msg);
                }
                if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "yes"){
                    $send_msg->send_whatsapp($phone_number,$str);                    
                }
            }
            if (count((array)$customer['manager']) > 0) {
                $str = "Dear Manager,\n\nPurchase advice has been updated.\n\nCustomer Name: ".ucwords($customer->owner_name)."\nPurchase Advice No: #".$id."\nOrder Date: ".date("j F, Y")."\n\nUpdated Products:\n".$product_string."\nVehicle No: ". (isset($purchase_advise->vehicle_number)?$purchase_advise->vehicle_number:'N/A') . "\n\nVIKAS ASSOCIATES.";   
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "yes") {
                    $send_msg->send_sms($phone_number,$msg);
                }
                if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "yes"){
                    $send_msg->send_whatsapp($phone_number,$str);                    
                }
            }
        }
        //         update sync table
        $tables = ['customers', 'all_purchase_products', 'purchase_advice'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */

        $parameter = Session::get('parameters');
        $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';

        PurchaseAdvise::where('id',$id)->update(['is_editable'=>1]);


        return redirect('purchaseorder_advise' . $parameters)->with('success', 'Purchase advise updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        $order_sort_type = $formFields['order_sort_type'];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if ($password == '') {
            return Redirect::to('purchaseorder_advise')->with('error', 'Please enter your password');
        }
        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            /* inventory code */
            $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_advice')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }

            PurchaseAdvise::find($id)->delete();
            PurchaseProducts::where('purchase_order_id', '=', $id)->where('order_type', '=', 'purchase_advice')->delete();
            Session::put('order-sort-type', $order_sort_type);

            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);
            //         update sync table
            $tables = ['customers', 'all_purchase_products', 'purchase_advice'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */

            return array('message' => 'success');
        } else {
            return array('message' => 'failed');
        }
    }

    /*
     * store purchase advice data into database
     *
     */

    public function store_advise() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $input_data = Input::all();
        if (Session::has('forms_purchase_advise')) {
            $session_array = Session::get('forms_purchase_advise');
            if (count((array)$session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('flash_message') == 'Purchase advice details successfully added.'){
                        return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advice details successfully added.');
                    }else{
                        return Redirect::back()->with('flash_message_error', 'This purchase advise is already saved. Please refresh the page');
                    }
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_purchase_advise', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_purchase_advise', $forms_array);
        }
        $validator = Validator::make($input_data, PurchaseAdvise::$store_purchase_validation);



        if ($validator->passes()) {
            $orderp = PurchaseOrder::where('id',$input_data['id'])->first();
            $date_string = preg_replace('~\x{00a0}~u', '', $input_data['bill_date']);
            $date = date("Y/m/d", strtotime(str_replace('/', '-', $date_string)));
            $datetime = new DateTime($date);
            $bill_date = $datetime->format('Y-m-d');
            $add_purchase_advice_array = [
                'supplier_id' => $input_data['supplier_id'],
                'created_by' => Auth::id(),
                'purchase_advice_date' => $bill_date,
                'delivery_location_id' => $input_data['delivery_location_id'],
                'other_location' => $input_data['other_location'],
                'other_location_difference' => $input_data['other_location_difference'],
                'vat_percentage' => $input_data['vat_percentage'],
                'expected_delivery_date' => $input_data['expected_delivery_date'],
                'remarks' => $input_data['grand_remark'],
                'advice_status' => 'in_process',
                'vehicle_number' => $input_data['vehicle_number'],
                'purchase_order_id' => $input_data['id'],
                'is_editable'=>$orderp->is_editable
            ];
            $add_purchase_advice = PurchaseAdvise::create($add_purchase_advice_array);
            $purchase_advice_id = DB::getPdo()->lastInsertId();
            if (isset($input_data['tcs_applicable'])){
                $add_purchase_advice = PurchaseAdvise::where('id', $purchase_advice_id)->update([
                    'tcs_applicable' => $input_data['tcs_applicable'] == 'yes' ? 1 : 0,
                    'tcs_percentage' => isset($input_data['tcs_percentage']) ? $input_data['tcs_percentage'] : '0.1',
                ]);
            }
            $purchase_advice_products = array();
            $total_quantity = '';
            $total_present_shipping = '';
            foreach ($input_data['product'] as $product_data) {
                if (($product_data['id'] != "")) {
                    if (isset($product_data['purchase']) && $product_data['purchase'] != "") {
                        if ($product_data['present_shipping'] != "") {
                            $purchase_advice_products = [
                                'purchase_order_id' => $purchase_advice_id,
                                'product_category_id' => $product_data['id'],
                                'unit_id' => $product_data['units'],
                                'length' => isset($product_data['length']) ? $product_data['length'] : 0,
                                'actual_pieces' => $product_data['actual_pieces'],
                                'quantity' => $product_data['present_shipping'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice',
                                'from' => $input_data['id'],
                                'present_shipping' => $product_data['present_shipping'],
                                'parent' => $product_data['key']
                            ];
                        } elseif ($product_data['present_shipping'] == "") {
                            $purchase_advice_products = [
                                'purchase_order_id' => $purchase_advice_id,
                                'product_category_id' => $product_data['id'],
                                'unit_id' => $product_data['units'],
                                'length' => isset($product_data['length']) ? $product_data['length'] : 0,
                                'actual_pieces' => $product_data['actual_pieces'],
                                'quantity' => $product_data['quantity'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice',
                                'from' => $input_data['id'],
                                'parent' => $product_data['key']
                            ];
                        }
                    } else {
                        if ($product_data['present_shipping'] != "") {
                            $purchase_advice_products = [
                                'purchase_order_id' => $purchase_advice_id,
                                'product_category_id' => $product_data['id'],
                                'unit_id' => $product_data['units'],
                                'length' => isset($product_data['length']) ? $product_data['length'] : 0,
                                'actual_pieces' => $product_data['actual_pieces'],
                                'quantity' => $product_data['present_shipping'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice',
                                'present_shipping' => $product_data['present_shipping']
                            ];
                        } elseif ($product_data['present_shipping'] == "") {
                            $purchase_advice_products = [
                                'purchase_order_id' => $purchase_advice_id,
                                'product_category_id' => $product_data['id'],
                                'unit_id' => $product_data['units'],
                                'length' => isset($product_data['length']) ? $product_data['length'] : 0,
                                'actual_pieces' => $product_data['actual_pieces'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice'
                            ];
                        }
                    }
                    if (isset($product_data['purchase']) && $product_data['purchase'] == 'purchase_order') {
                        $total_quantity = (float)$total_quantity + (float)$product_data['quantity'];
                        $total_present_shipping = (float)$total_present_shipping + (float)$product_data['present_shipping'];
                    }
                    $add_purchase_advice_products = PurchaseProducts::create($purchase_advice_products);
                }
            }

//            if ($total_present_shipping == $total_quantity || $total_present_shipping > $total_quantity) {
//                PurchaseOrder::where('id', '=', $input_data['id'])->update(array(
//                    'order_status' => 'completed'
//                ));
//            }

            $PO = PurchaseOrder::where('id', $input_data['id'])->get();
            $this->quantity_calculation_po($PO);

            /* inventory code */
            $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $purchase_advice_id)->where('order_type', 'purchase_advice')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }

            if (!empty($product_category_ids)) {
                $calc = new InventoryController();
                $calc->inventoryCalc($product_category_ids);
            }

            //         update sync table
            $tables = ['customers', 'purchase_order', 'all_purchase_products', 'purchase_advice'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */

            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advice details successfully added.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /*
     * Find pending purchase advice
     *
     */

    public function pending_purchase_advice() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $filteron = "";
        $filterby = "";
        $filteron = Input::get('filteron');
        $filterby = Input::get('filterby');
        if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
            $pending_advise = PurchaseAdvise::where('advice_status', '=', "in_process")
                            ->orderby($filteron, $filterby)->with('purchase_products', 'supplier', 'party')->paginate(20);
        } else {
            $pending_advise = PurchaseAdvise::where('advice_status', '=', "in_process")
                            ->with('purchase_products', 'supplier', 'party')->paginate(20);
        }
        $pending_advise->setPath('pending_purchase_advice');
        return View::make('pending_purchase_advice', array('pending_advise' => $pending_advise));
    }

    public function purchaseorder_advise_challan($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (Auth::user()->role_id == 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.purchase_product_details','purchase_order')->find($id);
        if (count((array)$purchase_advise) < 1) {
            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advise not found');
        }
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $units = Units::all();
        $labours = Labour::where('type', '<>', 'sale')->get();
        // $loaders = LoadedBy::where('type', '<>', 'sale')->get();
        $loaders = User::where('role_id', 8)->orWhere('role_id', 9)->orWhere('role_id', 0)->get();
        return view('purchaseorder_advise_challan', compact('purchase_advise', 'locations', 'units', 'labours', 'loaders'));
    }

    public function print_purchase_advise($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (Input::has('vehicle_number')) {
            $vehicle_number = Input::get('vehicle_number');
        }
        $current_date = date("/m/d/");
        $pr_a = PurchaseAdvise::where('id','=',$id)->with('purchase_order_single')->first();
        $vat_status = (isset($pr_a->purchase_order_single->vat_percentage)?$pr_a->purchase_order_single->vat_percentage:0);

        if($vat_status == "" OR $vat_status == null){
            $date_letter = 'PA' . $current_date . $id."A";
        }
        else{
            $date_letter = 'PA' . $current_date . $id.'P';
        }

        if (isset($vehicle_number) && $vehicle_number!= "") {
            PurchaseAdvise::where('id', '=', $id)->update(array(
                'serial_number' => $date_letter,
                'vehicle_number' => $vehicle_number,
            ));
        }else{
            PurchaseAdvise::where('id', '=', $id)->update(array(
                'serial_number' => $date_letter
            ));
        }

        $purchase_advise = PurchaseAdvise::with('supplier', 'purchase_products.purchase_product_details', 'purchase_products.unit', 'location')->find($id);
        $sms_flag = 1;

        /*
         * ------------------- ------------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE ADVISE
         * --------------------------------------------
         */
        $input_data = $purchase_advise['purchase_products'];  
        $total_quantity = 0;
        $product_string='';
        $i = 1;
        $send_sms = Input::get('send_sms');
        $send_whatsapp = Input::get('send_whatsapp');
        if ($sms_flag == 1) {
            $customer_id = $purchase_advise->supplier_id;
            $customer = Customer::with('manager')->find($customer_id);
            $cust_count = Customer::with('manager')->where('id',$customer_id)->count();
            $send_msg = new WelcomeController();
            foreach ($purchase_advise['purchase_products'] as $product_data) {
                if ($product_data['purchase_product_details']->alias_name != "") {
                    if ($product_data['unit_id'] == 1) {
                        $total_quantity = (float)$product_data['present_shipping']; 
                    }
                    if ($product_data['unit_id'] == 2) {
                        $total_quantity = (float)$product_data['present_shipping'] * (float)$product_data['purchase_product_details']->weight;
                    }
                    if ($product_data['unit_id'] == 3) {
                        $total_quantity = ((float)$product_data['present_shipping'] / (float)isset($product_data['purchase_product_details']->standard_length)?$product_data['purchase_product_details']->standard_length:1 ) * (float)$product_data['purchase_product_details']->weight;
                    }
                    if ($product_data['unit_id'] == 4) {
                        $total_quantity = ((float)$product_data['present_shipping'] * (float)$product_data['purchase_product_details']->weight * (float)$product_data['length']);
                    }
                    if ($product_data['unit_id'] == 5) {
                        $total_quantity = ((float)$product_data['present_shipping'] * (float)$product_data['purchase_product_details']->weight * ((float)$product_data['length'] / 305));
                    }
                    $product_string .= $i++ . ") " . $product_data['purchase_product_details']->alias_name . ", " . round((float)$total_quantity,2) . "KG, ₹". $product_data['price'] . " ";
                }
            }
            if ($cust_count > 0) {
                $str = "Dear Customer,\n\nYour purchase advice has been printed.\n\nCustomer Name: ".ucwords($customer->owner_name)."\nPurchase Advice No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: ". (isset($purchase_advise->vehicle_number)?$purchase_advise->vehicle_number:'N/A') . "\n\nVIKAS ASSOCIATES.";   
                if (App::environment('local')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "true") {
                    $send_msg->send_sms($phone_number,$msg);
                }
                if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                    $send_msg->send_whatsapp($phone_number,$str);                    
                }
            }

            if (count((array)$customer['manager']) > 0) {
                $str = "Dear Manager,\n\nPurchase advice has been printed.\n\nCustomer Name: ".ucwords($customer->owner_name)."\nPurchase Advice No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: ". (isset($purchase_advise->vehicle_number)?$purchase_advise->vehicle_number:'N/A') . "\n\nVIKAS ASSOCIATES.";   
                if (App::environment('local')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "true") {
                    $send_msg->send_sms($phone_number,$msg);
                }
                if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                    $send_msg->send_whatsapp($phone_number,$str);                    
                }
            }
        }

        /* inventory code */
        $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_advice')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        //         update sync table
        $tables = ['customers', 'all_purchase_products', 'purchase_advice'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */

        return view('print_purchase_advise', compact('purchase_advise'));
    }

    /**
     * This function returns all the pending quantity of purchase advise
     */
    function checkpending_quantity($purchase_advise) {
        $pending_orders = array();
        if (count((array)$purchase_advise) > 0) {

            foreach ($purchase_advise as $key => $del_order) {
                $purchase_order_quantity = 0;
                if (count((array)$del_order['purchase_products']) > 0) {

                    foreach ($del_order['purchase_products'] as $popk => $popv) {
                        $product_size = $popv['product_sub_category'];
                        //$product_size = ProductSubCategory::find($popv->product_category_id);

                        if ($popv->unit_id == 1) {
                            $purchase_order_quantity = (float)$purchase_order_quantity + (float)$popv->quantity;
                        }
                        if ($popv->unit_id == 2) {
                            $purchase_order_quantity = (float)$purchase_order_quantity + (float)($popv->quantity * (float)$product_size->weight);
                        }
                        if ($popv->unit_id == 3) {
                            $purchase_order_quantity = (float)$purchase_order_quantity + ((float)($popv->quantity / isset($product_size->standard_length)?$product_size->standard_length:1 ) * (float)$product_size->weight);
                        }
                        if ($popv->unit_id == 4) {
                            $purchase_order_quantity = (float)$purchase_order_quantity + (float)($popv->quantity * $product_size->weight * $popv->length);
                        }
                        if ($popv->unit_id == 5) {
                            $purchase_order_quantity = (float)$purchase_order_quantity + (float)($popv->quantity * $product_size->weight * (float)($popv->length/305));
                        }
                    }
                }
                $purchase_advise[$key]['total_quantity'] = $purchase_order_quantity;
            }
        }
        return $pending_orders;
    }

    /*
     * First get all orders
     * 1 if delevery order is generated from order then only calculate
     * pending order from delivery order
     * else take order details in pending order
     * 2 if delivery order is generated then take those products only
     * which has there in order rest skip
     */

    function quantity_calculation_po($purchase_orders) {

        foreach ($purchase_orders as $key => $order) {

            $purchase_order_quantity = 0;
            $purchase_order_advise_quantity = 0;
            //$purchase_order_advise_products = PurchaseProducts::where('from', '=', $order->id)->get();
            $purchase_order_advise_products = $order['purchase_product_has_from'];
            if (count((array)$purchase_order_advise_products) > 0) {
                foreach ($purchase_order_advise_products as $poapk => $poapv) {
                    $product_size = $poapv['product_sub_category'];
                    //$product_size = ProductSubCategory::find($poapv->product_category_id);
                    if ($poapv->unit_id == 1) {
                        $purchase_order_advise_quantity = (float)$purchase_order_advise_quantity + (float)$poapv->quantity;
                    }
                    if ($poapv->unit_id == 2) {
                        $purchase_order_advise_quantity = (float)$purchase_order_advise_quantity + (float)$poapv->quantity * (float)$product_size->weight;
                    }
                    if ($poapv->unit_id == 3) {
                        $purchase_order_advise_quantity = (float)$purchase_order_advise_quantity + (float)($poapv->quantity / isset($product_size->standard_length)?$product_size->standard_length:1 ) * (float)$product_size->weight;
                    }
                    if ($poapv->unit_id == 4) {
                        $purchase_order_advise_quantity = (float)$purchase_order_advise_quantity + (float)($poapv->quantity * $product_size->weight * $poapv->length);
                    }
                    if ($poapv->unit_id == 5) {
                        $purchase_order_advise_quantity = (float)$purchase_order_advise_quantity + (float)($poapv->quantity * $product_size->weight * (float)($poapv->length/305));
                    }
                }
            }

            if (count((array)$order['purchase_products']) > 0) {
                foreach ($order['purchase_products'] as $popk => $popv) {
                    $product_size = $popv['product_sub_category'];
                    //$product_size = ProductSubCategory::find($popv->product_category_id);
                    if ($popv->unit_id == 1) {
                        $purchase_order_quantity = (float)$purchase_order_quantity + (float)$popv->quantity;
                    }
                    if ($popv->unit_id == 2) {
                        $purchase_order_quantity = (float)$purchase_order_quantity + (float)($popv->quantity * $product_size->weight);
                    }
                    if ($popv->unit_id == 3) {
                        $purchase_order_quantity = (float)$purchase_order_quantity + ((float)($popv->quantity / isset($product_size->standard_length)?$product_size->standard_length:1 ) * (float)$product_size->weight);
                    }
                    if ($popv->unit_id == 4) {
                        $purchase_order_quantity = (float)$purchase_order_quantity + (float)($popv->quantity * $product_size->weight * $popv->length);
                    }
                    if ($popv->unit_id == 5) {
                        $purchase_order_quantity = (float)$purchase_order_quantity + (float)($popv->quantity * $product_size->weight * (float)($popv->length/305));
                    }
                }
            }

            if ($purchase_order_advise_quantity >= $purchase_order_quantity) {
                $purchase_orders[$key]['pending_quantity'] = 0;
            } else {
                $purchase_orders[$key]['pending_quantity'] = (float)($purchase_order_quantity - $purchase_order_advise_quantity);
            }

            if ($purchase_orders[$key]['pending_quantity'] == 0) {
                $purchase_orders[$key]['order_status'] = 'completed';
                PurchaseOrder::where('id', $purchase_orders[$key]['id'])->update(['order_status' => 'completed']);
            }
            $purchase_orders[$key]['total_quantity'] = $purchase_order_quantity;
        }
        return $purchase_orders;
    }

}
