<?php

namespace App\Http\Controllers;

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


class PurchaseAdviseController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the Purchase Advices.
     */
    public function index() {
         $data = Input::all();
              
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4) {
           return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');
        $session_sort_type_order = Session::get('order-sort-type');
        $qstring_sort_type_order = Input::get('purchaseaAdviseFilter');

        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            $qstring_sort_type_order = $qstring_sort_type_order;
        } else {
            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
                $qstring_sort_type_order = $session_sort_type_order;
            } else {
                $qstring_sort_type_order = "";
            }
        }

        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != '')) {
            $q->where('advice_status', '=', $qstring_sort_type_order);
        } else {
            $q->where('advice_status', '=', 'in_process');
        }
        
 
         $search_dates = [];
         if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $q->where('updated_at', 'like', $date1 . '%');
                } else {
                    $q->where('updated_at', '>=', $date1);
                    $q->where('updated_at', '<=', $date2.' 23:59:59');
                }
                $search_dates = [
                    'export_from_date' => $data["export_from_date"],
                    'export_to_date' => $data["export_to_date"]
                ];
                
                
            }
                    
            
        $purchase_advise = $q->orderBy('created_at', 'desc')->paginate(20);
        $pending_orders = $this->checkpending_quantity($purchase_advise);
        $purchase_advise->setPath('purchaseorder_advise');

        return View::make('purchase_advise', array('purchase_advise' => $purchase_advise, 'pending_orders' => $pending_orders,'search_dates'=>$search_dates));
    }

    
     
    
        /* Function used to export order details in excel */

    public function exportPurchaseAdviseBasedOnStatus() {
        $data = Input::all();
        
               
        if ($data['purchaseaAdviseFilter'] == 'Inprocess') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'in_process';
            $excel_sheet_name = 'Inprocess';
            $excel_name = 'Purchase-Advise-Pending-' . date('dmyhis');
        } elseif ($data['purchaseaAdviseFilter'] == 'Delivered') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'delivered';
            $excel_sheet_name = 'Completed';
            $excel_name = 'Purchase-Advise-Completed-' . date('dmyhis');
        } elseif ($data['purchaseaAdviseFilter'] == 'cancelled') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'cancelled';
            $excel_sheet_name = 'Cancelled';
            $excel_name = 'Purchase-Advise-Cancelled-' . date('dmyhis');
        }
        
        
        
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if(Auth::user()->role_id <> 5)
            {
                              
                if ($date1 == $date2) {
                    $order_objects = PurchaseAdvise::where('advice_status', $order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseAdvise::where('advice_status', $order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2.' 23:59:59')
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
            if(Auth::user()->role_id == 5)
            {
                $cust = Customer::where('owner_name','=', Auth::user()->first_name)
                    -> where('phone_number1','=', Auth::user()->mobile_number) 
                    -> where('email','=', Auth::user()->email)
                    ->first();  
                
                if ($date1 == $date2) {
                    $order_objects = PurchaseAdvise::where('updated_at', 'like', $date1 . '%')
                            -> where('customer_id','=',$cust->id)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseAdvise::where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2.' 23:59:59')
                            ->where('customer_id','=',$cust->id)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
            
            
        } else {
            
            if(Auth::user()->role_id <> 5)
            {
        
                $order_objects = PurchaseAdvise::where('advice_status', $order_status)
                    ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
           
            if(Auth::user()->role_id == 5){
               $cust = Customer::where('owner_name','=', Auth::user()->first_name)
                    -> where('phone_number1','=', Auth::user()->mobile_number) 
                    -> where('email','=', Auth::user()->email)
                    ->first();  
                     
                
               $order_objects = PurchaseAdvise::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                    -> where('customer_id','=',$cust->id)   
                    ->orderBy('created_at', 'desc')
                    ->get(); 
               
               $excel_sheet_name = 'Purchase-Order';
               $excel_name = 'Purchase-Order-' . date('dmyhis');
            }
        }

        if (count($order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Purchase Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();
            

            
            
            
            Excel::create($excel_name, function($excel) use($order_objects, $units, $delivery_location, $customers, $excel_sheet_name) {
                $excel->sheet('Purchase-Order-' . $excel_sheet_name, function($sheet) use($order_objects, $units, $delivery_location, $customers) {
                    $sheet->loadView('excelView.purchase_advise', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
                });
            })->export('xls');
        }
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        
        if (Auth::user()->role_id == 5 ) {
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

        $input_data = Input::all();
        if (Session::has('forms_purchase_advise')) {
            $session_array = Session::get('forms_purchase_advise');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message_error', 'This purchase advise is already saved. Please refresh the page');
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
        $j = count($input_data['product']);
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
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
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
                    'from' => isset($product_data['purchase'])?$product_data['purchase']:''
                ];
                PurchaseProducts::create($purchase_advise_products);
            }
        }
        return redirect('purchaseorder_advise')->with('success', 'Purchase advise added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id ="") {
        if (Auth::user()->role_id == 5 | $id =="" ) {
           return Redirect::back()->withInput()->with('error', 'You do not have permission.');
           } 
        
        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.purchase_product_details')->find($id);
        if (count($purchase_advise) < 1) {
            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advise not found');
        }
        return View::make('view_purchase_advice', array('purchase_advise' => $purchase_advise));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        
         if (Auth::user()->role_id == 5 ) {
           return Redirect::back()->withInput()->with('error', 'You do not have permission.');
           } 
        
        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.purchase_product_details')->find($id);
        if (count($purchase_advise) < 1) {
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

        $input_data = Input::all();
        if (Session::has('forms_edit_purchase_advise')) {
            $session_array = Session::get('forms_edit_purchase_advise');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message_error', 'This purchase advise is already updated. Please refresh the page');
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
        $purchase_advice_prod = PurchaseProducts::where('order_type', '=', 'purchase_advice')->where('purchase_order_id', '=', $id)->first();
        $purchase_advise->updated_at = $purchase_advice_prod->updated_at;
        $purchase_advise->save();
        
        
        
                /*
         * ------------------- ------------------------
         * SEND SMS TO Customer FOR edit PURCHASE ADVISE
         * --------------------------------------------
         */
        
        $purchase_advise = PurchaseAdvise::with('supplier', 'purchase_products.purchase_product_details', 'purchase_products.unit', 'location')->find($id);
        $input_data = $purchase_advise['purchase_products'];
        
      
            $customer_id = $purchase_advise->supplier_id;
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour Purchase Advise has been edited as follows ";
                foreach ($input_data as $product_data) {
                    $str .= $product_data['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= " Vehicle No. " . $purchase_advise->vehicle_number . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
//                    $phone_number = $customer->phone_number1;
                    $phone_number = $customer->mobile_number;
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
             if (count($customer['manager']) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n".Auth::user()->first_name." has logged Purchase Advise for " . $customer->owner_name . " \n";
                foreach ($input_data as $product_data) {
                    $str .= $product_data['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= " Vehicle No. " . $purchase_advise->vehicle_number . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
//                    $phone_number = $customer->phone_number1;
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
     
        
        return redirect('purchaseorder_advise')->with('success', 'Purchase advise updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        $order_sort_type = $formFields['order_sort_type'];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if ($password == '') {
            return Redirect::to('purchaseorder_advise')->with('error', 'Please enter your password');
        }
        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            PurchaseAdvise::find($id)->delete();
            PurchaseProducts::where('purchase_order_id', '=', $id)->where('order_type', '=', 'purchase_advice')->delete();
            Session::put('order-sort-type', $order_sort_type);
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

        $input_data = Input::all();
        if (Session::has('forms_purchase_advise')) {
            $session_array = Session::get('forms_purchase_advise');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message_error', 'This purchase advise is already saved. Please refresh the page');
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
            $date_string = preg_replace('~\x{00a0}~u', '', $input_data['bill_date']);
            $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
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
                'purchase_order_id' => $input_data['id']
            ];
            $add_purchase_advice = PurchaseAdvise::create($add_purchase_advice_array);
            $purchase_advice_id = DB::getPdo()->lastInsertId();
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
                                'actual_pieces' => $product_data['actual_pieces'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice'
                            ];
                        }
                    }
                    if (isset($product_data['purchase']) && $product_data['purchase'] == 'purchase_order') {
                        $total_quantity = $total_quantity + $product_data['quantity'];
                        $total_present_shipping = $total_present_shipping + $product_data['present_shipping'];
                    }
                    $add_purchase_advice_products = PurchaseProducts::create($purchase_advice_products);
                }
            }
            if ($total_present_shipping == $total_quantity || $total_present_shipping > $total_quantity) {
                PurchaseOrder::where('id', '=', $input_data['id'])->update(array(
                    'order_status' => 'completed'
                ));
            }
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
        
        if (Auth::user()->role_id == 5 ) {
           return Redirect::back()->withInput()->with('error', 'You do not have permission.');
           } 

        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.purchase_product_details')->find($id);
        if (count($purchase_advise) < 1) {
            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advise not found');
        }
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $units = Units::all();
        return view('purchaseorder_advise_challan', compact('purchase_advise', 'locations', 'units'));
    }

    public function print_purchase_advise($id) {

        $current_date = date("/m/d/");
        $date_letter = 'PA' . $current_date . $id;
        PurchaseAdvise::where('id', '=', $id)->update(array(
            'serial_number' => $date_letter
        ));
        $purchase_advise = PurchaseAdvise::with('supplier', 'purchase_products.purchase_product_details', 'purchase_products.unit', 'location')->find($id);

        /*
         * ------------------- ------------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE ADVISE
         * --------------------------------------------
         */
        $input_data = $purchase_advise['purchase_products'];
        $send_sms = Input::get('send_sms');
        if ($send_sms == 'true') {
            $customer_id = $purchase_advise->supplier_id;
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour purchase Advise has been created as follows\n";
                foreach ($input_data as $product_data) {
                    $str .= $product_data['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= " Vehicle No. " . $purchase_advise->vehicle_number . ".\nVIKAS ASSOCIATES";
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
            
            if (count($customer['manager']) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n".Auth::user()->first_name." has created Purchase Advise for " . $customer->owner_name . " \n";
                foreach ($input_data as $product_data) {
                    $str .= $product_data['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                    $total_quantity = $total_quantity + $product_data->quantity;
        }
                $str .= " Vehicle No. " . $purchase_advise->vehicle_number . ".\nVIKAS ASSOCIATES";
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
        return view('print_purchase_advise', compact('purchase_advise'));
    }

    /**
     * This function returns all the pending quantity of purchase advise
     */
    function checkpending_quantity($purchase_advise) {
        $pending_orders = array();
        if (count($purchase_advise) > 0) {

            foreach ($purchase_advise as $key => $del_order) {
                $purchase_order_quantity = 0;
                if (count($del_order['purchase_products']) > 0) {

                    foreach ($del_order['purchase_products'] as $popk => $popv) {
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
                $purchase_advise[$key]['total_quantity'] = $purchase_order_quantity;
            }
        }
        return $pending_orders;
    }

}
