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
use Config;
use App\PurchaseOrderCanceled;
use App\PurchaseAdvise;
use DateTime;
use App\ProductSubCategory;

class PurchaseOrderController extends Controller {

    public function __construct() {
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
    }

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
                ->with('customer', 'delivery_location', 'user', 'purchase_products.product_category.product_sub_category')
                ->Paginate(10);

        $pending_orders = $this->quantity_calculation($purchase_orders);

        $all_customers = Customer::all();
        $purchase_orders->setPath('purchase_orders');

        return view('purchase_order', compact('purchase_orders', 'all_customers', 'pending_orders'));
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
                $error_msg = $validate->messages();
                return Redirect::back()->withInput()->withErrors($validate);
            }
        }

        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_delivery_date']);
        $date = date("Y/m/d", strtotime($date_string));
        $datetime = new DateTime($date);

        $add_purchase_order_array = [
            'supplier_id' => $customer_id,
            'created_by' => Auth::id(),
//            'delivery_location_id' => $location_id,
            'order_for' => $input_data['order_for'],
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['purchase_order_remark'],
            'order_status' => "pending",
        ];

        /*
         * ------------------- -----------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE ORDER
         * -------------------------------------------
         */
//        $input = Input::all();
//        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
//            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
//            if (count($customer) > 0) {
//                $total_quantity = '';
//                $str = "Dear " . $customer->owner_name . ", your order has been logged for following:";
//                foreach ($input_data['product'] as $product_data) {
//                    if ($product_data['name'] != "") {
//                        $product = ProductSubCategory::where('product_category_id', '=', $product_data['id'])->first();
//                        $str .= $product->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ', ';
//                        $total_quantity = $total_quantity + $product_data['quantity'];
//                    }
//                }
//
//                $str .= " meterial will be despached by " . date("jS F, Y", strtotime($datetime->format('Y-m-d'))) . ". Vikas Associates, 9673000068";
//                $phone_number = $customer->phone_number1;
//                $msg = urlencode($str);
//                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
//                $ch = curl_init($url);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                $curl_scraped_page = curl_exec($ch);
//                curl_close($ch);
//            }
//        }
        $add_purchase_order = PurchaseOrder::create($add_purchase_order_array);
        $purchase_order_id = DB::getPdo()->lastInsertId();
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $add_delivery_location = PurchaseOrder::where('id', $purchase_order_id)->update([
                'delivery_location_id' => 0,
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference'],
//                 'difference' => $input_data['other_location_difference'],
            ]);
//            $location_id = DB::getPdo()->lastInsertId();
        } else {
            $add_delivery_location = PurchaseOrder::where('id', $purchase_order_id)->update([
                'delivery_location_id' => $input_data['purchase_order_location'],
                'other_location' => '',
                'other_location_difference' => '',
            ]);
//            $location_id = $input_data['purchase_order_location'];
        }
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
//        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
//
//            $add_purchase_order_array = [
//                'supplier_id' => $customer_id,
//                'created_by' => Auth::id(),                
//                'order_for' => $input_data['order_for'],
//                'vat_percentage' => $input_data['vat_percentage'],
//                'expected_delivery_date' => $datetime->format('Y-m-d'),
//                'remarks' => $input_data['purchase_order_remark'],
//                'order_status' => "pending"                
//            ];
//        } else {
        $add_purchase_order_array = [
            'is_view_all' => $input_data['viewable_by'],
            'supplier_id' => $customer_id,
            'created_by' => Auth::id(),
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['purchase_order_remark'],
            'order_status' => "pending"
        ];
//        }

        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR NEW ORDER
         * ----------------------------------
         */
//        $input = Input::all();
//        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
//            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
//            if (count($customer) > 0) {
//                $total_quantity = '';
//                $str = "Dear " . $customer->owner_name . ", your order has been edited and changed as following:";
//                foreach ($input_data['product'] as $product_data) {
//                    if ($product_data['name'] != "") {
//                        $product = ProductSubCategory::where('product_category_id', '=', $product_data['id'])->first();
//                        $str .= $product->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ', ';
//                        $total_quantity = $total_quantity + $product_data['quantity'];
//                    }
//                }
//                $str .= " meterial will be despached by " . date("jS F, Y", strtotime($datetime->format('Y-m-d'))) . ". Vikas Associates, 9673000068";
//                $phone_number = $customer->phone_number1;
//                $msg = urlencode($str);
//                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
//                echo '<pre>';
//                print_r($str);
//                echo '</pre>';
//                exit();
//                $ch = curl_init($url);
//                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                $curl_scraped_page = curl_exec($ch);
//                curl_close($ch);
//            }
//        }
        $update_purchase_order = $purchase_order->update($add_purchase_order_array);
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $purchase_order->update([
                'delivery_location_id' => 0,
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference'],
            ]);
//            $location_id = DB::getPdo()->lastInsertId();
        } else {
            $purchase_order->update([
                'delivery_location_id' => $input_data['purchase_order_location'],
                'other_location' => '',
                'other_location_difference' => '',
            ]);
//            $location_id = $input_data['purchase_order_location'];
        }
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

//        foreach ($purchase_orders as $orders) {
//            $check_if_advice_exists = PurchaseAdvise::where('purchase_order_id', '=', $order_id)->with('purchase_products')->get();
//
////            echo '<pre>';
////            print_r($check_if_advice_exists->toArray());
////            echo '</pre>';
//
//
//            foreach ($check_if_advice_exists as $a) {
//                $orders['pending'] = $a->quantity - $a->present_shipping;
//            }
//        }
//        echo $orders;
//        exit;


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

    function quantity_calculation($purchase_orders) {

        $pending_orders = array();
        foreach ($purchase_orders as $order) {

            $purchase_orders = PurchaseAdvise::where('purchase_order_id', $order->id)->with('purchase_products')->get();

            $all_del_orders = array();
            $pending_quantity = 0;
            $total_quantity = 0;

            if (count($purchase_orders) > 0) {

                foreach ($purchase_orders as $del_order) {

                    $del_all_order_products = PurchaseProducts::where('purchase_order_id', $del_order->id)
                                    ->where('from', 'purchase_order')->where('order_type', 'purchase_advice')->get();

                    $order_all_order_products = PurchaseProducts::where('purchase_order_id', $order->id)->where('order_type', 'purchase_order')->get();
                    $del_products = array();
                    $pending_quantity_del = 0;
                    $total_quantity_del = 0;

                    foreach ($del_all_order_products as $products) {

                        $p_qty = $products['present_shipping'];
                        if ($products['unit_id'] != 1) {
                            $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();

                            if ($products['unit_id'] == 2) {
                                $p_qtycalculated_quantity = $p_qty * $product_subcategory['weight'];
                            }
                            if ($products['unit_id'] == 3) {
                                $p_qtycalculated_quantity = ($p_qty / $product_subcategory['standard_length'] ) * $product_subcategory['weight'];
                            }
//                            $p_qtycalculated_quantity = $prod_quantity / $product_subcategory['weight'];
                            $p_qty = $p_qtycalculated_quantity;
                        }
                        $pending_quantity_del = $pending_quantity_del + $p_qty;
                        $prod_quantity = $products['quantity'];
                        if ($products['unit_id'] != 1) {
                            $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();

                            if ($products['unit_id'] == 2) {
                                $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                            }
                            if ($products['unit_id'] == 3) {
                                $calculated_quantity = ($prod_quantity / $product_subcategory['standard_length'] ) * $product_subcategory['weight'];
                            }
//                                    $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                            $prod_quantity = $calculated_quantity;
                        }




                        $total_quantity_del = $total_quantity_del + $prod_quantity;

                        $temp_products = array();
                        $temp_products['id'] = $del_order->id;
                        $temp_products['order_id'] = $order->id;
                        $temp_products['product_id'] = $products['product_category_id'];
                        $temp_products['unit'] = $products['unit_id'];
                        $temp_products['total_pending_quantity'] = (int) ($pending_quantity_del);
                        $temp_products['total_quantity'] = (int) $total_quantity_del;
                        array_push($del_products, $temp_products);
                    }

                    array_push($all_del_orders, $del_products);
                }

                if (count($del_all_order_products) > 0) {
                    $calculated_pendings = array();
                    $pend_qty = 0;
                    $total_qty = 0;
                    $total_pending;
                    foreach ($all_del_orders as $key => $dos) {
                        $len = count($dos);
                        $index = 0;
                        if ($len > 0) {
                            $index = $len - 1;
                        }
                        $tot_qty = $dos[$index]['total_quantity'];
                        foreach ($all_del_orders as $array2) {
                            if ($array2[$index]['order_id'] == $dos[$index]['order_id'] && $array2[$index]['product_id'] == $dos[$index]['product_id'] && $array2[$index]['unit'] == $dos[$index]['unit']) {

                                if ($array2[$index]['total_quantity'] > $tot_qty) {
                                    $tot_qty = $array2[$index]['total_quantity'];
//                                echo 'test'.$tot_qty;exit;
                                }
                            }
                        }
//                    if($dos['unit'] != $all_del_orders[$key+1]['unit']){    
//                        $tot_qty = $dos[$len - 1]['total_quantity'];
//                    }

                        $pend_qty = $pend_qty + $dos[$index]['total_pending_quantity'];
                    }
//                echo 'test' . $tot_qty;
//                exit;
                    $total_qty = $tot_qty;
                    $total_pending = $tot_qty - $pend_qty;
                    $temp = array();
                    $temp['id'] = $order->id;

                    $temp['total_pending_quantity'] = (int) ($total_pending);
                    $temp['total_quantity'] = (int) $total_qty;
                    array_push($pending_orders, $temp);
                }
            } else {



                $all_purchase_products = PurchaseProducts::where('purchase_order_id', $order->id)->where('order_type', 'purchase_order')->get();

                foreach ($all_purchase_products as $products) {

                    $kg = Units::first();
                    $prod_quantity = $products['quantity'];
                    if ($products['unit_id'] != 1) {
                        $product_subcategory = \App\ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();
                        if ($products['unit_id'] == 2) {
                            $calculated_quantity = $prod_quantity * $product_subcategory['weight'];
                        }
                        if ($products['unit_id'] == 3) {
                            $calculated_quantity = ($prod_quantity / $product_subcategory['standard_length'] ) * $product_subcategory['weight'];
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
