<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Redirect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Input;
use App\Inquiry;
use App\Order;
use App\User;
use App\DeliveryOrder;
use App\DeliveryLocation;
use App\ProductSubCategory;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseProducts;
use App\PurchaseChallan;
use App\PurchaseAdvise;
use App\Session;

class BulkDeleteController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('bulk_delete');
    }

    public function show_result() {
        
        $module = Input::get('select_module');
        $expected_date = Input::get('expected_date');
        $delete_seletected_module = Input::get('delete_seletected_module');
        
        if ($module == "") {
            return view('bulk_delete');
        }
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $result_data = [];
        
        switch ($module) {
//----------------------------------------------------           
            case 'inquiry':
                $head[0] = 'TALLY NAME';
                $head[1] = 'TOTAL QUANTITY';
                $head[2] = 'PHONE NUMBER';
                $head[3] = 'DELIVERY LOCATION';
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =Inquiry::find($delete_module);
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */

                if (Input::get('expected_date') != '') {
                    $result_temp = Inquiry::where('inquiry_status', '=', Input::get('expected_date'))
                                    ->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details')
                                    ->orderBy('created_at', 'desc')->Paginate(20);
                } else {
                    $result_temp = Inquiry::with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')
                                    ->where('inquiry_status', 'completed')
                                    ->orderBy('created_at', 'desc')->Paginate(20);
                }


                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $qty = 0;
                    foreach ($temp['inquiry_products'] as $prod) {
                        if ($prod['unit']->unit_name == 'KG') {
                            $qty += $prod->quantity;
                        }
                        if ($prod['unit']->unit_name == 'Pieces') {
                            $qty += $prod->quantity * $prod['inquiry_product_details']->weight;
                        }
                        if ($prod['unit']->unit_name == 'Meter') {
                            $qty += ($prod->quantity / $prod['inquiry_product_details']->standard_length) * $prod['inquiry_product_details']->weight;
                        }
                    }
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $result_data[$key][1] = '';
                    if (isset($temp['inquiry_products'][0]))
                        $result_data[$key][1] = round($qty, 2);
                    $result_data[$key][2] = $temp['customer']->phone_number1;
                    $result_data[$key][3] = '';
                    if (isset($temp['delivery_location']))
                        $result_data[$key][3] = $temp['delivery_location']->area_name;
                }

                
                break;
//----------------------------------------------------
            case 'order':
                $head[0] = 'TALLY NAME';
                $head[1] = 'TOTAL QUANTITY';
                $head[2] = 'MOBILE';
                $head[3] = 'DELIVERY LOCATION';
                $head[4] = 'ORDER BY';
                
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =Order::find($delete_module);
                        if(isset($inqiry_obj))
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */

                if (Input::get('expected_date') != '') {
                    $result_temp = Order::where('order_status', '=', 'completed')
                                    ->where('created_at','like',Input::get('expected_date').'%')
                                    ->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled')
                                    ->orderBy('created_at', 'desc')->Paginate(20);
                } else {
                    $result_temp = Order::with('all_order_products', 'customer', 'delivery_location', 'order_cancelled')
                                    ->where('order_status', 'completed')
                                    ->orderBy('created_at', 'desc')->Paginate(20);
                }

                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;

                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $total_size_quantity = 0;
                    foreach ($temp->all_order_products as $order_product_array) {
                            $total_size_quantity+=$order_product_array->quantity;
                    }
                    $result_data[$key][1] = round($total_size_quantity, 2);
                    $result_data[$key][2] = $temp['customer']->phone_number1;
                    if ($temp->delivery_location_id != 0)
                        $result_data[$key][3] = $temp['delivery_location']->area_name;
                    elseif ($temp->delivery_location_id == 0)
                        $result_data[$key][3] = $temp['other_location'];
                    $users = User::all();
                    foreach ($users as $u) {
                        if ($u['id'] == $temp['created_by']) {
                            $result_data[$key][4] = $u['first_name'];
                        }
                    }
                }
                break;
//----------------------------------------------------                
            case 'delivery_order':
                $head[0] = 'DATE';
                $head[1] = 'TALLY NAME';
                $head[2] = 'DELIVERY LOCATION';
                $head[3] = 'QUANTITY';
                $head[4] = 'PRESENT SHIPPING';
                $head[5] = 'VEHICLE NUMBER';
                
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =DeliveryOrder::find($delete_module);
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */
                
                if (Input::get('expected_date') != '') {
                    $result_temp = DeliveryOrder::orderBy('created_at', 'desc')->where('created_at','like',Input::get('expected_date').'%')->where('order_status', 'completed')->with('delivery_product', 'customer')->paginate(20);
                }else{
                    $result_temp = DeliveryOrder::orderBy('created_at', 'desc')->where('order_status', 'completed')->with('delivery_product', 'customer')->paginate(20);
                }
                $result_temp = $this->checkpending_quantity($result_temp);
                
                
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    
                    $result_data[$key][0] = date("F jS, Y", strtotime($temp->created_at));
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][1] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][1] = $temp['customer']->owner_name;
                    
                    if ($temp->delivery_location_id != 0) {
                        foreach ($delivery_locations as $location) {
                            if ($location->id == $temp->delivery_location_id) {
                                $result_data[$key][2] = $location->area_name;
                            }
                        }
                    } else {
                        $result_data[$key][2] = $temp->other_location;
                    }

                    $result_data[$key][3] = round($temp->total_quantity, 2);
                    $result_data[$key][4] = round($temp->present_shipping, 2);
                    $result_data[$key][5] = $temp->vehicle_number;
                }
                break;
//----------------------------------------------------            
            case 'delivery_challan':
                
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'PRESENT SHIPPING';
                
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =DeliveryChallan::find($delete_module);
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */
                
                if (Input::get('expected_date') != '') {
                    $result_temp = DeliveryChallan::where('challan_status', '=', 'completed')
                                    ->where('created_at','like',Input::get('expected_date').'%')
                                    ->with('customer', 'delivery_challan_products', 'delivery_order')
                                    ->orderBy('created_at', 'desc')->Paginate(20);
                } else {
                    $result_temp = DeliveryChallan::where('challan_status', '=', 'completed')
                                    ->with('customer', 'delivery_challan_products', 'delivery_order')
                                    ->orderBy('created_at', 'desc')->Paginate(20);
                }
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $result_data[$key][1] = $temp->serial_number;
                    $result_data[$key][2] = round($temp->total_quantity, 2);
                }
                break;
//----------------------------------------------------            
            case 'purchase_order':
                $head[0] = 'SUPPLIER NAME';
                $head[1] = 'MOBILE';
                $head[2] = 'DELIVERY LOCATION';
                $head[3] = 'ORDER BY';
                $head[4] = 'TOTAL QUANTITY';
                $head[5] = 'PENDING QUANTITY';
                
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =PurchaseOrder::find($delete_module);
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */
                
                if (Input::get('expected_date') != '') {
                    $purchase_orders = PurchaseOrder::where('order_status', '=', 'completed')->where('created_at','like',Input::get('expected_date').'%')->orderBy('created_at', 'desc')
                            ->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')
                            ->Paginate(20);
                } else {
                    $purchase_orders = PurchaseOrder::where('order_status', '=', 'completed')->orderBy('created_at', 'desc')
                            ->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')
                            ->Paginate(20);
                }

                $result_temp = $this->quantity_calculation($purchase_orders);
                
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $result_data[$key][1] = $temp['customer']->phone_number1;
                    
                    if ($temp->delivery_location_id != 0) {
                        foreach ($delivery_locations as $location) {
                            if ($location->id == $temp->delivery_location_id) {
                                $result_data[$key][2] = $location->area_name;
                            }
                        }
                    } else {
                        $result_data[$key][2] = $temp->other_location;
                    }
                    
                    $result_data[$key][3] = $temp['user']->first_name;
                    $result_data[$key][4] = round($temp->total_quantity, 2);
                    $result_data[$key][5] = round($temp->pending_quantity, 2);
                }
                
                break;
//----------------------------------------------------            
            case 'purchase_advice':
                $head[0] = 'DATE';
                $head[1] = 'TALLY NAME';
                $head[2] = 'VECHILE NUMBER';
                $head[3] = 'QUANTITY';
                $head[4] = 'SERIAL NUMBER';
                
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =PurchaseAdvise::find($delete_module);
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */
                
                if (Input::get('expected_date') != '') {
                    $purchase_advise = PurchaseAdvise::with('supplier', 'purchase_products')
                                    ->where('created_at','like',Input::get('expected_date').'%')
                                    ->where('advice_status', '=', 'delivered')
                                    ->orderBy('created_at', 'desc')->paginate(20);
                } else {
                    $purchase_advise = PurchaseAdvise::with('supplier', 'purchase_products')
                                    ->where('advice_status', '=', 'delivered')
                                    ->orderBy('created_at', 'desc')->paginate(20);
                }
                $result_temp = $this->checkpending_quantity($purchase_advise);
                
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $result_data[$key][0] =  date("F jS, Y", strtotime($temp->purchase_advice_date));
                    if ($temp['supplier']->tally_name != '')
                        $result_data[$key][1] = $temp['supplier']->tally_name;
                    else
                        $result_data[$key][1] = $temp['supplier']->owner_name;
                    $result_data[$key][2] = $temp->vehicle_number;
                    $result_data[$key][3] = round($temp->total_quantity, 2);
                    $result_data[$key][4] = $temp->serial_number;
                }
                
                break;
//----------------------------------------------------            
            case 'purchase_challan':
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'BILL NUMBER';
                $head[3] = 'BILL DATE';
                $head[4] = 'TOTAL QUANTITY';
                
                /*
                 * Delete selected inquiries.
                 */
                if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $inqiry_obj =PurchaseChallan::find($delete_module);
                        $inqiry_obj->delete();
                    }
                }
                /*
                 * Delete selected inquiries end.
                 */
                
                if (Input::get('expected_date') != '') {
                $result_temp = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->where('created_at','like',Input::get('expected_date').'%')
                    ->where('order_status', 'completed')
                    ->orderBy('created_at', 'desc')
                    ->Paginate(20);
                } else {
                $result_temp = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
                    ->where('order_status', 'completed')
                    ->orderBy('created_at', 'desc')
                    ->Paginate(20);
                }
                 foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    
                    if ($temp['supplier']->tally_name != '')
                        $result_data[$key][0] = $temp['supplier']->tally_name;
                    else
                        $result_data[$key][0] = $temp['supplier']->owner_name;
                    
                    $result_data[$key][1] = $temp->serial_number;
                    $result_data[$key][2] = $temp->bill_number;
                    $result_data[$key][3] = date("F jS, Y", strtotime($temp['purchase_advice']->purchase_advice_date));
                     $total_qty = 0;
                    foreach ($temp['all_purchase_products'] as $pc) {
                        if ($pc->unit_id == 1) {
                            $total_qty += $pc->quantity;
                        }
                        if ($pc->unit_id == 2) {
                            $total_qty += ($pc->quantity * $pc['purchase_product_details']->weight);
                        }
                        if ($pc->unit_id == 3) {
                            $total_qty += (($pc->quantity / $pc['purchase_product_details']->standard_length ) * $pc['purchase_product_details']->weight);
                        }
                    }
                    $result_data[$key][4] = round($temp['all_purchase_products']->sum('quantity'), 2);
                    
                }
                break;
//----------------------------------------------------            
        }


//        if (isset($result_temp)) {
            $result_temp->setPath('bulk-delete');
//        }
        $bulk_searched_result = 'bulk_searched_result';
//
        return view('bulk_delete', compact('result_data', 'result_temp', 'bulk_searched_result', 'head', 'module','expected_date', 'tr_id'));
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
    public function delete_selected() {
        echo'<pre>';
        print_r(Input::all());
        echo'</pre>';
        exit;
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
     * calculate the pending quantity and total quantity
     */

    function checkpending_quantity($delivery_orders) {
        $all_del_orders = array();
        $pending_orders = array();

        if (count($delivery_orders) > 0) {

            foreach ($delivery_orders as $key => $del_order) {
                $delivery_order_quantity = 0;
                $delivery_order_present_shipping = 0;
                if (count($del_order['delivery_product']) > 0) {
                    foreach ($del_order['delivery_product'] as $popk => $popv) {
                        $product_size = ProductSubCategory::find($popv->product_category_id);
                        if ($popv->unit_id == 1) {
                            $delivery_order_quantity = $delivery_order_quantity + $popv->quantity;
                            $delivery_order_present_shipping = $delivery_order_present_shipping + $popv->present_shipping;
                        }
                        if ($popv->unit_id == 2) {
                            $delivery_order_quantity = $delivery_order_quantity + ($popv->quantity * $product_size->weight);
                            $delivery_order_present_shipping = $delivery_order_present_shipping + ($popv->present_shipping * $product_size->weight);
                        }
                        if ($popv->unit_id == 3) {
                            $delivery_order_quantity = $delivery_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                            $delivery_order_present_shipping = $delivery_order_present_shipping + (($popv->present_shipping / $product_size->standard_length ) * $product_size->weight);
                        }
                    }
                }
                $delivery_orders[$key]['total_quantity'] = $delivery_order_quantity;
                $delivery_orders[$key]['present_shipping'] = $delivery_order_present_shipping;
            }
        }
        return $delivery_orders;
    }
     function quantity_calculation($purchase_orders) {

        foreach ($purchase_orders as $key => $order) {
            $purchase_order_quantity = 0;
            $purchase_order_advise_quantity = 0;
            $purchase_order_advise_products = PurchaseProducts::where('from', '=', $order->id)->get();

            if (count($purchase_order_advise_products) > 0) {
                foreach ($purchase_order_advise_products as $poapk => $poapv) {
                    $product_size = ProductSubCategory::find($poapv->product_category_id);
                    if ($poapv->unit_id == 1) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity;
                    }
                    if ($poapv->unit_id == 2) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity * $product_size->weight;
                    }
                    if ($poapv->unit_id == 3) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + ($poapv->quantity / $product_size->standard_length ) * $product_size->weight;
                    }
                }
            }

            if (count($order['purchase_products']) > 0) {
                foreach ($order['purchase_products'] as $popk => $popv) {
                    $product_size = ProductSubCategory::find($popv->product_category_id);
                    if ($popv->unit_id == 1) {
                        $purchase_order_quantity = $purchase_order_quantity + $popv->quantity;
                    }
                    if ($popv->unit_id == 2) {
                        $purchase_order_quantity = $purchase_order_quantity + ($popv->quantity * $product_size->weight);
                    }
                    if ($popv->unit_id == 3) {
                        $purchase_order_quantity = $purchase_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                    }
                }
            }

            if ($purchase_order_advise_quantity >= $purchase_order_quantity) {
                $purchase_orders[$key]['pending_quantity'] = 0;
            } else {
                $purchase_orders[$key]['pending_quantity'] = ($purchase_order_quantity - $purchase_order_advise_quantity);
            }
            $purchase_orders[$key]['total_quantity'] = $purchase_order_quantity;
        }
        return $purchase_orders;
    }

}
