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
use Session;

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

        $session_sort_type_order = Session::get('order-sort-type');
        if (isset($_GET['status_filter']))
            $qstring_sort_type_order = $_GET['status_filter'];
        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            $qstring_sort_type_order = $qstring_sort_type_order;
        } else {
            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
                $qstring_sort_type_order = $session_sort_type_order;
            } else {
                $qstring_sort_type_order = "";
            }
        }

        if ((isset($qstring_sort_type_order)) && ($qstring_sort_type_order != '')) {
            $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)->with('customer', 'delivery_challan_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(20);
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'delivery_challan_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(20);
        }

        if (count($allorders) > 0) {
            foreach ($allorders as $key => $order) {
                $order_quantity = 0;

                if (count($order['delivery_challan_products']) > 0) {

                    $order_quantity = $order['delivery_challan_products']->sum('actual_quantity');
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
                ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order')
                ->first();
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
//    public function edit($id) {
//        $allorder = DeliveryChallan::where('id', '=', $id)
//                ->where('challan_status', '=', 'pending')
//                ->with('all_order_products.unit', 'all_order_products.product_category', 'customer', 'delivery_order')
//                ->first();
//
//        $units = Units::all();
//        $delivery_locations = DeliveryLocation::all();
//        return View::make('edit_delivery_challan', compact('allorder', 'price_delivery_order', 'units', 'delivery_locations'));
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
//    public function update($id) {
//        $input_data = Input::all();
//
//        $i = 0;
//        $j = count($input_data['product']);
//
//        foreach ($input_data['product'] as $product_data) {
//            if ($product_data['name'] == "") {
//                $i++;
//            }
//        }
//
//        if ($i == $j) {
//            return Redirect::back()->with('validation_message', 'Please enter at least one product details');
//        }
//
//        $delivery_challan = DeliveryChallan::find($id);
//        $update_challan = $delivery_challan->update([
//            'bill_number' => $input_data['billno'],
//            'freight' => $input_data['freight'],
//            'loading_charge' => $input_data['loading'],
//            'loaded_by' => $input_data['loadedby'],
//            'labours' => $input_data['labour'],
//            'grand_price' => $input_data['grand_total'],
//            'remarks' => $input_data['challan_remark'],
//            'challan_status' => "Pending"
//        ]);
//
//        if (isset($input_data['discount'])) {
//            $delivery_challan->update([
//                'discount' => $input_data['discount']]);
//        }
//
//        if (isset($input_data['vat_percentage'])) {
//            $delivery_challan->update([
//                'vat_percentage' => $input_data['vat_percentage']]);
//        }
//
//        if (isset($input_data['billno'])) {
//            $delivery_challan->update([
//                "bill_number" => $input_data['billno']]);
//        }
//
//        $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
//        if ($j != 0) {
//            $order_products = array();
//            foreach ($input_data['product'] as $product_data) {
//                if ($product_data['name'] != "") {
//                    $order_products = [
//                        'order_id' => $id,
//                        'order_type' => 'delivery_challan',
//                        'product_category_id' => $product_data['id'],
//                        'unit_id' => $product_data['units'],
//                        'actual_pieces' => $product_data['actual_pieces'],
//                        'quantity' => $product_data['quantity'],
//                        'present_shipping' => $product_data['present_shipping'],
//                        'price' => $product_data['price']
//                    ];
//                    $add_order_products = AllOrderProducts::create($order_products);
//                }
//            }
//        }
//        return redirect('delivery_challan')->with('flash_message', 'One Delivery Challan is successfuly updated.');
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $order_sort_type = Input::get('order_sort_type');
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
            Session::put('order-sort-type', $order_sort_type);
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

        $calculated_vat_value = $allorder->grand_price * ($allorder->vat_percentage / 100);
        $allorder['calculated_vat_price'] = $calculated_vat_value;
        $convert_value = $this->convert_number($allorder);
        $allorder['convert_value'] = $convert_value;
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
                $str = "Dear '" . $customer->owner_name . "'\n your meterial has been desp as follows ";
                foreach ($input_data as $product_data) {
                    $product = ProductSubCategory::find($product_data->product_category_id);
//                    $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= " Trk No. " . $allorder['delivery_order']->vehicle_number .
                        ", Drv No. " . $allorder['delivery_order']->driver_contact_no .
                        ", Qty " . $allorder['delivery_challan_products']->sum('actual_quantity') .
                        ", Amt " . $allorder->grand_price .
                        ", Due by: " . date("jS F, Y", strtotime($allorder['delivery_order']->expected_delivery_date)) .
                        " Vikas Associates, 9673000068";
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

    function convert_number($all_orders) {

        $number = round($all_orders->grand_price, 2);
        $no = round($number);
//        $point = round($number - $no, 2) * 100;
//        $a = $no - $number;
//        $point = round(1 - $a, 2);
        $exploded_value = explode(".", $number);
        $point = $exploded_value[1];
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                        " " . $digits[$counter] . $plural . " " . $hundred :
                        $words[floor($number / 10) * 10]
                        . " " . $words[$number % 10] . " "
                        . $digits[$counter] . $plural . " " . $hundred;
            } else
                $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);

        if ($point != 0) {
            if (strlen($point) == 1)
                $points = $words[$point * 10];
            else
                $points = $words[$point];
            $strs = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);
//            $points = ($point) ?
//                    $words[$point / 10] . " " .
//                    $words[$point = $point % 10] : '';
            $convert_value = ucfirst($result . " rupees and " . $points . " paise");
        } else
            $convert_value = ucfirst($result);

        return $convert_value;
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

}
