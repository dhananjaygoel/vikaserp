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
use App\OrderCancelled;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ManualCompleteOrderRequest;
use App\DeliveryOrder;

class OrderController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index() {
        if ((isset($_GET['order_filter'])) && $_GET['order_filter'] != '') {
            if ($_GET['order_filter'] == 'cancelled') {
                $allorders = Order::where('order_status', '=', $_GET['order_filter'])
                                ->with('customer', 'delivery_location', 'all_order_products', 'order_cancelled')->orderBy('created_at', 'desc')->Paginate(2);
            } else {
                $allorders = Order::where('order_status', '=', $_GET['order_filter'])
                                ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
            }
        } else {
            $allorders = Order::where('order_status', '=', 'pending')->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
        }

        $users = User::all();

        $allorders->setPath('orders');
        return View::make('orders', compact('allorders', 'users', 'cancelledorders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
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
        $i = 0;
        $j = count($input_data['product']);
//        echo $input_data['estimated_date'];exit;
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }

        if ($i == $j) {
            return Redirect::back()->with('flash_message', 'Please insert product details');
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
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
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
        if ($input_data['status1'] == 'include_vat') {
            $vat_price = '';
        }
        if ($input_data['status1'] == 'exclude_vat') {
            $vat_price = $input_data['vat_price'];
        }
//        echo 'other location '.$input_data['location'];exit;
        $order = new Order();
        $order->order_source = $order_status;
        $order->supplier_id = $supplier_id;
        $order->customer_id = $customer_id;
        $order->created_by = Auth::id();
        $order->delivery_location_id = $input_data['add_order_location'];
        $order->vat_percentage = $vat_price;
        $order->estimated_delivery_date = date_format(date_create($input_data['estimated_date']), 'Y-m-d');
        $order->expected_delivery_date = date_format(date_create($input_data['expected_date']), 'Y-m-d');
        $order->remarks = $input_data['order_remark'];
        $order->order_status = "Pending";
        if (isset($input_data['location']) && ($input_data['location'] != "")) {
            $order->other_location = $input_data['location'];
        }
        $order->save();

        $order_id = DB::getPdo()->lastInsertId();
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $order_products = [
                    'order_id' => $order_id,
                    'order_type'=>'order',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_order_products = AllOrderProducts::create($order_products);
            }
        }
        return redirect('orders/' . $order_id . '/edit')->with('flash_message', 'Order details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category', 'customer')->first();
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

        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category', 'customer')->first();
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
            $validator = Validator::make($input_data, Customer::$existing_customer_order_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_id'];
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
        $order = Order::find($id);
        $update_order = $order->update([
            'order_source' => $order_status,
            'supplier_id' => $supplier_id,
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_inquiry_location'],
            'vat_percentage' => $input_data['vat_percentage'],
            'estimated_delivery_date' => date_format(date_create($input_data['estimated_date']), 'Y-m-d'),
            'expected_delivery_date' => date_format(date_create($input_data['expected_date']), 'Y-m-d'),
            'remarks' => $input_data['order_remark'],
            'order_status' => "Pending"
        ]);
        if ($input_data['add_inquiry_location'] == 0) {
            $update_order = $order->update([
                'other_location' => $input_data['other_location_name']
            ]);
        }
        if ($input_data['add_inquiry_location'] != 0) {
            $update_order = $order->update([
                'other_location' => ''
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

        return redirect('orders/' . $id . '/edit')->with('flash_message', 'Order details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {

        $password = Input::get('password');

        if ($password == '') {
            return Redirect::to('orders')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {

            $order = Order::find($id);
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
        return redirect('orders')->with('flash_message', 'One order is cancelled.');
    }

    public function create_delivery_order($id) {

        $order = Order::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();
        return View::make('create_delivery_order', compact('order', 'delivery_location', 'units', 'customers'));
    }

    public function store_delivery_order($id) {
        $input_data = Input::all();
        $validator = Validator::make($input_data, DeliveryOrder::$order_to_delivery_order_rules);
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
            $delivery_order->estimated_delivery_date = $order->estimated_delivery_date;
            $delivery_order->expected_delivery_date = $order->expected_delivery_date;
            $delivery_order->remarks = $input_data['remarks'];
            $delivery_order->vehicle_number = $input_data['vehicle_number'];
            $delivery_order->driver_name = $input_data['driver_name'];
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

}
