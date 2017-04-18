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

class LoadByController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $loader = '';
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';
            $loaders = LoadedBy::where('first_name', 'like', $term)->orWhere('last_name', 'like', $term)->orderBy('created_at', 'DESC');
        } else {
            $loaders = LoadedBy::orderBy('id', 'desc');
        }
        $loaders = $loaders->paginate(10);
        $loaders->setPath('loaded-by');
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
            if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
                $loader = LoadedBy::find($id);
                if ($loader->delete()) {
                    return redirect('performance/loaded-by')->with('success', 'Loader deleted succesfully');
                } else {
                    return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
                }
            }else{
                return redirect('performance/loaded-by')->with('error', 'Please enter a correct password');
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
                            $dashboard = new DashboardController();
                            if ($delivery_order_productinfo->unit_id == 1)
                                $deliver_sum += $delivery_order_productinfo->quantity;
                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
                                $deliver_sum += $dashboard->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
                        }
                    }
                    array_push($arr, $deliver_sum);
                    array_push($loader_array, $loaders);

                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = $delivery_order_info['created_at'];
                    $loader_arr['delivery_sum'] = $arr;
                    $loader_arr['loaders'] = $loaders;
                    
                }
            }
            $loaders_data[$var] = $loader_arr;
            $var++;            
        }
        $cnt = 0;
        $counter = 0;
        $loader_cnt = array();
        foreach ($loaders_data as $data) {
            $date_arr = array();
            $date_val = $data['delivery_date']->toDateTimeString();
            $date_arr['delivery_date'] = $date_val;            
            if (in_array($date_val, $date_arr)) {
                foreach ($data['loaders'] as $key => $loader) {
                    if (array_key_exists($loader, $loader_cnt)){
                        $val = $loader_cnt[$loader];
                        $loader_cnt[$loader] = $val +  ($data['delivery_sum'][0] / count($data['loaders']));
//                        $sum1 = $date_arr['delivery_sum'];
//                        $date_arr['delivery_sum'] = $sum1 + $data['delivery_sum'][0];
                    }else{
                        $loader_cnt[$loader] = $data['delivery_sum'][0] / count($data['loaders']);
//                        $date_arr['delivery_sum'] = $data['delivery_sum'][0];
                    }
                    $date_arr['delivery_sum'] = $data['delivery_sum'][0];
                }
                
                $cnt++;
            }
            
            $date_arr['count'] = $cnt;
            $date_arr['loaders'] = $loader_cnt;
            $final_arary[$counter] = $date_arr;
            $counter++;
        }
//        foreach ($loaders_data as $data) {
//            $date_arr = array();
//            $loader_cnt = array();
//            $date_val = $data['delivery_date']->toDateTimeString();
////            dd($data['delivery_sum'][0]/count($data['loaders']));
//            $date_arr['delivery_date'] = $date_val;
//            if (in_array($date_val, $date_arr)) {
//                $date_arr['delivery_id'] = $data['delivery_id'];
////                $date_arr['loaders'] = $data['loaders'];
//                foreach ($data['loaders'] as $key => $loader) {
//                    $loader_cnt[$loader] = $data['delivery_sum'][0] / count($data['loaders']);
////                    $date_arr['loaders'][$loader] = $data['delivery_sum'][0] / count($data['loaders']);
//                }
//                $date_arr['delivery_sum'] = $data['delivery_sum'];
//                $cnt++;
//            }
//            $date_arr['count'] = $cnt;
//            $final_arary[$counter] = $date_arr;
//            $counter++;
//        }
//        dd($final_arary);
//        dd($loader_cnt);
        dd($loaders_data);
        return view('loaded_by_performance')
                        ->with('loaders_data', $final_arary)
//                        ->with('loaders_data', $loaders_data)
                        ->with('loaded_by', $loaded_by)
                        ->with('performance_index', true);
    }
}
