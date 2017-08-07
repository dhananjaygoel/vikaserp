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
use App\City;
use App\States;
use App\ProductCategory;
use App\CustomerProductDifference;
use Auth;
use Hash;
use App;
use Config;
use Redirect;
use App\Inquiry;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;

class PendingCustomerController extends Controller {
    /*
     * sms configuration
     */

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
     */
    public function index() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customers = Customer::orderBy('created_at', 'desc')->where('customer_status', '=', 'pending')->paginate(20);
        $locations = DeliveryLocation::all();
        $customers->setPath('pending_customers');
        return View::make('pending_customers', array('customers' => $customers, 'locations' => $locations));
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::find($id);
        if (count($customer) < 1) {
            return redirect('pending_customers/')->with('error', 'Trying to access an invalid customer');
        }
        $managers = User::where('role_id', '=', 0)->get();
        $locations = DeliveryLocation::all();
        $states = States::all();
        $cities = City::all();
        $product_category = ProductCategory::all();
        return View::make('add_pendingcustomers', array('customer' => $customer, 'locations' => $locations, 'managers' => $managers, 'states' => $states, 'cities' => $cities, 'product_category' => $product_category));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::find($id);
        $locations = DeliveryLocation::all();
        return View::make('edit_pending_customers', array('customer' => $customer, 'locations' => $locations));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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

            //         update sync table         
            $tables = ['customers'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */

            return redirect('pending_customers')->with('success', 'Customer details updated successfully');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('pending_customers')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $customer = Customer::find($id);
            $customer_exist = array();
            $customer_exist['customer_inquiry'] = "";
            $customer_exist['customer_order'] = "";
            $customer_exist['customer_delivery_order'] = "";
            $customer_exist['customer_delivery_challan'] = "";
            $customer_exist['customer_purchase_order'] = "";
            $customer_exist['customer_purchase_advice'] = "";
            $customer_exist['customer_purchase_challan'] = "";

            $customer_inquiry = Inquiry::where('customer_id', $customer->id)->get();
            $customer_order = Order::where('customer_id', $customer->id)->get();
            $customer_delivery_order = DeliveryOrder::where('customer_id', $customer->id)->get();
            $customer_delivery_challan = DeliveryChallan::where('customer_id', $customer->id)->get();
            $customer_purchase_order = PurchaseOrder::where('supplier_id', $customer->id)->get();
            $customer_purchase_advice = PurchaseAdvise::where('supplier_id', $customer->id)->get();
            $customer_purchase_challan = PurchaseChallan::where('supplier_id', $customer->id)->get();

            $cust_msg = 'Customer can not be deleted as details are associated with one or more ';
            $cust_flag = "";

            if (isset($customer_inquiry) && (count($customer_inquiry) > 0)) {
                $customer_exist['customer_inquiry'] = 1;
                $cust_msg .= "Inquiry";
                $cust_flag = 1;
            }

            if (isset($customer_order) && (count($customer_order) > 0)) {
                $customer_exist['customer_order'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Order";
                } else {
                    $cust_msg .= "Order";
                }
                $cust_flag = 1;
            }

            if (isset($customer_delivery_order) && (count($customer_delivery_order) > 0)) {
                $customer_exist['customer_delivery_order'] = 1;

                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Delievry Order";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Delievry Order";
                } else {
                    $cust_msg .= "Delievry Order";
                }
                $cust_flag = 1;
            }

            if (isset($customer_delivery_challan) && (count($customer_delivery_challan) > 0)) {
                $customer_exist['customer_delivery_challan'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Delievry Challan";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Delievry Challan";
                } elseif ($customer_exist['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Delievry Challan";
                } else {
                    $cust_msg .= "Delievry Challan";
                }
                $cust_flag = 1;
            }

            if (isset($customer_purchase_order) && (count($customer_purchase_order) > 0)) {
                $customer_exist['customer_purchase_order'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } elseif (['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } elseif ($customer_exist['customer_delivery_challan'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } else {
                    $cust_msg .= "Purchase Order";
                }
                $cust_flag = 1;
            }

            if (isset($customer_purchase_advice) && (count($customer_purchase_advice) > 0)) {
                $customer_exist['customer_purchase_advice'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_delivery_challan'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_purchase_order'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } else {
                    $cust_msg .= "Purchase Advice";
                }
                $cust_flag = 1;
            }

            if (isset($customer_purchase_challan) && (count($customer_purchase_challan) > 0)) {
                $customer_exist['customer_purchase_challan'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_delivery_challan'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_purchase_order'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_purchase_advice'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } else {
                    $cust_msg .= "Purchase Challan";
                }
                $cust_flag = 1;
            }

            if ($cust_flag == 1) {
                return Redirect::to('pending_customers')->with('error', $cust_msg);
            } else {
                $customer->delete();
                $user = User::where('email', '=', $customer->email)
                        ->where('first_name', '=', $customer->owner_name)
                        ->where('mobile_number', '=', $customer->phone_number1)
                        ->delete();
                return Redirect::to('pending_customers')->with('success', 'Pending customer Successfully deleted');
            }
        } else {
            return Redirect::to('pending_customers')->with('error', 'Invalid password');
        }
    }

    /*
     * add the pending customer.
     */

    public function add_pending_customers(StoreCustomer $request, $id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::find($id);
        if (count($customer) < 1) {
            return redirect('pending_customers/')->with('error', 'Trying to access an invalid customer');
        }

        if (Input::has('status')) {
            $customer->is_supplier = Input::get('status');
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
        if (Input::has('city')) {
            $customer->city = Input::get('city');
        }
        if (Input::has('state')) {
            $customer->state = Input::get('state');
        }
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
        if (Input::has('phone_number2')) {
            $customer->phone_number2 = Input::get('phone_number2');
        }
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
            //set price difference of the category
            $user_obj = new WelcomeController();
            $user_obj->copy_customers();
            $product_category_id = Input::get('product_category_id');
            if (isset($product_category_id)) {
                foreach ($product_category_id as $key => $value) {
                    if (Input::get('product_differrence')[$key] != '') {
                        $product_difference = new CustomerProductDifference();
                        $product_difference->product_category_id = $value;
                        $product_difference->customer_id = $id;
                        $product_difference->difference_amount = Input::get('product_differrence')[$key];
                        $product_difference->save();
                    }
                }
            }

            /*
             * ------SEND SMS TO ALL ADMINS -----------------
             */
            $input = Input::all();
            $admins = User::where('role_id', '=', 4)->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $str = "Dear " . $admin->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has converted a new customer from " . Input::get('owner_name') . " to new account as " . Input::get('owner_name') . " kindly check.\nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $admin->mobile_number;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }
            
            //         update sync table         
            $tables = ['customers','users'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */
            
            return redirect('customers')->with('success', 'Customer successfully upgraded as permanent customer');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

}
