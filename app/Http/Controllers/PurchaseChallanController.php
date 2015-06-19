<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseChallan;
use App\PurchaseProducts;
use App\Http\Requests\PurchaseChallanRequest;
use Input;
use Closure;
use Illuminate\Database\Eloquent\Model;
use DB;
use Hash;
use Auth;
use App\Quotation;

class PurchaseChallanController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
        $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier')->Paginate(10);
        $purchase_challan->setPath('purchase_challan');
        return view('purchase_challan', compact('purchase_challan'));
    }

    /**
     * Show the form for creating a new resource.use Closure;
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
    public function store(PurchaseChallanRequest $request) {

        $add_challan = PurchaseChallan::create([
                    'expected_delivery_date' => $request->input('bill_date'),
                    'purchase_advice_id' => $request->input('purchase_advice_id'),
                    'purchase_order_id' => $request->input('purchase_order_id'),
                    'delivery_location_id' => $request->input('delivery_location_id'),
                    'serial_number' => $request->input('serial_no'),
                    'supplier_id' => $request->input('supplier_id'),
                    'created_by' => $request->input('created_by'),
                    'vehicle_number' => $request->input('vehicle_number'),
                    'freight' => $request->input('Freight'),
                    'unloaded_by' => $request->input('unloading'),
                    'unloading' => $request->input('loadedby'),
                    'labours' => $request->input('labour'),
                    'bill_number' => $request->input('billno'),
                    'remarks' => $request->input('remark'),
                    'order_status' => 'pending',
                    'vat_percentage' => $request->input('vat_percentage')
        ]);

        $challan_id = DB::getPdo()->lastInsertId();
        $input_data = Input::all();
        $order_products = array();

        foreach ($input_data['product'] as $product_data) {
//            if ($product_data['name'] != "") {
            $order_products = [
                'purchase_order_id' => $challan_id,
                'order_type' => 'purchase_challan',
                'product_category_id' => $product_data['product_category_id'],
                'unit_id' => $product_data['unit_id'],
                'quantity' => $product_data['quantity'],
                'present_shipping' => $product_data['present_shipping'],
                'price' => $product_data['price'],
            ];

            $add_order_products = PurchaseProducts::create($order_products);
//            }
        }

        return redirect('purchase_challan')->with('success', 'Challan details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'purchase_product.product_sub_category', 'purchase_product.unit')->where('id', $id)->first();
        return view('view_purchase_challan', compact('purchase_challan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {

        $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'purchase_product.product_sub_category', 'purchase_product.unit')->where('id', $id)->first();
        return view('edit_purchase_challan', compact('purchase_challan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, PurchaseChallanRequest $request) {

        $challan_data = Input::all();
        $purchase = array(
            'vehicle_number' => $request->input('vehicle_number'),
            'freight' => $request->input('Freight'),
            'unloaded_by' => $request->input('loadedby'),
            'unloading' => $request->input('unloading'),
            'labours' => $request->input('labour'),
            'bill_number' => $request->input('billno'),
            'remarks' => $request->input('remarks'),
            'remarks' => $request->input('remarks'),
            'discount' => $request->input('discount')
        );

        PurchaseChallan::where('id', $id)
                ->update($purchase);

        PurchaseProducts::where('purchase_order_id', $id)
                ->where('order_type', 'purchase_challan')
                ->delete();

        $input_data = Input::all();

        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            $order_products = [
                'purchase_order_id' => $id,
                'order_type' => 'purchase_challan',
                'product_category_id' => $product_data['product_category_id'],
                'unit_id' => $product_data['unit_id'],
                'quantity' => $product_data['quantity'],
                'present_shipping' => $product_data['present_shipping'],
                'price' => $product_data['price'],
            ];

            $add_order_products = PurchaseProducts::create($order_products);
        }

        return redirect('purchase_challan')->with('success', 'Challan details successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_challan = PurchaseChallan::find($id)->delete();
            return redirect('purchase_challan')->with('flash_success_message', 'Purchase challan details successfully deleted.');
        } else
            return redirect('purchase_challan')->with('flash_message', 'Please enter a correct password');
    }

}
