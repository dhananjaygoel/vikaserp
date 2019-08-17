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
use App\City;
use App\Customer;
use Hash;
use App\AllOrderProducts;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class SalesDaybookController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                                ->where('updated_at', 'like', $date1 . '%')
                                ->where('serial_number', 'like', '%P%')
                                ->with('customer', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_order.location', 'user', 'order_details', 'order_details.createdby', 'delivery_order', 'delivery_order.user', 'challan_loaded_by', 'challan_labours')
                                ->orderBy('updated_at', 'desc')->Paginate(20);
            } else {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                                ->where('updated_at', '>=', $date1)
                                ->where('serial_number', 'like', '%P%')
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->with('customer', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_order.location', 'user', 'order_details', 'order_details.createdby', 'delivery_order', 'delivery_order.user', 'challan_loaded_by', 'challan_labours')
                                ->orderBy('updated_at', 'desc')->Paginate(20);
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                            ->with('customer', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_order.location', 'user', 'order_details', 'order_details.createdby', 'delivery_order', 'delivery_order.user', 'challan_loaded_by', 'challan_labours')
                            ->where('serial_number', 'like', '%P%')
                            ->orderBy('updated_at', 'desc')->Paginate(20);
        }
        $supplier = Customer::all();
        $challan_date = array('challan_date' => Input::get('challan_date'));
        $allorders->setPath('sales_daybook');
        return view('sales_daybook', compact('allorders', 'challan_date', 'supplier', 'search_dates'));
    }


    function daily_pro_forma_invoice(){
        $data = Input::all();
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                    ->where('updated_at', 'like', $date1 . '%')
                    ->where('serial_number', 'like', '%A%')
                    ->with('customer', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_order.location', 'user', 'order_details', 'order_details.createdby', 'delivery_order', 'delivery_order.user', 'challan_loaded_by', 'challan_labours')
                    ->orderBy('updated_at', 'desc')->Paginate(20);
            } else {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                    ->where('updated_at', '>=', $date1)
                    ->where('serial_number', 'like', '%A%')
                    ->where('updated_at', '<=', $date2 . ' 23:59:59')
                    ->with('customer', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_order.location', 'user', 'order_details', 'order_details.createdby', 'delivery_order', 'delivery_order.user', 'challan_loaded_by', 'challan_labours')
                    ->orderBy('updated_at', 'desc')->Paginate(20);
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                ->with('customer', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_order.location', 'user', 'order_details', 'order_details.createdby', 'delivery_order', 'delivery_order.user', 'challan_loaded_by', 'challan_labours')
                ->where('serial_number', 'like', '%A%')
                ->orderBy('updated_at', 'desc')->Paginate(20);
        }
        $supplier = Customer::all();
        $challan_date = array('challan_date' => Input::get('challan_date'));
        //$allorders->setPath('sales_daybook');
        return view('daily_pro_forma_invoice', compact('allorders', 'challan_date', 'supplier', 'search_dates'));
    }

    /*
     * Challan date function is for sales daybook
     * All records of selected date
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
                            ->with('customer', 'all_order_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(20);
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
                }
                return Redirect::to('sales_daybook')->with('flash_message', 'Selected Challans are Successfully deleted');
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
            return Redirect::to('daily_pro_forma_invoice')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            $challan = DeliveryChallan::find($id);
            $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
            $challan->delete();
            return Redirect::to('daily_pro_forma_invoice')->with('flash_message', 'Challan is Successfully deleted');
        } else {
            return Redirect::to('daily_pro_forma_invoice')->with('error', 'Invalid password');
        }
    }

    public function export_sales_daybook() {
//        ini_set('allow_url_fopen',1);
        set_time_limit(0);
        gc_disable();
        $data = Input::all();
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                        ->where('updated_at', 'like', $date1 . '%')
//                       >with('customer.states', 'customer.customerproduct', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_challan_products.order_product_details.product_category', 'delivery_order', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')
                        ->with('delivery_challan_products.order_product_details', 'challan_loaded_by', 'challan_labours')
                        ->orderBy('updated_at', 'desc')
                        ->take(200)
                        ->get();
            } else {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                        ->where('updated_at', '>=', $date1)
                        ->where('updated_at', '<=', $date2 . ' 23:59:59')
//                        ->with('customer.states', 'customer.customerproduct', 'delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'delivery_challan_products.order_product_details.product_category', 'delivery_order', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')
                        ->with('delivery_challan_products.order_product_details', 'challan_loaded_by', 'challan_labours')
                        ->orderBy('updated_at', 'desc')
                        ->take(200)
                        ->get();
            }
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                    ->with('delivery_challan_products.order_product_details', 'challan_loaded_by', 'challan_labours')
                    ->orderBy('updated_at', 'desc')
//                    ->Paginate(200);   
                    ->take(200)
                    ->get();
        }    
       // echo '<pre>';
       // print_r($allorders) ;
       // exit;
        $VchNo = 0;        
        foreach ($allorders as $key => $value) {
            $sr[$VchNo]['date'] = date("d/m/Y", strtotime($value->updated_at));
            $sr[$VchNo]['type'] = 'Invoice';
            $sr[$VchNo]['no'] = $value->id;
            if($value->customer_id != '') {
                $customer = Customer::find($value->customer_id);
                $deliver_location = $customer->delivery_location_id;
                if($deliver_location){
                   // $city = City::find($deliver_location);
                   
                    $city_name = $value['delivery_challan_products'][0]->['order_product_details']->alias_name;//"Place of supply";
                }
                else{
                    $city_name = "";
                }
                if($customer) {
                    if($customer->tally_name) {
                        $tally_name = $customer->tally_name;
                    } else {
                        $tally_name = 'Anonymous User';
                    }                    
                    $total = $value->grand_price;
                    $total_btax = $value->grand_price;
                    $balance = $value->grand_price;
                    $tax = $value->vat_percentage; 
                    $status = 'Open';
                    $invoice_no = $value->doc_number; 
                    $due_date =  date("d/m/Y", strtotime($value->updated_at));
                    $placeof_supply = $city_name;
                } else {
                    $tally_name = 'Anonymous User';
                    $total = '0.00';
                    $total_btax = '0.00';
                    $balance = '0.00';
                    $tax = '0.00';
                    $status = '';
                    $invoice_no = '';
                    $due_date =  date("d/m/Y", strtotime($value->updated_at));
                    $placeof_supply = $city_name;
                }                                
            } else {
                $tally_name = 'Anonymous User';
                $total = '0.00';
                $total_btax = '0.00';
                $balance = '0.00';
                $tax = '0.00';
                $status = '';
                $invoice_no = '';
                $due_date =  date("d/m/Y", strtotime($value->updated_at));
                $placeof_supply = $city_name;
            }
            
            $sr[$VchNo]['customer'] = $tally_name;
            $sr[$VchNo]['due_date'] = $due_date;            
            $sr[$VchNo]['balance'] = $balance;            
            $sr[$VchNo]['total_btax'] = $total_btax;
            $sr[$VchNo]['tax'] = $tax;
            $sr[$VchNo]['total'] = $total;
            $sr[$VchNo]['status'] = $status;
            $sr[$VchNo]['invoice_no'] = $invoice_no;
            $sr[$VchNo]['placeof_supply'] = $placeof_supply;
            
            $VchNo++;
        }
        //echo '<pre>';
       // print_r($allorders);
       // exit;
        Excel::create('Sales Daybook', function($excel) use($sr) {
            $excel->sheet('Sales-Daybook', function($sheet) use($sr) {
                $sheet->loadView('excelView.sales', array('allorders' => $sr));
            });
        })->export('xls');
        exit();
        /*
          | ----------------------------------------------
          | Old Export Excel code !WARNING - Do not Delete
          | ----------------------------------------------
         */
        Excel::create('Sales Daybook', function($excel) use($allorders) {

            $excel->sheet('Order List', function($sheet) use($allorders) {
                $sheet->mergeCells('A1:E1');
                $sheet->mergeCells('A2:E2');
                $sheet->mergeCells('A3:E3');
                $sheet->mergeCells('A4:E4');

                $sheet->setStyle(array(
                    'vertical-align' => 'middle'
                ));

                $sheet->row(1, function ($row) {
                    $row->setFontSize(16);
                });
                $sheet->row(3, function ($row) {
                    $row->setFontSize(14);
                });
                $sheet->row(2, function ($row) {
                    $row->setFontSize(10);
                });
                $sheet->row(4, function ($row) {
                    $row->setFontSize(10);
                });

                $sheet->setHeight(1, 24);
                $sheet->setHeight(3, 20);

                $sheet->row(1, array('Vikash Associates...(' . date('Y') . ')'));
                $sheet->row(2, array('411014'));
                $sheet->row(3, array('Day Book'));
                $sheet->row(4, array(date('d-m-Y')));

                $data_array = array();
                $sheet->appendRow(array(
                    'Date', 'Particulars', 'Time', 'Vch Type', 'Vch No.', 'Inwards Qty', 'Rate', 'Amount', 'Credit Amount'
                ));
                foreach ($allorders as $key => $value) {
                    $sheet->appendRow(array(
                        'Date' => date('d-m-Y'),
                        'Particulars' => $value['customer']->owner_name,
                        'Time' => '',
                        'Vch Type' => '',
                        'Vch No.' => '',
                        'Inwards Qty' => '',
                        'Rate' => '',
                        'Amount' => '',
                        'Credit Amount' => '',
                    ));

                    foreach ($value['delivery_challan_products'] as $key1 => $value1) {
                        $sheet->appendRow(array(
                            'Date' => '',
                            'Particulars' => $value1['order_product_details']->alias_name,
                            'Time' => '',
                            'Vch Type' => '',
                            'Vch No.' => '',
                            'Inwards Qty' => $value1->quantity,
                            'Rate' => $value1->price,
                            'Amount' => ($value1->quantity * $value1->price),
                            'Credit Amount' => '',
                        ));
                    }
                    $sheet->appendRow(array(
                        'Date' => '',
                        'Particulars' => '',
                        'Time' => '',
                        'Vch Type' => '',
                        'Vch No.' => '',
                        'Inwards Qty' => '',
                        'Rate' => '',
                        'Amount' => '',
                        'Credit Amount' => $value->grand_price,
                    ));
                    $sheet->appendRow(array(
                        'Date' => '',
                        'Particulars' => '3loading',
                        'Time' => '',
                        'Vch Type' => '',
                        'Vch No.' => '',
                        'Inwards Qty' => '',
                        'Rate' => '',
                        'Amount' => '',
                        'Credit Amount' => $value->loading_charge,
                    ));
                    $sheet->appendRow(array(
                        'Date' => '',
                        'Particulars' => $value['delivery_order']->vehicle_number,
                        'Time' => '',
                        'Vch Type' => '',
                        'Vch No.' => '',
                        'Inwards Qty' => '',
                        'Rate' => '',
                        'Amount' => '',
                        'Credit Amount' => '',
                    ));
                    $sheet->appendRow(array(
                        'Date' => '',
                        'Particulars' => '',
                        'Time' => '',
                        'Vch Type' => '',
                        'Vch No.' => '',
                        'Inwards Qty' => '',
                        'Rate' => '',
                        'Amount' => '',
                        'Credit Amount' => '',
                    ));
                }

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 10
                    )
                ));

                $sheet->setAutoSize(true);
            });
        })->export('xls');
        exit();
    }

    /*
     * Print sales day book data
     *
     */

    public function print_sales_order_daybook() {
        set_time_limit(0);
        $data = Input::all();
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                                ->where('updated_at', 'like', $date1 . '%')
                                ->with('customer', 'delivery_challan_products', 'delivery_order.location', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')
                                ->orderBy('updated_at', 'desc')->get();
            } else {
                $allorders = DeliveryChallan::where('challan_status', '=', 'completed')
                                ->where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->with('customer', 'delivery_challan_products', 'delivery_order.location', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')
                                ->orderBy('updated_at', 'desc')->get();
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'completed')->with('customer', 'delivery_challan_products', 'delivery_order.location', 'user', 'delivery_location', 'challan_loaded_by', 'challan_labours')->orderBy('updated_at', 'desc')->get();
        }

        return view('print_sales_order_daybook', compact('allorders'));
    }

    public function recover() {
        $allorders = DeliveryChallan::all();
        $allorders = DeliveryChallan::where('serial_number', '=', 'DC/10/12/1603P')->get();
        $allorders[0]->grand_price = 65788.02;
        $allorders[0]->save();
        echo "<pre>";
        print_r($allorders[0]);
        echo "</pre>";
        exit;
    }

}
