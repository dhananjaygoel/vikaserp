<?php

namespace App\Http\Controllers;

use App\City;
use App\DeliveryLocation;
use App\States;
use App\Customer;
use App\User;
use App\ProductCategory;
use App\ProductSubCategory;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;
use App\Inventory;
use App\Inquiry;
use App\Order;
use App\Units;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\InquiryProducts;
use App\AllOrderProducts;
use App\PurchaseProducts;
use App\Http\Controllers\DeliveryOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App;
use Config;

class HomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
      |
     */

    /**
     * Create a new controller instance.
     */
    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', \Config::get('smsdata.profile_id'));
        define('PASS', \Config::get('smsdata.password'));
        define('SENDER_ID', \Config::get('smsdata.sender_id'));
        define('SMS_URL', \Config::get('smsdata.url'));
        define('SEND_SMS', \Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /**
     * Generate user OTP
     */
    public function generateUserOtp() {

        $user = User::where('mobile_number', '=', Input::get('username'))->first();
        if ($user)
            return json_encode(array('result' => true, 'message' => 'User found'));
        else
            return json_encode(array('result' => false, 'message' => 'User not found'));
    }

    /**
     * App user reset password
     */
    public function appUserResetPassword() {

        if (Input::get('otp') == '123456' || Input::get('otp') == 123456) {
            $user = User::where('mobile_number', '=', Input::get('username'))->first();
            if ($user) {
                $user->password = Hash::make(Input::get('password'));
                $user->save();
                return json_encode(array('result' => true, 'user' => $user, 'message' => 'Password reset successfully.'));
            } else {
                return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'User not found'));
            }
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'OTP does not match'));
        }
    }

    /**
     * App user verify OTP
     */
    public function appVerifyUserOtp() {

        if (Input::get('otp') == '123456' || Input::get('otp') == 123456) {
            $user = User::where('mobile_number', '=', Input::get('username'))->first();
            if ($user) {
                return json_encode(array('result' => true, 'user_id' => $user->id, 'mobile_status' => true, 'message' => 'OTP Verifed'));
            } else {
                return json_encode(array('result' => true, 'mobile_status' => false, 'message' => 'User not found'));
            }
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'OTP does not match'));
        }
    }

    /**
     * App user profile picture updated
     */
    public function appUserProfile() {

        if (isset($_FILES["myfile"])) {
            $ret = array();
            $output_dir = getcwd() . '/upload/admin/';
            $fileName = $_FILES["myfile"]["name"];
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $fileName);
            $ret[] = $fileName;
            $image_path = $output_dir . $fileName;
            chmod($image_path, 0777);
        }
        return json_encode(array('result' => true, 'message' => 'User profile picture added successfully'));
    }

    /**
     * App user login
     */
    public function applogin() {

        $data = Input::all();
        $username = $data['username'];
        $password = $data['password'];
        if (Auth::attempt(['mobile_number' => $username, 'password' => $password])) {
            return json_encode(array('result' => true, 'user' => auth()->user(), 'message' => 'Login Successfully Done'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Login Failed.'));
        }
    }

    /**
     * App user update profile info
     */
    public function appUpdateUser() {

        $user = User::find(Input::get('user_id'));
        if (!isset($user->id)) {
            return json_encode(array('result' => false, 'message' => 'User not found'));
        }
        if (Input::has('mobile_number') && (Input::get('mobile_number') != '') && ($user->mobile_number != Input::get('mobile_number'))) {
            return json_encode(array('result' => false, 'message' => 'Sorry! Username does not match'));
        }
        $user->first_name = (Input::has('first_name') && Input::get('first_name') != '') ? Input::get('first_name') : '';
        $user->last_name = (Input::has('last_name') && Input::get('last_name') != '') ? Input::get('last_name') : '';
        $user->email = (Input::has('email') && Input::get('email') != '') ? Input::get('email') : '';
        $user->phone_number = (Input::has('phone_number') && Input::get('phone_number') != '') ? Input::get('phone_number') : '';
        if ($user->save())
            return json_encode(array('result' => true, 'user_id' => $user->id, 'message' => 'User details updated successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App customer login
     */
    public function appCustomerLogin() {

        $customer = Customer::with('manager')->where('phone_number1', '=', Input::get('username'))->first();
        if ($customer) {
            if (Hash::check(Input::get('password'), $customer->password)) {
                return json_encode(array('result' => true, 'customer' => $customer, 'mobile_status' => true, 'message' => 'Login Successfully Done'));
            } else {
                return json_encode(array('result' => false, 'reason' => 'Password does not match', 'message' => 'Login Failed.'));
            }
        } else {
            return json_encode(array('result' => false, 'reason' => 'Customer not found', 'message' => 'Login Failed.'));
        }
    }

    /**
     * App customer reset password
     */
    public function customerResetPassword() {

        if (Input::get('otp') == '123456' || Input::get('otp') == 123456) {
            $customer = Customer::where('phone_number1', '=', Input::get('username'))->with('manager')->first();
            if ($customer) {
                $customer->password = Hash::make(Input::get('password'));
                $customer->save();
                return json_encode(array('result' => true, 'customer' => $customer, 'mobile_status' => true, 'message' => 'Password reset successfuly.'));
            } else {
                return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'Customer not found'));
            }
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'OTP does not match'));
        }
    }

    /**
     * App generate OTP
     */
    public function generateOtp() {

        $customer = Customer::where('phone_number1', '=', Input::get('username'))->first();
        if ($customer)
            return json_encode(array('result' => true, 'message' => 'Customer found'));
        else
            return json_encode(array('result' => true, 'message' => 'Customer not found'));
    }

    /**
     * App verify OTP
     */
    public function verifyOtp() {

        if (Input::get('otp') == '123456' || Input::get('otp') == 123456) {
            $customer = Customer::where('phone_number1', '=', Input::get('username'))->first();
            if ($customer)
                return json_encode(array('result' => true, 'customer_id' => $customer->id, 'mobile_status' => true, 'message' => 'OTP Verifed'));
            else
                return json_encode(array('result' => true, 'mobile_status' => false, 'message' => 'Customer not found'));
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'OTP does not match'));
        }
    }

    /**
     * App order status
     */
    public function appOrderStatus() {

        if (Input::has('order_id') && Input::get('order_id') > 0) {
            $orderid = Input::get('order_id');
            $order_detail = Order::find($orderid);
            $delivery_order_detail = DeliveryOrder::where('order_id', '=', $orderid)->first();
            $delivery_challan_detail = DeliveryChallan::where('order_id', '=', $orderid)->first();
            if (!isset($order_detail->id)) {
                return json_encode(array('result' => false, 'message' => 'Invalid order Id'));
            }
            if ($order_detail->order_status == 'completed') {
                if (isset($delivery_challan_detail) && $delivery_challan_detail->order_id == $orderid)
                    return json_encode(array('result' => true, 'message' => 'Out for delivery'));
                else
                    return json_encode(array('result' => true, 'message' => 'DO is placed'));
            } else {
                return json_encode(array('result' => true, 'message' => (($order_detail->created_at == $order_detail->updated_at) ? 'Order is placed' : 'Order is updated')));
            }
        } else {
            return json_encode(array('result' => false, 'message' => 'Please provide Order Id'));
        }
    }

    /**
     * App all relationship manager
     */
    public function appAllRelationshipManager() {

        $managers = User::where('role_id', '=', 0)->select('id', 'first_name', 'last_name')->get();
        return json_encode($managers);
    }

    /**
     * App track order
     */
    public function trackOrder($id) {

        $order_details = Order::find($id);
        return json_encode($order_details->order_status);
    }

    /**
     * App track order for customer app
     */
    public function trackOrderStatus() {
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

    /**
     * App track inquiry
     */
    public function trackInquiry($id) {

        $inquiry_details = Inquiry::find($id);
        return json_encode($inquiry_details->inquiry_status);
    }

    /**
     * App customer orders
     */
    public function customerOrders($id) {

        $order_details = Order::where('customer_id', '=', $id)->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled')->orderBy('created_at', 'desc')->get();
        return json_encode($order_details);
    }

    /**
     * App customer inquiries
     */
    public function customerInquiry($id) {

        $inquiry_details = Inquiry::where('customer_id', '=', $id)->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')->orderBy('created_at', 'desc')->get();
        return json_encode($inquiry_details);
    }

    /**
     * App customer info
     */
    public function customerInfo($id) {

        $customer_details = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
        return json_encode($customer_details);
    }

    /**
     * App customer status
     */
    public function customerStatus() {

        $input_data = Input::all();
        $customer_id = (json_decode($input_data['customer_id']));
        if (isset($customer_id[0])) {
            $id = $customer_id[0]->customer_id;
            $customer_details = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
            if (!isset($customer_details)) {
                return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'User not found'));
            }
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'User not found'));
        }
        return json_encode($customer_details);
        // $customer_details = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
        //$input_data = Input::all(); return json_encode($customer_details);
    }

    /**
     * App customer profile
     */
    public function appCustomerProfile() {

        if (isset($_FILES["myfile"])) {
            $ret = array();
            $output_dir = getcwd() . '/upload/';
            $fileName = $_FILES["myfile"]["name"];
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $fileName);
            $ret[] = $fileName;
            $image_path = $output_dir . $fileName;
            chmod($image_path, 0777);
        }
        return json_encode(array('result' => true, 'message' => 'User profile picture added successfully'));
    }

    /**
     * App add customer
     */
    public function addCustomer() {

        $customer_check = Customer::where('phone_number1', '=', Input::get('mobile'))->first();
        if (isset($customer_check->id)) {
            return json_encode(array('result' => false, 'customer_id' => $customer_check->id, 'message' => 'Customer already exist'));
        }
        $customer = new Customer();
        if (Input::has('customer_name'))
            $customer->owner_name = Input::get('customer_name');
        if (Input::has('contact_person'))
            $customer->contact_person = Input::get('contact_person');
        $customer->address1 = (Input::get('address1')) ? Input::get('address1') : '';
        if (Input::has('mobile'))
            $customer->phone_number1 = Input::get('mobile');
        if (Input::has('password'))
            $customer->password = Hash::make(Input::get('password'));
        $customer->customer_status = 'pending';
        $customer->company_name = (Input::has('company_name')) ? Input::get('company_name') : '';
        $customer->address2 = (Input::has('address2')) ? Input::get('address2') : '';
        $customer->city = (Input::has('city')) ? Input::get('city') : '';
        $customer->state = (Input::has('state')) ? Input::get('state') : '';
        $customer->zip = (Input::has('zip')) ? Input::get('zip') : '';
        $customer->email = (Input::has('email')) ? Input::get('email') : '';
        $customer->tally_name = (Input::has('tally_name')) ? Input::get('tally_name') : '';
        $customer->phone_number2 = (Input::has('phone_number2')) ? Input::get('phone_number2') : '';
        $customer->username = (Input::has('username')) ? Input::get('username') : '';
        $customer->credit_period = (Input::has('credit_period')) ? Input::get('credit_period') : 0;
        $customer->relationship_manager = (Input::has('relationship_manager')) ? Input::get('relationship_manager') : '';
        $customer->delivery_location_id = (Input::has('delivery_location_id')) ? Input::get('delivery_location_id') : '';
        if ($customer->save())
            return json_encode(array('result' => true, 'customer_id' => $customer->id, 'message' => 'Customer added successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App customer delete inquiry
     */
    public function appcustomerdeleteinquiry() {

        $input_data = Input::all();
        $inquiries = (json_decode($input_data['inquiry_deleted']));
        $customer_id = $input_data['customer_id'];
        if (count($inquiries) > 0) {
            foreach ($inquiries as $inquiry) {
                $inquiry_details = Inquiry::where('customer_id', '=', $customer_id)->find($inquiry);
                if ($inquiry_details && !empty($inquiry_details)) {
                    $inquiry_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Inquiries deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete inquiries
     */
    public function appdeleteinquiry() {

        $input_data = Input::all();
        $inquiries = (json_decode($input_data['inquiry_deleted']));
        if (count($inquiries) > 0) {
            foreach ($inquiries as $inquiry) {
                $inquiry_details = Inquiry::find($inquiry);
                if ($inquiry_details && !empty($inquiry_details)) {
                    $inquiry_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Inquiries deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App customer delete orders
     */
    public function appcustomerdeleteorder() {

        $input_data = Input::all();
        $orders = (json_decode($input_data['order_deleted']));
        $customer_id = $input_data['customer_id'];
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $order_details = Order::where('customer_id', '=', $customer_id)->find($order);
                if ($order_details && !empty($order_details)) {
                    $order_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Orders deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete orders
     */
    public function appdeleteorder() {

        $input_data = Input::all();
        $orders = (json_decode($input_data['order_deleted']));
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $order_details = Order::find($order);
                if ($order_details && !empty($order_details)) {
                    $order_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Orders deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete delivery orders
     */
    public function appdeletedelivery_order() {

        $input_data = Input::all();
        $delievry_orders = (json_decode($input_data['delivery_order_deleted']));
        if (count($delievry_orders) > 0) {
            foreach ($delievry_orders as $delievry_order) {
                $delievry_order_details = DeliveryOrder::find($delievry_order);
                if ($delievry_order_details && !empty($delievry_order_details)) {
                    $delievry_order_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Delievry Orders deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete delivery challans
     */
    public function appdeletedelivery_challan() {

        $input_data = Input::all();
        $delievry_challans = (json_decode($input_data['delivery_challan_deleted']));
        if (count($delievry_challans) > 0) {
            foreach ($delievry_challans as $delievry_challan) {
                $delievry_challan_details = DeliveryChallan::find($delievry_challan);
                if ($delievry_challan_details && !empty($delievry_challan_details)) {
                    $delievry_challan_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Delievry Challans deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete purchase orders
     */
    public function appdeletepurchase_order() {

        $input_data = Input::all();
        $purchase_orders = (json_decode($input_data['purchase_order_deleted']));
        if (count($purchase_orders) > 0) {
            foreach ($purchase_orders as $purchase_order) {
                $purchase_order_details = PurchaseOrder::find($purchase_order);
                if ($purchase_order_details && !empty($purchase_order_details)) {
                    $purchase_order_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Purchase Orders deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete purchase advise
     */
    public function appdeletepurchase_advise() {

        $input_data = Input::all();
        $purchase_advises = (json_decode($input_data['purchase_advise_deleted']));
        if (count($purchase_advises) > 0) {
            foreach ($purchase_advises as $purchase_advise) {
                $purchase_advise_details = PurchaseAdvise::find($purchase_advise);
                if ($purchase_advise_details && !empty($purchase_advise_details)) {
                    $purchase_advise_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Purchase Advise deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App delete purchase challans
     */
    public function appdeletepurchase_challan() {

        $input_data = Input::all();
        $purchase_challans = (json_decode($input_data['purchase_challan_deleted']));
        if (count($purchase_challans) > 0) {
            foreach ($purchase_challans as $purchase_challan) {
                $purchase_challan_details = PurchaseChallan::find($purchase_challan);
                if ($purchase_challan_details && !empty($purchase_challan_details)) {
                    $purchase_challan_details->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Purchase Challan deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App update customers
     */
    public function updateCustomer() {

        $customer = Customer::find(Input::get('customer_id'));
        if (!isset($customer->id)) {
            return json_encode(array('result' => false, 'message' => 'Customer not found'));
        }
        if (Input::has('mobile')) {
            $mobile = Input::get('mobile');
            if (!empty($mobile)) {
                if ($customer->phone_number1 != Input::get('mobile'))
                    return json_encode(array('result' => false, 'message' => 'Username does not match'));
            }
        }
        if (Input::has('customer_name') && Input::get('customer_name') != "")
            $customer->owner_name = Input::get('customer_name');
        if (Input::has('contact_person') && Input::get('contact_person') != "")
            $customer->contact_person = Input::get('contact_person');
        $customer->address1 = (Input::has('address1') && Input::get('address1')) ? Input::get('address1') : '';
        if (Input::has('mobile') && Input::get('mobile') != "")
            $customer->phone_number1 = Input::get('mobile');
        if (Input::has('password') && Input::get('password') != "")
            $customer->password = Hash::make(Input::get('password'));
        $customer->customer_status = 'pending';
        $customer->company_name = (Input::has('company_name') && Input::get('company_name') != "") ? Input::get('company_name') : '';
        $customer->address2 = (Input::has('address2') && Input::get('address2') != "") ? Input::get('address2') : '';
        $customer->city = (Input::has('city') && Input::get('city') != "") ? Input::get('city') : '';
        $customer->state = (Input::has('state') && Input::get('state') != "") ? Input::get('state') : '';
        $customer->zip = (Input::has('zip') && Input::get('zip') != "") ? Input::get('zip') : '';
        $customer->email = (Input::has('email') && Input::get('email') != "") ? Input::get('email') : '';
        $customer->tally_name = (Input::has('tally_name') && Input::get('tally_name') != "") ? Input::get('tally_name') : '';
        $customer->phone_number2 = (Input::has('phone_number2') && Input::get('phone_number2') != "") ? Input::get('phone_number2') : '';
        $customer->username = (Input::has('username') && Input::get('username') != "") ? Input::get('username') : '';
        $customer->credit_period = (Input::has('credit_period') && Input::get('credit_period') != "") ? Input::get('credit_period') : 0;
        $customer->relationship_manager = (Input::has('relationship_manager') && Input::get('delivery_location_id') != "") ? Input::get('relationship_manager') : '';
        $customer->delivery_location_id = (Input::has('delivery_location_id') && Input::get('delivery_location_id') != "") ? Input::get('delivery_location_id') : '';
        if ($customer->save())
            return json_encode(array('result' => true, 'customer_id' => $customer->id, 'message' => 'Customer details updated successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App contact us
     */
    public function appContactUs() {

        $data = Input::all();
        $dynamictext['name'] = $data['name'];
        $dynamictext['email'] = $data['email'];
        $dynamictext['number'] = $data['number'];
        $dynamictext['message'] = $data['message'];
        Mail::send('emails.contact_mail_admin', ['dynamictext' => $dynamictext], function($message) {
            $message->to("gamit@agstechnologies.com", "Test User")->subject('Contact from website');
        });
        return json_encode(array('result' => true, 'message' => 'Email send successfully'));
    }

    /**
     * App sync purchase challan
     */
    public function appSyncPurchaseChallan() {

        $input_data = Input::all();
        $purchase_challan_response = [];
        $customer_list = [];
        if (Input::has('purchase_challan')) {
            $purchasechallan = (json_decode($input_data['purchase_challan']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($input_data['customer']));
        }
        if (Input::has('purchase_challan_product')) {
            $purchasechallanproducts = (json_decode($input_data['purchase_challan_product']));
        }
        if (Input::has('purchase_challan_sync_date') && Input::get('purchase_challan_sync_date') != '') {
            $last_sync_date = Input::get('purchase_challan_sync_date');
            $purchase_challan_server = PurchaseChallan::where('created_at', '>', $last_sync_date)->with('all_purchase_products')->get();
            $purchase_challan_response['purchase_challan_server_added'] = ($purchase_challan_server && count($purchase_challan_server) > 0) ? $purchase_challan_server : array();

            $purchase_challan_updated_server = PurchaseChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('all_purchase_products')->get();
            $purchase_challan_response['purchase_challan_server_updated'] = ($purchase_challan_updated_server && count($purchase_challan_updated_server) > 0) ? $purchase_challan_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_challan_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_challan_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $purchase_challan_server = PurchaseChallan::with('all_purchase_products')->get();
            $purchase_challan_response['purchase_challan_server_added'] = ($purchase_challan_server && count($purchase_challan_server) > 0) ? $purchase_challan_server : array();
        }
        foreach ($purchasechallan as $key => $value) {
            if ($value->server_id > 0)
                $purchase_challan = PurchaseChallan::find($value->server_id);
            else
                $purchase_challan = new PurchaseChallan();

            if ($value->server_supplier_id == 0) {
                $add_supplier = new Customer();
                $add_supplier->addNewCustomer($value->supplier_name, "", $value->supplier_mobile, $value->credit_period);
                $customer_list[$value->id] = $add_supplier->id;
            }
            $purchase_challan->expected_delivery_date = $value->expected_delivery_date;
            $purchase_challan->purchase_advice_id = ($value->server_purchase_advice_id > 0) ? $value->server_purchase_advice_id : 0;
            $purchase_challan->purchase_order_id = ($value->server_purchase_order_id > 0) ? $value->server_purchase_order_id : 0;
            $purchase_challan->delivery_location_id = $value->delivery_location_id;
            $purchase_challan->serial_number = ($value->serial_number != '') ? $value->serial_number : "";
            $purchase_challan->supplier_id = $value->server_supplier_id;
            $purchase_challan->created_by = 1;
            $purchase_challan->vehicle_number = $value->vehicle_number;
            $purchase_challan->discount = $value->discount;
            $purchase_challan->unloaded_by = $value->unloaded_by;
            $purchase_challan->round_off = $value->round_off;
            $purchase_challan->labours = $value->labours;
            $purchase_challan->remarks = $value->remarks;
            $purchase_challan->grand_total = $value->grand_total;
            $purchase_challan->order_status = $value->order_status;
            $purchase_challan->freight = $value->freight;

            if ($value->vat_percentage > 0) {
                $purchase_challan->vat_percentage = $value->vat_percentage;
            }
            if ($value->delivery_location_id > 0) {
                $purchase_challan->delivery_location_id = $value->delivery_location_id;
            } else {
                $purchase_challan->other_location = $value->other_location_name;
                $purchase_challan->other_location_difference = $value->other_location_difference;
            }
            $purchase_challan->save();
            $purchase_challan_id = $purchase_challan->id;
            if ($value->server_id > 0) {
                PurchaseProducts::where('order_type', '=', 'purchase_challan')->where('purchase_order_id', '=', $value->server_id)->delete();
            }
            foreach ($purchasechallanproducts as $product_data) {
                if ($product_data->purchase_order_id == $value->id) {
                    $purchase_challan_products = [
                        'app_product_id' => $product_data->id,
                        'purchase_order_id' => $purchase_challan_id,
                        'order_type' => 'purchase_challan',
                        'product_category_id' => $product_data->product_category_id,
                        'unit_id' => $product_data->unit_id,
                        'quantity' => $product_data->quantity,
                        'price' => $product_data->price,
                        'remarks' => "",
                        'present_shipping' => $product_data->present_shipping,
                        'from' => ($product_data->server_pur_order_id > 0) ? $product_data->server_pur_order_id : ''
                    ];
                    PurchaseProducts::create($purchase_challan_products);
                }
            }
            if ($value->server_id > 0) {
                $purchase_challan_prod = PurchaseProducts::where('order_type', '=', 'purchase_challan')->where('purchase_order_id', '=', $value->server_id)->first();
                $purchase_challan->updated_at = $purchase_challan_prod->updated_at;
                $purchase_challan_response[$value->id] = PurchaseChallan::find($value->server_id);
                $purchase_challan_response[$value->id]['purchase_products'] = PurchaseProducts::where('order_type', '=', 'purchase_challan')->where('purchase_order_id', '=', $value->server_id)->get();
            } else {
                $purchase_challan_response[$value->id] = $purchase_challan_id;
            }
            $purchase_challan->save();
        }
        if (count($customer_list) > 0) {
            $purchase_challan_response['customer_new'] = $customer_list;
        }
        if (Input::has('purchase_challan_sync_date') && Input::get('purchase_challan_sync_date') != '' && Input::get('purchase_challan_sync_date') != NULL) {
            $purchase_challan_response['purchase_challan_deleted'] = PurchaseChallan::withTrashed()->where('deleted_at', '>=', Input::get('purchase_challan_sync_date'))->select('id')->get();
        }
        $purchase_challan_date = PurchaseChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_challan_date))
            $purchase_challan_response['latest_date'] = $purchase_challan_date->updated_at->toDateTimeString();
        else
            $purchase_challan_response['latest_date'] = "";

        return json_encode($purchase_challan_response);
    }

    /**
     * App sync purchase advise
     */
    public function appSyncPurchaseAdvise() {

        $input_data = Input::all();
        $purchase_advice_response = [];
        $customer_list = [];
        if (Input::has('purchase_advice')) {
            $purchaseadvices = (json_decode($input_data['purchase_advice']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($input_data['customer']));
        }
        if (Input::has('purchase_advice_product')) {
            $purchaseadviceproducts = (json_decode($input_data['purchase_advice_product']));
        }
        if (Input::has('purchase_advice_sync_date') && Input::get('purchase_advice_sync_date') != '') {
            $last_sync_date = Input::get('purchase_advice_sync_date');
            $purchase_advice_server = PurchaseAdvise::where('created_at', '>', $last_sync_date)->with('purchase_products')->get();
            $purchase_advice_response['purchase_advice_server_added'] = ($purchase_advice_server && count($purchase_advice_server) > 0) ? $purchase_advice_server : array();

            $purchase_advice_updated_server = PurchaseAdvise::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('purchase_products')->get();
            $purchase_advice_response['purchase_advice_server_updated'] = ($purchase_advice_updated_server && count($purchase_advice_updated_server) > 0) ? $purchase_advice_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_advice_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_advice_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $purchase_advice_server = PurchaseAdvise::with('purchase_products')->get();
            $purchase_advice_response['purchase_advice_server_added'] = ($purchase_advice_server && count($purchase_advice_server) > 0) ? $purchase_advice_server : array();
        }
        foreach ($purchaseadvices as $key => $value) {
            if ($value->server_id > 0)
                $purchase_advice = PurchaseAdvise::find($value->server_id);
            else
                $purchase_advice = new PurchaseAdvise();

            if ($value->server_supplier_id == 0) {
                $add_supplier = new Customer();
                $add_supplier->addNewCustomer($value->supplier_name, "", $value->supplier_mobile, $value->credit_period);
                $customer_list[$value->id] = $add_supplier->id;
            }
            $date_string = preg_replace('~\x{00a0}~u', ' ', $value->bill_date);
            $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
            $datetime = new DateTime($date);
            $date_string2 = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
            $date2 = date("Y/m/d", strtotime(str_replace('-', '/', $date_string2)));
            $datetime2 = new DateTime($date2);
            $purchase_advice->purchase_order_id = ($value->server_purchase_order_id > 0) ? $value->server_purchase_order_id : 0;
            $purchase_advice->purchase_advice_date = $datetime->format('Y-m-d');
            $purchase_advice->supplier_id = ($value->server_supplier_id > 0) ? $value->server_supplier_id : $customer_list[$value->id];
            $purchase_advice->created_by = 1;
            $purchase_advice->expected_delivery_date = $datetime2->format('Y-m-d');
            $purchase_advice->total_price = $value->total_price;
            $purchase_advice->remarks = $value->remarks;
            $purchase_advice->vehicle_number = $value->vehicle_number;
            $purchase_advice->order_for = $value->order_for;
            $purchase_advice->advice_status = ($value->advice_status != '') ? $value->advice_status : 'in_process';
            if ($value->vat_percentage > 0) {
                $purchase_advice->vat_percentage = $value->vat_percentage;
            }
            if ($value->delivery_location_id > 0) {
                $purchase_advice->delivery_location_id = $value->delivery_location_id;
            } else {
                $purchase_advice->other_location = $value->other_location_name;
                $purchase_advice->other_location_difference = $value->other_location_difference;
            }
            $purchase_advice->save();
            $purchase_advise_id = $purchase_advice->id;
            if ($value->server_id > 0) {
                PurchaseProducts::where('order_type', '=', 'purchase_advice')->where('purchase_order_id', '=', $value->server_id)->delete();
            }
            foreach ($purchaseadviceproducts as $product_data) {
                if ($product_data->purchase_order_id == $value->id) {
                    $purchase_advise_products = [
                        'app_product_id' => $product_data->id,
                        'purchase_order_id' => $purchase_advise_id,
                        'order_type' => 'purchase_advice',
                        'product_category_id' => $product_data->product_category_id,
                        'unit_id' => $product_data->unit_id,
                        'quantity' => $product_data->present_shipping,
                        'present_shipping' => $product_data->present_shipping,
                        'price' => $product_data->price,
                        'actual_pieces' => $product_data->actual_pieces,
                        'remarks' => "",
                        'from' => ($product_data->server_pur_order_id > 0) ? $product_data->server_pur_order_id : ''
                    ];
                    PurchaseProducts::create($purchase_advise_products);
                }
            }
            if ($value->server_id > 0) {
                $purchase_advice_prod = PurchaseProducts::where('order_type', '=', 'purchase_advice')->where('purchase_order_id', '=', $value->server_id)->first();
                $purchase_advice->updated_at = $purchase_advice_prod->updated_at;
                $purchase_advice_response[$value->id] = PurchaseAdvise::find($value->server_id);
                $purchase_advice_response[$value->id]['purchase_products'] = PurchaseProducts::where('order_type', '=', 'purchase_advice')->where('purchase_order_id', '=', $value->server_id)->get();
            } else {
                $purchase_advice_response[$value->id] = $purchase_advise_id;
            }
            $purchase_advice->save();
        }
        if (count($customer_list) > 0) {
            $purchase_advice_response['customer_new'] = $customer_list;
        }
        if (Input::has('purchase_advice_sync_date') && Input::get('purchase_advice_sync_date') != '' && Input::get('purchase_advice_sync_date') != NULL) {
            $purchase_advice_response['purchase_advise_deleted'] = PurchaseAdvise::withTrashed()->where('deleted_at', '>=', Input::get('purchase_advice_sync_date'))->select('id')->get();
        }
        $purchase_advice_date = PurchaseAdvise::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_advice_date))
            $purchase_advice_response['latest_date'] = $purchase_advice_date->updated_at->toDateTimeString();
        else
            $purchase_advice_response['latest_date'] = "";

        return json_encode($purchase_advice_response);
    }

    /**
     * App sync purchase order
     */
    public function appSyncPurchaseOrder() {

        $data = Input::all();
        $purchase_order_response = [];
        $customer_list = [];
        if (Input::has('purchase_order')) {
            $purchaseorders = (json_decode($data['purchase_order']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('purchase_order_product')) {
            $purchaseorderproducts = (json_decode($data['purchase_order_product']));
        }
        if (Input::has('purchase_order_sync_date') && Input::get('purchase_order_sync_date') != '') {
            $last_sync_date = Input::get('purchase_order_sync_date');
            $purchase_order_server = PurchaseOrder::where('created_at', '>', $last_sync_date)->with('purchase_products')->get();
            $purchase_order_response['purchase_order_server_added'] = ($purchase_order_server && count($purchase_order_server) > 0) ? $purchase_order_server : array();

            $purchase_order_updated_server = PurchaseOrder::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('purchase_products')->get();
            $purchase_order_response['purchase_order_server_updated'] = ($purchase_order_updated_server && count($purchase_order_updated_server) > 0) ? $purchase_order_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {

            $purchase_order_server = PurchaseOrder::with('purchase_products')->get();
            $purchase_order_response['purchase_order_server_added'] = ($purchase_order_server && count($purchase_order_server) > 0) ? $purchase_order_server : array();
        }
        foreach ($purchaseorders as $key => $value) {

            if ($value->server_id > 0)
                $purchase_order = PurchaseOrder::find($value->server_id);
            else
                $purchase_order = new PurchaseOrder();

            if ($value->server_supplier_id == 0) {
                $add_supplier = new Customer();
                $add_supplier->addNewCustomer($value->supplier_name, "", $value->supplier_mobile, $value->credit_period);
                $customer_list[$value->id] = $add_supplier->id;
            }
//            $expected_delivery_date = explode('-', $value->expected_delivery_date);
//            $expected_delivery_date = $expected_delivery_date[2] . '-' . $expected_delivery_date[0] . '-' . $expected_delivery_date[1];
//            $expected_delivery_date = date("Y-m-d", strtotime($expected_delivery_date));
            $purchase_order->supplier_id = ($value->server_supplier_id > 0) ? $value->server_supplier_id : $customer_list[$value->id];
            $purchase_order->created_by = 1;
            $purchase_order->order_for = ($value->customer_server_id > 0) ? $value->customer_server_id : 0;
            $purchase_order->vat_percentage = ($value->vat_percentage > 0) ? $value->vat_percentage : 0;
            $purchase_order->expected_delivery_date = $value->expected_delivery_date;
            $purchase_order->remarks = $value->remarks;
            $purchase_order->order_status = $value->order_status;
            $purchase_order->is_view_all = $value->is_view_all;
            if ($value->delivery_location_id > 0) {
                $purchase_order->delivery_location_id = $value->delivery_location_id;
            } else {
                $purchase_order->other_location = $value->other_location;
                $purchase_order->other_location_difference = $value->other_location_difference;
            }
            $purchase_order->save();
            $purchase_order_id = $purchase_order->id;
            $purchase_order_products = array();
            if ($value->server_id) {
                PurchaseProducts::where('order_type', '=', 'purchase_order')->where('purchase_order_id', '=', $value->server_id)->delete();
            }
            foreach ($purchaseorderproducts as $product_data) {
                if ($value->id == $product_data->purchase_order_id) {
                    $purchase_order_products = [
                        'app_product_id' => $product_data->id,
                        'purchase_order_id' => $purchase_order_id,
                        'product_category_id' => $product_data->product_category_id,
                        'unit_id' => $product_data->unit_id,
                        'quantity' => $product_data->actual_pieces,
                        'price' => $product_data->price,
                        'remarks' => '',
                    ];
                    PurchaseProducts::create($purchase_order_products);
                }
            }
            if ($value->server_id > 0) {
                $purchase_order_prod = PurchaseProducts::where('order_type', '=', 'purchase_order')->where('purchase_order_id', '=', $value->server_id)->first();
                $purchase_order->updated_at = $purchase_order_prod->updated_at;
                $purchase_order_response[$value->id] = PurchaseOrder::find($value->server_id);
                $purchase_order_response[$value->id]['purchase_products'] = PurchaseProducts::where('order_type', '=', 'purchase_order')->where('purchase_order_id', '=', $value->server_id)->get();
            } else {
                $purchase_order_response[$value->id] = $purchase_order_id;
            }
            $purchase_order->save();
        }
        if (count($customer_list) > 0) {
            $purchase_order_response['customer_new'] = $customer_list;
        }
        if (Input::has('purchase_order_sync_date') && Input::get('purchase_order_sync_date') != '' && Input::get('purchase_order_sync_date') != NULL) {
            $purchase_order_response['purchase_order_deleted'] = PurchaseOrder::withTrashed()->where('deleted_at', '>=', Input::get('purchase_order_sync_date'))->select('id')->get();
        }
        $purchase_order_date = PurchaseOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_order_date))
            $purchase_order_response['latest_date'] = $purchase_order_date->updated_at->toDateTimeString();
        else
            $purchase_order_response['latest_date'] = "";

        return json_encode($purchase_order_response);
    }

    /**
     * App sync delievry challan
     */
    public function appSyncDeliveryChallan() {

        $data = Input::all();
        $delivery_challan_response = [];
        $customer_list = [];
        if (Input::has('delivery_challan')) {
            $delivery_challans = (json_decode($data['delivery_challan']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('delivery_challan_product')) {
            $deliverychallanproducts = (json_decode($data['delivery_challan_product']));
        }
        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '') {
            $last_sync_date = Input::get('delivery_challan_sync_date');
            $delivery_challan_server = DeliveryChallan::where('created_at', '>', $last_sync_date)->with('delivery_challan_products')->get();
            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();

            $delivery_challan_updated_server = DeliveryChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('delivery_challan_products')->get();
            $delivery_challan_response['delivery_challan_server_updated'] = ($delivery_challan_updated_server && count($delivery_challan_updated_server) > 0) ? $delivery_challan_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $delivery_challan_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $delivery_challan_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $delivery_challan_server = DeliveryChallan::with('delivery_challan_products')->get();
            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();
        }

        foreach ($delivery_challans as $key => $value) {
            if ($value->server_id == 0)
                $delivery_challan = new DeliveryChallan();
            else
                $delivery_challan = DeliveryChallan::find($value->server_id);

            if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                $add_customers = new Customer();
                $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                $customer_list[$value->id] = $add_customers->id;
            }
            if ($value->server_order_id == 0) {
                $delivery_challan->order_id = 0;
            } else {
                $delivery_challan->order_id = $value->server_order_id;
            }
            if ($value->server_del_order_id == 0) {
                DeliveryOrder::where('id', '=', $value->server_del_order_id)->update(array('order_status' => $value->order_status));
                $delivery_challan->delivery_order_id = 0;
            } else {
                $delivery_challan->delivery_order_id = $value->server_del_order_id;
            }
            $delivery_challan->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
            $delivery_challan->created_by = 1;
            if (isset($value->bill_number)) {
                $delivery_challan->bill_number = $value->bill_number;
            }
            $delivery_challan->discount = ($value->discount != '') ? $value->discount : '';
            $delivery_challan->freight = ($value->freight != '') ? $value->freight : '';
            $delivery_challan->loading_charge = ($value->loading_charge != '') ? $value->loading_charge : '';
            $delivery_challan->round_off = ($value->round_off != '') ? $value->round_off : '';
            $delivery_challan->loaded_by = ($value->loaded_by != '') ? $value->loaded_by : '';
            $delivery_challan->labours = ($value->labours != '') ? $value->labours : '';
//            if (isset($value->vat_percentage) && $value->vat_percentage > 0) {
//                $delivery_challan->vat_percentage = $value->vat_percentage;
//            }
            $delivery_challan->grand_price = $value->grand_price;
            $delivery_challan->remarks = $value->remarks;
            $delivery_challan->challan_status = ($value->server_id > 0) ? $value->challan_status : "Pending";
            $delivery_challan->save();
            $delivery_challan_id = $delivery_challan->id;
            $delivery_challan_products = array();
            if ($value->server_id > 0)
                AllOrderProducts::where('order_type', '=', 'delivery_challan')->where('order_id', '=', $value->server_id)->delete();

            foreach ($deliverychallanproducts as $product_data) {
                if ($product_data->delivery_challan_id == $value->id) {
                    $delivery_challan_products = [
                        'app_product_id' => $product_data->id,
                        'order_id' => $delivery_challan_id,
                        'order_type' => 'delivery_challan',
                        'product_category_id' => $product_data->product_category_id,
                        'unit_id' => $product_data->unit_id,
                        'quantity' => $product_data->quantity,
                        'price' => $product_data->actual_price,
                        'remarks' => '',
                        'present_shipping' => $product_data->present_shipping,
                        'actual_pieces' => $product_data->actual_pieces,
                        'actual_quantity' => $product_data->actual_quantity,
                        'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                        'from' => 0, //Will need to check with app data
                        'parent' => 0, //Will need to check with app data
                    ];
                    AllOrderProducts::create($delivery_challan_products);
                }
            }
            if ($value->server_id > 0) {
                $delivery_challan_prod = AllOrderProducts::where('order_id', '=', $value->server_id)->where('order_type', '=', 'delivery_challan')->first();
                $delivery_challan->updated_at = $delivery_challan_prod->updated_at;
                $delivery_challan_response[$value->id] = DeliveryChallan::find($value->server_id);
                $delivery_challan_response[$value->id]['delivery_challan_products'] = AllOrderProducts::where('order_type', '=', 'delivery_challan')->where('order_id', '=', $value->server_id)->get();
            } else {
                $delivery_challan_response[$value->id] = $delivery_challan_id;
            }
            $delivery_challan->save();
        }
        if (count($customer_list) > 0) {
            $delivery_challan_response['customer_new'] = $customer_list;
        }
        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '' && Input::get('delivery_challan_sync_date') != NULL) {
            $delivery_challan_response['delivery_challan_deleted'] = DeliveryChallan::withTrashed()->where('deleted_at', '>=', Input::get('delivery_challan_sync_date'))->select('id')->get();
        }
        $delivery_challan_date = DeliveryChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($delivery_challan_date))
            $delivery_challan_response['latest_date'] = $delivery_challan_date->updated_at->toDateTimeString();
        else
            $delivery_challan_response['latest_date'] = "";

        return json_encode($delivery_challan_response);
    }

    /**
     * App sync delievry order
     */
    public function appSyncDeliveryOrder() {

        $data = Input::all();
        $delivery_order_response = [];
        $customer_list = [];
        if (Input::has('delivery_order')) {
            $delivery_orders = (json_decode($data['delivery_order']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('delivery_order_product')) {
            $deliveryorderproducts = (json_decode($data['delivery_order_product']));
        }

        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '') {
            $last_sync_date = Input::get('delivery_order_sync_date');
            $delivery_order_server = DeliveryOrder::where('created_at', '>', $last_sync_date)->with('delivery_product')->get();
            $delivery_order_response['delivery_order_server_added'] = ($delivery_order_server && count($delivery_order_server) > 0) ? $delivery_order_server : array();

            $delivery_order_updated_server = DeliveryOrder::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('delivery_product')->get();
            $delivery_order_response['delivery_order_server_updated'] = ($delivery_order_updated_server && count($delivery_order_updated_server) > 0) ? $delivery_order_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $delivery_order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $delivery_order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $delivery_order_server = DeliveryOrder::with('delivery_product')->get();
            $delivery_order_response['delivery_order_server_added'] = ($delivery_order_server && count($delivery_order_server) > 0) ? $delivery_order_server : array();
        }
        foreach ($delivery_orders as $key => $value) {

            if ($value->server_id == 0) {
                $delivery_order = new DeliveryOrder();
                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                    $add_customers = new Customer();
                    $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                    $customer_list[$value->id] = $add_customers->id;
                }
                $delivery_order->order_id = ($value->server_order_id > 0) ? $value->server_order_id : 0;
                $delivery_order->order_source = 'warehouse';
                $delivery_order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                $delivery_order->created_by = 1;
//                $delivery_order->vat_percentage = ($value->vatPercentage > 0 ) ? $value->vatPercentage : '';
                $delivery_order->estimate_price = 0;
                $delivery_order->vat_percentage = $value->vat_percentage;
                $delivery_order->expected_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
                $delivery_order->remarks = $value->remarks;
                $delivery_order->vehicle_number = ($value->vehicle_number != '') ? $value->vehicle_number : '';
                $delivery_order->driver_contact_no = ($value->driver_contact_no != '') ? $value->driver_contact_no : '';
                $delivery_order->order_status = $value->order_status;
                if ($value->delivery_location_id > 0) {
                    $delivery_order->delivery_location_id = $value->delivery_location_id;
                    $delivery_order->location_difference = $value->location_difference;
                } else {
                    $delivery_order->other_location = $value->other_location;
                    $delivery_order->location_difference = $value->other_location_difference;
                }
                $delivery_order->save();
                $delivery_order_id = $delivery_order->id;
                $delivery_order_products = array();
                foreach ($deliveryorderproducts as $product_data) {
                    $product = AllOrderProducts::where('order_id', '=', $value->server_order_id)
                            ->where('order_type', '=', 'order')
                            ->where('product_category_id', '=', $product_data->product_category_id)
                            ->select('id')
                            ->get();


                    if (isset($product[0])) {
                        $product_id = $product[0]->id;
                    } else {
                        $product_id = 0;
                    }

                    if ($product_data->delivery_order_id == $value->id) {
                        $delivery_order_products = [
                            'app_product_id' => $product_data->id,
                            'order_id' => $delivery_order_id,
                            'order_type' => 'delivery_order',
                            'product_category_id' => $product_data->product_category_id,
                            'unit_id' => $product_data->unit_id,
                            'from' => $value->server_order_id,
                            'parent' => $product_id,
                            'quantity' => $product_data->present_shipping,
                            'present_shipping' => $product_data->present_shipping,
                            'price' => $product_data->actualPrice,
                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                            'remarks' => ''
                        ];
                        AllOrderProducts::create($delivery_order_products);
                    }
                }
                $delivery_order_response[$value->id] = $delivery_order_id;
            } else {
                $delivery_order = DeliveryOrder::find($value->server_id);
                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                    $add_customers = new Customer();
                    $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                    $customer_list[$value->id] = $add_customers->id;
                }
                if ($value->order_id == 0) {
                    $delivery_order->order_id = 0;
                }
                $delivery_order->order_source = 'warehouse';
                $delivery_order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                $delivery_order->created_by = 1;
//                $delivery_order->vat_percentage = ($value->vatPercentage > 0 ) ? $value->vatPercentage : '';
                $delivery_order->estimate_price = 0;
                $delivery_order->expected_delivery_date = date_format(date_create(date("Y-m-d")), 'Y-m-d');
                $delivery_order->remarks = $value->remarks;
                $delivery_order->vehicle_number = ($value->vehicle_number != '') ? $value->vehicle_number : '';
                $delivery_order->driver_contact_no = ($value->driver_contact_no != '') ? $value->driver_contact_no : '';
                $delivery_order->order_status = $value->order_status;
                if ($value->delivery_location_id > 0) {
                    $delivery_order->delivery_location_id = $value->delivery_location_id;
                    $delivery_order->location_difference = $value->location_difference;
                } else {
                    $delivery_order->other_location = $value->other_location;
                    $delivery_order->location_difference = $value->other_location_difference;
                }
                $delivery_order_id = $delivery_order->id;
                $delivery_order_products = array();
                AllOrderProducts::where('order_type', '=', 'delivery_order')->where('order_id', '=', $delivery_order->id)->delete();
                foreach ($deliveryorderproducts as $product_data) {
                    $product = AllOrderProducts::where('order_id', '=', $value->server_order_id)
                            ->where('order_type', '=', 'order')
                            ->where('product_category_id', '=', $product_data->product_category_id)
                            ->select('id')
                            ->get();
                    if (isset($product[0])) {
                        $product_id = $product[0]->id;
                    } else {
                        $product_id = 0;
                    }

                    if ($product_data->delivery_order_id == $value->id) {
                        $delivery_order_products = [
                            'app_product_id' => $product_data->id,
                            'order_id' => $delivery_order_id,
                            'order_type' => 'delivery_order',
                            'product_category_id' => $product_data->product_category_id,
                            'unit_id' => $product_data->unit_id,
                            'from' => $value->server_order_id,
                            'parent' => $product_id,
                            'quantity' => $product_data->quantity,
                            'present_shipping' => $product_data->present_shipping,
                            'price' => $product_data->actualPrice,
                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                            'remarks' => ''
                        ];
                        AllOrderProducts::create($delivery_order_products);
                    }
                }
                $delivery_order_prod = AllOrderProducts::where('order_type', '=', 'delivery_order')->where('order_id', '=', $delivery_order_id)->first();
                $delivery_order->updated_at = $delivery_order_prod->updated_at;
                $delivery_order->save();
                $delivery_order_response[$value->server_id] = DeliveryOrder::find($delivery_order->id);
                $delivery_order_response[$value->server_id]['delivery_product'] = AllOrderProducts::where('order_type', '=', 'delivery_order')->where('order_id', '=', $delivery_order->id)->get();
            }
        }
        if (count($customer_list) > 0) {
            $delivery_order_response['customer_new'] = $customer_list;
        }
        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '' && Input::get('delivery_order_sync_date') != NULL) {
            $delivery_order_response['delivery_order_deleted'] = DeliveryOrder::withTrashed()->where('deleted_at', '>=', Input::get('delivery_order_sync_date'))->select('id')->get();
        }
        $delivery_order_date = DeliveryOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($delivery_order_date))
            $delivery_order_response['latest_date'] = $delivery_order_date->updated_at->toDateTimeString();
        else
            $delivery_order_response['latest_date'] = "";

        return json_encode($delivery_order_response);
    }

    /**
     * App sync order
     */
    public function appSyncOrder() {

        $data = Input::all();
        $order_response = [];
        $customer_list = [];
        if (Input::has('order')) {
            $orders = (json_decode($data['order']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('order_product')) {
            $orderproduct = (json_decode($data['order_product']));
        }
        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '') {
            $last_sync_date = Input::get('order_sync_date');
            $order_added_server = Order::where('created_at', '>', $last_sync_date)->with('all_order_products')->get();
            $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();

            $order_updated_server = Order::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('all_order_products')->get();
            $order_response['order_server_updated'] = ($order_updated_server && count($order_updated_server) > 0) ? $order_updated_server : '';

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $order_added_server = Order::with('all_order_products')->get();
            $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();
        }

        foreach ($orders as $key => $value) {
            if ($value->server_id == 0) {
                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                    $add_customers = new Customer();
                    $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                    $customer_list[$value->id] = $add_customers->id;
                }
                if ($value->supplier_server_id == 0) {
                    $order_status = 'warehouse';
                    $supplier_id = 0;
                } else {
                    $other_location_difference;
                    $order_status = 'supplier';
                    $supplier_id = $value->supplier_server_id;
                }
                $order = new Order();
                $order->order_source = $order_status;
                $order->supplier_id = $supplier_id;
                if (isset($value->vat_percentage)) {
                    $order->vat_percentage = $value->vat_percentage;
                }
                $order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                $order->created_by = 1;
//                $order->vat_percentage = ($value->vat_percentage == '') ? '' : $value->vat_percentage;
                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                $datetime = new DateTime($date);
                $order->expected_delivery_date = $datetime->format('Y-m-d');
                $order->remarks = $value->remarks;
                $order->flaged = ($value->flaged != '') ? $value->flaged : 0;
                $order->order_status = $value->order_status;
                if ($value->delivery_location_id > 0) {
                    $order->delivery_location_id = $value->delivery_location_id;
                    $order->location_difference = $value->location_difference;
                } else {
                    $order->delivery_location_id = 0;
                    $order->other_location = $value->other_location;
                    $order->location_difference = $value->other_location_difference;
                }
                $order->save();
                $order_id = $order->id;
                $order_products = array();
                foreach ($orderproduct as $product_data) {
                    if ($product_data->order_id == $value->id) {
                        $order_products = [
                            'app_product_id' => $product_data->id,
                            'order_id' => $order_id,
                            'order_type' => 'order',
                            'product_category_id' => $product_data->product_category_id,
                            'unit_id' => $product_data->unit_id,
                            'quantity' => $product_data->quantity,
                            'price' => $product_data->price,
                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                            'remarks' => '',
                        ];
                        AllOrderProducts::create($order_products);
                    }
                }
                $order_response[$value->id] = $order_id;
            } else {
                $order = Order::find($value->server_id);
                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                    $add_customers = new Customer();
                    $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                    $customer_list[$value->id] = $add_customers->id;
                }
                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                $datetime = new DateTime($date);
//                $order->vat_percentage = ($value->vat_percentage == '') ? '' : $value->vat_percentage;
                if ($value->supplier_server_id == 0) {
                    $order_status = 'warehouse';
                    $supplier_id = 0;
                } else {
                    $other_location_difference;
                    $order_status = 'supplier';
                    $supplier_id = $value->supplier_server_id;
                }
                $order->supplier_id = $supplier_id;
                $order->remarks = ($value->remarks != '') ? $value->remarks : '';
                $order->order_status = $value->order_status;
                if (isset($value->vat_percentage)) {
                    $order->vat_percentage = $value->vat_percentage;
                }
                $order->flaged = ($value->flaged != '') ? $value->flaged : 0;
                if ($value->delivery_location_id > 0) {
                    $order->delivery_location_id = $value->delivery_location_id;
                    $order->location_difference = $value->location_difference;
                } else {
                    $order->delivery_location_id = 0;
                    $order->other_location = $value->other_location;
                    $order->location_difference = $value->other_location_difference;
                }
                $order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                $order->expected_delivery_date = $datetime->format('Y-m-d');
                AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->delete();
                foreach ($orderproduct as $product_data) {
                    $order_products = array();
                    if ($product_data->order_id == $value->id) {
                        $order_products = [
                            'app_product_id' => $product_data->id,
                            'order_id' => $value->server_id,
                            'product_category_id' => $product_data->product_category_id,
                            'unit_id' => $product_data->unit_id,
                            'quantity' => $product_data->quantity,
                            'price' => $product_data->price,
                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                            'remarks' => '',
                        ];
                        AllOrderProducts::create($order_products);
                    }
                }
                $order_prod = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $value->server_id)->first();
                $order->updated_at = $order_prod->updated_at;
                $order->save();
                $order_response[$value->server_id] = Order::find($value->server_id);
                $order_response[$value->server_id]['all_order_products'] = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->get();
            }
        }
        if (count($customer_list) > 0) {
            $order_response['customer_new'] = $customer_list;
        }
        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '' && Input::get('order_sync_date') != NULL) {
            $order_response['order_deleted'] = Order::withTrashed()->where('deleted_at', '>=', Input::get('order_sync_date'))->select('id')->get();
        }
        $order_date = Order::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($order_date))
            $order_response['latest_date'] = $order_date->updated_at->toDateTimeString();
        else
            $order_response['latest_date'] = "";

        return json_encode($order_response);
    }

    /**
     * App sync order for customer app
     */
    public function appSyncOrder_customer() {
        $data = Input::all();
        $flag = 0;
        $order_response = [];
        $customer_list = [];
        if (Input::has('flag')) {
            $flag = (json_decode($data['flag']));
            if ($flag == 1) {
                if (Input::has('customer_id')) {
                    $customer_id = (json_decode($data['customer_id']));

                    if (Input::has('order')) {
                        $orders = (json_decode($data['order']));
                    }
                    if (Input::has('customer')) {
                        $customers = (json_decode($data['customer']));
                    }
                    if (Input::has('order_product')) {
                        $orderproduct = (json_decode($data['order_product']));
                    }
                    if (Input::has('order_sync_date') && Input::get('order_sync_date') != '') {
                        $last_sync_date = Input::get('order_sync_date');
                        $order_added_server = Order::where('created_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->with('all_order_products')->get();
                        $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();

                        $order_updated_server = Order::where('updated_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->whereRaw('updated_at > created_at')->with('all_order_products')->get();
                        $order_response['order_server_updated'] = ($order_updated_server && count($order_updated_server) > 0) ? $order_updated_server : '';

                        /* Send Updated customers */
                        $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->where('id', '=', $customer_id)->whereRaw('updated_at > created_at')->get();
                        $order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
                        /* Send New customers */
                        $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->where('id', '=', $customer_id)->get();
                        $order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
                    } else {
                        $order_added_server = Order::where('customer_id', '=', $customer_id)->with('all_order_products')->get();
                        $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();
                    }

                    foreach ($orders as $key => $value) {
                        if ($value->server_id == 0) {
                            if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                                $add_customers = new Customer();
                                $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                                $customer_list[$value->id] = $add_customers->id;
                            }
                            if ($value->supplier_server_id == 0) {
                                $order_status = 'warehouse';
                                $supplier_id = 0;
                            } else {
                                $other_location_difference;
                                $order_status = 'supplier';
                                $supplier_id = $value->supplier_server_id;
                            }
                            $order = new Order();
                            $order->order_source = $order_status;
                            $order->supplier_id = $supplier_id;
                            if (isset($value->vat_percentage)) {
                                $order->vat_percentage = $value->vat_percentage;
                            }
                            $order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                            $order->created_by = 1;
//                $order->vat_percentage = ($value->vat_percentage == '') ? '' : $value->vat_percentage;
                            $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                            $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                            $datetime = new DateTime($date);
                            $order->expected_delivery_date = $datetime->format('Y-m-d');
                            $order->remarks = $value->remarks;
                            $order->flaged = ($value->flaged != '') ? $value->flaged : 0;
                            $order->order_status = $value->order_status;
                            if ($value->delivery_location_id > 0) {
                                $order->delivery_location_id = $value->delivery_location_id;
                                $order->location_difference = $value->location_difference;
                            } else {
                                $order->delivery_location_id = 0;
                                $order->other_location = $value->other_location;
                                $order->location_difference = $value->other_location_difference;
                            }
                            $order->save();
                            $order_id = $order->id;
                            $order_products = array();
                            foreach ($orderproduct as $product_data) {
                                if ($product_data->order_id == $value->id) {
                                    $order_products = [
                                        'app_product_id' => $product_data->id,
                                        'order_id' => $order_id,
                                        'order_type' => 'order',
                                        'product_category_id' => $product_data->product_category_id,
                                        'unit_id' => $product_data->unit_id,
                                        'quantity' => $product_data->quantity,
                                        'price' => $product_data->price,
                                        'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                        'remarks' => '',
                                    ];
                                    AllOrderProducts::create($order_products);
                                }
                            }
                            $order_response[$value->id] = $order_id;
                        } else {
                            $order = Order::find($value->server_id);
                            if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                                $add_customers = new Customer();
                                $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                                $customer_list[$value->id] = $add_customers->id;
                            }
                            $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                            $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                            $datetime = new DateTime($date);
//                $order->vat_percentage = ($value->vat_percentage == '') ? '' : $value->vat_percentage;
                            if ($value->supplier_server_id == 0) {
                                $order_status = 'warehouse';
                                $supplier_id = 0;
                            } else {
                                $other_location_difference;
                                $order_status = 'supplier';
                                $supplier_id = $value->supplier_server_id;
                            }
                            $order->supplier_id = $supplier_id;
                            $order->remarks = ($value->remarks != '') ? $value->remarks : '';
                            $order->order_status = $value->order_status;
                            if (isset($value->vat_percentage)) {
                                $order->vat_percentage = $value->vat_percentage;
                            }
                            $order->flaged = ($value->flaged != '') ? $value->flaged : 0;
                            if ($value->delivery_location_id > 0) {
                                $order->delivery_location_id = $value->delivery_location_id;
                                $order->location_difference = $value->location_difference;
                            } else {
                                $order->delivery_location_id = 0;
                                $order->other_location = $value->other_location;
                                $order->location_difference = $value->other_location_difference;
                            }
                            $order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                            $order->expected_delivery_date = $datetime->format('Y-m-d');
                            AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->delete();
                            foreach ($orderproduct as $product_data) {
                                $order_products = array();
                                if ($product_data->order_id == $value->id) {
                                    $order_products = [
                                        'app_product_id' => $product_data->id,
                                        'order_id' => $value->server_id,
                                        'product_category_id' => $product_data->product_category_id,
                                        'unit_id' => $product_data->unit_id,
                                        'quantity' => $product_data->quantity,
                                        'price' => $product_data->price,
                                        'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                        'remarks' => '',
                                    ];
                                    AllOrderProducts::create($order_products);
                                }
                            }
                            $order_prod = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $value->server_id)->first();
                            $order->updated_at = $order_prod->updated_at;
                            $order->save();
                            $order_response[$value->server_id] = Order::find($value->server_id);
                            $order_response[$value->server_id]['all_order_products'] = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->get();
                        }
                    }
                    if (count($customer_list) > 0) {
                        $order_response['customer_new'] = $customer_list;
                    }
                    if (Input::has('order_sync_date') && Input::get('order_sync_date') != '' && Input::get('order_sync_date') != NULL) {
                        $order_response['order_deleted'] = Order::withTrashed()->where('deleted_at', '>=', Input::get('order_sync_date'))->select('id')->get();
                    }
                    $order_date = Order::select('updated_at')->where('customer_id', '=', $customer_id)->orderby('updated_at', 'DESC')->first();
                    if (!empty($order_date))
                        $order_response['latest_date'] = $order_date->updated_at->toDateTimeString();
                    else
                        $order_response['latest_date'] = "";

                    return json_encode($order_response);
                }
            }
        }
    }

    /**
     * App sync inquiries
     */
// All Functions added by user 157 for android request //
//    public function appsyncinquiry($inquiryies = NULL, $inquiry_customers = NULL, $inquiryiesproduct = NULL) {
    public function appsyncinquiry() {

        $data = Input::all();
        if (Input::has('inquiry')) {
            $inquiries = (json_decode($data['inquiry']));
        }
//        else {
//            if ($inquiryies != NULL) {
//                $inquiries = $inquiries;
//            }
//        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        /*
          else {
          if ($inquiry_customers != NULL) {
          $customers = $inquiry_customers;
          }
          } */
        if (Input::has('inquiry_product')) {
            $inquiryproduct = (json_decode($data['inquiry_product']));
        }
//        else {
//            if ($inquiryiesproduct != NULL) {
//                $inquiryproduct = $inquiryiesproduct;
//            }
//        }
        $inquiry_response = [];
        $customer_list = [];
        if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
            $last_sync_date = Input::get('inquiry_sync_date');
            $inquiry_added_server = Inquiry::where('created_at', '>', $last_sync_date)->with('inquiry_products')->get();
            $inquiry_response['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();

            $inquiry_updated_server = Inquiry::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('inquiry_products')->get();
            $inquiry_response['inquiry_server_updated'] = ($inquiry_updated_server && count($inquiry_updated_server) > 0) ? $inquiry_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $inquiry_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $inquiry_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $inquiry_added_server = Inquiry::with('inquiry_products')->get();
            $inquiry_response['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();
        }
        if (isset($inquiries)) {
            foreach ($inquiries as $key => $value) {
                if ($value->server_id > 0) {
                    $add_inquiry = Inquiry::find($value->server_id);
                    if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                        $add_customers = new Customer();
                        $add_customers->owner_name = $value->customer_name;
                        $add_customers->contact_person = $value->customer_contact_peron;
                        $add_customers->phone_number1 = $value->customer_mobile;
                        $add_customers->credit_period = $value->customer_credit_period;
                        $add_customers->customer_status = $value->customer_status;
                        $add_customers->save();
                        $customer_list[$value->id] = $add_customers->id;
                    }
                    /* Update customer here */
                    /*
                      $update_customers = Customer::find($add_inquiry->customer_server_id);
                      $update_customers->owner_name = $value->customer_name;
                      $update_customers->contact_person = $value->customer_contact_peron;
                      $update_customers->phone_number1 = $value->customer_mobile;
                      $update_customers->credit_period = $value->customer_credit_period;
                      $update_customers->customer_status = $update_customers->customer_status;
                      $update_customers->save();
                     */
                    /* Update customer ends here */
                    $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                    $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                    $datetime = new DateTime($date);
                    $add_inquiry->vat_percentage = ($value->vat_percentage == "" || empty($value->vat_percentage)) ? 0 : $value->vat_percentage;
                    if (($value->other_location == "") || empty($value->other_location)) {
                        $add_inquiry->delivery_location_id = $value->delivery_location_id;
                        $add_inquiry->location_difference = $value->location_difference;
                    } else {
                        $add_inquiry->delivery_location_id = 0;
                        $add_inquiry->other_location = $value->other_location;
                        $add_inquiry->location_difference = $value->location_difference;
                    }
                    if (isset($customer_list[$value->id]) && $customer_list[$value->id] > 0) {
                        $add_inquiry->customer_id = $customer_list[$value->id];
                    } else {
                        $add_inquiry->customer_id = $value->customer_server_id;
                        if ($value->customer_name == "" && $value->customer_contact_peron == "" && $value->customer_mobile = "" && $value->customer_credit_period = "") {
                            $update_customers = Customer::find($value->customer_server_id);
                            $update_customers->addNewCustomer($value->customer_name, $value->customer_contact_peron, $value->customer_mobile, $value->customer_credit_period);
                        }
                    }
                    $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                    $add_inquiry->remarks = ($value->remarks != '') ? $value->remarks : '';
                    $add_inquiry->inquiry_status = $value->inquiry_status;
                    $delete_old_inquiry_products = InquiryProducts::where('inquiry_id', '=', $value->server_id)->delete();
                    foreach ($inquiryproduct as $product_data) {
                        $inquiry_products = array();
                        if ($product_data->inquiry_id == $value->id) {
                            $inquiry_products = [
                                'app_product_id' => $product_data->id,
                                'inquiry_id' => $value->server_id,
                                'product_category_id' => $product_data->inquiry_product_id,
                                'unit_id' => $product_data->unit_id,
                                'quantity' => $product_data->quantity,
                                'price' => $product_data->price,
                                'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                'remarks' => '',
                            ];
                            $add_inquiry_products = InquiryProducts::create($inquiry_products);
                        }
                    }
                    $inquiry_products = InquiryProducts::where('inquiry_id', '=', $value->server_id)->first();
                    $add_inquiry->updated_at = $inquiry_products->updated_at;
                    $add_inquiry->save();
                    $inquiry_response[$value->server_id] = Inquiry::find($value->server_id);
                    $inquiry_response[$value->server_id]['inquiry_products'] = InquiryProducts::where('inquiry_id', '=', $value->server_id)->get();
                } else {

                    if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                        $add_customers = new Customer();
                        $add_customers->owner_name = $value->customer_name;
                        $add_customers->contact_person = $value->customer_contact_peron;
                        $add_customers->phone_number1 = $value->customer_mobile;
                        $add_customers->credit_period = $value->customer_credit_period;
                        $add_customers->customer_status = $value->customer_status;
                        $add_customers->save();
                        $customer_list[$value->id] = $add_customers->id;
                    }
                    $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                    $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                    $datetime = new DateTime($date);
                    $add_inquiry = new Inquiry();
                    $add_inquiry->customer_id = (!empty($value->customer_server_id) && $value->customer_server_id > 0) ? $value->customer_server_id : $customer_list[$value->id];
                    $add_inquiry->created_by = 1;
                    if (($value->other_location == "") || empty($value->other_location)) {
                        $add_inquiry->delivery_location_id = $value->delivery_location_id;
                        $add_inquiry->location_difference = $value->location_difference;
                    } else {
                        $add_inquiry->delivery_location_id = 0;
                        $add_inquiry->other_location = $value->other_location;
                        $add_inquiry->location_difference = $value->location_difference;
                    }

                    $add_inquiry->vat_percentage = ($value->vat_percentage != "") ? $value->vat_percentage : 0;
                    $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                    $add_inquiry->remarks = ($value->remarks != '') ? $value->remarks : '';
                    $add_inquiry->inquiry_status = $value->inquiry_status;
                    $add_inquiry->save();
                    $inquiry_id = $add_inquiry->id;
                    $inquiry_products_track = 0;
                    foreach ($inquiryproduct as $product_data) {
                        $inquiry_products = array();
                        if ($product_data->inquiry_id == $value->id) {
                            $inquiry_products = [
                                'app_product_id' => $product_data->id,
                                'inquiry_id' => $inquiry_id,
                                'product_category_id' => $product_data->inquiry_product_id,
                                'unit_id' => $product_data->unit_id,
                                'quantity' => $product_data->quantity,
                                'price' => $product_data->price,
                                'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                'remarks' => '',
                            ];
                            $add_inquiry_products = InquiryProducts::create($inquiry_products);
                        }
                    }
                    $inquiry_response[$value->id] = $inquiry_id;
                }
            }
        }



        if (count($customer_list) > 0) {
            $inquiry_response['customer_new'] = $customer_list;
        }
        if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
            $inquiry_response['inquiry_deleted'] = Inquiry::withTrashed()->where('deleted_at', '>=', Input::get('inquiry_sync_date'))->select('id')->get();
        }
        $inquiry_date = Inquiry::select('updated_at')->
                        orderby('updated_at', 'DESC')->first();
        if (!empty($inquiry_date))
            $inquiry_response[
                    'latest_date'] = $inquiry_date->updated_at->toDateTimeString();
        else
            $inquiry_response['latest_date'] = "";

//        if ($inquiryies != NULL || $inquiryiesproduct != NULL) {
//            return $inquiry_response;
//        } else {
        return json_encode($inquiry_response);

//        }
    }

    /**
     * customer App sync inquiries
     */
//    public function appsyncinquiry($inquiryies = NULL, $inquiry_customers = NULL, $inquiryiesproduct = NULL) {
    public function appsyncinquiry_customer() {

        $data = Input::all();

        if (Input::has('flag')) {
            $flag = (json_decode($data['flag']));
            if ($flag == 1) {
                if (Input::has('customer_id')) {
                    $customer_id = (json_decode($data['customer_id']));



                    if (Input::has('inquiry')) {
                        $inquiries = (json_decode($data['inquiry']));
                    }

                    if (Input::has('customer')) {
                        $customers = (json_decode($data['customer']));
                    }

                    if (Input::has('inquiry_product')) {
                        $inquiryproduct = (json_decode($data['inquiry_product']));
                    }

                    $inquiry_response = [];
                    $customer_list = [];
                    if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
                        $last_sync_date = Input::get('inquiry_sync_date');
                        $inquiry_added_server = Inquiry::where('created_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->with('inquiry_products')->get();
                        $inquiry_response['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();

                        $inquiry_updated_server = Inquiry::where('updated_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->whereRaw('updated_at > created_at')->with('inquiry_products')->get();
                        $inquiry_response['inquiry_server_updated'] = ($inquiry_updated_server && count($inquiry_updated_server) > 0) ? $inquiry_updated_server : array();

                        /* Send Updated customers */
                        $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->where('id', '=', $customer_id)->whereRaw('updated_at > created_at')->get();
                        $inquiry_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
                        /* Send New customers */
                        $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->where('id', '=', $customer_id)->get();
                        $inquiry_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
                    } else {
                        $inquiry_added_server = Inquiry::where('customer_id', '=', $customer_id)->with('inquiry_products')->get();
                        $inquiry_response['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();
                    }
                    if (isset($inquiries)) {
                        foreach ($inquiries as $key => $value) {
                            if ($value->server_id > 0) {
                                $add_inquiry = Inquiry::find($value->server_id);
                                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                                    $add_customers = new Customer();
                                    $add_customers->owner_name = $value->customer_name;
                                    $add_customers->contact_person = $value->customer_contact_peron;
                                    $add_customers->phone_number1 = $value->customer_mobile;
                                    $add_customers->credit_period = $value->customer_credit_period;
                                    $add_customers->customer_status = $value->customer_status;
                                    $add_customers->save();
                                    $customer_list[$value->id] = $add_customers->id;
                                }

                                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                                $datetime = new DateTime($date);
                                $add_inquiry->vat_percentage = ($value->vat_percentage == "" || empty($value->vat_percentage)) ? 0 : $value->vat_percentage;
                                if (($value->other_location == "") || empty($value->other_location)) {
                                    $add_inquiry->delivery_location_id = $value->delivery_location_id;
                                    $add_inquiry->location_difference = $value->location_difference;
                                } else {
                                    $add_inquiry->delivery_location_id = 0;
                                    $add_inquiry->other_location = $value->other_location;
                                    $add_inquiry->location_difference = $value->location_difference;
                                }
                                if (isset($customer_list[$value->id]) && $customer_list[$value->id] > 0) {
                                    $add_inquiry->customer_id = $customer_list[$value->id];
                                } else {
                                    $add_inquiry->customer_id = $value->customer_server_id;
                                    if ($value->customer_name == "" && $value->customer_contact_peron == "" && $value->customer_mobile = "" && $value->customer_credit_period = "") {
                                        $update_customers = Customer::find($value->customer_server_id);
                                        $update_customers->addNewCustomer($value->customer_name, $value->customer_contact_peron, $value->customer_mobile, $value->customer_credit_period);
                                    }
                                }
                                $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                                $add_inquiry->remarks = ($value->remarks != '') ? $value->remarks : '';
                                $add_inquiry->inquiry_status = $value->inquiry_status;
                                $delete_old_inquiry_products = InquiryProducts::where('inquiry_id', '=', $value->server_id)->delete();
                                foreach ($inquiryproduct as $product_data) {
                                    $inquiry_products = array();
                                    if ($product_data->inquiry_id == $value->id) {
                                        $inquiry_products = [
                                            'app_product_id' => $product_data->id,
                                            'inquiry_id' => $value->server_id,
                                            'product_category_id' => $product_data->inquiry_product_id,
                                            'unit_id' => $product_data->unit_id,
                                            'quantity' => $product_data->quantity,
                                            'price' => $product_data->price,
                                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                            'remarks' => '',
                                        ];
                                        $add_inquiry_products = InquiryProducts::create($inquiry_products);
                                    }
                                }
                                $inquiry_products = InquiryProducts::where('inquiry_id', '=', $value->server_id)->first();
                                $add_inquiry->updated_at = $inquiry_products->updated_at;
                                $add_inquiry->save();
                                $inquiry_response[$value->server_id] = Inquiry::find($value->server_id);
                                $inquiry_response[$value->server_id]['inquiry_products'] = InquiryProducts::where('inquiry_id', '=', $value->server_id)->get();
                            } else {

                                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                                    $add_customers = new Customer();
                                    $add_customers->owner_name = $value->customer_name;
                                    $add_customers->contact_person = $value->customer_contact_peron;
                                    $add_customers->phone_number1 = $value->customer_mobile;
                                    $add_customers->credit_period = $value->customer_credit_period;
                                    $add_customers->customer_status = $value->customer_status;
                                    $add_customers->save();
                                    $customer_list[$value->id] = $add_customers->id;
                                }
                                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                                $datetime = new DateTime($date);
                                $add_inquiry = new Inquiry();
                                $add_inquiry->customer_id = (!empty($value->customer_server_id) && $value->customer_server_id > 0) ? $value->customer_server_id : $customer_list[$value->id];
                                $add_inquiry->created_by = 1;
                                if (($value->other_location == "") || empty($value->other_location)) {
                                    $add_inquiry->delivery_location_id = $value->delivery_location_id;
                                    $add_inquiry->location_difference = $value->location_difference;
                                } else {
                                    $add_inquiry->delivery_location_id = 0;
                                    $add_inquiry->other_location = $value->other_location;
                                    $add_inquiry->location_difference = $value->location_difference;
                                }

                                $add_inquiry->vat_percentage = ($value->vat_percentage != "") ? $value->vat_percentage : 0;
                                $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                                $add_inquiry->remarks = ($value->remarks != '') ? $value->remarks : '';
                                $add_inquiry->inquiry_status = $value->inquiry_status;
                                $add_inquiry->save();
                                $inquiry_id = $add_inquiry->id;
                                $inquiry_products_track = 0;
                                foreach ($inquiryproduct as $product_data) {
                                    $inquiry_products = array();
                                    if ($product_data->inquiry_id == $value->id) {
                                        $inquiry_products = [
                                            'app_product_id' => $product_data->id,
                                            'inquiry_id' => $inquiry_id,
                                            'product_category_id' => $product_data->inquiry_product_id,
                                            'unit_id' => $product_data->unit_id,
                                            'quantity' => $product_data->quantity,
                                            'price' => $product_data->price,
                                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                            'remarks' => '',
                                        ];
                                        $add_inquiry_products = InquiryProducts::create($inquiry_products);
                                    }
                                }
                                $inquiry_response[$value->id] = $inquiry_id;
                            }
                        }
                    }



                    if (count($customer_list) > 0) {
                        $inquiry_response['customer_new'] = $customer_list;
                    }
                    if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
                        $inquiry_response['inquiry_deleted'] = Inquiry::withTrashed()->where('deleted_at', '>=', Input::get('inquiry_sync_date'))->select('id')->get();
                    }
                    $inquiry_date = Inquiry::select('updated_at')
                                    ->where('customer_id', '=', $customer_id)
                                    ->orderby('updated_at', 'DESC')->first();
                    if (!empty($inquiry_date))
                        $inquiry_response[
                                'latest_date'] = $inquiry_date->updated_at->toDateTimeString();
                    else
                        $inquiry_response['latest_date'] = "";

                    return json_encode($inquiry_response);
                }
            }
        }
    }

    /**
     * App sync and comare last sync dated and send updated date
     */
    public function appsync() {

//        $data = Input::all();
//        $inquiry_date = Inquiry::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $order_date = Order::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $delivery_order_date = DeliveryOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $delivery_challan_date = DeliveryChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $purchase_order_date = PurchaseOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $purchase_advice_date = PurchaseAdvise::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $purchase_challan_date = PurchaseChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $customer_date = Customer::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $user_date = User::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $product_category = ProductCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $product_subcategory_date = ProductSubCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $location_date = DeliveryLocation::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $city_date = City::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $state_date = States::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $inventory_date = Inventory::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        $syncdata = json_decode($data['sync_info']);
//        $sync = [];
//                $sync['inquiry'] = [$syncdata['inquiry'], $inquiry_date->updated_at->toDateTimeString()];
//                $sync['order'] = [$syncdata['order'], $order_date->updated_at->toDateTimeString()];
//                $sync['delivery_order'] = [$syncdata['delivery_order'], $delivery_order_date->updated_at->toDateTimeString()];
//                $sync['delivery_challan'] = [$syncdata['delivery_challan'], $delivery_challan_date->updated_at->toDateTimeString()];
//                $sync['purchase_order'] = [$syncdata['purchase_order'], $purchase_order_date->updated_at->toDateTimeString()];
//                $sync['purchase_advice'] = [$syncdata['purchase_advice'], $purchase_advice_date->updated_at->toDateTimeString()];
//                $sync['purchase_challan'] = [$syncdata['purchase_challan'], $purchase_challan_date->updated_at->toDateTimeString()];
//                $sync['customer'] = [$syncdata['customer'], $customer_date->updated_at->toDateTimeString()];
//                $sync['user'] = [$syncdata['user'], $user_date->updated_at->toDateTimeString()];
//                $sync['product_cat'] = [$syncdata['product_cat'], $product_category->updated_at->toDateTimeString()];
//                $sync['product_sub_cat'] = [$syncdata['product_sub_cat'], $product_subcategory_date->updated_at->toDateTimeString()];
//                $sync['location'] = [$syncdata['location'], $location_date->updated_at->toDateTimeString()];
//                $sync['city'] = [$syncdata['city'], $city_date->updated_at->toDateTimeString()];
//                $sync['state'] = [$syncdata['state'], $state_date->updated_at->toDateTimeString()];
//                $sync['inventory'] = [$syncdata['inventory'], $inventory_date->updated_at->toDateTimeString()];
        //        return json_encode($sync);





        $inquiry_date = Inquiry::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $order_date = Order::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $delivery_order_date = DeliveryOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $delivery_challan_date = DeliveryChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $purchase_order_date = PurchaseOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $purchase_advice_date = PurchaseAdvise::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $purchase_challan_date = PurchaseChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $customer_date = Customer::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $user_date = User::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $product_category = ProductCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $product_subcategory_date = ProductSubCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $location_date = DeliveryLocation::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $city_date = City::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $state_date = States::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $inventory_date = Inventory::select('updated_at')->orderby('updated_at', 'DESC')->first();

        $sync = [];
        $syncdata = ( json_decode(Input::get('sync_info'), true) );
        foreach ($syncdata as $synckey => $syncvalue) {
            if ($synckey == 'inquiry' && !empty($inquiry_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue,
                    'server_updated_date' => $inquiry_date->updated_at->toDateTimeString()];
            else
                $sync[$synckey] = [];
            if ($synckey == 'order' && !empty($order_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $order_date->updated_at->toDateTimeString()];

            if ($synckey == 'delivery_order' && !empty($delivery_order_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $delivery_order_date->updated_at->toDateTimeString()];

            if ($synckey == 'delivery_challan' && !empty($delivery_challan_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $delivery_challan_date->updated_at->toDateTimeString()];

            if ($synckey == 'purchase_order' && !empty($purchase_order_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $purchase_order_date->updated_at->toDateTimeString()];

            if ($synckey == 'purchase_advice' && !empty($purchase_advice_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $purchase_advice_date->updated_at->toDateTimeString()];

            if ($synckey == 'purchase_challan' && !empty($purchase_challan_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $purchase_challan_date->updated_at->toDateTimeString
                    ()];
            if ($synckey == 'customer' && !empty($customer_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $customer_date->updated_at->
                            toDateTimeString()];
            if ($synckey == 'user' && !empty($user_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $user_date->updated_at->toDateTimeString()];

            if ($synckey == 'product_cat' && !empty($product_category))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $product_category->updated_at->toDateTimeString()];
            if ($synckey == 'product_sub_cat' && !empty($product_subcategory_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $product_subcategory_date->updated_at->toDateTimeString
                    ()];
            if ($synckey == 'location' && !empty($location_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $location_date->updated_at->
                            toDateTimeString()];
            if ($synckey == 'city' && !empty($city_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $city_date->updated_at->
                            toDateTimeString()];
            if ($synckey == 'state' && !empty($state_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $state_date->updated_at->toDateTimeString()
                ];
            if ($synckey == 'inventory' && !empty($inventory_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $inventory_date->updated_at->toDateTimeString()];
        }

        return json_encode($sync);
    }

    /**
     * App dashboard counts
     */
    public function appcount() {
        $order = Order::all()->count();
        $pending_order = Order::where('order_status', 'pending')->count();
        $inquiry = Inquiry::all()->count();
        $pending_inquiry = Inquiry::where('inquiry_status', 'pending')->count();
        $delivery_order = DeliveryOrder::with('delivery_product')->get();
        $deliver_sum = 0;
        $deliver_pending_sum = 0;
        foreach ($delivery_order as $qty) {
            if ($qty->order_status == 'pending') {
                foreach ($qty['delivery_product'] as $qty_val) {
                    $deliver_pending_sum += $qty_val->quantity;
                }
            } else if ($qty->order_status == 'completed') {
                foreach ($qty['delivery_product'] as $qty_val) {
                    $deliver_sum += $qty_val->quantity;
                }
            }
        }
        $deliver_sum = $deliver_sum / 100;
        $deliver_pending_sum = $deliver_pending_sum / 100;
        $pur_challan = PurchaseChallan::with('purchase_product')->get();
        $challan_sum = 0;
        foreach ($pur_challan as $qty) {
            foreach ($qty['purchase_product'] as $qty_val) {
                $challan_sum += $qty_val->quantity;
            }
        }
        $challan_sum = $challan_sum / 100;
        $purc_order_sum = 0;
        $pur_challan = PurchaseOrder::with('purchase_products')->get();
        foreach ($pur_challan as $qty) {
            foreach ($qty['purchase_products'] as $qty_val) {
                $purc_order_sum += $qty_val->quantity;
            }
        }
        $purc_order_sum = $purc_order_sum / 100;
        $allcounts['order_counts'] = $order;
        $allcounts['pending_counts'] = $pending_order;
        $allcounts['inquiry_counts'] = $inquiry;
        $allcounts['pending_inquiry_counts'] = $pending_inquiry;
        $allcounts['deliver_sum'] = $deliver_sum;
        $allcounts['deliver_pending_sum'] = $deliver_pending_sum;
        $allcounts['challan_sum'] = $challan_sum;

        $allcounts['purc_order_sum'] = $challan_sum;

        return json_encode($allcounts);
    }

    /**
     * App inquiry
     */
    public function appinquiry() {

        $data = Input::all();
        $q = Inquiry::query();

        if ((isset($data['inquiry_filter'])) && $data['inquiry_filter'] != '')
            $q->where('inquiry_status', '=', $data['inquiry_filter']);

        if (Input::has('inquiry_sync_date') && $data['inquiry_sync_date'] != '')
            $q->where('created_at', '>', $data['inquiry_sync_date']);
        $inquiries['all'] = $q->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')->orderBy('created_at', 'desc')->get();

        $inquiry_date = Inquiry::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($inquiry_date)) {
            $inquiries['latest_date'] = $inquiry_date->updated_at->toDateTimeString();
        } else {
            $inquiries['latest_date'] = "";
        }
        return json_encode($inquiries);
    }

    /**
     * App orders
     */
    public function apporders() {

        $data = Input::all();
        $q = Order::query()
        ;
        if (isset($data['order_filter']) && $data['order_filter'] != '')
            $q->where('order_status', '=', $data['order_filter']);

        if (Input::has('order_sync_date') && $data['order_sync_date'] != '')
            $q->where('updated_at', '>', $data['order_sync_date']);
        $allorders['all'] = $q->with('all_order_products')->with('customer', 'delivery_location', 'order_cancelled')->orderBy('created_at', 'desc')->get();

        $orders_date = Order::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($orders_date)) {
            $allorders['latest_date'] = $orders_date->updated_at->toDateTimeString();
        } else {
            $allorders['latest_date'] = "";
        }

        return json_encode($allorders);
    }

    /**
     * App inventory
     */
    public function appinventory() {

        if (Input::has('inventory_sync_date') && Input::get('inventory_sync_date') != '') {
            $allinventory['all'] = Inventory::with('product_sub_category')->where('updated_at', '>', Input::get('inventory_sync_date'))->get();
        } else {
            $allinventory['all'] = Inventory::with('product_sub_category')->get();
        }
        $inventory_date = Inventory::select('updated_at')->
                        orderby('updated_at', 'DESC')->first();
        if (!empty($inventory_date))
            $allinventory[
                    'latest_date'] = $inventory_date->updated_at->toDateTimeString();
        else
            $allinventory['latest_date'] = "";
        return

                json_encode($allinventory);
    }

    /**
     * App get all delivery order
     */
    public function appdelivery_order() {

        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '') {
            $delivery_orders['all'] = DeliveryOrder ::where('updated_at', '>', Input:: get('delivery_order_sync_date'))->orderBy('created_at', 'desc')->with('delivery_product', 'customer')->get();
        } else {
            $delivery_orders['all'] = DeliveryOrder::orderBy('created_at', 'desc')->with('delivery_product', 'customer')->get();
        }
        $delivery_order_obj = new DeliveryOrderController();
        $delivery_orders = $delivery_order_obj->checkpending_quantity($delivery_orders);
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $data['delivery_details'] = $delivery_orders;
        $data['delivery_location'] = $delivery_locations;
        $delivery_order_date = DeliveryOrder::select('updated_at')->orderby
                        ('updated_at', 'DESC')->first();
        if (!empty($delivery_order_date))
            $data[
                    'latest_date'] = $delivery_order_date->updated_at->toDateTimeString();
        else
            $data['latest_date'] = "";

        return json_encode($data);
    }

    /**
     * App get all delievry challan
     */
    public function appalldelivery_challan() {

        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '') {
            $deliverychallans['all'] = DeliveryChallan :: with('customer', 'delivery_challan_products', 'delivery_order')->where('updated_at', '>', Input::get('delivery_challan_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $deliverychallans['all'] = DeliveryChallan ::with('customer', 'delivery_challan_products', 'delivery_order')->orderBy('created_at', 'desc')->get();
        } $deliverychallan_date = DeliveryChallan::select('updated_at')->orderby(
                        'updated_at', 'DESC')->first();
        if (!empty($deliverychallan_date))
            $deliverychallans[
                    'latest_date'] = $deliverychallan_date->updated_at->toDateTimeString();
        else
            $deliverychallans['latest_date'] = "";

        return json_encode($deliverychallans);
    }

    /**
     * App get all unit
     */
    public function appallunit() {

        if (Input::has('unit_sync_date') && Input:: get('unit_sync_date') != '') {
            $units['all'] = Units::where('updated_at', '>', Input::get('unit_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $units['all'] = Units::orderBy('created_at', 'desc')->get();
        }
        $unit_date = Units::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($unit_date)) {
            $units['latest_date'] = $unit_date->updated_at->toDateTimeString();
        } else {
            $units['latest_date'] = "";
        }

        return json_encode($units);
    }

    /**
     * App get all city
     */
    public function appallcity() {

        if (Input::has('city_sync_date') && Input::get('city_sync_date') != '') {
            $cities['all'] = City::with('states')->where('updated_at', '>', Input::get('city_sync_date'))->orderby('updated_at', 'DESC')->get();
        } else {
            $cities['all'] = City::with('states')->orderBy('created_at', 'desc')->get();
        }
        $city_date = City::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($city_date)) {
            $cities['latest_date'] = $city_date->updated_at->toDateTimeString();
        } else {
            $cities['latest_date'] = "";
        }

        return json_encode($cities);
    }

    /**
     * App get all state
     */
    public function appallstate() {

        if (Input::has('state_sync_date') && Input:: get('state_sync_date') != '') {
            $states['all'] = States::where('updated_at', '>', Input::get('state_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $states['all'] = States::orderBy('created_at', 'desc')->get();
        }
        $state_date = States::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($state_date)) {
            $states['latest_date'] = [$state_date->updated_at->toDateTimeString()];
        } else {
            $states['latest_date'] = [];
        }

        return json_encode($states);
    }

    /**
     * App get all customers
     */
    public function appallcustomers() {

        if (Input::has('customer_sync_date') && Input::get('customer_sync_date') != '') {
            $customers['all'] = Customer::where('updated_at', '>', Input::get('customer_sync_date'))->orderBy('tally_name', 'asc')->get();
        } else {
            $customers['all'] = Customer::orderBy('tally_name', 'asc')->get();
        }
        $customer_date = Customer::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($customer_date)) {
            $customers['latest_date'] = $customer_date->updated_at->toDateTimeString();
        } else {
            $customers['latest_date'] = "";
        }
        return

                json_encode($customers);
    }

    /**
     * App get all product category
     */
    public function appallproduct_category() {

        if (Input::has('product_category_sync_date') && Input::get('product_category_sync_date') != '') {
            $product_category['all'] = ProductCategory:: where('updated_at', '>', Input::get('product_category_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $product_category['all'] = ProductCategory::orderBy('created_at', 'desc')->get();
        } $product_category_date = ProductCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($product_category_date)) {
            $product_category['latest_date'] = $product_category_date->updated_at->toDateTimeString();
        } else {
            $product_category['latest_date'] = "";
        }
        return json_encode(
                $product_category);
    }

    /**
     * App get all product sub category
     */
    public function appallproduct_sub_category() {

        if (Input::has('product_subcategory_sync_date') && Input::get('product_subcategory_sync_date') != '') {
            $product_subcategory['all'] = ProductSubCategory::with('product_category')->where('updated_at', '>', Input::get('product_subcategory_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $product_subcategory['all'] = ProductSubCategory::with('product_category')->orderBy('created_at', 'desc')->get();
        } $product_subcategory_date = ProductSubCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($product_subcategory_date)) {
            $product_subcategory['latest_date'] = $product_subcategory_date->updated_at->toDateTimeString();
        } else {
            $product_subcategory['latest_date'] = "";
        }
        return

                json_encode($product_subcategory);
    }

    /**
     * App get all usres
     */
    public function appallusers() {

        if (Input::has('user_sync_date') && Input ::get('user_sync_date') != '') {
            $users_data['all'] = User::where('role_id', '!=', 0)->with('user_role')->where('updated_at', '>', Input::get('user_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $users_data['all'] = User::where('role_id', '!=', 0)->with('user_role')->orderBy('created_at', 'desc')->get();
        }
        $user_date = User::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($user_date)) {
            $users_data['latest_date'] = $user_date->updated_at->toDateTimeString();
        } else {
            $users_data['latest_date'] = "";
        }
        return

                json_encode($users_data);
    }

    /**
     * App get all pending customers
     */
    public function appallpending_customers() {

        if (Input::has('customer_sync_date') && Input::get('customer_sync_date') != '') {
            $customers['all'] = Customer:: where('updated_at', '>', Input::get('customer_sync_date'))->where('customer_status', '=', 'pending')->get();
        } else {
            $customers['all'] = Customer::where('customer_status', '=', 'pending')->where('customer_status', '=', 'pending')->get();
        }
        $customer_date = Customer::select('updated_at')->where('customer_status', '=', 'pending')->orderby('updated_at', 'DESC')->first();
        if (!empty($customer_date)) {
            $customers['latest_date'] = $customer_date->updated_at->toDateTimeString();
        } else {
            $customers['latest_date'] = "";
        }
        return

                json_encode($customers);
    }

    /**
     * App get all pending delivery orders
     */
    public function appallpending_delivery_order() {

        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '') {
            $delivery_data['all'] = DeliveryOrder::with('user', 'customer')->where('updated_at', '>', Input::get('delivery_order_sync_date'))->where('order_status', 'pending')->get();
        } else {
            $delivery_data['all'] = DeliveryOrder::with('user', 'customer')->where('order_status', 'pending')->get();
        } $delivery_order_date = DeliveryOrder::select('updated_at')->where('order_status', 'pending')->orderby('updated_at', 'DESC')->first();
        if (!empty($delivery_order_date)) {
            $delivery_data['latest_date'] = $delivery_order_date->updated_at->toDateTimeString();
        } else {
            $delivery_data['latest_date'] = "";
        }
        return

                json_encode($delivery_data);
    }

    /**
     * App get all purchase orders
     */
    public function appallpurchaseorders() {

        if (Input::has('purchase_order_sync_date') && Input::get('purchase_order_sync_date') != '') {
            $purchase_orders['all'] = PurchaseOrder:: with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')->where('updated_at', '>', Input::get('purchase_order_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $purchase_orders['all'] = PurchaseOrder:: with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')->orderBy('created_at', 'desc')->get();
        } $purchase_order_date = PurchaseOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_order_date)) {
            $purchase_orders['latest_date'] = $purchase_order_date->updated_at->toDateTimeString();
        } else {
            $purchase_orders['latest_date'] = "";
        }
        return

                json_encode($purchase_orders);
    }

    /**
     * App get all purchase advise
     */
    public function appallpurchaseorder_advise() {

        if (Input::has('purchase_advise_sync_date') && Input::get('purchase_advise_sync_date') != '') {
            $purchase_advise['all'] = PurchaseAdvise::with('supplier', 'purchase_products')->where('updated_at', '>', Input::get('purchase_advise_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $purchase_advise ['all'] = PurchaseAdvise::with('supplier', 'purchase_products')->orderBy('created_at', 'desc')->get();
        } $purchase_advise_date = PurchaseAdvise::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_advise_date)) {
            $purchase_advise['latest_date'] = $purchase_advise_date->updated_at->toDateTimeString();
        } else {
            $purchase_advise['latest_date'] = "";
        }
        return json_encode(
                $purchase_advise);
    }

    /**
     * App get all pending purchase advise
     */
    public function appallpending_purchase_advice() {

        if (Input::has('purchase_advise_sync_date') && Input::get('purchase_advise_sync_date') != '') {
            $purchase_advise['all'] = PurchaseAdvise::with('supplier', 'purchase_products')->where('updated_at', '>', Input::get('purchase_advise_sync_date'))->where('advice_status', '=', 'in_process')->orderBy('created_at', 'desc')->get();
        } else {
            $purchase_advise['all'] = PurchaseAdvise::with('supplier', 'purchase_products')->where('advice_status', '=', 'in_process')->orderBy('created_at', 'desc')->get();
        } $purchase_advise_date = PurchaseAdvise ::select('updated_at')->where('advice_status', '=', 'in_process')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_advise_date)) {
            $purchase_advise['latest_date'] = $purchase_advise_date->updated_at->toDateTimeString();
        } else {
            $purchase_advise['latest_date'] = "";
        }
        return

                json_encode($purchase_advise);
    }

    /**
     * App get all purchase challan
     */
    public function appallpurchase_challan() {

        if (Input::has('purchase_challan_sync_date') && Input::get('purchase_challan_sync_date') != '') {
            $purchase_challan['all'] = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')->where('updated_at', '>', Input::get('purchase_challan_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $purchase_challan['all'] = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')->orderBy('created_at', 'desc')->get();
        } $purchase_challan_date = PurchaseChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_challan_date)) {
            $purchase_challan['latest_date'] = $purchase_challan_date->updated_at->toDateTimeString();
        } else {
            $purchase_challan['latest_date'] = "";
        }
        return json_encode(
                $purchase_challan);
    }

    /**
     * App get all purchase order daybook
     */
    public function appallpurchase_order_daybook() {

        if (Input::has('purchase_orderdaybook_sync_date') && Input::get('purchase_orderdaybook_sync_date') != '') {
            $purchase_daybook['all'] = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details')->where('order_status', 'completed')->where('updated_at', '>', Input::get('purchase_orderdaybook_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $purchase_daybook['all'] = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details')->where('order_status', 'completed')->orderBy('created_at', 'desc')->get();
        } $purchase_daybook_date = PurchaseChallan::select('updated_at')->where('order_status', 'completed')->orderby('updated_at', 'DESC')->first();
        if (!empty($purchase_daybook_date)) {
            $purchase_daybook['latest_date'] = $purchase_daybook_date->updated_at->toDateTimeString();
        } else {
            $purchase_daybook['latest_date'] = "";
        }
        return

                json_encode($purchase_daybook);
    }

    /**
     * App get all location
     */
    public function applocation() {

        if (Input::has('delivery_location_sync_date') && Input::get('delivery_location_sync_date') != '') {
            $delivery_location['all'] = DeliveryLocation::with('city', 'states')->where('status', '=', 'permanent')->where('created_at', '>', Input::get('delivery_location_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $delivery_location['all'] = DeliveryLocation::with('city', 'states')->where('status', '=', 'permanent')->orderBy('created_at', 'desc')->get();
        } $delivery_location_date = DeliveryLocation::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($delivery_location_date)) {
            $delivery_location['latest_date'] = $delivery_location_date->updated_at->toDateTimeString();
        } else {
            $delivery_location['latest_date'] = "";
        }
        return

                json_encode($delivery_location);
    }

    /*
     * App sync customer inquiry
     */

    public function appsync_customerinquiry() {

        $data = Input::all();
        if (Input::has('inquiry')) {
            $inquiries = (json_decode($data['inquiry']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('inquiry_product')) {
            $inquiryproduct = (json_decode($data['inquiry_product']));
        }
        if (Input::has('customer_id')) {
            $customer_id = (json_decode($data['customer_id']));
        }
        $inquiry_response = [];
        $customer_list = [];
        if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
            $last_sync_date = Input:: get('inquiry_sync_date');
            $inquiry_added_server = Inquiry::where('created_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->with('inquiry_products')->get();
            $inquiry_response ['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();

            $inquiry_updated_server = Inquiry::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('customer_id', '=', $customer_id)->with('inquiry_products')->get();
            $inquiry_response ['inquiry_server_updated'] = ($inquiry_updated_server && count($inquiry_updated_server) > 0) ? $inquiry_updated_server : array();

            /* Send Updated customers */ $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('id', '=', $customer_id)->get();
            $inquiry_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */ $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->where('id', '=', $customer_id)->get();
            $inquiry_response ['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $inquiry_added_server = Inquiry::with('inquiry_products')->where('customer_id', '=', $customer_id)->get();
            $inquiry_response ['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();
        }
        if (isset($inquiries)) {
            foreach ($inquiries as $key => $value) {

                if ($value->server_id > 0) {
                    $add_inquiry = Inquiry::find($value->server_id);
                    if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                        $add_customers = new Customer();
                        $add_customers->owner_name = $value->customer_name;
                        $add_customers->contact_person = $value->customer_contact_peron;
                        $add_customers->phone_number1 = $value->customer_mobile;
                        $add_customers->credit_period = $value->customer_credit_period;
                        $add_customers->customer_status = 'Pending';
                        $add_customers->save();
                        $customer_list[$value->id] = $add_customers->id;
                    }
                    /* Update customer ends here */ $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                    $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                    $datetime = new DateTime($date);
                    $add_inquiry->vat_percentage = ($value->vat_percentage == "" || empty($value->vat_percentage)) ? 0 : $value->vat_percentage;
                    if (($value->other_location == "") || empty($value->other_location)) {
                        $add_inquiry->delivery_location_id = $value->delivery_location_id;
                        $add_inquiry->location_difference = $value->location_difference;
                    } else {
                        $add_inquiry->delivery_location_id = 0;
                        $add_inquiry->other_location = $value->other_location;
                        $add_inquiry->location_difference = $value->location_difference;
                    }
                    if (isset($customer_list[$value->id]) && $customer_list[$value->id] > 0) {
                        $add_inquiry->customer_id = $customer_list[$value->id];
                    } else {
                        $add_inquiry->customer_id = $value->customer_server_id;
                        $update_customers = Customer::find($value->customer_server_id);
                        $update_customers->addNewCustomer($value->customer_name, $value->customer_contact_peron, $value->customer_mobile, $value->customer_credit_period);
                    }
                    $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                    $add_inquiry->remarks = ($value->remarks != '') ? $value->remarks : '';
                    $add_inquiry->inquiry_status = $value->inquiry_status;
                    $delete_old_inquiry_products = InquiryProducts::where('inquiry_i d', '=', $value->server_id)->delete();
                    foreach ($inquiryproduct as $product_data) {
                        $inquiry_products = array();
                        if ($product_data->inquiry_id == $value->id) {
                            $inquiry_products = [
                                'inquiry_id' => $value->server_id,
                                'product_category_id' => $product_data->inquiry_product_id,
                                'unit_id' => $product_data->unit_id,
                                'quantity' => $product_data->quantity,
                                'price' => $product_data->price,
                                'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                'remarks' => '',
                            ];
                            $add_inquiry_products = InquiryProducts::create($inquiry_products);
                        }
                    }
                    $inquiry_products = InquiryProducts::where('inquiry_id', '=', $value->server_id)->first();
                    $add_inquiry->updated_at = $inquiry_products->updated_at;
                    $add_inquiry->save();
                    $inquiry_response[$value->server_id] = Inquiry::find($value->server_id);
                    $inquiry_response[$value->server_id]['inquiry_products'] = InquiryProducts::where('inquiry_id', '=', $value->server_id)->get();
                } else {

                    if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                        $add_customers = new Customer();
                        $add_customers->owner_name = $value->customer_name;
                        $add_customers->contact_person = $value->customer_contact_peron;
                        $add_customers->phone_number1 = $value->customer_mobile;
                        $add_customers->credit_period = $value->customer_credit_period;
                        $add_customers->customer_status = 'Pending';
                        $add_customers->save();
                        $customer_list[$value->id] = $add_customers->id;
                    }
                    $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                    $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                    $datetime = new DateTime($date);
                    $add_inquiry = new Inquiry ( );
                    $add_inquiry->customer_id = (!empty($value->customer_server_id) && $value->customer_server_id > 0) ? $value->customer_server_id : $customer_list[$value->id];
                    $add_inquiry->created_by = 1;
                    if (($value->other_location == "") || empty($value->other_location)) {
                        $add_inquiry->delivery_location_id = $value->delivery_location_id;
                        $add_inquiry->location_difference = $value->location_difference;
                    } else {
                        $add_inquiry->delivery_location_id = 0;
                        $add_inquiry->other_location = $value->other_location;
                        $add_inquiry->location_difference = $value->location_difference;
                    }

                    $add_inquiry->vat_percentage = ($value->vat_percentage != "") ? $value->vat_percentage : 0;
                    $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                    $add_inquiry->remarks = ($value->remarks != '') ? $value->remarks : '';
                    $add_inquiry->inquiry_status = "Pending";
                    $add_inquiry->save();
                    $inquiry_id = $add_inquiry->id;
                    foreach ($inquiryproduct as $product_data) {
                        $inquiry_products = array();
                        if ($product_data->inquiry_id == $value->id) {
                            $inquiry_products = [
                                'inquiry_id' => $inquiry_id,
                                'product_category_id' => $product_data->inquiry_product_id,
                                'unit_id' => $product_data->unit_id,
                                'quantity' => $product_data->quantity,
                                'price' => $product_data->price,
                                'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                                'remarks' => '',
                            ];
                            $add_inquiry_products = InquiryProducts::create($inquiry_products);
                        }
                    }
                    $inquiry_response[$value->id] = $inquiry_id;
                }
            }
        }
        if (count($customer_list) > 0) {
            $inquiry_response['customer_new'] = $customer_list;
        }
        if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
            $inquiry_response['inquiry_deleted'] = Inquiry::withTrashed()->where('customer_id', '=', $customer_id)->where('deleted_at', '>=', Input::get('inquiry_sync_date'))->select('id')->get();
        }
        $inquiry_date = Inquiry::select('updated_at')->orderby('updated_at', 'DESC')->where('customer_id', '=', $customer_id)->first();

        if (!empty($inquiry_date))
            $inquiry_response[
                    'latest_date'] = $inquiry_date->updated_at->toDateTimeString();
        else
            $inquiry_response['latest_date'] = "";

        return

                json_encode($inquiry_response);
    }

    /**
     * App sync customer order
     */
    public function appsync_customerorder() {

        $data = Input::all();
        $order_response = [];
        $customer_list = [];
        if (Input::has('order')) {
            $orders = (json_decode($data['order']));
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('order_product')) {
            $orderproduct = (json_decode($data['order_product']));
        }
        if (Input::has('customer_id')) {
            $customer_id = (json_decode($data['customer_id']) );
        }
        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '') {
            $last_sync_date = Input:: get('order_sync_date');
            $order_added_server = Order::where('created_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->with('all_order_products')->get();
            $order_response ['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();

            $order_updated_server = Order::where('updated_at', '>', $last_sync_date)->where('customer_id', '=', $customer_id)->whereRaw('updated_at > created_at')->with('all_order_products')->get();
            $order_response ['order_server_updated'] = ($order_updated_server && count($order_updated_server) > 0) ? $order_updated_server : '';

            /* Send Updated customers */ $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->where('id', '=', $customer_id)->whereRaw('updated_at > created_at')->get();
            $order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */ $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->where('id', '=', $customer_id)->get();
            $order_response ['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $order_added_server = Order::with('all_order_products')->where('customer_id', '=', $customer_id)->get();
            $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();
        }

        foreach ($orders as $key => $value) {
            if ($value->server_id == 0) {
                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                    $add_customers = new Customer();
                    $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                    $customer_list[$value->id] = $add_customers->id;
                }
                if ($value->supplier_server_id == 0) {
                    $order_status = 'warehouse';
                    $supplier_id = 0;
                } else {
                    $other_location_difference;
                    $order_status = 'supplier';
                    $supplier_id = $value->supplier_server_id;
                }
                $order = new Order();
                $order->order_source = $order_status;
                $order->supplier_id = $supplier_id;
                $order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                $order->created_by = 1;
//                $order->vat_percentage = ($value->vat_percentage == '') ? '' : $value->vat_percentage;
                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                $datetime = new DateTime($date);
                $order->expected_delivery_date = $datetime->format('Y-m-d');
                $order->remarks = $value->remarks;
                $order->flaged = ($value->flaged != '') ? $value->flaged : 0;
                $order->order_status = "Pending";
                if ($value->delivery_location_id > 0) {
                    $order->delivery_location_id = $value->delivery_location_id;
                    $order->location_difference = $value->location_difference;
                } else {
                    $order->delivery_location_id = 0;
                    $order->other_location = $value->other_location;
                    $order->location_difference = $value->other_location_difference;
                }
                $order->save();
                $order_id = $order->id;
                $order_products = array();
                foreach ($orderproduct as $product_data) {
                    if ($product_data->order_id == $value->id) {
                        $order_products = [
                            'order_id' => $order_id,
                            'order_type' => 'order',
                            'product_category_id' => $product_data->product_category_id,
                            'unit_id' => $product_data->unit_id,
                            'quantity' => $product_data->quantity,
                            'price' => $product_data->price,
                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                            'remarks' => '',
                        ];
                        AllOrderProducts::create($order_products);
                    }
                }
                $order_response[$value->id] = $order_id;
            } else {
                $order = Order::find($value->server_id);
                if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                    $add_customers = new Customer();
                    $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
                    $customer_list[$value->id] = $add_customers->id;
                }
                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expected_delivery_date);
                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                $datetime = new DateTime($date);
//                $order->vat_percentage = ($value->vat_percentage == '') ? '' : $value->vat_percentage;
                if ($value->supplier_server_id == 0) {
                    $order_status = 'warehouse';
                    $supplier_id = 0;
                } else {
                    $other_location_difference;
                    $order_status = 'supplier';
                    $supplier_id = $value->supplier_server_id;
                }
                $order->supplier_id = $supplier_id;
                $order->remarks = ($value->remarks != '') ? $value->remarks : '';
                $order->order_status = $value->order_status;
                $order->flaged = ($value->flaged != '') ? $value->flaged : 0;
                if ($value->delivery_location_id > 0) {
                    $order->delivery_location_id = $value->delivery_location_id;
                    $order->location_difference = $value->location_difference;
                } else {
                    $order->delivery_location_id = 0;
                    $order->other_location = $value->other_location;
                    $order->location_difference = $value->other_location_difference;
                }
                $order->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
                $order->expected_delivery_date = $datetime->format('Y-m-d');
                AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->delete();
                foreach ($orderproduct as $product_data) {
                    $order_products = array();
                    if ($product_data->order_id == $value->id) {
                        $order_products = [
                            'order_id' => $value->server_id,
                            'product_category_id' => $product_data->product_category_id,
                            'unit_id' => $product_data->unit_id,
                            'quantity' => $product_data->quantity,
                            'price' => $product_data->price,
                            'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
                            'remarks' => '',
                        ];
                        AllOrderProducts::create($order_products);
                    }
                }
                $order_prod = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $value->server_id)->first();
                $order->updated_at = $order_prod->updated_at;
                $order->save();
                $order_response[$value->server_id] = Order::find($value->server_id);
                $order_response[$value->server_id]['all_order_products'] = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->get();
            }
        }
        if (count($customer_list) > 0) {
            $order_response ['customer_new'] = $customer_list;
        }
        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '' && Input::get('order_sync_date') != NULL) {
            $order_response['order_deleted'] = Order::withTrashed()->where('customer_id', '=', $customer_id)->where('deleted_at', '>=', Input::get('order_sync_date'))->select('id')->get();
        }
        $order_date = Order::select('updated_at')->where('customer_id', '=', $customer_id)->orderby('updated_at', 'DESC')->first();
        if (!empty($order_date))
            $order_response['latest_date'] = $order_date->updated_at->toDateTimeString();
        else
            $order_response['latest_date'] = "";

        return json_encode($order_response);
    }

    /**
     * App sync Generate Serial Number DO
     */
    public function appprintdeliveryorder() {
        $data = Input::all();
        $server_id = json_decode($data['delivery_order']);

        if (isset($server_id)) {
            $id = $server_id[0]->server_id;
            $DO = DeliveryOrder::select('serial_no')->find($id);

            if ($DO['serial_no'] != "") {
                $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details', 'unit', 'location')->find($id);
            } else {
                $current_date = date("m/d/");
                $date_letter = 'DO/' . $current_date . "" . $id;
                DeliveryOrder:: where('id', $id)->update(array('serial_no' => $date_letter));
                $delivery_data = DeliveryOrder::with('customer', 'delivery_product.order_product_details', 'unit', 'location')->find($id);
            }
        } else {
            $delivery_data = "";
        }
        return json_encode($delivery_data);
    }

    public function appprintdeliverychallan() {
        $data = Input::all();
        $server_id = json_decode($data['delivery_challan']);


        if ($server_id[0]->server_id != "") {
            $id = $server_id[0]->server_id;
            $DC = DeliveryChallan::with('delivery_challan_products')->find($id);
            $update_delivery_challan = $DC;
            $vat_applicable = 0;
            $total_vat_amount = 0;

            if ($DC->serial_number != "") {
                $delivery_data = DeliveryChallan::where('id', '=', $id)
                                ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();
            } else {
                if (isset($DC->delivery_order_id)) {
                    $delivery_order_id = $DC->delivery_order_id;
                } else {
                    $delivery_order_id = 0;
                }

                if ($delivery_order_id != 0) {
                    $DO = DeliveryOrder::where('id', '=', $delivery_order_id)->get();
                    $serial_number_delivery_order = $DO[0]['serial_no'];
                } else {
                    $serial_number_delivery_order = 0;
                }
                $current_date = date("m/d/");

                if (isset($update_delivery_challan->delivery_challan_products) && count($update_delivery_challan->delivery_challan_products) > 0) {
                    foreach ($update_delivery_challan->delivery_challan_products as $key => $delivery_challan_products) {
                        if ($delivery_challan_products->vat_percentage > 0) {
                            $vat_applicable = 1;
                            if ($delivery_challan_products->vat_percentage != '' && $delivery_challan_products->vat_percentage > 0) {
                                $total_vat_amount = $total_vat_amount + (($delivery_challan_products->present_shipping * $delivery_challan_products->price * $delivery_challan_products->vat_percentage) / 100);
                            }
                        }
                    }
                }
                if ($update_delivery_challan->ref_delivery_challan_id == 0) {
                    $modified_id = $id;
                } else {
                    $modified_id = $update_delivery_challan->ref_delivery_challan_id;
                }
                $date_letter = 'DC/' . $current_date . $modified_id . (($vat_applicable > 0) ? "P" : "A");


                $update_delivery_challan->serial_number = $date_letter;
                $update_delivery_challan->challan_status = 'completed';
                $update_delivery_challan->save();
                $delivery_challan_obj = new DeliveryChallanController();
                $delivery_challan_obj->checkpending_quantity();
                $allorder = DeliveryChallan::where('id', '=', $id)->where('challan_status', '=', 'completed')
                                ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();
                //        $calculated_vat_value = $allorder->grand_price * ($allorder->vat_percentage / 100);
                //        $allorder['calculated_vat_price'] = $calculated_vat_value;
                $number = $allorder->grand_price;
                $exploded_value = explode(".", $number);

                if (!isset($exploded_value[1])) {
                    $number = number_format($number, 2, '.', '');
                    $allorder->grand_price = $number;
                    $allorder->save();
                    $exploded_value = explode(".", $number);
                }

                $result_paisa = $exploded_value[1] % 10;
                if (isset($exploded_value[1]) && strlen($exploded_value[1]) > 1 && $result_paisa != 0) {
                    $convert_value = $delivery_challan_obj->convert_number_to_words($allorder->grand_price);
                } else {
                    $convert_value = $delivery_challan_obj->convert_number($allorder->grand_price);
                }
                $allorder['convert_value'] = $convert_value;

                $delivery_data = DeliveryChallan::where('id', '=', $id)
                                ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();
            }
        } else {
            $delivery_data = "";
        }

        return json_encode($delivery_data);
    }

// All Functions added by user 157 for app ends here //

    /*
     * Unwanted code starts here 
     * 
     */

    /*
      public function demorouteandroid() {
      return json_encode(array(
      'result' => true,
      'message' => 'Thank you for visiting'), 200
      );
      }

      public function devicetesting() {
      $agent = new Agent();
      if ($agent->isMobile()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This device is mobile'), 200
      );
      }
      if ($agent->isTablet()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This device is tablet'), 200
      );
      }
      }

      public function phonetesting() {
      $agent = new Agent();
      if ($agent->isPhone()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This is phone device'), 200
      );
      } else {
      return json_encode(array(
      'result' => true,
      'message' => 'This is not a phone device'), 200
      );
      }
      }

      public function robottesting() {
      $agent = new Agent();
      if ($agent->isRobot()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This is robot device'), 200
      );
      } else {
      return json_encode(array(
      'result' => true,
      'message' => 'This is not a robot device'), 200
      );
      }
      }

      public function platformname() {
      $agent = new Agent();
      $platform = $agent->platform();

      $result = [];
      $result[] = $platform;
      return json_encode($result);
      }

      public function platformversion() {
      $agent = new Agent();
      $platform = $agent->platform();
      $version = $agent->version($platform);
      $result = [];
      $result[] = $version;
      return json_encode($result);
      }

      public function browserversion() {
      $agent = new Agent();
      $browser = $agent->browser();
      $version = $agent->version($browser);
      $result = [];
      $result[] = $version;
      return json_encode($result);
      }

      public function devicename() {
      $agent = new Agent();
      $device = $agent->device();

      $result = [];
      $result[] = $device;
      return json_encode($result);
      }

      public function androidtesting() {
      $agent = new Agent();

      if ($agent->isNexus()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This is Nexus device'), 200
      );
      }
      if ($agent->isSafari()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This is Safari device'), 200
      );
      }
      if ($agent->isAndroidOS()) {
      return json_encode(array(
      'result' => true,
      'message' => 'This is android device'), 200
      );
      } else {
      return json_encode(array(
      'result' => true,
      'message' => 'This is not an android device'), 200
      );
      }
      }
     */
    /*
     * Unwanted code ends here
     * 
     */

    /**
     * Show the application dashboard to the user.
     */
    public function

    index() {
        return view('dashboard');
    }

    public function updatedata() {
        $product_data = ProductCategory::all();
        foreach ($product_data as $value) {
            $product = ProductCategory::find($value->id);

            $product->price_new = $product->price;
            $product->save();
        }
    }

    public function showupdatedata() {
        $product_data = ProductCategory::all();
        echo "<table>";
        foreach ($product_data as $value) {
            echo "<tr><td>" . $value->id . "</td><td>" . $value->product_type_id . "</td><td>" . $value->product_category_name . "</td><td>" . $value->price .
            "</td><td>" . $value->price_new . "</td></tr>";
        }
        echo "</table>";
    }

    public function update_delivery_location() {
        $product_data = Customer::where('delivery_location_id', '=', 0)->update(['delivery_location_id' => 32]);
    }

    /**
     * This function takes backup of whole datbase of local machine
     * Note : just change the $DBUSER, $DBPASSWD, $DATABASE values as per your need and you can use this for any project
     */
    public function database_backup_local() {
        $DBUSER = "root";
        $DBPASSWD = "root123";
        $DATABASE = "steel-trading-automation";

        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment;
        filename = "' . $filename . '"');

        $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";

        passthru($cmd);

        exit(0);
    }

    /**
     * This function takes backup of whole datbase of test server machine
     */
    public function database_backup_test() {
        $DBUSER = "vikasags_vikuser";
        $DBPASSWD = "CFpNH.#JblZe";
        $DATABASE = "vikasags_vikasdb";
        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment;
        filename = "' . $filename . '"');

        $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";

        passthru($cmd);

        exit(0);
    }

    /**
     * This function takes backup of whole datbase of production server machine
     */
    public function database_backup_live() {
        $DBUSER = "vikaserp_agsus";
        $DBPASSWD = "passags756";
        $DATABASE = "vikaserp_ags";
        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";
        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment;
        filename = "' . $filename . '"');
        $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";
        passthru($cmd);
        exit(0);
    }

    /**
     * This function send a sms
     */
    public function test_sms() {

        $customer_id = 1648;

        $customer = Customer::with('manager')->find($customer_id);

        if (count($customer) > 0) {
            $total_quantity = '';
            $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nyour inq. has been logged for foll. ";


            $str .= " prices and avlblty will be qtd shortly \nVIKAS ASSOCIATES";
            if (App::environment('development')) {
                $phone_number = \Config::get('smsdata.send_sms_to');
            } else {
                $phone_number = '9102069701155';
            }



            $msg = urlencode($str);
            $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                 echo "<pre>";
//                print_r( $url);
//                echo "</pre>";
            if (SEND_SMS === true) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
            }
        }

        $result = "Text messgae  send to $phone_number - $curl_scraped_page";
        return json_encode($result);
    }

    /*
      |------------------------------------------------
      | SEND SMS TO THE CUSTOMER WITH CREATE INQUIRY
      |------------------------------------------------
     */

    function appsyncinquiry_sms() {
        $data = Input::all();
        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product') && Input::has('sendsms') && Input::has('user')) {
            $inquiries = (json_decode($data['inquiry']));
            $customers = (json_decode($data['customer']));
            $inquiryproduct = (json_decode($data['inquiry_product']));
            $user = (json_decode($data['user']));

            $customer_id = $customers[0]->id;

            if (Input::has('sendsms')) {
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nyour inq. has been logged for foll. ";
                    foreach ($inquiryproduct as $product_data) {
                        $product_details = InquiryProducts::with('inquiry_product_details')->find($product_data->id);

                        if (isset($product_details['inquiry_product_details']->alias_name)) {
                            $product_size = ProductSubCategory::find($product_data->id);

                            $str .= $product_details['inquiry_product_details']->alias_name . '- ' . $product_data->quantity . ', ';
                            if ($product_data->unit_id == 1) {
                                $total_quantity = $total_quantity + $product_data->quantity;
                            }
                            if ($product_data->unit_id == 2) {
                                $total_quantity = $total_quantity + $product_data->quantity * $product_size->weight;
                            }
                            if ($product_data->unit_id == 3) {
                                $total_quantity = $total_quantity + ($product_data->quantity / $product_size->standard_length ) * $product_size->weight;
                            }
                        }
                        else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Inquiry not found.";
                            return json_encode($result);
                        }
                    }
                    $str .= " prices and avlblty will be qtd shortly \nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = \Config::get('smsdata.send_sms_to');
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

                if (count($customer->manager) > 0) {
                    $str = "Dear '" . $customer->manager->first_name . "'\n'" . $user[0]->first_name . "' has logged an enquiry for '" . $customer->owner_name . "', '" . round($total_quantity, 2) . "'. Kindly chk and qt. Vikas Associates";
                    if (App::environment('development')) {
                        $phone_number = \Config::get('smsdata.send_sms_to');
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
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

    /*
      |------------------------------------------------
      | SEND SMS TO THE CUSTOMER WITH UPDATED QUOTATIONS
      |------------------------------------------------
     */

    function appsyncinquiryedit_sms() {
        $input = Input::all();
        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product') && Input::has('sendsms') && Input::has('user')) {
            $inquiries = (json_decode($input['inquiry']));
            $customers = (json_decode($input['customer']));
            $inquiryproduct = (json_decode($input['inquiry_product']));
            $user = (json_decode($input['user']));
            $customer_id = $customers[0]->id;

            if (Input::has('sendsms')) {
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';

                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour inq. has been edited for foll. ";
                    foreach ($inquiryproduct as $product_data) {
                        $product_details = InquiryProducts::with('inquiry_product_details')->find($product_data->id);


                        if ($product_details['inquiry_product_details']->alias_name != "") {
                            $str .= $product_details['inquiry_product_details']->alias_name . ' - ' . $product_data->quantity . ', ';
                            $total_quantity = $total_quantity + $product_data->quantity;
                        }
                        else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Inquiry not found.";
                            return json_encode($result);
                        }
                    }
                    $str .= " prices and avlblty will be qtd shortly. \nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = \Config::get('smsdata.send_sms_to');
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

                    if (count($customer['manager']) > 0) {
                        $str = "Dear '" . $customer->manager->first_name . "'\nDT " . date("j M, Y") . "\n" . $user[0]->first_name . " has edited an enquiry for '" . $customer->owner_name . ", '" . $total_quantity . "' Kindly chk and qt.\nVIKAS ASSOCIATES";
                        if (App::environment('development')) {
                            $phone_number = \Config::get('smsdata.send_sms_to');
                        } else {
                            $phone_number = $customer->manager->mobile_number;
                        }
                        $msg = urlencode($str);
                        $url = SMS_URL . "?user = " . PROFILE_ID . "&pwd = " . PASS . "&senderid = " . SENDER_ID . "&mobileno = " . $phone_number . "&msgtext = " . $msg . "&smstype = 0";
                        if (SEND_SMS === true) {
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $curl_scraped_page = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

    /*
     * ------------------- --------------
     * SEND SMS TO CUSTOMER FOR NEW ORDER
     * ----------------------------------
     */

    function appsyncorder_sms() {
        $input = Input::all();
//
//        $order = Order::with('customer','all_order_products')->find(1);
//        echo "<pre>";
//        print_r(json_encode($order));
//        echo "</pre>";
//        exit;

        if (Input::has('order') && Input::has('customer') && Input::has('order_product') && Input::has('sendsms') && Input::has('user')) {
            $orders = (json_decode($input['order']));
            $customers = (json_decode($input['customer']));
            $orderproduct = (json_decode($input['order_product']));
            $user = (json_decode($input['user']));
            $customer_id = $customers[0]->id;

            if (Input::has('sendsms')) {
                $customer = Customer::with('manager')->find($customer_id);
                if ($customer->phone_number1 != "") {
                    if (count($customer) > 0) {
                        $total_quantity = '';
                        $str = "Dear '" . $customer->owner_name . "'\n your order has been logged as following \n";
                        foreach ($orderproduct as $product_data) {

                            $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);

                            if (isset($product_details['order_product_details']) && $product_details['order_product_details']->alias_name != "") {
                                $product = ProductSubCategory::find($product_data->product_category_id);

                                $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ", \n";
                                if ($product_data->unit_id == 1) {
                                    $total_quantity = $total_quantity + $product_data->quantity;
                                }
                                if ($product_data->unit_id == 2) {
                                    $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
                                }
                                if ($product_data->unit_id == 3) {
                                    $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
                                }
                            }
                            else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Order not found.";
                            return json_encode($result);
                        }
                        }


                        $str .= " meterial will be desp by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";

                        if (App::environment('development')) {
                            $phone_number = \Config::get('smsdata.send_sms_to');
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
                        if (count($customer['manager']) > 0) {
//                        $str = "Dear '" . $customer['manager']->first_name . "'\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has logged an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk.\nVIKAS ASSOCIATES";
                            $str = urlencode($str);
                            if (App::environment('development')) {
                                $phone_number = \Config::get('smsdata.send_sms_to');
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
                    }
                }
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

    /*
     * ------------------- --------------
     * SEND SMS TO CUSTOMER FOR UPDATE/EDIT ORDER
     * ----------------------------------
     */

    function appsyncorderedit_sms() {

        $input = Input::all();

        if (Input::has('order') && Input::has('customer') && Input::has('order_product') && Input::has('sendsms') && Input::has('user')) {
            $orders = (json_decode($input['order']));
            $customers = (json_decode($input['customer']));
            $orderproduct = (json_decode($input['order_product']));
            $user = (json_decode($input['user']));
            $customer_id = $customers[0]->id;

            if (Input::has('sendsms')) {
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nYour order has been edited and changed as following \n";
                    foreach ($orderproduct as $product_data) {

                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);

                        if (isset($product_details['order_product_details']) && $product_details['order_product_details']->alias_name != "") {
                            $product = ProductSubCategory::find($product_data->id);
                            $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                            if ($product_data->unit_id == 1) {
                                $total_quantity = $total_quantity + $product_data->quantity;
                            }
                            if ($product_data->unit_id == 2) {
                                $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
                            }
                            if ($product_data->unit_id == 3) {
                                $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
                            }
                        } else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Order not found.";
                            return json_encode($result);
                        }
                    }
                    $str .= " meterial will be desp by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = \Config::get('smsdata.send_sms_to');
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
                    if (count($customer->manager) > 0) {
//                    $str = "Dear '" . $customer->manager->first_name . "'\n'" . Auth::user()->first_name . "' has edited and changed an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk. \nVIKAS ASSOCIATES";
                        if (App::environment('development')) {
                            $phone_number = \Config::get('smsdata.send_sms_to');
                        } else {
                            $phone_number = $customer->manager->mobile_number;
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
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

    /*
      |------------------- -----------------------
      | SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
      | -------------------------------------------
     */

    function appsyncdeliveryorder_sms() {
//        $deliveryorder = DeliveryOrder::with('customer','delivery_product')->find(1);
//        echo "<pre>";
//        print_r(json_encode($deliveryorder));
//        echo "</pre>";
//        exit;
        $data = Input::all();

        if (Input::has('delivery_order') && Input::has('customer') && Input::has('delivery_order_product') && Input::has('user') && Input::has('sendsms')) {


            $delivery_orders = (json_decode($data['delivery_order']));
            $customers = (json_decode($data['customer']));
            $deliveryorderproducts = (json_decode($data['delivery_order_product']));
            $user = (json_decode($data['user']));
            $customer_id = $customers[0]->id;

            $send_sms = Input::get('sendsms');
            if ($send_sms == 'true') {

                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT" . date("j M, Y") . "\nYour DO has been created as follows ";
                    foreach ($deliveryorderproducts as $product_data) {

                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);


                        if (isset($product_details['order_product_details']->alias_name) && $product_details['order_product_details']->alias_name != "") {
                            $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ',';
                        } else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Delivery Order not found.";
                            return json_encode($result);
                        }

                        $total_quantity = $total_quantity + $product_data->quantity;
                    }
                    $str .= " Trk No. " . $delivery_orders[0]->vehicle_number . ", Drv No. " . $delivery_orders[0]->driver_contact_no . ". \nVIKAS ASSOCIATES";
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
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

    /*
      | ------------------- -----------------------
      | SEND SMS TO CUSTOMER FOR NEW DELIVERY CHALLAN
      | -------------------------------------------
     */

    function appsyncdeliverychallan_sms() {
        $data = Input::all();


//         $deliveryorder = DeliveryChallan::with('customer','all_order_products')->find(1);
//        echo "<pre>";
//        print_r(json_encode($deliveryorder));
//        echo "</pre>";
//        exit;


        if (Input::has('delivery_challan') && Input::has('customer') && Input::has('delivery_challan_product') && Input::has('user') && Input::has('sendsms')) {
            $delivery_challans = (json_decode($data['delivery_challan']));
            $customers = (json_decode($data['customer']));
            $deliverychallanproducts = (json_decode($data['delivery_challan_product']));
            $user = (json_decode($data['user']));
            $customer_id = $customers[0]->id;


            $send_sms = Input::get('sendsms');
            if ($send_sms == 'true') {

                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $total_actual_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour meterial has been desp as follows ";
                    foreach ($deliverychallanproducts as $product_data) {
                        $product = ProductSubCategory::find($product_data->product_category_id);

                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
//                    $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                        $total_quantity = $total_quantity + $product_data->quantity;
                        $total_actual_quantity = $total_actual_quantity + $product_data->actual_quantity;
                    }

                    $delivery_order = DeliveryOrder::find($delivery_challans[0]->delivery_order_id);

                    if (isset($delivery_order)) {
                        $str .= " Trk No. " . $delivery_order->vehicle_number .
                                ", Drv No. " . $delivery_order->driver_contact_no .
//                            ", Qty " . $product_data->sum('actual_quantity') .
                                ", Qty " . $total_actual_quantity .
                                ", Amt " . $delivery_challans[0]->grand_price .
                                ", Due by: " . date("jS F, Y", strtotime($delivery_order->expected_delivery_date)) .
                                "\nVIKAS ASSOCIATES";
                    } else {
                        $result['send_message'] = "Error";
                        $result['reasons'] = "Delivery Challan not found.";
                        return json_encode($result);
                    }

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
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

    /*
     * ------------------- ------------------------
     * SEND SMS TO CUSTOMER FOR NEW PURCHASE ADVISE
     * --------------------------------------------
     */

    function appsyncpurchaseadvise_sms() {
        $input_data = Input::all();


//         $purchaseadvise = PurchaseAdvise::with('supplier','purchase_products')->find(1233);
//        echo "<pre>";
//        print_r(json_encode($purchaseadvise));
//        echo "</pre>";
//        exit;



        if (Input::has('purchase_advice') && Input::has('customer') && Input::has('purchase_advice_product') && Input::has('user') && Input::has('sendsms')) {
            $purchaseadvices = (json_decode($input_data['purchase_advice']));
            $customers = (json_decode($input_data['customer']));
            $purchaseadviceproducts = (json_decode($input_data['purchase_advice_product']));
            $user = (json_decode($input_data['user']));
            $customer_id = $customers[0]->id;



            $send_sms = Input::get('sendsms');
            if ($send_sms == 'true') {
//                $customer_id = $purchase_advise->supplier_id;
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour purchase Advise has been created as follows ";
                    foreach ($purchaseadviceproducts as $product_data) {
                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);


//                      

                        $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                        $total_quantity = $total_quantity + $product_data->quantity;
                    }
                    $str .= " Trk No. " . $purchaseadvices[0]->vehicle_number . ".\nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
//                    $phone_number = $customer->phone_number1;
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
            }
            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
    }

}
