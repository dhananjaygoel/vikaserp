<?php

namespace App\Exports;

use Auth;
use App\Customer;
use App\Units;
use App\DeliveryLocation;
use App\DeliveryOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DOExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return DeliveryOrder::all();
    // }

    public function view(): View
    {
        $data = Input::all();
        set_time_limit(0);
        ini_set('max_execution_time', 1000);
        if ($data['delivery_order_status'] == 'Inprocess') {
            $delivery_order_status = 'pending';
            $excel_sheet_name = 'Inprocess';
            $excel_name = 'DeliveryOrder-InProcess-' . date('dmyhis');
        } elseif ($data['delivery_order_status'] == 'Delivered') {
            $delivery_order_status = 'completed';
            $excel_sheet_name = 'Delivered';
            $excel_name = 'DeliveryOrder-Delivered-' . date('dmyhis');
        }
        if (Auth::user()->role_id == 9){
            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->where('del_boy', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('del_boy', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            } else {
                $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                        ->where('del_boy', Auth::user()->id)
                        ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        }
        elseif (Auth::user()->role_id == 8){
            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->where('del_supervisor', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('del_supervisor', Auth::user()->id)
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            } else {
                $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                        ->where('del_supervisor', Auth::user()->id)
                        ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        }else {
            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            } else {
                $delivery_order_objects = DeliveryOrder::where('order_status', $delivery_order_status)
                        ->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }
        }
        if (count((array)$delivery_order_objects) == 0) {
            return redirect::back()->with('error', 'No data found');
        } else {
            $units = Units::all();
            $delivery_locations = DeliveryLocation::all();
            $customers = Customer::all();
            return view('excelView.delivery_order', array('delivery_order_objects' => $delivery_order_objects, 'units' => $units, 'delivery_locations' => $delivery_locations, 'customers' => $customers));
        }
    }
}
