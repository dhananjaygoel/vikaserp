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
use Redirect;
use App\User;
use Auth;
use App;
use Hash;
use Config;
use App\Units;
use App\DeliveryLocation;
use App\Customer;
use App\ProductSubCategory;
use Session;

class DeliveryChallanController extends Controller {

    public function __construct() {

        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        $data = Input::all();
        $session_sort_type_order = Session::get('order-sort-type');
        if (isset($data['status_filter']))
            $qstring_sort_type_order = $data['status_filter'];
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
            $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)->with('customer', 'delivery_challan_products', 'delivery_order')
                            ->orderBy('updated_at', 'desc')->Paginate(20);
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'delivery_challan_products', 'delivery_order')
                            ->orderBy('updated_at', 'desc')->Paginate(20);
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
     * Display the specified Delivery Challan Details.
     */
    public function show($id) {

        $allorder = DeliveryChallan::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')->find($id);
        if (count($allorder) < 1) {
            return redirect('delivery_challan')->with('success', 'Invalid challan or challan not found');
        }
        return view('delivery_challan_details', compact('allorder'));
    }

    /**
     * Show Edit Delivery Challan Details
     */
    public function edit($id) {

        $allorder = DeliveryChallan::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order')
//                ->where('challan_status', '=', 'pending')
                ->find($id);
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        return view('edit_delivery_challan', compact('allorder', 'price_delivery_order', 'units', 'delivery_locations'));
    }

    /**
     * Update Delivery Challan Details
     */
    public function update($id) {

        $input_data = Input::all();
        if (!isset($input_data['grand_total']) || $input_data['grand_total'] == '') {
            return Redirect::back()->with('validation_message', 'No Value Updated. Please update something');
        }
        if (Session::has('forms_edit_delivery_challan')) {
            $session_array = Session::get('forms_edit_delivery_challan');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('validation_message', 'This delivery challan is already Updated. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_edit_delivery_challan', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_delivery_challan', $forms_array);
        }
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
        $delivery_challan->bill_number = $input_data['billno'];
        $delivery_challan->loaded_by = $input_data['loadedby'];
//        $delivery_challan->labours = $input_data['labour'];
        $delivery_challan->discount = $input_data['discount'];
        $delivery_challan->freight = $input_data['freight'];
        $delivery_challan->loading_charge = $input_data['loading'];
        $delivery_challan->round_off = $input_data['round_off'];
        $delivery_challan->grand_price = $input_data['grand_total'];
        $delivery_challan->remarks = $input_data['challan_remark'];
        if (isset($input_data['loading_vat_percentage'])) {
            $delivery_challan->loading_vat_percentage = $input_data['loading_vat_percentage'];
        } else {
            $delivery_challan->loading_vat_percentage = 0;
        }
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

        AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
        if ($j != 0) {
            $order_products = array();
            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] != "" && $product_data['actual_quantity'] != "") {
                    $order_products = [
                        'order_id' => $id,
                        'order_type' => 'delivery_challan',
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'actual_pieces' => $product_data['actual_pieces'],
                        'actual_quantity' => $product_data['actual_quantity'],
                        'quantity' => $product_data['actual_quantity'],
                        'present_shipping' => $product_data['actual_quantity'],
                        'price' => $product_data['price'],
                        'vat_percentage' => ($product_data['vat_percentage'] != '') ? $product_data['vat_percentage'] : 0,
                        'from' => $input_data['order_id'],
                        'parent' => $input_data['order'],
                    ];
                    AllOrderProducts::create($order_products);
                }
            }
            $delivery_challan_prod = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->first();
            $delivery_challan->updated_at = $delivery_challan_prod->updated_at;
            $delivery_challan->save();
        }
        return redirect('delivery_challan')->with('flash_message', 'Delivery Challan details updated successfuly .');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        $order_sort_type = $formFields['order_sort_type'];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
        }
        if ($password == '') {
            return Redirect::to('delivery_challan')->with('error', 'Please enter your password');
        }
        if (Hash::check($password, Auth::user()->password)) {
            AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
            DeliveryChallan::find($id)->delete();
            Session::put('order-sort-type', $order_sort_type);
            return array('message' => 'success');
        } else {
            return array('message' => 'failed');
        }
    }

    /*
     * Generate Serial number and print Delivery Challan
     */

    public function print_delivery_challan($id) {

        $serial_number_delivery_order = Input::get('serial_number');
        $current_date = date("m/d/");
        $update_delivery_challan = DeliveryChallan::with('delivery_challan_products')->find($id);
        $vat_applicable = 0;
        $total_vat_amount = 0;
        if (isset($update_delivery_challan->delivery_challan_products) && count($update_delivery_challan->delivery_challan_products) > 0) {
            foreach ($update_delivery_challan->delivery_challan_products as $key => $delivery_challan_products) {
                if ($delivery_challan_products->vat_percentage > 0) {
                    $vat_applicable = 1;
                    if ($delivery_challan_products->vat_percentage != '' && $delivery_challan_products->vat_percentage > 0) {
                        $total_vat_amount = $total_vat_amount + (($delivery_challan_products->present_shipping * $delivery_challan_products->price * $delivery_challan_products->vat_percentage) / 100);
                    }
                }
            }
        }
        $date_letter = 'DC/' . $current_date . $id . (($vat_applicable > 0) ? "P" : "A");
        $update_delivery_challan->serial_number = $date_letter;
        $update_delivery_challan->challan_status = 'completed';
        $update_delivery_challan->save();
        $this->checkpending_quantity();
        $allorder = DeliveryChallan::where('id', '=', $id)->where('challan_status', '=', 'completed')
                        ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();
        $calculated_vat_value = $allorder->grand_price * ($allorder->vat_percentage / 100);
        $allorder['calculated_vat_price'] = $calculated_vat_value;
        $number = $allorder->grand_price;
        $exploded_value = explode(".", $number);
        $result_paisa = $exploded_value[1] % 10;
        if (isset($exploded_value[1]) && strlen($exploded_value[1]) > 1 && $result_paisa != 0) {
            $convert_value = $this->convert_number_to_words($allorder->grand_price);
        } else {
            $convert_value = $this->convert_number($allorder->grand_price);
        }
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
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour meterial has been desp as follows ";
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
                        "\nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
        return view('print_delivery_challan', compact('allorder', 'total_vat_amount'));
    }

    /*
     * Convert numbers into words
     */

    function convert_number_to_words($all_orders) {

//      $number = $all_orders->grand_price;
        $number = $all_orders;
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    function convert_number($all_orders) {

//        $number = round($all_orders->grand_price, 2);
//        $number = $all_orders->grand_price;
        $number = $all_orders;
        $exploded_value = explode(".", $number);
        $no = $exploded_value[0];
        $point = $number;

        $result_paisa = $exploded_value[1] % 10;

        if (isset($exploded_value[1]) && strlen($exploded_value[1]) > 1 && $result_paisa != 0) {

//            $number = $all_orders->grand_price;
            $number = $all_orders;
            $no = round($number);
            $point = round($number - $no, 2) * 100;
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
            $points = ($point) ?
                    "." . $words[$point / 10] . " " .
                    $words[$point = $point % 10] : '';
            return $result . "Rupees  " . $points . " Paise";
        } else {

//            $number = $all_orders->grand_price;
            $number = $all_orders;
            $exploded_value = explode(".", $number);
            $no = $exploded_value[0];
            $point = $exploded_value[1];
            $hundred = null;
            $digits_1 = strlen($exploded_value[0]);
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
                    $points = $point; //$points = $words[$point];
                $strs = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);

                if ($point % 10 == 0) {
                    $convert_value = ucfirst($result . " point " . $words[$points]);
                } else {
                    $points = ($point) ? ($words[$point / 10] . " " . $words[$point = $point % 10]) : '';
                    $convert_value = ucfirst($result . " rupees and " . $points . " paise");
                }
            } else
                $convert_value = ucfirst($result);
            return $convert_value;
        }
    }

    /*
     * Find total pending quantity
     */

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
