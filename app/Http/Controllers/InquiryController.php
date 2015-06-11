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

class InquiryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $inquiries = Inquiry::with('customer', 'delivery_location')->orderBy('created_at', 'desc')->Paginate(2);
        $inquiries->setPath('inquiry');
        return view('inquiry', compact('inquiries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        return view('add_inquiry', compact('units', 'delivery_locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $input_data = Input::all();
        if ($input_data['customer_status'] == "new_customer") {
            $customers = new Customer();
            $customers->owner_name = $input_data['customer_name'];
            $customers->contact_person = $input_data['contact_person'];
            $customers->phone_number1 = $input_data['mobile_number'];
            $customers->credit_period = $input_data['credit_period'];
            $customers->customer_status = 'pending';
            $customers->save();
            $customer_id = $customers->id;
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $customer_id = $input_data['customer_id'];
        }
        $add_inquiry = Inquiry::create([
                    'customer_id' => $customer_id,
                    'created_by' => Auth::id(),
                    'delivery_location_id' => $input_data['add_inquiry_location'],
                    'vat_percentage' => $input_data['vat_percentage'],
                    'estimated_delivery_date' => date_format(date_create($input_data['date']), 'Y-m-d'),
                    'remarks' => $input_data['inquiry_remark'],
                    'inquiry_status' => "Pending"
        ]);
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
        return redirect('inquiry/' . $inquiry_id . '/edit')->with('flash_message', 'Inquiry details successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
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
    public function update($id) {
        $input_data = Input::all();
        $customers = Customer::find($input_data['customer_id']);
        if ($input_data['customer_status'] == "new_customer") {
            $customers->owner_name = $input_data['customer_name'];
            $customers->contact_person = $input_data['contact_person'];
            $customers->phone_number1 = $input_data['phone_number'];
            $customers->credit_period = $input_data['credit_period'];
            $customers->customer_status = 'pending';
            $customers->save();
            $customer_id = $customers->id;
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $customer_id = $input_data['customer_id'];
        }
        $inquiry = Inquiry::find($id);
        $update_inquiry = $inquiry->update([
            'customer_id' => $customer_id,
            'created_by' => Auth::id(),
            'delivery_location_id' => $input_data['add_inquiry_location'],
            'vat_percentage' => $input_data['vat_percentage'],
            'estimated_delivery_date' => date_format(date_create($input_data['date']), 'Y-m-d'),
            'remarks' => $input_data['inquiry_remark'],
            'inquiry_status' => "Pending"
        ]);
        $inquiry_id = DB::getPdo()->lastInsertId();
//        $inquiry_products = array();
//        foreach ($input_data['product'] as $product_data) {
//            if ($product_data['name'] != "") {
//                $inquiry_products = [
//                    'inquiry_id' => $inquiry_id,
////                    'product_category_id' => $product_data['name'],
//                    'product_category_id' => 1,
//                    'unit_id' => $product_data['units'],
//                    'quantity' => $product_data['quantity'],
//                    'price' => $product_data['price'],
//                    'remarks' => $product_data['remark'],
//                ];
//                $add_inquiry_products = InquiryProducts::update($inquiry_products);
//            }
//        }
        return redirect('inquiry/' . $inquiry_id . '/edit')->with('flash_message', 'Inquiry details successfully modified.');
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

    public function fetch_existing_customer() {
        $term = '%' . Input::get('term') . '%';
        $customers = Customer::where('owner_name', 'like', $term)->get();
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
        $products = ProductCategory::where('product_category_name', 'like', $term)->get();
        if (count($products) > 0) {
            foreach ($products as $product) {
                $data_array[] = [
                    'value' => $product->product_category_name,
                    'id' => $product->id,
                    'product_price' => $product->price
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No Products',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

}
