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
use DB;
use Auth;

class InquiryController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $inquiries = Inquiry::with('customer', 'delivery_location')->Paginate(1);
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
//            $customers->mobile_number = $input_data['mobile_number'];
            $customers->credit_period = $input_data['credit_period'];
            $customers->customer_status = 'pending';
            $customers->save();
            $add_inquiry = Inquiry::create([
                        'customer_id' => $customers->id,
                        'created_by' => Auth::id(),
                        'delivery_location_id' => $input_data['add_inquiry_location'],
                        'vat_percentage' => $input_data['vat_percentage'],
                        'estimated_delivery_date' => date_format(date_create($input_data['date']), 'Y-m-d'),
                        'remarks' => $input_data['inquiry_remark'],
                        'inquiry_status' => "Pending"
            ]);
        } elseif ($input_data['customer_status'] == "existing_customer") {
            $customer_id = $input_data['customer_id'];
            $add_inquiry = Inquiry::create([
                        'customer_id' => $customer_id,
                        'created_by' => Auth::id(),
                        'delivery_location_id' => $input_data['add_inquiry_location'],
                        'vat_percentage' => $input_data['vat_percentage'],
                        'estimated_delivery_date' => date_format(date_create($input_data['date']), 'Y-m-d'),
                        'remarks' => $input_data['inquiry_remark'],
                        'inquiry_status' => "Pending"
            ]);
        }
        $inquiry_id = DB::getPdo()->lastInsertId();
        $inquiry_products = array();
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] != "") {
                $inquiry_products = [
                    'inquiry_id' => $inquiry_id,
//                    'product_category_id' => $product_data['name'],
                    'product_category_id' => 1,
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
        $inquiry = Inquiry::find($id)->with('inquiry_products.unit', 'customer')->get();
        echo '<pre>';
        print_r($inquiry->toArray());
        echo '</pre>';
        exit;
        return view('inquiry_details', compact('inquiry'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        echo '<pre>';
        print_r($id);
        echo '</pre>';
        exit;
        $inquiry = Inquiry::find($id)->with('inquiry_products', 'customer');
        $units = Units::all();
        $delivery_location = DeliveryLocation::all();
        echo '<pre>';
        print_r($inquiry);
        echo '</pre>';
        exit;
        return view('edit_inquiry', compact('inquiry', 'delivery_locations', 'units'));
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

    public function fetch_existing_customer() {
        $term = '%' . Input::get('term') . '%';
        $customers = Customer::where('owner_name', 'like', $term)->get();
        $data_array = array();
        foreach ($customers as $customer) {
            array_push($data_array, [
                'label' => $customer->owner_name,
                'value' => $customer->id
            ]);
        }
//        foreach ($customers as $customer) {
//            array_push($data_array, [
//                '<li value="' . $customer->id . '">' . $customer->owner_name . '</li>',
//            ]);
//        }
        echo json_encode(array('data_array' => $data_array));
    }

}
