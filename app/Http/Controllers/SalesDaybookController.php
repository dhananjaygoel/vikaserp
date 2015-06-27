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
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class SalesDaybookController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 ) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

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
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
            $allorders->setPath('sales_daybook_date');
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
        if (Auth::user()->role_id != 0 ) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
    
    public function export_sales_daybook() {
        
        $allorders = DeliveryChallan::where('challan_status', '=', 'completed')->with('customer', 'all_order_products', 'delivery_order', 'user')
                ->get();
//        echo '<pre>';
//        print_r($allorders->toArray());
//        echo '</pre>';
//        exit;
        

        $sheet_data = array();
        foreach ($allorders as $key => $value) {

            $sheet_data[$key]['date'] = date("d F, Y", strtotime($value->created_at));
            $sheet_data[$key]['Party_name'] = $value['customer']->owner_name;
            $sheet_data[$key]['vehicle_number'] = $value['delivery_order']->vehicle_number;
            $sheet_data[$key]['orderedby'] = $value['user'][0]->first_name;
            $sheet_data[$key]['loaded_by'] = $value->loaded_by;
            $sheet_data[$key]['labours'] = $value->labours;
            $sheet_data[$key]['amount'] = $value->amount;
            $sheet_data[$key]['bill_number'] = $value->bill_number;
            $sheet_data[$key]['remarks'] = $value->remarks;
            $sheet_data[$key]['created_at'] = $value->created_at;
            $sheet_data[$key]['updated_at'] = $value->updated_at;
        }
        
//        echo '<pre>';
//        print_r($sheet_data);
//        echo '</pre>';
//        exit;

        Excel::create('Sales-Daybook-list', function($excel) use($sheet_data) {

            $excel->sheet('Order List', function($sheet) use($sheet_data) {
                $sheet->fromArray($sheet_data);
            });
        })->export('xls');

        exit;
        
        
    }
}
