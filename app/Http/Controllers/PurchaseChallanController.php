<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseChallanExport;
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
use Twilio\Rest\Client;

class PurchaseChallanController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        define('TWILIO_SID', Config::get('smsdata.twilio_sid'));
        define('TWILIO_TOKEN', Config::get('smsdata.twilio_token'));
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
            $excel_name = '-Pending-' . date('dmyhis');
        } elseif ($data['order_filter'] == 'Delivered' | $data['order_filter'] == 'completed') {
            $excel_name = '-Completed-' . date('dmyhis');
        } elseif ($data['order_filter'] == 'cancelled') {
            $excel_name = '-Cancelled-' . date('dmyhis');
        }

        return Excel::download(new PurchaseChallanExport, 'Purchase-Challan'.$excel_name.'.xls');

            // Excel::create($excel_name, function($excel) use($order_objects, $units, $delivery_location, $customers, $excel_sheet_name) {
            //     $excel->sheet('Purchase-Order-' . $excel_sheet_name, function($sheet) use($order_objects, $units, $delivery_location, $customers) {
            //         $sheet->loadView('excelView.purchase_challan', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
            //     });
            // })->export('xls');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseChallanRequest $request) {

        $input_data = Input::all();
        $sms_flag = 0;
        $purchase_advise_details = PurchaseAdvise::find($request->input('purchase_advice_id'));
        if ($purchase_advise_details['advice_status'] == 'delivered') {
            if(Session::has('success') == 'Challan details successfully added.' ){
                return redirect('purchase_challan')->with('success', 'Challan details successfully added.');
            }else{
                return Redirect::back()->with('validation_message', 'This purchase advise is already converted to purchase challan. Please refresh the page');
                exit;
            }
        }
        if (Session::has('forms_purchase_challan')) {
            $session_array = Session::get('forms_purchase_challan');
            if (count((array)$session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('success') == 'Challan details successfully added.'){
                        return redirect('purchase_challan')->with('success', 'Challan details successfully added.');
                    }else{
                        return Redirect::back()->with('flash_message', 'This order is already saved. Please refresh the page');
                    }
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
        $add_challan->is_editable = $purchase_advise_details->is_editable;
        // dd($add_challan);
        $add_challan->save();

        $challan_id = DB::getPdo()->lastInsertId();

        // $pr_c = PurchaseChallan::where('id','=',$challan_id)->with('purchase_order_single')->first();
        
        $vat_status = ((isset($input_data['vat_percentage']) && $input_data['vat_percentage'] != "") ? $input_data['vat_percentage'] : 0 );
        if($vat_status == 0 ){
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

        $input_data = Input::all();
//        $order_products = array();
        $order_products = [];
        $total_qty = 0;
        foreach ($input_data['product'] as $product_data) {
            if (isset($product_data['id']) && $product_data['id'] != "") {
                $total_qty = $total_qty + $product_data['quantity'];
//                $order_products = [
                $order_products[] = [
                    'purchase_order_id' => $challan_id,
                    'order_type' => 'purchase_challan',
                    'actual_pieces' => $product_data['actual_pieces'],
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => $product_data['unit_id'],
                    'length' => isset($product_data['length']) ? $product_data['length'] : 0,
                    'quantity' => $product_data['quantity'],
                    'present_shipping' => $product_data['present_shipping'],
                    'price' => $product_data['price'],
                    'parent' => $product_data['id'],
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
//                $add_order_products = PurchaseProducts::create($order_products);
            } else {
                $total_qty = $total_qty + $product_data['quantity'];
//                $order_products = [
                $order_products[] = [
                    'purchase_order_id' => $challan_id,
                    'order_type' => 'purchase_challan',
                    'actual_pieces' => $product_data['actual_pieces'],
                    'product_category_id' => $product_data['product_category_id'],
                    'unit_id' => $product_data['unit_id'],
                    'length' => isset($product_data['length']) ? $product_data['length'] : 0,
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
                    'total_qty' => $total_qty,
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
                    'total_qty' => $total_qty,
                ];
            }
            $add_loaders_info = App\DeliveryChallanLabours::insert($labours_info);
        }

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
        // if (isset($challan['vat_percentage']) && !empty($challan['vat_percentage']) && $challan != "") {
        //     $sms_flag = 1;
        // }
        // /**/

        $input_data = $purchase_challan['all_purchase_products'];
        $send_sms = Input::get('send_sms');

//        if($send_sms == 'true'){
        if ( $sms_flag == 1) {
            $customer_id = $purchase_challan->supplier_id;
            $customer = Customer::with('manager')->find($customer_id);
            if (count((array)$customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\nDT " . date("j F, Y") . "\nYour material has been dispatched as follows ";
                foreach ($input_data as $product_data) {
                    $product = ProductSubCategory::find($product_data->product_category_id);
                    if ($product_data['unit']->id == 1) {
                        $total_quantity = (float)$total_quantity + (float)$product_data->quantity;
                    }
                    if ($product_data['unit']->id == 2) {
                        $total_quantity = (float)$total_quantity + (float)$product_data->quantity * (float)$product->weight;
                    }
                    if ($product_data['unit']->id == 3) {
                        $total_quantity = (float)$total_quantity + (float)($product_data->quantity / $product->standard_length ) * (float)$product->weight;
                    }
                    if ($product_data['unit']->id == 4) {
                        $total_quantity = (float)$total_quantity + (float)($product_data->quantity * $product->weight * $product_data->length);
                    }
                    if ($product_data['unit']->id == 5) {
                        $total_quantity = (float)$total_quantity + (float)($product_data->quantity * $product->weight * (float)($product_data->length/305));
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

            if (count((array)$customer['manager']) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j F, Y") . "\n" . Auth::user()->first_name . " has dispatched material for " . $customer->owner_name . " as follows ";
                foreach ($input_data as $product_data) {
                    $product = ProductSubCategory::find($product_data->product_category_id);
                    if ($product_data['unit']->id == 1) {
                        $total_quantity = (float)$total_quantity + (float)$product_data->quantity;
                    }
                    if ($product_data['unit']->id == 2) {
                        $total_quantity = (float)$total_quantity + (float)$product_data->quantity * (float)$product->weight;
                    }
                    if ($product_data['unit']->id == 3) {
                        $total_quantity = (float)$total_quantity + (float)($product_data->quantity / $product->standard_length ) * (float)$product->weight;
                    }
                    if ($product_data['unit']->id == 4) {
                        $total_quantity = (float)$total_quantity + (float)($product_data->quantity * $product->weight * $product_data->length);
                    }
                    if ($product_data['unit']->id == 5) {
                        $total_quantity = (float)$total_quantity + (float)($product_data->quantity * $product->weight * (float)($product_data->length/305));
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
        if (count((array)$purchase_challan) < 1) {
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
//        if (count((array)$purchase_challan) < 1) {
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
        $sms_flag = 1;

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
        $product_category_ids = [];
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
        $total_quantity = 0;
        $product_string = '';
        $i = 1;
        $send_sms = Input::get('send_sms');
        $send_whatsapp = Input::get('send_whatsapp');
        if ($sms_flag == 1) {
            $customer_id = $purchase_challan->supplier_id;
            $customer = Customer::with('manager')->find($customer_id);
            $send_msg = new WelcomeController();
            foreach ($input_data as $product_data) {
                $total_quantity = $total_quantity + (float)$product_data->quantity;
                // $product = ProductSubCategory::find($product_data->product_category_id);
                // if ($product_data['unit']->id == 1) {
                //     $total_quantity = (float)$product_data->quantity;
                // }
                // if ($product_data['unit']->id == 2) {
                //     $total_quantity = (float)$product_data->quantity * (float)$product->weight;
                // }
                // if ($product_data['unit']->id == 3) {
                //     $total_quantity = (float)($product_data->quantity / $product->standard_length ) * (float)$product->weight;
                // }
                // if ($product_data['unit']->id == 4) {
                //     $total_quantity = (float)($product_data->quantity * $product->weight * $product_data->length);
                // }
                // if ($product_data['unit']->id == 5) {
                //     $total_quantity = (float)($product_data->quantity * $product->weight * (float)($product_data->length/305));
                // }
                $product_string .= $i++ . ") " . $product_data['purchase_product_details']->alias_name . ", " . round((float)$product_data->quantity,2) . "KG, ₹". $product_data['price'] . " ";
            }
            if (count((array)$customer) > 0) {
                $str = "Dear Customer,\n\nYour purchase challan has been printed.\n\nCustomer Name: ".ucwords($customer->owner_name)."\nPurchase Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: ". (isset($purchase_challan['purchase_advice']->vehicle_number)?$purchase_challan['purchase_advice']->vehicle_number:'N/A') . "\nTotal Quantity: ".round($total_quantity, 2)."\nAmount: ".(isset($purchase_challan->grand_total)?round($purchase_challan->grand_total,0):'N/A')."\nDue By: ".date("j M, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))."\n\nVIKAS ASSOCIATES.";   
                if (App::environment('local')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "true") {
                    $send_msg->send_sms($phone_number,$msg);
                }
                if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                    $send_msg->send_whatsapp($phone_number,$str);                    
                }
            }
            if (count((array)$customer['manager']) > 0) {
                $str = "Dear Manager,\n\nPurchase challan has been printed.\n\nCustomer Name: ".ucwords($customer->owner_name)."\nPurchase Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: ". (isset($purchase_challan['purchase_advice']->vehicle_number)?$purchase_challan['purchase_advice']->vehicle_number:'N/A') . "\nTotal Quantity: ".round($input_data->sum('quantity'), 2)."\nAmount: ".(isset($purchase_challan->grand_total)?round($purchase_challan->grand_total,0):'N/A')."\nDue By: ".date("j M, Y", strtotime($purchase_challan['purchase_advice']->expected_delivery_date))."\n\nVIKAS ASSOCIATES.";   

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = (isset($customer['manager']->mobile_number) && !empty($customer['manager']->mobile_number))?$customer['manager']->mobile_number:'';
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "true") {
                    $send_msg->send_sms($phone_number,$msg);
                }
                if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                    $send_msg->send_whatsapp($phone_number,$str);                    
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
