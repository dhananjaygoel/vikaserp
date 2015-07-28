<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\PurchaseChallan;
use App\Units;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeliveryLocation;
use App\Http\Requests\CityRequest;
use App\Http\Requests\EditCityRequest;
use Input;
use Illuminate\Support\Facades\DB;
use Hash;
use Auth;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseDaybookController extends Controller {

    public function __construct() {
        $this->middleware('validIP');
    }

    public function index() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('purchase_order_daybook')->with('error', 'You do not have permission.');
        }

        $purchase_daybook = 0;
        if (Input::get('date') != "") {

            $purchase_daybook = PurchaseChallan::with('orderedby', 'supplier')
                    ->where('order_status', 'completed')
                    ->whereHas('purchase_advice', function($query) {
                        $query->where('purchase_advice_date', '=', date("Y-m-d", strtotime(Input::get('date'))));
                    })
                    ->with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->orderBy('created_at', 'desc')
                    ->Paginate(20);
        } else {

            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->where('order_status', 'completed')
                    ->orderBy('created_at', 'desc')
                    ->Paginate(20);
        }
        
        $purchase_daybook->setPath('purchase_order_daybook');
        return view('purchase_order_daybook', compact('purchase_daybook'));
    }

    public function delete_all_daybook() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $id = Input::all();

        if (Hash::check(Input::get('delete_all_password'), Auth::user()->password)) {
            if (isset($id['daybook'])) {
                foreach ($id['daybook'] as $key) {

                    PurchaseChallan::find($key)->delete();
                }

                return redirect('purchase_order_daybook')->with('success', 'purchase day book details successfully deleted.');
            } else {
                return redirect('purchase_order_daybook')->with('error', 'Please select at least on record to delete');
            }
        } else {

            return redirect('purchase_order_daybook')->with('error', 'Please enter a correct password');
        }
    }

    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_challan = PurchaseChallan::find($id)->delete();
            return redirect('purchase_order_daybook')->with('success', 'purchase day book details successfully deleted.');
        } else {
            return redirect('purchase_order_daybook')->with('error', 'Please enter a correct password');
        }
    }

    public function expert_purchase_daybook() {

        $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details', 'delivery_location')
                ->where('order_status', 'completed')
                ->get();

        Excel::create('Sales Daybook', function($excel) use($purchase_daybook) {

            $excel->sheet('Sales-Daybook', function($sheet) use($purchase_daybook) {

                $sheet->loadView('excelView.purchase', array('purchase_orders' => $purchase_daybook));
            });
        })->export('xls');




        exit();

        $sheet_data = array();
        $i = 1;
        foreach ($purchase_daybook as $key => $value) {

            $sheet_data[$key]['Sl no.'] = $i++;
            $sheet_data[$key]['Pa no.'] = $value['purchase_advice']->serial_number;
            $sheet_data[$key]['Name'] = $value['supplier']->owner_name;
            $sheet_data[$key]['Delivery Location'] = $value['delivery_location']->area_name;

            $total_qunatity = 0;
            foreach ($value["all_purchase_products"] as $products) {

                if ($products->unit_id == 1) {
                    $total_qunatity += $products->present_shipping;
                }
                if ($products->unit_id == 2) {
                    $total_qunatity += ($products->present_shipping * $products['order_product_details']->weight);
                }
                if ($products->unit_id == 3) {
                    $total_qunatity += (($products->present_shipping / $products['order_product_details']->standard_length ) * $products['order_product_details']->weight);
                }
            }


            $sheet_data[$key]['Quantity'] = $total_qunatity;
            $sheet_data[$key]['amount'] = $value->grand_total;
            $sheet_data[$key]['bill_number'] = $value->bill_number;
            $sheet_data[$key]['vehicle_number'] = $value->vehicle_number;
            $sheet_data[$key]['Unloaded By'] = $value->unloaded_by;
            $sheet_data[$key]['labours'] = $value->labours;
            $sheet_data[$key]['remarks'] = $value->remarks;
        }

        Excel::create('Purchase-Daybook-list', function($excel) use($sheet_data) {

            $excel->sheet('Order List', function($sheet) use($sheet_data) {
                $sheet->fromArray($sheet_data);
            });
        })->export('xls');
    }

    public function print_purchase_daybook() {

        $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.product_category.product_sub_category', 'delivery_location')
                ->where('order_status', 'completed')
                ->get();

        return view('print_purchase_order_daybook', compact('purchase_daybook'));
    }

}
