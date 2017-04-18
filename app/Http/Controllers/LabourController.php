<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomer;
use View;
use Hash;
use Auth;
use App;
use Redirect;
use App\User;
use App\DeliveryLocation;
use App\ProductCategory;
use App\CustomerProductDifference;
use App\Customer;
use Input;
use App\States;
use App\City;
use App\Inquiry;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;
use Config;
use App\ProductType;
use App\Labour;

use Illuminate\Support\Facades\Validator;

class LabourController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders');
        }

        $labours = '';
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';

            $labours = \App\Labour::orderBy('first_name', 'asc')
                    ->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
                    ->orWhere('phone_number', 'like', $term)
                    ->paginate(20);
        } else {
            $labours = Labour::orderBy('updated_at', 'desc')->paginate(20);
        }

        $labours->setPath('labours');
        $city = City::all();


        return view('labours', array('labours' => $labours, 'city' => $city))->with('performance_index',true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
//            return Redirect::to('orders')->with('error', 'You do not have permission.');
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();

        return View::make('add_labours')->with('performance_index',true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        
        $validator = Validator::make($request->input(), Labour::$new_labours_inquiry_rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        $labour = new \App\Labour();

        

        if (Input::has('first_name')) {
            $labour->first_name = trim(Input::get('first_name'));
        }
        if (Input::has('last_name')) {
            $labour->last_name = trim(Input::get('last_name'));
        }
        if (Input::has('password')) {
            $labour->password = trim(Input::get('password'));
        }
        if (Input::has('phone_number')) {
            $labour->phone_number = trim(Input::get('phone_number'));
        }

        if ($labour->save()) {
            return redirect('performance/labours')->with('success', 'Labour Succesfully added');
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving labour');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $labour = Labour::find($id);
        return View::make('labour_details', array('labour' => $labour))->with('performance_index',true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $labour = Labour::find($id);
        if (count($labour) < 1) {
            return redirect('performance/labours/')->with('error', 'Trying to access an invalid labour');
        }

        return View::make('edit_labours', array('labour' => $labour))->with('performance_index',true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $validator = Validator::make($request->input(), Labour::$new_labours_inquiry_rules);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        $labour = Labour::find($id);

        if (Input::has('first_name')) {
            $labour->first_name = trim(Input::get('first_name'));
        }
        if (Input::has('last_name')) {
            $labour->last_name = trim(Input::get('last_name'));        }


        if (Input::has('password')) {
            $labour->password = Input::get('password');
        }
        if (Input::has('phone_number')) {
            $labour->phone_number = trim(Input::get('phone_number'));
        }

        if ($labour->save()) {
            return redirect('performance/labours')->with('success', 'Labours details updated successfully');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving labours');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('performance/labours')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            $labour = Labour::find($id);

            $labour_exist = array();
            
            $labour_exist['customer_delivery_challan'] = "";
            $labour_exist['customer_purchase_challan'] = "";
            
            $labour_delivery_challan = DeliveryChallan::where('labours', $labour->id)->get();           
            $labour_purchase_challan = PurchaseChallan::where('labours', $labour->id)->get();

            $cust_msg = 'Labour can not be deleted as details are associated with one or more ';
            $cust_flag = "";

            if (isset($labour_delivery_challan) && (count($labour_delivery_challan) > 0)) {
                $labour_exist['customer_delivery_challan'] = 1;
                $cust_msg .= "Delievry Challan";
                $cust_flag = 1;
            }           

            if (isset($labour_purchase_challan) && (count($labour_purchase_challan) > 0)) {
                $labour_exist['customer_purchase_challan'] = 1;
                $cust_msg .= "Purchase Challan";
                $cust_flag = 1;
            }

            if ($cust_flag == 1) {
                return Redirect::to('performance/labours')->with('error', $cust_msg);
            } else {
                $labour->delete();                
                return Redirect::to('performance/labours')->with('success', 'Labour deleted successfully.');
            }
        } else {
            return Redirect::to('performance/labours')->with('error', 'Invalid password');
        }
    }
    
    
    public function labourPerformance() {
        
        $labours = Labour::get();
        $labours_data = App\DeliveryChallanLabours::with('dc_labour')->with('dc_delivery_challan.delivery_order.delivery_product')->get();
       
        
        return View::make('labour_performance', array('labours' => $labours))->with('performance_index',true);
    }
    
    
   
}
