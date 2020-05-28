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
        $delivery_order_objects = 0;
        $q = DeliveryOrder::query();

        if ($data['delivery_order_status'] == 'Inprocess') {
            $q->where('order_status', 'pending');
            // $delivery_order_status = 'pending';
            // $excel_sheet_name = 'Inprocess';
            // $excel_name = 'DeliveryOrder-InProcess-' . date('dmyhis');
        } elseif ($data['delivery_order_status'] == 'Delivered') {
            $q->where('order_status', 'completed');
            // $delivery_order_status = 'completed';
            // $excel_sheet_name = 'Delivered';
            // $excel_name = 'DeliveryOrder-Delivered-' . date('dmyhis');
        }
        if (Auth::user()->role_id == 9){
            $q->where('del_boy', Auth::user()->id);
       }
        if (Auth::user()->role_id == 8){
            $q->where('del_supervisor', Auth::user()->id);
       }
       $search_dates = [];

        if ((isset($data["export_from_date"]) && $data["export_from_date"] != "") && (isset($data["export_to_date"]) && $data["export_to_date"] != "")) {

            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');

            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $q->where('created_at', 'like', $date1 . '%');
            } else {
                $q->where('created_at', '>=', $date1);
                $q->where('created_at', '<=', $date2 . ' 23:59:59');
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        }

        $delivery_order_objects = $q->with('customer', 'delivery_product.order_product_details', 'user', 'order_details', 'order_details.createdby')
        ->orderBy('created_at', 'desc')
        ->get();
        
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
