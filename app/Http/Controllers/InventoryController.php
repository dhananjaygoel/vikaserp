<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductSubCategory;
use App\Inventory;
use App\PurchaseChallan;
use App\PurchaseAdvise;
use App\DeliveryOrder;
use App\PurchaseOrder;
use App\DeliveryChallan;
use App\Order;
use App\AllOrderProducts;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Input;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;
use App\ProductCategory;
use Response;
use App\Repositories\DropboxStorageRepository;
use Auth;
use DB;
use Illuminate\Support\Facades\Mail;
use Config;
use Carbon\Carbon;

class InventoryController extends Controller {

    /**
     * Product search in inventory module
     */
    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
    }

    public function fetchInventoryProductName() {

        $term = '%' . Input::get('term') . '%';
        $product = ProductSubCategory::where('alias_name', 'like', $term)->get();
        if (count($product) > 0) {
            foreach ($product as $prod) {
                $data_array[] = [ 'value' => $prod->alias_name];
            }
        } else {
            $data_array[] = [ 'value' => 'No Product found'];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    /**
     * Update Product's opening stock from inventory
     */
    public function update_inventory() {

        $qty = Input::get('opening_stock');
        $minimal = Input::get('minimal');
//        $inventory_details = Inventory::find(Input::get('id'));
        $inventory_details = Inventory::where('product_sub_category_id', '=', Input::get('id'))->first();
        if (!empty($inventory_details)) {
            $inventory_details->opening_qty = $qty;
            $inventory_details->minimal = $minimal;
            $physical_qty = ($qty + $inventory_details->purchase_challan_qty) - $inventory_details->sales_challan_qty;
            $inventory_details->physical_closing_qty = $physical_qty;
            $virtual_qty = ($inventory_details->physical_closing_qty + $inventory_details->pending_purchase_order_qty + $inventory_details->pending_purchase_advise_qty) - ($inventory_details->pending_sales_order_qty + $inventory_details->pending_delivery_order_qty);
            //$inventory_details->virtual_qty = $virtual_qty;
            $inventory_details->save();
            $total = ($inventory_details->physical_closing_qty + $inventory_details->pending_purchase_advise_qty) - ($inventory_details->pending_sales_order_qty + $inventory_details->pending_delivery_order_qty);
            $inventory_details['class'] = ($total < $inventory_details->minimal) ? 'yes' : 'no';
        }

        return json_encode($inventory_details);
    }

    /**
     * Display a all product inventory with stock details
     */
    public function index() {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }


        if (Input::has('export_data') && Input::get('export_data') == 'Export Inventory List') {
            $this->export_inventory(Input::get());
        }

//        $this->updateOpeningStock();
        

        $query = Inventory::query();
        if (Input::has('inventory_filter') && Input::get('inventory_filter') == 'minimal') {

//                $query->where('minimal','<','physical_closing_qty'-'pending_delivery_order_qty'-'pending_sales_order_qty'+'pending_purchase_advise_qty');
            $query->whereRaw('minimal < physical_closing_qty-pending_delivery_order_qty-pending_sales_order_qty+pending_purchase_advise_qty');
        }
        if (Input::has('product_category_filter') && Input::get('product_category_filter') != '') {
            $categoryid = Input::get('product_category_filter');
            $query->whereHas('product_sub_category', function($q) use($categoryid) {
                $q->where('product_category_id', '=', $categoryid);
            });
        }

        if (Input::has('search_inventory') && Input::get('search_inventory') != '') {
            $alias_name = '%' . Input::get('search_inventory') . '%';
            $product_sub_id = ProductSubCategory::where('alias_name', 'LIKE', $alias_name)->first();
            if (count($product_sub_id)) {
                $query->where('product_sub_category_id', '=', $product_sub_id->id);
            }
        }

        $product_category = ProductCategory::orderBy('created_at', 'desc')->get();
        //$inventory_newlist = $query->with('product_sub_category')->paginate(50);
//        $inventory_newlist = $query->with(array('product_sub_category' => function($query1) {
//        $query1->orderBy('alias_name', 'ASC');
//    }))->paginate(50);

        $inventory_newlist = $query->with('product_sub_category')
                ->join('product_sub_category', 'inventory.product_sub_category_id', '=', 'product_sub_category.id')
                ->orderBy('product_sub_category.alias_name', 'ASC')
                ->paginate(50);
        $inventory_newlist->setPath('inventory');
        if (count($inventory_newlist)) {

            $virtual_stock_qty = array();
            foreach ($inventory_newlist as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_sub_category_id;
                $virtual_qty = ($product_categoriy->physical_closing_qty + $product_categoriy->pending_purchase_order_qty + $product_categoriy->pending_purchase_advise_qty) - ($product_categoriy->pending_sales_order_qty + $product_categoriy->pending_delivery_order_qty);
                array_push($virtual_stock_qty,$virtual_qty);
            }

            $this->inventoryCalc($product_category_ids);
        }

        return view('add_inventory')->with(['inventory_list' => $inventory_newlist,'virtual_qty'=>$virtual_stock_qty, 'product_category' => $product_category]);
    }

    /* find latested updated records */

    public function inventoryCalc($product_category_ids = []) {

        if (empty($product_category_ids)) {
//            $dc_list = AllOrderProducts::orderBy('updated_at', 'DESC')->withTrashed()->take(50)->get();
//            foreach ($dc_list as $dc) {
//                $product_category_ids[] = $dc->product_category_id;
//            }
//
//
//            $dc_list = \App\PurchaseProducts::select('product_category_id')->orderBy('updated_at', 'DESC')->take(50)->get();
//            foreach ($dc_list as $dc) {
//                $product_category_ids[] = $dc->product_category_id;
//            }


            $product_category_ids = array_unique($product_category_ids);
        }
        $q = Inventory::query();
//        $inventory_list = $q->with('product_sub_category')->paginate(50);
        $inventory_list = $q
                        ->whereIn('product_sub_category_id', $product_category_ids)
                        ->with('product_sub_category')->get();

        $orders = Order::where('order_status', '=', 'pending')
                        ->with(['all_order_products.product_sub_category', 'all_order_products' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])->get();


        $delivery_orders = $order_delivery_orders = DeliveryOrder::where('order_status', '=', 'pending')
                        ->with(['delivery_product.product_sub_category', 'delivery_product' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])->get();

//        $delivery_orders = DeliveryOrder::where('order_status', '=', 'pending')
//                        ->with(['delivery_product.product_sub_category', 'delivery_product' => function($q) use($product_category_ids) {
//                                $q->whereIn('product_category_id', $product_category_ids);
//                            }])->get();
        $today = Carbon::now()->toDateString();                            
        $delivery_challan = DeliveryChallan::where('challan_status', '=', 'pending')
                            ->with(['delivery_challan_products.product_sub_category', 'delivery_challan_products' => function($q) use($product_category_ids) {
                                    $q->whereIn('product_category_id', $product_category_ids);
                                }])
                            ->whereRaw('DATE(delivery_challan.created_at) = ?', [$today])
                            ->get();
                                
        $delivery_challan_completed = DeliveryChallan::where('challan_status', '=', 'completed')
                ->whereRaw('Date(updated_at) = CURDATE()')
                        ->with(['delivery_challan_products.product_sub_category', 'delivery_challan_products' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])
                            ->where('challan_status', '=', 'completed')
//                            ->whereRaw("Date(delivery_challan.created_at)","=","CURDATE()")
                            ->whereRaw('DATE(delivery_challan.created_at) = ?', [$today])
                            ->get();
                            
        $purchase_orders = PurchaseOrder::where('order_status', '=', 'pending')
                        ->with(['purchase_products.product_sub_category', 'purchase_products' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])->get();


        $purchase_orders_purchase_advice = $purchase_advice = PurchaseAdvise::where('advice_status', '=', 'in_process')
                        ->with(['purchase_products.product_sub_category', 'purchase_products' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])->get();
        $today = Carbon::now()->toDateString();
        $purchase_challan = PurchaseChallan::where('order_status', '=', 'pending')
                        ->with(['all_purchase_products.product_sub_category', 'all_purchase_products' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])
                            ->whereRaw('DATE(purchase_challan.created_at) = ?', [$today])        
                            ->get();
                            
        $purchase_challan_completed = PurchaseChallan::where('order_status', '=', 'completed')
                ->whereRaw('Date(updated_at) = CURDATE()')
                        ->with(['all_purchase_products.product_sub_category', 'all_purchase_products' => function($q) use($product_category_ids) {
                                $q->whereIn('product_category_id', $product_category_ids);
                            }])
                            ->whereRaw('DATE(purchase_challan.created_at) = ?', [$today])
                            ->get();
        $update_inventory_data = [];
        foreach ($inventory_list as $inventory) {
            $order_qty = 0;
            $sales_challan_qty = 0;
            $purchase_challan_qty = 0;
            $sales_challan_qty_completed = 0;
            $purchase_challan_qty_completed = 0;
            $pending_sales_order_qty = 0;
            $pending_delivery_order_qty = 0;
            $pending_purchase_order_qty = 0;
            $pending_purchase_advice_qty = 0;
            $virtual_stock_qty = 0;
            $physical_closing = 0;
            $orders_pending_delivery_order_qty = 0;
            $purchase_orders_pending_purchase_advice_qty = 0;

            $product_sub_id = $inventory->product_sub_category_id;
            /* ===================== Pending order details ===================== */

//            $orders = Order::where('order_status', '=', 'pending')
//                            ->with(['all_order_products.product_sub_category', 'all_order_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
            if (isset($orders) && count($orders) > 0) {
                foreach ($orders as $orders_details) {
                    if (isset($orders_details->all_order_products) && count($orders_details->all_order_products) > 0) {
                        foreach ($orders_details->all_order_products as $orders_product_details) {
                            if (isset($orders_product_details) && $orders_product_details->quantity != '' && $orders_product_details['product_category_id'] == $product_sub_id) {
//                                $order_qty = $order_qty + $orders_product_details->quantity;
                                if ($orders_product_details->unit_id == 1) {
                                    $order_qty = $order_qty + $orders_product_details->quantity;
                                }
                                if ($orders_product_details->unit_id == 2) {
                                    $order_qty = $order_qty + ($orders_product_details->quantity * $orders_product_details->product_sub_category->weight);
                                }
                                if ($orders_product_details->unit_id == 3) {
                                    $order_qty = $order_qty + (($orders_product_details->quantity / $orders_product_details->product_sub_category->standard_length ) * $orders_product_details->product_sub_category->weight);
                                }
                            }
                        }

//                        $order_delivery_orders = DeliveryOrder::where('order_id', '=', $orders_details->id)
//                                        ->where('order_status', '=', 'pending')
//                                        ->with(['delivery_product.product_sub_category', 'delivery_product' => function($q) use($product_sub_id) {
//                                                $q->where('product_category_id', '=', $product_sub_id);
//                                            }])->get();

                        if (isset($order_delivery_orders) && count($order_delivery_orders) > 0) {
                            foreach ($order_delivery_orders as $delivery_orders_details) {
                                if (isset($delivery_orders_details->delivery_product) && count($delivery_orders_details->delivery_product) > 0) {
                                    foreach ($delivery_orders_details->delivery_product as $delivery_orders_product_details) {
                                        if ($delivery_orders_product_details['product_category_id'] == $product_sub_id && $delivery_orders_details['order_id'] == $orders_details->id) {
                                            if (isset($delivery_orders_product_details) && $delivery_orders_product_details->quantity != '') {
//                                        $orders_pending_delivery_order_qty = $orders_pending_delivery_order_qty + $delivery_orders_product_details->quantity;
                                                if ($delivery_orders_product_details->unit_id == 1) {
                                                    $orders_pending_delivery_order_qty = $orders_pending_delivery_order_qty + $delivery_orders_product_details->quantity;
                                                }
                                                if ($delivery_orders_product_details->unit_id == 2) {
                                                    $orders_pending_delivery_order_qty = $orders_pending_delivery_order_qty + ($delivery_orders_product_details->quantity * $delivery_orders_product_details->product_sub_category->weight);
                                                }
                                                if ($delivery_orders_product_details->unit_id == 3) {
                                                    $orders_pending_delivery_order_qty = $orders_pending_delivery_order_qty + (($delivery_orders_product_details->quantity / $delivery_orders_product_details->product_sub_category->standard_length ) * $delivery_orders_product_details->product_sub_category->weight);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $order_qty = $order_qty - $orders_pending_delivery_order_qty;
            }
            /* ===================== Pending delivery order details ===================== */

//            $delivery_orders = DeliveryOrder::where('order_status', '=', 'pending')
//                            ->with(['delivery_product.product_sub_category', 'delivery_product' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();

            if (isset($delivery_orders) && count($delivery_orders) > 0) {
                foreach ($delivery_orders as $delivery_orders_details) {
                    if (isset($delivery_orders_details->delivery_product) && count($delivery_orders_details->delivery_product) > 0) {
                        foreach ($delivery_orders_details->delivery_product as $delivery_orders_product_details) {
                            if ($delivery_orders_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($delivery_orders_product_details) && $delivery_orders_product_details->quantity != '') {
//                                $pending_delivery_order_qty = $pending_delivery_order_qty + $delivery_orders_product_details->quantity;
                                    if ($delivery_orders_product_details->unit_id == 1) {
                                        $pending_delivery_order_qty = $pending_delivery_order_qty + $delivery_orders_product_details->quantity;
                                    }
                                    if ($delivery_orders_product_details->unit_id == 2) {
                                        $pending_delivery_order_qty = $pending_delivery_order_qty + ($delivery_orders_product_details->quantity * $delivery_orders_product_details->product_sub_category->weight);
                                    }
                                    if ($delivery_orders_product_details->unit_id == 3) {
                                        $pending_delivery_order_qty = $pending_delivery_order_qty + (($delivery_orders_product_details->quantity / $delivery_orders_product_details->product_sub_category->standard_length ) * $delivery_orders_product_details->product_sub_category->weight);
                                    }
                                }
                            }
                        }
                    }
                }
            }
//            /* ===================== Pending Delievry Challan details =====================old code replace by new below */
//            $delivery_challan = DeliveryChallan::where('challan_status', '=', 'pending')
//                            ->with(['delivery_challan_products.product_sub_category', 'delivery_challan_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();


            if (isset($delivery_challan) && count($delivery_challan) > 0) {
                foreach ($delivery_challan as $delivery_challan_details) {
                    if (isset($delivery_challan_details->delivery_challan_products) && count($delivery_challan_details->delivery_challan_products) > 0) {
                        foreach ($delivery_challan_details->delivery_challan_products as $delivery_challan_product_details) {
                            if ($delivery_challan_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($delivery_challan_product_details) && $delivery_challan_product_details->quantity != '') {
//                                $sales_challan_qty = $sales_challan_qty + $delivery_challan_product_details->quantity;
                                    $sales_challan_qty = $sales_challan_qty + $delivery_challan_product_details->actual_quantity;
//                                    if ($delivery_challan_product_details->unit_id == 1) {
//                                        $sales_challan_qty = $sales_challan_qty + $delivery_challan_product_details->quantity;
//                                    }
//                                    if ($delivery_challan_product_details->unit_id == 2) {
//                                        $sales_challan_qty = $sales_challan_qty + ($delivery_challan_product_details->quantity * $delivery_challan_product_details->product_sub_category->weight);
//                                    }
//                                    if ($delivery_challan_product_details->unit_id == 3) {
//                                        $sales_challan_qty = $sales_challan_qty + (($delivery_challan_product_details->quantity / $delivery_challan_product_details->product_sub_category->standard_length ) * $delivery_challan_product_details->product_sub_category->weight);
//                                    }
                                }
                            }
                        }
                    }
                }
            }


            /* ===================== Completed Delievry Challan details =====================old code replace by new below */

//            $delivery_challan_completed = DeliveryChallan::where('challan_status', '=', 'completed')
//                            ->with(['delivery_challan_products.product_sub_category', 'delivery_challan_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
            if (isset($delivery_challan_completed) && count($delivery_challan_completed) > 0) {
                foreach ($delivery_challan_completed as $delivery_challan_details) {
                    if (isset($delivery_challan_details->delivery_challan_products) && count($delivery_challan_details->delivery_challan_products) > 0) {
                        foreach ($delivery_challan_details->delivery_challan_products as $delivery_challan_product_details) {
                            if ($delivery_challan_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($delivery_challan_product_details) && $delivery_challan_product_details->quantity != '') {
//                                $sales_challan_qty_completed = $sales_challan_qty_completed + $delivery_challan_product_details->quantity;                                    
                                    $sales_challan_qty = $sales_challan_qty + $delivery_challan_product_details->actual_quantity;
//                                    if ($delivery_challan_product_details->unit_id == 1) {
//                                        $sales_challan_qty_completed = $sales_challan_qty_completed + $delivery_challan_product_details->quantity;
//                                    }
//                                    if ($delivery_challan_product_details->unit_id == 2) {
//                                        $sales_challan_qty_completed = $sales_challan_qty_completed + ($delivery_challan_product_details->quantity * $delivery_challan_product_details->product_sub_category->weight);
//                                    }
//                                    if ($delivery_challan_product_details->unit_id == 3) {
//                                        $sales_challan_qty_completed = $sales_challan_qty_completed + (($delivery_challan_product_details->quantity / $delivery_challan_product_details->product_sub_category->standard_length ) * $delivery_challan_product_details->product_sub_category->weight);
//                                    }
                                }
                            }
                        }
                    }
                }
            }



//            /* ===================== competed Delievry Challan details ===================== new code replaced by old one */
//            $delivery_orders = DeliveryOrder::where('order_status', '=', 'completed')
//                     ->where('updated_at', 'like', date('Y-m-d') . '%')
//                            ->with(['delivery_product.product_sub_category', 'delivery_product' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
//           
//            if (isset($delivery_orders) && count($delivery_orders) > 0) {
//                foreach ($delivery_orders as $delivery_orders_details) {
//                    if (isset($delivery_orders_details->delivery_product) && count($delivery_orders_details->delivery_product) > 0) {
//                        foreach ($delivery_orders_details->delivery_product as $delivery_orders_product_details) {
//                            if (isset($delivery_orders_product_details) && $delivery_orders_product_details->quantity != '') {
////                                $sales_challan_qty_completed = $sales_challan_qty_completed + $delivery_orders_product_details->quantity;
//                                if ($delivery_orders_product_details->unit_id == 1) {
//                                    $sales_challan_qty_completed = $sales_challan_qty_completed + $delivery_orders_product_details->quantity;
//                                }
//                                if ($delivery_orders_product_details->unit_id == 2) {
//                                    $sales_challan_qty_completed = $sales_challan_qty_completed + ($delivery_orders_product_details->quantity * $delivery_orders_product_details->product_sub_category->weight);
//                                }
//                                if ($delivery_orders_product_details->unit_id == 3) {
//                                    $sales_challan_qty_completed = $sales_challan_qty_completed + (($delivery_orders_product_details->quantity / $delivery_orders_product_details->product_sub_category->standard_length ) * $delivery_orders_product_details->product_sub_category->weight);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//



            /* ===================== Purchase order details ===================== */
//            $purchase_orders = PurchaseOrder::where('order_status', '=', 'pending')
//                            ->with(['purchase_products.product_sub_category', 'purchase_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
            if (isset($purchase_orders) && count($purchase_orders) > 0) {
                foreach ($purchase_orders as $purchase_orders_details) {
                    if (isset($purchase_orders_details->purchase_products) && count($purchase_orders_details->purchase_products) > 0) {
                        foreach ($purchase_orders_details->purchase_products as $purchase_orders_product_details) {
                            if ($purchase_orders_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($purchase_orders_product_details) && $purchase_orders_product_details->quantity != '' && count($purchase_orders_product_details->quantity) > 0) {
//                                $pending_purchase_order_qty = $pending_purchase_order_qty + $purchase_orders_product_details->quantity;
                                    if ($purchase_orders_product_details->unit_id == 1) {
                                        $pending_purchase_order_qty = $pending_purchase_order_qty + $purchase_orders_product_details->quantity;
                                    }
                                    if ($purchase_orders_product_details->unit_id == 2) {
                                        if (isset($purchase_orders_product_details->product_sub_category->weight)) {
                                            $pending_purchase_order_qty = $pending_purchase_order_qty + ($purchase_orders_product_details->quantity * $purchase_orders_product_details->product_sub_category->weight);
                                        }
                                    }
                                    if ($purchase_orders_product_details->unit_id == 3) {
                                        $pending_purchase_order_qty = $pending_purchase_order_qty + (($purchase_orders_product_details->quantity / $purchase_orders_product_details->product_sub_category->standard_length ) * $purchase_orders_product_details->product_sub_category->weight);
                                    }
                                }
                            }
                        }

//                        $purchase_orders_purchase_advice = PurchaseAdvise::where('advice_status', '=', 'in_process')
//                                        ->where('purchase_order_id', '=', $purchase_orders_details->id)
//                                        ->with(['purchase_products.product_sub_category', 'purchase_products' => function($q) use($product_sub_id) {
//                                                $q->where('product_category_id', '=', $product_sub_id);
//                                            }])->get();
                        if (isset($purchase_orders_purchase_advice) && count($purchase_orders_purchase_advice) > 0) {
                            foreach ($purchase_orders_purchase_advice as $purchase_advice_details) {
                                if (isset($purchase_advice_details->purchase_products) && count($purchase_advice_details->purchase_products) > 0) {
                                    foreach ($purchase_advice_details->purchase_products as $purchase_advice_product_details) {
                                        if ($purchase_advice_product_details['product_category_id'] == $product_sub_id && $purchase_advice_product_details['order_id'] == $orders_details->id) {
                                            if (isset($purchase_advice_product_details) && $purchase_advice_product_details->quantity != '') {
//                                        $purchase_orders_pending_purchase_advice_qty = $purchase_orders_pending_purchase_advice_qty + $purchase_advice_product_details->quantity;
                                                if ($purchase_advice_product_details->unit_id == 1) {
                                                    $purchase_orders_pending_purchase_advice_qty = $purchase_orders_pending_purchase_advice_qty + $purchase_advice_product_details->quantity;
                                                }
                                                if ($purchase_advice_product_details->unit_id == 2) {
                                                    $purchase_orders_pending_purchase_advice_qty = $purchase_orders_pending_purchase_advice_qty + ($purchase_advice_product_details->quantity * $purchase_advice_product_details->product_sub_category->weight);
                                                }
                                                if ($purchase_advice_product_details->unit_id == 3) {
                                                    $purchase_orders_pending_purchase_advice_qty = $purchase_orders_pending_purchase_advice_qty + (($purchase_advice_product_details->quantity / $purchase_advice_product_details->product_sub_category->standard_length ) * $purchase_advice_product_details->product_sub_category->weight);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $pending_purchase_order_qty = $pending_purchase_order_qty - $purchase_orders_pending_purchase_advice_qty;
            }
            /* ===================== Purchase advice details ===================== */
//            $purchase_advice = PurchaseAdvise::where('advice_status', '=', 'in_process')
//                            ->with(['purchase_products.product_sub_category', 'purchase_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
            if (isset($purchase_advice) && count($purchase_advice) > 0) {
                foreach ($purchase_advice as $purchase_advice_details) {
                    if (isset($purchase_advice_details->purchase_products) && count($purchase_advice_details->purchase_products) > 0) {
                        foreach ($purchase_advice_details->purchase_products as $purchase_advice_product_details) {
                            if ($purchase_advice_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($purchase_advice_product_details) && $purchase_advice_product_details->quantity != '') {
//                                $pending_purchase_advice_qty = $pending_purchase_advice_qty + $purchase_advice_product_details->quantity;
                                    if ($purchase_advice_product_details->unit_id == 1) {
                                        $pending_purchase_advice_qty = $pending_purchase_advice_qty + $purchase_advice_product_details->quantity;
                                    }
                                    if ($purchase_advice_product_details->unit_id == 2) {
                                        $pending_purchase_advice_qty = $pending_purchase_advice_qty + ($purchase_advice_product_details->quantity * $purchase_advice_product_details->product_sub_category->weight);
                                    }
                                    if ($purchase_advice_product_details->unit_id == 3) {
                                        $pending_purchase_advice_qty = $pending_purchase_advice_qty + (($purchase_advice_product_details->quantity / $purchase_advice_product_details->product_sub_category->standard_length ) * $purchase_advice_product_details->product_sub_category->weight);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /* ===================== Purchase Challan details ===================== */
//            $purchase_challan = PurchaseChallan::where('order_status', '=', 'pending')
//                            ->with(['all_purchase_products.product_sub_category', 'all_purchase_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
            if (isset($purchase_challan) && count($purchase_challan) > 0) {
                foreach ($purchase_challan as $purchase_challan_details) {
                    if (isset($purchase_challan_details->all_purchase_products) && count($purchase_challan_details->all_purchase_products) > 0) {
                        foreach ($purchase_challan_details->all_purchase_products as $purchase_challan_product_details) {
                            if ($purchase_challan_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($purchase_challan_product_details) && $purchase_challan_product_details->quantity != '') {
                                $purchase_challan_qty = $purchase_challan_qty + $purchase_challan_product_details->quantity;
//                                    if ($purchase_challan_product_details->unit_id == 1) {
//                                        $purchase_challan_qty = $purchase_challan_qty + $purchase_challan_product_details->quantity;
//                                    }
//                                    if ($purchase_challan_product_details->unit_id == 2) {
//                                        $purchase_challan_qty = $purchase_challan_qty + ($purchase_challan_product_details->quantity * $purchase_challan_product_details->product_sub_category->weight);
//                                    }
//                                    if ($purchase_challan_product_details->unit_id == 3) {
//                                        $purchase_challan_qty = $purchase_challan_qty + (($purchase_challan_product_details->quantity / $purchase_challan_product_details->product_sub_category->standard_length ) * $purchase_challan_product_details->product_sub_category->weight);
//                                    }
                                }
                            }
                        }
                    }
                }
            }


            /* ===================== Purchase Challan details ===================== */
//            $purchase_challan_completed = PurchaseChallan::where('order_status', '=', 'completed')
//                            ->with(['all_purchase_products.product_sub_category', 'all_purchase_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
            if (isset($purchase_challan_completed) && count($purchase_challan_completed) > 0) {
                foreach ($purchase_challan_completed as $purchase_challan_details) {
                    if (isset($purchase_challan_details->all_purchase_products) && count($purchase_challan_details->all_purchase_products) > 0) {
                        foreach ($purchase_challan_details->all_purchase_products as $purchase_challan_product_details) {
                            if ($purchase_challan_product_details['product_category_id'] == $product_sub_id) {
                                if (isset($purchase_challan_product_details) && $purchase_challan_product_details->quantity != '') {
                                $purchase_challan_qty_completed = $purchase_challan_qty_completed + $purchase_challan_product_details->quantity;
//                                    if ($purchase_challan_product_details->unit_id == 1) {
//                                        $purchase_challan_qty_completed = $purchase_challan_qty_completed + $purchase_challan_product_details->quantity;
//                                    }
//                                    if ($purchase_challan_product_details->unit_id == 2) {
//                                        $purchase_challan_qty_completed = $purchase_challan_qty_completed + ($purchase_challan_product_details->quantity * $purchase_challan_product_details->product_sub_category->weight);
//                                    }
//                                    if ($purchase_challan_product_details->unit_id == 3) {
//                                        $purchase_challan_qty_completed = $purchase_challan_qty_completed + (($purchase_challan_product_details->quantity / $purchase_challan_product_details->product_sub_category->standard_length ) * $purchase_challan_product_details->product_sub_category->weight);
//                                    }
                                }
                            }
                        }
                    }
                }
            }


//                        /* ===================== Purchase advice details ===================== */
//            $purchase_advice = PurchaseAdvise::where('advice_status', '=', 'delivered')
//                    ->where('updated_at', 'like', date('Y-m-d') . '%')
//                            ->with(['purchase_products.product_sub_category', 'purchase_products' => function($q) use($product_sub_id) {
//                                    $q->where('product_category_id', '=', $product_sub_id);
//                                }])->get();
//            if (isset($purchase_advice) && count($purchase_advice) > 0) {
//                foreach ($purchase_advice as $purchase_advice_details) {
//                    if (isset($purchase_advice_details->purchase_products) && count($purchase_advice_details->purchase_products) > 0) {
//                        foreach ($purchase_advice_details->purchase_products as $purchase_advice_product_details) {
//                            if (isset($purchase_advice_product_details) && $purchase_advice_product_details->quantity != '') {
////                                $purchase_challan_qty = $purchase_challan_qty + $purchase_advice_product_details->quantity;
//                                if ($purchase_advice_product_details->unit_id == 1) {
//                                    $purchase_challan_qty = $purchase_challan_qty + $purchase_advice_product_details->quantity;
//                                }
//                                if ($purchase_advice_product_details->unit_id == 2) {
//                                    $purchase_challan_qty = $purchase_challan_qty + ($purchase_advice_product_details->quantity * $purchase_advice_product_details->product_sub_category->weight);
//                                }
//                                if ($purchase_advice_product_details->unit_id == 3) {
//                                    $purchase_challan_qty = $purchase_challan_qty + (($purchase_advice_product_details->quantity / $purchase_advice_product_details->product_sub_category->standard_length ) * $purchase_advice_product_details->product_sub_category->weight);
//                                }
//                            }
//                        }
//                    }
//                }
//            }

            /* ===================== Query ends here ===================== */

//            $physical_closing = ($inventory->opening_qty + $purchase_challan_qty ) - $sales_challan_qty;

            $purchase_challan_qty_all = $purchase_challan_qty_completed + $purchase_challan_qty;
            $sales_challan_qty_all = $sales_challan_qty_completed + $sales_challan_qty;

//            $physical_closing = ($inventory->opening_qty + $purchase_challan_qty_completed ) - $sales_challan_qty_completed;
            $physical_closing = ($inventory->opening_qty + $purchase_challan_qty_all ) - $sales_challan_qty_all;

            $inventory_details = Inventory::where('product_sub_category_id', '=', $product_sub_id)->first();
            $inventory_details->opening_qty = $inventory->opening_qty;
            $inventory_details->sales_challan_qty = $sales_challan_qty_all;
//            $inventory_details->sales_challan_qty = $sales_challan_qty;
//            $inventory_details->sales_challan_qty = $sales_challan_qty_completed;
//            $inventory_details->purchase_challan_qty = $purchase_challan_qty;
            $inventory_details->purchase_challan_qty = $purchase_challan_qty_all;
            $inventory_details->physical_closing_qty = $physical_closing;
            $inventory_details->pending_sales_order_qty = $order_qty;
            $inventory_details->pending_delivery_order_qty = $pending_delivery_order_qty;
            $inventory_details->pending_purchase_order_qty = $pending_purchase_order_qty;
            $inventory_details->pending_purchase_advise_qty = $pending_purchase_advice_qty;
            $virtual_qty = ($physical_closing + $inventory_details->pending_purchase_order_qty + $inventory_details->pending_purchase_advise_qty) - ($inventory_details->pending_sales_order_qty + $inventory_details->pending_delivery_order_qty);
            //$inventory_details->virtual_qty = $virtual_qty;
            $inventory_details->save();
        }
    }

   
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
//
    }

    /**
     * Update all inventory list
     */
    public function store() {

        $data = Input::all();
        $data_dup = Input::all();
        $token = array_pull($data, '_token');
        $currentpage = array_pull($data, 'pagenumber');
        $i = 1;
        $j = 1;
        foreach ($data as $key => $value) {
            if (($j % 2) != 0) {
                if ($value < 0) {
                    return back()->with('error', 'Negative values are not allowed in minimal stock.');
                }
            }
            $j++;
        }
        foreach ($data as $key => $value) {
            if (($i % 2) != 0) {
                $minimal_value = $value;
                $myarray = explode("_", $key);
                $opening_qty_value = $myarray[1];
//                $inventory_details = Inventory::find($myarray[1]);
                $inventory_details = Inventory::where('product_sub_category_id', '=', $myarray[1])->first();
                $inventory_details->minimal = $minimal_value;
                $inventory_details->opening_qty = $data_dup[$opening_qty_value];
                $physical_qty = ($data_dup[$opening_qty_value] + $inventory_details->purchase_challan_qty) - $inventory_details->sales_challan_qty;
                $inventory_details->physical_closing_qty = $physical_qty;
                $virtual_qty = ($inventory_details->physical_closing_qty + $inventory_details->pending_purchase_order_qty + $inventory_details->pending_purchase_advise_qty) - ($inventory_details->pending_sales_order_qty + $inventory_details->pending_delivery_order_qty);
                //$inventory_details->virtual_qty = $virtual_qty;
                $inventory_details->save();
            }
            $i++;
        }
        $inventory_newlist = Inventory::with('product_sub_category')->paginate(50);
        $appendurl = ($currentpage > 1) ? "?page=" . $currentpage : '';
        return redirect('inventory' . $appendurl)->with(['inventory_list' => $inventory_newlist, 'success' => 'Inventory details successfully updated.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
//
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
//
    }

    /*
     * Cron for updating physical stock to opening stock(Cron will run at 7 pm everyday)
     */

    public function updateOpeningStock() {

        $inventory_list = Inventory::first();
        $current = \Carbon\Carbon::now();
        if ($current->hour > 1) {
            $inventory = new Inventory();
            if (isset($inventory_list->opening_qty_date) && $inventory_list->opening_qty_date != NULL) {
                $last_updated = explode(' ', $inventory_list->opening_qty_date);
                $last_updated_date = $last_updated[0];
                $last_updated_time = explode(':', $last_updated[1]);
                $current_date = $current->toDateString();
                $current_hour = $current->hour;
                if ($last_updated_date < $current_date) {
                    $inventory->update_opening_stock();
                } else if ($last_updated_date == $current_date) {
                    if ($current_hour > 1 && $last_updated_time[0] < 1) {
                        $inventory->update_opening_stock();
                    }
                }
            } else {
                $inventory->update_opening_stock();
            }
        }
    }

    /*
     * Cron for updating physical stock to opening stock(Cron will run at 7 pm everyday)
     */

    public function updateOpeningStockCron() {
        $is_update = 0;
        $inventory_list = Inventory::first();
        $current = \Carbon\Carbon::now();
        $inventory = new Inventory();
        $last_updated;
        if (isset($inventory_list->opening_qty_date) && $inventory_list->opening_qty_date != NULL) {
            $last_updated = explode(' ', $inventory_list->opening_qty_date);
            $last_updated_date = $last_updated[0];
            $last_updated_time = explode(':', $last_updated[1]);
            $current_date = $current->toDateString();
            if (!$last_updated_date < $current_date) {
                $is_update = $inventory->update_opening_stock();
            }
        } else {
            $is_update = $inventory->update_opening_stock();
        }

        if ($is_update > 0)
            $str = $is_update . " records has been updated at " . $current_date . " " . $current->toTimeString();
        else {
            $str = "No records has been updated. " . $last_updated[0] . " " . $last_updated[1];
        }

        echo $str;
    }

    /*
     * Export inventory details in excel file
     */

    public function export_inventory($input = null) {

        $query = Inventory::query();
        if (Input::has('inventory_filter') && Input::get('inventory_filter') == 'minimal') {

//                $query->where('minimal','<','physical_closing_qty'-'pending_delivery_order_qty'-'pending_sales_order_qty'+'pending_purchase_advise_qty');
            $query->whereRaw('minimal < physical_closing_qty-pending_delivery_order_qty-pending_sales_order_qty+pending_purchase_advise_qty');
        }
        if (Input::has('product_category_filter') && Input::get('product_category_filter') != '') {
            $categoryid = Input::get('product_category_filter');
            $query->whereHas('product_sub_category', function($q) use($categoryid) {
                $q->where('product_category_id', '=', $categoryid);
            });
        }

        if (Input::has('search_inventory') && Input::get('search_inventory') != '') {
            $alias_name = '%' . trim(Input::get('search_inventory')) . '%';
            $product_sub_id = ProductSubCategory::where('alias_name', 'LIKE', $alias_name)->first();
            if (count($product_sub_id)) {
                $query->where('product_sub_category_id', '=', $product_sub_id->id);
            }else{
                return;
            }
        }


        $inventorys = $query->with('product_sub_category')
                ->join('product_sub_category', 'inventory.product_sub_category_id', '=', 'product_sub_category.id')
                ->orderBy('product_sub_category.alias_name', 'ASC')
                ->get();
        

        Excel::create('Inventory List', function($excel) use($inventorys) {
            $excel->sheet('Inventory-List', function($sheet) use($inventorys) {
                $virtual_stock_qty = array();
                foreach ($inventorys as $inventory) {
                    $virtual_qty = ($inventory->physical_closing_qty + $inventory->pending_purchase_order_qty + $inventory->pending_purchase_advise_qty) - ($inventory->pending_sales_order_qty + $inventory->pending_delivery_order_qty);
                    array_push($virtual_stock_qty,$virtual_qty);
                }
                
                $sheet->loadView('excelView.inventory', array('inventorys' => $inventorys,'virtual_stock_qty'=>$virtual_stock_qty));
            });
        })->export('xls');
        exit();
    }

//    public function export_inventory() {
//
//        $inventorys = Inventory::with('product_sub_category')->get();
//        Excel::create('Inventory List', function($excel) use($inventorys) {
//            $excel->sheet('Inventory-List', function($sheet) use($inventorys) {
//                $sheet->loadView('excelView.inventory', array('inventorys' => $inventorys));
//            });
//        })->export('xls');
//        exit();
//    }

    /*
     * Fill product sub category reference in inventory table with 0.00 opening stock
     */

    public function fillInventoryList() {

        $subcategory_list = ProductSubCategory::all();
        $inventory_list = Inventory::all();
        if (count($subcategory_list) > 0 && count($inventory_list) == 0) {
            foreach ($subcategory_list as $subcategory) {
                $product_category_idsinventory = new Inventory();
                $product_category_idsinventory->product_sub_category_id = $subcategory->id;
                $product_category_idsinventory->save();
            }
        }
        echo "Inventory product listing updated successfully";
    }

    public function inventoryReport() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();

        if (count($product_cat) > 0) {
            $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'asc')->first()->get();
//        $product_last = ProductCategory::where('id', '=' , 40)->with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();

            $size_array = [];
            $thickness_array = [];
            $report_arr = [];
            $final_arr = [];
            $product_id = $product_last[0]->id;
            //dd($product_id);
            $product_type = $product_last[0]->product_type_id;
            if ($product_type == 1 || $product_type == 3) {
                $product_column = "Size";
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    if (!in_array($sub_cat->thickness, $thickness_array)) {
                        array_push($thickness_array, $sub_cat->thickness);
                    }
                }
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    if (!in_array($sub_cat->size, $size_array)) {
                        array_push($size_array, $sub_cat->size);
                    }
                }
                foreach ($size_array as $size) {
                    foreach ($thickness_array as $thickness) {
                        foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                            if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                                $inventory = $sub_cat['product_inventory'];
                                $total_qnty = 0;
                                if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                    $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                                $report_arr[$size][$thickness] = $total_qnty;
                            }
                        }
                    }
                }
            }
            if ($product_type == 2) {
                $product_column = "Product Alias";
                array_push($thickness_array, "NA");
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    if (!in_array($sub_cat->alias_name, $size_array)) {
                        array_push($size_array, $sub_cat->alias_name);
                    }
                }
                foreach ($size_array as $size) {
                    foreach ($thickness_array as $thickness) {
                        foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                            if ($sub_cat->thickness == $thickness && $size == $sub_cat->alias_name) {
                                $inventory = $sub_cat['product_inventory'];
                                $total_qnty = 0;
                                if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                    $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                                $report_arr[$size][$thickness] = $total_qnty;
                            }
                        }
                    }
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    if (isset($report_arr[$size][$thickness])) {
//                        $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                        $final_arr[$size][$thickness] = round($report_arr[$size][$thickness] / 1000, 2);
                    } else {
                        $final_arr[$size][$thickness] = "-";
                    }
                }
            }

            $report_arr = $final_arr;
            return view('inventory_report')->with('product_cat', $product_cat)
                            ->with('product_id', $product_id)
                            ->with('product_last', $product_last)
                            ->with('thickness_array', $thickness_array)
                            ->with('report_arr', $report_arr)
                            ->with('product_column', $product_column);
        }
        $product_id = 0;
        $product_column = 0;

        return view('inventory_report')
                        ->with('product_column', $product_column)
                        ->with('product_id', $product_id);
    }

    public function getInventoryReport(Request $request) {
        $product_id = $request->input('product_id');
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
//        $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                            } else {
                                $total_qnty = "-";
                            }
                            $report_arr[$size][$thickness] = $total_qnty;
                        }
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $inventory = $sub_cat['product_inventory'];
                    $total_qnty = 0;
                    if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                        $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                    } else {
                        $total_qnty = "-";
                    }
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_qnty;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
//                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                    $final_arr[$size][$thickness] = round($report_arr[$size][$thickness] / 1000, 2);
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;
        $html = view('_inventory_report')->with('product_cat', $product_cat)
                ->with('product_last', $product_last)
                ->with('thickness_array', $thickness_array)
                ->with('report_arr', $report_arr)
                ->with('product_column', $product_column)
                ->render();

        return Response::json(['success' => true, 'html' => $html]);
//        return view('inventory_report')->with('product_cat',$product_cat)->with('product_last',$product_last);
    }

    public function inventoryPriceList() {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();

        if (count($product_cat) > 0) {
            $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'asc')->first()->get();
//        $product_last = ProductCategory::where('id', '=' , 40)->with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();
            $product_id = $product_last[0]->id;
            $product_price = $product_last[0]->price;
            $size_array = [];
            $thickness_array = [];
            $report_arr = [];
            $final_arr = [];
            $product_type = $product_last[0]->product_type_id;
            if ($product_type == 1 || $product_type == 3) {
                $product_column = "Size";
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    if (!in_array($sub_cat->thickness, $thickness_array)) {
                        array_push($thickness_array, $sub_cat->thickness);
                    }
                }
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    if (!in_array($sub_cat->size, $size_array)) {
                        array_push($size_array, $sub_cat->size);
                    }
                }
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        $total_price = 0;
                        if ($sub_cat->thickness == $thickness) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_price = $product_price + $sub_cat->difference;

                            $report_arr[$sub_cat->size][$sub_cat->thickness] = $total_price;
                        }
                    }
                }
            }
            if ($product_type == 2) {
                $product_column = "Product Alias";
                array_push($thickness_array, "NA");
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    if (!in_array($sub_cat->alias_name, $size_array)) {
                        array_push($size_array, $sub_cat->alias_name);
                    }
                }
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        $total_price = 0;
                        $inventory = $sub_cat['product_inventory'];
                        $total_price = $product_price + $sub_cat->difference;
                        $report_arr[$sub_cat->alias_name][$thickness] = $total_price;
                    }
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    if (isset($report_arr[$size][$thickness])) {
                        $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                    } else {
                        $final_arr[$size][$thickness] = "-";
                    }
                }
            }

            $report_arr = $final_arr;
            return view('inventory_price_list')->with('product_cat', $product_cat)
                            ->with('product_id', $product_id)
                            ->with('product_last', $product_last)
                            ->with('thickness_array', $thickness_array)
                            ->with('product_column', $product_column)
                            ->with('report_arr', $report_arr);
        }
        $product_id = 0;
        $product_column = 0;
        return view('inventory_price_list')->with('product_id', $product_id)
                        ->with('product_column', $product_column);
    }

    public function getInventoryPriceList(Request $request) {
        $product_id = $request->input('product_id');
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
//        $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $product_price = $product_last[0]->price;
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    if ($sub_cat->thickness == $thickness) {
                        $inventory = $sub_cat['product_inventory'];
                        $total_price = $product_price + $sub_cat->difference;

                        $report_arr[$sub_cat->size][$sub_cat->thickness] = $total_price;
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    $inventory = $sub_cat['product_inventory'];
                    $total_price = $product_price + $sub_cat->difference;
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_price;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;

        $html = view('_inventory_price_list')->with('product_cat', $product_cat)
                ->with('product_id', $product_id)
                ->with('product_last', $product_last)
                ->with('thickness_array', $thickness_array)
                ->with('product_column', $product_column)
                ->with('report_arr', $report_arr)
                ->render();

        return Response::json(['success' => true, 'html' => $html]);
//        return view('inventory_report')->with('product_cat',$product_cat)->with('product_last',$product_last);
    }

    public function setInventoryPrice(Request $request) {
        $product_id = $request->input('product_id');
        $size = $request->input('size');
        $thickness = $request->input('thickness');
        $new_price = $request->input('new_price');
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            if (isset($product_id) && isset($size) && isset($thickness)) {
                $subproduct = ProductSubCategory::where('product_category_id', '=', $product_id)
                                ->where('thickness', '=', $thickness)
                                ->where('size', '=', $size)->get();
                $sub_prod_id = $subproduct[0]->id;
            }
        }
        if ($product_type == 2) {
            if (isset($product_id) && isset($size)) {
                $subproduct = ProductSubCategory::where('product_category_id', '=', $product_id)
                                ->where('alias_name', '=', $size)->get();
                $sub_prod_id = $subproduct[0]->id;
            }
        }
        $product_category = ProductCategory::where('id', '=', $product_id)->get();
        $product_base_price = $product_category[0]->price;
        $difference = $new_price - $product_base_price;
        $update_sub_prod = ProductSubCategory::find($sub_prod_id);
        $update_sub_prod->difference = $difference;
        $update_sub_prod->save();
        return Response::json(['success' => true]);
    }

    public function exportinventoryPriceList(Request $request) {
        $product_id = $request->input('product_id');
        $size = $request->input('size');
        $thickness = $request->input('thickness');
        $new_price = $request->input('new_price');
        $product_id = $request->input('product_id');
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
//        $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $product_price = $product_last[0]->price;
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    if ($sub_cat->thickness == $thickness) {
                        $inventory = $sub_cat['product_inventory'];
                        $total_price = $product_price + $sub_cat->difference;

                        $report_arr[$sub_cat->size][$sub_cat->thickness] = $total_price;
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    $inventory = $sub_cat['product_inventory'];
                    $total_price = $product_price + $sub_cat->difference;
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_price;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;
        Excel::create('Inventory Price List', function($excel) use($product_last, $thickness_array, $report_arr, $product_column) {
            $excel->sheet('Inventory Price List', function($sheet) use($product_last, $thickness_array, $report_arr, $product_column) {
                $sheet->loadView('excelView.inventory_price_list', array('product_last' => $product_last, 'thickness_array' => $thickness_array, 'report_arr' => $report_arr, 'product_column' => $product_column));
            });
        })->export('xls');
    }

    public function exportInventoryReport(Request $request) {
        $product_id = $request->input('product_id');
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
//        $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                            } else {
                                $total_qnty = "-";
                            }
                            $report_arr[$size][$thickness] = $total_qnty;
                        }
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $inventory = $sub_cat['product_inventory'];
                    $total_qnty = 0;
                    if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                        $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                    } else {
                        $total_qnty = "-";
                    }
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_qnty;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;
        Excel::create('Inventory Report', function($excel) use($product_last, $thickness_array, $report_arr, $product_column) {
            $excel->sheet('Inventory Report', function($sheet) use($product_last, $thickness_array, $report_arr, $product_column) {
                $sheet->loadView('excelView.inventory_report', array('product_last' => $product_last, 'thickness_array' => $thickness_array, 'report_arr' => $report_arr, 'product_column' => $product_column));
            });
        })->export('xls');
    }

    public function print_inventory_report($id, DropboxStorageRepository $connection) {
        $product_id = $id;
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                            } else {
                                $total_qnty = "-";
                            }
                            $report_arr[$size][$thickness] = $total_qnty;
                        }
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $inventory = $sub_cat['product_inventory'];
                    $total_qnty = 0;
                    if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                        $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                    } else {
                        $total_qnty = "-";
                    }
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_qnty;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;

        return view('print_inventory_report')->with('product_last', $product_last)
                        ->with('thickness_array', $thickness_array)
                        ->with('product_column', $product_column)
                        ->with('report_arr', $report_arr);
    }

    public function print_inventory_price_list($id, DropboxStorageRepository $connection) {
        $product_id = $id;
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
//        $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'desc')->limit(1)->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $product_price = $product_last[0]->price;
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    if ($sub_cat->thickness == $thickness) {
                        $inventory = $sub_cat['product_inventory'];
                        $total_price = $product_price + $sub_cat->difference;

                        $report_arr[$sub_cat->size][$sub_cat->thickness] = $total_price;
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    $inventory = $sub_cat['product_inventory'];
                    $total_price = $product_price + $sub_cat->difference;
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_price;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;

        return view('print_inventory_report')->with('product_last', $product_last)
                        ->with('thickness_array', $thickness_array)
                        ->with('product_column', $product_column)
                        ->with('report_arr', $report_arr);
    }
    
    public function reset_minimal_and_opening() {
        
//        $count = DB::table('inventory')->update(array('minimal' => 0,'opening_qty' => 0));
//        echo $count." records updated";
        
    }
    
}
