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

class DashboardController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
    }

    /*
      | Will get counts of total Orders and Pending Delivery Orders.
      | Counts of All purchases. Pending or Inprocess.
      | Counts of total customer in the system
      | Counts of total Inquiries
     */

    public function index() {  
      
        if (Auth::user()->role_id == 5 ) {            
           return Redirect::to('inquiry');           
        }
        
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 2) {
            return Redirect::to('customers');
        }
        $order = Order::all()->count();
        $orders = Order::with('all_order_products')->get();
        $order_pending_sum =0;
        $pending_order = Order::where('order_status', 'pending')->count();
        
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
        $inquiry_pending_sum =0;
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
        
        $delivery_order = DeliveryOrder::with('delivery_product')->get();
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
            foreach ($delivery_order_info->delivery_product as $delivery_order_productinfo) {
                if ($delivery_order_productinfo->unit_id == 1)
                    $deliver_sum += $delivery_order_productinfo->quantity;
                elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
                    $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
            }
        }
        $deliver_sum = $deliver_sum / 1000;
        $deliver_pending_sum = $deliver_pending_sum / 1000;
        $delivery_challan = DeliveryChallan::with('delivery_challan_products')->get();
        $delivery_challan_sum = 0;
       
//        foreach ($pur_challan as $qty) {
//            foreach ($qty['delivery_challan_products'] as $qty_val) {
//                $challan_sum += $qty_val->quantity;
//            }
//        }
        foreach ($delivery_challan as $delivery_challan_info) {
            foreach ($delivery_challan_info->delivery_challan_products as $delivery_challan_productinfo) {
//                if ($delivery_challan_productinfo->unit_id == 1)
//                    $delivery_challan_sum += $delivery_challan_productinfo->quantity;
//                else
//                    $delivery_challan_sum += $this->checkpending_quantity($delivery_challan_productinfo->unit_id, $delivery_challan_productinfo->product_category_id, $delivery_challan_productinfo->quantity);
               
               
                if($delivery_challan_info->challan_status == 'completed'){
                    $delivery_challan_sum =  $delivery_challan_sum + $delivery_challan_productinfo->actual_quantity;
                }
            }
        }
        $delivery_challan_sum = $delivery_challan_sum / 1000;
        $purc_order_sum = 0;
        $purchase_order = PurchaseOrder::with('purchase_products')->get();
//        foreach ($pur_challan as $qty) {
//            foreach ($qty['purchase_products'] as $qty_val) {
//                $purc_order_sum += $qty_val->quantity;
//            }
//        }
        foreach ($purchase_order as $purchase_order_info) {
            foreach ($purchase_order_info->purchase_products as $purchase_order_productinfo) {
                if ($purchase_order_productinfo->unit_id == 1)
                    $purc_order_sum += $purchase_order_productinfo->quantity;
                else
                    $purc_order_sum += $this->checkpending_quantity($purchase_order_productinfo->unit_id, $purchase_order_productinfo->product_category_id, $purchase_order_productinfo->quantity);
            }
        }
        $purc_order_sum = $purc_order_sum / 1000;
        return view('dashboard', compact('order', 'pending_order','order_pending_sum', 'inquiry', 'pending_inquiry', 'inquiry_pending_sum', 'deliver_sum', 'deliver_pending_sum', 'delivery_challan_sum', 'purc_order_sum'));
    }

    function checkpending_quantity($unit_id, $product_category_id, $product_qty) {

        $kg_qty = 0;
        $product_info = ProductSubCategory::find($product_category_id);
        if ($unit_id == 1) {
            if(isset($product_info->quantity)){
                 $kg_qty = $product_info->quantity;
            }else{
                $kg_qty = 0;
            }
           
        } elseif ($unit_id == 2) {
            if(isset($product_info->weight)){
                    $weight = $product_info->weight;
            }else{
                 $weight =0;
            }
            $kg_qty = $kg_qty + ($product_qty * $weight);
        } elseif ($unit_id == 3) {
            if(!isset($weight) )
            {
                $weight=1;
            }
            if(isset($product_info->standard_length)){
                $std_length = $product_info->standard_length;
            }else{
                $std_length =  0;
            }
            $kg_qty = $kg_qty + (($product_qty /  $std_length ) * $weight);
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

}
