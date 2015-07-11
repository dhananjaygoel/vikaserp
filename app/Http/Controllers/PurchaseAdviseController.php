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
use App\Units;
use App\Http\Requests\StorePurchaseAdvise;
use Redirect;
use Validator;
use DateTime;

class PurchaseAdviseController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');

        if (Input::has('purchaseaAdviseFilter') && Input::get('purchaseaAdviseFilter') != '') {
            $q->where('advice_status', '=', Input::get('purchaseaAdviseFilter'));
        }

        $purchase_advise = $q->paginate(10);
        $purchase_advise->setPath('purchaseorder_advise');

        return View::make('purchase_advise', array('purchase_advise' => $purchase_advise));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $customers = Customer::where('customer_status', '=', 'permanent')->get();

        $delivery_locations = DeliveryLocation::all();

        $units = Units::all();

        return View::make('add_purchase_advise', array('customers' => $customers, 'delivery_locations' => $delivery_locations, 'units' => $units));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StorePurchaseAdvise $request) {
        $input_data = Input::all();
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->with('error', 'Please insert product details');
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
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['supplier_status'] == "existing") {
            $validator = Validator::make($input_data, Customer::$existing_supplier_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['supplier_id'];
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }

        $purchase_advise_array = array();
        $purchase_advise_array['purchase_advice_date'] = date('Y-m-d', strtotime($input_data['bill_date']));
        $purchase_advise_array['supplier_id'] = $customer_id;
        $purchase_advise_array['created_by'] = Auth::id();
        $purchase_advise_array['expected_delivery_date'] = $input_data['expected_delivery_date'];
        $purchase_advise_array['total_price'] = $input_data['total_price'];
        $purchase_advise_array['remarks'] = $input_data['remarks'];
        $purchase_advise_array['vehicle_number'] = $input_data['vehicle_number'];
        $purchase_advise_array['order_for'] = $input_data['order_for'];


        if (isset($input_data['is_vat']) && $input_data['is_vat'] == "exclude_vat") {
            $purchase_advise_array['vat_percentage'] = $input_data['vat_percentage'];
        }
        if (isset($input_data['delivery_location_id']) && $input_data['delivery_location_id'] == "other") {
//            $delivery_location = DeliveryLocation::create(array('area_name' => $input_data['new_location'], 'status' => 'pending'));
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
                    'present_shipping' => $product_data['quantity']
                ];
                $add_purchase_advise_products = PurchaseProducts::create($purchase_advise_products);
            }
        }
        return redirect('purchaseorder_advise')->with('success', 'Purchase advise added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.product_sub_category')->find($id);

        return View::make('view_purchase_advice', array('purchase_advise' => $purchase_advise));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.product_category.product_sub_category')->find($id);

        $locations = DeliveryLocation::all();
        $units = Units::all();

        return View::make('edit_purchase_advise', array('locations' => $locations, 'units' => $units, 'purchase_advise' => $purchase_advise));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        $input_data = Input::all();
        $purchase_advise = PurchaseAdvise::find($id);
        $purchase_advise->update(
                array(
                    'remarks' => $input_data['remarks'],
                    'vehicle_number' => $input_data['vehicle_number']
        ));


        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {

                if (isset($product_data['purchase_product_id']) && $product_data['purchase_product_id'] != '') {
                    $purchase_product = PurchaseProducts::where('id', '=', $product_data['purchase_product_id'])->first();
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
                        'present_shipping' => $product_data['present_shipping'],
                        'actual_pieces' => $product_data['actual_pieces'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark'],
                        'present_shipping' => $product_data['present_shipping']
                    ];

                    $add_purchase_advise_products = PurchaseProducts::create($purchase_advise_products);
                }
            }
        }

        return redirect('purchaseorder_advise')->with('success', 'Purchase advise updated successfully');
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
            return Redirect::to('purchaseorder_advise')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $purchase_advise = PurchaseAdvise::find($id);
            $purchase_advise->delete();
            return Redirect::to('purchaseorder_advise')->with('success', 'Purchase advise Successfully deleted');
        } else {
            return Redirect::to('purchaseorder_advise')->with('error', 'Invalid password');
        }
    }

    public function store_advise() {

        $input_data = Input::all();

        $validator = Validator::make($input_data, PurchaseAdvise::$store_purchase_validation);
        if ($validator->passes()) {

            $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['bill_date']);
            $date = date("Y/m/d", strtotime($date_string));
            $datetime = new DateTime($date);

            $add_purchase_advice_array = [
                'supplier_id' => $input_data['supplier_id'],
                'created_by' => Auth::id(),
                'purchase_advice_date' => $datetime->format('Y-m-d'),
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






            foreach ($input_data['product'] as $product_data) {
                if (($product_data['id'] != "")) {

//                if (isset($product_data['name']) || ($product_data['id'] != "")) {
                    if (isset($product_data['purchase']) && $product_data['purchase'] != "") {

                        if ($product_data['present_shipping'] != "") {
                            
                            $purchase_advice_products = [
                                'purchase_order_id' => $purchase_advice_id,
                                'product_category_id' => $product_data['id'],
                                'unit_id' => $product_data['units'],
                                'actual_pieces' => $product_data['actual_pieces'],
                                'quantity' => $product_data['quantity'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice',
                                'from' => $product_data['purchase'],
                                'present_shipping' => $product_data['present_shipping']
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
                                'from' => $product_data['purchase']
                            ];
                            
                        }
                    } else {
                        if ($product_data['present_shipping'] != "") {
                            $purchase_advice_products = [
                                'purchase_order_id' => $purchase_advice_id,
                                'product_category_id' => $product_data['id'],
                                'unit_id' => $product_data['units'],
                                'actual_pieces' => $product_data['actual_pieces'],
                                'quantity' => $product_data['quantity'],
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
                                'quantity' => $product_data['quantity'],
                                'price' => $product_data['price'],
                                'remarks' => $product_data['remark'],
                                'order_type' => 'purchase_advice'
                            ];
                        }
                    }

                    $add_purchase_advice_products = PurchaseProducts::create($purchase_advice_products);
                }
            }
            return redirect('purchaseorder_advise')->with('flash_message', 'Purchase advice details successfully added.');
        } else {

            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function pending_purchase_advice() {

        $pending_advise = PurchaseAdvise::where('advice_status', '=', 'in_process')->with('purchase_products', 'supplier', 'party')->paginate(10);
        $pending_advise->setPath('pending_purchase_advice');

        return View::make('pending_purchase_advice', array('pending_advise' => $pending_advise));
    }

    public function purchaseorder_advise_challan($id) {

        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.product_category.product_sub_category')->find($id);

        $locations = DeliveryLocation::all();
        $units = Units::all();

        return view('purchaseorder_advise_challan', compact('purchase_advise', 'locations', 'units'));
    }

    public function print_purchase_advise($id) {

        $purchase_advise = PurchaseAdvise::with('supplier', 'location', 'purchase_products.unit', 'purchase_products.product_sub_category')->find(Input::get('pa_id'));


        $current_date = date("M/y/m/");

        $date_letter = 'PO/' . $current_date . "" . Input::get('pa_id');
        PurchaseAdvise::where('id', '=', $id)->update(array(
            'serial_number' => $date_letter
//            'advice_status' => "delivered"
        ));

        return view('print_purchase_advise', compact('purchase_advise'));
    }

}
