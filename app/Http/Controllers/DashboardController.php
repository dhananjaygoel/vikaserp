<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\Inquiry;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\ProductSubCategory;
use DB;
use Config;
use Rollbar\Rollbar;
use Rollbar\Payload\Level;

class DashboardController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('SEND_LOG', Config::get('rollbar.send'));
//        if (SEND_LOG === true) {
//            $config = array(
//                'access_token' => Config::get('rollbar.access_token'),
//                'environment' => 'delevopment'
//            );
//            Rollbar::init($config);
//        }
    }

    /*
      | Will get counts of total Orders and Pending Delivery Orders.
      | Counts of All purchases. Pending or Inprocess.
      | Counts of total customer in the system
      | Counts of total Inquiries
     */

    public function index() {

            if (Auth::user()->role_id == 5) {
                return Redirect::to('inquiry');
            }

            if (Auth::user()->role_id != 0 && Auth::user()->role_id != 2) {
                return Redirect::to('customers');
            }
            $inquiries_stats_all = [];
            $orders_stats_all = [];
            $delivery_challan_stats_all = [];
//        $order = Order::all()->count();
            
            $orders = Order::where('order_status','pending')->with('all_order_products')->get();           
            $order_pending_sum = 0;
            

//        $pending_order = Order::where('order_status', 'pending')->count();

            foreach ($orders as $order) {
                if ($order->order_status == 'pending') {
                    foreach ($order->all_order_products as $all_order_products) {
                        if ($all_order_products->unit_id == 1)
                            $order_pending_sum += $all_order_products->quantity;
                        elseif (($all_order_products->unit_id == 2) || ($all_order_products->unit_id == 3))
                            $order_pending_sum += $this->checkpending_quantity($all_order_products->unit_id, $all_order_products->product_category_id, $all_order_products->quantity);
                    }
                }
            }

            $order_pending_sum = $order_pending_sum / 1000;

            $inquiry = Inquiry::all()->count();
            $pending_inquiry = Inquiry::where('inquiry_status', 'pending')->count();
            $inquiry_pending_sum = 0;
            $inquiries = Inquiry::with('inquiry_products')->get();
            foreach ($inquiries as $inquiry) {
                foreach ($inquiry->inquiry_products as $all_inquiry_products) {
                    if ($inquiry->inquiry_status == 'pending') {

                        if ($all_inquiry_products->unit_id == 1)
                            $inquiry_pending_sum += $all_inquiry_products->quantity;
                        elseif (($all_inquiry_products->unit_id == 2) || ($all_inquiry_products->unit_id == 3))
                            $inquiry_pending_sum += $this->checkpending_quantity($all_inquiry_products->unit_id, $all_inquiry_products->product_category_id, $all_inquiry_products->quantity);
                    }
                }
            }

            $inquiry_pending_sum = $inquiry_pending_sum / 1000;

            $delivery_order = DeliveryOrder::where('order_status','pending')->with('delivery_product')->get();
            $deliver_sum = 0;
            $deliver_pending_sum = 0;
//        foreach ($delivery_order as $qty) {
//            if ($qty->order_status == 'pending') {
//                foreach ($qty['delivery_product'] as $qty_val) {
//                    $deliver_pending_sum += $qty_val->quantity;
//                }
//            } else if ($qty->order_status == 'completed') {
//                foreach ($qty['delivery_product'] as $qty_val) {
//                    $deliver_sum += $qty_val->quantity;
//                }
//            }
//        }
            foreach ($delivery_order as $delivery_order_info) {
                if ($delivery_order_info->order_status == 'pending') {
                    foreach ($delivery_order_info->delivery_product as $delivery_order_productinfo) {
                        if ($delivery_order_productinfo->unit_id == 1)
                            $deliver_pending_sum += $delivery_order_productinfo->quantity;
                        elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
                            $deliver_pending_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
                    }
                }
//            foreach ($delivery_order_info->delivery_product as $delivery_order_productinfo) {
//                if ($delivery_order_productinfo->unit_id == 1)
//                    $deliver_sum += $delivery_order_productinfo->quantity;
//                elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                    $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
//            }
            }
            $deliver_sum = $deliver_sum / 1000;
            $deliver_pending_sum = $deliver_pending_sum / 1000;


//        $delivery_challan = DeliveryChallan::with('delivery_challan_products')->get();
//        $delivery_challan_sum = 0;
//       
////        foreach ($pur_challan as $qty) {
////            foreach ($qty['delivery_challan_products'] as $qty_val) {
////                $challan_sum += $qty_val->quantity;
////            }
////        }
//        foreach ($delivery_challan as $delivery_challan_info) {
//            foreach ($delivery_challan_info->delivery_challan_products as $delivery_challan_productinfo) {
////                if ($delivery_challan_productinfo->unit_id == 1)
////                    $delivery_challan_sum += $delivery_challan_productinfo->quantity;
////                else
////                    $delivery_challan_sum += $this->checkpending_quantity($delivery_challan_productinfo->unit_id, $delivery_challan_productinfo->product_category_id, $delivery_challan_productinfo->quantity);
//               
//               
//                if($delivery_challan_info->challan_status == 'completed'){
//                    $delivery_challan_sum =  $delivery_challan_sum + $delivery_challan_productinfo->actual_quantity;
//                }
//            }
//        }
//        $delivery_challan_sum = $delivery_challan_sum / 1000;
//        $purc_order_sum = 0;
//        $purchase_order = PurchaseOrder::with('purchase_products')->get();
////        foreach ($pur_challan as $qty) {
////            foreach ($qty['purchase_products'] as $qty_val) {
////                $purc_order_sum += $qty_val->quantity;
////            }
////        }
//        foreach ($purchase_order as $purchase_order_info) {
//            foreach ($purchase_order_info->purchase_products as $purchase_order_productinfo) {
//                if ($purchase_order_productinfo->unit_id == 1)
//                    $purc_order_sum += $purchase_order_productinfo->quantity;
//                else
//                    $purc_order_sum += $this->checkpending_quantity($purchase_order_productinfo->unit_id, $purchase_order_productinfo->product_category_id, $purchase_order_productinfo->quantity);
//            }
//        }
//        $purc_order_sum = $purc_order_sum / 1000;
//        dd(DB::getQueryLog());           
//            exit;


            return view('dashboard', compact('order_pending_sum', 'inquiry_pending_sum', 'deliver_pending_sum'));
        
//        return view('dashboard', compact('order', 'pending_order','order_pending_sum', 'inquiry', 'pending_inquiry', 'inquiry_pending_sum', 'deliver_sum', 'deliver_pending_sum', 'delivery_challan_sum', 'purc_order_sum'));
    }

    function checkpending_quantity($unit_id, $product_category_id, $product_qty) {

        $kg_qty = 0;
        $product_info = ProductSubCategory::find($product_category_id);
        if ($unit_id == 1) {
            if (isset($product_info->quantity)) {
                $kg_qty = $product_info->quantity;
            } else {
                $kg_qty = 0;
            }
        } elseif ($unit_id == 2) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
            } else {
                $weight = 0;
            }
            $kg_qty = $kg_qty + ($product_qty * $weight);
        } elseif ($unit_id == 3) {
            if (!isset($weight)) {
                $weight = 1;
            }
            if (isset($product_info->standard_length)) {
                $std_length = $product_info->standard_length;
            } else {
                $std_length = 0;
            }
            $kg_qty = $kg_qty + (($product_qty / $std_length ) * $weight);
        }
        return $kg_qty;
    }

//    public function logout() {
//
//        Auth::logout(); // logout user
//        return redirect(\URL::previous());
//    }

    /*
      | Custom redirect method returnen by compiler
      | Sometimes Compiler returns/redirects to home.
      | So this method will redirect it to the dashboard.
     */

    public function homeredirect() {
        return redirect('dashboard');
    }

    public function graph_inquiry() {

        /* To get Inquiry stats for graph */


        for ($i = 1; $i <= 7; $i++) {
            $inquiries_stats_all[$i]['pipe'] = 0;
            $inquiries_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $inquiries_stats_all[$i]['day'] = $date_search;
            $inquiries_stats = Inquiry::with('inquiry_products.inquiry_product_details')
                    ->where('inquiry_status', '=', 'completed')
                    ->where('updated_at', 'like', $date_search . '%')
                    ->get();

            foreach ($inquiries_stats as $inquiry) {

                foreach ($inquiry['inquiry_products'] as $inquiry_products) {
                    if (isset($inquiry_products['inquiry_product_details']['product_category']['product_type_id'])) {
                        if ($inquiry_products['inquiry_product_details']['product_category']['product_type_id'] == 1) {
                            if ($inquiry_products['unit_id'] == 1)
                                $inquiries_stats_all[$i]['pipe'] += $inquiry_products['quantity'];
                            elseif (($inquiry_products['unit_id'] == 2) || ($inquiry_products['unit_id'] == 3))
                                $inquiries_stats_all[$i]['pipe'] += $this->checkpending_quantity($inquiry_products['unit_id'], $inquiry_products['product_category_id'], $inquiry_products['quantity']);
                        }else {
                            if ($inquiry_products['unit_id'] == 1)
                                $inquiries_stats_all[$i]['structure'] += $inquiry_products['quantity'];
                            elseif (($inquiry_products['unit_id'] == 2) || ($inquiry_products['unit_id'] == 3))
                                $inquiries_stats_all[$i]['structure'] += $this->checkpending_quantity($inquiry_products['unit_id'], $inquiry_products['product_category_id'], $inquiry_products['quantity']);
                        }
                    }
                }
            }

            $inquiries_stats_all[$i]['pipe'] = round($inquiries_stats_all[$i]['pipe'] / 1000, 2);
            $inquiries_stats_all[$i]['structure'] = round($inquiries_stats_all[$i]['structure'] / 1000, 2);
        }


        return ($inquiries_stats_all);
    }

    public function graph_order() {

        /* To get Order stats for graph */



        for ($i = 1; $i <= 7; $i++) {
            $orders_stats_all[$i]['pipe'] = 0;
            $orders_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $orders_stats_all[$i]['day'] = $date_search;
            $orders_stats = Order::with('all_order_products.order_product_details')
                    ->where('order_status', '=', 'completed')
                    ->where('updated_at', 'like', $date_search . '%')
                    ->get();

            foreach ($orders_stats as $order) {

                foreach ($order['all_order_products'] as $order_products) {


                    if (isset($order_products['order_product_details']['product_category']['product_type_id'])) {
                        if ($order_products['order_product_details']['product_category']['product_type_id'] == 1) {
                            if ($order_products['unit_id'] == 1)
                                $orders_stats_all[$i]['pipe'] += $order_products['quantity'];
                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
                                $orders_stats_all[$i]['pipe'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
                        }else {
                            if ($order_products['unit_id'] == 1)
                                $orders_stats_all[$i]['structure'] += $order_products['quantity'];
                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
                                $orders_stats_all[$i]['structure'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
                        }
                    }
                }
            }

            $orders_stats_all[$i]['pipe'] = round($orders_stats_all[$i]['pipe'] / 1000, 2);
            $orders_stats_all[$i]['structure'] = round($orders_stats_all[$i]['structure'] / 1000, 2);
        }


        return ($orders_stats_all);
    }

    public function graph_delivery_challan() {

        /* To get Delivery Challan stats for graph */


        for ($i = 1; $i <= 7; $i++) {
            $delivery_challan_stats_all[$i]['pipe'] = 0;
            $delivery_challan_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $delivery_challan_stats_all[$i]['day'] = $date_search;
            $delivery_challan_stats = DeliveryChallan::with('delivery_challan_products')
                    ->where('challan_status', '=', 'completed')
                    ->where('updated_at', 'like', $date_search . '%')
                    ->get();

            foreach ($delivery_challan_stats as $delivery_challan) {
                foreach ($delivery_challan['delivery_challan_products'] as $delivery_challan_products) {

                    if (isset($delivery_challan_products['order_product_details']['product_category']['product_type_id'])) {
                        if ($delivery_challan_products['order_product_details']['product_category']['product_type_id'] == 1) {
                            if ($delivery_challan_products['unit_id'] == 1)
                                $delivery_challan_stats_all[$i]['pipe'] += $delivery_challan_products['quantity'];
                            elseif (($delivery_challan_products['unit_id'] == 2) || ($delivery_challan_products['unit_id'] == 3))
                                $delivery_challan_stats_all[$i]['pipe'] += $this->checkpending_quantity($delivery_challan_products['unit_id'], $delivery_challan_products['product_category_id'], $delivery_challan_products['quantity']);
                        }else {
                            if ($delivery_challan_products['unit_id'] == 1)
                                $delivery_challan_stats_all[$i]['structure'] += $delivery_challan_products['quantity'];
                            elseif (($delivery_challan_products['unit_id'] == 2) || ($delivery_challan_products['unit_id'] == 3))
                                $delivery_challan_stats_all[$i]['structure'] += $this->checkpending_quantity($delivery_challan_products['unit_id'], $delivery_challan_products['product_category_id'], $delivery_challan_products['quantity']);
                        }
                    }
                }
            }

            $delivery_challan_stats_all[$i]['pipe'] = round($delivery_challan_stats_all[$i]['pipe'] / 1000, 2);
            $delivery_challan_stats_all[$i]['structure'] = round($delivery_challan_stats_all[$i]['structure'] / 1000, 2);
        }


        return ($delivery_challan_stats_all);
    }

}
