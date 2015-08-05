<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseChallan;
use App\PurchaseProducts;
use App\Http\Requests\PurchaseChallanRequest;
use Input;
use Closure;
use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;
use App;
use Auth;
use Config;
use App\Quotation;
use App\DeliveryLocation;
use App\Customer;
use App\ProductSubCategory;
use App\PurchaseAdvise;

class PurchaseChallanController extends Controller {

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
     *
     * @return Response
     */
    public function index() {

        if (isset($_GET['order_filter']) && $_GET['order_filter'] != '') {
            $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->where('order_status', $_GET['order_filter'])
                    ->orderBy('created_at', 'desc')
                    ->Paginate(20);
        } else {
            $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->where('order_status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->Paginate(20);
        }

        $purchase_challan->setPath('purchase_challan');
        return view('purchase_challan', compact('purchase_challan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PurchaseChallanRequest $request) {

        $input_data = Input::all();
        $add_challan = new PurchaseChallan();
        $add_challan->expected_delivery_date = $request->input('bill_date');
        $add_challan->purchase_advice_id = $request->input('purchase_advice_id');
        $add_challan->purchase_order_id = $request->input('purchase_order_id');
        $add_challan->delivery_location_id = $request->input('delivery_location_id');
        $add_challan->serial_number = $request->input('serial_no');
        $add_challan->supplier_id = $request->input('supplier_id');
        $add_challan->created_by = $request->input('created_by');
        $add_challan->vehicle_number = $request->input('vehicle_number');
        $add_challan->discount = $request->input('discount');
        $add_challan->unloaded_by = $request->input('unloaded_by');
        $add_challan->round_off = $request->input('round_off');
        $add_challan->labours = $request->input('labour');
        $add_challan->remarks = $request->input('remark');
        $add_challan->grand_total = $request->input('grand_total');
        $add_challan->order_status = 'pending';
        $add_challan->freight = $input_data['Freight'];
        $add_challan->save();

        $challan_id = DB::getPdo()->lastInsertId();
        $challan = PurchaseChallan::find($challan_id);
        PurchaseAdvise::where('id', '=', $request->input('purchase_advice_id'))->update(array(
            'advice_status' => 'delivered'
        ));
        if (isset($input_data['billno']) && $input_data['billno'] != '') {
            $challan->update([
                'bill_number' => $request->input('billno')
            ]);
        }
        if (isset($input_data['vat_percentage']) && $input_data['vat_percentage'] != '') {
            $challan->update([
                'vat_percentage' => $request->input('vat_percentage')
            ]);
        }
        $input_data = Input::all();
        $order_products = array();

        foreach ($input_data['product'] as $product_data) {
            if (isset($product_data['id']) && $product_data['id'] != "") {
                $order_products = [
                    'purchase_order_id' => $challan_id,
                    'order_type' => 'purchase_challan',
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => $product_data['unit_id'],
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price'],
                    'parent' => $product_data['id'],
                ];

                $add_order_products = PurchaseProducts::create($order_products);
            } else {
                $order_products = [
                    'purchase_order_id' => $challan_id,
                    'order_type' => 'purchase_challan',
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => $product_data['unit_id'],
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price']
                ];

                $add_order_products = PurchaseProducts::create($order_products);
            }
        }

        return redirect('purchase_challan')->with('success', 'Challan details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        $purchase_challan = PurchaseChallan::with('purchase_advice', 'delivery_location', 'supplier', 'purchase_product.purchase_product_details', 'purchase_product.unit')->where('id', $id)->first();
        if (count($purchase_challan) < 1) {
            return redirect('purchase_challan')->with('flash_message', 'Challan not found');
        }

        return view('view_purchase_challan', compact('purchase_challan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
//    public function edit($id) {
//
//        $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'purchase_product.product_sub_category', 'purchase_product.unit')->where('id', $id)->first();
//        if (count($purchase_challan) < 1) {
//            return redirect('purchase_challan')->with('flash_message', 'Challan not found');
//        }
//        return view('edit_purchase_challan', compact('purchase_challan'));
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
//    public function update($id, PurchaseChallanRequest $request) {
//
//        $challan_data = Input::all();
//        $purchase = array(
//            'vehicle_number' => $request->input('vehicle_number'),
//            'freight' => $request->input('Freight'),
//            'unloaded_by' => $request->input('unloaded_by'),
//            'labours' => $request->input('labour'),
//            'bill_number' => $request->input('billno'),
//            'remarks' => $request->input('remarks'),
//            'remarks' => $request->input('remarks'),
//            'discount' => $request->input('discount')
//        );
//
//        PurchaseChallan::where('id', $id)
//                ->update($purchase);
//
//        PurchaseProducts::where('purchase_order_id', $id)
//                ->where('order_type', 'purchase_challan')
//                ->delete();
//
//        $input_data = Input::all();
//
//        $order_products = array();
//        foreach ($input_data['product'] as $product_data) {
//            $order_products = [
//                'purchase_order_id' => $id,
//                'order_type' => 'purchase_challan',
//                'product_category_id' => $product_data['product_category_id'],
//                'unit_id' => $product_data['unit_id'],
//                'quantity' => $product_data['quantity'],
//                'present_shipping' => $product_data['present_shipping'],
//                'price' => $product_data['price'],
//            ];
//
//            $add_order_products = PurchaseProducts::create($order_products);
//        }
//
//        return redirect('purchase_challan')->with('success', 'Challan details successfully updated');
//    }

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
            $delete_purchase_challan = PurchaseChallan::find($id)->delete();
            return redirect('purchase_challan')->with('flash_success_message', 'Purchase challan details successfully deleted.');
        } else
            return redirect('purchase_challan')->with('flash_message', 'Please enter a correct password');
    }

    public function print_purchase_challan($id) {

        $current_date = date("m/d");

        $date_letter = 'PC/' . $current_date . "/" . $id;
        PurchaseChallan::where('id', $id)->update(array(
            'serial_number' => $date_letter,
            'order_status' => "Completed"
        ));

        $purchase_challan = PurchaseChallan::with('purchase_advice', 'delivery_location', 'supplier', 'all_purchase_products.purchase_product_details', 'all_purchase_products.unit')->where('id', $id)->first();

        /*
         * ------------------- -----------------------
         * SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
         * -------------------------------------------
         */
        $input_data = $purchase_challan['all_purchase_products'];
        $send_sms = Input::get('send_sms');
        if ($send_sms == 'true') {
            $customer_id = $purchase_challan->supplier_id;
            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear '" . $customer->owner_name . "'\n your meterial has been desp as follows ";
                foreach ($input_data as $product_data) {
                    $product = ProductSubCategory::find($product_data->product_category_id);
                    if ($product_data['unit']->id == 1) {
                        $total_quantity = $total_quantity + $product_data->quantity;
                    }
                    if ($product_data['unit']->id == 2) {
                        $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
                    }
                    if ($product_data['unit']->id == 3) {
                        $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
                    }
                }
                $str .= " Trk No. " . $purchase_challan['purchase_advice']->vehicle_number
                        . ", Qty. " . round($input_data->sum('quantity'), 2)
                        . ", Amt. " . $purchase_challan->grand_total
                        . ", Due by " . date("jS F, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))
                        . ", . Vikas Associates, 9673000068";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
        return view('print_purchase_challan', compact('purchase_challan'));
    }

}
