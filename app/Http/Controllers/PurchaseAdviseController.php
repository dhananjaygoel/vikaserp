<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Customer;
use Input;
use Auth;
use App\PurchaseAdvise;
use App\PurchaseProducts;
use App\DeliveryLocation;
use DB;

class PurchaseAdviseController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return View::make('purchase_advise');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $customers = Customer::where('customer_status', '=', 'permanent')->get();
        
        $locations = DeliveryLocation::all();
        
        return View::make('add_purchase_advise', array('customers' => $customers, 'locations' => $locations));
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

    public function store_advise() {
        $input_data = Input::all();
//        echo '<pre>';
//        print_r($input_data);
//        echo '</pre>';
//        exit;
        $add_purchase_advice_array = [
            'supplier_id' => $input_data['supplier_id'],
            'created_by' => Auth::id(),
            'purchase_advice_date' => $input_data['bill_date'],
            'delivery_location_id' => $input_data['delivery_location_id'],
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => $input_data['expected_delivery_date'],
            'remarks' => $input_data['grand_remark'],
            'advice_status' => 'Pending',
            'vehicle_number' => $input_data['vehicle_number']
        ];
        $add_purchase_advice = PurchaseAdvise::create($add_purchase_advice_array);
        $purchase_advice_id = DB::getPdo()->lastInsertId();
        $purchase_advice_products = array();
        foreach ($input_data['product'] as $product_data) {
            if (isset($product_data['name']) || ($product_data['id'] != "")) {
                if ($product_data['present_shipping'] != "") {
                    $purchase_advice_products = [
                        'purchase_order_id_id' => $purchase_advice_id,
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'quantity' => $product_data['quantity'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark'],
                        'order_type' => 'purchase_advice',
                        'present_shipping' => $product_data['present_shipping']
                    ];
                } elseif ($product_data['present_shipping'] == "") {
                    $purchase_advice_products = [
                        'purchase_order_id_id' => $purchase_advice_id,
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'quantity' => $product_data['quantity'],
                        'price' => $product_data['price'],
                        'remarks' => $product_data['remark'],
                        'order_type' => 'purchase_advice',
                    ];
                }
                $add_purchase_advice_products = PurchaseProducts::create($purchase_advice_products);
            }
        }
    }

}
