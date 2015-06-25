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
use App\PurchaseChallan;
use App\PurchaseOrder;

class DashboardController extends Controller {

    public function index() {

        $order = Order::all()->count();
        $pending_order = Order::where('order_status', 'pending')->count();
        $inquiry = Inquiry::all()->count();
        $pending_inquiry = Inquiry::where('inquiry_status', 'pending')->count();


        $delivery_order = DeliveryOrder::with('delivery_product')->get();

        $deliver_sum = 0;
        $deliver_pending_sum = 0;
        foreach ($delivery_order as $qty) {
            if ($qty->order_status == 'pending') {
                foreach ($qty['delivery_product'] as $qty_val) {
                    $deliver_pending_sum += $qty_val->quantity;
                }
            } else if ($qty->order_status == 'completed') {
                foreach ($qty['delivery_product'] as $qty_val) {
                    $deliver_sum += $qty_val->quantity;
                }
            }
        }

//       $a = $product_sub_cat = DeliveryOrder::with(['delivery_product' =>
//                    function($query) {
//                        $query->sum('quantity');
//                    }])
//                ->get();
//        echo '<pre>';
//        print_r($a->toArray());
//        echo '</pre>';
//
//        exit;



        $deliver_sum = $deliver_sum / 100;
        $deliver_pending_sum = $deliver_pending_sum / 100;

        $pur_challan = PurchaseChallan::with('purchase_product')->get();
        $challan_sum = 0;
        foreach ($pur_challan as $qty) {

            foreach ($qty['purchase_product'] as $qty_val) {
                $challan_sum += $qty_val->quantity;
            }
        }

        $challan_sum = $challan_sum / 100;

        $purc_order_sum = 0;
        $pur_challan = PurchaseOrder::with('purchase_products')->get();
        foreach ($pur_challan as $qty) {

            foreach ($qty['purchase_products'] as $qty_val) {
                $purc_order_sum += $qty_val->quantity;
            }
        }

        $purc_order_sum = $purc_order_sum / 100;

        return view('dashboard', compact('order', 'pending_order', 'inquiry', 'pending_inquiry', 'deliver_sum', 'deliver_pending_sum', 'challan_sum', 'purc_order_sum'));
    }

    public function logout() {

        Auth::logout(); // logout user
        return redirect(\URL::previous());
    }

    public function homeredirect() {

        return redirect('dashboard');
    }

}
