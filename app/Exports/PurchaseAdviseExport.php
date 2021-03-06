<?php

namespace App\Exports;

use App\PurchaseAdvise;
use Auth;
use Input;
use App\Units;
use App\DeliveryLocation;
use App\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class PurchaseAdviseExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return PurchaseAdvise::all();
    // }

    public function view() : View
    {
        $data = Input::all();
        set_time_limit(0);
        if ($data['purchaseaAdviseFilter'] == 'In_process' || $data['purchaseaAdviseFilter'] == 'in_process') {
            $order_status = 'in_process';
        } elseif ($data['purchaseaAdviseFilter'] == 'Delivered' || $data['purchaseaAdviseFilter'] == 'delivered') {
            $order_status = 'delivered';
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if (Auth::user()->role_id <> 5) {

                if ($date1 == $date2) {
                    $order_objects = PurchaseAdvise::where('advice_status','=', $order_status)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseAdvise::where('advice_status','=', $order_status)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();

                if ($date1 == $date2) {
                    $order_objects = PurchaseAdvise::where('updated_at', 'like', $date1 . '%')
                            ->where('customer_id', '=', $cust->id)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseAdvise::where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('customer_id', '=', $cust->id)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
        } else {

            if (Auth::user()->role_id <> 5) {

                $order_objects = PurchaseAdvise::where('advice_status', $order_status)
                        ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }

            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();


                $order_objects = PurchaseAdvise::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'supplier')
                        ->where('customer_id', '=', $cust->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

            }
        }

        if (count((array)$order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Purchase Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();

            return view('excelView.purchase_advise', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
        }
    }
}
