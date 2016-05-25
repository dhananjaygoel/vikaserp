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
    /*
      | Will get counts of total Orders and Pending Delivery Orders.
      | Counts of All purchases. Pending or Inprocess.
      | Counts of total customer in the system
      | Counts of total Inquiries
     */

    public function index() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 2) {
            return Redirect::to('customers');
        }

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
        $deliver_sum = $deliver_sum / 100;
        $deliver_pending_sum = $deliver_pending_sum / 100;

        $pur_challan = DeliveryChallan::with('delivery_challan_products')->get();
        $challan_sum = 0;
        foreach ($pur_challan as $qty) {
            foreach ($qty['delivery_challan_products'] as $qty_val) {
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
