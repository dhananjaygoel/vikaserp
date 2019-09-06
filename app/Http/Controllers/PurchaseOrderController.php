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
use App;
use Hash;
use Mail;
use Config;
use App\PurchaseOrderCanceled;
use DateTime;
use App\ProductSubCategory;
use Session;
use App\PurchaseAdvise;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseOrderController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /* Function used to export order details in excel */

    public function exportPurchaseOrderBasedOnStatus() {
        $data = Input::all();
        if ($data['order_status'] == 'pending') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'pending';
            $excel_sheet_name = 'Pending';
            $excel_name = 'Purchase-Order-Pending-' . date('dmyhis');
        } elseif ($data['order_status'] == 'completed') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'completed';
            $excel_sheet_name = 'Completed';
            $excel_name = 'Purchase-Order-Completed-' . date('dmyhis');
        } elseif ($data['order_status'] == 'cancelled') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'canceled';
            $excel_sheet_name = 'Cancelled';
            $excel_name = 'Purchase-Order-Cancelled-' . date('dmyhis');
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if (Auth::user()->role_id <> 5) {
                if ($date1 == $date2) {
                    $order_objects = PurchaseOrder::where('order_status', $order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseOrder::where('order_status', $order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();

                if ($date1 == $date2) {
                    $order_objects = PurchaseOrder::where('updated_at', 'like', $date1 . '%')
                            ->where('customer_id', '=', $cust->id)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseOrder::where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('customer_id', '=', $cust->id)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
        } else {

            if (Auth::user()->role_id <> 5) {

                $order_objects = PurchaseOrder::where('order_status', $order_status)
                        ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }

            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();


                $order_objects = PurchaseOrder::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                        ->where('customer_id', '=', $cust->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                $excel_sheet_name = 'Purchase-Order';
                $excel_name = 'Purchase-Order-' . date('dmyhis');
            }
        }

        if (count($order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Purchase Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();

//            echo "<pre>";
//            print_r($order_objects[0]['purchase_products'][0]->toArray());
//            echo "</pre>";
//            exit;
//            

            Excel::create($excel_name, function($excel) use($order_objects, $units, $delivery_location, $customers, $excel_sheet_name) {
                $excel->sheet('Purchase-Order-' . $excel_sheet_name, function($sheet) use($order_objects, $units, $delivery_location, $customers) {
                    $sheet->loadView('excelView.purchase_order', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
                });
            })->export('xls');
        }
    }

    /*
     * Show purchase order list
     */

    public function index(PurchaseOrderRequest $request) {
        // echo '<pre>';
        // print_r(Auth::user()->role_id);
        // exit;
        // echo 'sdkhfjksd';
        // exit;
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $q = PurchaseOrder::query();
        if ((isset($data['pending_purchase_order'])) && $data['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $data['pending_purchase_order'])->get();
        }
        if ((isset($data['order_for_filter'])) && $data['order_for_filter'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($data['order_for_filter'])) && $data['order_for_filter'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }

        if ((isset($data['order_filter'])) && $data['order_filter'] != '') {
            $q = $q->where('order_status', '=', $data['order_filter']);
        } else {
            $q = $q->where('order_status', '=', 'pending');
        }        

        if (Auth::user()->role_id > 1) {
            $q->where('is_view_all', '=', 1);
        }


//        if ((isset($data['order_filter'])) && $data['order_filter'] != '') {
//            $q = $q->where('order_status', '=', $data['order_filter'])
//                    ->where('is_view_all', '=', 0);
//        } else {
//            $q = $q->where('order_status', '=', 'pending')->where('is_view_all', '=', 0);
//        }
//        $session_sort_type_order = Session::get('order-sort-type');
//        $qstring_sort_type_order = $data['order_filter'];
        $session_sort_type_order = Session::get('purchase-order-sort-type');
        if (isset($data['order_filter']))
            $qstring_sort_type_order = $data['order_filter'];
        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            $qstring_sort_type_order = $qstring_sort_type_order;
        } else {
            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
                $qstring_sort_type_order = $session_sort_type_order;
            } else {
                $qstring_sort_type_order = "";
            }
        }
        if (Auth::user()->role_id < 2) {
            if ((isset($data['order_status'])) && $data['order_status'] != '') {
                $q = $q->where('order_status', '=', $data['order_status']);
            }
        }


        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $q->where('updated_at', 'like', $date1 . '%');
            } else {
                $q->where('updated_at', '>=', $date1);
                $q->where('updated_at', '<=', $date2 . ' 23:59:59');
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        }

        $purchase_orders = $q->orderBy('created_at', 'desc')
                ->with('customer', 'user', 'purchase_products.purchase_product_details', 'purchase_product_has_from','delivery_location')
                ->Paginate(20);
        $purchase_orders = $this->quantity_calculation($purchase_orders);
        

//        foreach ($purchase_orders as $key => $purchase_order) {
//
//            if ($purchase_order->pending_quantity == 0 && $purchase_order->order_status == 'pending') {
//                $po = PurchaseOrder::where('id', $purchase_order->id)->update(['order_status' => 'completed']);
//            }
//        }
     
       
        $all_customers = Customer::where('customer_status', '=', 'permanent')->orderBy('tally_name', 'ASC')->get();
        $purchase_orders->setPath('purchase_orders');

        $parameters = parse_url($request->fullUrl());
        $parameters = isset($parameters['query']) ? $parameters['query'] : '';
        Session::put('parameters', $parameters);

        return view('purchase_order', compact('purchase_orders', 'all_customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $customers = Customer::where('customer_status', '=', 'permanent')->orderBy('tally_name', 'ASC')->get();
        return view('add_purchase_order', compact('units', 'delivery_locations', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseOrderRequest $request) {

        $input_data = Input::all();
        $sms_flag = 0;
        if (Session::has('forms_purchase_order')) {
            $session_array = Session::get('forms_purchase_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This purchase order is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_purchase_order', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_purchase_order', $forms_array);
        }
        $rules = array(
            'purchase_order_location' => 'required',
        );
        $validator = Validator::make($input_data, $rules);

        if ($validator->fails()) {
            Session::forget('product');
            Session::put('input_data', $input_data);
            return Redirect::back()->withErrors($validator)->withInput();
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->withInput()->with('flash_message', 'Please insert product details');
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
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['supplier_status'] == "existing_supplier") {

            $validate = Validator::make($input_data, array('autocomplete_supplier_id' => 'required'));

            if ($validate->passes()) {
                $customer_id = $input_data['autocomplete_supplier_id'];
            } else {
                $error_msg = $validate->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validate);
            }
        }

        $expected_delivery_date = explode('-', $input_data['expected_delivery_date']);
        $expected_delivery_date = $expected_delivery_date[2] . '-' . $expected_delivery_date[0] . '-' . $expected_delivery_date[1];
        $expected_delivery_date = date("Y-m-d", strtotime($expected_delivery_date));

        $add_purchase_order_array = [
            'is_view_all' => $input_data['viewable_by'],
            'supplier_id' => $customer_id,
            'created_by' => Auth::id(),
            'order_for' => $input_data['order_for'],
            'vat_percentage' => $input_data['vat_percentage'],
            'vat_status' => $input_data['vat_status'],
            'expected_delivery_date' => $expected_delivery_date,
            'remarks' => $input_data['purchase_order_remark'],
            'order_status' => "pending",
            'discount_type' => $input_data['discount_type'],
            'discount_unit' => $input_data['discount_unit'],
            'discount' => $input_data['discount'],
        ];

        if ($input_data['purchase_order_location'] > 0) {
            $add_purchase_order_array['delivery_location_id'] = $input_data['purchase_order_location'];
        } else {
            $add_purchase_order_array['other_location'] = $input_data['other_location_name'];
            $add_purchase_order_array['other_location_difference'] = $input_data['other_location_difference'];
        }

        /*
         * ------------------- -----------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE ORDER
         * -------------------------------------------
         */

        /* check for vat/gst items */
        if (isset($input_data['vat_percentage']) && !empty($input_data['vat_percentage'])) {
            $sms_flag = 1;
        }
        /**/


        $input = Input::all();
        if ($sms_flag == 1) {
//        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour purchase order has been logged for following \n";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }

                $str .= " material will be dispatched by " . date("j M, Y", strtotime($expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
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
                $total_quantity = '';
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . "  has logged purchase order for " . $customer->owner_name . " \n";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }

                $str .= " material will be dispatched by " . date("j F, Y", strtotime($expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
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


        $add_purchase_order = PurchaseOrder::create($add_purchase_order_array);
        $purchase_order_id = DB::getPdo()->lastInsertId();
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $add_delivery_location = PurchaseOrder::where('id', $purchase_order_id)->update([
                'delivery_location_id' => 0,
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference'],
            ]);
        } else {
            $add_delivery_location = PurchaseOrder::where('id', $purchase_order_id)->update([
                'delivery_location_id' => $input_data['purchase_order_location'],
                'other_location' => '',
                'other_location_difference' => '',
            ]);
        }
        $purchase_order_products = array();
        dd($input_data);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $purchase_order_products = [
                    'purchase_order_id' => $purchase_order_id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'length' => $product_data['length'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                PurchaseProducts::create($purchase_order_products);
            }
        }


        /* inventory code */
        $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $purchase_order_id)->where('order_type', 'purchase_order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
          | ------------------------------------------------------
          | SEND EMAIL TO SUPPLIER ON CREATE OF NEW PURCHASE ORDER
          | ------------------------------------------------------
         */
        if ($input_data['supplier_status'] != "new_supplier") {

            if (isset($input_data['send_email'])) {
                $customers = Customer::find($customer_id);
//                if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
                $purchase_order = PurchaseOrder::with('purchase_products.purchase_product_details', 'delivery_location')->find($purchase_order_id);

                if (count($purchase_order) > 0) {
                    if (count($purchase_order['delivery_location']) > 0) {
                        $delivery_location = $purchase_order['delivery_location']->area_name;
                    } else {
                        $delivery_location = $purchase_order->other_location;
                    }
                    $mail_array = array(
                        'customer_name' => $customers->owner_name,
                        'expected_delivery_date' => $purchase_order->expected_delivery_date,
                        'created_date' => $purchase_order->updated_at,
                        'delivery_location' => $delivery_location,
                        'order_product' => $purchase_order['purchase_products'],
                        'source' => 'create_order'
                    );

                    $receipent = array();
                    if (App::environment('development')) {
                        $receipent['email'] = Config::get('smsdata.emailData.email');
                        $receipent['name'] = Config::get('smsdata.emailData.name');
                    } else {
                        $receipent['email'] = $customers->email;
                        $receipent['name'] = $customers->owner_name;
                    }

                    Mail::send('emails.new_purchase_order_mail', ['purchase_order' => $mail_array], function($message) use($receipent) {
                        $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: New Purchase Order');
                    });
                }
//                }
            }
        }
        //         update sync table         
        $tables = ['customers', 'all_purchase_products', 'purchase_order'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        return redirect('purchase_orders')->with('flash_message', 'Purchase order details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $purchase_orders = PurchaseOrder::with('purchase_products.unit', 'delivery_location', 'purchase_products.purchase_product_details', 'customer', 'user')->find($id);
        // $prod = PurchaseProducts::all();
        // dd($prod);
        if (count($purchase_orders) < 1) {
            return redirect('purchase_orders')->with('flash_message', 'Purchase order not found');
        }
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        return view('purchase_order_details', compact('purchase_orders','customers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $purchase_order = PurchaseOrder::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')->find($id);
        if (count($purchase_order) < 1) {
            return redirect('purchase_orders')->with('flash_message', 'Purchase order not found');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        $customers = Customer::where('customer_status', '=', 'permanent')->get();
        return view('edit_purchase_order', compact('purchase_order', 'delivery_locations', 'units', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $input_data = Input::all();
        $sms_flag = 0;
        if (Session::has('forms_edit_purchase_order')) {
            $session_array = Session::get('forms_edit_purchase_order');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message_error', 'This purchase order is already updated. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_edit_purchase_order', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_purchase_order', $forms_array);
        }
        $customer_id = 0;
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
                if (isset($input_data['pending_user_id']) && $input_data['pending_user_id'] > 0) {
                    $pending_cust = array(
                        'owner_name' => $input_data['supplier_name'],
                        'phone_number1' => $input_data['mobile_number'],
                        'credit_period' => $input_data['credit_period'],
                        'customer_status' => 'pending'
                    );
                    Customer::where('id', $input_data['pending_user_id'])->update($pending_cust);
                    $customer_id = $input_data['pending_user_id'];
                } else {
                    $customers = new Customer();
                    $customers->owner_name = $input_data['supplier_name'];
                    $customers->phone_number1 = $input_data['mobile_number'];
                    $customers->credit_period = $input_data['credit_period'];
                    $customers->customer_status = 'pending';
                    $customers->save();
                    $customer_id = $customers->id;
                }
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['supplier_status'] == "existing_supplier") {

            $validator = Validator::make($input_data, Customer::$existing_supplier_inquiry_rules);
            if ($validator->passes()) {

                $customer_id = $input_data['autocomplete_supplier_id'];
            } else {
                $error_msg = $validator->messages();
                Session::forget('product');
                Session::put('input_data', $input_data);
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $date_string = preg_replace('~\x{00a0}~u', ' ', $input_data['expected_delivery_date']);
        $date = date("Y-m-d", strtotime(str_replace('-', '/', $date_string)));
        $datetime = new DateTime($date);
        $purchase_order = PurchaseOrder::find($id);
        if ($input_data['vat_status'] == 'include_vat') {
            $vat_percentage = '';
        } else {
            $vat_percentage = $input_data['vat_percentage'];
        }
        $add_purchase_order_array = [
            'is_view_all' => $input_data['viewable_by'],
            'supplier_id' => $customer_id,
            'order_for' => $input_data['order_for'],
            'created_by' => Auth::id(),
            'vat_percentage' => $vat_percentage,
            'expected_delivery_date' => $datetime->format('Y-m-d'),
            'remarks' => $input_data['purchase_order_remark'],
            'order_status' => "pending",
            'discount_type' => $input_data['discount_type'],
            'discount_unit' => $input_data['discount_unit'],
            'discount' => $input_data['discount'],
        ];
        /*
         * ------------------- --------------
         * SEND SMS TO CUSTOMER FOR update ORDER
         * ----------------------------------
         */
        /* check for vat/gst items */
        if (isset($input_data['vat_percentage']) && !empty($input_data['vat_percentage']) && $vat_percentage != "") {
            $sms_flag = 1;
        }
        /**/
        $input = Input::all();
//        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
        if ($sms_flag == 1) {
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour purchase order has been edited and changed as follows \n";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }
                $str .= " material will be dispatched by " . date("j M, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";

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
                $total_quantity = '';
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . "  has edited a purchase order for " . $customer->owner_name . " \n";
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $str .= $product_data['name'] . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ",\n";
                        $total_quantity = $total_quantity + $product_data['quantity'];
                    }
                }
                $str .= " material will be dispatched by " . date("j F, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";

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


        $update_purchase_order = $purchase_order->update($add_purchase_order_array);
        if (isset($input_data['purchase_order_location']) && ($input_data['purchase_order_location'] == -1)) {

            $purchase_order->update([
                'delivery_location_id' => 0,
                'other_location' => $input_data['other_location_name'],
                'other_location_difference' => $input_data['other_location_difference'],
            ]);
        } else {
            $purchase_order->update([
                'delivery_location_id' => $input_data['purchase_order_location'],
                'other_location' => '',
                'other_location_difference' => '',
            ]);
        }
        $purchase_order_products = array();
        PurchaseProducts::where('order_type', '=', 'purchase_order')->where('purchase_order_id', '=', $id)->delete();
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
                PurchaseProducts::create($purchase_order_products);
            }
        }
        $purchase_order_prod = PurchaseProducts::where('order_type', '=', 'purchase_order')->where('purchase_order_id', '=', $id)->first();
        $purchase_order->updated_at = $purchase_order_prod->updated_at;
        $purchase_order->save();

        /* inventory code */
        $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_order')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);


        /*
          | ------------------------------------------------------
          | SEND EMAIL TO SUPPLIER ON UPDATE OF NEW PURCHASE ORDER
          | ------------------------------------------------------
         */
        if (isset($input_data['send_email'])) {
            $customers = Customer::find($customer_id);
//            if (!filter_var($customers->email, FILTER_VALIDATE_EMAIL) === false) {
            $purchase_order = PurchaseOrder::with('purchase_products.purchase_product_details', 'delivery_location')->find($id);
            if (count($purchase_order) > 0) {
                if (count($purchase_order['delivery_location']) > 0) {
                    $delivery_location = $purchase_order['delivery_location']->area_name;
                } else {
                    $delivery_location = $purchase_order->other_location;
                }
                $mail_array = array(
                    'customer_name' => $customers->owner_name,
                    'expected_delivery_date' => $purchase_order->expected_delivery_date,
                    'created_date' => $purchase_order->updated_at,
                    'delivery_location' => $delivery_location,
                    'order_product' => $purchase_order['purchase_products'],
                    'source' => 'update_order'
                );

                $receipent = array();
                if (App::environment('development')) {
                    $receipent['email'] = Config::get('smsdata.emailData.email');
                    $receipent['name'] = Config::get('smsdata.emailData.name');
                } else {
                    $receipent['email'] = $customers->email;
                    $receipent['name'] = $customers->owner_name;
                }

                Mail::send('emails.new_purchase_order_mail', ['purchase_order' => $mail_array], function($message) use($receipent) {
                    $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: Purchase Order Updated');
                });
            }
//            }
        }

        //         update sync table         
        $tables = ['customers', 'all_purchase_products', 'purchase_order'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */

        $parameter = Session::get('parameters');
        $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';

        $purchase_orders =  PurchaseOrder::orderBy('created_at', 'desc')
                ->with('purchase_products.purchase_product_details', 'purchase_product_has_from')->Paginate(20);
        $purchase_orders = $this->quantity_calculation($purchase_orders);

        PurchaseOrder::where('id',$id)->update(['is_editable'=>1]);
        
        return redirect('purchase_orders' . $parameters)->with('flash_message', 'Purchase order details successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {

        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        $order_sort_type = $formFields['order_sort_type'];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check($password, Auth::user()->password)) {
            /* inventory code */
            $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_order')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }


            PurchaseOrder::find($id)->delete();
            PurchaseProducts::where('purchase_order_id', '=', $id)->where('order_type', '=', 'purchase_order')->delete();
            Session::put('order-sort-type', $order_sort_type);

            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);

            //         update sync table         
            $tables = ['customers', 'all_purchase_products', 'purchase_order'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */

            return array('message' => 'success');
        } else {
            return array('message' => 'failed');
        }
    }

    public function create_purchase_advice($order_id = "") {

        if (Auth::user()->role_id == 5 | $order_id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        
        $purchase_orders = PurchaseOrder::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer', 'purchase_advice.purchase_products', 'purchase_products.purchase_product_advise')->find($order_id);
        
        foreach ($purchase_orders['purchase_products'] as $key => $value) {                                  $total_advise_product_quantity =0;
            if (isset($value['purchase_product_advise']) && count($value['purchase_product_advise'])) {
                $purchase_advise_products = $value['purchase_product_advise'];
            } else {
                $purchase_advise_products = PurchaseProducts::where('from', '=', $value->purchase_order_id)->where('product_category_id', '=', $value->product_category_id)->where('order_type','=','purchase_advice')->get();
            }
            if(isset($purchase_advise_products) && !empty($purchase_advise_products)){
                foreach ($purchase_advise_products as $prod) {
                    $total_advise_product_quantity = $total_advise_product_quantity+ $prod->quantity;
                }
            }
            if($value->quantity - $total_advise_product_quantity>0){
                $purchase_orders['purchase_products'][$key]['pending_quantity'] = ($value->quantity - $total_advise_product_quantity);                
            }else{
               unset($purchase_orders['purchase_products'][$key]);
            }
        }
        
        if (count($purchase_orders) < 1) {
            return redirect('purchase_orders')->with('flash_message', 'Purchase order not found');
        }
        return view('create_purchase_advice', compact('purchase_orders'));
    }

    /*
     * complete the purchase order mannually
     */

    public function manual_complete() {

        $inputData = Input::get('formData');
        $sms_flag = 0;
        parse_str($inputData, $input_data);
        $purchase_order_id = $input_data['purchase_order_id'];
        $purchase_order = PurchaseOrder::with('purchase_products.purchase_product_details', 'purchase_products.unit', 'customer')->find($purchase_order_id);

        /*
          | ------------------- -----------------------------------------
          | SEND SMS TO CUSTOMER FOR MANUALLY COMPLETING A PURCHASE ORDER
          | -------------------------------------------------------------
         */
        $inputData = Input::get('formData');
        parse_str($inputData, $input);
        /* check for vat/gst items */
        if (isset($purchase_order['vat_percentage']) && !empty($purchase_order['vat_percentage']) && $purchase_order['vat_percentage'] != "") {
            $sms_flag = 1;
        }
        /**/
        if ($sms_flag == 1) {
            if (isset($input['sendsms']) && $input['sendsms'] == "true") {
                $customer = Customer::with('manager')->find($purchase_order['customer']->id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->owner_name . "\n Your purchase order has been completed for following \n";
                    foreach ($purchase_order['purchase_products'] as $product_data) {
                        $str .= $product_data['purchase_product_details']->alias_name . ' - ' . $product_data['quantity'] . ' - ' . $product_data['price'] . ", \n";
                    }
                    $str .= ".\nVIKAS ASSOCIATES";
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
        }

        /*
          | ----------------------------------------------------------------
          | SEND EMAIL TO CUSTOMER WHEN PURCHASE ORDER IS COMPLETED MANUALLY
          | ----------------------------------------------------------------
         */
        if (isset($input_data['send_email']) && $input_data['send_email'] == 'true' && $purchase_order['customer']->email != "") {
            $customers = $purchase_order['customer'];
            $purchase_order = PurchaseOrder::with('purchase_products.purchase_product_details', 'purchase_products.unit', 'customer')->find($purchase_order_id);
            if (count($purchase_order) > 0) {
                if (count($purchase_order['delivery_location']) > 0) {
                    $delivery_location = $purchase_order['delivery_location']->area_name;
                } else {
                    $delivery_location = $purchase_order->other_location;
                }
                $mail_array = array(
                    'customer_name' => $customers->owner_name,
                    'expected_delivery_date' => $purchase_order->expected_delivery_date,
                    'created_date' => $purchase_order->updated_at,
                    'delivery_location' => $delivery_location,
                    'order_product' => $purchase_order['purchase_products']
                );

                $receipent = array();
                if (App::environment('development')) {
                    $receipent['email'] = Config::get('smsdata.emailData.email');
                    $receipent['name'] = Config::get('smsdata.emailData.name');
                } else {
                    $receipent['email'] = $customers->email;
                    $receipent['name'] = $customers->owner_name;
                }

                Mail::send('emails.complete_purchase_order_mail', ['order' => $mail_array], function($message) use($receipent) {
                    $message->to($receipent['email'], $receipent['name'])->subject('Vikash Associates: Order Completed');
                });
            }
        }

        $purchase_order_canceled = PurchaseOrderCanceled::create([
                    'purchase_order_id' => $input_data['purchase_order_id'],
                    'purchase_type' => $input_data['module_name'],
                    'reason' => $input_data['reason']
        ]);
        $change_status = PurchaseOrder::where('id', '=', $input_data['purchase_order_id'])
                ->update(array(
            'order_status' => 'canceled'
        ));

        //         update sync table         
        $tables = ['customers', 'all_purchase_products', 'purchase_order'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */

        return array('message' => 'success');
    }

    public function purchase_order_report() {

        $data = Input::all();
        $q = PurchaseOrder::query();
        $q->where('order_status', '=', 'pending')->orderBy('created_at', 'desc')->with('customer', 'delivery_location', 'user', 'purchase_products');
        if ((isset($data['pending_purchase_order'])) && $data['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $data['pending_purchase_order'])->get();
        }
        if ((isset($data['order_for_filter'])) && $data['order_for_filter'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($data['order_for_filter'])) && $data['order_for_filter'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }
        if (Auth::user()->role_id > 1) {
            $q->where('is_view_all', '=', 0);
        }
        $purchase_orders = $q->Paginate(20);
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
     */

    function quantity_calculation($purchase_orders) {

        foreach ($purchase_orders as $key => $order) {
            
            $purchase_order_quantity = 0;
            $purchase_order_advise_quantity = 0;
            //$purchase_order_advise_products = PurchaseProducts::where('from', '=', $order->id)->get();
            $purchase_order_advise_products = $order['purchase_advice'];
            if (count($purchase_order_advise_products) > 0) {
                foreach ($purchase_order_advise_products as  $purchase_advice) {
                    foreach ($purchase_advice['purchase_products'] as $prod) {
                        $product_size = $prod['product_sub_category'];                    
                        if ($prod->unit_id == 1) {
                            $purchase_order_advise_quantity = $purchase_order_advise_quantity + $prod->quantity;
                        }
                        if ($prod->unit_id == 2) {
                            $purchase_order_advise_quantity = $purchase_order_advise_quantity + $prod->quantity * $product_size->weight;
                        }
                        if ($prod->unit_id == 3) {
                            $purchase_order_advise_quantity = $purchase_order_advise_quantity + ($prod->quantity / $product_size->standard_length ) * $product_size->weight;
                        }
                    }
                }
            }

            if (count($order['purchase_products']) > 0) {

                foreach ($order['purchase_products'] as $popk => $popv) {
                    $product_size = $popv['product_sub_category'];
                    $productsubcat = App\ProductCategory::find($product_size->product_category_id);
                    if($productsubcat->product_type_id == 3 && $product_size->length_unit != ""){
                        if($product_size->length_unit == "ft"){
                            $purchase_order_quantity = $product_size->weight * $product_size->standard_length;
                        }
                        else{
                            $purchase_order_quantity = ($product_size->weight/305) * $product_size->standard_length;
                        }
                    }
                    else{
                        if ($popv->unit_id == 1) {
                            $purchase_order_quantity = $purchase_order_quantity + $popv->quantity;
                        }
                        if ($popv->unit_id == 2) {
                            $purchase_order_quantity = $purchase_order_quantity + ($popv->quantity * $product_size->weight);
                        }
                        if ($popv->unit_id == 3) {
                            $purchase_order_quantity = $purchase_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                        }
                    }
                    //$product_size = ProductSubCategory::find($popv->product_category_id);
                }
            }

            if ($purchase_order_advise_quantity >= $purchase_order_quantity) {
                $purchase_orders[$key]['pending_quantity'] = 0;
            } else {
                $purchase_orders[$key]['pending_quantity'] = ($purchase_order_quantity - $purchase_order_advise_quantity);
            }
            
            if( $purchase_orders[$key]['pending_quantity'] == 0){                
               $purchase_orders[$key]['order_status'] = 'completed';                 
               PurchaseOrder::where('id', $purchase_orders[$key]['id'])->update(['order_status' => 'completed']);
            }
            $purchase_orders[$key]['total_quantity'] = $purchase_order_quantity;
        }
        return $purchase_orders;
    }

}
