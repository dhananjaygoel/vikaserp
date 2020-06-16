<?php

namespace App\Exports;

use App\PurchaseOrder;
use Auth;
use Input;
use App\Units;
use App\DeliveryLocation;
use App\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseOrderExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return PurchaseOrder::all();
    // }

    public function view() : View
    {
        $data = Input::all();
        $q = PurchaseOrder::query();

        if ((isset($data['pending_purchase_order'])) && $data['pending_purchase_order'] != '') {
            $q->where('supplier_id', '=', $data['pending_purchase_order'])->get();
        }
        if ((isset($data['order_for'])) && $data['order_for'] == 'warehouse') {
            $q->where('order_for', '=', 0)->get();
        } elseif ((isset($data['order_for'])) && $data['order_for'] == 'direct') {
            $q->where('order_for', '!=', 0)->get();
        }
        if ((isset($data['order_status'])) && $data['order_status'] != '') {
            $q = $q->where('order_status', '=', $data['order_status']);
        } else {
            $q = $q->where('order_status', '=', 'pending');
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            // dd($data["export_from_date"]);
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if ($date1 == $date2) {
                $q->where('updated_at', 'like', $date1 . '%');
            } else {
                $q->where('updated_at', '>=', $date1);
                $q->where('updated_at', '<=', $date2 . ' 23:59:59');
            }
            $search_dates = [
                'export_from_date' => $data["export_from_date"],
                'export_to_date' => $data["export_to_date"]
            ];
        }
        if (Auth::user()->role_id > 1) {
            $q->where('is_view_all', '=', 1);
        }
        if (Auth::user()->role_id <> 5) {
        $order_objects = $q->orderBy('created_at', 'desc')
        ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
        ->get();
        } elseif(Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();
                        
            $order_objects = $q->orderBy('created_at', 'desc')
            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
            ->where('customer_id', '=', $cust->id)
            ->get(); 
        }

        $excel_sheet_name = 'Purchase-Order';
        $excel_name = 'Purchase-Order-' . date('dmyhis');

        if (count((array)$order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Purchase Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();
            return view('excelView.purchase_order', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
        }
    }
}
