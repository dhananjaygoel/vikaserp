<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Auth;
use App\DeliveryChallan;
use Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\User;
use Hash;
use App\AllOrderProducts;

class SalesDaybookController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {


        $allorders = DeliveryChallan::where('challan_status', '=', 'completed')->with('customer', 'all_order_products', 'delivery_order', 'user')->orderBy('created_at', 'desc')->Paginate(10);
        $challan_date = '';
        $allorders->setPath('sales_daybook');
        return view('sales_daybook', compact('allorders', 'challan_date'));
    }
    
    /*
     * Challan date function is for sales daybook 
     * All records of selected date
     * 
     */

    public function challan_date() {
        $input_data = Input::all();
        $validator = Validator::make($input_data, DeliveryChallan::$challan_date_rules);
        if ($validator->passes()) {
            $date = $input_data['challan_date'];
            $y = date("Y", strtotime($date));
            $m = date("m", strtotime($date));
            $d = date("d", strtotime($date));
            $challan_date = \Carbon\Carbon::create($y, $m, $d, 0, 0, 0);

                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                            ->whereRaw('DATE(created_at) = ?', [$challan_date])
                            ->with('customer', 'all_order_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(10);
            $challan_date = $input_data['challan_date'];
            $allorders->setPath('date/');
            return view('sales_daybook', compact('allorders', 'challan_date'));
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    
    /*
     * Delete multiple selected challan
     * 
     */
    public function delete_multiple_challan() {
        $input_data = Input::all();
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('sales_daybook')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {

            foreach ($input_data['challan_id'] as $product_data) {
                if ($product_data['checkbox'] != "") {
                    $id = $product_data['checkbox'];
                    $challan = DeliveryChallan::find($id);
                    $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
                    $challan->delete();
                }
            }

            return Redirect::to('sales_daybook')->with('flash_message', 'Selected Challans are Successfully deleted');
        } else {
            return Redirect::to('sales_daybook')->with('error', 'Invalid password');
        }
    }

    
    /*
     * Delete Challan of particular id
     */    
    public function delete_challan($id) {
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('sales_daybook')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $challan = DeliveryChallan::find($id);
            $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
            $challan->delete();
            return Redirect::to('sales_daybook')->with('flash_message', 'Challan is Successfully deleted');
        } else {
            return Redirect::to('sales_daybook')->with('error', 'Invalid password');
        }
    }
}
