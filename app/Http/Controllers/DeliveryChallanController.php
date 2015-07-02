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
use App\CustomerProductDifference;
use App\Units;
use App\DeliveryLocation;

class DeliveryChallanController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if ((isset($_GET['status_filter'])) && $_GET['status_filter'] != '') {
            $allorders = DeliveryChallan::where('challan_status', '=', $_GET['status_filter'])->with('customer', 'all_order_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(10);
        } else {
            $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'all_order_products', 'delivery_order')->orderBy('created_at', 'desc')->Paginate(10);
        }

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
        $price_delivery_order = $this->calculate_price($allorder);
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        return View::make('edit_delivery_challan', compact('allorder', 'price_delivery_order', 'units', 'delivery_locations'));
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
            if (isset($input_data['billno'])) {
                $delivery_challan->update([
                    "bill_number" => $input_data['billno']]);
            }
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
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
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
            if ($gen_dc != 1 && $pending_quantity == 0 && $order->order_status != 'completed') {

                Order::where('id', $order->id)->update(array(
                    'order_status' => "completed"
                ));
            }
        }
    }

    function calculate_price($delivery_data) {

        $product_rates = array();
        foreach ($delivery_data->all_order_products as $product) {

            $sub_product = \App\ProductSubCategory::where('product_category_id', $product->product_category_id)->first();
            $product_category = \App\ProductCategory::where('id', $product->product_category_id)->first();
            $user_id = $delivery_data['customer']->id;
            $users_set_price_product = CustomerProductDifference::where('product_category_id', $product->product_category_id)
                            ->where('customer_id', $user_id)->first();
            $total_rate = $product_category->price;
            $users_set_price = 0;
            if (count($users_set_price_product) > 0) {
                $total_rate = $total_rate + $users_set_price_product->difference_amount;
                $users_set_price = $users_set_price_product->difference_amount;
            }
            if ($sub_product->difference > 0) {
                $total_rate = $total_rate + $sub_product->difference;
            }

            $product_rate = array();
            $product_rate["product_id"] = $product->product_category_id;
            $product_rate["product_price"] = $product_category->price;
            $product_rate["difference"] = $sub_product->difference;
            $product_rate["difference_amount"] = $users_set_price;
            $product_rate["total_rate"] = $total_rate;
            array_push($product_rates, $product_rate);
        }
        return $product_rates;
    }

}
