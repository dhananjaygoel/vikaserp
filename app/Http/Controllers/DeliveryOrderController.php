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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Hash;
use Auth;

class DeliveryOrderController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $delivery_data = 0;
        if (Input::get('order_status')) {

            if (Input::get('order_status') == 'Inprocess') {
                $delivery_data = DeliveryOrder::where('order_status', 'pending')->paginate(10);
            } elseif (Input::get('order_status') == 'Delivered') {
                $delivery_data = DeliveryOrder::where('order_status', 'completed')->paginate(10);
            }
        } else {
            $delivery_data = DeliveryOrder::paginate(10);
        }



        $delivery_data->setPath('delivery_order');
        return view('delivery_order', compact('delivery_data'));
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
        return view('add_delivery_order', compact('units', 'delivery_locations', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
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
            return Redirect::back()->with('validation_message', 'Please enter at least one product details');
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
        } elseif ($input_data['customer_status'] == "exist_customer") {
            $validator = Validator::make($input_data, array('autocomplete_customer_id' => 'required'));
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }

//        if ($input_data['status'] == 'warehouse') {
//            $order_status = 'warehouse';
//            $supplier_id = 0;
//        }
//        if ($input_data['status'] == 'supplier') {
//            $order_status = 'supplier';
//            $supplier_id = $input_data['supplier_id'];
//        }

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
        $delivery_order->delivery_location_id = $input_data['add_order_location'];
        $delivery_order->vat_percentage = $vat_price;
        $delivery_order->estimate_price = 0;
        $delivery_order->estimated_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
        $delivery_order->expected_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
        $delivery_order->remarks = $input_data['order_remark'];
        $delivery_order->vehicle_number = $input_data['vehicle_number'];
        $delivery_order->driver_name = $input_data['driver_name'];
        $delivery_order->driver_contact_no = $input_data['driver_contact'];
        $delivery_order->order_status = "Pending";

        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $delivery_order->other_location = $input_data['other_location_name'];
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
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.product_category')->where('id', $id)->get();
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::all();

//        echo '<pre>';
//        print_r($delivery_data->toArray());
//        echo '</pre>';
//        exit;
        return view('view_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.product_category')->where('id', $id)->get();
        $customers = Customer::all();

//        echo '<pre>';
//        print_r($delivery_data->toArray());
//        echo '</pre>';
//        exit;

        return view('edit_delivery_order', compact('delivery_data', 'units', 'delivery_locations', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        $input_data = Input::all();
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->with('validation_message', 'Please enter at least one product details');
        }

        $customer_id = 0;
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


            $validator = Validator::make($input_data, array('autocomplete_customer_id' => 'required'));
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
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

        DeliveryOrder::where('id', $id)->update(array(
            'order_id' => 0,
            'order_source' => 'warehouse',
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_order_location'],
            'vat_percentage' => $vat_price,
            'estimate_price' => 0,
            'estimated_delivery_date' => date_format(date_create(date("Y-m-d")), 'Y-m-d'),
            'expected_delivery_date' => date_format(date_create(date("Y-m-d")), 'Y-m-d'),
            'remarks' => $input_data['order_remark'],
            'vehicle_number' => $input_data['vehicle_number'],
            'driver_name' => $input_data['driver_name'],
            'driver_contact_no' => $input_data['driver_contact'],
            'order_status' => "Pending"
        ));


        if ($j != 0) { //if (product list is not empty) 
            $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)
                    ->where('order_type', '=', 'delivery_order')
                    ->delete();

            $order_products = array();
            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] != "") {
                    $order_products = [
                        'order_id' => $id,
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
        }

        return redirect('delivery_order')->with('validation_message', 'Delivery order details successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
            DeliveryOrder::find($id)->delete();
            return redirect('delivery_order')->with('success', 'Delivery order details successfully deleted.');
        } else {
            return redirect('delivery_order')->with('wrong', 'You have entered wrong credentials');
        }
    }

    public function pending_delivery_order() {
        
        $delivery_data = 0;
        if (Input::get('order_status')) {

            if (Input::get('order_status') == 'Inprocess') {
                $delivery_data = DeliveryOrder::with('user')->where('order_status', 'pending')->paginate(10);
            } elseif (Input::get('order_status') == 'Delivered') {
                $delivery_data = DeliveryOrder::with('user')->where('order_status', 'completed')->paginate(10);
            }
        } else {
            $delivery_data = DeliveryOrder::with('user')->where('order_status', 'pending')->paginate(10);
        }



        $delivery_data->setPath('pending_delivery_order');
        
        return view('pending_delivery_order', compact('delivery_data'));
    }
    
    public function create_delivery_challan($id) {
        $delivery_data = DeliveryOrder::with('customer', 'delivery_product.product_category')->where('id', $id)->get();
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::all();

        echo '<pre>';
        print_r($delivery_data->toArray());
        echo '</pre>';
        exit;
        return view('create_delivery_challan', compact('delivery_data', 'units', 'delivery_locations', 'customers'));
        
//        $order = DeliveryOrder::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category', 'customer')->first();
//        $units = Units::all();
//        $delivery_location = DeliveryLocation::all();
//        $customers = Customer::all();
//        return view('create_delivery_challan', compact('order', 'delivery_location', 'units', 'customers'));
    }
    public function store_delivery_challan($id){
        $order = DeliveryOrder::where('id', '=', $id)->with('all_order_products.unit', 'all_order_products.product_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();
        return view('create_delivery_challan', compact('order', 'delivery_location', 'units', 'customers'));
    }
    
}
