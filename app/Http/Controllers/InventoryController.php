<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ProductCategory;
use App\ProductType;
use App\ProductSubCategory;
use App\Units;
use App\Inventory;
use App\PurchaseChallan;
use App\PurchaseAdvise;
use App\DeliveryOrder;
use App\PurchaseOrder;
use App\DeliveryChallan;
use App\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Input;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller {

    public function update_inventory() {
        $inventory_details = Inventory::find(Input::get('id'));
        $inventory_details->opening_qty = Input::get('opening_stock');
        $inventory_details->save();
        echo 'yes';
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {



        $inventory_list = Inventory::with('product_sub_category')->paginate(5);
        foreach ($inventory_list as $inventory) {

            $order_qty = 0;
            $sales_challan_qty = 0;
            $purchase_challan_qty = 0;
            $pending_sales_order_qty = 0;
            $pending_delivery_order_qty = 0;
            $pending_purchase_order_qty = 0;
            $pending_purchase_advice_qty = 0;
            $virtual_stock_qty = 0;
            $physical_closing = 0;

            $product_sub_id = $inventory->product_sub_category_id;
            $orders = Order::where('order_status', '=', 'pending')
                            ->with(['all_order_products' => function($q) use($product_sub_id) {
                                    $q->where('product_category_id', '=', $product_sub_id);
                                }])->get();
            if (isset($orders) && count($orders) > 0) {
                foreach ($orders as $orders_details) {
                    if (isset($orders_details->all_order_products) && count($orders_details->all_order_products) > 0) {
                        foreach ($orders_details->all_order_products as $orders_product_details) {
                            if (isset($orders_product_details) && $orders_product_details->quantity != '') {
                                $order_qty = $order_qty + $orders_product_details->quantity;
                            }
                        }
                    }
                }
            }
            $delivery_challan = DeliveryChallan::where('challan_status', '=', 'pending')
                            ->with(['delivery_challan_products' => function($q) use($product_sub_id) {
                                    $q->where('product_category_id', '=', $product_sub_id);
                                }])->get();


            if (isset($delivery_challan) && count($delivery_challan) > 0) {
                foreach ($delivery_challan as $delivery_challan_details) {
                    if (isset($delivery_challan_details->delivery_challan_products) && count($delivery_challan_details->delivery_challan_products) > 0) {
                        foreach ($delivery_challan_details->delivery_challan_products as $delivery_challan_product_details) {
                            if (isset($delivery_challan_product_details) && $delivery_challan_product_details->quantity != '') {
                                $sales_challan_qty = $sales_challan_qty + $delivery_challan_product_details->quantity;
                            }
                        }
                    }
                }
            }
            $purchase_challan = PurchaseChallan::where('order_status', '=', 'pending')
                            ->with(['all_purchase_products' => function($q) use($product_sub_id) {
                                    $q->where('product_category_id', '=', $product_sub_id);
                                }])->get();

            if (isset($purchase_challan) && count($purchase_challan) > 0) {
                foreach ($purchase_challan as $purchase_challan_details) {
                    if (isset($purchase_challan_details->all_purchase_products) && count($purchase_challan_details->all_purchase_products) > 0) {
                        foreach ($purchase_challan_details->all_purchase_products as $purchase_advice_product_details) {
                            if (isset($purchase_advice_product_details) && $purchase_advice_product_details->quantity != '') {
                                $purchase_challan_qty = $purchase_challan_qty + $purchase_advice_product_details->quantity;
                            }
                        }
                    }
                }
            }

            $purchase_advice = PurchaseAdvise::with(['purchase_products' => function($q) use($product_sub_id) {
                            $q->where('product_category_id', '=', $product_sub_id);
                        }])->get();

            if (isset($purchase_advice) && count($purchase_advice) > 0) {
                foreach ($purchase_advice as $purchase_advice_details) {
                    if (isset($purchase_advice_details->purchase_products) && count($purchase_advice_details->purchase_products) > 0) {
                        foreach ($purchase_advice_details->purchase_products as $purchase_advice_product_details) {
                            if (isset($purchase_advice_product_details) && $purchase_advice_product_details->quantity != '') {
                                $pending_purchase_advice_qty = $pending_purchase_advice_qty + $purchase_advice_product_details->quantity;
                            }
                        }
                    }
                }
            }

            $delivery_orders = DeliveryOrder::with(['delivery_product' => function($q) use($product_sub_id) {
                            $q->where('product_category_id', '=', $product_sub_id);
                        }])->get();

            if (isset($delivery_orders) && count($delivery_orders) > 0) {
                foreach ($delivery_orders as $delivery_orders_details) {
                    if (isset($delivery_orders_details->delivery_product) && count($delivery_orders_details->delivery_product) > 0) {
                        foreach ($delivery_orders_details->delivery_product as $delivery_orders_product_details) {
                            if (isset($delivery_orders_product_details) && $delivery_orders_product_details->quantity != '') {
                                $pending_delivery_order_qty = $pending_delivery_order_qty + $delivery_orders_product_details->quantity;
                            }
                        }
                    }
                }
            }

            $purchase_orders = PurchaseOrder::where('order_status', '=', 'pending')
                            ->with(['purchase_products' => function($q) use($product_sub_id) {
                                    $q->where('product_category_id', '=', $product_sub_id);
                                }])->get();

            if (isset($purchase_orders) && count($purchase_orders) > 0) {
                foreach ($purchase_orders as $purchase_orders_details) {
                    if (isset($purchase_orders_details->purchase_products) && count($purchase_orders_details->purchase_products) > 0) {
                        foreach ($purchase_orders_details->purchase_products as $purchase_orders_product_details) {
                            if (isset($purchase_orders_product_details) && $purchase_orders_product_details->quantity != '') {
                                $pending_purchase_order_qty = $pending_purchase_order_qty + $purchase_orders_product_details->quantity;
                            }
                        }
                    }
                }
            }

            $physical_closing = ($inventory->opening_qty + $purchase_challan_qty ) - $sales_challan_qty;

//            $inventory['sales_challan_qty'] = $sales_challan_qty;
//            $inventory['purchase_challan_qty'] = $purchase_challan_qty;
//            $inventory['physical_closing'] = $physical_closing;
//
//            $inventory['order_qty'] = $order_qty - $pending_delivery_order_qty;
//            $inventory['pending_delivery_order_qty'] = $pending_delivery_order_qty - $sales_challan_qty;
//            $inventory['pending_purchase_order_qty'] = $pending_purchase_order_qty - $pending_purchase_advice_qty;
//            $inventory['pending_purchase_advice_qty'] = $pending_purchase_advice_qty - $purchase_challan_qty;
//            $inventory['virtual_stock_qty'] = ($physical_closing + $inventory['pending_purchase_order_qty'] + $inventory['pending_purchase_advice_qty']) - ($inventory['order_qty'] + $inventory['pending_delivery_order_qty']);
//            echo "<pre>";
//            print_r($sales_challan_qty);
//            echo "<pre>";
//            exit();
//            $inventory_array = [
//                'sales_challan_qty' => $sales_challan_qty,
//                'purchase_challan_qty' => $purchase_challan_qty,
//                'physical_closing_qty' => $physical_closing,
//                'pending_sales_order_qty' => $order_qty - $pending_delivery_order_qty,
//                'pending_delivery_order_qty' => $pending_delivery_order_qty - $sales_challan_qty,
//                'pending_purchase_order_qty' => $pending_purchase_order_qty - $pending_purchase_advice_qty,
//                'pending_purchase_advise_qty' => $pending_purchase_advice_qty - $purchase_challan_qty,
//                'virtual_qty' => ($physical_closing + $inventory['pending_purchase_order_qty'] + $inventory['pending_purchase_advice_qty']) - ($inventory['order_qty'] + $inventory['pending_delivery_order_qty']),
//            ];

            $inventory_details = Inventory::where('product_sub_category_id', '=', $inventory->product_sub_category_id)->first();
            $inventory_details->opening_qty = $inventory->opening_qty;
            $inventory_details->sales_challan_qty = $sales_challan_qty;
            $inventory_details->purchase_challan_qty = $purchase_challan_qty;
            $inventory_details->physical_closing_qty = $physical_closing;
            $inventory_details->pending_sales_order_qty = $order_qty - $pending_delivery_order_qty;
            $inventory_details->pending_delivery_order_qty = $pending_delivery_order_qty - $sales_challan_qty;
            $inventory_details->pending_purchase_order_qty = $pending_purchase_order_qty - $pending_purchase_advice_qty;
            $inventory_details->pending_purchase_advise_qty = $pending_purchase_advice_qty - $purchase_challan_qty;
            $inventory_details->virtual_qty = ($physical_closing + $inventory_details->pending_purchase_order_qty + $inventory_details->pending_purchase_advise_qty) - ($inventory_details->pending_sales_order_qty + $inventory_details->pending_delivery_order_qty);
            $inventory_details->save();
        }
        $inventory_newlist = Inventory::with('product_sub_category')->paginate(5);
//        echo "<pre>";
//        print_r($inventory_newlist->toArray());
//        echo "<pre>";
//        exit();
//        echo "<pre>";
//        print_r($inventory_list->toArray());
//        echo "<pre>";
//        exit();
//        echo "<pre>";
//        print_r($purchase_challan_qty);
//        echo "<pre>";
//        echo "<pre>";
//        print_r($pending_purchase_advice_qty);
//        echo "<pre>";
//        echo "<pre>";
//        print_r($pending_delivery_order_qty);
//        echo "<pre>";
//        echo "<pre>";
//        print_r($pending_purchase_order_qty);
//        echo "<pre>";
//        echo "<pre>";
//        print_r($sales_challan_qty);
//        echo "<pre>";
//        exit();

        return view('add_inventory')->with(['inventory_list' => $inventory_newlist]);
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
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
//
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
//
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
     * Fill product sub category reference in inventory table with 0.00 opening stock
     */

    public function export_inventory() {
        $inventorys = Inventory::with('product_sub_category')->get();
        Excel::create('Inventory List', function($excel) use($inventorys) {
            $excel->sheet('Inventory-List', function($sheet) use($inventorys) {
                $sheet->loadView('excelView.inventory', array('inventorys' => $inventorys));
            });
        })->export('xls');
        exit();
    }

    public function fillInventoryList() {

        $subcategory_list = ProductSubCategory::all();
        $inventory_list = Inventory::all();

        if (count($subcategory_list) > 0 && count($inventory_list) == 0) {

            foreach ($subcategory_list as $subcategory) {
                $newinventory = new Inventory();
                $newinventory->product_sub_category_id = $subcategory->id;
                $newinventory->save();
            }
        }
        echo "Inventory product listing updated successfully";
    }

}
