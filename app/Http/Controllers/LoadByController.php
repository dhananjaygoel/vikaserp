<?php

namespace App\Http\Controllers;


use App\Order;
use App\Inquiry;
use App\DeliveryOrder;
use App\PurchaseOrder;
use App\ProductSubCategory;
use App\LoadedBy;
use App\DeliveryChallan;
use App\DeliveryChallanLoadedBy;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DashboardController;

class LoadByController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $loaders = LoadedBy::orderBy('id', 'desc')->get();
        return view('loaded_by')->with('loaders', $loaders)->with('performance_index', true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
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
        if ($loader->save()) {
            return redirect('loaded-by')->with('success', 'Loader Succesfully added');
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
                if ($loader->save()) {
                    return redirect('loaded-by')->with('success', 'Loader succesfully updated.');
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
        if (isset($id) && !empty($id)) {
            $loader = LoadedBy::find($id);
            if ($loader->delete()) {
                return redirect('loaded-by')->with('success', 'Loader deleted succesfully');
            } else {
                return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
            }
        }
    }
    
    public function performance(Request $request){
        $loaders = LoadedBy::all();
        $loaders_data = DeliveryChallanLoadedBy::with('dc_loaded_by')->with('dc_delivery_challan.delivery_order.delivery_product')->get();
        $deliver_sum = 0;
        
//        dd($loaders_data);
        $loader_arr = array();
        foreach ($loaders_data as $loader) {
            $loader_id = $loader->loaded_by_id;
            foreach ($loader->dc_delivery_challan as $challan_info) {
                $arr = array();
                $deliver_pending_sum = 0;
//                dd($challan_info);
                if ($challan_info->delivery_order['order_status'] == 'completed') {
                    foreach ($challan_info->delivery_order->delivery_product as $delivery_order_productinfo) {
//                     dd($delivery_order_productinfo );
//                        foreach ($delivery_order_info->delivery_product as $delivery_order_productinfo) {
                            if ($delivery_order_productinfo->unit_id == 1)
                                $deliver_pending_sum += $delivery_order_productinfo->quantity;
                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
                                $deliver_pending_sum += DashboardController::checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
//                            dd($deliver_pending_sum);
//                        } 
                            
//                            dd($arr);
                    }
                    $arr = array($loader_id,$deliver_pending_sum);
                }
            }
            $loader_arr = array($loader_arr, $arr);
//            $loader_arr = array_prepend($loader_arr , $arr);
        }
        dd($loader_arr);
//        $delivery_order = DeliveryOrder::with('delivery_product')->get();
//        $deliver_sum = 0;s
//        $deliver_pending_sum = 0;
//        foreach ($delivery_order as $delivery_order_info) {
//            if ($delivery_order_info->order_status == 'completed') {
//                foreach ($delivery_order_info->delivery_product as $delivery_order_productinfo) {
//                    if ($delivery_order_productinfo->unit_id == 1)
//                        $deliver_pending_sum += $delivery_order_productinfo->quantity;
//                    elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                        $deliver_pending_sum += DashboardController::checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
//                    
//                }
//            }
//        }
        
//        dd($loaders_data);
        dd($delivery_order_arr);
        return view('loaded_by_performance')->with('loaders_data', $loaders_data)
                ->with('loaders',$loaders)
                ->with('performance_index',true);
        
    }
        
}
