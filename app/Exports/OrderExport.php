<?php

namespace App\Exports;

use Auth;
use App\Order;
use App\Customer;
use App\Units;
use App\TerritoryLocation;
use App\DeliveryLocation;
use App\ProductSubCategory;
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
        $q = Order::query();

        if ($data['order_status'] == 'pending') {
            $q->where('is_approved', '=', 'yes')
            ->where('order_status', '=', 'pending');
        } elseif ($data['order_status'] == 'completed') {
            $q->where('order_status', '=', 'completed');
        } elseif ($data['order_status'] == 'approval') {
            $q->where('is_approved', '=', 'no')
            ->where('order_status', '=', 'pending');
        } elseif ($data['order_status'] == 'cancelled') {
            $q->where('order_status', '=', 'cancelled');
        }
        if (isset($data["territory_filter"]) && $data["territory_filter"] != '') {
            $loc_arr = [];
            $territory_arr = [];
            $territory_id = $data["territory_filter"];
            $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
            if (isset($territory_locations)) {
                foreach ($territory_locations as $loc) {
                    if (!in_array($loc->teritory_id, $loc_arr)) {
                        array_push($territory_arr, $loc->teritory_id);
                    }
                    array_push($loc_arr, $loc->location_id);
                }
                $q->whereIn('delivery_location_id', $loc_arr);
            }
        }
        if (isset($data['party_filter']) && $data['party_filter'] != '') {
            $q->where('customer_id', '=', $data['party_filter']);
        }
        if (isset($data['fulfilled_filter']) && $data['fulfilled_filter'] != '') {
            if ($data['fulfilled_filter'] == '0') {
                $q->where('order_source', '=', 'warehouse');
            }
            if ($data['fulfilled_filter'] == 'all') {
                $q->where('order_source','=', 'supplier');
            }
        }
        if ((isset($data['location_filter'])) && $data['location_filter'] != '') {
            $q->where('delivery_location_id', '=', $data['location_filter']);
        }
        if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
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
        $product_category_id = 0;
        if (isset($data['size_filter']) && $data['size_filter'] != '') {
            $size = $data['size_filter'];
            $result = explode(' - ',$size);
            $subquerytest = ProductSubCategory::select('id')->where('size', '=', $result[0])->where('alias_name','=',$result[1])->first();
            if (isset($subquerytest)) {
                $product_category_id = $subquerytest->id;
                $q->whereHas('all_order_products.product_sub_category', function($query) use ($product_category_id) {
                    $query->where('id', '=', $product_category_id);
                });
            } else {
                return Redirect::back()->withInput()->with('flash_message', 'Please Enter Valid Size Name');
            }
        } else {
            $q->with('all_order_products');
        }
        if (Auth::user()->role_id <> 5) {
            $order_objects = $q->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                                ->orderBy('created_at', 'desc')
                                ->get();
        }
        if (Auth::user()->role_id == 5) {
            $cust = Customer::where('owner_name', '=', Auth::user()->first_name)
                            ->where('phone_number1', '=', Auth::user()->mobile_number)
                            ->where('email', '=', Auth::user()->email)
                            ->first();

            $order_objects = $q->where('customer_id', '=', $cust->id)
                            ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'createdby')
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        $excel_sheet_name = 'Order';
        $excel_name = 'Order-' . date('dmyhis');

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
