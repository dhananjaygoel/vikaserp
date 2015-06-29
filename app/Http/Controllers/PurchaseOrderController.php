<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeliveryLocation;
use App\Units;
use App\Customer;
use App\Http\Requests\PurchaseOrderRequest;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\PurchaseOrder;
use App\PurchaseProducts;
use Auth;
use DB;
use Hash;
use Mail;
use App\PurchaseOrderCanceled;
use App\PurchaseAdvise;
use DateTime;

class PurchaseOrderController extends Controller {

    public function index() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $q = PurchaseOrder::query();

        if ((isset($_GET['pending_purchase_order'])) && $_GET['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $_GET['pending_purchase_order'])->get();
        }
        if ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }


        if (Auth::user()->role_id > 1) {
            if ((isset($_GET['purchase_order_filter'])) && $_GET['purchase_order_filter'] != '') {
                $q = $q->where('order_status', '=', $_GET['purchase_order_filter'])
                        ->where('is_view_all', '=', 0);
            } else {
                $q = $q->where('is_view_all', '=', 0);
            }
        }

        if (Auth::user()->role_id < 1) {
            if ((isset($_GET['purchase_order_filter'])) && $_GET['purchase_order_filter'] != '') {
                $q = $q->where('order_status', '=', $_GET['purchase_order_filter']);
            }
        }

        $purchase_orders = $q->orderBy('created_at', 'desc')
                ->with('customer', 'delivery_location', 'user', 'purchase_products')
                ->Paginate(10);
        $all_customers = Customer::all();
        $purchase_orders->setPath('purchase_orders');
        return view('purchase_order', compact('purchase_orders', 'all_customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::where('customer_status', '=', 'permanent')->get();
        return view('add_purchase_order', compact('units', 'delivery_locations', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PurchaseOrderRequest $request) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

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

        if ($input_data['supplier_status'] == "new_supplier") {
            $validator = Validator::make($input_data, Customer::$new_supplier_inquiry_rules);
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
        } elseif ($input_data['supplier_status'] == "existing_supplier") {

            $validate = Validator::make($input_data, array('autocomplete_supplier_id' => 'required'));

            if ($validate->passes()) {
                $customer_id = $input_data['autocomplete_supplier_id'];

                //send mail
                if (isset($input_data['send_email'])) {
                    $customers = Customer::find($input_data['autocomplete_supplier_id']);

                    Mail::send('emails.purchase_order_add_email', ['key' => $customers->owner_name], function($message) {
                        $message->to('deepakw@agstechnologies.com', 'John Smith')->subject('Purchase details updated!');
                    });
                }
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }

        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $add_delivery_location = DeliveryLocation::create([
                        'area_name' => $input_data['other_location_name'],
                        'status' => 'pending'
            ]);
            $location_id = DB::getPdo()->lastInsertId();
        } else {

            $location_id = $input_data['purchase_order_location'];
        }

        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_delivery_date']);
        $date = date("Y-m-d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);

        $add_purchase_order_array = [
            'supplier_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $location_id,
            'order_for' => $input_data['order_for'],
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['purchase_order_remark'],
            'inquiry_status' => "pending",
        ];

        $add_purchase_order = PurchaseOrder::create($add_purchase_order_array);
        $purchase_order_id = DB::getPdo()->lastInsertId();
        $purchase_order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $purchase_order_products = [
                    'purchase_order_id' => $purchase_order_id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_purchase_order_products = PurchaseProducts::create($purchase_order_products);
            }
        }

        return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $purchase_orders = PurchaseOrder::where('id', '=', $id)->with('purchase_products.unit', 'purchase_products.product_category.product_sub_category', 'customer')->first();
        return view('purchase_order_details', compact('purchase_orders'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $purchase_order = PurchaseOrder::where('id', '=', $id)->with('purchase_products.unit', 'purchase_products.product_category.product_sub_category', 'customer')->first();
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::where('customer_status', '=', 'permanent')->get();
        return view('edit_purchase_order', compact('purchase_order', 'delivery_locations', 'units', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
        $customers = Customer::find($input_data['supplier_id']);

        if ($input_data['supplier_status'] == "new_supplier") {
            $validator = Validator::make($input_data, Customer::$new_supplier_inquiry_rules);
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
        } elseif ($input_data['supplier_status'] == "existing_supplier") {

            //send mail
            if (isset($input_data['send_email'])) {
                $customers = Customer::find($input_data['autocomplete_supplier_id']);

                Mail::send('emails.purchase_order_email', ['key' => $customers->owner_name], function($message) {
                    $message->to('deepakw@agstechnologies.com', 'John Smith')->subject('Purchase details updated');
                });
            }

            $validator = Validator::make($input_data, Customer::$existing_supplier_inquiry_rules);
            if ($validator->passes()) {

                $customer_id = $input_data['autocomplete_supplier_id'];
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }

        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_delivery_date']);
        $date = date("Y-m-d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);


        $purchase_order = PurchaseOrder::find($id);
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {

            $add_purchase_order_array = [
                'supplier_id' => $customer_id,
                'created_by' => Auth::id(),
                'delivery_location_id' => $input_data['purchase_order_location'],
                'order_for' => $input_data['order_for'],
                'vat_percentage' => $input_data['vat_percentage'],
                'expected_delivery_date' => $datetime->format('Y-m-d'),
                'remarks' => $input_data['purchase_order_remark'],
                'inquiry_status' => "pending",
                'other_location' => $input_data['other_location_name']
            ];
        } else {
            $add_purchase_order_array = [
                'is_view_all' => $input_data['viewable_by'],
                'supplier_id' => $customer_id,
                'created_by' => Auth::id(),
                'delivery_location_id' => $input_data['purchase_order_location'],
                'vat_percentage' => $input_data['vat_percentage'],
                'expected_delivery_date' => $datetime->format('Y-m-d'),
                'remarks' => $input_data['purchase_order_remark'],
                'inquiry_status' => "pending"
            ];
        }

        $update_purchase_order = $purchase_order->update($add_purchase_order_array);
        $purchase_order_products = array();
        $delete_old_purchase_products = PurchaseProducts::where('purchase_order_id', '=', $id)->delete();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $purchase_order_products = [
                    'purchase_order_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_purchase_order_products = PurchaseProducts::create($purchase_order_products);
            }
        }
        return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully updated.');
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
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_order = PurchaseOrder::find($id)->delete();
            $delete_purchase_products = PurchaseProducts::where('purchase_order_id', '=', $id)->delete();
            return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully deleted.');
        } else {
            return redirect('purchase_orders')->with('flash_message', 'Please enter a correct password.');
        }
    }

    public function create_purchase_advice($order_id) {

        $purchase_orders = PurchaseOrder::where('id', '=', $order_id)->with('purchase_products.unit', 'purchase_products.product_category.product_sub_category', 'customer', 'purchase_advice.purchase_products')->first();

        foreach ($purchase_orders as $orders) {
            $check_if_advice_exists = PurchaseAdvise::where('purchase_order_id', '=', $order_id)->with('purchase_products')->get();
            foreach ($check_if_advice_exists as $a) {
                $orders['pending'] = $a->quantity - $a->present_shipping;
            }
        }

        return view('create_purchase_advice', compact('purchase_orders'));
    }

    public function manual_complete() {
        $input_data = Input::all();
        $purchase_order_canceled = PurchaseOrderCanceled::create([
                    'purchase_order_id' => $input_data['purchase_order_id'],
                    'purchase_type' => $input_data['module_name'],
                    'reason' => $input_data['reason']
        ]);
        $change_status = PurchaseOrder::where('id', '=', $input_data['purchase_order_id'])->update(array('order_status' => 'canceled'));
        return redirect('purchase_orders')->with('flash_message', 'Successfully completed purchase order');
    }

    public function purchase_order_report() {
        $q = PurchaseOrder::query();
        $q->where('order_status', '=', 'pending')
                ->orderBy('created_at', 'desc')
                ->with('customer', 'delivery_location', 'user', 'purchase_products');
        if ((isset($_GET['pending_purchase_order'])) && $_GET['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $_GET['pending_purchase_order'])->get();
        }
        if ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($_GET['order_for_filter'])) && $_GET['order_for_filter'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }
        if (Auth::user()->role_id > 1) {
            $q->where('is_view_all', '=', 0);
        }
        $purchase_orders = $q->Paginate(10);
        $purchase_orders->setPath('purchase_order_report');
        $all_customers = Customer::all();
        return view('purchase_order_report', compact('purchase_orders', 'all_customers'));
    }

}
