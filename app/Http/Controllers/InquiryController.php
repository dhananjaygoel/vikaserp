<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use Input;
use App\Units;
use App\DeliveryLocation;
use App\Inquiry;
use App\InquiryProducts;
use App\ProductCategory;
use DB;
use Auth;
use App\Http\Requests\InquiryRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Hash;
use App\ProductSubCategory;
use App\Order;
use App\AllOrderProducts;

class InquiryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if ((isset($_GET['inquiry_filter'])) && $_GET['inquiry_filter'] != '') {
            $inquiries = Inquiry::where('inquiry_status', '=', $_GET['inquiry_filter'])
                            ->with('customer', 'delivery_location', 'inquiry_products')->orderBy('created_at', 'desc')->Paginate(5);
        } else {
            $inquiries = Inquiry::with('customer', 'delivery_location', 'inquiry_products')->orderBy('created_at', 'desc')->Paginate(5);
        }
        $inquiries->setPath('inquiry');
        return view('inquiry', compact('inquiries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        return view('add_inquiry', compact('units', 'delivery_locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(InquiryRequest $request) {
        $input_data = Input::all();
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->with('flash_message', 'Please insert product details');
        }
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                $customers = new Customer();
                $customers->owner_name = $input_data['customer_name'];
                $customers->contact_person = $input_data['contact_person'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
                $customers->save();
                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $add_delivery_location = DeliveryLocation::create([
                        'area_name' => $input_data['other_location_name'],
                        'status' => 'pending'
            ]);
            $location_id = DB::getPdo()->lastInsertId();
            $add_inquiry_array = [
                'customer_id' => $customer_id,
                'created_by' => Auth::id(),
                'delivery_location_id' => $location_id,
                'vat_percentage' => $input_data['vat_percentage'],
//                'expected_delivery_date' => date_format(date_create($input_data['date']), 'Y-m-d'),
//                'expected_delivery_date' => date('Y-m-d', strtotime($input_data['date'])),
                'expected_delivery_date' => date('Y-m-d', strtotime($input_data['date'])),
                'remarks' => $input_data['inquiry_remark'],
                'inquiry_status' => "Pending",
                'other_location' => $input_data['other_location_name']
            ];
        } else {
            $add_inquiry_array = [
                'customer_id' => $customer_id,
                'created_by' => Auth::id(),
                'delivery_location_id' => $input_data['add_inquiry_location'],
                'vat_percentage' => $input_data['vat_percentage'],
                'expected_delivery_date' => date('Y-m-d', strtotime($input_data['date'])),
                'remarks' => $input_data['inquiry_remark'],
                'inquiry_status' => "Pending"
            ];
        }
        $add_inquiry = Inquiry::create($add_inquiry_array);
        $inquiry_id = DB::getPdo()->lastInsertId();
        $inquiry_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $inquiry_products = [
                    'inquiry_id' => $inquiry_id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_inquiry_products = InquiryProducts::create($inquiry_products);
            }
        }
        return redirect('inquiry')->with('flash_success_message', 'Inquiry details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $inquiry = Inquiry::where('id', '=', $id)->with('inquiry_products.unit', 'inquiry_products.product_category', 'customer')->first();
        return view('inquiry_details', compact('inquiry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $inquiry = Inquiry::where('id', '=', $id)->with('inquiry_products.unit', 'inquiry_products.product_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        return view('edit_inquiry', compact('inquiry', 'delivery_location', 'units'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, InquiryRequest $request) {
        $input_data = Input::all();
        $i = 0;
        $j = count($input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->with('flash_message', 'Please insert product details');
        }
        $customers = Customer::find($input_data['customer_id']);
        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                $customers->owner_name = $input_data['customer_name'];
                $customers->contact_person = $input_data['contact_person'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
                $customers->save();
                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }
        $inquiry = Inquiry::find($id);
        $update_inquiry = $inquiry->update([
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_inquiry_location'],
            'vat_percentage' => $input_data['vat_percentage'],
            'expected_delivery_date' => date_format(date_create($input_data['date']), 'Y-m-d'),
            'remarks' => $input_data['inquiry_remark'],
            'inquiry_status' => "Pending"
        ]);
        $inquiry_products = array();

        $delete_old_inquiry_products = InquiryProducts::where('inquiry_id', '=', $id)->delete();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $inquiry_products = [
                    'inquiry_id' => $id,
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_inquiry_products = InquiryProducts::create($inquiry_products);
            }
        }
        return redirect('inquiry')->with('flash_success_message', 'Inquiry details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 ) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_inquiry = Inquiry::find($id)->delete();
            $delete_inquiry_products = InquiryProducts::where('inquiry_id', '=', $id)->delete();
            return redirect('inquiry')->with('flash_message', 'Inquiry details successfully deleted.');
        } else {
            return redirect('inquiry')->with('flash_message', 'Please enter a correct password.');
        }
    }

    public function fetch_existing_customer() {
        $term = '%' . Input::get('term') . '%';
        $customers = Customer::where('owner_name', 'like', $term)->where('customer_status', '=', 'permanent')->get();
        if (count($customers) > 0) {
            foreach ($customers as $customer) {
                $data_array[] = [
                    'value' => $customer->owner_name,
                    'id' => $customer->id
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No Customers',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    public function fetch_products() {
        $term = '%' . Input::get('term') . '%';
        $products = ProductCategory::with(array('product_sub_categories' => function($query) use ($term) {
                        $query->where('alias_name', 'like', $term)->get();
                    })
                )->get();

        if (count($products) > 0) {
            foreach ($products as $product) {
                if (!empty($product['product_sub_categories'])) {
                    foreach ($product['product_sub_categories'] as $product_sub_cat) {
                        $data_array[] = [
                            'value' => $product_sub_cat->alias_name,
                            'id' => $product->id,
                            'product_price' => $product->price
                        ];
                    }
                }
            }
        } else {
            $data_array[] = [
                'value' => 'No Products',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    public function store_price() {
        $input_data = Input::all();
        $update_price = InquiryProducts::where('id', '=', $input_data['id'])->update(['price' => $input_data['updated_price']]);
    }

    public function get_product_sub_category() {

        $product = ProductCategory::with('product_sub_categories')->where('id', Input::get('added_product_id'))->first();
        $units = Units::all();
           
        $unit = array();
        $i = 0;
        foreach ($units as $u){
           $unit[$i]['id'] =  $u->id;
           $unit[$i]['unit_name'] =  $u->unit_name;
           $i++;
        }

        $prod = array();
        $i = 0;
        $prod[$i]['id'] = $product->id;
        $prod[$i]['unit_id'] = $product['product_sub_categories'][0]->unit_id;
        $prod[$i]['price'] = $product->price;

        
        echo json_encode(array('prod' => $prod, 'unit'=> $unit));
        exit;
    }
    /*
     * Inquiery to Order
     * place order
     */
    function place_order($id){  
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $inquiry = Inquiry::where('id', '=', $id)->where(['inquiry_status' => 'Completed'])->get();
        if(count($inquiry)>0){
            return redirect('inquiry')->with('flash_message', 'Please select other inquiry, order is generated for this inquiry.');
        }
        
        $inquiry = Inquiry::where('id', '=', $id)->with('inquiry_products.unit', 'inquiry_products.product_category', 'customer')->first();
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        $customers = Customer::all();
        return view('place_order', compact('inquiry','customers' , 'delivery_location', 'units'));        
    }
    
    function store_place_order($id,InquiryRequest $request){
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $input_data = Input::all();
        $i = 0;
        $j = count($input_data['product']);
//        echo $input_data['estimated_date'];exit;
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }

        if ($i == $j) {
            return Redirect::back()->with('flash_message', 'Please insert product details');
        }

        if ($input_data['customer_status'] == "new_customer") {
            $validator = Validator::make($input_data, Customer::$new_customer_inquiry_rules);
            if ($validator->passes()) {
                $customers = new Customer();
                $customers->owner_name = $input_data['customer_name'];
                $customers->contact_person = $input_data['contact_person'];
                $customers->phone_number1 = $input_data['mobile_number'];
                $customers->credit_period = $input_data['credit_period'];
                $customers->customer_status = 'pending';
                $customers->save();
                $customer_id = $customers->id;
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $validator = Validator::make($input_data, Customer::$existing_customer_inquiry_rules);
            if ($validator->passes()) {
                $customer_id = $input_data['autocomplete_customer_id'];
            } else {
                $error_msg = $validator->messages();
                return Redirect::back()->withInput()->withErrors($validator);
            }
        }

        if ($input_data['status'] == 'warehouse') {
            $order_status = 'warehouse';
            $supplier_id = 0;
        }
        if ($input_data['status'] == 'supplier') {
            $order_status = 'supplier';
            $supplier_id = $input_data['supplier_id'];
        }
        if ($input_data['vat_status'] == 'include_vat') {
            $vat_price = '';
        }
        if ($input_data['vat_status'] == 'exclude_vat') {
            $vat_price = $input_data['vat_percentage'];
        }
//        echo 'other location '.$input_data['location'];exit;
        $order = new Order();
        $order->order_source = $order_status;
        $order->supplier_id = $supplier_id;
        $order->customer_id = $customer_id;
        $order->created_by = Auth::id();
        $order->delivery_location_id = $input_data['add_inquiry_location'];
        $order->vat_percentage = $vat_price;
//        $order->estimated_delivery_date = date_format(date_create($input_data['estimated_date']), 'Y-m-d');
        $order->expected_delivery_date = date_format(date_create($input_data['date']), 'Y-m-d');
        $order->remarks = $input_data['inquiry_remark'];
        $order->order_status = "Pending";
        if (isset($input_data['other_location_name']) && ($input_data['other_location_name'] != "")) {
            $order->other_location = $input_data['other_location_name'];
        }
        $order->save();

        $order_id = DB::getPdo()->lastInsertId();
        $order_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $order_products = [
                    'order_id' => $order_id,
                    'order_type' => 'order',
                    'product_category_id' => $product_data['id'],
                    'unit_id' => $product_data['units'],
                    'quantity' => $product_data['quantity'],
                    'price' => $product_data['price'],
                    'remarks' => $product_data['remark'],
                ];
                $add_order_products = AllOrderProducts::create($order_products);
            }
        }
        Inquiry::where('id', '=', $id)->update(['inquiry_status' => 'Completed']);        
        return redirect('inquiry')->with('flash_success_message', 'One Order successfully generated for Inquiry.');
        
    }
    
    
    /*
     * Price calculation
     */
    function calculate_price($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        exit;
    }
}
