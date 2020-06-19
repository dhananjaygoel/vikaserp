<?php

namespace App\Exports;

use App\PurchaseChallan;
use Auth;
use Input;
use App\Units;
use App\DeliveryLocation;
use App\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseDaybookExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return PurchaseChallan::all();
    // }

    public function view() : View
    {
        $v = "P";
        set_time_limit(0);
        $data = Input::all();
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"]) ) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                        ->where('order_status', 'completed')
                        ->where(function($query) {
                            $v = "P";
                            $query
                            ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                            ->orWhere('vat_percentage','>','0');
                        })
                        ->where('updated_at', 'like', $date1 . '%')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            } else {
                $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                        ->where('order_status', 'completed')
                        ->where(function($query) {
                            $v = "P";
                            $query
                            ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                            ->orWhere('vat_percentage','>','0');
                        })
                        ->where('updated_at', '>=', $date1)
                        ->where('updated_at', '<=', $date2.' 23:59:59')
                        ->orderBy('updated_at', 'desc')
                        ->get();
            }
        } else {
            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier.states', 'all_purchase_products.purchase_product_details', 'delivery_location')
                    ->where('order_status', 'completed')
                    ->where(function($query) {
                        $v = "P";
                        $query
                        ->whereRaw('SUBSTRING(serial_number, -1)="'.$v.'"')
                        ->orWhere('vat_percentage','>','0');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->get();
        }
        return view('excelView.purchase', array('purchase_orders' => $purchase_daybook));
    }
}
