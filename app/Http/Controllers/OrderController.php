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
use App\DeliveryChallan;
use App\ProductSubCategory;
use DateTime;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Territory;
use App\TerritoryLocation;

class OrderController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
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

    public function under_loading_truck(Request $request){
        $update = Order::where('id',$request->order_id)->update([
            'del_supervisor'=>$request->del_spervisor,
            'del_boy' => $request->del_boy,
            'empty_truck_weight' => $request->empty_truck_weight,
            'party_name' => $request->party_name,
            'vehicle_number' => $request->vehical_number
        ]);
        if($update){
            if($request->del_spervisor){
                User::where('id',$request->del_spervisor)->update(['status'=>2]);
            }
            if($request->del_boy){
                User::where('id',$request->del_boy)->update(['status'=>2]);
            }
            echo "success";
        }
        else{
            echo "failed";
        }
    }


    public function loaded_truck(Request $request){
        $update = Order::where('id',$request->order_id)->update([
            'del_supervisor'=>$request->del_spervisor,
            'del_boy' => $request->del_boy,
            'final_truck_weight' => $request->final_truck_weight,
            'product_detail_table' => $request->product_detail_table,
            'labour_pipe' => $request->labour_pipe,
            'labour_structure' => $request->labour_structure
        ]);
        if($update){
            if($request->del_spervisor){
                User::where('id',$request->del_spervisor)->update(['status'=>1]);
            }
            if($request->del_boy){
                User::where('id',$request->del_boy)->update(['status'=>1]);
            }
            echo "success";
        }
        else{
            echo "failed";
        }
    }
    public function loaded_truck_delivery(Request $request){
        $delivery_data = DeliveryOrder::where('order_id',$request->order_id)->first();
        if(!is_null($delivery_data->del_boy) || !is_null($delivery_data->del_spervisor)) 
        {       
            $update = Order::where('id',$request->order_id)->update([
                'final_truck_weight' => $request->final_truck_weight,
            ]);  
            $update_delivery = DeliveryOrder::where('order_id',$request->order_id)->update([
                'final_truck_weight' => $request->final_truck_weight,
            ]);      
            if($update){            
                echo "success";
            }
            else{
                echo "failed";
            }
        }
        // else{

        //         echo "failed".$delivery_data; 
        //         // return Redirect::back()->withInput()->with('err-p', 'Please select delivery supervisor or delivery boy');
        // }
        

    }
    public function delivery_order_spervisor(Request $request){
      if($request->del_spervisor==null)
            $request->del_spervisor=null;  
        $update = Order::where('id',$request->order_id)->update([
            'del_supervisor'=>$request->del_spervisor  
        ]);  
        $update_delivery = DeliveryOrder::where('order_id',$request->order_id)->update([
            'del_supervisor'=>$request->del_spervisor,            
        ]);      
        if($update){
            if($request->del_spervisor){
                User::where('id',$request->del_spervisor)->update(['status'=>1]);
            }            
            echo "success";
        }
        else{
            echo "failed";
        }        

    }
    public function delivery_order_del_boy(Request $request){  
    if($request->del_boy==null)
            $request->del_boy=null; 

        $update = Order::where('id',$request->order_id)->update([
            'del_boy'=>$request->del_boy  
        ]);  
        $update_delivery = DeliveryOrder::where('order_id',$request->order_id)->update([
            'del_boy'=>$request->del_boy,            
        ]);      
        if($update){
            if($request->del_boy){
                User::where('id',$request->del_boy)->update(['status'=>1]);
            }            
            echo "success";
        }
        else{
            echo "failed";
        }        

    }


    public function index(PlaceOrderRequest $request) {
        ini_set('memory_limit','128M');

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        $data = Input::all();
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
        }

        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();
        }

        $order_sorttype = Session::get('order-sort-type');
        if (isset($order_sorttype) && ($order_sorttype != "")) {
            $data['order_filter'] = $order_sorttype;
        }
        $q = Order::query();
        if (Auth::user()->role_id == 5) {
            
        } else {
            if (isset($data['order_filter']) && $data['order_filter'] != '') {
                if ($data['order_filter'] == 'approval') {
                    $q->where('is_approved', '=', 'no')
                            ->where('order_status', '=', 'pending');
                } elseif ($data['order_filter'] == 'pending') {
                    $q->where('is_approved', '=', 'yes')
                            ->where('order_status', '=', 'pending');
                } else {
                    $q->where('order_status', '=', $data['order_filter']);
                }
            } elseif (isset($data['order_status']) && $data['order_status'] != '') {
                if ($data['order_status'] == 'approval') {
                    $q->where('is_approved', '=', 'no')
                            ->where('order_status', '=', 'pending');
                } elseif ($data['order_status'] == 'pending') {
                    $q->where('is_approved', '=', 'yes')
                            ->where('order_status', '=', 'pending');
                } else {
                    $q->where('order_status', '=', $data['order_status']);
                }
            } else {
                $q->where('order_status', '=', 'pending')
                  ->where('is_approved', '=', 'yes');
            }
            if (isset($data["territory_filter"]) && $data["territory_filter"] != '') {
                $loc_arr = [];
                $territory_arr = [];
                $territory_id = $data["territory_filter"];
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                if (isset($territory_locations)) {
                    foreach ($territory_locations as $loc) {
                        if (!in_array($loc->teritory_id, $loc_arr)) {
                            array_push($territory_arr, $loc->teritory_id);
                        }
                        array_push($loc_arr, $loc->location_id);
                    }
                    $q->whereIn('delivery_location_id', $loc_arr);
                }
            }
            if (isset($data['party_filter']) && $data['party_filter'] != '') {
                $q->where('customer_id', '=', $data['party_filter']);
            }
            if (isset($data['fulfilled_filter']) && $data['fulfilled_filter'] != '') {
                if ($data['fulfilled_filter'] == '0') {
                    $q->where('order_source', '=', 'warehouse');
                }
                if ($data['fulfilled_filter'] == 'all') {
                    $q->where('order_source','=', 'supplier');
                }
            }
            if ((isset($data['location_filter'])) && $data['location_filter'] != '') {
                $q->where('delivery_location_id', '=', $data['location_filter']);
            }
        }
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
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
        $product_category_id = 0;
        if (isset($data['size_filter']) && $data['size_filter'] != '') {
            $size = $data['size_filter'];
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
        if (Input::has('flag') && Input::get('flag') == 'true') {
            if (Auth::user()->role_id <> 5) {
                $allorders = $q->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled', 'delivery_orders')->orderBy('flaged', 'desc')->orderBy('created_at', 'desc')->paginate(20); // included `delivery_orders`
            }
            if (Auth::user()->role_id == 5) {
                $allorders = $q->where('customer_id', '=', $cust->id)->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled', 'delivery_orders')->orderBy('flaged', 'desc')->orderBy('updated_at', 'desc')->paginate(20); // included `delivery_orders`
            }
        } else {
            if (Auth::user()->role_id <> 5) {
                $allorders = $q->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled', 'createdby', 'delivery_orders')->orderBy('updated_at', 'desc')->paginate(20); // included `delivery_orders`
            }
            if (Auth::user()->role_id == 5) {
                $allorders = $q->where('customer_id', '=', $cust->id)->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled', 'delivery_orders')->orderBy('created_at', 'desc')->paginate(20); // included `delivery_orders`
            }
        }
        $users = User::all();
        if (Auth::user()->role_id <> 5) {
            $customers = Customer::orderBy('tally_name', 'ASC')->get();
        }
        if (Auth::user()->role_id == 5) {
            $customers = Customer::where('id', '=', $cust->id)->orderBy('tally_name', 'ASC')->get();
        }

        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $delivery_order = AllOrderProducts::where('order_type', '=', 'delivery_order')->where('product_category_id', '=', $product_category_id)->get();

        //$product_size = ProductSubCategory::all();
        // dd("ii");
        $pending_orders = $this->checkpending_quantity($allorders);
        $allorders->setPath('orders');
//        $non_approved_orders = Order::with('all_order_products', 'customer', 'delivery_location', 'createdby')
//                ->where('is_approved', '=', 'no')
//                ->where('order_status', '=', 'pending')
//                ->orderBy('created_at', 'desc')
//                ->paginate(20);
//
//        $this->checkpending_quantity($non_approved_orders);
//
//        if (isset($data['order_filter']) && $data['order_filter'] != '' && $data['order_filter'] == 'approval') {
//            $allorders = $non_approved_orders;
//        }


        $all_territories = Territory::get();
        if (Input::has('export_data')) {
            $data = Input::all();
            $is_approved = 'yes';
            if ($data['order_status'] == 'pending') {
                $order_status = 'pending';
                $excel_sheet_name = 'Pending';
                $excel_name = 'Order-Pending-' . date('dmyhis');
            } elseif ($data['order_status'] == 'completed') {
                $order_status = 'completed';
                $excel_sheet_name = 'Completed';
                $excel_name = 'Order-Completed-' . date('dmyhis');
            } elseif ($data['order_status'] == 'approval') {
                $is_approved = 'no';
                $order_status = 'pending';
                $excel_sheet_name = 'Approval';
                $excel_name = 'Order-Pending-Approval' . date('dmyhis');
            } elseif ($data['order_status'] == 'cancelled') {
                $order_status = 'cancelled';
                $excel_sheet_name = 'Cancelled';
                $excel_name = 'Order-Cancelled-' . date('dmyhis');
            }
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();
            Excel::create($excel_name, function($excel) use($allorders, $units, $delivery_location, $customers, $excel_sheet_name) {
                $excel->sheet('Order-' . $excel_sheet_name, function($sheet) use($allorders, $units, $delivery_location, $customers) {
                    $sheet->loadView('excelView.order', array('order_objects' => $allorders, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
                });
            })->export('xls');
        }
        $parameters = parse_url($request->fullUrl());
        $parameters = isset($parameters['query']) ? $parameters['query'] : '';
        Session::put('parameters', $parameters);

        // dd($allorders);
        // dd($delivery_location);            

        return View::make('orders', compact('delivery_location', 'delivery_order', 'customers', 'allorders', 'users', 'cancelledorders', 'pending_orders', 'product_size', 'product_category_id', 'search_dates', 'all_territories'));
    }

    /**
     * Functioanlity: Add new order page display
     */
    public function create() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 5) {
           $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

           $order = Customer::with('delivery_location')->find($cust->id);

//         $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer')->find($id);
//         if (count($order) < 1) {
//            return redirect('orders')->with('flash_message', 'Order does not exist.');
//         }

           if (count($order) < 1) {
               return redirect('order')->with('flash_message', 'Order does not exist.');
           }
        }

        $units = Units::all();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return View::make('add_orders', compact('customers', 'order', 'units', 'delivery_locations'));
    }

    /**
     * Functioanlity: Flag order
     */
    public function flagOrder() {
        $data = Input::all();
        if ($data['module'] == 'order') {
            $order_details = Order::find(Input::get('order_id'));
            $order_details->flagOrder($order_details);
        } elseif ($data['module'] == 'deliveryorder') {
            $delivery_order_details = DeliveryOrder::find(Input::get('order_id'));
            $delivery_order_details->flagDelievryOrder($delivery_order_details);
        }
    }

    /**
     * Functioanlity: Save order details
     */
    public function store(PlaceOrderRequest $request) {

        $input_data = Input::all();
        $sms_flag = 0;
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
        $rules = ['status' => 'required'];
        $validator1 = Validator::make($input_data, $rules);
        if (!$validator1->passes()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator1)->withInput();
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
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withInput()->with('flash_message', 'Please select Delivery Location.');
        }
        if ($input_data['expected_date'] == '') {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withInput()->with('flash_message', 'Please select Expected Delivery date.');
        }
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes() && $validator1->passes()) {                
                $customers = new Customer();
                $newcustomer = $customers->addNewCustomer($input_data['customer_name'], $input_data['contact_person'], $input_data['mobile_number'], $input_data['credit_period'], $input_data['add_order_location']);
                $customer_id = $newcustomer->id;               
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
        
        if ($input_data['status'] == 'warehouse') {
            $order_status = 'warehouse';
            $supplier_id = 0;
        }
        if ($input_data['status'] == 'supplier') {
            $other_location_difference;
            $order_status = 'supplier';
            $supplier_id = $input_data['supplier_id'];
        }
        $discount_type = $input_data['discount_type'];
        $discount_unit = $input_data['discount_unit'];
        $discount = $input_data['discount'];
//        if ($input_data['status1'] == 'include_vat') {
//            $vat_price = '';
//        }
//        if ($input_data['status1'] == 'exclude_vat') {
//            $vat_price = $input_data['vat_price'];
//        }        
        $order = new Order();
        $order->order_source = $order_status;
        $order->supplier_id = $supplier_id;
        $order->customer_id = $customer_id;
        $order->created_by = Auth::id();
        //$order->vat_percentage = $input_data['vat_price'];
        $order->discount_type = $discount_type;
        $order->discount_unit = $discount_unit;
        $order->discount = $discount;
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
        $order->expected_delivery_date = $datetime->format('Y-m-d');
        $order->remarks = $input_data['order_remark'];
        $order->order_status = "Pending";
        if (Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4)
            $order->is_approved = 'yes';
        if (isset($input_data['location']) && ($input_data['location'] != "")) {
            $order->delivery_location_id = 0;
            $order->other_location = $input_data['location'];
            $order->location_difference = $input_data['location_difference'];
        } else {
            $order->delivery_location_id = $input_data['add_order_location'];
            $order->location_difference = $input_data['location_difference'];
        }
        $order->save();
                    // 'length' => (isset($product_data['length']) && $product_data['length'] == $product_data['length']) ? $product_data['length'] : 0,
        $order_id = $order->id;
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {          

            if (($product_data['name'] != "") && ($product_data['id'] != "") && ($product_data['id'] > 0)) {
                $tmp = [
                    'order_id' => $order_id,
                    'order_type' => 'order',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'length' => (isset($product_data['length']) && $product_data['length'] == $product_data['length']) ? $product_data['length'] : 0,
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                array_push($order_products, $tmp);
                /* check for vat/gst items */
                if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') {
                    $sms_flag = 1;
                }
                /**/
            }
        }
        if (count($order_products)) {
            AllOrderProducts::insert($order_products);
        }

        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR NEW ORDER
         * ----------------------------------
         */

        $input = Input::all();
//        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
        if ($sms_flag == 1) {
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nYour order has been created as following \n";
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
                        if ($product_data['units'] == 4) {
                            $total_quantity = $total_quantity + $product_data['quantity'] * $product->weight;
                        }
                        if ($product_data['units'] == 5) {
                            $total_quantity = $total_quantity + $product_data['quantity'] * ($product->weight / 305);
                        }
                    }
                }
                $str .= " material will be dispatched by " . date("j M, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
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
                    $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has created an order for " . $customer->owner_name . ", " . round($total_quantity, 2) . "'. Kindly check. \nVIKAS ASSOCIATES";
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




        /* inventory code */
//        $calc = new WelcomeController();
//        $calc->setInventoryValues($order_id, "order", "no");
        $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $order_id)->where('order_type', 'order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }
        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
          | ---------------------------------------------
          | SEND EMAIL TO CUSTOMER ON CREATE OF NEW ORDER
          | ---------------------------------------------
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);
//            if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
            $order = Order::with('all_order_products.order_product_details', 'delivery_location')->find($order_id);
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

        //         update sync table         
        $tables = ['customers', 'orders', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        return redirect('orders')->with('flash_message', 'Order details successfully added.');
    }

    /**
     * Functioanlity: Display order details of particulat order
     */
    public function show($id, PlaceOrderRequest $request) {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')->where('customer_id', '=', $cust->id)->find($id);
        }

        if (Auth::user()->role_id <> 5) {
            $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')->find($id);
        }

        if (count($order) < 1) {
            return redirect('orders')->with('flash_message', 'Order does not exist.');
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        $is_approval = $request->input();

        return View::make('order_detail', compact('order', 'delivery_location', 'units', 'customers', 'is_approval'));
    }

    /**
     * Functioanlity: Show edit order details page
     */
    public function edit($id, PlaceOrderRequest $request) {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }


        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')->where('customer_id', '=', $cust->id)->find($id);
        }

        if (Auth::user()->role_id <> 5) {
            $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')->find($id);
        }

        if (count($order) < 1) {
            return redirect('orders')->with('flash_message', 'Order does not exist.');
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::where('customer_status', 'permanent')->get();
        $is_approval = $request->input();

        return View::make('edit_order', compact('order', 'delivery_location', 'units', 'customers', 'is_approval'));
    }

    /**
     * Functioanlity: Update order details
     */
    public function update($id, PlaceOrderRequest $request) {

        $input_data = Input::all();        
        $sms_flag = 0;
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
        $rules = [ 'status' => 'required'];
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
                    $newcustomer = $customers->addNewCustomer($input_data['customer_name'], $input_data['contact_person'], $input_data['mobile_number'], $input_data['credit_period'], $input_data['add_order_location']);
                    $customer_id = $newcustomer->id;
                }
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } else if (isset($input_data['customer_status']) && $input_data['customer_status'] == "existing_customer") {
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
//        if ($input_data['vat_status'] == 'include_vat') {
//            $vat_price = '';
//        }
//        if ($input_data['vat_status'] == 'exclude_vat') {
//            $vat_price = $input_data['vat_percentage'];
//        }
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
        $order = Order::find($id);
        $update_order = $order->update([
            'order_source' => $order_status,
            'supplier_id' => $supplier_id,
            'customer_id' => $customer_id,
//            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_inquiry_location'],
         //   'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['order_remark'],
            'discount_type' => $input_data['discount_type'],
            'discount_unit' => $input_data['discount_unit'],
            'discount' => $input_data['discount'],
        ]);
        if ($input_data['add_inquiry_location'] == 'other') {
            $update_order = $order->update([
                'other_location' => $input_data['other_location_name'],
                'location_difference' => $input_data['location_difference']
            ]);
        } else {
            $update_order = $order->update([ 'other_location' => '',
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
                    'length' => $product_data['length'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark']
                ];
                $edit_order_products = AllOrderProducts::find($product_data['order']);
                $edit_order_products->update($order_products);
            }
            if ($product_data['name'] != "" && $product_data['order'] == '') {
                $order_products = [
                    'order_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'length' => $product_data['length'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark']
                ];
                AllOrderProducts::create($order_products);
            }

            /* check for vat/gst items */
            if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') {
                $sms_flag = 1;
            }
            /**/
        }
        $order_prod = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $id)->first();
        $order->updated_at = $order_prod->updated_at;
        if ($order->is_approved == 'no') {
            if (Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4) {
                $order->is_approved = 'yes';
            }
        }

        $order->save();
        /* inventory code */
//        $calc = new WelcomeController();
//        $calc->setInventoryValues($id, "order", "no");
        $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }
        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR UPDATE ORDER
         * ----------------------------------
         */
        $input = Input::all();
        if ($sms_flag == 1) {
            if (isset($input['way']) && $input['way'] == "approval") {
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nAdmin has approved your order for following items\n";
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

                            if ($product_data['units'] == 4) {
                                $total_quantity = $total_quantity + $product_data['quantity'] * $product->weight;
                            }
                            if ($product_data['units'] == 5) {
                                $total_quantity = $total_quantity + $product_data['quantity'] * ($product->weight / 305);
                            }
                        }
                    }
                    $str .= " material will be dispatched by " . date("j M, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
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
                        $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has approved an order for " . $customer->owner_name . ", " . round($total_quantity, 2) . "'. Kindly check. \nVIKAS ASSOCIATES";
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
//            } else if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            } else {
                $customer = Customer::with('manager')->find($customer_id);
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
                            if ($product_data['units'] == 4) {
                                $total_quantity = $total_quantity + $product_data['quantity'] * $product->weight;
                            }
                            if ($product_data['units'] == 5) {
                                $total_quantity = $total_quantity + $product_data['quantity'] * ($product->weight / 305);
                            }
                        }
                    }
                    $str .= " material will be dispatched by " . date("j M, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
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
                        $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has edited and changed an order for " . $customer->owner_name . ", " . round($total_quantity, 2) . "'. Kindly check. \nVIKAS ASSOCIATES";
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



        /*
          | ---------------------------------------------
          | SEND EMAIL TO CUSTOMER ON UPDATE OF NEW ORDER
          | ---------------------------------------------
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);

            $order = Order::with('all_order_products.order_product_details', 'delivery_location')->find($id);
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
        //         update sync table         
        $tables = ['customers', 'orders', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        $parameter = Session::get('parameters');
        $parameters = (isset($parameter) && !empty($parameter)) ? '?' . Session::get('parameters') : '';


        Order::where('id',$id)->update(['is_editable'=>1]);

        /* end code */
        return redirect('orders' . $parameters)->with('flash_message', 'Order details successfully modified.');
    }

    /**
     * Functioanlity: Delete individual order details
     */
    public function destroy($id) {

        $inputData = Input::get('formData');
        $flag = 0;
        $sms_flag = 0;
        if (empty($inputData)) {
            $formFields = Input::all();
            $flag = 1;
        } else {
            parse_str($inputData, $formFields);
        }

        $password = $formFields['password'];
        $userinfo = auth()->user();
        $order_sort_type = $formFields['order_sort_type'];
        if ($userinfo->role_id != 0 && $userinfo->role_id != 1) {
            return redirect('orders')->with('error', 'You do not have permission.');
        } elseif ($password == '') {
//            return redirect('orders')->with('error', 'Please enter your password');
            return Redirect::back()->with('error', 'Please enter your password');
        }
        if (Hash::check($password, $userinfo->password)) {

            if ($flag == 1) {

                if (Input::has('way') && Input::get('way') == 'reject') {

                    $ord = Order::find($id);
                    $customer = Customer::with('manager')->find($ord->customer_id);
                    if (count($customer) > 0) {
                        $total_quantity = '';
                        $str = '';

                        $input_data = AllOrderProducts::with('order_product_details')->where('order_id', '=', Input::get('user_id'))->where('order_type', 'order')->get();

                        foreach ($input_data as $product_data) {

                            if ($product_data['order_product_details']->alias_name != "") {
                                $product = ProductSubCategory::find($product_data['id']);                                
                                $str .= $product_data['order_product_details']->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
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

                            /* check for vat/gst items */
                            if (isset($ord['vat_percentage']) && $ord['vat_percentage'] > 0) {
                                $sms_flag = 1;
                            }
                            /**/
                        }
                        if ($sms_flag == 1) {
                            $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nAdmin has rejected your order for following items \n";
                            foreach ($input_data as $product_data) {

                                if ($product_data['order_product_details']->alias_name != "") {
                                    $str .= $product_data['order_product_details']->alias_name . ' - ' . $product_data['quantity'] . "\n ";
                                    $total_quantity = $total_quantity + $product_data['quantity'];
                                }
                            }
                            $str .= "\nVIKAS ASSOCIATES";                            
                           
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
                                $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has edited and changed an order for " . $customer->owner_name . ", " . round($total_quantity, 2) . "'. Kindly check. \nVIKAS ASSOCIATES";
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

                /* inventory code */
//                $calc = new WelcomeController();
//                $calc->setInventoryValues($id, "order", "yes");
                $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'order')->get();
                foreach ($product_categories as $product_categoriy) {
                    $product_category_ids[] = $product_categoriy->product_category_id;
                }
                /* */

//                AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'order')->delete();


                $delete_records = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'order')->get();

                if ($delete_records != null) {
                    foreach ($delete_records as $elm) {
                        $elm->delete();
                    }
                }

                $delete_record = Order::find($id);
                if ($delete_record != null) {
                    $delete_record->delete();
                }


                /* inventory code */
                $calc = new InventoryController();
                $calc->inventoryCalc($product_category_ids);
                $message = "Record deleted successfully.";
                /**/
//                Session::put('order-sort-type', $order_sort_type);
                if (Input::has('way') && Input::get('way') == 'reject') {
                    $message = "Order rejected successfully.";
//                    return Redirect::to('orders?order_filter=approval')->with('success', 'Record deleted successfully.');
                }

                $parameter = Session::get('parameters');
                $parameters = (isset($parameter) && !empty($parameter)) ? '?' . Session::get('parameters') : '';
                return Redirect::to('orders' . $parameters)->with('success', $message);
            }
            return array('message' => 'success');
        } else {
            if ($flag == 1) {
                return Redirect::to('orders')->with('error', 'Please enter correct password.');
            }
            return array('message' => 'failed');
        }
    }

    /*
     * Functioanlity: Manual Complete individual order
     */

    public function manual_complete_order() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return redirect('orders')->with('error', 'You do not have permission.');
        }
        $sms_flag = 0;
        $formFields = Input::get('formData');
        parse_str($formFields, $input);
        $order_id = $input['order_id'];
        $reason_type = $input['reason_type'];
        $reason = $input['reason'];

        /* inventory code */
//        $calc = new WelcomeController();
//        $calc->setInventoryValues($order_id, "order", "yes");
        $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $order_id)->where('order_type', 'order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }
        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        $order = Order::with('all_order_products.order_product_details', 'all_order_products.unit', 'customer')->find($order_id);

        /* check for vat/gst items */
        foreach ($order['all_order_products'] as $product_data) {
            if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] <> '0.00') {
                $sms_flag = 1;
            }
        }
        /**/

        /*
          | ------------------- ---------------------------------
          | SEND SMS TO CUSTOMER FOR MANUALLY COMPLETING AN ORDER
          | -----------------------------------------------------
         */

//        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
        if ($sms_flag == 1) {
            $customer = Customer::with('manager')->find($order['customer']->id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\n Your order has been canceled for following \n";
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
            $order = Order::with('all_order_products.order_product_details', 'delivery_location')->find($order_id);
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
        $order->order_status = "Cancelled";
        $order->save();
        $cancel_order = OrderCancelled::create([
                    'order_id' => $order_id,
                    'order_type' => 'Order',
                    'reason_type' => $reason_type,
                    'reason' => $reason,
                    'cancelled_by' => Auth::id()
        ]);

        //         update sync table         
        $tables = ['orders', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        return array('message' => 'success');
    }

    /*
     * Functioanlity: Create New Delivery Order
     */

    public function create_delivery_order($id) {
        if (Auth::user()->role_id == 5) {
            return redirect('orders')->with('error', 'You do not have permission.');
        }
        /* old */
        //$order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer')->find($id);
        /* old */

        /* new */
        $order = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'all_order_products.sum_quntity', 'customer')->find($id);
        /* new */


        if (count($order) < 1) {
            return redirect('orders')->with('flash_message', 'Order does not exist.');
        }

        foreach ($order['all_order_products'] as $key => $value) {
            //old
            //$delivery_order_products = AllOrderProducts::where('parent', '=', $value->id)->get();
            //$total_delivery_order_product_quantity = $delivery_order_products->sum('quantity');
            //old
            // new
            $total_delivery_order_product_quantity = $value['sum_quntity']->sum('quantity');
            // new
            $order['all_order_products'][$key]['pending_quantity'] = ($value->quantity - $total_delivery_order_product_quantity);
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return view('create_delivery_order', compact('order', 'delivery_location', 'units', 'customers'));
    }

    /*
     * Individual order pending details
     */

    public function pending_quantity_order($id) {

        $pending_orders = array();
        $delivery_orders = DeliveryOrder::where('order_id', $id)->get();
//        $order_products = AllOrderProducts::where('order_id', $id)->where('order_type', 'order')->get();
//        $pending_qty = 0;
        $total_qty = 0;
        $temp_array = array();
        foreach ($delivery_orders as $del_order) {
            $all_order_products = AllOrderProducts::where('order_id', $del_order->id)->where('from', '!=', '')->where('order_type', 'delivery_order')->get();
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
//        $total_quantity_ord = 0;
//        $tot_pend_qty = 0;
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
            $order = Order::with('all_order_products')->find($id);
            $delivery_order = new DeliveryOrder();
            $delivery_order->order_id = $id;
            $delivery_order->customer_id = $input_data['customer_id'];
            $delivery_order->order_source = $order->order_source;
            $delivery_order->supplier_id = $order->supplier_id;
            $delivery_order->created_by = $user->id;
//            $delivery_order->vat_percentage = $order->vat_percentage;
          //  $delivery_order->vat_percentage = $input_data['vat_percentage'];
            $delivery_order->expected_delivery_date = $order->expected_delivery_date;
            $delivery_order->remarks = $input_data['remarks'];
            $delivery_order->vehicle_number = $input_data['vehicle_number'];
            $delivery_order->driver_contact_no = $input_data['driver_contact'];
            $delivery_order->order_status = 'Pending';
            $delivery_order->discount_type = $input_data['discount_type'];
            $delivery_order->discount_unit = $input_data['discount_unit'];
            $delivery_order->discount = $input_data['discount'];
            if ($order->other_location == '') {
                $delivery_order->delivery_location_id = $order->delivery_location_id;
                $delivery_order->other_location = '';
                $delivery_order->location_difference = $order->location_difference;
            } else {
                $delivery_order->other_location = $order->other_location;
                $delivery_order->location_difference = $order->location_difference;
            }

            // $delivery_order->empty_truck_weight = $order->empty_truck_weight;
            // $delivery_order->final_truck_weight = $order->final_truck_weight;
            $delivery_order->del_supervisor = $order->del_supervisor;
            $delivery_order->del_boy = $order->del_boy;
            $delivery_order->party_name = $order->party_name;
            $delivery_order->product_detail_table = $order->product_detail_table;
            $delivery_order->labour_pipe = $order->labour_pipe;
            $delivery_order->labour_structure = $order->labour_structure;

            $delivery_order->is_editable = $order->is_editable;

            $delivery_order->save();
            $delivery_order_id = $delivery_order->id;
            $created_at = $delivery_order->created_at;
            $updated_at = $delivery_order->updated_at;
            $total_qty = 0;
            $present_shipping = 0;
//            $order_products = array();
            $order_products = [];
            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] != "" && $product_data['order'] != '') {
//                    $order_products = [
                    $order_products[] = [
                        'order_id' => $delivery_order_id,
                        'order_type' => 'delivery_order',
                        'from' => $id,
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'length' => $product_data['length'],
                        'quantity' => $product_data['present_shipping'],
                        'present_shipping' => $product_data['present_shipping'],
                        'price' => $product_data['price'],
                        'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                        'remarks' => $product_data['remark'],
                        'parent' => $product_data['order'],
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    ];
                    $total_qty = $total_qty + $product_data['quantity'];
                    $present_shipping = $present_shipping + $product_data['present_shipping'];
//                    AllOrderProducts::create($order_products);
                }
                if ($product_data['name'] != "" && $product_data['order'] == '') {
//                    $order_products = [
                    $order_products[] = [
                        'order_id' => $delivery_order_id,
                        'order_type' => 'delivery_order',
                        'from' => '',
                        'product_category_id' => $product_data['product_category_id'],
                        'length' => $product_data['length'],
                        'unit_id' => $product_data['units'],
                        'quantity' => $product_data['present_shipping'],
                        'present_shipping' => $product_data['present_shipping'],
                        'price' => $product_data['price'],
//                        'vat_percentage' => ($product_data['vat_percentage'] != ''&& isset($product_data['vat_percentage'])) ? $product_data['vat_percentage'] : 0,
                        'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                        'remarks' => $product_data['remark'],
                        'parent' => '',
                        'created_at' => $created_at,
                        'updated_at' => $updated_at
                    ];
//                    AllOrderProducts::create($order_products);
                }
            }
            AllOrderProducts::insert($order_products);

            //If pending quantity is Zero complete the order
            if ($present_shipping == $total_qty || $present_shipping >= $total_qty) {
                Order::where('id', '=', $id)->update(array('order_status' => 'completed'));
            }

            /* inventory code */
            $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $delivery_order_id)->where('order_type', 'delivery_order')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }
            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);

            //         update sync table         
            $tables = ['customers', 'orders', 'all_order_products', 'delivery_order'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */
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


            /* new */
            $delivery_order_products = NULL;
            if (isset($order['delivery_orders']) && count($order['delivery_orders'])) {
                $delivery_order_products = AllOrderProducts::with('product_sub_category')->where('from', '=', $order->id)->where('order_type', '=', 'delivery_order')->get();
            }





            /* new */
            /* old */
            // $delievry_order_details = DeliveryOrder::where('order_id', '=', $order->id)->first();
            // if (!empty($delievry_order_details)) {
            //     $delivery_order_products = AllOrderProducts::where('from', '=', $delievry_order_details->order_id)->where('order_type', '=', 'delivery_order')->get();
            // } else {
            //     $delivery_order_products = NULL;
            // }
            /* old */

            if (count($delivery_order_products) > 0) {
                // echo "in del;";
                // dd($delivery_order_products);

                foreach ($delivery_order_products as $dopk => $dopv) {
                    //new 
                    $product_size = $dopv['product_sub_category'];
                    //new
                    /* old */

                    //$product_size = ProductSubCategory::find($dopv->product_category_id);

                    /* old */
//                   $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity;
                    $productsubcat = App\ProductCategory::find($product_size->product_category_id);
                    if($productsubcat->product_type_id == 3 && $product_size->length_unit != ""){
                        /*if($product_size->length_unit == "ft"){
                            $delivery_order_quantity = $dopv->quantity * $product_size->weight * length;
                        }
                        else{
                            $delivery_order_quantity = $dopv->quantity * ($product_size->weight/305)*(length/305);
                        }*/

                        if ($dopv->unit_id == 1) {
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity;
                        } elseif ($dopv->unit_id == 2) {
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * $product_size->weight;
                        } elseif ($dopv->unit_id == 3) {
                            if ($product_size->standard_length) {
                                $delivery_order_quantity = $delivery_order_quantity + ($dopv->quantity / $product_size->standard_length ) * $product_size->weight;
                            } else {
                                $delivery_order_quantity = $delivery_order_quantity + ($dopv->quantity * $product_size->weight);
                            }
                        }
                        elseif($dopv->unit_id == 4) {
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * $product_size->weight * $dopv->length;
                        }
                        elseif($dopv->unit_id == 5){
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * ($product_size->weight/305) * ($dopv->length/305);
                        }
                    }
                    else{
                        if ($dopv->unit_id == 1) {
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity;
                        } elseif ($dopv->unit_id == 2) {
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * $product_size->weight;
                        } elseif ($dopv->unit_id == 3) {
                            if ($product_size->standard_length) {
                                $delivery_order_quantity = $delivery_order_quantity + ($dopv->quantity / $product_size->standard_length ) * $product_size->weight;
                            } else {
                                $delivery_order_quantity = $delivery_order_quantity + ($dopv->quantity * $product_size->weight);
                            }
                        }
                        elseif($dopv->unit_id == 4) {
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * $product_size->weight * $dopv->length;
                        }
                        elseif($dopv->unit_id == 5){
                            $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * ($product_size->weight/305) * ($dopv->length/305);
                        }
                    }
                }
            }
            if (count($order['all_order_products']) > 0) {

                foreach ($order['all_order_products'] as $opk => $opv) {
                    /* new */
                    if (isset($opv['product_sub_category'])) {
                        $product_size = $opv['product_sub_category'];
                    } else {
                        $product_size = ProductSubCategory::find($opv->product_category_id);
                    }

                    $productsubcat = App\ProductCategory::find($product_size->product_category_id);
                    if($productsubcat->product_type_id == 3 && $product_size->length_unit != ""){
                        /*if($product_size->length_unit == "ft"){
                            $order_quantity = $order_quantity + $opv->quantity * $product_size->weight;
                        }
                        else{
                            $order_quantity = $order_quantity + $opv->quantity * ($product_size->weight/305);
                        }*/
                        
                        if ($opv->unit_id == 1) {
                            $order_quantity = $order_quantity + $opv->quantity;
                        } elseif ($opv->unit_id == 2) {
                            $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                        } elseif ($opv->unit_id == 3) {
                            if ($product_size->standard_length) {
                                $order_quantity = $order_quantity + (($opv->quantity / $product_size->standard_length ) * $product_size->weight);
                            } else {
                                $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                            }
                        }
                        elseif($opv->unit_id == 4) {
                            $order_quantity = $order_quantity + $opv->quantity * $product_size->weight * $opv->length;
                        }
                        elseif($opv->unit_id == 5){
                            $order_quantity = $order_quantity + $opv->quantity * ($product_size->weight/305) * ($opv->length/305);
                        }
                    }
                    else{
                        if ($opv->unit_id == 1) {
                            $order_quantity = $order_quantity + $opv->quantity;
                        } elseif ($opv->unit_id == 2) {
                            $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                        } elseif ($opv->unit_id == 3) {
                            if ($product_size->standard_length) {
                                $order_quantity = $order_quantity + (($opv->quantity / $product_size->standard_length ) * $product_size->weight);
                            } else {
                                $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                                // dd($order_quantity);                            
                            }
                        }
                        elseif($opv->unit_id == 4) {
                            $order_quantity = $order_quantity + $opv->quantity * $product_size->weight * $opv->length;
                        }
                        elseif($opv->unit_id == 5){
                            $order_quantity = $order_quantity + $opv->quantity * ($product_size->weight/305) * ($opv->length/305);
                        }
                        // echo $delivery_order_quantity."-->".$order_quantity."<--;<br>";
                    }

                    /* new */
                    /* old */
                    //$product_size = ProductSubCategory::find($opv->product_category_id);
//                    $order_quantity = $order_quantity + $opv->quantity;

                    /* old */

                }
            }

           // $pr_s_c = AllOrderProducts::with('product_sub_category')->where('from', '=', $order->id)->get();
            $allorders[$key]['pending_quantity'] = ($delivery_order_quantity >= $order_quantity) ? 0 : ($order_quantity - $delivery_order_quantity);
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
                $data_array[] = [ 'value' => $prod->size];
            }
        } else {
            $data_array[] = [ 'value' => 'No size found'];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    /* Function used to export order details in excel */

    public function exportOrderBasedOnStatus1() {
        $data = Input::all();
        $is_approved = 'yes';
        if ($data['order_status'] == 'pending') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'pending';
            $excel_sheet_name = 'Pending';
            $excel_name = 'Order-Pending-' . date('dmyhis');
        } elseif ($data['order_status'] == 'completed') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'completed';
            $excel_sheet_name = 'Completed';
            $excel_name = 'Order-Completed-' . date('dmyhis');
        } elseif ($data['order_status'] == 'approval') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $is_approved = 'no';
            $order_status = 'pending';
            $excel_sheet_name = 'Approval';
            $excel_name = 'Order-Pending-Approval' . date('dmyhis');
        } elseif ($data['order_status'] == 'cancelled') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'cancelled';
            $excel_sheet_name = 'Cancelled';
            $excel_name = 'Order-Cancelled-' . date('dmyhis');
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if (Auth::user()->role_id <> 5) {
                if ($date1 == $date2) {
                    $order_objects = Order::where('order_status', $order_status)
                            ->where('is_approved', '=', $is_approved)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = Order::where('order_status', $order_status)
                            ->where('is_approved', '=', $is_approved)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();

                if ($date1 == $date2) {
                    $order_objects = Order::where('updated_at', 'like', $date1 . '%')
                            ->where('customer_id', '=', $cust->id)
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = Order::where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('customer_id', '=', $cust->id)
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
        } else {

            if (Auth::user()->role_id <> 5) {

                $order_objects = Order::where('order_status', $order_status)
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }

            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();


                $order_objects = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                        ->where('customer_id', '=', $cust->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                $excel_sheet_name = 'Order';
                $excel_name = 'Order-' . date('dmyhis');
            }
        }

        if (count($order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();
            Excel::create($excel_name, function($excel) use($order_objects, $units, $delivery_location, $customers, $excel_sheet_name) {
                $excel->sheet('Order-' . $excel_sheet_name, function($sheet) use($order_objects, $units, $delivery_location, $customers) {
                    $sheet->loadView('excelView.order', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
                });
            })->export('xls');
        }
    }

    public function track($id) {

        if (Auth::user()->role_id != 5) {

            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $is_approve = Order::where('id', '=', $id)
                ->where('is_approved', 'no')
                ->get();

        if (count($is_approve)) {
            return Redirect::to('orders')->withInput()->with('error', 'Order have to be approved by Admin.');
        }


        if (isset($id)) {
            $order_id = $id;
            $customer = Order::find($id);
            if (count($customer) == 0) {
                return Redirect::back()->withInput()->with('error', 'Invalid Order.');
            }

            $customer_id = $customer->customer_id;

            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();
        } else {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }


        if ($customer_id <> $cust->id) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }



        $order_status_responase = array();
        if (isset($order_id) && $order_id > 0 && isset($customer_id) && $customer_id > 0) {

            $order_status_responase['order_details'] = Order::with('all_order_products')->where('id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();

            $order_status_responase['delivery_order_details'] = DeliveryOrder::with('delivery_product')->where('order_id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();

            $order_status_responase['delivery_challan_details'] = DeliveryChallan::with('delivery_challan_products')->where('order_id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();
        } else {
            return json_encode(array('result' => false, 'track_order_status' => false, 'message' => 'Order not found'));
        }

        //  return json_encode($order_status_responase);
//       echo "<pre>";
//       print_r( $order_status_responase['delivery_order_details']->toArray());
//       echo "</pre>";
//       exit;

        return View::make('track_order', compact('order_status_responase'));
    }

}
