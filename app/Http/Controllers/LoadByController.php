<?php

namespace App\Http\Controllers;

use App\Order;
use App\Inquiry;
use App\LoadedBy;
use App\Http\Requests;
use App\DeliveryOrder;
use App\PurchaseOrder;
use App\DeliveryChallan;
use App\ProductSubCategory;
use Illuminate\Http\Request;
use App\DeliveryChallanLoadedBy;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Response;
use Config;

class LoadByController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    public function index() {
        $loader = '';
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';
            $loaders = LoadedBy::where('first_name', 'like', $term)->orWhere('last_name', 'like', $term)->orderBy('created_at', 'DESC');
        } else {
            $loaders = LoadedBy::orderBy('id', 'desc');
        }
        $loaders = $loaders->paginate(20);
        $loaders->setPath('loaded-by');
        return view('loaded_by')->with('loaders', $loaders)->with('performance_index', true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        return view('addedit_loaded_by')->with('performance_index', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->input(), LoadedBy::$ValidateNewLoader, LoadedBy::$validatorMessages);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        $loader = new LoadedBy();
        $loader->first_name = Input::get('first_name');
        $loader->last_name = Input::get('last_name');
        $loader->phone_number = Input::get('mobile_number');
        $loader->password = Hash::make(Input::get('password'));
        if (Input::has('loader_type')) {
            $loader->type = trim(Input::get('loader_type'));
        } else {
            $loader->type = 'sale';
        }

        if ($loader->save()) {
            return redirect('performance/loaded-by')->with('success', 'Loader Succesfully added');
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        if (isset($id) && !empty($id)) {
            $loader = LoadedBy::find($id);
            return view('view_loaded_by')->with('loader', $loader)->with('performance_index', true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        if (isset($id) && !empty($id)) {
            $loader = LoadedBy::find($id);
            return view('addedit_loaded_by')->with('loader', $loader)->with('performance_index', true);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        if (isset($id) && !empty($id)) {
            $loader = LoadedBy::find($id);
            if (isset($loader)) {
                if (Input::has('password') || Input::has('confirm_password')) {
                    $validator = Validator::make($request->input(), LoadedBy::$ValidateNewLoader, LoadedBy::$validatorMessages);
                    if ($validator->fails()) {
                        return redirect()->back()->withInput()->withErrors($validator->errors());
                    }
                    $loader->password = Hash::make(Input::get('password'));
                } else {
                    $validator = Validator::make($request->input(), LoadedBy::$ValidateUpdateLoader, LoadedBy::$validatorMessages);
                    if ($validator->fails()) {
                        return redirect()->back()->withInput()->withErrors($validator->errors());
                    }
                }
                $loader->first_name = Input::get('first_name');
                $loader->last_name = Input::get('last_name');
                $loader->phone_number = Input::get('mobile_number');
                if (Input::has('loader_type')) {
                    $loader->type = trim(Input::get('loader_type'));
                }

                if ($loader->save()) {
                    return redirect('performance/loaded-by')->with('success', 'Loader succesfully updated.');
                } else {
                    return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, Request $request) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        if (isset($id) && !empty($id)) {
            if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
                $loader = LoadedBy::find($id);
                if ($loader->delete()) {
                    return redirect('performance/loaded-by')->with('success', 'Loader deleted succesfully');
                } else {
                    return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
                }
            } else {
                return redirect('performance/loaded-by')->with('error', 'Please enter a correct password');
            }
        }
    }

    public function performance(Request $request) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $loaded_by = LoadedBy::all();
        $date = date('Y-m-01', time());
        if (Input::has('val')) {
            $val = Input::get('val');
            if ($val == "Month") {
                $year = trim(Input::get('month'));
                $date = date("$year-01-01");
                $enddate = date("$year-12-31", strtotime($year));
                if ($year == date('Y')) {
                    $enddate = date("$year-m-t");
                }
                $delivery_order_data = DeliveryChallan::
                        has('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->withTrashed()
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->withTrashed()
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
                $loaded_by_all = \App\DeliveryChallanLoadedBy::
                        where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
            } else if ($val == "Day") {
                $month = Input::get('month');
                $date = date("Y-m-01", strtotime($month));
                $enddate = date("Y-m-t", strtotime($month));
                $delivery_order_data = DeliveryChallan::
                        has('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->withTrashed()
                        ->get();

                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->withTrashed()
                        ->get();
                $loaded_by_all = \App\DeliveryChallanLoadedBy::
                        where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
            }
        } else {
//            $delivery_order_data = DeliveryChallan::
//                    has('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
//                    ->with('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
//                    ->withTrashed()
////            $delivery_order_data = DeliveryChallan::with('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')
////                    ->where('created_at', '>', "$date")
//                    ->get();

            $purchase_order_data = \App\PurchaseChallan::
                    has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                    ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                    ->withTrashed()
                    ->get();

            $loaded_by_all = \App\DeliveryChallanLoadedBy::get();
        }



        $temp1 = [];
        $pipe = [];
        $loader_arr = [];
        $summedArray = [];
        foreach ($loaded_by_all as $loaded_by_key => $loaded_by_value) {            
            if ($loaded_by_value['total_qty'] != 0) {                
                $total_qty_temp = 0;
                $id = $loaded_by_value['delivery_challan_id'];
                if (isset($summedArray[$id])) {
                    $total_qty_temp = $summedArray[$id];
                }
                if (!isset($loader_arr[$id]['pipe_loaders'])) {
                    $temp_pipe = array();
                }
                if (!isset($loader_arr[$id]['structure_loaders'])) {
                    $temp = array();
                }
                $summedArray[$id] = $total_qty_temp + $loaded_by_value['total_qty'];
                $loader_arr[$id]['delivery_id'] = $id;
                $loader_arr[$id]['delivery_date'] = date('Y-m-d', strtotime($loaded_by_value['created_at']));
                
                $loader_arr[$id]['tonnage'] = $total_qty_temp + $loaded_by_value['total_qty'];
                array_push($temp_pipe, $loaded_by_value['loaded_by_id']);
                array_push($temp, $loaded_by_value['loaded_by_id']);
                $loader_arr[$id]['loaders'] = $temp_pipe;
                if ($loaded_by_value['product_type_id'] == 1) {
                    $loader_arr[$id]['pipe_loaders'] = $temp_pipe;
                    $loader_arr[$id]['pipe_tonnage'] = $loaded_by_value['total_qty'];
                } else if($loaded_by_value['product_type_id'] == 2) {
                    $loader_arr[$id]['structure_loaders'] = $temp;
                    $loader_arr[$id]['structure_tonnage'] = $loaded_by_value['total_qty'];
                }
               
            }
            
        }
        
        foreach ($loader_arr as $key => $value_temp) {
            if(isset($value_temp['pipe_loaders'])){
               $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
               $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
               $loaders_data[$var]['tonnage'] = $value_temp['pipe_tonnage']/ 1000;
               $loaders_data[$var++]['loaders'] = $value_temp['pipe_loaders'];
            }
            if(isset($value_temp['structure_loaders'])){
               $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
               $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
               $loaders_data[$var]['tonnage'] = $value_temp['structure_tonnage']/ 1000;
               $loaders_data[$var++]['loaders'] = $value_temp['structure_loaders'];
            }
        }
        
        



        foreach ($purchase_order_data as $delivery_order_info) {
            $arr = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_loaded_by) && count($delivery_order_info->challan_loaded_by) > 0 && !empty($delivery_order_info->challan_loaded_by)) {
                foreach ($delivery_order_info->challan_loaded_by as $challan_info) {
                    $deliver_sum = 0;
                    array_push($loaders, $challan_info->loaded_by_id);
                    foreach ($challan_info->pc_delivery_challan as $info) {
                        foreach ($info->all_purchase_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->quantity;
                        }
                    }
                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['tonnage'] = round($deliver_sum / count($loaders, 2));
                    $loader_arr['loaders'] = $loaders;
                }
            }
            $loaders_data[$var++] = $loader_arr;
        }

        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);

        $final_array = array();
        $k = 0;
        if (isset($loaded_by) && isset($loaders_data)) {
            foreach ($loaded_by as $key => $labour) {
                foreach ($loaders_data as $key_data => $data) {
                    if (isset($data['loaders'])) {
                        foreach ($data['loaders'] as $key_value => $value) {
                            if ($value == $labour['id']) {
                                $final_array[$k++] = [
                                    'delivery_id' => $data['delivery_id'],
                                    'loader_id' => $value,
                                    'date' => $data['delivery_date'],
                                    'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : 0)
                                ];
                            }
                        }
                    }
                }
            }
        }
        if ($request->ajax()) {
            if ($val == "Month") {
                $html = view('_loaded_by_performance')
                        ->with('date', $enddate)
                        ->with('final_array', $final_array)
                        ->with('loaded_by', $loaded_by)
                        ->with('performance_index', true)
                        ->with('filter_with', "months")
                        ->render();
            } else {
                $html = view('_loaded_by_performance')
                        ->with('date', $date)
                        ->with('final_array', $final_array)
                        ->with('loaded_by', $loaded_by)
                        ->with('performance_index', true)
                        ->with('filter_with', "days")
                        ->render();
            }
            return Response::json(['success' => true, 'date' => $date, 'final_array' => $final_array, 'loaded_by' => $loaded_by, 'performance_index', true, 'html' => $html]);
        } else {
            return view('loaded_by_performance')
                            ->with('date', $date)
                            ->with('final_array', $final_array)
                            ->with('loaded_by', $loaded_by)
                            ->with('performance_index', true);
        }
    }

    public function performance_temp(Request $request) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $loaded_by = LoadedBy::all();
        $date = date('Y-m-01', time());
        if (Input::has('val')) {
            $val = Input::get('val');
            if ($val == "Month") {
                $year = trim(Input::get('month'));
                $date = date("$year-01-01");
                $enddate = date("$year-12-31", strtotime($year));
                if ($year == date('Y')) {
                    $enddate = date("$year-m-t");
                }
                $delivery_order_data = DeliveryChallan::
                        has('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
            } else if ($val == "Day") {
                $month = Input::get('month');
                $date = date("Y-m-01", strtotime($month));
                $enddate = date("Y-m-t", strtotime($month));
                $delivery_order_data = DeliveryChallan::
                        has('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                        ->get();

                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                        ->get();
            }
        } else {
            $delivery_order_data = DeliveryChallan::
                    has('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
                    ->with('challan_loaded_by.dc_delivery_challan.delivery_challan_products')
//            $delivery_order_data = DeliveryChallan::with('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')
//                    ->where('created_at', '>', "$date")
                    ->get();

            $purchase_order_data = \App\PurchaseChallan::
                    has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                    ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                    ->get();
        }
        foreach ($delivery_order_data as $delivery_order_info) {
            $arr = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_loaded_by) && count($delivery_order_info->challan_loaded_by) > 0 && !empty($delivery_order_info->challan_loaded_by)) {
                foreach ($delivery_order_info->challan_loaded_by as $challan_info) {
                    $deliver_sum = 0;
                    array_push($loaders, $challan_info->loaded_by_id);
                    foreach ($challan_info->dc_delivery_challan as $info) {
                        foreach ($info->delivery_challan_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->actual_quantity;
//                                if ($delivery_order_productinfo->unit_id == 1)
//                                    $deliver_sum += $delivery_order_productinfo->quantity;
//                                elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                                    $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
                        }
                    }
                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['tonnage'] = round($deliver_sum / count($loaders, 2));
                    $loader_arr['loaders'] = $loaders;
                }
            }
            $loaders_data[$var++] = $loader_arr;
        }

        foreach ($purchase_order_data as $delivery_order_info) {
            $arr = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_loaded_by) && count($delivery_order_info->challan_loaded_by) > 0 && !empty($delivery_order_info->challan_loaded_by)) {
                foreach ($delivery_order_info->challan_loaded_by as $challan_info) {
                    $deliver_sum = 0;
                    array_push($loaders, $challan_info->loaded_by_id);
                    foreach ($challan_info->pc_delivery_challan as $info) {
                        foreach ($info->all_purchase_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->quantity;
//                                
                        }
                    }
                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['tonnage'] = round($deliver_sum / count($loaders, 2));
                    $loader_arr['loaders'] = $loaders;
                }
            }
            $loaders_data[$var++] = $loader_arr;
        }

        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);
        $final_array = array();
        $k = 0;
        if (isset($loaded_by) && isset($loaders_data)) {
            foreach ($loaded_by as $key => $labour) {
                foreach ($loaders_data as $key_data => $data) {
                    if (isset($data['loaders'])) {
                        foreach ($data['loaders'] as $key_value => $value) {
                            if ($value == $labour['id']) {
                                $final_array[$k++] = [
                                    'delivery_id' => $data['delivery_id'],
                                    'loader_id' => $value,
                                    'date' => $data['delivery_date'],
                                    'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : 0)
                                ];
                            }
                        }
                    }
                }
            }
        }
        if ($request->ajax()) {
            if ($val == "Month") {
                $html = view('_loaded_by_performance')
                        ->with('date', $enddate)
                        ->with('final_array', $final_array)
                        ->with('loaded_by', $loaded_by)
                        ->with('performance_index', true)
                        ->with('filter_with', "months")
                        ->render();
            } else {
                $html = view('_loaded_by_performance')
                        ->with('date', $date)
                        ->with('final_array', $final_array)
                        ->with('loaded_by', $loaded_by)
                        ->with('performance_index', true)
                        ->with('filter_with', "days")
                        ->render();
            }
            return Response::json(['success' => true, 'date' => $date, 'final_array' => $final_array, 'loaded_by' => $loaded_by, 'performance_index', true, 'html' => $html]);
        } else {
            return view('loaded_by_performance')
                            ->with('date', $date)
                            ->with('final_array', $final_array)
                            ->with('loaded_by', $loaded_by)
                            ->with('performance_index', true);
        }
    }

    function checkpending_quantity($unit_id, $product_category_id, $product_qty) {

        $kg_qty = 0;
        $product_info = ProductSubCategory::find($product_category_id);
        if ($unit_id == 1) {
            if (isset($product_info->quantity)) {
                $kg_qty = $product_info->quantity;
            } else {
                $kg_qty = 0;
            }
        } elseif ($unit_id == 2) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
            } else {
                $weight = 0;
            }
            $kg_qty = $kg_qty + ($product_qty * $weight);
        } elseif ($unit_id == 3) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
            } else {
                $weight = 1;
            }
            if (isset($product_info->standard_length)) {
                $std_length = $product_info->standard_length;
            } else {
                $std_length = 0;
            }
            $kg_qty = $kg_qty + (($product_qty / $std_length ) * $weight);
        }
        return $kg_qty;
    }

}
