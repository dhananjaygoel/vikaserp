<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\User;
use App\Inquiry;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\ProductSubCategory;
use DB;
use Config;
use Rollbar\Rollbar;
use Rollbar\Payload\Level;
use Carbon;
use App\AllOrderProducts;

class DashboardController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        // $this->middleware('validIP');

//        if (Config::get('rollbar.send') === true) {
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

    public function ipvalid_dashboard(){
        return view('dashboard_ipvalid');
    }

    public function index() {
        // dd(Auth::user()->id);
        // exit;
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if(Auth::user()->role_id == 8 || Auth::user()->role_id == 9 || Auth::user()->role_id == 3){
            User::where('id',Auth::user()->id)->update(['is_active'=>'1']);
            return Redirect::to('delivery_order');
        }

        if (Auth::user()->role_id == 7) {
            return Redirect::to('vehicle-list');
        }
        if (Auth::user()->role_id == 5) {
            return Redirect::to('inquiry');
        }

        if (Auth::user()->role_id == 10) {
            return Redirect::to('bulk-delete');
        }

        if (Auth::user()->role_id == 4) {
            return Redirect::to('delivery_order');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }


        $inquiries_stats_all = [];
        $orders_stats_all = [];
        $delivery_challan_stats_all = [];
//        $order = Order::all()->count();

        $orders = Order::where('order_status', 'pending')->with('all_order_products')->get();
        $order_pending_sum = 0;

//        $pending_order = Order::where('order_status', 'pending')->count();

        foreach ($orders as $order) {
            if ($order->order_status == 'pending') {
                $order_pending = 0;
                foreach ($order->all_order_products as $all_order_products) {
                    if ($all_order_products->unit_id == 1){
                        $order_pending += $all_order_products->quantity;
                    } elseif ($all_order_products->unit_id == 2){
                        $order_pending += $all_order_products->quantity * $all_order_products->product_sub_category->weight;
                    } elseif ($all_order_products->unit_id == 3){
                        $order_pending += ($all_order_products->quantity / $all_order_products->product_sub_category->standard_length) * $all_order_products->product_sub_category->weight;
                    } elseif ($all_order_products->unit_id == 4){
                        $order_pending += $all_order_products->quantity * $all_order_products->product_sub_category->weight * $all_order_products->length;
                    } elseif ($all_order_products->unit_id == 5){
                        $order_pending += $all_order_products->quantity * $all_order_products->product_sub_category->weight * ($all_order_products->length/305);
                    }

                    // $order_pending_sum += $this->checkpending_quantity($all_order_products->unit_id, $all_order_products->product_category_id, $all_order_products->quantity);
                    //     $order_pending_sum += $this->checkpending_quantity($all_order_products->unit_id, $all_order_products->product_category_id, $all_order_products->quantity, $all_order_products->product_sub_category, $all_order_products->length);
                }
                $order_pending_sum += round($order_pending,2);
            }
        }

        $order_pending_sum = $order_pending_sum / 1000;

        $inquiry = Inquiry::all()->count();
        $pending_inquiry = Inquiry::where('inquiry_status', 'pending')
                                    ->where('is_approved', 'yes')->count();
        $inquiry_pending_sum = 0;
        $inquiries = Inquiry::with('inquiry_products')->get();
        foreach ($inquiries as $inquiry) {
            if ($inquiry->inquiry_status == 'pending' && $inquiry->is_approved == 'yes') {
                $inquiry_pending = 0;
                foreach ($inquiry->inquiry_products as $all_inquiry_products) {
                    if ($all_inquiry_products->unit_id == 1){
                        $inquiry_pending += $all_inquiry_products->quantity;
                    } elseif ($all_inquiry_products->unit_id == 2){
                        $inquiry_pending += $all_inquiry_products->quantity * $all_inquiry_products->product_sub_category->weight;
                    } elseif ($all_inquiry_products->unit_id == 3){
                        $inquiry_pending += ($all_inquiry_products->quantity / $all_inquiry_products->product_sub_category->standard_length) * $all_inquiry_products->product_sub_category->weight;
                    } elseif ($all_inquiry_products->unit_id == 4){
                        $inquiry_pending += $all_inquiry_products->quantity * $all_inquiry_products->product_sub_category->weight * $all_inquiry_products->length;
                    } elseif ($all_inquiry_products->unit_id == 5){
                        $inquiry_pending += $all_inquiry_products->quantity * $all_inquiry_products->product_sub_category->weight * ($all_inquiry_products->length/305);
                    }

                // if ($inquiry->inquiry_status == 'pending' && $inquiry->is_approved == 'yes') {
                //     if ($all_inquiry_products->unit_id == 1)
                    //     $inquiry_pending_sum += $all_inquiry_products->quantity;
                    // elseif (($all_inquiry_products->unit_id == 2) || ($all_inquiry_products->unit_id == 3))

                    // $inquiry_pending_sum += $this->checkpending_quantity($all_inquiry_products->unit_id, $all_inquiry_products->product_category_id, $all_inquiry_products->quantity);
                        // $inquiry_pending_sum += $this->checkpending_quantity($all_inquiry_products->unit_id, $all_inquiry_products->product_category_id, $all_inquiry_products->quantity, $all_inquiry_products->product_sub_category, $all_inquiry_products->length);
                        
                }
                $inquiry_pending_sum += round($inquiry_pending,2);
            }
        }
        $inquiry_pending_sum = $inquiry_pending_sum / 1000;


        $delivery_order = DeliveryOrder::where('order_status', 'pending')->with('delivery_product')->get();
        $deliver_sum = 0;
        $deliver_pending_sum = 0;

        foreach ($delivery_order as $delivery_order_info) {
            if ($delivery_order_info->order_status == 'pending') {
                $deliver_pending = 0;
                foreach ($delivery_order_info->delivery_product as $delivery_order_productinfo) {
                    if(isset($delivery_order_productinfo->actual_pieces) && !empty($delivery_order_productinfo->actual_pieces) && isset($delivery_order_productinfo->actual_quantity) && !empty($delivery_order_productinfo->actual_quantity)){
                        $deliver_pending += $delivery_order_productinfo->quantity;
                    }
                    else{
                        if ($delivery_order_productinfo->unit_id == 1){
                            $deliver_pending += $delivery_order_productinfo->quantity;
                        } elseif ($delivery_order_productinfo->unit_id == 2){
                            $deliver_pending += $delivery_order_productinfo->quantity * $delivery_order_productinfo->product_sub_category->weight;
                        } elseif ($delivery_order_productinfo->unit_id == 3){
                            $deliver_pending += ($delivery_order_productinfo->quantity / $delivery_order_productinfo->product_sub_category->standard_length) * $delivery_order_productinfo->product_sub_category->weight;
                        } elseif ($delivery_order_productinfo->unit_id == 4){
                            $deliver_pending += $delivery_order_productinfo->quantity * $delivery_order_productinfo->product_sub_category->weight * $delivery_order_productinfo->length;
                        } elseif ($delivery_order_productinfo->unit_id == 5){
                            $deliver_pending += $delivery_order_productinfo->quantity * $delivery_order_productinfo->product_sub_category->weight * ($delivery_order_productinfo->length/305);
                        }
                    }

                //     if ($delivery_order_productinfo->unit_id == 1)
                //         $deliver_pending_sum += $delivery_order_productinfo->quantity;
                //     elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))

                //     // $deliver_pending_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
                //         $deliver_pending_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity, $delivery_order_productinfo->product_sub_category, $delivery_order_productinfo->length);
                }
                $deliver_pending_sum += round($deliver_pending,2);
            }
        }
        $deliver_sum = $deliver_sum / 1000;
        $deliver_pending_sum = $deliver_pending_sum / 1000;

        return view('dashboard', compact('order_pending_sum', 'inquiry_pending_sum', 'deliver_pending_sum'));

//        return view('dashboard', compact('order', 'pending_order','order_pending_sum', 'inquiry', 'pending_inquiry', 'inquiry_pending_sum', 'deliver_sum', 'deliver_pending_sum', 'delivery_challan_sum', 'purc_order_sum'));
    }

    function checkpending_quantity($unit_id, $product_category_id, $product_qty, $prod_info = false, $product_length = false) {

        $kg_qty = 0;
        if ($prod_info && count((array)(array)$prod_info)) {
            $product_info = $prod_info;
        } else {
            $product_info = ProductSubCategory::find($product_category_id);
        }

        if ($unit_id == 1) {
            if (isset($product_info->quantity)) {
                $kg_qty = $product_info->quantity;
            } else {
                $kg_qty = 0;
            }
        } elseif ($unit_id == 2) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
                $product_qty = $product_info->quantity;
            } else {
                $weight = 0;
            }
            $kg_qty = $kg_qty + ($product_qty * $weight);
        } elseif ($unit_id == 3) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
                $product_qty = $product_info->quantity;
            } else {
                $weight = 1;
            }
            $std_length = 1;
            if (isset($product_info->standard_length) && $product_info->standard_length <> 0) {
                $std_length = $product_info->standard_length;
            }

            $kg_qty = $kg_qty + (($product_qty / $std_length ) * $weight);
        } elseif ($unit_id == 4) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
                $product_qty = $product_info->quantity;
            } else {
                $weight = 1;
            }
            $kg_qty = $kg_qty + $product_qty * $weight * $product_length;
        } elseif ($unit_id == 5) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
                $product_qty = $product_info->quantity;
            } else {
                $weight = 1;
            }
            $kg_qty = $kg_qty + $product_qty * $weight * ($product_length/305);
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

    public function homeredirect(Request $request) {
        if ($request->user()->hasOldPassword()) {
            return redirect('change_password');
        }
        return redirect('dashboard');
    }

    /* To get Inquiry stats for graph */

    public function graph_inquiry() {


        $date = new Carbon\Carbon;
        $date_search = $date->subDays(7);
        $orders_stats_all;
        $inquiries_stats = Inquiry::with('inquiry_products.inquiry_product_details')
                ->where('inquiry_status', '=', 'completed')
                ->where('updated_at', '>', $date_search)
                ->get();

        for ($i = 1; $i <= 7; $i++) {
            $inquiries_stats_all[$i]['pipe'] = 0;
            $inquiries_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $inquiries_stats_all[$i]['day'] = $date_search;


            foreach ($inquiries_stats as $inquiry) {
                if (date('Y-m-d', strtotime($inquiry->updated_at)) == $date_search) {
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
            }

            $inquiries_stats_all[$i]['pipe'] = round($inquiries_stats_all[$i]['pipe'] / 1000, 2);
            $inquiries_stats_all[$i]['structure'] = round($inquiries_stats_all[$i]['structure'] / 1000, 2);
        }


        return ($inquiries_stats_all);
    }

//    public function graph_order() {
//
//        /* To get Order stats for graph */
//        for ($i = 1; $i <= 7; $i++) {
//            $orders_stats_all[$i]['pipe'] = 0;
//            $orders_stats_all[$i]['structure'] = 0;
//            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
//            $orders_stats_all[$i]['day'] = $date_search;
//            $orders_stats = Order::with('all_order_products')
//                    ->where('order_status', '=', 'completed')
//                    ->where('updated_at', 'like', $date_search . '%')
//                    ->get();
//            if (count((array)$orders_stats) > 0) {
//                foreach ($orders_stats as $order) {
//                    foreach ($order['all_order_products'] as $order_products) {
//                        if (isset($order_products['order_product_details']['product_category']['product_type_id'])) {
//                            if ($order_products['order_product_details']['product_category']['product_type_id'] == 1) {
//                                if ($order_products['unit_id'] == 1) {
//                                    $orders_stats_all[$i]['pipe'] += $order_products['quantity'];
//                                } elseif (($order_products['unit_id'] == 2)) {
//                                    $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] * $order_products['order_product_details']['weight']);
//                                } elseif (($order_products['unit_id'] == 3)) {
//                                    $standard_length = $order_products['order_product_details']['standard_length'];
//                                    if ($order_products['order_product_details']['standard_length'] == 0) {
//                                        $standard_length = 1;
//                                    }
//                                    $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] / $standard_length * $order_products['order_product_details']['weight']);
//                                }
////                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
////                                $orders_stats_all[$i]['pipe'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
//                            } else {
//                                if ($order_products['unit_id'] == 1) {
//                                    $orders_stats_all[$i]['structure'] += $order_products['quantity'];
//                                } elseif (($order_products['unit_id'] == 2)) {
//                                    $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] * $order_products['order_product_details']['weight']);
//                                } elseif (($order_products['unit_id'] == 3)) {
//                                    $standard_length = $order_products['order_product_details']['standard_length'];
//                                    if ($order_products['order_product_details']['standard_length'] == 0) {
//                                        $standard_length = 1;
//                                    }
//                                    $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] / $standard_length * $order_products['order_product_details']['weight']);
//                                }
////                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
////                                $orders_stats_all[$i]['structure'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
//                            }
//                        }
//                    }
//                }
//            }
//
//            $orders_stats_all[$i]['pipe'] = round($orders_stats_all[$i]['pipe'] / 1000, 2);
//            $orders_stats_all[$i]['structure'] = round($orders_stats_all[$i]['structure'] / 1000, 2);
//        }
//
//
//        return ($orders_stats_all);
//    }


    /* To get Order stats for graph */
    public function graph_order() {
        $date = new Carbon\Carbon;
        $date_search = $date->subDays(7);
        $orders_stats_all;

        $orders_stats = Order::with('aopwpsc', 'aopwpsc.order_product_details.product_category')->where('order_status', '=', 'completed')
                ->where('updated_at', '>', $date_search)
                ->orderBy('updated_at')
                ->get();

        for ($i = 1; $i <= 7; $i++) {
            $orders_stats_all[$i]['pipe'] = 0;
            $orders_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $orders_stats_all[$i]['day'] = $date_search;

            if (count((array)$orders_stats) > 0) {
                foreach ($orders_stats as $order) {
                    if (date('Y-m-d', strtotime($order->updated_at)) == $date_search) {
                        foreach ($order['aopwpsc'] as $order_products) {

                            if (isset($order_products['order_product_details']['product_category']['product_type_id'])) {
                                if ($order_products['order_product_details']['product_category']['product_type_id'] == 1) {
                                    if ($order_products['unit_id'] == 1) {
                                        $orders_stats_all[$i]['pipe'] += $order_products['quantity'];
                                    } elseif (($order_products['unit_id'] == 2)) {
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] * $order_products['order_product_details']['weight']);
                                    } elseif (($order_products['unit_id'] == 3)) {
                                        $standard_length = $order_products['order_product_details']['standard_length'];
                                        if ($order_products['order_product_details']['standard_length'] == 0) {
                                            $standard_length = 1;
                                        }
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] / $standard_length * $order_products['order_product_details']['weight']);
                                    }
//                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
//                                $orders_stats_all[$i]['pipe'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
                                } else {
                                    if ($order_products['unit_id'] == 1) {
                                        $orders_stats_all[$i]['structure'] += $order_products['quantity'];
                                    } elseif (($order_products['unit_id'] == 2)) {
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] * $order_products['order_product_details']['weight']);
                                    } elseif (($order_products['unit_id'] == 3)) {
                                        $standard_length = $order_products['order_product_details']['standard_length'];
                                        if ($order_products['order_product_details']['standard_length'] == 0) {
                                            $standard_length = 1;
                                        }
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] / $standard_length * $order_products['order_product_details']['weight']);
                                    }
//                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
//                                $orders_stats_all[$i]['structure'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
                                }
                            }
                        }
                    }
                }
            }

            $orders_stats_all[$i]['pipe'] = round($orders_stats_all[$i]['pipe'] / 1000, 2);
            $orders_stats_all[$i]['structure'] = round($orders_stats_all[$i]['structure'] / 1000, 2);
        }


        return ($orders_stats_all);
    }

    /* To get Delivery Challan stats for graph */

    public function graph_delivery_challan() {


        $date = new Carbon\Carbon;
        $date_search = $date->subDays(7);
        $orders_stats_all;
        $delivery_challan_stats = DeliveryChallan::with('delivery_challan_products', 'delivery_challan_products.order_product_details.product_category')
                ->where('challan_status', '=', 'completed')
                ->where('updated_at', '>', $date_search)
                ->get();

        for ($i = 1; $i <= 7; $i++) {
            $delivery_challan_stats_all[$i]['pipe'] = 0;
            $delivery_challan_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $delivery_challan_stats_all[$i]['day'] = $date_search;


            foreach ($delivery_challan_stats as $delivery_challan) {
                if (date('Y-m-d', strtotime($delivery_challan->updated_at)) == $date_search) {
                    foreach ($delivery_challan['delivery_challan_products'] as $delivery_challan_products) {

                        if (isset($delivery_challan_products['order_product_details']['product_category']['product_type_id'])) {
                            if ($delivery_challan_products['order_product_details']['product_category']['product_type_id'] == 1) {
                                $delivery_challan_stats_all[$i]['pipe'] += $delivery_challan_products['actual_quantity'];
//                            if ($delivery_challan_products['unit_id'] == 1) {
//                                $delivery_challan_stats_all[$i]['pipe'] += $delivery_challan_products['quantity'];
//                            } elseif (($delivery_challan_products['unit_id'] == 2)) {
//                                $delivery_challan_stats_all[$i]['pipe'] += ($delivery_challan_products['quantity'] * $delivery_challan_products['order_product_details']['weight']);
//                            } elseif (($delivery_challan_products['unit_id'] == 3)) {
//                                $standard_length = $delivery_challan_products['order_product_details']['standard_length'];
//                                if ($delivery_challan_products['order_product_details']['standard_length'] == 0) {
//                                    $standard_length = 1;
//                                }
//                                $delivery_challan_stats_all[$i]['pipe'] += ($delivery_challan_products['quantity'] / $standard_length * $delivery_challan_products['order_product_details']['weight']);
//                            }
//                            elseif (($delivery_challan_products['unit_id'] == 2) || ($delivery_challan_products['unit_id'] == 3))
//                                $delivery_challan_stats_all[$i]['pipe'] += $this->checkpending_quantity($delivery_challan_products['unit_id'], $delivery_challan_products['product_category_id'], $delivery_challan_products['quantity']);
                            } else {
                                $delivery_challan_stats_all[$i]['structure'] += $delivery_challan_products['actual_quantity'];
//                            if ($delivery_challan_products['unit_id'] == 1){
//                                $delivery_challan_stats_all[$i]['structure'] += $delivery_challan_products['quantity'];
//                            }elseif (($delivery_challan_products['unit_id'] == 2)) {
//
//
//                                $delivery_challan_stats_all[$i]['structure'] += ($delivery_challan_products['quantity'] * $delivery_challan_products['order_product_details']['weight']);
//                            } elseif (($delivery_challan_products['unit_id'] == 3)) {
//                                $standard_length = $delivery_challan_products['order_product_details']['standard_length'];
//                                if ($delivery_challan_products['order_product_details']['standard_length'] == 0) {
//                                    $standard_length = 1;
//                                }
//                                $delivery_challan_stats_all[$i]['structure'] += ($delivery_challan_products['quantity'] / $standard_length * $delivery_challan_products['order_product_details']['weight']);
//                            }
//                            elseif (($delivery_challan_products['unit_id'] == 2) || ($delivery_challan_products['unit_id'] == 3))
//                                $delivery_challan_stats_all[$i]['structure'] += $this->checkpending_quantity($delivery_challan_products['unit_id'], $delivery_challan_products['product_category_id'], $delivery_challan_products['quantity']);
                            }
                        }
                    }
                }
            }

            $delivery_challan_stats_all[$i]['pipe'] = round($delivery_challan_stats_all[$i]['pipe'] / 1000, 2);
            $delivery_challan_stats_all[$i]['structure'] = round($delivery_challan_stats_all[$i]['structure'] / 1000, 2);
        }
        return ($delivery_challan_stats_all);
    }

//     public function graph_delivery_challan() {
//
//        /* To get Delivery Challan stats for graph */
//
//
//        for ($i = 1; $i <= 7; $i++) {
//            $delivery_challan_stats_all[$i]['pipe'] = 0;
//            $delivery_challan_stats_all[$i]['structure'] = 0;
//            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
//            $delivery_challan_stats_all[$i]['day'] = $date_search;
//            $delivery_challan_stats = DeliveryChallan::with('delivery_challan_products')
//                    ->where('challan_status', '=', 'completed')
//                    ->where('updated_at', 'like', $date_search . '%')
//                    ->get();
//
//            foreach ($delivery_challan_stats as $delivery_challan) {
//                foreach ($delivery_challan['delivery_challan_products'] as $delivery_challan_products) {
//
//                    if (isset($delivery_challan_products['order_product_details']['product_category']['product_type_id'])) {
//                        if ($delivery_challan_products['order_product_details']['product_category']['product_type_id'] == 1) {
//
//                            if ($delivery_challan_products['unit_id'] == 1) {
//                                $delivery_challan_stats_all[$i]['pipe'] += $delivery_challan_products['quantity'];
//                            } elseif (($delivery_challan_products['unit_id'] == 2)) {
//                                $delivery_challan_stats_all[$i]['pipe'] += ($delivery_challan_products['quantity'] * $delivery_challan_products['order_product_details']['weight']);
//                            } elseif (($delivery_challan_products['unit_id'] == 3)) {
//                                $standard_length = $delivery_challan_products['order_product_details']['standard_length'];
//                                if ($delivery_challan_products['order_product_details']['standard_length'] == 0) {
//                                    $standard_length = 1;
//                                }
//                                $delivery_challan_stats_all[$i]['pipe'] += ($delivery_challan_products['quantity'] / $standard_length * $delivery_challan_products['order_product_details']['weight']);
//                            }
////                            elseif (($delivery_challan_products['unit_id'] == 2) || ($delivery_challan_products['unit_id'] == 3))
////                                $delivery_challan_stats_all[$i]['pipe'] += $this->checkpending_quantity($delivery_challan_products['unit_id'], $delivery_challan_products['product_category_id'], $delivery_challan_products['quantity']);
//                        } else {
//
//                            if ($delivery_challan_products['unit_id'] == 1){
//                                $delivery_challan_stats_all[$i]['structure'] += $delivery_challan_products['quantity'];
//                            }elseif (($delivery_challan_products['unit_id'] == 2)) {
//
//
//                                $delivery_challan_stats_all[$i]['structure'] += ($delivery_challan_products['quantity'] * $delivery_challan_products['order_product_details']['weight']);
//                            } elseif (($delivery_challan_products['unit_id'] == 3)) {
//                                $standard_length = $delivery_challan_products['order_product_details']['standard_length'];
//                                if ($delivery_challan_products['order_product_details']['standard_length'] == 0) {
//                                    $standard_length = 1;
//                                }
//                                $delivery_challan_stats_all[$i]['structure'] += ($delivery_challan_products['quantity'] / $standard_length * $delivery_challan_products['order_product_details']['weight']);
//                            }
////                            elseif (($delivery_challan_products['unit_id'] == 2) || ($delivery_challan_products['unit_id'] == 3))
////                                $delivery_challan_stats_all[$i]['structure'] += $this->checkpending_quantity($delivery_challan_products['unit_id'], $delivery_challan_products['product_category_id'], $delivery_challan_products['quantity']);
//                        }
//                    }
//                }
//            }
//
//            $delivery_challan_stats_all[$i]['pipe'] = round($delivery_challan_stats_all[$i]['pipe'] / 1000, 2);
//            $delivery_challan_stats_all[$i]['structure'] = round($delivery_challan_stats_all[$i]['structure'] / 1000, 2);
//        }
//        return ($delivery_challan_stats_all);
//    }
//
//
//    public function graph_order_temp() {
//
//        $date = new Carbon\Carbon;
//        $date_search = $date->subDays(6);
//        $orders_stats_all;
//
//        $orders_stats = Order::where('order_status', '=', 'completed')
//                ->where('updated_at', '>', $date_search)
//                ->orderBy('updated_at')
//                ->get();
//
//
//        $list_with_ids = [];
//        $list_with_date_and_ids = [];
//        foreach ($orders_stats as $orders_stat) {
//            $list_with_ids[] = $orders_stat->id;
//            $list_with_date_and_ids[$orders_stat->id] = date_format(date_create($orders_stat->updated_at), "Y-m-d");
//        }
//
////        $product_list = \App\AllOrderProducts::with('order_product_details')
////                        ->whereIn('order_id', $list_with_ids)
////                        ->where('order_type', 'order')->get();
//
//        set_time_limit(0);
//        $product_list = AllOrderProducts::with('order_product_details')
//                ->whereIn('order_id', $list_with_ids)
//                ->where('order_type', 'delivery_order')
//                ->get();
//
//
//        echo "<pre>";
//        print_r($product_list);
//        echo "</pre>";
//        exit;
//
//        $i = 1;
//        $list[$i]['pipe'] = 0;
//        $list[$i]['structure'] = 0;
//        $list[$i]['day'] = "";
//        $list = [];
//
//        foreach ($product_list as $order_product) {
//            $list[$i]['day'] = $list_with_date_and_ids[$order_product->order_id];
//            if (isset($order_product['order_product_details']['product_category']->product_type_id)) {
//                if ($order_product['order_product_details']['product_category']->product_type_id == 1) {
//                    if ($order_product['unit_id'] == 1)
//                        $list[$i]['pipe'] = $order_product['quantity'];
//                    elseif (($order_product['unit_id'] == 2) || ($order_product['unit_id'] == 3)) {
//                        $list[$i]['pipe'] = $this->checkpending_quantity($order_product['unit_id'], $order_product['product_category_id'], $order_product['quantity']);
//                    }
//                    $list[$i]['structure'] = 0;
//                } else {
//                    $list[$i]['pipe'] = 0;
//                    if ($order_product['unit_id'] == 1)
//                        $list[$i]['structure'] = $order_product['quantity'];
//                    elseif (($order_product['unit_id'] == 2) || ($order_product['unit_id'] == 3)) {
//                        $list[$i]['structure'] = $this->checkpending_quantity($order_product['unit_id'], $order_product['product_category_id'], $order_product['quantity']);
//                    }
//                }
//            }
//            $i++;
//        }
//
//        $date_search = [];
//        for ($i = 7; $i >= 1; $i--) {
//            $date_search[] = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
//        }
//
//        $j = 1;
//        $orders_stats_temp[$j]['pipe'] = 0;
//        $orders_stats_temp[$j]['structure'] = 0;
//        $orders_stats_temp[$j]['day'] = "";
//        $orders_stats_temp[0]['day'] = "";
//        foreach ($list as $k => $subArray) {
//            $pipe = 0;
//            $structure = 0;
//            if ($orders_stats_temp[$j - 1]['day'] <> $subArray['day']) {
//                $orders_stats_temp[$j]['day'] = $subArray['day'];
//                foreach ($date_search as $key => $temp) {
//                    if ($temp == $subArray['day']) {
//                        unset($date_search[$key]);
//                    }
//                }
//                foreach ($list as $k1 => $subArray_1) {
//                    if ($subArray['day'] == $subArray_1['day']) {
//                        $pipe += $subArray_1['pipe'];
//                        $structure += $subArray_1['structure'];
//                    }
//                }
//                $orders_stats_temp[$j]['pipe'] = round($pipe / 1000, 2);
//                $orders_stats_temp[$j]['structure'] = round($structure / 1000, 2);
//                $j++;
//            }
//        }
//
//        if (count((array)$date_search)) {
//            $k = count((array)$orders_stats_temp) + 1;
//            foreach ($date_search as $key => $temp) {
//                $orders_stats_temp[$k]['day'] = $temp;
//                $orders_stats_temp[$k]['pipe'] = 0;
//                $orders_stats_temp[$k]['structure'] = 0;
//                $k++;
//            }
//        }
//
//        unset($orders_stats_temp[1]);
//        $orders_stats_temp = array_values($orders_stats_temp);
//        unset($orders_stats_temp[0]);
//
//
//
//        return ($orders_stats_temp);
//        exit;
//    }
}
