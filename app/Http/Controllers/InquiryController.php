<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
Use Cache;
use Illuminate\Http\Request;
use App\Customer;
use Input;
use App\Units;
use App\DeliveryLocation;
use App\Inquiry;
use App\InquiryProducts;
use DB;
use Config;
use Auth;
use Mail;
use View;
use App;
use App\Http\Requests\InquiryRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Hash;
use App\ProductSubCategory;
use App\Order;
use App\AllOrderProducts;
use DateTime;
use App\CustomerProductDifference;
use Session;
use Illuminate\Support\Facades\Event;
use Memcached;
use Maatwebsite\Excel\Facades\Excel;

class InquiryController extends Controller {

    public function __construct() {

        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP', ['except' => ['create', 'store', 'fetch_existing_customer', 'fetch_products']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

        $data = Input::all();

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id <> 5) {
            if ((isset($data['inquiry_filter'])) && $data['inquiry_filter'] != '') {
                $inquiries = Inquiry::where('inquiry_status', '=', $data['inquiry_filter'])->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details')->orderBy('created_at', 'desc')->Paginate(20);
            } else {
                $inquiries = Inquiry::with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')
                        ->where('inquiry_status', 'pending')
                        ->orderBy('created_at', 'desc')
                        ->Paginate(20);
            }
        }
        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            if ((isset($data['inquiry_filter'])) && $data['inquiry_filter'] != '') {
                $inquiries = Inquiry::where('inquiry_status', '=', $data['inquiry_filter'])
                                ->where('customer_id', '=', $cust->id)
                                ->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details')
                                ->orderBy('created_at', 'desc')->Paginate(20);
            } else {
                $inquiries = Inquiry::with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')
                        ->where('inquiry_status', 'pending')
                        ->where('customer_id', '=', $cust->id)
                        ->orderBy('created_at', 'desc')
                        ->Paginate(20);
            }
        }


        $inquiries->setPath('inquiry');
        return view('inquiry', compact('inquiries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            $inquiry = Customer::with('delivery_location')->find($cust->id);

            if (count($inquiry) < 1) {
                return redirect('inquiry')->with('flash_message', 'Inquiry does not exist.');
            }
        }

        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        return view('add_inquiry', compact('units', 'inquiry', 'delivery_locations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InquiryRequest $request) {

        $input_data = Input::all();
        if (Session::has('forms_inquiry')) {
            $session_array = Session::get('forms_inquiry');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This inquiry is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_inquiry', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_inquiry', $forms_array);
        }
        $rules = array(
            'add_inquiry_location' => 'required',
        );
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "" || $product_data['id'] == "" || $product_data['id'] <= 0) {
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
                $customers_info = $customers->addNewCustomer($input_data['customer_name'], $input_data['contact_person'], $input_data['mobile_number'], $input_data['credit_period']);
                $customer_id = $customers_info->id;
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $add_inquiry = new Inquiry(); //::create($add_inquiry_array);
        $add_inquiry->customer_id = $customer_id;
        $add_inquiry->created_by = Auth::id();
        if ('other' == $input_data['add_inquiry_location']) {
            $add_inquiry->delivery_location_id = 0;
            $add_inquiry->other_location = $input_data['other_location_name'];
            $add_inquiry->location_difference = $input_data['location_difference'];
        } else {
            $add_inquiry->delivery_location_id = $input_data['add_inquiry_location'];
            $add_inquiry->location_difference = $input_data['location_difference'];
        }
        $add_inquiry->vat_percentage = $input_data['vat_percentage'];
        $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
        $add_inquiry->remarks = $input_data['inquiry_remark'];
        $add_inquiry->inquiry_status = "Pending";
        $add_inquiry->save();
        $inquiry_id = $add_inquiry->id;
        $inquiry_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $inquiry_products = [
                    'inquiry_id' => $inquiry_id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark']
                ];
                InquiryProducts::create($inquiry_products);
            }
        }

        /*
          |--------------------------------------------------
          | SEND SMS TO THE CUSTOMER AND RELATIONSHIP MANAGER
          |--------------------------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour inquiry has been logged for following\n ";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $product_size = ProductSubCategory::find($product_data['id']);
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ', ';
                        if ($product_data['units'] == 1) {
                            $total_quantity = $total_quantity + $product_data['quantity'];
                        }
                        if ($product_data['units'] == 2) {
                            $total_quantity = $total_quantity + $product_data['quantity'] * $product_size->weight;
                        }
                        if ($product_data['units'] == 3) {
                            $total_quantity = $total_quantity + ($product_data['quantity'] / $product_size->standard_length ) * $product_size->weight;
                        }
                    }
                }

                $str .= " prices and availability will be contacted shortly \nVIKAS ASSOCIATES";
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

            if (count($customer['manager']) > 0) {
//                $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has logged an enquiry for '" . $customer->owner_name . "', '" . round($total_quantity, 2) . "'. Kindly check and contact. Vikas Associates";
//                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\nYour inquiry has been logged for following\n ";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
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
        
       
        
        return redirect('inquiry')->with('flash_success_message', 'Inquiry details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            $inquiry = Inquiry::with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->Where('customer_id', '=', $cust->id)->find($id);
        }

        if (Auth::user()->role_id <> 5) {
            $inquiry = Inquiry::with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->find($id);
        }



        if (count($inquiry) < 1) {
            return redirect('inquiry')->with('flash_message', 'Inquiry does not exist.');
        }
        $flash_message = '';
        $delivery_location = DeliveryLocation::all();

        /*
          |------------------------------------------------
          | SEND SMS TO THE CUSTOMER WITH LATEST QUOTATIONS
          |------------------------------------------------
         */
        $input_data = $inquiry['inquiry_products'];
        $input = Input::all();
        $str = "";
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer_id = $inquiry->customer_id;
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nPrices for your inquiry are as follows\n";
                foreach ($input_data as $product_data) {
                    $str .= $product_data['inquiry_product_details']->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                    $total_quantity = $total_quantity + $product_data['quantity'];
                }
                $str .= " materials will be dispatched by " . date('j M, Y', strtotime($inquiry['expected_delivery_date'])) . ".\nVIKAS ASSOCIATES";
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
            if (count($customer['manager']) > 0) {
                $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has logged an enquiry for " . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly check and contact. Vikas Associates";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
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
            Inquiry::where('id', '=', $id)->update(array(
                'sms_count' => ($inquiry->sms_count + 1),
            ));
            $flash_message = "Message sent successfully";
            return redirect('inquiry')->with('flash_success_message', 'Message sent successfully');
        }
//        $inquiry = Inquiry::where('id', '=', $id)->with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer')->first();
        return View::make('inquiry_details', array('inquiry' => $inquiry, 'delivery_location' => $delivery_location, 'message' => $flash_message));
//        return redirect('inquiry')->with('flash_success_message', 'Message sent successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }


        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            $inquiry = $inquiry = Inquiry::with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->Where('customer_id', '=', $cust->id)->find($id);
        }


        if (Auth::user()->role_id <> 5) {
            $inquiry = Inquiry::with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->find($id);
        }


        if (count($inquiry) < 1) {
            return redirect('inquiry')->with('flash_message', 'Inquiry does not exist.');
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        return view('edit_inquiry', compact('inquiry', 'delivery_location', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, InquiryRequest $request) {

        $input_data = Input::all();
        if (Session::has('forms_edit_inquiry')) {
            $session_array = Session::get('forms_edit_inquiry');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message_error', 'This inquiry is already updated. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_edit_inquiry', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_inquiry', $forms_array);
        }
        $rules = array('add_inquiry_location' => 'required');
        $validator = Validator::make($input_data, $rules);
        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
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
//        if ($input_data['vat_status'] == 'include_vat') {
//            $vat_price = '';
//        } elseif ($input_data['vat_status'] == 'exclude_vat') {
//            $vat_price = $input_data['vat_percentage'];
//        }

        if (isset($input_data['vat_percentage'])) {
            $vat_price = $input_data['vat_percentage'];
        } else {
            $vat_price = 0;
        }
        $customers = Customer::find($input_data['customer_id']);
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                $customers->owner_name = $input_data['customer_name'];
                $customers->contact_person = $input_data['contact_person'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
                $customers->save();
                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $location_id = $input_data['add_inquiry_location'];
        if ($input_data['add_inquiry_location'] == 'other') {
            $location_id = 0;
            $other_location = $input_data['other_location_name'];
            $location_difference = $input_data['location_difference'];
        } else {
            $location_difference = $input_data['location_difference'];
        }
        $inquiry = Inquiry::find($id);
        $update_inquiry = $inquiry->update([
            'customer_id' => $customer_id,
//            'created_by' => Auth::id(),
            'vat_percentage' => $vat_price,
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['inquiry_remark']
//            'inquiry_status' => $input_data['inquiry_status']
        ]);
        if ($location_id == 0) {
            $inquiry->update([
                'delivery_location_id' => 0,
                'other_location' => $other_location,
                'location_difference' => $location_difference
            ]);
        } else {
            $inquiry->update([
                'other_location' => '',
                'location_difference' => $location_difference,
                'delivery_location_id' => $location_id
            ]);
        }
        $inquiry_products = array();
        InquiryProducts::where('inquiry_id', '=', $id)->delete();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $inquiry_products = [
                    'inquiry_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark'],
                ];
                InquiryProducts::create($inquiry_products);
            }
        }
        $inquiry_products = InquiryProducts::where('inquiry_id', '=', $id)->first();
        $inquiry->updated_at = $inquiry_products->updated_at;
        $inquiry->save();
        /*
          |------------------------------------------------
          | SEND SMS TO THE CUSTOMER WITH UPDATED QUOTATIONS
          |------------------------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour inquiry has been edited for following. ";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ', ';
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }
                $str .= " prices and availability will be contacted shortly. \nVIKAS ASSOCIATES";
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

                if (count($customer['manager']) > 0) {
                    $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited an enquiry for '" . $customer->owner_name . ", '" . $total_quantity . "' Kindly check and contact.\nVIKAS ASSOCIATES";
                    $str ="hello inq edit for admin";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer['manager']->mobile_number;
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
        }
        
      
        return redirect('inquiry')->with('flash_success_message', 'Inquiry details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy() {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        }
        if (Input::has('inquiry_id') && Input::has('password') && (Hash::check(Input::get('password'), Auth::user()->password))) {
            InquiryProducts::where('inquiry_id', '=', Input::get('inquiry_id'))->delete();
            Inquiry::find(Input::get('inquiry_id'))->delete();
            return redirect('inquiry')->with('flash_success_message', 'Inquiry deleted successfully.');
        } else {
            return redirect('inquiry')->with('flash_message', 'Please enter valid password.');
        }
    }

    /*
     * Fetch Exsisting customer
     */

    public function fetch_existing_customer() {

        $term = Input::get('term');
        if ($term != '') {

            $tally_name_search = substr($term, strpos($term, " - ") + strlen(" - "));
            $term = '%' . $term . '%';
//            $tally_name_search = '%' . $tally_name_search . '%';           


            $customers = Customer::where(function($query) use($term) {
                                $query->whereHas('only_city', function($q) use ($term) {
                                    $q->where('city_name', 'like', $term);
                                });
                            })
                            ->where('tally_name', '<>', '')
                            ->where('customer_status', '=', 'permanent')
                            ->orWhere('company_name', $term)
                            ->orWhere('tally_name', 'like', $tally_name_search)
                            ->orWhere('tally_name', 'like', $term)
                            ->orWhere('id', 'like', $term)
                            ->with('delivery_location')
                            ->orderBy('tally_name', 'ASC')
                            ->select('id AS id', DB::raw('CONCAT(id," - ",tally_name) AS value'), /* ('tally_name AS value'), */ 'delivery_location_id AS delivery_location_id')->get(array('delivery_location.difference.id as difference'));
        } else {
            $customers = Customer::with('delivery_location')->where('tally_name', '<>', '')->orderBy('tally_name', 'ASC')->select(DB::raw('CONCAT(id," - ",tally_name) AS value'), 'id AS id', 'delivery_location_id AS delivery_location_id')->get();
        }

//        if (count($customers) > 0) {
//            foreach ($customers as $customer) {
//                $data_array[] = [
//                    'value' =>$customer->tally_name,
//                    'id' => $customer->id,
//                    'delivery_location_id' => $customer->delivery_location_id,
//                    'location_difference' =>  $customer['deliverylocation']->difference,
//                ];
//            }
//           
//            
//        } else {
//            $customers[] = [ 'value' => 'No Customers'];
//        }
        echo json_encode(array('data_array' => $customers));
    }

    /*
     * find the product list base on the user inputs
     */

    public function fetch_products() {

//        $delivery_location = Input::get('delivery_location');
        $customer_id = Input::get('customer_id');
//        $location_diff = 0;
        $products = ProductSubCategory::where('alias_name', 'like', '%' . Input::get('term') . '%')->with('product_category')->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                $cust = 0;
                if ($customer_id > 0) {
                    $customer = CustomerProductDifference::where('customer_id', $customer_id)->where('product_category_id', $product['product_category']->id)->first();
                    if (count($customer) > 0) {
                        $cust = $customer->difference_amount;
                    }
                }
                $data_array[] = [
                    'value' => $product->alias_name,
                    'id' => $product->id,
                    'product_price' => $product['product_category']->price + $cust + Input::get('location_difference') + $product->difference
                ];
            }
        } else {
            $data_array[] = [ 'value' => 'No Products'];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    /*
     * calculate the product price
     */

    public function recalculate_product_price() {

        $delivery_location = Input::get('delivery_location');
        $customer_id = Input::get('customer_id');
        $product_id = Input::get('product_id');
        $location_diff = 0;
        $location_diff = Input::get('location_difference');
        $term = Input::get('term');
        $product = ProductSubCategory::find($product_id);
        $cust = 0;
        if ($customer_id > 0) {
            $customer = CustomerProductDifference::where('customer_id', $customer_id)->where('product_category_id', $product['product_category']->id)->first();
            if (count($customer) > 0) {
                $cust = $customer->difference_amount;
            }
        }
        $data_array[] = [ 'value' => $product->alias_name,
            'id' => $product->id,
            'product_price' => $product['product_category']->price + $cust + $location_diff + $product->difference
        ];
        echo json_encode(array('data_array' => $data_array));
    }

    /*
     * store the price
     */

    public function store_price() {

        InquiryProducts::where('id', '=', Input::get('id'))->update(['price' => Input::get('updated_price')]);
    }

    /*
     * Inquiery to Order
     * place order loads the form
     */

    function place_order($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                    ->where('phone_number1', '=', Auth::user()->mobile_number)
                    ->where('email', '=', Auth::user()->email)
                    ->first();

            $inquiry = Inquiry::where('id', '=', $id)->with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->where('inquiry_status', '<>', 'Completed')->Where('customer_id', '=', $cust->id)->first();
        }

        if (Auth::user()->role_id <> 5) {
            $inquiry = Inquiry::where('id', '=', $id)->with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->where('inquiry_status', '<>', 'Completed')->first();
        }


        if (count($inquiry) < 1) {
            return redirect('inquiry')->with('flash_message', 'Please select other inquiry, order is generated for this inquiry.');
        }
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();
        return view('place_order', compact('inquiry', 'customers', 'delivery_location', 'units'));
    }

    /*
     * save the order details form for the delivery order
     */

    function store_place_order($id, InquiryRequest $request) {





        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 5) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $input_data = Input::all();
        $inquiry = Inquiry::find($id);
        if (Session::has('forms_order')) {
            $session_array = Session::get('forms_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This order is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_order', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_order', $forms_array);
        }
        $customer_id = 0;
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_date']);
        $date = date("Y-m-d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
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
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                if ($input_data['pending_user_id'] > 0) {
                    $pending_cust = array(
                        'owner_name' => $input_data['customer_name'],
                        'contact_person' => $input_data['contact_person'],
                        'phone_number1' => $input_data['mobile_number'],
                        'credit_period' => $input_data['credit_period']
                    );
                    Customer::where('id', $input_data['pending_user_id'])->update($pending_cust);
                    $customer_id = $input_data['pending_user_id'];
                } else {
                    $customers = new Customer();
                    $customers->owner_name = $input_data['customer_name'];
                    $customers->contact_person = $input_data['contact_person'];
                    $customers->phone_number1 = $input_data['mobile_number'];
                    $customers->credit_period = $input_data['credit_period'];
                    $customers->customer_status = 'pending';
                    $customers->save();
                    $customer_id = $customers->id;
                }
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['existing_customer_name'];
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
//        if ($input_data['vat_status'] == 'include_vat') {
//            $vat_price = '';
//        }
//        if ($input_data['vat_status'] == 'exclude_vat') {
//            $vat_price = $input_data['vat_percentage'];
//        }
        if (isset($input_data['vat_percentage'])) {
            $vat_price = $input_data['vat_percentage'];
        } else {
            $vat_price = 0;
        }

        $order = new Order();
        $order->order_source = $order_status;
        $order->supplier_id = $supplier_id;
        $order->customer_id = $customer_id;
//        $order->created_by = Auth::id();
        $order->created_by = $inquiry->created_by;
        $order->vat_percentage = $vat_price;
        $order->expected_delivery_date = $datetime->format('Y-m-d');
        $order->remarks = $input_data['inquiry_remark'];
        $order->order_status = "Pending";
        if ('other' == $input_data['add_inquiry_location']) {
            $order->other_location = $input_data['other_location_name'];
            $order->location_difference = $input_data['location_difference'];
        } else {
            $order->delivery_location_id = $input_data['add_inquiry_location'];
            $order->location_difference = $input_data['location_difference'];
        }
        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR NEW ORDER
         * ----------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour order has been logged for following \n";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $product = ProductSubCategory::find($product_data['id']);
                        $str .= $product->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ", \n";
                        if ($product_data['units'] == 1) {
                            $total_quantity = $total_quantity + $product_data['quantity'];
                        }
                        if ($product_data['units'] == 2) {
                            $total_quantity = $total_quantity + $product_data['quantity'] * $product->weight;
                        }
                        if ($product_data['units'] == 3) {
                            $total_quantity = $total_quantity + ($product_data['quantity'] / $product->standard_length ) * $product->weight;
                        }
                    }
                }
                $str .= " material will be dispatched by " . date("j M, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
//                $url = SMS_URL . "?user = " . PROFILE_ID . "&pwd = " . PASS . "&senderid = " . SENDER_ID . "&mobileno = " . $phone_number . "&msgtext = " . $msg . "&smstype = 0";
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
            if (count($customer['manager']) > 0) {
                $str = "Dear " . $customer['manager']->first_name . "\n" . Auth::user()->first_name . " has created an order for " . $customer->owner_name . " '" . round($total_quantity, 2) . "'. Kindly check. Vikas Associates";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
                }
                $msg = urlencode($str);
//                $url = SMS_URL . "?user = " . PROFILE_ID . "&pwd = " . PASS . "&senderid = " . SENDER_ID . "&mobileno = " . $phone_number . "&msgtext = " . $msg . "&smstype = 0";
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }


        $order->save();
        $order_id = $order->id;
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $order_products = [
                    'order_id' => $order_id,
                    'order_type' => 'order',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                    'remarks' => $product_data['remark']
                ];
                AllOrderProducts::create($order_products);
            }
        }

        /*
         * send mail
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);
            if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
                $order = Order::with('all_order_products.order_product_details', 'delivery_location')->find($order_id);
                if (count($order) > 0) {
                    $delivery_location = (count($order['delivery_location']) > 0) ? $order['delivery_location']->area_name : $order->other_location;
                    $mail_array = array(
                        'customer_name' => $customers->owner_name,
                        'expected_delivery_date' => $order->expected_delivery_date,
                        'created_date' => $order->created_at,
                        'delivery_location' => $delivery_location,
                        'order_product' => $order['all_order_products'],
                        'source' => 'inquiry'
                    );
                    $receipent = array();
                    if (App::environment('development')) {
                        $receipent['email'] = Config::get('smsdata.emailData.email');
                        $receipent['name'] = Config::get('smsdata.emailData.name');
                    } else {
                        $receipent['email'] = $customers->email;
                        $receipent['name'] = $customers->owner_name;
                    }
                    Mail::send('emails.new_order_mail', ['order' => $mail_array], function($message) use($receipent) {
                        $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: New Order');
                    });
                }
            }
        }
        Inquiry::where('id', '=', $id)->update(['inquiry_status' => 'Completed']);
        return redirect('inquiry')->with('flash_success_message', 'One Order successfully generated for Inquiry.');
    }

    /* Function used to export inquiry records */

    public function exportinquiryBasedOnStatus($inquiry_status) {

        if ($inquiry_status == 'Pending') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $inquiry_status = 'pending';
            $excel_sheet_name = 'Pending';
            $excel_name = 'Inquiry-Pending-' . date('dmyhis');
        } elseif ($inquiry_status == 'Completed') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $inquiry_status = 'completed';
            $excel_sheet_name = 'Completed';
            $excel_name = 'Inquiry-Completed-' . date('dmyhis');
        }

        $inquiry_objects = Inquiry::where('inquiry_status', $inquiry_status)->with('inquiry_products.unit', 'inquiry_products.inquiry_product_details', 'customer', 'createdby')->get();
        if (count($inquiry_objects) == 0) {
            return redirect::back()->with('flash_message', 'No data found');
        } else {
            $delivery_location = DeliveryLocation::all();
            Excel::create($excel_name, function($excel) use($inquiry_objects, $excel_sheet_name, $delivery_location) {
                $excel->sheet('Inquiry-' . $excel_sheet_name, function($sheet) use($inquiry_objects, $delivery_location) {
                    $sheet->loadView('excelView.inquiry', array('inquiry_objects' => $inquiry_objects, 'delivery_location' => $delivery_location));
                });
            })->export('xls');
        }
    }

    function getenviroment() {
        print_r(App::environment());
    }

}
