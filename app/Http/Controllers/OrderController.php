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
use App\ProductCategory;
use Input;
use DB;
use Auth;
use App\User;
use Hash;
use Mail;
use App\OrderCancelled;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ManualCompleteOrderRequest;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\ProductSubCategory;
use DateTime;

class OrderController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index() {
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
        if (isset($_GET['size_filter']) && $_GET['size_filter'] != '') {
            $size = $_GET['size_filter'];
            $q->with('all_order_products')
                    ->whereHas('all_order_products.product_category.product_sub_categories', function($query) use ($size) {
                        $query->where('size', '=', $size);
                    });
        }else{
            $q->with('all_order_products');
        }
        
        $allorders = $q->with('customer', 'delivery_location', 'order_cancelled')
                        ->orderBy('created_at', 'desc')->paginate(10);
        $users = User::all();
        $customers = Customer::all();
        $delivery_location = DeliveryLocation::all();
        $delivery_order = DeliveryOrder::all();
//        $allorder_products = AllOrderProducts::where('order_type', 'order')->groupBy('quantity')->get();
        $product_size = ProductSubCategory::all();

        $users = User::all();
        $pending_orders = $this->checkpending_quantity($allorders);

        $allorders->setPath('orders');
        return View::make('orders', compact('delivery_location', 'customers', 'allorders', 'users', 'cancelledorders', 'pending_orders', 'product_size'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::all();
        return View::make('add_orders', compact('customers', 'units', 'delivery_locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PlaceOrderRequest $request) {

        $input_data = Input::all();

//        echo $input_data['existing_customer_name'];exit;
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                $customers = new Customer();
                $customers->owner_name = $input_data['customer_name'];
                $customers->contact_person = $input_data['contact_person'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
//                $customers->save();
//                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];
                //send mail
                if (isset($input_data['send_email'])) {
                    $customers = Customer::find($customer_id);

                    Mail::send('emails.order_complete_email', ['key' => $customers->owner_name], function($message) {
                        $message->to('deepakw@agstechnologies.com', 'John Smith')->subject('Order details created');
                    });
                }
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "" && $product_data['quantity'] == "") {
                $i++;
            }
//            echo $product_data['price'];
        }
//        exit;

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
//        $order->estimated_delivery_date = date_format(date_create($input_data['estimated_date']), 'Y-m-d');

        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);

        $order->expected_delivery_date = $datetime->format('Y-m-d');

        $order->remarks = $input_data['order_remark'];
        $order->order_status = "Pending";
        if (isset($input_data['location']) && ($input_data['location'] != "")) {
            $order->delivery_location_id = 0;
            $order->other_location = $input_data['location'];
            $order->other_location_difference = $input_data['other_location_difference'];
        } else {
            $order->delivery_location_id = $input_data['add_order_location'];
        }
        $order->save();

        $order_id = DB::getPdo()->lastInsertId();
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
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
        return redirect('orders')->with('flash_message', 'Order details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category.product_sub_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();

        return View::make('order_detail', compact('order', 'delivery_location', 'units', 'customers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category.product_sub_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();

        return View::make('edit_order', compact('order', 'delivery_location', 'units', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, PlaceOrderRequest $request) {

        $input_data = Input::all();
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

        if (isset($input_data['customer_status']) && $input_data['customer_status'] == "new_customer") {
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
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif (isset($input_data['customer_status']) && $input_data['customer_status'] == "existing_customer") {

            //mail
            $validator = Validator::make($input_data, Customer::$existing_customer_order_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];

                //send mail
                if (isset($input_data['send_email'])) {
                    $customers = Customer::find($customer_id);

                    Mail::send('emails.order_complete_email', ['key' => $customers->owner_name], function($message) {
                        $message->to('deepakw@agstechnologies.com', 'John Smith')->subject('Order details updated');
                    });
                }
            } else {
                $error_msg = $validator->messages();
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

//        $order->expected_delivery_date = $datetime->format('Y-m-d');
        $order = Order::find($id);
        $update_order = $order->update([
            'order_source' => $order_status,
            'supplier_id' => $supplier_id,
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_inquiry_location'],
            'vat_percentage' => $input_data['vat_percentage'],
//            'estimated_delivery_date' => date_format(date_create($input_data['estimated_date']), 'Y-m-d'),
            'expected_delivery_date' => $datetime->format('Y-m-d'),
//            'expected_delivery_date' => date_format(date_create($input_data['expected_date']), 'Y-m-d'),
            'remarks' => $input_data['order_remark'],
            'order_status' => "Pending"
        ]);
        if ($input_data['add_inquiry_location'] == 0) {
            $update_order = $order->update([
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference']
            ]);
        }
        if ($input_data['add_inquiry_location'] != 0) {
            $update_order = $order->update([
                'other_location' => '',
                'other_location_difference' => ''
            ]);
        }

        $order_products = array();

        $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'order')->delete();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
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

        return redirect('orders')->with('flash_message', 'Order details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');

        if ($password == '') {
            return Redirect::to('orders')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {

            $order = Order::find($id);

            $all_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'order');

            foreach ($all_order_products as $products) {
                $products->delete();
            }
            $order->delete();
            return redirect('orders')->with('flash_message', 'One record is deleted.');
        } else {
            return Redirect::back()->with('flash_message', 'Password entered is not valid.');
        }
    }

    /*
     * Manual Complete order
     */

    public function manual_complete_order(ManualCompleteOrderRequest $request) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $input_data = Input::all();

        $order_id = $input_data['order_id'];
        $reason_type = $input_data['reason_type'];
        $reason = $input_data['reason'];

        $cancel_order = OrderCancelled::create([
                    'order_id' => $order_id,
                    'order_type' => 'Order',
                    'reason_type' => $reason_type,
                    'reason' => $reason,
                    'cancelled_by' => Auth::id()
        ]);
        $order = Order::find($order_id);
        $update_order = $order->update([
            'order_status' => "Cancelled"
        ]);

        //send mail
        $orders = Order::where('id', '=', $input_data['order_id'])
                        ->with('customer')->first();

        if (isset($input_data['send_email']) && $orders['customer']->email != "") {

            $customers = Customer::find($input_data['autocomplete_supplier_id']);

            Mail::send('emails.order_complete_email', ['key' => $orders['customer']->owner_name], function($message) {
//                $message->to($orders['customer']->email, 'John Smith')->subject('Order Complete!');
                $message->to('deepakw@agstechnologies.com', 'John Smith')->subject('Order Complete!');
            });
        }



        return redirect('orders')->with('flash_message', 'One order is cancelled.');
    }

    public function create_delivery_order($id) {

        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();
        $pending_orders = $this->pending_quantity_order($id);

        return View::make('create_delivery_order', compact('order', 'delivery_location', 'units', 'customers', 'pending_orders'));
    }

    public function pending_quantity_order($id) {
        $pending_orders = array();

        $delivery_orders = DeliveryOrder::where('order_id', $id)->get();
        $pending_quantity = 0;
        $total_quantity = 0;
        foreach ($delivery_orders as $del_order) {
//            $all_order_products = AllOrderProducts::where('order_id', $del_order->id)->where('order_type', 'delivery_order')->get();
//            $pending_quantity = 0;
//            $total_quantity = 0;
//            foreach ($all_order_products as $products) {
//                $p_qty = $products['quantity'] - $products['present_shipping'];
//                $temp = array();
//                $temp['order_id'] = $id;
//                $temp['id'] = $products['id'];
//                $temp['product_id'] = $products['product_category_id'];
//                $temp['total_pending_quantity'] = $p_qty;
//                array_push($pending_orders, $temp);
//            }
            $del_all_order_products = AllOrderProducts::where('order_id', $del_order->id)->where('order_type', 'delivery_order')->get();
            $order_all_order_products = AllOrderProducts::where('order_id', $id)->where('order_type', 'order')->get();
            foreach ($del_all_order_products as $products) {
                $p_qty = $products['present_shipping'];
                $pending_quantity = $pending_quantity + $p_qty;
                $kg = Units::first();


                foreach ($order_all_order_products as $order_products) {
                    if ($order_products['product_category_id'] == $products['product_category_id']) {
                        $prod_quantity = $order_products['quantity'];
                        if ($products['unit_id'] != 1) {
                            $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();

                            if ($products['unit_id'] == 2) {
                                $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                            }
                            if ($products['unit_id'] == 3) {
                                $calculated_quantity = ($prod_quantity / $product_subcategory['size'] ) * $product_subcategory['weight'];
                            }
                            $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                            $prod_quantity = $calculated_quantity;
                        }
                        $total_quantity = $prod_quantity;
                    } else {
                        $prod_quantity = $products['quantity'];
                        if ($products['unit_id'] != 1) {

                            $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();

                            if ($products['unit_id'] == 2) {
                                $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                            }
                            if ($products['unit_id'] == 3) {
                                $calculated_quantity = ($prod_quantity / $product_subcategory['size'] ) * $product_subcategory['weight'];
                            }
                            $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                            $prod_quantity = $calculated_quantity;
                        }
                        $total_quantity = $total_quantity + $prod_quantity;
                    }
                }
            }
            $temp = array();
            $temp['product_id'] = $products['product_category_id'];
            $temp['id'] = $id;
            $temp['total_pending_quantity'] = (int) ($total_quantity - $pending_quantity);
            $temp['total_quantity'] = (int) $total_quantity;
            $product = 0;

            if (count($pending_orders) > 0) {
                foreach ($pending_orders as $key => $pendings) {
                    if ($pending_orders[$key]['product_id'] == $temp['product_id']) {
                        $product = 1;
                        $pending_orders[$key]['total_pending_quantity'] = $pending_orders[$key]['total_pending_quantity'] - $temp['total_pending_quantity'];      
                    }
                }
            }
            if ($product == 0) {
                array_push($pending_orders, $temp);
            }

        }


//        echo '<pre>';
//        print_r($pending_orders);
//        echo '</pre>';
//        exit;
        return $pending_orders;
    }

    public function store_delivery_order($id) {
        $input_data = Input::all();

        $validator = Validator::make($input_data, Order::$order_to_delivery_order_rules);
        if ($validator->passes()) {
            $user = Auth::user();
            $order = Order::where('id', '=', $id)->with('all_order_products')->first();
            $delivery_order = new DeliveryOrder();
            $delivery_order->order_id = $id;
            $delivery_order->customer_id = $input_data['customer_id'];
            $delivery_order->order_source = $order->order_source;
            $delivery_order->created_by = $user->id;
            $delivery_order->delivery_location_id = $order->delivery_location_id;
            $delivery_order->other_location = $order->other_location;
            $delivery_order->vat_percentage = $order->vat_percentage;
//            $delivery_order->estimated_delivery_date = $order->estimated_delivery_date;
            $delivery_order->expected_delivery_date = $order->expected_delivery_date;
            $delivery_order->remarks = $input_data['remarks'];
            $delivery_order->vehicle_number = $input_data['vehicle_number'];
//            $delivery_order->driver_name = $input_data['driver_name'];
            $delivery_order->driver_contact_no = $input_data['driver_contact'];
            $delivery_order->order_status = 'Pending';
            $delivery_order->save();

            $order_products = array();
            $order_id = DB::getPdo()->lastInsertId();
            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] != "") {
                    $order_products = [
                        'order_id' => $order_id,
                        'order_type' => 'delivery_order',
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'quantity' => $product_data['quantity'],
                        'present_shipping' => $product_data['present_shipping'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark']
                    ];
                    $add_order_products = AllOrderProducts::create($order_products);
                }
            }
            return redirect('orders')->with('flash_message', 'One order converted to Delivery order.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    function checkpending_quantity($allorders) {
        $pending_orders = array();
        foreach ($allorders as $order) {
            $delivery_orders = DeliveryOrder::where('order_id', $order->id)->with('delivery_product')->get();
//            echo '<pre>';
//            print_r($delivery_orders->toArray());
//            echo '</pre>';

            $pending_quantity = 0;

            $total_quantity = 0;
            if (count($delivery_orders) > 0) {

                foreach ($delivery_orders as $del_order) {
                    $del_all_order_products = AllOrderProducts::where('order_id', $del_order->id)->where('order_type', 'delivery_order')->get();
                    $order_all_order_products = AllOrderProducts::where('order_id', $order->id)->where('order_type', 'order')->get();
                    foreach ($del_all_order_products as $products) {
                        $p_qty = $products['present_shipping'];
                        $pending_quantity = $pending_quantity + $p_qty;
                        $kg = Units::first();


                        foreach ($order_all_order_products as $order_products) {
                            if ($order_products['product_category_id'] == $products['product_category_id']) {
                                $prod_quantity = $order_products['quantity'];
                                if ($products['unit_id'] != 1) {
                                    $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();

                                    if ($products['unit_id'] == 2) {
                                        $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                                    }
                                    if ($products['unit_id'] == 3) {
                                        $calculated_quantity = ($prod_quantity / $product_subcategory['size'] ) * $product_subcategory['weight'];
                                    }
                                    $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                                    $prod_quantity = $calculated_quantity;
                                }
                                $total_quantity = $prod_quantity;
                            } else {
                                $prod_quantity = $products['quantity'];
                                if ($products['unit_id'] != 1) {

                                    $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();

                                    if ($products['unit_id'] == 2) {
                                        $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                                    }
                                    if ($products['unit_id'] == 3) {
                                        $calculated_quantity = ($prod_quantity / $product_subcategory['size'] ) * $product_subcategory['weight'];
                                    }
                                    $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                                    $prod_quantity = $calculated_quantity;
                                }
                                $total_quantity = $total_quantity + $prod_quantity;
                            }
                        }
                    }
                }
                $temp = array();
                $temp['id'] = $order->id;
                $temp['total_pending_quantity'] = (int) ($total_quantity - $pending_quantity);
                $temp['total_quantity'] = (int) $total_quantity;
//                echo $pending_quantity;
//                echo '<pre>';
//                print_r($temp);
//                echo '</pre>';
//                exit;
                array_push($pending_orders, $temp);
            } else {

                $all_order_products = AllOrderProducts::where('order_id', $order->id)->where('order_type', 'order')->get();

                foreach ($all_order_products as $products) {
                    $kg = Units::first();
                    $prod_quantity = $products['quantity'];
                    if ($products['unit_id'] != 1) {
                        $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();
                        if ($products['unit_id'] == 2) {
                            $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                        }
                        if ($products['unit_id'] == 3) {
                            $calculated_quantity = ($prod_quantity / $product_subcategory['size'] ) * $product_subcategory['weight'];
                        }
//                        $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                        $prod_quantity = $calculated_quantity;
                    }
                    $total_quantity = $total_quantity + $prod_quantity;
                }

                $temp = array();
                $temp['id'] = $order->id;
                $temp['total_pending_quantity'] = (int) $total_quantity;
                $temp['total_quantity'] = (int) $total_quantity;

                array_push($pending_orders, $temp);
            }
        }
//        echo '<pre>';
//        print_r($pending_orders);
//        echo '</pre>';
//        exit;
        return $pending_orders;
    }

}
