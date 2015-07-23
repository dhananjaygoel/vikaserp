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
use App\DeliveryLocation;
use Hash;
use App\AllOrderProducts;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class SalesDaybookController extends Controller {

    public function __construct() {
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $allorders = DeliveryChallan::where('challan_status', '=', 'completed')->with('customer', 'all_order_products.unit', 'all_order_products.order_product_details', 'delivery_order.location', 'user')->orderBy('created_at', 'desc')->Paginate(10);
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
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $input_data = Input::all();

        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('sales_daybook')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {

            if (isset($input_data['challan_id'])) {
                foreach ($input_data['challan_id'] as $product_data) {
                    if ($product_data['checkbox'] != "") {
                        $id = $product_data['checkbox'];
                        $challan = DeliveryChallan::find($id);
                        $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
                        $challan->delete();
                    }

                    return Redirect::to('sales_daybook')->with('flash_message', 'Selected Challans are Successfully deleted');
                }
            } else {
                return Redirect::to('sales_daybook')->with('error', 'Please select at least on record to delete');
            }
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

        $allorders = DeliveryChallan::where('challan_status', '=', 'completed')->with('customer', 'all_order_products.unit', 'delivery_order', 'user', 'delivery_location')->orderBy('created_at', 'desc')->get();

        $sheet_data = array();
        $i = 1; //export;
        foreach ($allorders as $key => $value) {

            $sheet_data[$key]['Sr no.'] = $i++;
            $sheet_data[$key]['Do No.'] = $value['delivery_order']->serial_no;
            $sheet_data[$key]['Name'] = $value['customer']->owner_name;
            $sheet_data[$key]['Delivery Location'] = $value['delivery_location']->area_name;

            $total_qunatity = 0;
            foreach ($value["all_order_products"] as $products) {
                if ($products['unit']->id == 1) {
                    $total_qunatity += $products->present_shipping;
                }
                if ($products['unit']->id == 2) {
                    $total_qunatity += ($products->present_shipping * $products['order_product_details']->weight);
                }
                if ($products['unit']->id == 3) {
                    $total_qunatity += (($products->present_shipping / $products['order_product_details']->standard_length ) * $products['order_product_details']->weight);
                }
            }


            $sheet_data[$key]['Quantity'] = $total_qunatity;
            $sheet_data[$key]['Grand Total'] = $value->grand_price;
            $sheet_data[$key]['Bill No.'] = $value->bill_number;
            $sheet_data[$key]['Truck No.'] = $value['delivery_order']->vehicle_number;
            $sheet_data[$key]['Loaded By'] = $value->loaded_by;
            $sheet_data[$key]['Labour'] = $value->labours;
            $sheet_data[$key]['Remarks'] = $value->remarks;
        }

        Excel::create('Sales-Daybook-list', function($excel) use($sheet_data) {

            $excel->sheet('Order List', function($sheet) use($sheet_data) {
                $sheet->fromArray($sheet_data);
            });
        })->export('xls');
    }

    public function print_sales_order_daybook() {

        $allorders = DeliveryChallan::where('challan_status', '=', 'completed')->with('customer', 'all_order_products', 'delivery_order.location', 'user', 'delivery_location')->orderBy('created_at', 'desc')->get();
        return view('print_sales_order_daybook', compact('allorders'));
    }

}
