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
use App\Territory;
use App\TerritoryLocation;
use App\Repositories\DropboxStorageRepository;
use App\CollectionUser;
use Illuminate\Support\Facades\DB;
use App\Customer_receipts;
use App\Receipt;
use Response;
use App\CustomerReceiptsDebitedTo;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller {

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
     * Display a listing of the customer.
     */
    public function index() {        
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders');
        }        

        $customers = '';
        
        $customer_filter = Input::get('customer_filter');
        $customers = Customer::orderBy('tally_name', 'asc');
        
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';

            $customers = $customers->where(function($query) use($term) {
                        $query->whereHas('city', function($q) use ($term) {
                            $q->where('city_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('deliverylocation', function($q) use ($term) {
                            $q->where('area_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('manager', function($q) use ($term) {
                            $q->where('first_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere('tally_name', 'like', $term)
                    ->orWhere('phone_number1', 'like', $term)
                    ->orWhere('phone_number2', 'like', $term)
                    ->where('customer_status', '=', 'permanent');
                    
        } 
        if (isset($customer_filter) && !empty($customer_filter)) {
            if($customer_filter=='supplier'){                
                $customers = $customers->where('is_supplier', '=', 'yes');                
            }
            elseif($customer_filter=='customer'){               
                $customers = $customers->where('is_supplier', '!=', 'yes');                                       
            }
            
        }
        
//        dd($customer_filter);

        $customers = $customers->where('customer_status', '=', 'permanent');
        $customers = $customers->paginate(20);
        $customers->setPath('customers');
        $city = City::all();
        return View::make('customers', array('customers' => $customers, 'city' => $city));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create() {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
//            return Redirect::to('orders')->with('error', 'You do not have permission.');
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $managers = User::where('role_id', '=', 0)->get();
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $states = States::orderBy('state_name', 'ASC')->get();
        $cities = City::orderBy('city_name', 'ASC')->get();
        $product_category = ProductCategory::all();
        return View::make('add_customers', array('managers' => $managers, 'locations' => $locations, 'product_category' => $product_category, 'states' => $states, 'cities' => $cities));
    }

    /**
     * Store a newly created customer in database.
     */
    public function store(StoreCustomer $request) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = new Customer();
        $users = new User();
        $customer->owner_name = Input::get('owner_name');
        $users->first_name = Input::get('owner_name');

        $users->role_id = '5';

        $already_exists_mobile_number = Customer::where('phone_number1', '=', Input::get('phone_number1'))
                ->get();

        if (count($already_exists_mobile_number) > 0) {
            return Redirect::back()->with('error', 'Mobile number is already associated with another account.')->withInput();
        }

        if (Input::has('status')) {
            $customer->is_supplier = Input::get('status');
        }
        if (Input::has('company_name')) {
            $customer->company_name = Input::get('company_name');
        }
        if (Input::has('gstin_number')) {
            $customer->gstin_number = Input::get('gstin_number');
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
            $users->email = Input::get('email');
        }
        $customer->tally_name = Input::get('tally_name');
        $customer->phone_number1 = Input::get('phone_number1');
        $users->mobile_number = Input::get('phone_number1');


        if (Input::has('phone_number2')) {
            $customer->phone_number2 = Input::get('phone_number2');
            $users->phone_number = Input::get('phone_number2');
        }
        if (Input::has('username')) {
            $customer->username = Input::get('username');
        }
        if (Input::has('credit_period')) {
            $customer->credit_period = Input::get('credit_period');
        } else {
            $customer->credit_period = 0;
        }

        if (Input::has('relationship_manager')) {
            $customer->relationship_manager = Input::get('relationship_manager');
        }
        $customer->delivery_location_id = Input::get('delivery_location');

        if (Input::has('password') && Input::get('password') != '') {
            $customer->password = Hash::make(Input::get('password'));
            $users->password = Hash::make(Input::get('password'));
        }
        $customer->customer_status = 'permanent';
        if ($customer->save() && $users->save()) {
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

            $customer_id = $customer->id;


            /*
              | ----------------------
              | SEND SMS TO ALL ADMINS
              | ----------------------
             */
            $input = Input::all();
            $admins = User::where('role_id', '=', 4)->get();
            $customer = Customer::with('manager')->find($customer_id);

            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $product_type = ProductType::find($request->input('product_type'));
                    $str = "Dear " . $admin->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";
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

            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
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

            if (count($customer['manager']) > 0) {
                $str = "Dear " . $customer['manager']->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
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

            //         update sync table         
            $tables = ['customers', 'users'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */

            return redirect('customers')->with('success', 'Customer Succesfully added');
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Display the specific customer.
     */
    public function show($id) {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
        $states = States::all();
        $cities = City::all();
        $product_category = ProductCategory::all();
        return View::make('customer_details', array('customer' => $customer, 'states' => $states, 'cities' => $cities, 'product_category' => $product_category));
    }

    /**
     * Show the form for editing the specific customer.
     */
    public function edit($id) {

        $states = States::all();
        $cities = City::all();
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::with('customerproduct')->find($id);
        if (count($customer) < 1) {
            return redirect('customers/')->with('error', 'Trying to access an invalid customer');
        }
        $managers = User::where('role_id', '=', 0)->get();
        $locations = DeliveryLocation::all();
        $product_category = ProductCategory::all();
        //$product_category = ProductCategory::whereNotIn('product_category_name',['Local Coil- Light','Local Coil'])->get();
        return View::make('edit_customers', array('customer' => $customer, 'managers' => $managers, 'locations' => $locations, 'product_category' => $product_category, 'states' => $states, 'cities' => $cities));
    }

    /**
     * Update the specific customer in database.
     */
    public function update(StoreCustomer $request, $id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::find($id);

        $already_exists_mobile_number = Customer::where('phone_number1', '=', Input::get('phone_number1'))
                ->where('id', '<>', $id)
                ->get();

        if (count($already_exists_mobile_number) > 0) {
            return Redirect::back()->with('error', 'Mobile number is already associated with another account.');
        }


//               
        $users = User::where('role_id', '=', '5')
                ->where('email', '=', $customer->email)
                ->where('mobile_number', '=', $customer->phone_number1)
                ->where('phone_number', '=', $customer->phone_number2)
                ->where('created_at', '=', $customer->created_at)
                ->first();


        if (count($customer) < 1 && count($users) < 1) {
            return redirect('customers/')->with('error', 'Trying to access an invalid customer');
        }

        $customer->owner_name = Input::get('owner_name');
        if (Input::has('status')) {
            $customer->is_supplier = Input::get('status');
        }

        if (Input::has('owner_name')) {
            $users->first_name = Input::get('owner_name');
        }


        $users->role_id = '5';

        if (Input::has('company_name')) {
            $customer->company_name = Input::get('company_name');
        }
        if (Input::has('gstin_number')) {
            $customer->gstin_number = Input::get('gstin_number');
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
//        if (Input::has('email')) {
        $customer->email = Input::get('email');
        $users->email = Input::get('email');
//        }

        $customer->tally_name = Input::get('tally_name');
        $customer->tally_category = Input::get('tally_category');
        $customer->tally_sub_category = Input::get('tally_sub_category');
        $customer->phone_number1 = Input::get('phone_number1');
        $users->mobile_number = Input::get('phone_number1');
        if (Input::has('phone_number2')) {
            $customer->phone_number2 = Input::get('phone_number2');
            $users->phone_number = Input::get('phone_number2');
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
            $customer->password = Hash::make(Input::get('password'));
            $users->password = Hash::make(Input::get('password'));
        }

        if ($customer->save() && $users->save()) {
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


            /*
              | ----------------------
              | SEND SMS TO  ADMIN AND CUSTOMER
              | ----------------------
             */

            $customer = Customer::with('manager')->find($id);

            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer->owner_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited your profile - " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
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

            if (count($customer['manager']) > 0) {
                $str = "Dear " . $customer['manager']->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited a customer - " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
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


            //         update sync table         
            $tables = ['customers', 'users'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */


            return redirect('customers')->with('success', 'Customer details updated successfully');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Remove the specific customer from database.
     */
    public function destroy($id) {

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
                return Redirect::to('customers')->with('error', $cust_msg);
            } else {
                $customer->delete();
                $user = User::where('email', '=', $customer->email)
                        ->where('first_name', '=', $customer->owner_name)
                        ->where('mobile_number', '=', $customer->phone_number1)
                        ->where('created_at', '=', $customer->created_at)
                        ->delete();

                //         update sync table         
                $tables = ['customers', 'users'];
                $ec = new WelcomeController();
                $ec->set_updated_date_to_sync_table($tables);
                /* end code */

                return Redirect::to('customers')->with('success', 'Customer deleted successfully.');
            }
        } else {
            return Redirect::to('customers')->with('error', 'Invalid password');
        }
    }

    /**
     * App track order for customer
     */
    public function trackOrderStatus($order_id) {
        $input_data = Input::all();
        if (isset($input_data['order_id'])) {
            $order_info = (json_decode($input_data['order_id']));
            if (isset($order_info[0])) {
                $order_id = $order_info[0]->order_id;
            } else
                $order_id = 0;
        }
        else {
            return json_encode(array('result' => false, 'track_order_status' => false, 'message' => 'Order not found'));
        }


        if (isset($input_data['customer_id'])) {
            $customer_info = (json_decode($input_data['customer_id']));
            if (isset($customer_info[0]))
                $customer_id = $customer_info[0]->customer_id;
            else
                $customer_id = 0;
        }
        $order_status_responase = array();
        if (isset($order_id) && $order_id > 0 && isset($customer_id) && $customer_id > 0) {

            $order_status_responase['order_details'] = Order::with('all_order_products')->where('id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();

            $order_status_responase['delivery_order_details'] = DeliveryOrder::with('delivery_product')->where('order_id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();

            $order_status_responase['delivery_challan_details'] = DeliveryChallan::with('delivery_challan_products')->where('order_id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();
        } else {
            return json_encode(array('result' => false, 'track_order_status' => false, 'message' => 'Order not found'));
        }

        return json_encode($order_status_responase);
    }

    /*
      | Get city list per state for forms
      | @return Json Responce
     */

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

    /*
      | Show list of all products category
      | Used to set price per customer
     */

    public function set_price($id = "") {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $customer_id = array('id' => $id);
        $cutomer_difference = CustomerProductDifference::where('customer_id', $id)->get();
        $product_category = ProductCategory::all();
        return view('set_price', compact('cutomer_difference', 'product_category', 'customer_id'));
    }

    /*
      | Update Individual customer product category
      | Difference
     */

    public function update_set_price() {

        $customer_id = Input::get('customer_id');
        $product_differrence = Input::get('product_differrence');
        if (Input::get('product_differrence') != '') {
            $product_difference1 = CustomerProductDifference::where('customer_id', $customer_id)->delete();
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
    }

    /*
      | to update bulk price of the product categories
      | Populate forms
     */

    public function bulk_set_price() {


        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $product_type = 1;
        if (Input::get('product_filter') != "") {
            $product_type = Input::get('product_filter');
        }

        if (Input::get('search') != "") {

            $term = '%' . Input::get('search') . '%';

            $customer = Customer::orderBy('tally_name', 'ASC')
                    ->where(function($query) use($term) {
                        $query->whereHas('city', function($q) use ($term) {
                            $q->where('city_name', 'like', $term)
                            ->orWhere('tally_name', 'like', $term);
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('deliverylocation', function($q) use ($term) {
                            $q->where('area_name', 'like', $term)
                            ->orWhere('tally_name', 'like', $term);
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('manager', function($q) use ($term) {
                            $q->where('first_name', 'like', $term)
                            ->orWhere('tally_name', 'like', $term);
                        });
                    })
                    ->with('city')
                    ->where('customer_status', ' = ', 'permanent')
                    ->paginate(20);
        } else {
            $customer = Customer::with('customerproduct')->where('customer_status', 'permanent')->orderBy('tally_name', 'ASC')->paginate(20);
        }

        $product_category = ProductCategory::where('product_type_id', $product_type)->get();
        $customer->setPath('bulk_set_price');
        $product_type = ProductType::all();
        $filter = array(Input::get('product_filter'), Input::get('search'));

        return view('bulk_set_price', compact('customer', 'product_category', 'product_type', 'filter'));
    }

    /*
      | Update Individual customer product category
      | Difference
     */

    public function save_all_set_price() {

        $data = Input::all();
        $page = "";
        if (isset($data['page']) && $data['page'] != '') {
            $page = $data['page'];
        }
        $product_filter = "";
        if (isset($data['product_filter']) && $data['product_filter'] != '') {
            $product_filter = $data['product_filter'];
        }
        $product_pipe_category = ProductCategory::where('product_type_id', 1)->get();
        $product_structure_category = ProductCategory::where('product_type_id', 2)->get();
        $product_category = ProductCategory::all();

        foreach ($data['set_diff'] as $key => $value) {

            if (isset($value['cust_id']) && $value['cust_id'] != "") {
                $pipe = $value['pipe'];
                $custid = $value['cust_id'];
                $structure = $value['structure'];

                $count = CustomerProductDifference::where('customer_id', $custid)->count();
                if ($count == 0) {
                    foreach ($product_category as $value) {
                        if ($value->product_type_id == 1 && isset($pipe) && $pipe != "") {
                            $diff = new CustomerProductDifference();
                            $diff->product_category_id = $value->id;
                            $diff->difference_amount = $pipe;
                            $diff->customer_id = $custid;
                            $diff->save();
                        }
                        if ($value->product_type_id == 2 && isset($structure) && $structure != "") {
                            $diff = new CustomerProductDifference();
                            $diff->product_category_id = $value->id;
                            $diff->difference_amount = $structure;
                            $diff->customer_id = $custid;
                            $diff->save();
                        }
                    }
                } else {

                    if (isset($value['pipe']) && !empty($value['pipe']) && $value['pipe'] != "") {

                        foreach ($product_pipe_category as $curr_category) {
                            $count = CustomerProductDifference::where('product_category_id', $curr_category->id)
                                            ->where('customer_id', $custid)->count();
                            if ($count == 0) {
                                $diff = new CustomerProductDifference();
                                $diff->product_category_id = $curr_category->product_type_id;
                                $diff->difference_amount = $pipe;
                                $diff->customer_id = $custid;
                                $diff->save();
                            } else {
                                CustomerProductDifference::where('product_category_id', $curr_category->id)
                                        ->where('customer_id', $custid)->update(array('difference_amount' => $pipe));
                            }
                        }
                    }
                    if (isset($value['structure']) && !empty($value['structure']) && $value['structure'] != "") {
                        foreach ($product_structure_category as $curr_category) {
                            $count = CustomerProductDifference::where('product_category_id', $curr_category->id)
                                            ->where('customer_id', $custid)->count();
                            if ($count == 0) {
                                $diff = new CustomerProductDifference();
                                $diff->product_category_id = $curr_category->product_type_id;
                                $diff->difference_amount = $structure;
                                $diff->customer_id = $custid;
                                $diff->save();
                            } else {
                                CustomerProductDifference::where('product_category_id', $curr_category->id)
                                        ->where('customer_id', $custid)
                                        ->update(array('difference_amount' => $structure));
                            }
                        }
                    }
                }
            }
        }

        return redirect('bulk_set_price?page = ' . $page . '&product_filter = ' . $product_filter)->with('success', 'Customer Set price for the product successfully updated');
    }

    public function get_customers_list() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 6) {
            return redirect()->back();
        }
        $customers = '';
        $loc_arr = [];
        $search = Input::get('search');
        $territory_id = Input::get('territory_filter');
        $location_id = Input::get('location_filter');
        $date_filter = Input::get('date_filter');
        if (Auth::user()->role_id == 0) {
            /* old code */
//            $customers = Customer::with('delivery_challan')
//                            ->with('customer_receipt')
//                            ->with('customer_receipt_debit')
//                            ->with('collection_user_location.collection_user')
//                            ->with('delivery_location')
//                            ->with('collection_user_location')
//                            ->orderBy('created_at', 'desc')
//                            ->whereHas('delivery_challan', function ($query) {
//                                $query->where('challan_status', '=', 'completed');
//                            });

            $customers = Customer::has('customer_receipt')
                    ->orHas('customer_receipt_debit')
                    ->orHas('delivery_challan')
                    ->with('customer_receipt')
                    ->with('customer_receipt_debit')
                    ->with('delivery_challan')
                    ->with('collection_user_location.collection_user')
                    ->with('delivery_location')
                    ->with('collection_user_location')
                    ->orderBy('created_at', 'desc');

            /* new code */

//            $customers = Customer::whereHas('delivery_challan', function ($query) {
//                        $query->where('delivery_challan.challan_status', 'completed');
//                    })
//                    ->with('customer_receipt')
//                    ->with('customer_receipt_debit')
//                    ->with('collection_user_location.collection_user')
//                    ->with('delivery_location')
//                    ->orderBy('created_at', 'desc');
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                        $query->where('challan_status', '=', 'completed');
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                        $query->where('challan_status', '=', 'completed');
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                        $query->where('challan_status', '=', 'completed');
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    $query->where('challan_status', '=', 'completed');
                });

//               dd($customers->toSql());  
//                dd($customers->get());
            }
        }
        if (Auth::user()->role_id == 6) {
            $territory_id = Input::get('territory_filter');
            $user_id = Auth::user()->id;
            $user_loc_arr = [];
            $user_territory_arr = [];
            $collection_user_locations = CollectionUser::where('user_id', '=', $user_id)->get();
            foreach ($collection_user_locations as $loc) {
                array_push($user_loc_arr, $loc->location_id);
                if (!in_array($loc->teritory_id, $user_territory_arr)) {
                    array_push($user_territory_arr, $loc->teritory_id);
                }
            }

            $customers = Customer::with(['delivery_challan' => function ($query) {
                            $query->where('delivery_challan.challan_status', 'completed');
                        }])
                    ->with('customer_receipt', 'customer_receipt_debit')
                    ->orderBy('created_at', 'desc')
                    ->whereIn('delivery_location_id', $user_loc_arr);

            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')
                            ->whereIn('id', $user_loc_arr)->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                });
            }
        }




        $customers = $customers->paginate(20)->setPath('due-payment');

        if (isset($user_territory_arr)) {
            $territories = Territory::whereIn('id', $user_territory_arr)->orderBy('created_at', 'DESC')->get();
        } else {
            $territories = Territory::orderBy('created_at', 'DESC')->get();
        }
        return View('customer_list')->with('customers', $customers)
                        ->with('delivery_location', $delivery_location)
                        ->with('territories', $territories);
    }

//    
//    public function get_customers_list() {
//        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 6) {
//            return redirect()->back();
//        }
//        $customers = '';
//        $loc_arr = [];
//        $search = Input::get('search');
//        $territory_id = Input::get('territory_filter');
//        $location_id = Input::get('location_filter');
//        $date_filter = Input::get('date_filter');
//        if (Auth::user()->role_id == 0) {
//            /* old code */
////            $customers = Customer::with('delivery_challan')->with('customer_receipt')->with('collection_user_location.collection_user')->with('delivery_location')->with('collection_user_location')->orderBy('created_at', 'desc')
////                                    ->whereHas('delivery_challan', function ($query) {
////                                    $query->where('challan_status','=', 'completed');                                            
////                                    });
//
//            /* new code */
//
//            $customers = Customer::whereHas('delivery_challan', function ($query) {
//                        $query->where('delivery_challan.challan_status', 'completed');
//                    })
//                    ->with('customer_receipt')
//                    ->with('customer_receipt_debit')
//                    ->with('collection_user_location.collection_user')
//                    ->with('delivery_location')
//                    ->orderBy('created_at', 'desc');
//            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
//            if (isset($search) && !empty($search)) {
//                $term = '%' . $search . '%';
//                $customers->Where('tally_name', 'like', $term);
//            }
//            if (isset($territory_id) && !empty($territory_id)) {
//                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
//                foreach ($territory_locations as $loc) {
//                    array_push($loc_arr, $loc->location_id);
//                }
//                $customers->whereIn('delivery_location_id', $loc_arr);
//                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
//            }
//            if (isset($location_id) && !empty($location_id)) {
//                $customers->where('delivery_location_id', '=', $location_id);
//            }
//            if (isset($date_filter) && !empty($date_filter)) {
//                if ($date_filter == 1) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                    });
//                } else
//                if ($date_filter == 3) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
//                    });
//                } else
//                if ($date_filter == 7) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
//                    });
//                }
//            } else {
//                $customers->whereHas('delivery_challan', function ($query) {
//                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                });
//
////               dd($customers->toSql());  
////                dd($customers->get());
//            }
//        }
//        if (Auth::user()->role_id == 6) {
//            $territory_id = Input::get('territory_filter');
//            $user_id = Auth::user()->id;
//            $user_loc_arr = [];
//            $user_territory_arr = [];
//            $collection_user_locations = CollectionUser::where('user_id', '=', $user_id)->get();
//            foreach ($collection_user_locations as $loc) {
//                array_push($user_loc_arr, $loc->location_id);
//                if (!in_array($loc->teritory_id, $user_territory_arr)) {
//                    array_push($user_territory_arr, $loc->teritory_id);
//                }
//            }
//
//            $customers = Customer::with(['delivery_challan' => function ($query) {
//                            $query->where('delivery_challan.challan_status', 'completed');
//                        }])
//                    ->with('customer_receipt', 'customer_receipt_debit')
//                    ->orderBy('created_at', 'desc')
//                    ->whereIn('delivery_location_id', $user_loc_arr);
//
//            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')
//                            ->whereIn('id', $user_loc_arr)->get();
//            if (isset($search) && !empty($search)) {
//                $term = '%' . $search . '%';
//                $customers->Where('tally_name', 'like', $term);
//            }
//            if (isset($territory_id) && !empty($territory_id)) {
//                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
//                foreach ($territory_locations as $loc) {
//                    array_push($loc_arr, $loc->location_id);
//                }
//                $customers->whereIn('delivery_location_id', $loc_arr);
//                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
//            }
//            if (isset($location_id) && !empty($location_id)) {
//                $customers->where('delivery_location_id', '=', $location_id);
//            }
//            if (isset($date_filter) && !empty($date_filter)) {
//                if ($date_filter == 1) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                    });
//                } else
//                if ($date_filter == 3) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
//                    });
//                } else
//                if ($date_filter == 7) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
//                    });
//                }
//            } else {
//                $customers->whereHas('delivery_challan', function ($query) {
//                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                });
//            }
//        }
//        $customers = $customers->paginate(20)->setPath('due-payment');
//
//        if (isset($user_territory_arr)) {
//            $territories = Territory::whereIn('id', $user_territory_arr)->orderBy('created_at', 'DESC')->get();
//        } else {
//            $territories = Territory::orderBy('created_at', 'DESC')->get();
//        }
//        return View('customer_list')->with('customers', $customers)
//                        ->with('delivery_location', $delivery_location)
//                        ->with('territories', $territories);
//    }





    public function get_customer_details($id) {
        $date_filter = Input::get('date_filter');
        $customer = '';
        $customer = Customer::with(['delivery_challan' => function ($query) {
                        $query->where('delivery_challan.challan_status', 'completed');
                    }])
                ->with('customer_receipt', 'customer_receipt_debit')
                ->find($id);

        $credit_period = $customer->credit_period;
        $settle_filter = Input::get('settle_filter');
        $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                ->where('challan_status', 'completed')
//                ->whereRaw('grand_price!=settle_amount');
                ->whereRaw('CAST(grand_price AS DECIMAL(10,2)) != CAST(settle_amount AS DECIMAL(10,2))');
        if (isset($settle_filter) && $settle_filter != '' && $settle_filter == 'Settled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
//                    ->whereRaw('grand_price=settle_amount');
                    ->whereRaw('CAST(grand_price AS DECIMAL(10,2)) = CAST(settle_amount AS DECIMAL(10,2))');
        }
        if (isset($settle_filter) && $settle_filter == 'Unsettled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
//                    ->whereRaw('grand_price != settle_amount');
                    ->whereRaw('CAST(grand_price AS DECIMAL(10,2)) != CAST(settle_amount AS DECIMAL(10,2))');
        }

        if (isset($date_filter) && !empty($date_filter)) {
            if ($date_filter == 1) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
            } else
            if ($date_filter == 3) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
            } else
            if ($date_filter == 7) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
            }
        } else {
            $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
        }
        $delivery_challans = $delivery_challans->get();

        $discount_user = Customer::where('owner_name', 'like', '%Discount User%')
                        ->orWhere('tally_name', 'like', '%Discount User%')
                        ->select('id')->first();


        if (count($discount_user) && $discount_user->id == $id) {
            $is_discount_user = 'true';
        } else {
            $is_discount_user = 'false';
        }

//        dd(DB::getQueryLog());
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        return View('customer_details_view')->with('customer', $customer)
                        ->with('delivery_challans', $delivery_challans)
                        ->with('delivery_location', $delivery_location)
                        ->with('is_discount_user', $is_discount_user);
    }

    public function print_account_customers(DropboxStorageRepository $connection) {
        $customers = '';
        $loc_arr = [];
        $search = Input::get('search');
        $territory_id = Input::get('territory_filter');
        $location_id = Input::get('location_filter');
        $date_filter = Input::get('date_filter');
        if (Auth::user()->role_id == 0) {
            $customers = Customer::with(['delivery_challan' => function ($query) {
                            $query->where('delivery_challan.challan_status', 'completed');
                        }])
                    ->with('customer_receipt')
                    ->with('collection_user_location.collection_user')
                    ->with('delivery_location')
                    ->with('collection_user_location')
                    ->orderBy('created_at', 'desc');
//            $customers = Customer::with('delivery_challan')->with('customer_receipt')->with('collection_user_location.collection_user')->with('delivery_location')->with('collection_user_location')->orderBy('created_at', 'desc')
//                    ->whereHas('delivery_challan', function ($query) {
//                $query->where('challan_status', '=', 'completed');
//            });
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                });

//               dd($customers->toSql());  
//                dd($customers->get());
            }
        }
        if (Auth::user()->role_id == 6) {
            $territory_id = Input::get('territory_filter');
            $user_id = Auth::user()->id;
            $user_loc_arr = [];
            $user_territory_arr = [];
            $collection_user_locations = CollectionUser::where('user_id', '=', $user_id)->get();
            foreach ($collection_user_locations as $loc) {
                array_push($user_loc_arr, $loc->location_id);
                if (!in_array($loc->teritory_id, $user_territory_arr)) {
                    array_push($user_territory_arr, $loc->teritory_id);
                }
            }

            $customers = Customer::with(['delivery_challan' => function ($query) {
                            $query->where('delivery_challan.challan_status', 'completed');
                        }])
                    ->with('customer_receipt')
                    ->orderBy('created_at', 'desc')
                    ->whereIn('delivery_location_id', $user_loc_arr);
//            $customers = Customer::with('delivery_challan')->with('customer_receipt')->orderBy('created_at', 'desc')
//                            ->whereHas('delivery_challan', function ($query) {
//                                $query->where('challan_status', '=', 'completed');
//                            })->whereIn('delivery_location_id', $user_loc_arr);
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                });

//               dd($customers->toSql());  
//                dd($customers->get());
            }
        }
        $customers = $customers->get();
        $city = City::all();
        $territories = Territory::orderBy('created_at', 'DESC')->get();
        return View('print_account_customers')->with('customers', $customers)->with('city', $city)
                        ->with('delivery_location', $delivery_location)
                        ->with('territories', $territories);
    }

    public function print_customer_details(DropboxStorageRepository $connection) {
        $customer = '';
        $id = Input::get('customer_id');
        $customer = Customer::with(['delivery_challan' => function ($query) {
                        $query->where('delivery_challan.challan_status', 'completed');
                    }])->with('customer_receipt')->find($id);
//        $customer = Customer::with('delivery_challan')->with('customer_receipt')->find($id);
        $credit_period = $customer->credit_period;
        $settle_filter = Input::get('settle_filter');
        $date_filter = Input::get('date_filter');
        $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                ->where('challan_status', 'completed')
                ->whereRaw('grand_price!=settle_amount');
        if (isset($settle_filter) && $settle_filter != '' && $settle_filter == 'Settled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
                    ->whereRaw('grand_price=settle_amount');
        }
        if (isset($settle_filter) && $settle_filter == 'Unsettled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
                    ->whereRaw('grand_price != settle_amount');
        }

        if (isset($date_filter) && !empty($date_filter)) {
            if ($date_filter == 1) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
            } else
            if ($date_filter == 3) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
            } else
            if ($date_filter == 7) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
            }
        } else {
            $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
        }
        $delivery_challans = $delivery_challans->get();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        return View('print_customer_details_view')->with('customer', $customer)
                        ->with('delivery_challans', $delivery_challans)
                        ->with('delivery_location', $delivery_location);
    }

    public function change_unsettled_amount() {
        $customer_id = Input::get('customer_id');
        $old_amount = Input::get('old_amount');
        $new_amount = Input::get('new_amount');
        $difference = $new_amount - $old_amount;


        $receipts = Customer_receipts::where('customer_id', '=', $customer_id)->orderBy('created_at', 'DESC')->first();
        if (isset($receipts) && !empty($receipts)) {
            $receipt_id = $receipts->id;
            $receipt = Customer_receipts::find($receipt_id);
            $receipt_amount = $receipt->settled_amount;
            $new_unsettle_amount = $receipt_amount + $difference;
            $receipt->settled_amount = $new_unsettle_amount;
            $receipt->save();
        } else {
            $receiptObj = new Receipt();
            if ($receiptObj->save()) {
                $customerReceiptObj = new Customer_receipts();
                $customerReceiptObj->customer_id = $customer_id;
                $customerReceiptObj->settled_amount = $new_amount;
                $customerReceiptObj->debited_by_type = 1;
//                $customerReceiptObj->debited_to = 1;
//                $customerReceiptObj->debited_to = 1;
                $customerReceiptObj->receipt_id = $receiptObj->id;
                $customerReceiptObj->save();
            }
        }

        return Response::json(['success' => true]);
    }

    public function pass_journal_entry() {
        $customer_id = Input::get('customer_id');
        $old_amount = Input::get('old_amount');
        $new_amount = Input::get('new_amount');
        $due_amount = Input::get('due_amount');

        if ($old_amount <> '0' && $due_amount == '0') {


            $cust_id = Customer::where('owner_name', 'like', '%Discount User%')
                            ->orWhere('tally_name', 'like', '%Discount User%')
                            ->select('id')->first();

            if ($old_amount > 0) {
                $customer_dt_id = $customer_id;
                $customer_cr_id = $cust_id->id;
            } else if ($old_amount < 0) {
                $customer_dt_id = $cust_id->id;
                $customer_cr_id = $customer_id;
                $old_amount = abs($old_amount);
            }

            $receiptObj = new Receipt();
            if ($receiptObj->save()) {
                $customerReceiptObj = new Customer_receipts();
                $customerReceiptObj->customer_id = $customer_cr_id;
                $customerReceiptObj->settled_amount = $old_amount;
                $customerReceiptObj->debited_by_type = 1;
                $customerReceiptObj->receipt_id = $receiptObj->id;
                $customerReceiptObj->save();

                $customerReceiptObj_debit = new CustomerReceiptsDebitedTo();
                $customerReceiptObj_debit->customer_id = $customer_dt_id;
                $customerReceiptObj_debit->settled_amount = $old_amount;
                $customerReceiptObj_debit->debited_by_type = 1;
                $customerReceiptObj_debit->receipt_id = $receiptObj->id;
                $customerReceiptObj_debit->save();
            }

            Session::flash('success', 'Journal entry has been created');
            return Response::json(['success' => true]);
        }

        Session::flash('error', 'Something went wrong.');
        return Response::json(['success' => false]);
    }

}
