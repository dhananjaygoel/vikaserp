<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\DeliveryChallan;
use App\Order;
use App\AllOrderProducts;
use App\DeliveryOrder;
use Input;
use Validator;
use Redirect;
use App\User;
use Auth;
use App;
use Hash;
use Config;
use App\CustomerProductDifference;
use App\Units;
use App\DeliveryLocation;
use App\Customer;
use App\ProductSubCategory;

class DeliveryChallanController extends Controller {

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

        if ((isset($_GET['status_filter'])) && $_GET['status_filter'] != '') {
            $allorders = DeliveryChallan::where('challan_status', '=', $_GET['status_filter'])->with('customer', 'delivery_challan_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(20);
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'delivery_challan_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(20);
        }

        if (count($allorders) > 0) {
            foreach ($allorders as $key => $order) {
                $order_quantity = 0;

                if (count($order['delivery_challan_products']) > 0) {
                    foreach ($order['delivery_challan_products'] as $opk => $opv) {
                        $product_size = ProductSubCategory::find($opv->product_category_id);
                        if ($opv->unit_id == 1) {
                            $order_quantity = $order_quantity + $opv->actual_quantity;
                        }
                        if ($opv->unit_id == 2) {
                            $order_quantity = $order_quantity + ($opv->actual_quantity * $product_size->weight);
                        }
                        if ($opv->unit_id == 3) {
                            $order_quantity = $order_quantity + (($opv->actual_quantity / $product_size->standard_length ) * $product_size->weight);
                        }
                    }
                }

                $allorders[$key]['total_quantity'] = $order_quantity;
            }
        }

        $allorders->setPath('delivery_challan');

        return view('delivery_challan', compact('allorders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        $allorder = DeliveryChallan::where('id', '=', $id)
//                        ->where('challan_status', '=', 'pending')
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order')->first();
        if (count($allorder) < 1) {
            return redirect('delivery_challan')->with('success', 'Invalid challan or challan not found');
        }
        return View::make('delivery_challan_details', compact('allorder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $allorder = DeliveryChallan::where('id', '=', $id)
                        ->where('challan_status', '=', 'pending')
                        ->with('all_order_products.unit', 'all_order_products.product_category', 'customer', 'delivery_order')->first();
        $price_delivery_order = $this->calculate_price($allorder);
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        return View::make('edit_delivery_challan', compact('allorder', 'price_delivery_order', 'units', 'delivery_locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $input_data = Input::all();

        $validator = Validator::make($input_data, DeliveryOrder::$order_to_delivery_challan_rules);
        if ($validator->passes()) {

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

            $delivery_challan = DeliveryChallan::find($id);
            $update_challan = $delivery_challan->update([
                'bill_number' => $input_data['billno'],
                'freight' => $input_data['freight'],
                'loading_charge' => $input_data['loading'],
                'loaded_by' => $input_data['loadedby'],
                'labours' => $input_data['labour'],
                'grand_price' => $input_data['grand_total'],
                'remarks' => $input_data['challan_remark'],
                'challan_status' => "Pending"
            ]);

            if (isset($input_data['discount'])) {
                $delivery_challan->update([
                    'discount' => $input_data['discount']]);
            }

            if (isset($input_data['vat_percentage'])) {
                $delivery_challan->update([
                    'vat_percentage' => $input_data['vat_percentage']]);
            }

            if (isset($input_data['billno'])) {
                $delivery_challan->update([
                    "bill_number" => $input_data['billno']]);
            }

            $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
            if ($j != 0) {
                $order_products = array();
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $order_products = [
                            'order_id' => $id,
                            'order_type' => 'delivery_challan',
                            'product_category_id' => $product_data['id'],
                            'unit_id' => $product_data['units'],
                            'actual_pieces' => $product_data['actual_pieces'],
                            'quantity' => $product_data['quantity'],
                            'present_shipping' => $product_data['present_shipping'],
                            'price' => $product_data['price']
                        ];
                        $add_order_products = AllOrderProducts::create($order_products);
                    }
                }
            }
            return redirect('delivery_challan')->with('flash_message', 'One Delivery Challan is successfuly updated.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');

        if ($password == '') {
            return Redirect::to('delivery_challan')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {

            $order = DeliveryChallan::find($id);

            $all_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan');

            foreach ($all_order_products as $products) {
                $products->delete();
            }
            $order->delete();
            return redirect('delivery_challan')->with('flash_message', 'One record is deleted.');
        } else {
            return Redirect::back()->with('flash_message', 'Password entered is not valid.');
        }
    }

    //Generate Serial number and print Delivery Challan
    public function print_delivery_challan($id) {

        $serial_number_delivery_order = Input::get('serial_number');
        $current_date = date("m/d/");
        $date_letter = 'DC/' . $current_date . $id;

        DeliveryChallan::where('id', $id)->update(array(
            'serial_number' => $date_letter,
            'challan_status' => "completed"
        ));

        $this->checkpending_quantity();

        $allorder = DeliveryChallan::where('id', '=', $id)
                        ->where('challan_status', '=', 'completed')
                        ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();

        /*
          | ------------------- -----------------------
          | SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
          | -------------------------------------------
         */
        $input_data = $allorder['delivery_challan_products'];
        $send_sms = Input::get('send_sms');
        if ($send_sms == 'true') {
            $customer_id = $allorder->customer_id;
            $customer = Customer::where('id', '=', $customer_id)->with('manager')->first();
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . ", your meterial has been despatched as follows:";
                foreach ($input_data as $product_data) {
                    $product = ProductSubCategory::find($product_data->product_category_id);
                    $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= " Truck Number: " .
                        $allorder['delivery_order']->vehicle_number .
                        ", Driver number: " . $allorder['delivery_order']->driver_contact_no .
                        ", Quantity: " . $allorder['delivery_challan_products']->sum('present_shipping') .
                        ", Amount: " . $allorder->grand_price .
                        ", Due By: " . date("jS F, Y", strtotime($allorder['delivery_order']->expected_delivery_date)) .
                        ", . Vikas Associates, 9673000068";
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
        return view('print_delivery_challan', compact('allorder'));
    }

    function checkpending_quantity() {
        $allorders = Order::all();
        $allorder_new = [];
        foreach ($allorders as $order) {
            $delivery_orders = DeliveryOrder::where('order_id', $order->id)->get();

            $gen_dc = 1;
            $pending_quantity = 0;
            foreach ($delivery_orders as $del_order) {
                $delivery_challans = DeliveryChallan::where('delivery_order_id', $del_order->id)->get();
                foreach ($delivery_challans as $del_challan) {
                    $gen_dc = 0;
                    $all_order_products = AllOrderProducts::where('order_id', $order->id)->where('order_type', 'delivery_order')->get();
                    foreach ($all_order_products as $products) {
                        $p_qty = $products['quantity'] - $products['present_shipping'];
                        $pending_quantity = $pending_quantity + $p_qty;
                    }
                }
            }
            if ($gen_dc != 1 && $pending_quantity == 0 && $order->order_status != 'completed') {

                Order::where('id', $order->id)->update(array(
                    'order_status' => "completed"
                ));
            }
        }
    }

    function calculate_price($delivery_data) {

        $product_rates = array();
        foreach ($delivery_data->all_order_products as $product) {

            $sub_product = \App\ProductSubCategory::where('product_category_id', $product->product_category_id)->first();
            $product_category = \App\ProductCategory::where('id', $product->product_category_id)->first();
            $user_id = $delivery_data['customer']->id;
            $users_set_price_product = CustomerProductDifference::where('product_category_id', $product->product_category_id)
                            ->where('customer_id', $user_id)->first();
            $total_rate = $product_category->price;
            $users_set_price = 0;
            if (count($users_set_price_product) > 0) {
                $total_rate = $total_rate + $users_set_price_product->difference_amount;
                $users_set_price = $users_set_price_product->difference_amount;
            }
            if ($sub_product->difference > 0) {
                $total_rate = $total_rate + $sub_product->difference;
            }

            $product_rate = array();
            $product_rate["product_id"] = $product->product_category_id;
            $product_rate["product_price"] = $product_category->price;
            $product_rate["difference"] = $sub_product->difference;
            $product_rate["difference_amount"] = $users_set_price;
            $product_rate["total_rate"] = $total_rate;
            array_push($product_rates, $product_rate);
        }
        return $product_rates;
    }

}
