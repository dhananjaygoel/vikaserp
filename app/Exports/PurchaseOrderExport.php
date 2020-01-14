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
        if ($data['order_status'] == 'pending') {
            $order_status = 'pending';
        } elseif ($data['order_status'] == 'completed') {
            $order_status = 'completed';
        } elseif ($data['order_status'] == 'cancelled') {
            $order_status = 'canceled';
        }

        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
            $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
            if (Auth::user()->role_id <> 5) {
                if ($data['order_for'] == 'direct') {
                    if ($date1 == $date2) {
                        $order_objects = PurchaseOrder::where('order_status', $order_status)
                                ->where('updated_at', 'like', $date1 . '%')
                                ->where('order_for', '!=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    } else {
                        $order_objects = PurchaseOrder::where('order_status', $order_status)
                                ->where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->where('order_for', '!=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    }
                } elseif ($data['order_for'] == 'warehouse') {
                    if ($date1 == $date2) {
                        $order_objects = PurchaseOrder::where('order_status', $order_status)
                                ->where('updated_at', 'like', $date1 . '%')
                                ->where('order_for', '=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    } else {
                        $order_objects = PurchaseOrder::where('order_status', $order_status)
                                ->where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->where('order_for', '=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    }
                } else {
                    if ($date1 == $date2) {
                        $order_objects = PurchaseOrder::where('order_status', $order_status)
                                ->where('updated_at', 'like', $date1 . '%')
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    } else {
                        $order_objects = PurchaseOrder::where('order_status', $order_status)
                                ->where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    }
                }
            }
            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();
                if ($data['order_for'] == 'direct') {
                    if ($date1 == $date2) {
                        $order_objects = PurchaseOrder::where('updated_at', 'like', $date1 . '%')
                                ->where('customer_id', '=', $cust->id)
                                ->where('order_for', '!=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    } else {
                        $order_objects = PurchaseOrder::where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->where('customer_id', '=', $cust->id)
                                ->where('order_for', '!=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    }
                } elseif ($data['order_for'] == 'warehouse') {
                    if ($date1 == $date2) {
                        $order_objects = PurchaseOrder::where('updated_at', 'like', $date1 . '%')
                                ->where('customer_id', '=', $cust->id)
                                ->where('order_for', '=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    } else {
                        $order_objects = PurchaseOrder::where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->where('customer_id', '=', $cust->id)
                                ->where('order_for', '=', 0)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    }
                } else {
                    if ($date1 == $date2) {
                        $order_objects = PurchaseOrder::where('updated_at', 'like', $date1 . '%')
                                ->where('customer_id', '=', $cust->id)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    } else {
                        $order_objects = PurchaseOrder::where('updated_at', '>=', $date1)
                                ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                ->where('customer_id', '=', $cust->id)
                                ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->get();
                    }
                }
            }
        } else {
            if (Auth::user()->role_id <> 5) {
                if ($data['order_for'] == 'direct') {
                    $order_objects = PurchaseOrder::where('order_status', $order_status)
                            ->where('order_for', '!=', 0)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();
                } elseif ($data['order_for'] == 'warehouse') {
                    $order_objects = PurchaseOrder::where('order_status', $order_status)
                            ->where('order_for', '=', 0)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();   
                } else {
                    $order_objects = PurchaseOrder::where('order_status', $order_status)
                            ->with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }

            if (Auth::user()->role_id == 5) {
                $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                        ->where('phone_number1', '=', Auth::user()->mobile_number)
                        ->where('email', '=', Auth::user()->email)
                        ->first();

                if ($data['order_for'] == 'direct') {
                    $order_objects = PurchaseOrder::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->where('customer_id', '=', $cust->id)
                            ->where('order_for', '!=', 0)
                            ->orderBy('created_at', 'desc')
                            ->get();
                } elseif ($data['order_for'] == 'warehouse') {
                    $order_objects = PurchaseOrder::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->where('customer_id', '=', $cust->id)
                            ->where('order_for', '=', 0)
                            ->orderBy('created_at', 'desc')
                            ->get();
                } else {
                    $order_objects = PurchaseOrder::with('purchase_products.unit', 'purchase_products.purchase_product_details', 'customer')
                            ->where('customer_id', '=', $cust->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
                }
            }
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
