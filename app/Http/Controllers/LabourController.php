<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomer;
use View;
use Hash;
use Auth;
use App;
use Redirect;
use App\User;
use App\DeliveryLocation;
use App\ProductCategory;
use App\CustomerProductDifference;
use App\Customer;
use Input;
use App\States;
use App\City;
use App\Inquiry;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;
use Config;
use App\ProductType;
use App\Labour;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\ProductSubCategory;
use Illuminate\Support\Facades\DB;

class LabourController extends Controller {

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
     *
     * @return Response
     */
    public function index(Request $request) {
        $request->url();
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $labours = '';
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';

            $labours = Labour::orderBy('first_name', 'asc')
                    ->where(function($query) use ($term) {
                        $query->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('phone_number', 'like', $term);
                    })
                    ->paginate(20);
        } else {
            $labours = Labour::orderBy('updated_at', 'desc')->paginate(20);
        }

        if ($request->is('*labours*')) {
            $labours->setPath('labours');
        } else {
            $labours->setPath('performance/labours');
        }


        $city = City::all();


        return view('labours', array('labours' => $labours, 'city' => $city))->with('performance_index', true);
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
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        return View::make('add_labours')->with('performance_index', true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }

        $validator = Validator::make($request->input(), Labour::$new_labours_inquiry_rules, Labour::$validatorMessages);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        $labour = new \App\Labour();
        if (Input::has('first_name')) {
            $labour->first_name = trim(Input::get('first_name'));
        }
        if (Input::has('last_name')) {
            $labour->last_name = trim(Input::get('last_name'));
        }
        if (Input::has('password')) {
            $labour->password = trim(Input::get('password'));
        }
        if (Input::has('phone_number')) {
            $labour->phone_number = trim(Input::get('phone_number'));
        }

        if (Input::has('labour_type')) {
            $labour->type = trim(Input::get('labour_type'));
        } else {
            $labour->type = 'sale';
        }

        if ($labour->save()) {
            return redirect('performance/labours')->with('success', 'Labour Succesfully added');
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving labour');
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
        $labour = Labour::find($id);
        return View::make('labour_details', array('labour' => $labour))->with('performance_index', true);
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
        $labour = Labour::find($id);
        if (count($labour) < 1) {
            return redirect('performance/labours/')->with('error', 'Trying to access an invalid labour');
        }

        return View::make('edit_labours', array('labour' => $labour))->with('performance_index', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $validator = Validator::make($request->input(), Labour::$new_labours_inquiry_rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        $labour = Labour::find($id);

        if (Input::has('first_name')) {
            $labour->first_name = trim(Input::get('first_name'));
        }
        if (Input::has('last_name')) {
            $labour->last_name = trim(Input::get('last_name'));
        }


        if (Input::has('password')) {
            $labour->password = Input::get('password');
        }
        if (Input::has('phone_number')) {
            $labour->phone_number = trim(Input::get('phone_number'));
        }

        if (Input::has('labour_type')) {
            $labour->type = trim(Input::get('labour_type'));
        }

        if ($labour->save()) {
            return redirect('performance/labours')->with('success', 'Labours details updated successfully');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving labours');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('performance/labours')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {

            $labour = Labour::find($id);
            if ($labour->delete()) {
                return redirect('performance/labours')->with('success', 'Labour deleted succesfully');
            } else {
                return Redirect::back()->withInput()->with('error', 'Some error occoured while deleting customer');
            }
//            $labour = Labour::find($id);
//
//            $labour_exist = array();
//            
//            $labour_exist['customer_delivery_challan'] = "";
//            $labour_exist['customer_purchase_challan'] = "";
//            
//            $labour_delivery_challan = DeliveryChallan::where('labours', $labour->id)->get();           
//            $labour_purchase_challan = PurchaseChallan::where('labours', $labour->id)->get();
//
//            $cust_msg = 'Labour can not be deleted as details are associated with one or more ';
//            $cust_flag = "";
//
//            if (isset($labour_delivery_challan) && (count($labour_delivery_challan) > 0)) {
//                $labour_exist['customer_delivery_challan'] = 1;
//                $cust_msg .= "Delievry Challan";
//                $cust_flag = 1;
//            }           
//
//            if (isset($labour_purchase_challan) && (count($labour_purchase_challan) > 0)) {
//                $labour_exist['customer_purchase_challan'] = 1;
//                $cust_msg .= "Purchase Challan";
//                $cust_flag = 1;
//            }
//
//            if ($cust_flag == 1) {
//                return Redirect::to('performance/labours')->with('error', $cust_msg);
//            } else {
//                $labour->delete();                
//                return Redirect::to('performance/labours')->with('success', 'Labour deleted successfully.');
//            }
        } else {
            return Redirect::to('performance/labours')->with('error', 'Invalid password');
        }
    }

    public function labourPerformance(Request $request) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $labours = Labour::all();
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
                        has('challan_labours.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_labours')
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();

                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_labours.pc_delivery_challan.all_purchase_products')
                        ->with('challan_labours')
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
            } else if ($val == "Day") {
                $month = Input::get('month');
                $date = date("Y-m-01", strtotime($month));
                $enddate = date("Y-m-t", strtotime($month));
                $realenddate = date('Y-m-d', time());

                $delivery_order_data = DeliveryChallan::
                        has('challan_labours.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_labours')
                        ->get();

                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_labours.pc_delivery_challan.all_purchase_products')
                        ->with('challan_labours')
                        ->get();
            }
        } else {
            $enddate = date("Y-m-d");
//            $delivery_order_data = DeliveryChallan::with('challan_labours','delivery_challan_products')
//            $delivery_order_data = DeliveryChallan::
//                    has('challan_labours.dc_delivery_challan.delivery_challan_products')
//                    ->with('challan_labours')
////            $delivery_order_data = DeliveryChallan::with('challan_labours.dc_delivery_challan.delivery_order.delivery_product')
//                    ->get();


            $purchase_order_data = \App\PurchaseChallan::
                    has('challan_labours.pc_delivery_challan.all_purchase_products')
                    ->with('challan_labours')
                    ->get();

            $labour_all = \App\DeliveryChallanLabours::get();
        }
//        foreach ($delivery_order_data as $delivery_order_info) {
//            $arr = array();
//            $arr_money = array();
//            $loaders = array();
//            if (isset($delivery_order_info->challan_labours) && count($delivery_order_info->challan_labours) > 0 && !empty($delivery_order_info->challan_labours)) {
//                foreach ($delivery_order_info->challan_labours as $challan_info) {
//                    $deliver_sum = 0.00;
//                    $money = 0.00;
//                    array_push($loaders, $challan_info->labours_id);
//                    foreach ($challan_info->dc_delivery_challan as $info) {
//                        foreach ($info->delivery_challan_products as $delivery_order_productinfo) {
//                            $deliver_sum += $delivery_order_productinfo->actual_quantity;
////                            if ($delivery_order_productinfo->unit_id == 1)
////                                $deliver_sum += $delivery_order_productinfo->quantity;
////                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
////                                $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity,$delivery_order_productinfo->product_sub_category);
//                        }
//                    }
//
//
//                    array_push($loader_array, $loaders);
//                    $all_kg = $deliver_sum / count($loaders);
//                    $all_tonnage = $all_kg / 1000;
//                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
//                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
//                    $loader_arr['labours'] = $loaders;
//                    $loader_arr['tonnage'] = $all_tonnage;
////                    $loader_arr['delivery_sum_money'] = $info->loading_charge / count($loaders);
//                }
//            }
//            $loaders_data[$var] = $loader_arr;
//            $var++;
//        }
        
        $temp1 = [];
        $pipe = [];
        $loader_arr = [];
        $summedArray = [];

       
        foreach ($labour_all as $loaded_by_key => $labour_value) {            
            if ($labour_value['total_qty'] != 0) {                
                $total_qty_temp = 0;
                $id = $labour_value['delivery_challan_id'];
                if (isset($summedArray[$id])) {
                    $total_qty_temp = $summedArray[$id];
                }
                if (!isset($loader_arr[$id]['pipe_labour'])) {
                    $temp_pipe = array();
                }
                if (!isset($loader_arr[$id]['structure_labour'])) {
                    $temp = array();
                }
                $summedArray[$id] = $total_qty_temp + $labour_value['total_qty'];
                $loader_arr[$id]['delivery_id'] = $id;
                $loader_arr[$id]['delivery_date'] = date('Y-m-d', strtotime($labour_value['created_at']));
                
                $loader_arr[$id]['tonnage'] = $total_qty_temp + $labour_value['total_qty'];
                array_push($temp_pipe, $labour_value['labours_id']);
                array_push($temp, $labour_value['labours_id']);
                $loader_arr[$id]['labours'] = $temp_pipe;
                if ($labour_value['product_type_id'] == 1) {
                    $loader_arr[$id]['pipe_labour'] = $temp_pipe;
                    $loader_arr[$id]['pipe_tonnage'] = $labour_value['total_qty'];
                } else if($labour_value['product_type_id'] == 2) {
                    $loader_arr[$id]['structure_labour'] = $temp;
                    $loader_arr[$id]['structure_tonnage'] = $labour_value['total_qty'];
                }
               
            }
            
        }
        
        
        foreach ($loader_arr as $key => $value_temp) {
            if(isset($value_temp['pipe_labour'])){
               $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
               $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
               $loaders_data[$var]['tonnage'] = $value_temp['pipe_tonnage']/ 1000;
               $loaders_data[$var++]['labours'] = $value_temp['pipe_labour'];
            }
            if(isset($value_temp['structure_labour'])){
               $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
               $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
               $loaders_data[$var]['tonnage'] = $value_temp['structure_tonnage']/ 1000;
               $loaders_data[$var++]['labours'] = $value_temp['structure_labour'];
            }
        }
        
        
        foreach ($purchase_order_data as $delivery_order_info) {
            $arr = array();
            $arr_money = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_labours) && count($delivery_order_info->challan_labours) > 0 && !empty($delivery_order_info->challan_labours)) {
                foreach ($delivery_order_info->challan_labours as $challan_info) {
                    $deliver_sum = 0.00;
                    $money = 0.00;
                    array_push($loaders, $challan_info->labours_id);
                    foreach ($challan_info->pc_delivery_challan as $info) {
                        foreach ($info->all_purchase_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->quantity;
//                            if ($delivery_order_productinfo->unit_id == 1)
//                                $deliver_sum += $delivery_order_productinfo->quantity;
//                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                                $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity,$delivery_order_productinfo->product_sub_category);
                        }
                    }


                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['labours'] = $loaders;
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['delivery_sum_money'] = $info->loading_charge / count($loaders);
                }
            }
            $loaders_data[$var] = $loader_arr;
            $var++;
        }


        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);


        $final_array = array();
        $k = 0;
        foreach ($labours as $key => $labour) {
            foreach ($loaders_data as $key_data => $data) {
                foreach ($data['labours'] as $key_value => $value) {
                    if ($value == $labour['id']) {
                        $final_array[$k++] = [
                            'delivery_id' => $data['delivery_id'],
                            'labour_id' => $value,
                            'date' => $data['delivery_date'],
                            'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : '0'),
                            'delivery_sum_money' => isset($data['delivery_sum_money']) ? $data['delivery_sum_money'] : '0',
                        ];
                    }
                }
            }
        }
        if ($request->ajax()) {
            if ($val == "Month") {
                $html = view('_labours_performance')
                        ->with('labours', $labours)
                        ->with('data', $final_array)
                        ->with('enddate', $enddate)
                        ->with('filter', 'Months')
                        ->with('performance_index', true)
                        ->render();
            } else {
                $html = view('_labours_performance')
                        ->with('labours', $labours)
                        ->with('data', $final_array)
                        ->with('enddate', $enddate)
                        ->with('filter', 'Days')
                        ->with('performance_index', true)
                        ->render();
            }
            return Response::json(['success' => true, 'date' => $enddate, 'final_array' => $final_array, 'labours' => $labours, 'performance_index', true, 'html' => $html]);
        } else {
            return view('labour_performance')
                            ->with('labours', $labours)
                            ->with('data', $final_array)
                            ->with('enddate', $enddate)
                            ->with('performance_index', true);
        }
    }

    public function labourPerformance_temp(Request $request) {
        if (Auth::user()->role_id != 0) {
            return redirect()->back();
        }
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $labours = Labour::all();
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
                        has('challan_labours.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_labours')
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();

                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_labours.pc_delivery_challan.all_purchase_products')
                        ->with('challan_labours')
                        ->where('created_at', '>=', "$date")
                        ->where('created_at', '<=', "$enddate")
                        ->get();
            } else if ($val == "Day") {
                $month = Input::get('month');
                $date = date("Y-m-01", strtotime($month));
                $enddate = date("Y-m-t", strtotime($month));
                $realenddate = date('Y-m-d', time());

                $delivery_order_data = DeliveryChallan::
                        has('challan_labours.dc_delivery_challan.delivery_challan_products')
                        ->with('challan_labours')
                        ->get();

                $purchase_order_data = \App\PurchaseChallan::
                        has('challan_labours.pc_delivery_challan.all_purchase_products')
                        ->with('challan_labours')
                        ->get();
            }
        } else {
            $enddate = date("Y-m-d");
//            $delivery_order_data = DeliveryChallan::with('challan_labours','delivery_challan_products')
            $delivery_order_data = DeliveryChallan::
                    has('challan_labours.dc_delivery_challan.delivery_challan_products')
                    ->with('challan_labours')
//            $delivery_order_data = DeliveryChallan::with('challan_labours.dc_delivery_challan.delivery_order.delivery_product')
                    ->get();


            $purchase_order_data = \App\PurchaseChallan::
                    has('challan_labours.pc_delivery_challan.all_purchase_products')
                    ->with('challan_labours')
                    ->get();
        }
        foreach ($delivery_order_data as $delivery_order_info) {
            $arr = array();
            $arr_money = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_labours) && count($delivery_order_info->challan_labours) > 0 && !empty($delivery_order_info->challan_labours)) {
                foreach ($delivery_order_info->challan_labours as $challan_info) {
                    $deliver_sum = 0.00;
                    $money = 0.00;
                    array_push($loaders, $challan_info->labours_id);
                    foreach ($challan_info->dc_delivery_challan as $info) {
                        foreach ($info->delivery_challan_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->actual_quantity;
//                            if ($delivery_order_productinfo->unit_id == 1)
//                                $deliver_sum += $delivery_order_productinfo->quantity;
//                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                                $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity,$delivery_order_productinfo->product_sub_category);
                        }
                    }


                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['labours'] = $loaders;
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['delivery_sum_money'] = $info->loading_charge / count($loaders);
                }
            }
            $loaders_data[$var] = $loader_arr;
            $var++;
        }

        foreach ($purchase_order_data as $delivery_order_info) {
            $arr = array();
            $arr_money = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_labours) && count($delivery_order_info->challan_labours) > 0 && !empty($delivery_order_info->challan_labours)) {
                foreach ($delivery_order_info->challan_labours as $challan_info) {
                    $deliver_sum = 0.00;
                    $money = 0.00;
                    array_push($loaders, $challan_info->labours_id);
                    foreach ($challan_info->pc_delivery_challan as $info) {
                        foreach ($info->all_purchase_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->quantity;
//                            if ($delivery_order_productinfo->unit_id == 1)
//                                $deliver_sum += $delivery_order_productinfo->quantity;
//                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                                $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity,$delivery_order_productinfo->product_sub_category);
                        }
                    }


                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['labours'] = $loaders;
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['delivery_sum_money'] = $info->loading_charge / count($loaders);
                }
            }
            $loaders_data[$var] = $loader_arr;
            $var++;
        }


        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);


        $final_array = array();
        $k = 0;
        foreach ($labours as $key => $labour) {
            foreach ($loaders_data as $key_data => $data) {
                foreach ($data['labours'] as $key_value => $value) {
                    if ($value == $labour['id']) {
                        $final_array[$k++] = [
                            'delivery_id' => $data['delivery_id'],
                            'labour_id' => $value,
                            'date' => $data['delivery_date'],
                            'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : '0'),
                            'delivery_sum_money' => isset($data['delivery_sum_money']) ? $data['delivery_sum_money'] : '0',
                        ];
                    }
                }
            }
        }
        if ($request->ajax()) {
            if ($val == "Month") {
                $html = view('_labours_performance')
                        ->with('labours', $labours)
                        ->with('data', $final_array)
                        ->with('enddate', $enddate)
                        ->with('filter', 'Months')
                        ->with('performance_index', true)
                        ->render();
            } else {
                $html = view('_labours_performance')
                        ->with('labours', $labours)
                        ->with('data', $final_array)
                        ->with('enddate', $enddate)
                        ->with('filter', 'Days')
                        ->with('performance_index', true)
                        ->render();
            }
            return Response::json(['success' => true, 'date' => $enddate, 'final_array' => $final_array, 'labours' => $labours, 'performance_index', true, 'html' => $html]);
        } else {
            return view('labour_performance')
                            ->with('labours', $labours)
                            ->with('data', $final_array)
                            ->with('enddate', $enddate)
                            ->with('performance_index', true);
        }
    }

    function checkpending_quantity($unit_id, $product_category_id, $product_qty, $prod_info = false) {

        $kg_qty = 0;
        if ($prod_info && count($prod_info)) {
            $product_info = $prod_info;
        } else {
            $product_info = ProductSubCategory::find($product_category_id);
        }

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
