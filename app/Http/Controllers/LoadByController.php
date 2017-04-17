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
        if (isset($id) && !empty($id)) {
            $loader = LoadedBy::find($id);
            if ($loader->delete()) {
                return redirect('performance/loaded-by')->with('success', 'Loader deleted succesfully');
            } else {
                return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
            }
        }
    }
    
    public function performance(Request $request) {
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $loaded_by = LoadedBy::all();
        $delivery_order_data = DeliveryChallan::with('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')->get();
        foreach ($delivery_order_data as $delivery_order_info) {
            $arr = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_loaded_by) && count($delivery_order_info->challan_loaded_by) > 0 && !empty($delivery_order_info->challan_loaded_by)) {
                foreach ($delivery_order_info->challan_loaded_by as $challan_info) {
                    $deliver_sum = 0;
                    array_push($loaders, $challan_info->loaded_by_id);
                    foreach ($challan_info->dc_delivery_challan as $info) {
                        foreach ($info->delivery_order->delivery_product as $delivery_order_productinfo) {
                            if ($delivery_order_productinfo->unit_id == 1)
                                $deliver_sum += $delivery_order_productinfo->quantity;
                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
                                $deliver_sum += DashboardController::checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
                        }
                    }
                    array_push($arr, $deliver_sum);
                    array_push($loader_array, $loaders);

                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = $delivery_order_info['created_at'];
                    $loader_arr['loaders'] = $loaders;
                    $loader_arr['delivery_sum'] = $arr;
                }
            }
            $loaders_data[$var] = $loader_arr;
            $var++;
        }
        return view('loaded_by_performance')->with('loaders_data', $loaders_data)
                        ->with('loaded_by', $loaded_by)
                        ->with('performance_index', true);
    }
}
