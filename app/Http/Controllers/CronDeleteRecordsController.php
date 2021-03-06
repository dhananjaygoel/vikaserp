<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inquiry;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;

class CronDeleteRecordsController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
    }

    /**
     * Delete data which is 7 days old.
     */
    public function index() {

//        Units::create(['unit_name' => 'tons']);
//        exit();
        $date = date('Y-m-d H:i:s', strtotime('-7 days'));

        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED INQUIRY 7 DAYS OLD
          | --------------------------------------------------------------------
         */
        $inquiry = Inquiry::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('inquiry_status', '=', 'completed')
                    ->orWhere('inquiry_status', '=', 'canceled');
                })
                ->get();

        if (count((array)$inquiry) > 0) {
            foreach ($inquiry as $key => $value) {
                $inquiry[$key]->delete();
            }
        }


        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED ORDER 7 DAYS OLD
          | --------------------------------------------------------------------
         */
        $order = Order::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('order_status', '=', 'completed')
                    ->orWhere('order_status', '=', 'cancelled');
                })
                ->get();

        if (count((array)$order) > 0) {
            foreach ($order as $key => $value) {
//                $check_if_delivery_order_exists = DeliveryOrder::where('order_id', '=', $order[$key])->get();
//                $check_if_delivery_challan_exists = DeliveryChallan::where('order_id', '=', $order[$key])->get();
//                if ((count((array)$check_if_delivery_order_exists) == 0) || (count((array)$check_if_delivery_challan_exists) == 0)) {
                $order[$key]->delete();
//                }
            }
        }

        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED DELIVERY ORDER 7 DAYS OLD
          | --------------------------------------------------------------------
         */

        $delivery_order = DeliveryOrder::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('order_status', '=', 'completed')
                    ->orWhere('order_status', '=', 'cancelled');
                })
                ->get();

        if (count((array)$delivery_order) > 0) {
            foreach ($delivery_order as $key => $value) {
//                $check_if_delivery_challan_exists = DeliveryChallan::where('delivery_order_id', '=', $delivery_order[$key])->get();
//                if ((count((array)$check_if_delivery_challan_exists) == 0)) {
                $delivery_order[$key]->delete();
//                }
            }
        }

        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED DELIVERY CHALLAN 7 DAYS OLD
          | --------------------------------------------------------------------
         */

        $delivery_challan = DeliveryChallan::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('challan_status', '=', 'completed');
                })
                ->get();

        if (count((array)$delivery_challan) > 0) {
            foreach ($delivery_challan as $key => $value) {
                $delivery_challan[$key]->delete();
            }
        }

        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED PURCHASE ORDER 7 DAYS OLD
          | --------------------------------------------------------------------
         */

        $purchase_order = PurchaseOrder::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('order_status', '=', 'completed')
                    ->orWhere('order_status', '=', 'canceled');
                })
                ->get();

        if (count((array)$purchase_order) > 0) {
            foreach ($purchase_order as $key => $value) {
//                $check_if_purchase_advise_exists = PurchaseAdvise::where('purchase_order_id', '=', $purchase_order[$key])->get();
//                $check_if_purchase_challan_exists = PurchaseChallan::where('purchase_order_id', '=', $purchase_order[$key])->get();
//                if ((count((array)$check_if_purchase_advise_exists) == 0) || (count((array)$check_if_purchase_challan_exists) == 0)) {
                $purchase_order[$key]->delete();
//                }
            }
        }

        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED PURCHASE ADVISE 7 DAYS OLD
          | --------------------------------------------------------------------
         */

        $purchase_advise = PurchaseAdvise::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('advice_status', '=', 'delivered');
                })
                ->get();

        if (count((array)$purchase_advise) > 0) {
            foreach ($purchase_advise as $key => $value) {
//                $check_if_purchase_challan_exists = PurchaseChallan::where('purchase_order_id', '=', $purchase_advise[$key])->get();
//                if (count((array)$check_if_purchase_challan_exists) == 0) {
                $purchase_advise[$key]->delete();
//                }
            }
        }

        /*
          | --------------------------------------------------------------------
          | DELETE ALL COMPLETED PURCHASE CHALLAN 7 DAYS OLD
          | --------------------------------------------------------------------
         */

        $purchase_challan = PurchaseChallan::where('updated_at', '<', $date)
                ->where(function($query) {
                    $query->where('order_status', '=', 'completed');
                })
                ->get();

        if (count((array)$purchase_challan) > 0) {
            foreach ($purchase_challan as $key => $value) {
                $purchase_challan[$key]->delete();
            }
        }

        echo 'success';
    }

}
