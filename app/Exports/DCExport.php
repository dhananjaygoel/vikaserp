<?php

namespace App\Exports;

use App\DeliveryChallan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DCExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $data = Input::all();
        set_time_limit(0);
        ini_set('max_execution_time', 1000);
        if ($data['delivery_order_status'] == 'pending') {
            $delivery_order_status = 'pending';
            $excel_sheet_name = 'Pending';
            $excel_name = 'DeliveryChallan-InProgress-' . date('dmyhis');

            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
                        ->where('updated_at', 'like', $date1 . '%')
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            } else {
                $delivery_challan_objects = DeliveryChallan::where('challan_status',$delivery_order_status)
                        ->where('updated_at', '>=', $date1)
                        ->where('updated_at', '<=', $date2 . ' 23:59:59')
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('updated_at', 'desc')
                        ->get();
                }
            } else {
                $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
                    ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')->orderBy('updated_at', 'desc')->get();
            }

        } elseif ($data['delivery_order_status'] == 'completed') {
            $delivery_order_status = 'completed';
            $excel_sheet_name = 'Completed';
            $excel_name = 'DeliveryChallan-Completed-' . date('dmyhis');

            if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
                        ->where('serial_number', 'like', '%P')
                        ->where('updated_at', 'like', $date1 . '%')
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            } else {
                $delivery_challan_objects = DeliveryChallan::where('challan_status',$delivery_order_status)
                        ->where('serial_number', 'like', '%P')
                        ->where('updated_at', '>=', $date1)
                        ->where('updated_at', '<=', $date2 . ' 23:59:59')
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            }
            } else {
                $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
                    ->where('serial_number', 'like', '%P')
                    ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')->orderBy('updated_at', 'desc')->get();
            }
        }
// dd($delivery_challan_objects);
        if (count((array)$delivery_challan_objects) == 0) {
            return redirect::back()->with('flash_message', 'No data found');
        } else {

            return view('excelView.dc_export', [
                'delivery_challan_objects' => $delivery_challan_objects
            ]);


        // return DeliveryChallan::all();
        }
    }
}
