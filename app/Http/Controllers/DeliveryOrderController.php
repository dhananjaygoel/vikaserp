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
        $dellivery_data = DeliveryOrder::paginate(10);
        return view('delivery_order', compact('dellivery_data'));
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
                $customer_id = $input_data['customer_name'];
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
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                
                $add_order_products = AllOrderProducts::create($order_products);
            }
        }

//        $delivery_order = new DeliveryOrder();
//        $delivery_order->customer_id = $customer_id;
//        $delivery_order->created_by = Auth::id();
//        $delivery_order->serial_number = '123456789';
//
//        $delivery_order->delivery_location_id = $input_data['add_order_location'];
//        $delivery_order->vat_percentage = $vat_price;
//        $delivery_order->delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
//        $delivery_order->estimate_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
//        $delivery_order->target_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
//
//        $delivery_order->order_status = "Pending";
//        $delivery_order->save();
        
        return redirect('delivery_order')->with('validation_message', 'Delivery order details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        
    }

}
