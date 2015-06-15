<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Customer;
use App\DeliveryLocation;
use App\Http\Requests\StoreCustomer;
use Input;
use App\User;
use Auth;
use Hash;
use Redirect;

class PendingCustomerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $customers = Customer::where('customer_status', '=', 'pending')->paginate(10);

        return View::make('pending_customers', array('customers' => $customers));
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

        $customer = Customer::where('id', '=', $id)->first();
        if (count($customer) < 1) {
            return redirect('pending_customers/')->with('error', 'Trying to access an invalid customer');
        }

        $managers = User::where('role_id', '=', 1)->get();

        $locations = DeliveryLocation::all();

        return View::make('add_pendingcustomers', array('customer' => $customer, 'locations' => $locations, 'managers' => $managers));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $customer = Customer::find($id);
        $locations = DeliveryLocation::all();
        return View::make('edit_pending_customers', array('customer' => $customer, 'locations' => $locations));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $customer = Customer::find($id);
        if (count($customer) < 1) {
            return redirect('pending_customers/')->with('error', 'Trying to access an invalid customer');
        }

        if (Input::has('owner_name')) {
            $customer->owner_name = Input::get('owner_name');
        }
        if (Input::has('contact_person')) {
            $customer->contact_person = Input::get('contact_person');
        }
        if (Input::has('phone_number1')) {
            $customer->phone_number1 = Input::get('phone_number1');
        }

        if (Input::has('delivery_location')) {
            $customer->delivery_location_id = Input::get('delivery_location');
        }

        $customer->customer_status = 'pending';

        if ($customer->save()) {
            return redirect('pending_customers')->with('success', 'Customer details updated successfully');
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
            return Redirect::to('pending_customers')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $customer = Customer::find($id);
            $customer->delete();
            return Redirect::to('pending_customers')->with('success', 'Pending customer Successfully deleted');
        } else {
            return Redirect::to('pending_customers')->with('error', 'Invalid password');
        }
    }

    public function add_pending_customers(StoreCustomer $request, $id) {

        $customer = Customer::find($id);
        if (count($customer) < 1) {
            return redirect('pending_customers/')->with('error', 'Trying to access an invalid customer');
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

        $customer->customer_status = 'permanent';


        if ($customer->save()) {
            return redirect('customers')->with('success', 'Customer successfully upgraded as permanent customer');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

}
