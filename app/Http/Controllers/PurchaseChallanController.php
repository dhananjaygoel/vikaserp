<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseChallan;
use App\PurchaseProducts;
use App\Http\Requests\PurchaseChallanRequest;
use Input;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Units;
use App\DeliveryLocation;
use Hash;
use App;
use Auth;
use Config;
use App\Customer;
use App\ProductSubCategory;
use App\PurchaseAdvise;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use App\DeliveryChallanLoadedBy;

class PurchaseChallanController extends Controller {

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
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        $data = Input::all();

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (isset($data['order_filter']) && $data['order_filter'] != '') {

            if ($data['order_filter'] == "Inprocess" | $data['order_filter'] == "pending")
                $status = 'pending';
            if ($data['order_filter'] == "completed" | $data['order_filter'] == "Delivered")
                $status = 'completed';


            $q = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->where('order_status', $status);


            $search_dates = [];
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
//             
//            print_r($status."--".$date1."--".$date2);
////            print_r($data['order_filter']);
//            echo "<pre>";
//            print_r($q->toSql());
//            echo "</pre>";
//            exit; 

            $purchase_challan = $q->orderBy('created_at', 'desc')->paginate(20);
        } else {
            $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
                            ->where('order_status', 'pending')->orderBy('created_at', 'desc')->Paginate(20);
        }

        $purchase_challan->setPath('purchase_challan');
        return view('purchase_challan', compact('purchase_challan'));
    }

    /* Function used to export order details in excel */

    public function exportPurchaseChallanBasedOnStatus() {
        $data = Input::all();


        if ($data['order_filter'] == 'Inprocess' | $data['order_filter'] == 'pending') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'pending')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'pending';
            $excel_sheet_name = 'Inprocess';
            $excel_name = 'Purchase-Challan-Pending-' . date('dmyhis');
        } elseif ($data['order_filter'] == 'Delivered' | $data['order_filter'] == 'completed') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'completed';
            $excel_sheet_name = 'Completed';
            $excel_name = 'Purchase-Challan-Completed-' . date('dmyhis');
        } elseif ($data['order_filter'] == 'cancelled') {
//                $delivery_data = DeliveryOrder::orderBy('updated_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer', 'order_details')->paginate(20);
            $order_status = 'cancelled';
            $excel_sheet_name = 'Cancelled';
            $excel_name = 'Purchase-Challan-Cancelled-' . date('dmyhis');
        }


        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if (Auth::user()->role_id <> 5) {

                if ($date1 == $date2) {
                    $order_objects = PurchaseChallan::where('order_status', $order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('all_purchase_products.unit', 'all_purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseChallan::where('order_status', $order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('all_purchase_products.unit', 'all_purchase_products.purchase_product_details', 'supplier')
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
                    $order_objects = PurchaseChallan::where('updated_at', 'like', $date1 . '%')
                            ->where('customer_id', '=', $cust->id)
                            ->with('all_purchase_products.unit', 'all_purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseChallan::where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('customer_id', '=', $cust->id)
                            ->with('all_purchase_products.unit', 'all_purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
        } else {

            if (Auth::user()->role_id <> 5) {

                $order_objects = PurchaseChallan::where('order_status', $order_status)
                        ->with('all_purchase_products.unit', 'all_purchase_products.purchase_product_details', 'supplier')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }

            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();


                $order_objects = PurchaseChallan::with('all_purchase_products.unit', 'all_purchase_products.purchase_product_details', 'supplier')
                        ->where('customer_id', '=', $cust->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                $excel_sheet_name = 'Purchase-Order';
                $excel_name = 'Purchase-Order-' . date('dmyhis');
            }
        }

//        echo "<pre>";
//        print_r($order_objects[0]['all_purchase_products'][0]['purchase_product_details']->alias_name);
//        echo "</pre>";
//        exit;
//       

        if (count($order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Purchase Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();





            Excel::create($excel_name, function($excel) use($order_objects, $units, $delivery_location, $customers, $excel_sheet_name) {
                $excel->sheet('Purchase-Order-' . $excel_sheet_name, function($sheet) use($order_objects, $units, $delivery_location, $customers) {
                    $sheet->loadView('excelView.purchase_challan', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
                });
            })->export('xls');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseChallanRequest $request) {

        $input_data = Input::all();
        $sms_flag = 0;
        $purchase_advise_details = PurchaseAdvise::find($request->input('purchase_advice_id'));
        if ($purchase_advise_details->advice_status == 'delivered') {
            return Redirect::back()->with('validation_message', 'This purchase advise is already converted to purchase challan. Please refresh the page');
        }
        if (Session::has('forms_purchase_challan')) {
            $session_array = Session::get('forms_purchase_challan');
            if (count($session_array) > 0) {
                if (in_array($input_data['form_key'], $session_array)) {
                    return Redirect::back()->with('flash_message', 'This order is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_purchase_challan', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_purchase_challan', $forms_array);
        }



        $current_date = date("m/d");
        $sms_flag = 0;






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
//        $add_challan->unloaded_by = $request->input('unloaded_by');
        $add_challan->round_off = $request->input('round_off');
//        $add_challan->labours = $request->input('labour');
        $add_challan->remarks = $request->input('remark');
        $add_challan->grand_total = $request->input('grand_total');
        $add_challan->order_status = 'pending';
        $add_challan->freight = $input_data['Freight'];
        $add_challan->save();

        $challan_id = DB::getPdo()->lastInsertId();

        $pr_c = PurchaseChallan::where('id','=',$challan_id)->with('purchase_order_single')->first();
        $vat_status = $pr_c->purchase_order_single->vat_percentage;
        if($vat_status == "" OR $vat_status == null){
            $date_letter = 'PC/' . $current_date . "/" . $challan_id.'A';
        }
        else{
            $date_letter = 'PC/' . $current_date . "/" . $challan_id.'P';
        }

        PurchaseChallan::where('id',$challan_id)->update(['serial_number'=>$date_letter]);


        $created_at = $add_challan->created_at;
        $updated_at = $add_challan->updated_at;

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

        if (isset($input_data['unloaded_by'])) {
            $loaders = $input_data['unloaded_by'];
            $loaders_info = [];
            foreach ($loaders as $loader) {
                $loaders_info[] = [
                    'delivery_challan_id' => $challan_id,
                    'loaded_by_id' => $loader,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'purchase',
                ];
            }
            $add_loaders_info = DeliveryChallanLoadedBy::insert($loaders_info);
        }

        if (isset($input_data['labour'])) {
            $labours = $input_data['labour'];
            $labours_info = [];
            foreach ($labours as $labour) {
                $labours_info[] = [
                    'delivery_challan_id' => $challan_id,
                    'labours_id' => $labour,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                    'type' => 'purchase',
                ];
            }
            $add_loaders_info = App\DeliveryChallanLabours::insert($labours_info);
        }

        $input_data = Input::all();
//        $order_products = array();
        $order_products = [];

        foreach ($input_data['product'] as $product_data) {
            if (isset($product_data['id']) && $product_data['id'] != "") {
//                $order_products = [
                $order_products[] = [
                    'purchase_order_id' => $challan_id,
                    'order_type' => 'purchase_challan',
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => $product_data['unit_id'],
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price'],
                    'parent' => $product_data['id'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
//                $add_order_products = PurchaseProducts::create($order_products);
            } else {
//                $order_products = [
                $order_products[] = [
                    'purchase_order_id' => $challan_id,
                    'order_type' => 'purchase_challan',
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => $product_data['unit_id'],
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price'],
                    'parent' => '',
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
//                $add_order_products = PurchaseProducts::create($order_products);
            }
        }
        $add_order_products = PurchaseProducts::insert($order_products);

        $purchase_challan = PurchaseChallan::with('purchase_advice', 'delivery_location', 'supplier', 'all_purchase_products.purchase_product_details', 'all_purchase_products.unit')->find($challan_id);



        /* inventory code */
        $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $challan_id)->where('order_type', 'purchase_challan')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
         * ------------------- -----------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE CHALLAN
         * -------------------------------------------
         */

        /* check for vat/gst items */
        if (isset($challan['vat_percentage']) && !empty($challan['vat_percentage']) && $challan != "") {
            $sms_flag = 1;
        }
        /**/       

        $input_data = $purchase_challan['all_purchase_products'];
        $send_sms = Input::get('send_sms');
       
//        if($send_sms == 'true'){
        if ( $sms_flag == 1) {
            $customer_id = $purchase_challan->supplier_id;
            $customer = Customer::with('manager')->find($customer_id);
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour material has been dispatched as follows ";
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
                $str .= " Vehicle No. " . $purchase_challan['purchase_advice']->vehicle_number
                        . ", Quantity. " . round($input_data->sum('quantity'), 2)
                        . ", Amount. " . $purchase_challan->grand_total
                        . ", Due by " . date("j M, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))
                        . ".\nVIKAS ASSOCIATES";
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
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has dispatched material for " . $customer->owner_name . " as follows ";
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
                $str .= " Vehicle No. " . $purchase_challan['purchase_advice']->vehicle_number
                        . ", Quantity. " . round($input_data->sum('quantity'), 2)
                        . ", Amount. " . $purchase_challan->grand_total
                        . ", Due by " . date("j M, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))
                        . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
//                    $phone_number = $customer->phone_number1;
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


        //         update sync table         
        $tables = ['customers', 'purchase_challan', 'all_purchase_products', 'purchase_advice'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */


        return redirect('purchase_challan')->with('success', 'Challan details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id = "") {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id == 5 | $id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $purchase_challan = PurchaseChallan::with('purchase_advice','purchase_order','delivery_location', 'supplier', 'purchase_product.purchase_product_details', 'purchase_product.unit', 'challan_loaded_by.dc_loaded_by', 'challan_labours.dc_labour')->find($id);
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        if (count($purchase_challan) < 1) {
            return redirect('purchase_challan')->with('flash_message', 'Challan not found');
        }

        return view('view_purchase_challan', compact('purchase_challan','customers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
//    public function edit($id) {
//
//        $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'purchase_product.product_sub_category', 'purchase_product.unit')->find($id);
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
     */
    public function destroy($id) {
        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check($password, Auth::user()->password)) {

            /* inventory code */
            $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_challan')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }

            PurchaseChallan::find($id)->delete();
            PurchaseProducts::where('purchase_order_id', '=', $id)->where('order_type', '=', 'purchase_challan')->delete();

            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);
            //         update sync table         
            $tables = ['customers', 'purchase_challan', 'all_purchase_products'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */
            return array('message' => 'success');
        } else {
            return array('message' => 'failed');
        }
    }

    public function print_purchase_challan($id) {

        //$current_date = date("m/d");
        $sms_flag = 0;

       /* $pr_c = PurchaseChallan::where('id','=',$id)->with('purchase_order_single')->first();
        $vat_status = $pr_c->purchase_order_single->vat_percentage;
        if($vat_status == "" OR $vat_status == null){
            $date_letter = 'PC/' . $current_date . "/" . $id.'A';
        }
        else{
            $date_letter = 'PC/' . $current_date . "/" . $id.'P';
        }*/

        PurchaseChallan::where('id', $id)
                ->where('order_status', '<>', 'Completed')
                ->update(array(
                  //  'serial_number' => $date_letter,
                    'order_status' => "Completed"
        ));
        $purchase_challan = PurchaseChallan::with('purchase_advice', 'delivery_location', 'supplier', 'all_purchase_products.purchase_product_details', 'all_purchase_products.unit')->find($id);

        /* inventory code */
        $product_categories = PurchaseProducts::select('product_category_id')->where('purchase_order_id', $id)->where('order_type', 'purchase_challan')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }
        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

        /*
         * ------------------- -----------------------
         * SEND SMS TO CUSTOMER FOR NEW PURCHASE CHALLAN
         * -------------------------------------------
         */
        $input_data = $purchase_challan['all_purchase_products'];
        /* check for vat/gst items */
        if (isset($purchase_challan['vat_percentage']) && !empty($purchase_challan['vat_percentage']) && $purchase_challan['vat_percentage'] != "") {
            $sms_flag = 1;
        }
        /**/

        $send_sms = Input::get('send_sms');
        if ($sms_flag == 1) {
            if ($send_sms == 'true') {
                $customer_id = $purchase_challan->supplier_id;
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour material has been delivered as follows ";
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
                    $str .= " Vehicle No. " . $purchase_challan['purchase_advice']->vehicle_number
                            . ", Quantity. " . round($input_data->sum('quantity'), 2)
                            . ", Amount " . $purchase_challan->grand_total
                            . ", Due by " . date("j M, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))
                            . ".\nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer->phone_number1;
//                    $phone_number = $customer['manager']->mobile_number;
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
                    $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has delivered for " . $customer->owner_name . " as follows ";
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
                    $str .= " Vehicle No. " . $purchase_challan['purchase_advice']->vehicle_number
                            . ", Quantity. " . round($input_data->sum('quantity'), 2)
                            . ", Amount " . $purchase_challan->grand_total
                            . ", Due by " . date("j M, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))
                            . ".\nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
//                        $phone_number = $customer->phone_number1;
                    $phone_number = (isset($customer['manager']->mobile_number) && !empty($customer['manager']->mobile_number))?$customer['manager']->mobile_number:'';
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
        //         update sync table         
        $tables = ['customers', 'purchase_challan', 'all_purchase_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        
        return view('print_purchase_challan', compact('purchase_challan'));
    }

}
