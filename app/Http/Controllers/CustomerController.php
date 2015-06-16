<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomer;
use View;
use Hash;
use Auth;
use Redirect;
use App\User;
use App\DeliveryLocation;
use App\ProductCategory;
use App\CustomerProductDifference;
use App\Customer;
use Input;

class CustomerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $q = Customer::query();
        $q->where('customer_status', '=', 'permanent');
        if (Input::get('search') != '') {
            $q->where('owner_name', 'like', '%' . Input::get('search') . '%')
                    ->orWhere('company_name', 'like', '%' . Input::get('search') . '%');
        }

        $customers = $q->paginate(10);
        $customers->setPath('customers');
        return View::make('customers', array('customers' => $customers));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {

        $managers = User::where('role_id', '=', 1)->get();

        $locations = DeliveryLocation::all();

        $product_category = ProductCategory::all();

        return View::make('add_customers', array('managers' => $managers, 'locations' => $locations, 'product_category' => $product_category));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreCustomer $request) {

        $customer = new Customer();

        $customer->owner_name = Input::get('owner_name');
        if (Input::has('company_name')) {
            $customer->company_name = Input::get('company_name');
        }
        if (Input::has('contact_person')) {
            $customer->contact_person = Input::get('contact_person');
        }
        if (Input::has('address1')) {
            $customer->address1 = Input::get('address1');
        }
        if (Input::has('address2')) {
            $customer->address2 = Input::get('address2');
        }
        $customer->city = Input::get('city');
        $customer->state = Input::get('state');
        if (Input::has('zip')) {
            $customer->zip = Input::get('zip');
        }
        if (Input::has('email')) {
            $customer->email = Input::get('email');
        }

        $customer->tally_name = Input::get('tally_name');
        $customer->tally_category = Input::get('tally_category');
        $customer->tally_sub_category = Input::get('tally_sub_category');
        $customer->phone_number1 = Input::get('phone_number1');

        if (Input::has('vat_tin_number')) {
            $customer->vat_tin_number = Input::get('vat_tin_number');
        }
        if (Input::has('excise_number')) {
            $customer->excise_number = Input::get('excise_number');
        }
        if (Input::has('username')) {
            $customer->username = Input::get('username');
        }
        if (Input::has('credit_period')) {
            $customer->credit_period = Input::get('credit_period');
        }
        if (Input::has('relationship_manager')) {
            $customer->relationship_manager = Input::get('relationship_manager');
        }

        $customer->delivery_location_id = Input::get('delivery_location');

        if (Input::has('password') && Input::get('password') != '') {
            $customer->password = Hash::make(Input::get('relationship_manager'));
        }

        $customer->customer_status = 'permanent';

        if ($customer->save()) {

            $product_category_id = Input::get('product_category_id');
            if (isset($product_category_id)) {
                foreach ($product_category_id as $key => $value) {
                    if (Input::get('product_differrence')[$key] != '') {
                        $product_difference = new CustomerProductDifference();
                        $product_difference->product_category_id = $value;
                        $product_difference->customer_id = $customer->id;
                        $product_difference->difference_amount = Input::get('product_differrence')[$key];
                        $product_difference->save();
                    }
                }
            }

            return redirect('customers')->with('success', 'Customer Succesfully added');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {

        $customer = Customer::with('deliverylocation', 'manager')->find($id);

        return View::make('customer_details', array('customer' => $customer));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $customer = Customer::where('id', '=', $id)->with('customerproduct')->first();
        if (count($customer) < 1) {
            return redirect('customers/')->with('error', 'Trying to access an invalid customer');
        }

        $managers = User::where('role_id', '=', 1)->get();

        $locations = DeliveryLocation::all();

        $product_category = ProductCategory::all();

        return View::make('edit_customers', array('customer' => $customer, 'managers' => $managers, 'locations' => $locations, 'product_category' => $product_category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(StoreCustomer $request, $id) {
        $customer = Customer::find($id);
        if (count($customer) < 1) {
            return redirect('customers/')->with('error', 'Trying to access an invalid customer');
        }

        $customer->owner_name = Input::get('owner_name');
        if (Input::has('company_name')) {
            $customer->company_name = Input::get('company_name');
        }
        if (Input::has('contact_person')) {
            $customer->contact_person = Input::get('contact_person');
        }
        if (Input::has('address1')) {
            $customer->address1 = Input::get('address1');
        }
        if (Input::has('address2')) {
            $customer->address2 = Input::get('address2');
        }
        $customer->city = Input::get('city');
        $customer->state = Input::get('state');
        if (Input::has('zip')) {
            $customer->zip = Input::get('zip');
        }
        if (Input::has('email')) {
            $customer->email = Input::get('email');
        }

        $customer->tally_name = Input::get('tally_name');
        $customer->tally_category = Input::get('tally_category');
        $customer->tally_sub_category = Input::get('tally_sub_category');
        $customer->phone_number1 = Input::get('phone_number1');

        if (Input::has('vat_tin_number')) {
            $customer->vat_tin_number = Input::get('vat_tin_number');
        }
        if (Input::has('excise_number')) {
            $customer->excise_number = Input::get('excise_number');
        }
        if (Input::has('username')) {
            $customer->username = Input::get('username');
        }
        if (Input::has('credit_period')) {
            $customer->credit_period = Input::get('credit_period');
        }
        if (Input::has('relationship_manager')) {
            $customer->relationship_manager = Input::get('relationship_manager');
        }

        $customer->delivery_location_id = Input::get('delivery_location');

        if (Input::has('password') && Input::get('password') != '') {
            $customer->password = Hash::make(Input::get('relationship_manager'));
        }


        if ($customer->save()) {
            $product_category_id = Input::get('product_category_id');
            if (isset($product_category_id)) {
                foreach ($product_category_id as $key => $value) {
                    if (Input::get('product_differrence')[$key] != '') {
                        $product_difference = CustomerProductDifference::where('product_category_id', '=', $value)->first();
                        if (count($product_difference) > 0) {
                            $product_difference = $product_difference;
                        } else {
                            $product_difference = new CustomerProductDifference();
                        }
                        $product_difference->product_category_id = $value;
                        $product_difference->customer_id = $customer->id;
                        $product_difference->difference_amount = Input::get('product_differrence')[$key];
                        $product_difference->save();
                    } else {
                        $product_difference1 = CustomerProductDifference::where('product_category_id', '=', $value)->first();
                        if (count($product_difference1) > 0) {
                            $product_difference1->delete();
                        }
                    }
                }
            }
            
            return redirect('customers')->with('success', 'Customer details updated successfully');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('customers')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $customer = Customer::find($id);
            $customer->delete();
            return Redirect::to('customers')->with('success', 'Customer Successfully deleted');
        } else {
            return Redirect::to('customers')->with('error', 'Invalid password');
        }
    }

}
