<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\DeliveryChallan;
use App\Order;
use App\AllOrderProducts;
use App\DeliveryOrder;
use Input;
use Validator;
use Redirect;
use App\User;
use Auth;
use Hash;

class DeliveryChallanController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'all_order_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(10);

        $allorders->setPath('delivery_challan');
        return view('delivery_challan', compact('allorders'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        $allorder = DeliveryChallan::where('id', '=', $id)
                        ->where('challan_status', '=', 'pending')
                        ->with('all_order_products.unit', 'all_order_products.product_category', 'customer', 'delivery_order')->first();

        return View::make('delivery_challan_details', compact('allorder'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $allorder = DeliveryChallan::where('id', '=', $id)
                        ->where('challan_status', '=', 'pending')
                        ->with('all_order_products.unit', 'all_order_products.product_category', 'customer', 'delivery_order')->first();

        return View::make('edit_delivery_challan', compact('allorder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $input_data = Input::all();
        $validator = Validator::make($input_data, DeliveryOrder::$order_to_delivery_challan_rules);
        if ($validator->passes()) {


            $i = 0;
            $j = count($input_data['product']);

            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] == "") {
                    $i++;
                }
            }

            if ($i == $j) {
                return Redirect::back()->with('validation_message', 'Please enter at least one product details');
            }

            $delivery_challan = DeliveryChallan::find($id);
            $update_challan = $delivery_challan->update([
                'bill_number' => $input_data['billno'],
                'discount' => $input_data['discount'],
                'freight' => $input_data['freight'],
                'loading_charge' => $input_data['loading'],
                'loaded_by' => $input_data['loadedby'],
                'labours' => $input_data['labour'],
                'vat_percentage' => $input_data['vat_percentage'],
                'grand_price' => $input_data['grand_total'],
                'remarks' => $input_data['challan_remark'],
                'challan_status' => "Pending"
            ]);

            $delete_old_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
            if ($j != 0) {
                $order_products = array();
                foreach ($input_data['product'] as $product_data) {
                    if ($product_data['name'] != "") {
                        $order_products = [
                            'order_id' => $id,
                            'order_type' => 'delivery_challan',
                            'product_category_id' => $product_data['id'],
                            'unit_id' => $product_data['units'],
                            'actual_pieces' => $product_data['actual_pieces'],
                            'quantity' => $product_data['quantity'],
                            'present_shipping' => $product_data['present_shipping'],
                            'price' => $product_data['price']
                        ];
                        $add_order_products = AllOrderProducts::create($order_products);
                    }
                }
            }
            return redirect('delivery_challan')->with('flash_message', 'One Delivery Challan is successfuly updated.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 ) {
            return Redirect::to('delivery_challan')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');

        if ($password == '') {
            return Redirect::to('delivery_challan')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {

            $order = DeliveryChallan::find($id);

            $all_order_products = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan');

            foreach ($all_order_products as $products) {
                $products->delete();
            }
            $order->delete();
            return redirect('delivery_challan')->with('flash_message', 'One record is deleted.');
        } else {
            return Redirect::back()->with('flash_message', 'Password entered is not valid.');
        }
    }

    //Generate Serial number and print Delivery Challan
    public function print_delivery_challan($id) {
        $serial_number_delivery_order = Input::get('serial_number');
//        $delivery_order_id = Input::get('delivery_order_id');
//        $current_date = date("M/y/m/");
//        $date_letter =  $date.$delivery_order_id. "/" . $id;
        $date_letter = $serial_number_delivery_order . "/" . $id;
        DeliveryChallan::where('id', $id)->update(array(
            'serial_number' => $date_letter,
            'challan_status' => "completed"
        ));
        $this->checkpending_quantity();

        return redirect('delivery_challan')->with('validation_message', 'Delivery order is successfuly printed.');
//        echo $date_letter;
    }

    function checkpending_quantity() {
        $allorders = Order::all();
        $allorder_new = [];
        foreach ($allorders as $order) {
            $delivery_orders = DeliveryOrder::where('order_id', $order->id)->get();

            $gen_dc = 1;
            $pending_quantity = 0;
            foreach ($delivery_orders as $del_order) {
                $delivery_challans = DeliveryChallan::where('delivery_order_id', $del_order->id)->get();
                foreach ($delivery_challans as $del_challan) {
                    $gen_dc = 0;
                    $all_order_products = AllOrderProducts::where('order_id', $order->id)->where('order_type', 'delivery_order')->get();
                    foreach ($all_order_products as $products) {
                        $p_qty = $products['quantity'] - $products['present_shipping'];
                        $pending_quantity = $pending_quantity + $p_qty;
                    }
                }
            }
//            echo '<br>pending quantity ' . $pending_quantity . ' order ' . $order->id;
            if ($gen_dc != 1 && $pending_quantity == 0 && $order->order_status !='completed') {
                
                Order::where('id', $order->id)->update(array(
                    'order_status' => "completed"
                ));
            }


//            
//            $allorders['total_pending_quantity_'.$order->id]=$pending_quantity;
        }
    }

}
