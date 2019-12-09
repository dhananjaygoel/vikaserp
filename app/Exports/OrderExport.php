<?php

namespace App\Exports;

use Auth;
use App\Order;
use App\Customer;
use App\Units;
use App\DeliveryLocation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Order::all();
    // }

    public function view(): View
    {
        $data = Input::all();
        
        if ($data['order_status'] == 'pending') {
            $is_approved = 'yes';
            $order_status = 'pending';
        } elseif ($data['order_status'] == 'completed') {
            $is_approved = 'yes';
            $order_status = 'completed';
        } elseif ($data['order_status'] == 'approval') {
            $is_approved = 'no';
            $order_status = 'pending';
        } elseif ($data['order_status'] == 'cancelled') {
            $is_approved = 'yes';
            $order_status = 'cancelled';
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if (Auth::user()->role_id <> 5) {
                if ($date1 == $date2) {
                    $order_objects = Order::where('order_status', $order_status)
                            ->where('is_approved',$is_approved)
                            ->where('updated_at', 'like', $date1 . '%')
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = Order::where('order_status', $order_status)
                            ->where('is_approved', '=', $is_approved)
                            ->where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
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
                    $order_objects = Order::where('updated_at', 'like', $date1 . '%')
                            ->where('customer_id', '=', $cust->id)
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = Order::where('updated_at', '>=', $date1)
                            ->where('updated_at', '<=', $date2 . ' 23:59:59')
                            ->where('customer_id', '=', $cust->id)
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
        } else {

            if (Auth::user()->role_id <> 5) {

                $order_objects = Order::where('order_status', $order_status)
                        ->where('is_approved', '=', $is_approved)
                        ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                        ->orderBy('created_at', 'desc')
                        ->get();
            }

            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();


                $order_objects = Order::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                        ->where('customer_id', '=', $cust->id)
                        ->orderBy('created_at', 'desc')
                        ->get();

                $excel_sheet_name = 'Order';
                $excel_name = 'Order-' . date('dmyhis');
            }
        }

        if (count((array)$order_objects) == 0) {
            return redirect::back()->with('flash_message', 'Order does not exist.');
        } else {
            $units = Units::all();
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            $customers = Customer::orderBy('tally_name', 'ASC')->get();
            return view('excelView.order', array('order_objects' => $order_objects, 'units' => $units, 'delivery_location' => $delivery_location, 'customers' => $customers));
        }
    }
}
