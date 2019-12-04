<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Customer;
use App\Units;
use App\DeliveryLocation;
use App\Order;
use App\AllOrderProducts;
use App\Http\Requests\PlaceOrderRequest;
use App\ProductSubCategory;
use Input;
use DB;
use Auth;
use App\User;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ManualCompleteOrderRequest;
use App\DeliveryOrder;

class PendingOrderReportController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        $data = Input::all();
        if ((isset($data['party_filter'])) && $data['party_filter'] != '') {
            $allorders = Order::where('customer_id', '=', $data['party_filter'])->where('order_status', '=', 'pending')
                            ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
        } elseif ((isset($data['fulfilled_filter'])) && $data['fulfilled_filter'] != '') {
            if ($data['fulfilled_filter'] == '0') {
                $allorders = Order::where('order_status', '=', 'pending')->where('order_source', '=', 'warehouse')
                                ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
            } else {
                if ($data['fulfilled_filter'] == 'all') {
                    $allorders = $allorders = Order::where('order_status', '=', 'pending')->where('order_source', '=', 'supplier')
                                    ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
                } else {
                    $allorders = Order::where('order_status', '=', 'pending')->where('order_source', '=', 'supplier')
                                    ->where('supplier_id', '=', $data['fulfilled_filter'])
                                    ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
                }
            }
        } elseif ((isset($data['location_filter'])) && $data['location_filter'] != '') {
            if ($data['location_filter'] != '0') {
                $allorders = Order::where('order_status', '=', 'pending')->where('delivery_location_id', '=', $data['location_filter'])
                                ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
            } else {
                $allorders = Order::where('order_status', '=', 'pending')->where('other_location', '=', $data['location_filter'])
                                ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
            }
        } elseif ((isset($data['size_filter'])) && $data['size_filter'] != '') {
            $size = $data['size_filter'];
            $allorders = Order::where('order_status', '=', 'pending')
                            ->with(array('customer', 'delivery_location', 'all_order_products' =>
                                function($q) use($size) {
                                    $q->where('quantity', '=', $size)->where('order_type', 'order');
                                }))->Paginate(20);
        } else {
            $allorders = Order::where('order_status', '=', 'pending')->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(20);
        }

        $users = User::all();
        $customers = Customer::all();
        $delivery_location = DeliveryLocation::all();
        $delivery_order = DeliveryOrder::all();
        $allorder_products = AllOrderProducts::where('order_type', 'order')->groupBy('quantity')->get();

//                where('order_type','=','order')->get();
        $total_quantity = $this->checkpending_quantity($allorders);
//        $allorders->setPath('pending_order_report');
        return View::make('pending_order_report', compact('allorders', 'users', 'customers', 'delivery_location', 'delivery_order', 'allorder_products', 'total_quantity'));
    }

    /**
     * This function returns all the pending quantity of orders
     */
    function checkpending_quantity($allorders) {
        $pending_orders = array();
        foreach ($allorders as $order) {
            $delivery_orders = DeliveryOrder::where('order_id', $order->id)->get();
            $pending_quantity = 0;
            $total_quantity = 0;
            foreach ($delivery_orders as $del_order) {
                $all_order_products = AllOrderProducts::where('order_id', $del_order->id)->where('order_type', 'delivery_order')->get();
                foreach ($all_order_products as $products) {
                    $p_qty = $products['quantity'] - $products['present_shipping'];
                    $pending_quantity = $pending_quantity + $p_qty;
                    $kg = Units::first();
                    $prod_quantity = $products['quantity'];
                    if ($products['unit_id'] != $kg->id) {
                        $product_subcategory = ProductSubCategory::where('product_category_id', $products['product_category_id'])->first();
                        $calculated_quantity = $prod_quantity / $product_subcategory['weight'];
                        $prod_quantity = $calculated_quantity;
                    }
                    $total_quantity = $total_quantity + $prod_quantity;
                }
            }
            $temp = array();
            $temp['id'] = $order->id;
            $temp['total_pending_quantity'] = $pending_quantity;
            $temp['total_quantity'] = $total_quantity;
            array_push($pending_orders, $temp);

//            $allorders['total_pending_quantity_'.$order->id]=$pending_quantity;
        }
        return $pending_orders;
    }

}
