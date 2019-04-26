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
use Redirect;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseDaybookController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        $this->middleware('validIP');
    }

    /*
     * Show list of purchase day book
     *
     */

    public function index() {
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4 ) {
           return Redirect::back()->withInput()->with('error', 'You do not have permission.');
           }
        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier','challan_loaded_by','challan_labours','all_purchase_products.purchase_product_details')
                        ->where('order_status', 'completed')
                        ->where('updated_at', 'like', $date1 . '%')
                        ->where('serial_number','LIKE','%A%')
                        ->orderBy('updated_at', 'desc')
                        ->Paginate(20);
            } else {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier','challan_loaded_by','challan_labours','all_purchase_products.purchase_product_details')
                        ->where('order_status', 'completed')
                        ->where('updated_at', '>=', $date1)
                        ->where('serial_number','LIKE','%A%')
                        ->where('updated_at', '<=', $date2.' 23:59:59')                        
                        ->orderBy('updated_at', 'desc')
                        ->Paginate(20);
            }
        } else {
            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier','challan_loaded_by','challan_labours','all_purchase_products.purchase_product_details')
                                                ->where('order_status', 'completed')
                                                ->where('serial_number','LIKE','%A%')
                                                ->orderBy('updated_at', 'desc')
                                                ->Paginate(20);
        }        
        $purchase_daybook->setPath('purchase_order_daybook');        
        return view('purchase_order_daybook', compact('purchase_daybook'));
    }



    function purchase_estimate(){
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4 ) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $v = "P";
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier','challan_loaded_by','challan_labours','all_purchase_products.purchase_product_details')
                    ->where('order_status', 'completed')
                    ->where('updated_at', 'like', $date1 . '%')
                    ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                    ->orderBy('updated_at', 'desc')
                    ->Paginate(20);
            } else {
                $v = "P";
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier','challan_loaded_by','challan_labours','all_purchase_products.purchase_product_details')
                    ->where('order_status', 'completed')
                    ->where('updated_at', '>=', $date1)
                    ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                    ->where('updated_at', '<=', $date2.' 23:59:59')
                    ->orderBy('updated_at', 'desc')
                    ->Paginate(20);
            }
        } else {
            $v = "P";
            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier','challan_loaded_by','challan_labours','all_purchase_products.purchase_product_details')
                ->where('order_status', 'completed')
                ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                ->orderBy('updated_at', 'desc')
                ->Paginate(20);
        }
        $purchase_daybook->setPath('purchase_order_daybook');
        return view('purchase_estimate', compact('purchase_daybook'));
    }

    /*
     * Delete all purchase day book
     *
     */

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

    /*
     * Delete particular purchase day book
     *
     */

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

    /*
     * Export/download purchase day book data into excel file
     *
     */

    public function expert_purchase_daybook() {
        set_time_limit(0);
        $data = Input::all();
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"]) ) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                        ->where('order_status', 'completed')
                        ->where('serial_number','LIKE','%A%')
                        ->where('updated_at', 'like', $date1 . '%')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            } else {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                        ->where('order_status', 'completed')
                        ->where('serial_number','LIKE','%A%')
                        ->where('updated_at', '>=', $date1)
                        ->where('updated_at', '<=', $date2.' 23:59:59')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            }
        } else {
            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                    ->where('order_status', 'completed')
                    ->where('serial_number','LIKE','%A%')
                    ->orderBy('updated_at', 'desc')
                    ->get();
        }
        Excel::create('Purchase Daybook', function($excel) use($purchase_daybook) {
            $excel->sheet('Purchase-Daybook', function($sheet) use($purchase_daybook) {
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


    public function expert_purchase_estimate() {
        $v = "P";
        set_time_limit(0);
        $data = Input::all();
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"]) ) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                    ->where('order_status', 'completed')
                    ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                    ->where('updated_at', 'like', $date1 . '%')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            } else {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                    ->where('order_status', 'completed')
                    ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                    ->where('updated_at', '>=', $date1)
                    ->where('updated_at', '<=', $date2.' 23:59:59')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        } else {
            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                ->where('order_status', 'completed')
                ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                ->orderBy('updated_at', 'desc')
                ->get();
        }
        Excel::create('Purchase Estimate', function($excel) use($purchase_daybook) {
            $excel->sheet('Purchase-Estimate', function($sheet) use($purchase_daybook) {
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

    /*
     * Print purchase day book data into excel file
     *
     */

    public function print_purchase_daybook() {

        $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details', 'delivery_location')
                ->where('order_status', 'completed')
                ->orderBy('created_at', 'desc')
                ->get();

        return view('print_purchase_order_daybook', compact('purchase_daybook'));
    }

}
