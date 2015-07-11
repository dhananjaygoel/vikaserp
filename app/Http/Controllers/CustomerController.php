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
use App\URLAccess;
use App\States;
use App\City;
use Config;
use App\ProductType;

class CustomerController extends Controller {

    public function __construct() {
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
//
//        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
////            echo 'test 1';exit;
//            $this->middleware('admin_mw');            
//            
//        }else{            
//
//            if(Auth::user()->role_id == 1){
////                echo 'test 2';exit;
////                $this->middleware('guest',['except'=>['create','show']]); 
//                $this->middleware('admin_mw',['only'=>['destroy']]);
//            }
//        }
//        $this->middleware('auth', ['only' => 'create']);
//        $this->middleware('admin_mw');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders');
        }
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
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $managers = User::where('role_id', '=', 1)->get();

        $locations = DeliveryLocation::all();
        $states = States::all();
        $cities = City::all();

        $product_category = ProductCategory::all();

        return View::make('add_customers', array('managers' => $managers, 'locations' => $locations, 'product_category' => $product_category, 'states' => $states, 'cities' => $cities));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreCustomer $request) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
//        $customer->tally_category = Input::get('tally_category');
//        $customer->tally_sub_category = Input::get('tally_sub_category');
        $customer->phone_number1 = Input::get('phone_number1');

//        if (Input::has('vat_tin_number')) {
//            $customer->vat_tin_number = Input::get('vat_tin_number');
//        }
//        if (Input::has('excise_number')) {
//            $customer->excise_number = Input::get('excise_number');
//        }
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
            /*
             * ------SEND SMS TO ALL ADMINS -----------------
             */
//            $input = Input::all();
//            $admins = User::where('role_id', '=', 4)->get();
//            if (count($admins) > 0) {
//                foreach ($admins as $key => $admin) {
//                    $product_type = ProductType::find($request->input('product_type'));
//                    $str = "Dear " . $admin->first_name . ", <br/> " . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly chk. <br />Vikas associates";
//                    $phone_number = $admin->mobile_number;
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
//                    $ch = curl_init($url);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    $curl_scraped_page = curl_exec($ch);
//                    curl_close($ch);
//                    echo $curl_scraped_page;
//                }
//            }

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
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
        $states = States::all();
        $cities = City::all();

        $product_category = ProductCategory::all();

        return View::make('customer_details', array('customer' => $customer, 'states' => $states, 'cities' => $cities, 'product_category' => $product_category));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $states = States::all();
        $cities = City::all();

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $customer = Customer::where('id', '=', $id)->with('customerproduct')->first();
        if (count($customer) < 1) {
            return redirect('customers/')->with('error', 'Trying to access an invalid customer');
        }

        $managers = User::where('role_id', '=', 1)->get();

        $locations = DeliveryLocation::all();

        $product_category = ProductCategory::all();

        return View::make('edit_customers', array('customer' => $customer, 'managers' => $managers, 'locations' => $locations, 'product_category' => $product_category, 'states' => $states, 'cities' => $cities));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(StoreCustomer $request, $id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
//        echo 'test come';
//        exit;
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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

    public function get_city() {
        $state_id = Input::get('state');

        $data = City::where('state_id', $state_id)->get();

        $city = array();
        $i = 0;
        foreach ($data as $key => $val) {
            $city[$i]['id'] = $data[$key]->id;
            $city[$i]['city_name'] = $data[$key]->city_name;
            $i++;
        }
        echo json_encode(array('city' => $city));
        exit;
    }

    public function set_price($id) {
        $customer_id = array('id' => $id);
        $cutomer_difference = CustomerProductDifference::where('customer_id', $id)->get();
        $product_category = ProductCategory::all();
        return view('set_price', compact('cutomer_difference', 'product_category', 'customer_id'));
    }

    public function update_set_price() {

        $customer_id = Input::get('customer_id');

        $product_differrence = Input::get('product_differrence');

        if (Input::get('product_differrence') != '') {



            $product_difference1 = CustomerProductDifference::where('customer_id', $customer_id)
                    ->delete();

            $product_category_id = Input::get('product_category_id');
            if (isset($product_category_id)) {

                foreach ($product_category_id as $key => $value) {
                    if (Input::get('product_differrence')[$key] != '') {
                        $product_difference = new CustomerProductDifference();
                        $product_difference->product_category_id = $value;
                        $product_difference->customer_id = $customer_id;
                        $product_difference->difference_amount = Input::get('product_differrence')[$key];
                        $product_difference->save();
                    }
                }
            }

            return redirect('set_price/' . $customer_id)->with('success', 'Customer Set price successfully updated');
        } else {
            return redirect('set_price/' . $customer_id)->with('error', 'Please enter the customer set price please');
        }






//        $customer_id = Input::get('customer_id');
//
//        $product_category_id = Input::get('product_category_id');
//        if (isset($product_category_id)) {
//            
//            foreach ($product_category_id as $key => $value) {
//                if (Input::get('product_differrence')[$key] != '') {
//
//
//                    $product_difference->product_category_id = $value;
//                    $product_difference->customer_id = $customer_id;
//                    $product_difference->difference_amount = Input::get('product_differrence')[$key];
//                    $product_difference->save();
//                    $product_difference = CustomerProductDifference::where('product_category_id', '=', $value)->first();
//                    if (count($product_difference) > 0) {
//                        $product_difference = $product_difference;
//                    } else {
//                        $product_difference = new CustomerProductDifference();
//                    }
//
//                    $product_difference->product_category_id = $value;
//                    $product_difference->customer_id = $customer_id;
//                    $product_difference->difference_amount = Input::get('product_differrence')[$key];
//                    $product_difference->save();
//                } else {
//                    $product_difference1 = CustomerProductDifference::where('product_category_id', '=', $value)
//                            ->where('customer_id', $customer_id)
//                            ->first();
//                    if (count($product_difference1) > 0) {
//                        $product_difference1->delete();
//                    }
//                }
//            }
//
//            return redirect('set_price/' . $customer_id)->with('success', 'Customer Set price successfully updated');
//        }
    }

}
