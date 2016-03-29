<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
use App\States;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryLocation;
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

class DeliveryOrderController extends Controller {
    /*
     * sms construction
     */

    public function __construct() {
	define('PROFILE_ID', Config::get('smsdata.profile_id'));
	define('PASS', Config::get('smsdata.password'));
	define('SENDER_ID', Config::get('smsdata.sender_id'));
	define('SMS_URL', Config::get('smsdata.url'));
	define('SEND_SMS', Config::get('smsdata.send'));
	$this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

	if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
	    return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
	}

	$session_sort_type_order = Session::get('order-sort-type');

	$qstring_sort_type_order = Input::get('order_status');

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
	if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {

	    if ($qstring_sort_type_order == 'Inprocess') {
		$delivery_data = DeliveryOrder::orderBy('created_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer')->paginate(20);
	    } elseif ($qstring_sort_type_order == 'Delivered') {
		$delivery_data = DeliveryOrder::orderBy('created_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer')->paginate(20);
	    }
	} else {
	    $delivery_data = DeliveryOrder::orderBy('created_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer')->paginate(20);
	}

	$delivery_data = $this->checkpending_quantity($delivery_data);
	$delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
	$delivery_data->setPath('delivery_order');
	return view('delivery_order', compact('delivery_data', 'delivery_locations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
	$units = Units::all();
	$delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
	$customers = Customer::orderBy('tally_name', 'ASC')->get();
	return view('add_delivery_order', compact('units', 'delivery_locations', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store() {

	$input_data = Input::all();
	$i = 0;
	$customer_id = 0;

	$j = count($input_data['product']);

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

	$vat_price = 0;
	if ($input_data['status1'] == 'include_vat') {
	    $vat_price = '';
	}
	if ($input_data['status1'] == 'exclude_vat') {
	    $vat_price = $input_data['vat_price'];
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
	$delivery_order_id = DB::getPdo()->lastInsertId();

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
		    'remarks' => $product_data['remark'],
		];

		$add_order_products = AllOrderProducts::create($order_products);
	    }
	}
	return redirect('delivery_order')->with('validation_message', 'Delivery order details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
	$delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->where('id', $id)->first();
	if (count($delivery_data) < 1) {
	    return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
	}
	$units = Units::all();
	$delivery_locations = DeliveryLocation::all();
	$customers = Customer::all();

	return view('view_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
	$units = Units::all();
	$delivery_locations = DeliveryLocation::all();
	$delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->where('id', $id)->first();
	if (count($delivery_data) < 1) {
	    return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
	}
	$customers = Customer::all();
	$pending_orders = $this->pending_quantity_order($id);

	return view('edit_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers', 'pending_orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {
	$input_data = Input::all();
	$customer_id = 0;
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

		    Customer::where('id', $input_data['pending_user_id'])
			    ->update($pending_cust);

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
	if ($input_data['status1'] == 'include_vat') {
	    $vat_price = '';
	}
	if ($input_data['status1'] == 'exclude_vat') {
	    $vat_price = $input_data['vat_price'];
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
	    'remarks' => $input_data['order_remark'],
	    'vehicle_number' => $input_data['vehicle_number'],
	    'driver_contact_no' => $input_data['driver_contact']
	));

	$order_products = array();
	foreach ($input_data['product'] as $product_data) {
	    if ($product_data['order'] != '' || $product_data['id'] != '') {
		$order_products = [
		    'order_id' => $id,
		    'order_type' => 'delivery_order',
		    'product_category_id' => $product_data['product_category_id'],
		    'unit_id' => $product_data['units'],
		    'quantity' => $product_data['quantity'],
		    'present_shipping' => $product_data['present_shipping'],
		    'price' => $product_data['price'],
		    'remarks' => $product_data['remark'],
		];

		$add_order_products = AllOrderProducts::where('id', '=', $product_data['id'])->update($order_products);
	    } else if ($product_data['name'] != "" && $product_data['order'] == '') {
		$order_products = [
		    'order_id' => $id,
		    'order_type' => 'delivery_order',
		    'product_category_id' => $product_data['product_category_id'],
		    'unit_id' => $product_data['units'],
		    'quantity' => $product_data['present_shipping'],
		    'present_shipping' => $product_data['present_shipping'],
		    'price' => $product_data['price'],
		    'remarks' => $product_data['remark'],
		];

		$add_order_products = AllOrderProducts::create($order_products);
	    }
	}

	return redirect('delivery_order')->with('validation_message', 'Delivery order details successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
	if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
	    return Redirect::to('delivery_order')->with('error', 'You do not have permission.');
	}
	$inputData = Input::get('formData');
	parse_str($inputData, $formFields);
	$password = $formFields['password'];
	$order_sort_type = $formFields['order_sort_type'];

	if (Hash::check($password, Auth::user()->password)) {
	    DeliveryOrder::find($id)->delete();
	    AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_order')->delete();
	    Session::put('order-sort-type', $order_sort_type);
	    return array('message' => 'success');
	} else {
	    return array('message' => 'failed');
	}
    }

    /*
     * Load the list view of pending delivery order.
     */

    public function pending_delivery_order() {

	$filteron = "";
	$filterby = "";
	$filteron = Input::get('filteron');
	$filterby = Input::get('filterby');

	$delivery_data = 0;
	if (Input::get('order_status')) {

	    if (Input::get('order_status') == 'Inprocess') {

		if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
		    $delivery_data = DeliveryOrder::where('order_status', 'pending')
			    ->orderby($filteron, $filterby)
			    ->with('user', 'customer')
			    ->paginate(20);
		} else {
		    $delivery_data = DeliveryOrder::with('user', 'customer')->where('order_status', 'pending')->paginate(20);
		}
	    } elseif (Input::get('order_status') == 'Delivered') {
		if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
		    $delivery_data = DeliveryOrder::
			    where('order_status', 'completed')
			    ->orderby($filteron, $filterby)
			    ->with('user', 'customer')
			    ->paginate(20);
		} else {
		    $delivery_data = DeliveryOrder::with('user', 'customer')->where('order_status', 'completed')->paginate(20);
		}
	    }
	} else {
	    if ((isset($filteron) && ($filteron != "")) && (isset($filterby) && ($filterby != ""))) {
		$delivery_data = DeliveryOrder::
			where('order_status', 'pending')
			->orderby($filteron, $filterby)
			->with('user', 'customer')
			->paginate(20);
	    } else {
		$delivery_data = DeliveryOrder::with('user', 'customer')->where('order_status', 'pending')->paginate(20);
	    }
	}

	$delivery_data->setPath('pending_delivery_order');
	return view('pending_delivery_order', compact('delivery_data'));
    }

    /*
     * displey the create delivery challan form
     */

    public function create_delivery_challan($id) {

	$delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details')->where('id', $id)->first();
	if (count($delivery_data) < 1) {
	    return redirect('delivery_order')->with('validation_message', 'Inavalid delivery order.');
	}

	$units = Units::all();
	$delivery_locations = DeliveryLocation::all();
	$customers = Customer::all();
	return view('create_delivery_challan', compact('delivery_data', 'units', 'delivery_locations', 'customers'));
    }

    /*
     * save create delivery challan form details for the challan
     */

    public function store_delivery_challan($id) {

	$input_data = Input::all();
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
	$delivery_challan->loaded_by = $input_data['loadedby'];
	$delivery_challan->labours = $input_data['labour'];

	if (isset($input_data['vat_percentage'])) {
	    $delivery_challan->vat_percentage = $input_data['vat_percentage'];
	}

	$delivery_challan->grand_price = $input_data['grand_total'];
	$delivery_challan->remarks = $input_data['challan_remark'];
	$delivery_challan->challan_status = "Pending";
	$delivery_challan->save();

	$delivery_challan_id = DB::getPdo()->lastInsertId();
	$order_products = array();
	foreach ($input_data['product'] as $product_data) {
	    if ($product_data['name'] != "" && $product_data['order'] != "") {
		$order_products = [
		    'order_id' => $delivery_challan_id,
		    'order_type' => 'delivery_challan',
		    'product_category_id' => $product_data['id'],
		    'unit_id' => $product_data['units'],
		    'actual_pieces' => $product_data['actual_pieces'],
		    'actual_quantity' => $product_data['actual_quantity'],
		    'quantity' => $product_data['actual_quantity'],
		    'present_shipping' => $product_data['present_shipping'],
		    'price' => $product_data['price'],
		    'from' => $input_data['order_id'],
		    'parent' => $product_data['order'],
		];
		$add_order_products = AllOrderProducts::create($order_products);
	    } else if ($product_data['name'] != "" && $product_data['order'] == "") {
		$order_products = [
		    'order_id' => $delivery_challan_id,
		    'order_type' => 'delivery_challan',
		    'product_category_id' => $product_data['id'],
		    'unit_id' => $product_data['units'],
		    'actual_pieces' => $product_data['actual_pieces'],
		    'actual_quantity' => $product_data['quantity'],
		    'quantity' => $product_data['quantity'],
		    'present_shipping' => $product_data['present_shipping'],
		    'price' => $product_data['price'],
		    'from' => ''
		];
		$add_order_products = AllOrderProducts::create($order_products);
	    }
	}
	DeliveryOrder::where('id', '=', $input_data['order_id'])->update(array(
	    'order_status' => 'completed'
	));
	return redirect('delivery_order')->with('success', 'One Delivery Challan is successfully created.');
    }

    /*
     * Generate Serial number and print Delivery order
     * as well as send the sms to the customer
     */

    public function print_delivery_order($id) {

	$current_date = date("m/d/");
	$date_letter = 'DO/' . $current_date . "" . $id;
	DeliveryOrder::where('id', $id)->update(array(
	    'serial_no' => $date_letter,
	));

	$delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details', 'unit', 'location')->where('id', $id)->first();
	$units = Units::all();
	$delivery_locations = DeliveryLocation::all();
	$customers = Customer::all();
	/*
	  |------------------- -----------------------
	  | SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
	  | -------------------------------------------
	 */
	$input_data = $delivery_data['delivery_product'];
	$send_sms = Input::get('send_sms');
	if ($send_sms == 'true') {
	    $customer_id = $delivery_data->customer_id;
	    $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
	    if (count($customer) > 0) {
		$total_quantity = '';
		$str = "Dear '" . $customer->owner_name . "'\nDT" . date("j M, Y") . "\nYour DO has been created as follows ";
		foreach ($input_data as $product_data) {
		    $str .= $product_data['order_product_details']->alias_name . ' - ' . $product_data->quantity . ',';
		    $total_quantity = $total_quantity + $product_data->quantity;
		}
		$str .= " Trk No. " . $delivery_data->vehicle_number . ", Drv No. " . $delivery_data->driver_contact_no . ". \nVIKAS ASSOCIATES";

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
	return view('print_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers'));
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

	$product_category = \App\ProductCategory::where('id', $input_data)->first();
	$product_price = $product_category->price;
	$product_sub_category = \App\ProductSubCategory::where('product_category_id', $input_data)->first();
	$product_difference = $product_sub_category['difference'];
	$customer_product = CustomerProductDifference::where('customer_id', $customer_id)
			->where('product_category_id', $input_data)->first();
	$customer_difference = 0;
	if (count($customer_product) > 0) {
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
	$all_del_orders = array();
	$pending_orders = array();

	if (count($delivery_orders) > 0) {

	    foreach ($delivery_orders as $key => $del_order) {
		$delivery_order_quantity = 0;
		$delivery_order_present_shipping = 0;
		if (count($del_order['delivery_product']) > 0) {
		    foreach ($del_order['delivery_product'] as $popk => $popv) {
			$product_size = ProductSubCategory::find($popv->product_category_id);
			if ($popv->unit_id == 1) {
			    $delivery_order_quantity = $delivery_order_quantity + $popv->quantity;
			    $delivery_order_present_shipping = $delivery_order_present_shipping + $popv->present_shipping;
			}
			if ($popv->unit_id == 2) {
			    $delivery_order_quantity = $delivery_order_quantity + ($popv->quantity * $product_size->weight);
			    $delivery_order_present_shipping = $delivery_order_present_shipping + ($popv->present_shipping * $product_size->weight);
			}
			if ($popv->unit_id == 3) {
			    $delivery_order_quantity = $delivery_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
			    $delivery_order_present_shipping = $delivery_order_present_shipping + (($popv->present_shipping / $product_size->standard_length ) * $product_size->weight);
			}
		    }
		}
		$delivery_orders[$key]['total_quantity'] = $delivery_order_quantity;
		$delivery_orders[$key]['present_shipping'] = $delivery_order_present_shipping;
	    }
	}
	return $delivery_orders;
    }

}
