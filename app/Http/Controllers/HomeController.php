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
use App\Labour;
use App\LoadedBy;
use App\CollectionUser;
use App\Territory;
use App\TerritoryLocation;
use App\Receipt;
use App\Customer_receipts;
use App\Debited_to;
use Maatwebsite\Excel\Facades\Excel;
use Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use App\SyncTableInfo;

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
//        $this->middleware('validIP');
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
     * send user OTP
     */
    public function userotp_sms() {

        $input = Input::all();

        if (Input::has('mobile_number') && Input::has('otp')) {
            $mobile_number = (json_decode($input['mobile_number']));
            $otp = (json_decode($input['otp']));
            $str = " Dear user, \n Your verification code is " . $otp . ".\nVIKAS ASSOCIATES";

            $msg = urlencode($str);
            $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $mobile_number . "&msgtext=" . $msg . "&smstype=0";
            if (SEND_SMS === true) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
            }

            $result['send_message'] = "Success";
            $result['message_body'] = $str;
        } else {
            $result['send_message'] = "Error";
        }

        return json_encode($result);
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
        $inquiries_data = (json_decode($input_data['inquiry_deleted']));
        if (count($inquiries_data) > 0) {
            if (Input::has('inquiry')) {
                $inquiries = (json_decode($input_data['inquiry']));
                foreach ($inquiries as $inquiry) {
                    if (isset($inquiry->send_sms) && $inquiry->send_sms == 'true') {
                        $this->inquiry_sms();
                    }
                }
            }
            foreach ($inquiries_data as $inquiry) {
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
        $orders_del = (json_decode($input_data['order_deleted']));
        if (count($orders_del) > 0) {
            if (Input::has('order')) {
                $orders = (json_decode($input_data['order']));
                foreach ($orders as $order) {
                    if (isset($order->send_sms) && $order->send_sms == 'true') {
                        $this->order_sms();
                    }
                }
            }
            foreach ($orders_del as $order) {
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

    function purchasechallan_sms() {
        $input_data = Input::all();

        if (Input::has('purchase_challan') && Input::has('customer') && Input::has('purchase_challan_product')) {
            $purchasechallans = (json_decode($input_data['purchase_challan']));
            $customers = (json_decode($input_data['customer']));
            $purchasechallanproducts = (json_decode($input_data['purchase_challan_product']));
            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $purchasechallans;
            }

            if (isset($purchasechallans[0]->sms_role) && $purchasechallans[0]->sms_role == '1') {

                $message_body_cust_first = "Your material has been dispatched as follows\n";
                $message_body_cust_last = "";
                $message_body_manager_first = "Admin has dispatched for";
            } elseif (isset($purchasechallans[0]->sms_role) && $purchasechallans[0]->sms_role == '2') {
                $message_body_cust_first = "Your material has been edited as follows\n";
                $message_body_cust_last = "";
                $message_body_manager_first = "Admin has edited material for";
            }

            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . (isset($customer[0]->supplier_tally_name) ? $customer[0]->supplier_tally_name : $customer[0]->supplier_name) . "\nDT " . date("j M, Y") . "\n" . $message_body_cust_first;
                foreach ($purchasechallanproducts as $product_data) {
                    $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= "Vehicle No. " . (isset($purchasechallans[0]->vehicle_number) ? $purchasechallans[0]->vehicle_number : 'N/A')
                        . ", Quantity. " . (isset($purchasechallans[0]->total_quantity) ? round($purchasechallans[0]->total_quantity, 2) : '')
                        . ", Amount " . (isset($purchasechallans[0]->grand_total) ? $purchasechallans[0]->grand_total : '0')
                        . ", Due by " . date("j M, Y", strtotime($purchasechallans[0]->expected_delivery_date))
                        . ".\nVIKAS ASSOCIATES";
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer[0]->supplier_mobile;
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
            if ($purchasechallans[0]->server_supplier_id > 0) {
                $customer = Customer::with('manager')->find($purchasechallans[0]->server_supplier_id);
                if (!empty($customer->manager)) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->manager->first_name . "\nDT " . date("j M, Y") . "\n" . $message_body_manager_first . " " . $customer->owner_name . " \n";
                    foreach ($purchasechallanproducts as $product_data) {
                        $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                        $total_quantity = $total_quantity + $product_data->quantity;
                    }
                    $str .= "Vehicle No. " . (isset($purchasechallans[0]->vehicle_number) ? $purchasechallans[0]->vehicle_number : 'N/A')
                            . ", Quantity. " . (isset($purchasechallans[0]->total_quantity) ? round($purchasechallans[0]->total_quantity, 2) : '')
                            . ", Amount " . (isset($purchasechallans[0]->grand_total) ? $purchasechallans[0]->grand_total : '0')
                            . ", Due by " . date("j M, Y", strtotime($purchasechallans[0]->expected_delivery_date))
                            . ".\nVIKAS ASSOCIATES";
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
            }
        } else {
            
        }
        return;
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
            foreach ($purchasechallan as $pc) {
                if (isset($pc->send_sms) && $pc->send_sms == 'true') {
                    $this->purchasechallan_sms();
                }
            }
        }
        if (Input::has('customer')) {
            $customers = (json_decode($input_data['customer']));
        }
        if (Input::has('purchase_challan_product')) {
            $purchasechallanproducts = (json_decode($input_data['purchase_challan_product']));
        }
        if (Input::has('purchase_challan_sync_date') && Input::get('purchase_challan_sync_date') != '') {
            $last_sync_date = Input::get('purchase_challan_sync_date');
            $purchase_challan_server = PurchaseChallan::where('created_at', '>', $last_sync_date)->where('order_status', 'pending')->whereRaw('created_at = updated_at')->with('all_purchase_products')->get();
            $purchase_challan_response['purchase_challan_server_added'] = ($purchase_challan_server && count($purchase_challan_server) > 0) ? $purchase_challan_server : array();

//            $purchase_challan_updated_server = PurchaseChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('order_status','pending')->with('all_purchase_products')->get();
            $purchase_challan_updated_server = PurchaseChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('all_purchase_products')->get();
            $purchase_challan_response['purchase_challan_server_updated'] = ($purchase_challan_updated_server && count($purchase_challan_updated_server) > 0) ? $purchase_challan_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_challan_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_challan_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $purchase_challan_server = PurchaseChallan::with('all_purchase_products')->where('order_status', 'pending')->get();
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

    public function appSyncPurchaseChallanPagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));
            $skip = ($page - 1) * $limit;
        }

        if ($last_id == 0) {
            $purchase_challan_server = PurchaseChallan::with('all_purchase_products')
                    ->orderBy('id', 'DESC')
//                ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $purchase_challan_server = PurchaseChallan::with('all_purchase_products')
                    ->orderBy('id', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }


        $order_response['purchase_challan_server_added'] = ($purchase_challan_server && count($purchase_challan_server) > 0) ? $purchase_challan_server : array();

        return json_encode($order_response);
    }

    /**
     * API SMS Purchase Advise
     */
    function purchaseadvise_sms() {
        $input_data = Input::all();

        if (Input::has('purchase_advice') && Input::has('customer') && Input::has('purchase_advice_product')) {
            $purchaseadvices = (json_decode($input_data['purchase_advice']));
            $customers = (json_decode($input_data['customer']));
            $purchaseadviceproducts = (json_decode($input_data['purchase_advice_product']));
            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $purchaseadvices;
            }

            if (isset($purchaseadvices[0]->sms_role) && $purchaseadvices[0]->sms_role == '1') {

                $message_body_cust_first = "Your purchase Advise has been created as follows\n";
                $message_body_cust_last = "Vehicle No. " . $purchaseadvices[0]->vehicle_number . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has created Purchase Advise for";
            } elseif (isset($purchaseadvices[0]->sms_role) && $purchaseadvices[0]->sms_role == '2') {
                $message_body_cust_first = "Your purchase Advise has been edited as follows\n";
                $message_body_cust_last = "Vehicle No. " . $purchaseadvices[0]->vehicle_number . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has edited Purchase Advise for";
            } else {
                return;
            }

            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . (isset($customer[0]->supplier_tally_name) ? $customer[0]->supplier_tally_name : $customer[0]->supplier_name) . "\nDT " . date("j M, Y") . "\n" . $message_body_cust_first;
                foreach ($purchaseadviceproducts as $product_data) {
                    $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= $message_body_cust_last;
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer[0]->supplier_mobile;
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

            if ($purchaseadvices[0]->server_supplier_id > 0) {
                $customer = Customer::with('manager')->find($purchaseadvices[0]->server_supplier_id);
                if (!empty($customer->manager)) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->manager->first_name . "\nDT " . date("j M, Y") . "\n" . $message_body_manager_first . " " . $customer->owner_name . " \n";
                    foreach ($purchaseadviceproducts as $product_data) {
                        $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                        $total_quantity = $total_quantity + $product_data->quantity;
                    }
                    $str .= $message_body_cust_last;
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
            }
        } else {
            
        }

        return;
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
            foreach ($purchaseadvices as $purchaseadvice) {
                if (isset($purchaseadvice->send_sms) && $purchaseadvice->send_sms == 'true') {
                    $this->purchaseadvise_sms();
                }
            }
        }
        if (Input::has('customer')) {
            $customers = (json_decode($input_data['customer']));
        }
        if (Input::has('purchase_advice_product')) {
            $purchaseadviceproducts = (json_decode($input_data['purchase_advice_product']));
        }
        if (Input::has('purchase_advice_sync_date') && Input::get('purchase_advice_sync_date') != '') {
            $last_sync_date = Input::get('purchase_advice_sync_date');
            $purchase_advice_server = PurchaseAdvise::where('created_at', '>', $last_sync_date)->where('advice_status', 'in_process')->whereRaw('created_at = updated_at')->with('purchase_products')->get();
            $purchase_advice_response['purchase_advice_server_added'] = ($purchase_advice_server && count($purchase_advice_server) > 0) ? $purchase_advice_server : array();

            $purchase_advice_updated_server = PurchaseAdvise::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('purchase_products')->get();
//            $purchase_advice_updated_server = PurchaseAdvise::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('advice_status', 'in_process')->with('purchase_products')->get();
            $purchase_advice_response['purchase_advice_server_updated'] = ($purchase_advice_updated_server && count($purchase_advice_updated_server) > 0) ? $purchase_advice_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_advice_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_advice_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
//            $purchase_advice_server = PurchaseAdvise::with('purchase_products')->get();
            $purchase_advice_server = PurchaseAdvise::with('purchase_products')
                    ->where('advice_status', 'in_process')
                    ->get();
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

    public function appSyncPurchaseAdvisePagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));
            $skip = ($page - 1) * $limit;
        }

        if ($last_id == 0) {
            $purchase_advice_server = PurchaseAdvise::with('purchase_products')
                    ->orderBy('id', 'DESC')
//                ->where('id', '<', $last_id)
                    ->where('advice_status', '<>', 'in_process')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $purchase_advice_server = PurchaseAdvise::with('purchase_products')
                    ->orderBy('id', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('advice_status', '<>', 'in_process')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }


        $order_response['purchase_advice_server_added'] = ($purchase_advice_server && count($purchase_advice_server) > 0) ? $purchase_advice_server : array();

        return json_encode($order_response);
    }

    /**
     * API SMS Purchase Order
     */
    function purchaseorder_sms() {
        $input = Input::all();

        if (Input::has('purchase_order') && Input::has('customer') && Input::has('purchase_order_product')) {
            $purchaseorders = (json_decode($input['purchase_order']));
            $customers = (json_decode($input['customer']));
            $purchaseorderproducts = (json_decode($input['purchase_order_product']));
            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $purchaseorders;
            }


            if (isset($purchaseorders[0]->sms_role) && $purchaseorders[0]->sms_role == '1') {

                $message_body_cust_first = "Your purchase order has been logged for following \n";
                $message_body_cust_last = "material will be dispatched by " . date("j M, Y", strtotime($purchaseorders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has logged purchase order for";
            } elseif (isset($purchaseorders[0]->sms_role) && $purchaseorders[0]->sms_role == '2') {
                $message_body_cust_first = "Your purchase Advise has been edited as follows\n";
                $message_body_cust_last = "Vehicle No. " . $purchaseadvices[0]->vehicle_number . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has edited Purchase Advise for";
            } else {
                return;
            }

            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear '" . (isset($customer[0]->supplier_tally_name) ? $customer[0]->supplier_tally_name : $customer[0]->supplier_name) . "'\nDT " . date("j M, Y") . "\n" . $message_body_cust_first;
                foreach ($purchaseorderproducts as $product_data) {
                    if (isset($product_data->product_name) && $product_data->product_name != "") {
                        $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                        $total_quantity = $total_quantity + $product_data->quantity;
                    } else {
                        $result['send_message'] = "Error";
                        $result['reasons'] = "Purchase Order not found.";
                        return;
                    }
                }
                $str .= $message_body_cust_last;
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $phone_number = $customer[0]->supplier_mobile;
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

            if ($purchaseorders[0]->server_supplier_id > 0) {
                $customer = Customer::with('manager')->find($purchaseorders[0]->server_supplier_id);
                if (!empty($customer->manager)) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->manager->first_name . "\nDT " . date("j M, Y") . "\n" . $message_body_manager_first . " " . $customer->owner_name . " \n";
                    foreach ($purchaseorderproducts as $product_data) {
                        if (isset($product_data->product_name) && $product_data->product_name != "") {
                            $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                            $total_quantity = $total_quantity + $product_data->quantity;
                        } else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Purchase Order not found.";
                            return;
                        }
                    }

                    $str .= $message_body_cust_last;
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
            }
        } else {
            
        }

        return;
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
            foreach ($purchaseorders as $purchaseorder) {
                if (isset($purchaseorder->send_sms) && $purchaseorder->send_sms == 'true') {
                    $this->purchaseorder_sms();
                }
            }
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('purchase_order_product')) {
            $purchaseorderproducts = (json_decode($data['purchase_order_product']));
        }
        if (Input::has('purchase_order_sync_date') && Input::get('purchase_order_sync_date') != '') {
            $last_sync_date = Input::get('purchase_order_sync_date');
            $purchase_order_server = PurchaseOrder::where('created_at', '>', $last_sync_date)->where('order_status', 'pending')->whereRaw('created_at = updated_at')->with('purchase_products')->get();
            $purchase_order_response['purchase_order_server_added'] = ($purchase_order_server && count($purchase_order_server) > 0) ? $purchase_order_server : array();

//            $purchase_order_updated_server = PurchaseOrder::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('order_status', 'pending')->with('purchase_products')->get();
            $purchase_order_updated_server = PurchaseOrder::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('purchase_products')->get();
            $purchase_order_response['purchase_order_server_updated'] = ($purchase_order_updated_server && count($purchase_order_updated_server) > 0) ? $purchase_order_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
//            $purchase_order_server = PurchaseOrder::with('purchase_products')->get();
            $purchase_order_server = PurchaseOrder::with('purchase_products')
                    ->where('order_status', 'pending')
                    ->get();
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

    /*
     *  API PO Sync Pagination: to get all completed PO
     *
     */

    public function appSyncPurchaseOrderPagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));

            $skip = ($page - 1) * $limit;
        }

        if ($last_id == 0) {
            $purchase_order_server = PurchaseOrder::with('purchase_products')
                    ->orderBy('id', 'DESC')
//                ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $purchase_order_server = PurchaseOrder::with('purchase_products')
                    ->orderBy('id', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }


        $order_response['purchase_order_server_added'] = ($purchase_order_server && count($purchase_order_server) > 0) ? $purchase_order_server : array();

        return json_encode($order_response);
    }

    /**
     * API SMS delievry order
     */
    function deliverychallan_sms() {
        $data = Input::all();
        if (Input::has('delivery_challan') && Input::has('customer') && Input::has('delivery_challan_product')) {
            $delivery_challans = (json_decode($data['delivery_challan']));
            $customers = (json_decode($data['customer']));
            $deliverychallanproducts = (json_decode($data['delivery_challan_product']));

            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $delivery_challans;
            }

            if (isset($delivery_challans[0]->sms_role) && $delivery_challans[0]->sms_role == '1') {
                $message_body_cust_first = "Your material has been dispatched as follows1\n";
                $message_body_cust_last = "";
                $message_body_manager_first = "Admin has dispatched material for";
            } elseif (isset($delivery_challans[0]->sms_role) && $delivery_challans[0]->sms_role == '2') {
                $message_body_cust_first = "Your material has been edited as follows\n";
                $message_body_cust_last = "";
                $message_body_manager_first = "Admin has edited material for";
            } else {
                return;
            }


            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer[0]->customer_name . "\nDT " . date("j M, Y") . "\n" . $message_body_cust_first;
                foreach ($deliverychallanproducts as $product_data) {

                    $str .= $s = $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $do = DeliveryOrder::find($delivery_challans[0]->server_del_order_id);

                $str .= $s .= "\nVehicle No. " . (!empty($do->vehicle_number) ? $do->vehicle_number : 'N/A') .
                        ", Drv No. " . (!empty($do->driver_contact_no) ? $do->driver_contact_no : 'N/A') .
                        ", Quantity " . (isset($delivery_challans[0]->total_quantity) ? $delivery_challans[0]->total_quantity : $total_quantity) .
                        ", Amount " . (isset($delivery_challans[0]->grand_price) ? $delivery_challans[0]->grand_price : 'N/A') .
                        ", Due by: " . date("j F, Y", strtotime($do->expected_delivery_date)) .
                        "\nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer[0]->customer_mobile;
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

            if ($delivery_challans[0]->customer_server_id > 0) {
                $customer = Customer::with('manager')->find($delivery_challans[0]->customer_server_id);
                if (!empty($customer->manager)) {
                    $str = "Dear " . $customer->manager->first_name . "\nDT " . date("j M, Y") . "\n" . $message_body_manager_first . " " . $customer->owner_name . " as follows\n" . $s;

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
            }
        } else {
            
        }
        return;
    }

//    /**
//     * App sync delievry challan
//     */
//    public function appSyncDeliveryChallan() {
//
//        $data = Input::all();
//        $delivery_challan_response = [];
//        $customer_list = [];
//        if (Input::has('delivery_challan')) {
//            $delivery_challans = (json_decode($data['delivery_challan']));
//            foreach ($delivery_challans as $delivery_challan) {
//                if (isset($delivery_challan->send_sms) && $delivery_challan->send_sms == 'true') {
//                    $this->deliverychallan_sms();
//                }
//            }
//        }
//        if (Input::has('customer')) {
//            $customers = (json_decode($data['customer']));
//        }
//        if (Input::has('delivery_challan_product')) {
//            $deliverychallanproducts = (json_decode($data['delivery_challan_product']));
//        }
//        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '') {
//            $last_sync_date = Input::get('delivery_challan_sync_date');
//            $delivery_challan_server = DeliveryChallan::where('created_at', '>', $last_sync_date)->with('delivery_challan_products')->get();
//            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();
//
//            $delivery_challan_updated_server = DeliveryChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('delivery_challan_products')->get();
//            $delivery_challan_response['delivery_challan_server_updated'] = ($delivery_challan_updated_server && count($delivery_challan_updated_server) > 0) ? $delivery_challan_updated_server : array();
//
//            /* Send Updated customers */
//            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
//            $delivery_challan_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
//            /* Send New customers */
//            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
//            $delivery_challan_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
//        } else {
//            $delivery_challan_server = DeliveryChallan::with('delivery_challan_products')->get();
//            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();
//        }
//
//        foreach ($delivery_challans as $key => $value) {
//            if ($value->server_id == 0)
//                $delivery_challan = new DeliveryChallan();
//            else
//                $delivery_challan = DeliveryChallan::find($value->server_id);
//
//            if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
//                $add_customers = new Customer();
//                $add_customers->addNewCustomer($value->customer_name, $value->customer_contact_person, $value->customer_mobile, $value->customer_credit_period);
//                $customer_list[$value->id] = $add_customers->id;
//            }
//            if ($value->server_order_id == 0) {
//                $delivery_challan->order_id = 0;
//            } else {
//                $delivery_challan->order_id = $value->server_order_id;
//            }
//            if ($value->server_del_order_id == 0) {
//                DeliveryOrder::where('id', '=', $value->server_del_order_id)->update(array('order_status' => $value->order_status));
//                $delivery_challan->delivery_order_id = 0;
//            } else {
//                $delivery_challan->delivery_order_id = $value->server_del_order_id;
//            }
//            $delivery_challan->customer_id = ($value->customer_server_id == 0) ? $customer_list[$value->id] : $value->customer_server_id;
//            $delivery_challan->created_by = 1;
//            if (isset($value->bill_number)) {
//                $delivery_challan->bill_number = $value->bill_number;
//            }
//            $delivery_challan->discount = ($value->discount != '') ? $value->discount : '';
//            $delivery_challan->freight = ($value->freight != '') ? $value->freight : '';
//            $delivery_challan->loading_charge = ($value->loading_charge != '') ? $value->loading_charge : '';
//            $delivery_challan->round_off = ($value->round_off != '') ? $value->round_off : '';
//            $delivery_challan->loaded_by = ($value->loaded_by != '') ? $value->loaded_by : '';
//            $delivery_challan->labours = ($value->labours != '') ? $value->labours : '';
////            if (isset($value->vat_percentage) && $value->vat_percentage > 0) {
////                $delivery_challan->vat_percentage = $value->vat_percentage;
////            }
//            $delivery_challan->grand_price = $value->grand_price;
//            $delivery_challan->remarks = $value->remarks;
//            $delivery_challan->challan_status = ($value->server_id > 0) ? $value->challan_status : "Pending";
//            $delivery_challan->save();
//            $delivery_challan_id = $delivery_challan->id;
//            $delivery_challan_products = array();
//            if ($value->server_id > 0)
//                AllOrderProducts::where('order_type', '=', 'delivery_challan')->where('order_id', '=', $value->server_id)->delete();
//
//            foreach ($deliverychallanproducts as $product_data) {
//                if ($product_data->delivery_challan_id == $value->id) {
//                    $delivery_challan_products = [
//                        'app_product_id' => $product_data->id,
//                        'order_id' => $delivery_challan_id,
//                        'order_type' => 'delivery_challan',
//                        'product_category_id' => $product_data->product_category_id,
//                        'unit_id' => $product_data->unit_id,
//                        'quantity' => $product_data->quantity,
//                        'price' => $product_data->actual_price,
//                        'remarks' => '',
//                        'present_shipping' => $product_data->present_shipping,
//                        'actual_pieces' => $product_data->actual_pieces,
//                        'actual_quantity' => $product_data->actual_quantity,
//                        'vat_percentage' => ($product_data->vat_percentage != '') ? $product_data->vat_percentage : 0,
//                        'from' => 0, //Will need to check with app data
//                        'parent' => 0, //Will need to check with app data
//                    ];
//                    AllOrderProducts::create($delivery_challan_products);
//                }
//            }
//            if ($value->server_id > 0) {
//                $delivery_challan_prod = AllOrderProducts::where('order_id', '=', $value->server_id)->where('order_type', '=', 'delivery_challan')->first();
//                $delivery_challan->updated_at = $delivery_challan_prod->updated_at;
//                $delivery_challan_response[$value->id] = DeliveryChallan::find($value->server_id);
//                $delivery_challan_response[$value->id]['delivery_challan_products'] = AllOrderProducts::where('order_type', '=', 'delivery_challan')->where('order_id', '=', $value->server_id)->get();
//            } else {
//                $delivery_challan_response[$value->id] = $delivery_challan_id;
//            }
//            $delivery_challan->save();
//        }
//        if (count($customer_list) > 0) {
//            $delivery_challan_response['customer_new'] = $customer_list;
//        }
//        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '' && Input::get('delivery_challan_sync_date') != NULL) {
//            $delivery_challan_response['delivery_challan_deleted'] = DeliveryChallan::withTrashed()->where('deleted_at', '>=', Input::get('delivery_challan_sync_date'))->select('id')->get();
//        }
//        $delivery_challan_date = DeliveryChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($delivery_challan_date))
//            $delivery_challan_response['latest_date'] = $delivery_challan_date->updated_at->toDateTimeString();
//        else
//            $delivery_challan_response['latest_date'] = "";
//
//        return json_encode($delivery_challan_response);
//    }

    /**
     * App sync delievry challan
     */
    public function appSyncDeliveryChallan() {

        $data = Input::all();
        $delivery_challan_response = [];
        $customer_list = [];
        if (Input::has('delivery_challan')) {
            $delivery_challans = (json_decode($data['delivery_challan']));
            foreach ($delivery_challans as $delivery_challan) {
                if (isset($delivery_challan->send_sms) && $delivery_challan->send_sms == 'true') {
                    $this->deliverychallan_sms();
                }
            }
        }
        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('delivery_challan_labour')) {
            $deliverychallanlabour = (json_decode($data['delivery_challan_labour']));
        }

        if (Input::has('delivery_challan_loadedby')) {
            $deliverychallanloadedby = (json_decode($data['delivery_challan_loadedby']));
        }

        if (Input::has('delivery_challan_product')) {
            $deliverychallanproducts = (json_decode($data['delivery_challan_product']));
        }

        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '') {
            $last_sync_date = Input::get('delivery_challan_sync_date');
            $delivery_challan_server = DeliveryChallan::where('created_at', '>', $last_sync_date)->with('delivery_challan_products', 'challan_loaded_by', 'challan_labours', 'delivery_order')->where('challan_status', 'pending')->whereRaw('created_at = updated_at')->get();
            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();

//            $delivery_challan_updated_server = DeliveryChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('challan_status','pending')->with('delivery_challan_products', 'challan_loaded_by')->get();
            $delivery_challan_updated_server = DeliveryChallan::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('delivery_challan_products', 'challan_loaded_by', 'delivery_order')->get();
            $delivery_challan_response['delivery_challan_server_updated'] = ($delivery_challan_updated_server && count($delivery_challan_updated_server) > 0) ? $delivery_challan_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $delivery_challan_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $delivery_challan_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $delivery_challan_server = DeliveryChallan::with('delivery_challan_products', 'challan_loaded_by', 'challan_labours', 'delivery_order')->where('challan_status', 'pending')->get();
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
                DeliveryOrder::where('id', '=', $value->server_del_order_id)->update(array(
                    'empty_truck_weight' => isset($value->empty_truck_weight) ? $value->empty_truck_weight : '0',
                    'final_truck_weight' => isset($value->final_truck_weight) ? $value->final_truck_weight : '0',
                ));
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


            /* add labours if new dc created */
            if ($value->server_id == 0) {
                $labour_array = [];
                foreach ($deliverychallanlabour as $key_labour => $labour_list) {
                    if ($value->id == $labour_list->local_dc_id) {
                        /* if labour created offline */
                        if ($labour_list->server_labour_id == 0) {
                            $labour_check = Labour::where('phone_number', '=', $labour_list->phone_number)->where('first_name', '=', $labour_list->first_name)
                                            ->where('last_name', '=', $labour_list->last_name)->first();
                            if (!isset($labour_check->id)) {
                                $labour = new Labour();
                                $labour->first_name = $labour_list->first_name;
                                $labour->last_name = $labour_list->last_name;
//                                $labour->password = Hash::make($labour_list->password);
                                $labour->phone_number = $labour_list->phone_number;
                                $labour->save();
                                $labour_id = $labour->id;
                            } else {
                                $labour_id = $labour_check->id;
                            }

                            $labour_array[] = [$labour_list->local_labour_id => $labour_id];
                        } else {
                            $labour_id = $labour_list->server_labour_id;
                        }

                        $dc_labour = new App\DeliveryChallanLabours();
                        $dc_labour->delivery_challan_id = $delivery_challan_id;
                        $dc_labour->labours_id = $labour_id;
                        $dc_labour->type = "sale";
                        $dc_labour->product_type_id = isset($labour_list->product_type_id) ? $labour_list->product_type_id : '0';
                        $dc_labour->save();
                    }
                }
            }
            $delivery_challan_response["labour_server_added"] = isset($labour_array) ? $labour_array : "";

            /* add loadedby if new dc created */
            $loadedby_array = [];
            if ($value->server_id == 0) {
                foreach ($deliverychallanloadedby as $key_labour => $loadedby_list) {
                    if ($value->id == $loadedby_list->local_dc_id) {
                        /* if labour created offline */
                        if ($loadedby_list->server_loadedby_id == 0) {
                            $loadedby_check = LoadedBy::where('phone_number', '=', $loadedby_list->phone_number)->where('first_name', '=', $loadedby_list->first_name)
                                            ->where('last_name', '=', $loadedby_list->last_name)->first();
                            if (!isset($loadedby_check->id)) {
                                $loadedby = new LoadedBy();

                                $loadedby->first_name = $loadedby_list->first_name;
                                $loadedby->last_name = $loadedby_list->last_name;
                                $loadedby->password = Hash::make($loadedby_list->password);
                                $loadedby->phone_number = $loadedby_list->phone_number;
                                $loadedby->save();
                                $loadedby_id = $loadedby->id;
                            } else {
                                $loadedby_id = $loadedby_check->id;
                            }


                            $loadedby_array[] = [$loadedby_list->local_loadedby_id => $loadedby_id];
                        } else {
                            $loadedby_id = $loadedby_list->server_loadedby_id;
                        }

                        $dc_labour = new App\DeliveryChallanLoadedBy();
                        $dc_labour->delivery_challan_id = $delivery_challan_id;
                        $dc_labour->loaded_by_id = $loadedby_id;
                        $dc_labour->type = "sale";
                        $dc_labour->product_type_id = isset($loadedby_list->product_type_id) ? $loadedby_list->product_type_id : '0';
                        $dc_labour->save();
                    }
                }
            }
            $delivery_challan_response["loadedby_server_added"] = $loadedby_array;

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

    public function appSyncDeliveryChallanPagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));

            $skip = ($page - 1) * $limit;
        }

        if ($last_id == 0) {
            $delivery_challan_response = DeliveryChallan::with('delivery_challan_products', 'challan_loaded_by', 'challan_labours', 'delivery_order')
                    ->orderBy('id', 'DESC')
//                ->where('id', '<', $last_id)
                    ->where('challan_status', '<>', 'pending')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $delivery_challan_response = DeliveryChallan::with('delivery_challan_products', 'challan_loaded_by', 'challan_labours', 'delivery_order')
                    ->orderBy('id', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('challan_status', '<>', 'pending')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }


        $order_response['delivery_challan_server_added'] = ($delivery_challan_response && count($delivery_challan_response) > 0) ? $delivery_challan_response : array();

        return json_encode($order_response);
    }

    /**
     * API SMS delievry order
     */
    function deliveryorder_sms() {

        $data = Input::all();

        if (Input::has('delivery_order') && Input::has('customer') && Input::has('delivery_order_product')) {


            $delivery_orders = (json_decode($data['delivery_order']));
            $customers = (json_decode($data['customer']));
            $deliveryorderproducts = (json_decode($data['delivery_order_product']));

            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $delivery_orders;
            }

            if (isset($delivery_orders[0]->sms_role) && $delivery_orders[0]->sms_role == '1') {
                $message_body_cust_first = "Your DO has been created as follows\n";
                $message_body_cust_last = "";
                $message_body_manager_first = "Admin has created DO for";
            } elseif (isset($delivery_orders[0]->sms_role) && $delivery_orders[0]->sms_role == '2') {
                $message_body_cust_first = "Your DO has been edited for following";
                $message_body_cust_last = "";
                $message_body_manager_first = "Admin has edited an DO for";
            } else {
                return;
            }
            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . $customer[0]->customer_name . "\nDT " . date("j M, Y") . "\n" . $message_body_cust_first;
                foreach ($deliveryorderproducts as $product_data) {
                    $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                    $total_quantity = $total_quantity + $product_data->quantity;
                }
                $str .= "Vehicle No. " . (!empty($delivery_orders[0]->vehicle_number) ? $delivery_orders[0]->vehicle_number : 'N/A') . ", Drv No. " . (!empty($delivery_orders[0]->driver_contact_no) ? $delivery_orders[0]->driver_contact_no : 'N/A') . ". \nVIKAS ASSOCIATES";

                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer[0]->customer_mobile;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                if (SEND_SMS === true) {
//                    $ch = curl_init($url);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    $curl_scraped_page = curl_exec($ch);
//                    curl_close($ch);
//                }
            }
            if ($delivery_orders[0]->customer_server_id > 0) {
                $customer = Customer::with('manager')->find($delivery_orders[0]->customer_server_id);
                if (!empty($customer->manager)) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->manager->first_name . "\nDT " . date("j M, Y") . "\n" . $message_body_manager_first . " " . $customer->owner_name . " as follows\n";
                    foreach ($deliveryorderproducts as $product_data) {
                        $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                        $total_quantity = $total_quantity + $product_data->quantity;
                    }
                    $str .= "Vehicle No. " . (!empty($delivery_orders[0]->vehicle_number) ? $delivery_orders[0]->vehicle_number : 'N/A') . ", Drv No. " . (!empty($delivery_orders[0]->driver_contact_no) ? $delivery_orders[0]->driver_contact_no : 'N/A') . ". \nVIKAS ASSOCIATES";
                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer['manager']->mobile_number;
                    }

                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
                }
            }
        } else {
            
        }
        return;
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
            foreach ($delivery_orders as $delivery_order) {
                if (isset($delivery_order->send_sms) && $delivery_order->send_sms == 'true') {
                    $this->deliveryorder_sms();
                }
            }
        }

        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('delivery_order_product')) {
            $deliveryorderproducts = (json_decode($data['delivery_order_product']));
        }

        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '') {
            $last_sync_date = Input::get('delivery_order_sync_date');
            $delivery_order_server = DeliveryOrder::where('created_at', '>', $last_sync_date)->where('order_status', 'pending')->whereRaw('created_at = updated_at')->with('delivery_product')->get();
            $delivery_order_response['delivery_order_server_added'] = ($delivery_order_server && count($delivery_order_server) > 0) ? $delivery_order_server : array();

            $delivery_order_updated_server = DeliveryOrder::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('delivery_product')->get();

//            $delivery_order_updated_server = DeliveryOrder::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('order_status', 'pending')->with('delivery_product')->get();
            $delivery_order_response['delivery_order_server_updated'] = ($delivery_order_updated_server && count($delivery_order_updated_server) > 0) ? $delivery_order_updated_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $delivery_order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $delivery_order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
//            $delivery_order_server = DeliveryOrder::with('delivery_product')->get();
            $delivery_order_server = DeliveryOrder::with('delivery_product')
                    ->where('order_status', 'pending')
                    ->get();
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

    /*
      API DO PAgination: to get all completed DOs
     * $delivery_order_response['delivery_order_server_added']
     *      */

    public function appSyncDeliveryOrderPagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));

            $skip = ($page - 1) * $limit;
        }

        if ($last_id == 0) {
            $delivery_order_response = DeliveryOrder::with('delivery_product')
                    ->orderBy('id', 'DESC')
//                ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $delivery_order_response = DeliveryOrder::with('delivery_product')
                    ->orderBy('id', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }


        $order_response['delivery_order_server_added'] = ($delivery_order_response && count($delivery_order_response) > 0) ? $delivery_order_response : array();

        return json_encode($order_response);
    }

    /**
     * API SMS Order 
     */
    function order_sms() {
        $input = Input::all();

        if (Input::has('order') && Input::has('customer') && Input::has('order_product')) {
            $orders = (json_decode($input['order']));
            $customers = (json_decode($input['customer']));
            $orderproduct = "";
            $orderproduct = (json_decode($input['order_product']));
            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $orders;
            }

            if (isset($orders[0]->sms_role) && $orders[0]->sms_role == '1') {
                $message_body_cust_first = "Your order has been created as following";
                $message_body_cust_last = "material will be dispatched by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has logged an order for";
            } elseif (isset($orders[0]->sms_role) && $orders[0]->sms_role == '2') {
                $message_body_cust_first = "Your order has been edited for following";
                $message_body_cust_last = "material will be dispatched by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has edited an order for";
            } elseif (isset($orders[0]->sms_role) && $orders[0]->sms_role == '3') {
                $message_body_cust_first = "Admin has approved your order for following items";
                $message_body_cust_last = "material will be dispatched by " . date("j M, Y", strtotime($datetime->format('Y-m-d'))) . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has approved an order for";
            } elseif (isset($orders[0]->sms_role) && $orders[0]->sms_role == '4') {
                $message_body_cust_first = "Admin has rejected your order for following items.";
                $message_body_cust_last = "VIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has rejected an order for";
            } else {
                return;
            }


//            $customer = Customer::with('manager')->find($customer_id);

            if (count($customer) > 0 && $customer[0]->customer_mobile != "") {
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . strtoupper($customer[0]->customer_name) . "\nDT " . date("j M, Y") . "\n" . $message_body_cust_first . "\n";
                    foreach ($orderproduct as $product_data) {

                        if (isset($product_data) && $product_data->product_name != "") {

                            $str .= $product_data->product_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ", \n";
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
//                            return json_encode($result);
                        }
                    }
                    $str .= $message_body_cust_last;

                    if (App::environment('development')) {
                        $phone_number = \Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer[0]->customer_mobile;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }

                    if ($orders[0]->customer_server_id > 0) {
                        $customer = Customer::with('manager')->find($orders[0]->customer_server_id);
                        if (count($customer->manager) > 0 && !empty($customer->manager)) {
                            $str = "Dear '" . $customer->manager->first_name . "'\nDT " . date("j M, Y") . "\n" . $message_body_manager_first . " " . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk.\nVIKAS ASSOCIATES";

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
        } else {
            
        }
        return;
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
            foreach ($orders as $order) {
                if (isset($order->send_sms) && $order->send_sms == 'true') {
                    $this->order_sms();
                }
            }
        }

        if (Input::has('customer')) {
            $customers = (json_decode($data['customer']));
        }
        if (Input::has('order_product')) {
            $orderproduct = (json_decode($data['order_product']));
        }
        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '') {
            $last_sync_date = Input::get('order_sync_date');
            $order_added_server = Order::where('created_at', '>', $last_sync_date)->where('order_status', 'pending')->whereRaw('created_at = updated_at')->with('all_order_products')->get();
            $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();

            $order_updated_server = Order::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('all_order_products')->get();
//            $order_updated_server = Order::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('order_status', 'pending')->with('all_order_products')->get();
            $order_response['order_server_updated'] = ($order_updated_server && count($order_updated_server) > 0) ? $order_updated_server : '';

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $order_added_server = Order::with('all_order_products')
                    ->where('order_status', 'pending')
                    ->get();
//            $order_added_server = Order::with('all_order_products')->orderBy('id', 'ASEC')
//                            ->limit('1000')->get();
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
                $order->is_approved = 'yes';
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
                $order->is_approved = 'yes';
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

    /*
      App Sync order for admin for pagination

     */

    public function appSyncOrderPagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));

            $skip = ($page - 1) * $limit;
        }


        if ($last_id == 0) {
            $order_added_server = Order::with('all_order_products')
                    ->orderBy('updated_at', 'DESC')
//                    ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $order_added_server = Order::with('all_order_products')
                    ->orderBy('updated_at', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('order_status', '<>', 'pending')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }
        $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();

        return json_encode($order_response);
    }

//    public function appSyncOrderPagination() {
//        $data = Input::all();
//        $order_response = [];
//        $skip = 1000;
//        $limit = 100;
//        if (Input::has('last_id')) {
//            $last_id = (json_decode($data['last_id']));
//        }
//
//        if (Input::has('record_count_per_page')) {
//            $limit = (json_decode($data['record_count_per_page']));
//        }
//
//        if (Input::has('page_number')) {
//            $page = (json_decode($data['page_number']));
//            if ($page > 2)
//                $skip = ($page - 1) * $limit;
//        }
//        $order_added_server = Order::with('all_order_products')->orderBy('id', 'ASEC')
//                        ->limit('100')->get();
//
//        if (count($order_added_server)) {
//            foreach ($order_added_server as $key => $order) {
//                if ($order->id == $last_id)
//                    $skip = $key;
//            }
//            $skip +=1;
//        }
//        $order_added_server = Order::with('all_order_products')
//                ->orderBy('id', 'ASEC')
//                ->skip($skip)
//                ->limit($limit)
//                ->get();
//        $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();
//
//        return json_encode($order_response);
//    }

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
                        foreach ($orders as $order) {
                            if (isset($order->send_sms) && $order->send_sms == 'true') {
                                $this->order_sms();
                            }
                        }
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
     * API SMS Inquiry 
     */
    function inquiry_sms() {
        $data = Input::all();
        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product')) {
            $inquiries = (json_decode($data['inquiry']));
            $customers = (json_decode($data['customer']));
            $inquiryproduct = (json_decode($data['inquiry_product']));
            if (count($customers) > 0) {
                $customer = $customers;
            } else {
                $customer = $inquiries;
            }

            $addon_message = "";
            $datetime = $inquiries[0]->expected_delivery_date;
            if (isset($inquiries[0]->sms_role) && $inquiries[0]->sms_role == '1') {

                $message_body_cust_first = "Your inquiry has been logged for following";
                $message_body_cust_last = "Prices and availability will be contacted shortly. \nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has logged an inquiry for";
            } elseif (isset($inquiries[0]->sms_role) && $inquiries[0]->sms_role == '2') {
                $message_body_cust_first = "Your inquiry has been edited for following";
                $message_body_cust_last = "Prices and availability will be contacted shortly. \nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has edited an enquiry for";
            } elseif (isset($inquiries[0]->sms_role) && $inquiries[0]->sms_role == '3') {
                $message_body_cust_first = "Admin has approved your inquiry for following items.";
                $message_body_cust_last = "Prices and availability will be contacted shortly. \nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has approved an enquiry for";
            } elseif (isset($inquiries[0]->sms_role) && $inquiries[0]->sms_role == '4') {
                $message_body_cust_first = "Admin has rejected your inquiry for following items.";
                $message_body_cust_last = "VIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has rejected an inquiry for";
            } elseif (isset($inquiries[0]->sms_role) && $inquiries[0]->sms_role == '5') {
                $message_body_cust_first = "Prices for your inquiry are as follows";
                $message_body_cust_last = "\nmaterials will be dispatched by " . date('j M, Y', strtotime($datetime)) . ".\nVIKAS ASSOCIATES";
                $message_body_manager_first = "Admin has logged an enquiry for";
            } else {
                return;
            }


            if (count($customer) > 0) {
                $total_quantity = '';
                $str = "Dear " . (isset($customer[0]->customer_name) ? $customer[0]->customer_name : $customer[0]->owner_name) . "\nDT " . date("j M, Y") . "\n" . $message_body_cust_first . "\n";
                foreach ($inquiryproduct as $product_data) {

                    if (isset($product_data->product_name)) {
                        $product_size = ProductSubCategory::find($product_data->id);
                        if (isset($inquiries[0]->sms_role) && $inquiries[0]->sms_role == '5') {
                            $addon_message = '- ' . $product_data->price;
                        }
                        $str .= $product_data->product_name . '- ' . $product_data->quantity . $addon_message . ', ';
                        if ($product_data->unit_id == 1) {
                            $total_quantity = $total_quantity + $product_data->quantity;
                        }
                        if ($product_data->unit_id == 2) {
                            $total_quantity = $total_quantity + $product_data->quantity * $product_size->weight;
                        }
                        if ($product_data->unit_id == 3) {
                            $total_quantity = $total_quantity + ($product_data->quantity / $product_size->standard_length ) * $product_size->weight;
                        }
                    } else {
                        $result['send_message'] = "Error";
                        $result['reasons'] = "Inquiry not found.";
                        return json_encode($result);
                    }
                }
                $str .= $message_body_cust_last;
                if (App::environment('development')) {
                    $phone_number = \Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = isset($customer[0]->customer_mobile) ? $customer[0]->customer_mobile : $customer[0]->phone_number1;
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

            if ($inquiries[0]->customer_server_id > 0) {

                $customer = Customer::with('manager')->find($inquiries[0]->customer_server_id);
                if (!empty($customer->manager)) {
                    $str = "Dear " . $customer->manager->first_name . ", " . $message_body_manager_first . " " . $customer->owner_name . "', '" . round($total_quantity, 2) . "'. Kindly check and contact. Vikas Associates";

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
        } else {
            return false;
        }

        return true;
    }

    /**
     * App sync inquiries
     */
    public function appsyncinquiry() {
        $data = Input::all();
        if (Input::has('inquiry')) {
            $inquiries = (json_decode($data['inquiry']));
            foreach ($inquiries as $inquiry) {
                if (isset($inquiry->send_sms) && $inquiry->send_sms == 'true') {
                    $this->inquiry_sms();
                }
            }
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
            $inquiry_added_server = Inquiry::where('created_at', '>', $last_sync_date)->where('inquiry_status', 'pending')->whereRaw('created_at = updated_at')->with('inquiry_products')->get();
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
//            $inquiry_added_server = Inquiry::with('inquiry_products')->get();
            $inquiry_added_server = Inquiry::with('inquiry_products')
                    ->where('inquiry_status', 'pending')
                    ->get();
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
                    $add_inquiry->is_approved = 'yes';
                    $add_inquiry->save();
                    $inquiry_response[$value->server_id] = Inquiry::find($value->server_id);
                    $inquiry_response[$value->server_id]['inquiry_products'] = InquiryProducts::where('inquiry_id', '=', $value->server_id)->get();
                } else {

                    if ($value->customer_server_id == 0 || $value->customer_server_id == '0') {
                        $add_customers = new Customer();

                        foreach ($customers as $customer) {
                            if ($value->customer_id == $customer->id) {
                                $add_customers->owner_name = (isset($customer->owner_name) ? $customer->owner_name : $value->customer_name);
                                $add_customers->contact_person = (isset($customer->contact_person) ? $customer->contact_person : $value->customer_contact_peron);
                                $add_customers->phone_number1 = (isset($customer->phone_number1) ? $customer->phone_number1 : $value->customer_mobile);
                                $add_customers->credit_period = (isset($customer->credit_period) ? $customer->credit_period : $value->customer_credit_period);
                                $add_customers->customer_status = (isset($customer->customer_status) ? $customer->customer_status : $value->customer_status);
                                $add_customers->save();
                                $customer_list[$value->id] = $add_customers->id;
                            }
                        }
//                        $add_customers->owner_name = $value->customer_name;
//                        $add_customers->contact_person = $value->customer_contact_peron;
//                        $add_customers->phone_number1 = $value->customer_mobile;
//                        $add_customers->credit_period = $value->customer_credit_period;
//                        $add_customers->customer_status = $value->customer_status;
//                        $add_customers->save();
//                        $customer_list[$value->id] = $add_customers->id;
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
                    $add_inquiry->is_approved = 'yes';
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

    /*
      Inquiry pagination: To Get all Completed Inquiries.

     *      */

    public function appsyncinquirypagination() {
        $data = Input::all();
        $order_response = [];
        $skip = 1000;
        $limit = 1000;
        $last_id = 0;
        if (Input::has('last_id')) {
            $last_id = (json_decode($data['last_id']));
        }

        if (Input::has('record_count_per_page')) {
            $limit = (json_decode($data['record_count_per_page']));
        }

        if (Input::has('page_number')) {
            $page = (json_decode($data['page_number']));

            $skip = ($page - 1) * $limit;
        }

        if ($last_id == 0) {
            $inquiry_response = Inquiry::with('inquiry_products')
                    ->orderBy('id', 'DESC')
//                ->where('id', '<', $last_id)
                    ->where('inquiry_status', '<>', 'pending')
                    ->skip($skip)
                    ->limit($limit)
                    ->get();
        } else {
            $inquiry_response = Inquiry::with('inquiry_products')
                    ->orderBy('id', 'DESC')
                    ->where('id', '<', $last_id)
                    ->where('inquiry_status', '<>', 'pending')
//                ->skip($skip)
                    ->limit($limit)
                    ->get();
        }

        $order_response['inquiry_server_added'] = ($inquiry_response && count($inquiry_response) > 0) ? $inquiry_response : array();

        return json_encode($order_response);
    }

    /**
     * customer App sync inquiries
     */
    public function appsyncinquiry_customer() {

        $data = Input::all();

        if (Input::has('flag')) {
            $flag = (json_decode($data['flag']));
            if ($flag == 1) {
                if (Input::has('customer_id')) {
                    $customer_id = (json_decode($data['customer_id']));

                    if (Input::has('inquiry')) {
                        $inquiries = (json_decode($data['inquiry']));
                        foreach ($inquiries as $inquiry) {
                            if (isset($inquiry->send_sms) && $inquiry->send_sms == 'true') {
                                $this->inquiry_sms();
                            }
                        }
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
     * App sync performance- labours
     */
    public function appSyncLabours() {

        $data = Input::all();
        $labour_response = [];
        $customer_list = [];
        if (Input::has('labours')) {
            $labours = (json_decode($data['labours']));
        }

        if (Input::has('labours_sync_date') && Input::get('labours_sync_date') != '') {
            $last_sync_date = Input::get('labours_sync_date');
            $labour_server = Labour::where('created_at', '>', $last_sync_date)->get();
            $labour_response['labour_server_added'] = ($labour_server && count($labour_server) > 0) ? $labour_server : array();

            $labour_updated_server = Labour::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $labour_response['labour_server_updated'] = ($labour_updated_server && count($labour_updated_server) > 0) ? $labour_updated_server : array();
        } else {
            $labour_server = Labour::get();
            $labour_response['labour_server_added'] = ($labour_server && count($labour_server) > 0) ? $labour_server : array();
        }

        foreach ($labours as $key => $value) {
            if ($value->server_id == 0) {
                $labour_check = Labour::where('phone_number', '=', $value->phone_number)
                        ->where('first_name', '=', $value->first_name)
                        ->where('last_name', '=', $value->last_name)
                        ->first();
                if (!isset($labour_check->id)) {
                    $labour = new Labour();
                    if (isset($value->first_name))
                        $labour->first_name = $value->first_name;
                    if (isset($value->last_name))
                        $labour->last_name = $value->last_name;
                    if (isset($value->password))
                        $labour->password = Hash::make($value->password);
                    if (isset($value->phone_number))
                        $labour->phone_number = $value->phone_number;
                    $labour->save();
                    $labour_id = $labour->id;
                    $labour_response[$value->id] = $labour_id;
                }
            } else {
                $labour = Labour::find($value->server_id);
                if (isset($value->first_name) && $value->first_name != "")
                    $labour->first_name = $value->first_name;
                if (isset($value->last_name) && $value->last_name != "")
                    $labour->last_name = $value->last_name;
                if (isset($value->phone_number) && $value->phone_number != "")
                    $labour->phone_number = $value->phone_number;
                if (isset($value->password) && $value->password != "")
                    $labour->password = Hash::make($value->password);
                $labour_id = $labour->id;
                $delivery_order_products = array();
                $labour->save();
                $labour_response[$value->server_id] = Labour::find($labour->id);
            }
        }

        if (Input::has('labours_sync_date') && Input::get('labours_sync_date') != '' && Input::get('labours_sync_date') != NULL) {
            $labour_response['labour_deleted'] = Labour::withTrashed()->where('deleted_at', '>=', Input::get('labours_sync_date'))->select('id')->get();
        }
        $labour_date = Labour::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($labour_date))
            $labour_response['latest_date'] = $labour_date->updated_at->toDateTimeString();
        else
            $labour_response['latest_date'] = "";

        return json_encode($labour_response);
    }

    /**
     * App Labours Master delete
     */
    public function appSyncLaboursdelete() {
        $input_data = Input::all();
        $labours = (json_decode($input_data['labours_deleted']));

        if (count($labours) > 0) {
            foreach ($labours as $labour) {
                $labour_data = Labour::find($labour);

                if ($labour_data) {
                    $labours_dcs = \App\DeliveryChallanLabours::where('labours_id', '=', $labour)->get();
                    foreach ($labours_dcs as $labours_dc) {
                        $labours_dc->delete();
                    }
                    $labour_data->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'labours deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App sync performance- loadedby
     */
    public function appSyncLoadedby() {

        $data = Input::all();
        $loadedby_response = [];
        $customer_list = [];
        if (Input::has('loadedby')) {
            $loadedby = (json_decode($data['loadedby']));
        }

        if (Input::has('loadedby_sync_date') && Input::get('loadedby_sync_date') != '') {
            $last_sync_date = Input::get('loadedby_sync_date');
            $labour_server = LoadedBy::where('created_at', '>', $last_sync_date)->get();
            $loadedby_response['labour_server_added'] = ($labour_server && count($labour_server) > 0) ? $labour_server : array();

            $labour_updated_server = LoadedBy::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $loadedby_response['labour_server_updated'] = ($labour_updated_server && count($labour_updated_server) > 0) ? $labour_updated_server : array();
        } else {
            $labour_server = LoadedBy::get();
            $loadedby_response['labour_server_added'] = ($labour_server && count($labour_server) > 0) ? $labour_server : array();
        }

        foreach ($loadedby as $key => $value) {
            if ($value->server_id == 0) {
                $labour_check = LoadedBy::where('phone_number', '=', $value->phone_number)
                        ->where('first_name', '=', $value->first_name)
                        ->where('last_name', '=', $value->last_name)
                        ->first();

                if (!isset($labour_check->id)) {
                    $labour = new LoadedBy();
                    if (isset($value->first_name))
                        $labour->first_name = $value->first_name;
                    if (isset($value->last_name))
                        $labour->last_name = $value->last_name;
                    if (isset($value->password))
                        $labour->password = Hash::make($value->password);
                    if (isset($value->phone_number))
                        $labour->phone_number = $value->phone_number;
                    $labour->save();
                    $labour_id = $labour->id;
                }else {
                    $labour_id = $labour_check->id;
                }
                $loadedby_response[$value->id] = $labour_id;
            } else {
                $labour = LoadedBy::find($value->server_id);
                if (count($labour) > 0) {
                    if (isset($value->first_name) && $value->first_name != "")
                        $labour->first_name = $value->first_name;
                    if (isset($value->last_name) && $value->last_name != "")
                        $labour->last_name = $value->last_name;
                    if (isset($value->phone_number) && $value->phone_number != "")
                        $labour->phone_number = $value->phone_number;
                    if (isset($value->password) && $value->password != "")
                        $labour->password = Hash::make($value->password);
                    $labour_id = $labour->id;
                    $labour->save();
                    $loadedby_response[$value->server_id] = LoadedBy::find($labour->id);
                }
            }
        }

        if (Input::has('loadedby_sync_date') && Input::get('loadedby_sync_date') != '' && Input::get('loadedby_sync_date') != NULL) {
            $loadedby_response['labour_deleted'] = LoadedBy::withTrashed()->where('deleted_at', '>=', Input::get('loadedby_sync_date'))->select('id')->get();
//            $loadedby_response['labour_deleted'] = array();
        }
        $labour_date = LoadedBy::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($labour_date))
            $loadedby_response['latest_date'] = $labour_date->updated_at->toDateTimeString();
        else
            $loadedby_response['latest_date'] = "";

        return json_encode($loadedby_response);
    }

    /**
     * App Loaded by Master delete
     */
    public function appSyncLoadedbydelete() {
        $input_data = Input::all();
        $loadedby = (json_decode($input_data['loadedby_deleted']));

        if (count($loadedby) > 0) {
            foreach ($loadedby as $loadedby) {
                $loadedby_data = LoadedBy::find($loadedby);
                if ($loadedby_data) {

                    $loadedby_dcs = \App\DeliveryChallanLoadedBy::where('loaded_by_id', '=', $loadedby)->get();
                    foreach ($loadedby_dcs as $loadedby_dc) {
                        $loadedby_dc->delete();
                    }
                    $loadedby_data->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Loader deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App sync Receipt Master
     */
    public function appsyncreceipt() {

        $data = Input::all();
        $receipt_response = [];
        $customer_list = [];
        if (Input::has('receipt')) {
            $receipt = (json_decode($data['receipt']));
        }

        if (Input::has('receipt_customer')) {
            $receipt_customer = (json_decode($data['receipt_customer']));
        }

        if (Input::has('receipt_sync_date') && Input::get('receipt_sync_date') != '') {
            $last_sync_date = Input::get('receipt_sync_date');
            $receipt_server = Receipt::with('customer_receipts')->where('created_at', '>', $last_sync_date)->get();
            $receipt_response['receipt_server_added'] = ($receipt_server && count($receipt_server) > 0) ? $receipt_server : array();

            $receipt_updated_server = Receipt::with('customer_receipts')->where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $receipt_response['receipt_server_updated'] = ($receipt_updated_server && count($receipt_updated_server) > 0) ? $receipt_updated_server : array();
        } else {
            $receipt_server = Receipt::with('customer_receipts')->orderBy('id', 'desc')->get();
            $receipt_response['receipt_server_added'] = ($receipt_server && count($receipt_server) > 0) ? $receipt_server : array();
        }

        foreach ($receipt as $key => $value) {
            if ($value->server_id == 0) {
                $receiptObj = new Receipt();
                $receiptObj->created_at = $value->created_at;
                $receiptObj->updated_at = $value->updated_at;
                if ($receiptObj->save()) {
                    foreach ($receipt_customer as $key1 => $user) {
                        if ($value->id == $user->local_receipt_id) {
                            $customerReceiptObj = new Customer_receipts();
                            $customerReceiptObj->customer_id = $user->server_customer_id;
                            $customerReceiptObj->settled_amount = $user->settled_amount;
                            $customerReceiptObj->debited_to = $user->debited_to;
                            $customerReceiptObj->receipt_id = $receiptObj->id;
                            $customerReceiptObj->debited_by_type = $user->debited_to_type;
                            $customerReceiptObj->save();
                        }
                    }
                }
                $receiptObj->save();
                $receipt_id = $receiptObj->id;
                $receipt_response[$value->id] = $receipt_id;
            } else {
                $receiptObj = Receipt::with('customer_receipts')->find($value->server_id);
                if (isset($receiptObj->customer_receipts)) {
                    foreach ($receiptObj->customer_receipts as $customers) {
                        $customerObj = Customer_receipts::find($customers->id);
                        $customerObj->delete();
                    }
                }
                foreach ($receipt_customer as $key => $user) {
                    if ($value->id == $user->local_receipt_id) {
                        $customerReceiptObj = new Customer_receipts();
                        $customerReceiptObj->customer_id = $user->server_customer_id;
                        $customerReceiptObj->settled_amount = $user->settled_amount;
                        $customerReceiptObj->debited_to = $user->debited_to;
                        $customerReceiptObj->receipt_id = $receiptObj->id;
                        $customerReceiptObj->debited_by_type = $user->debited_to_type;
                        $customerReceiptObj->save();
                    }
                }
                $receipt_id = $receiptObj->id;
                $delivery_order_products = array();
                $receiptObj->save();
                $receipt_response[$value->server_id] = Receipt::with('customer_receipts')->find($value->server_id);
            }
        }

        if (Input::has('receipt_sync_date') && Input::get('receipt_sync_date') != '' && Input::get('receipt_sync_date') != NULL) {
//            $receipt_response['receipt_server_deleted'] = array();
            $receipt_response['receipt_server_non_deleted'] = Receipt::select('id')->get();
        }
        $receipt_date = Receipt::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($receipt_date))
            $receipt_response['latest_date'] = $receipt_date->updated_at->toDateTimeString();
        else
            $receipt_response['latest_date'] = "";

        return json_encode($receipt_response);
    }

    /**
     * App Receipt Master delete
     */
    public function appsyncreceiptdelete() {
        $input_data = Input::all();
        $receipts = (json_decode($input_data['receipt_deleted']));

        if (count($receipts) > 0) {
            foreach ($receipts as $receipt) {

                $receipt_data = Receipt::find($receipt);

                if ($receipt_data) {
                    $customer_recripts = Customer_receipts::where('receipt_id', '=', $receipt)->get();
                    foreach ($customer_recripts as $customer_recript) {
                        $customer_recript->delete();
                    }
                    $receipt_data->delete();
                }
            }
            return json_encode(array('result' => true, 'message' => 'Receipts deleted successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Nothing to delete. Please provide valid records to delete'));
        }
    }

    /**
     * App Receipt customer list
     */
    public function appsyncreceiptcustomerlist() {
        $receipt_customer_list_response = [];
        $tally_users = Customer::where('customer_status', 'permanent')->get();
        $debited_to_journal = $tally_users;
        $debited_to_bank = Debited_to::where('debited_to_type', '=', 2)->get();
        $debited_to_cash = Debited_to::where('debited_to_type', '=', 3)->get();

        $receipt_customer_list_response['journal']['tally_user'] = $tally_users;
        $receipt_customer_list_response['journal']['debited_to'] = $debited_to_journal;
        $receipt_customer_list_response['bank']['tally_user'] = $tally_users;
        $receipt_customer_list_response['bank']['debited_to'] = $debited_to_bank;
        $receipt_customer_list_response['cash']['tally_user'] = $tally_users;
        $receipt_customer_list_response['cash']['debited_to'] = $debited_to_cash;
        return json_encode($receipt_customer_list_response);
    }

    /**
     * App sync Territory
     */
    public function appsyncterritory() {

        $data = Input::all();
        if (Input::has('territories')) {
            $territories = (json_decode($data['territories']));
        }
        if (Input::has('territory_locations')) {
            $territorylocations = (json_decode($data['territory_locations']));
        }
        $territory_response = [];
        if (Input::has('territory_sync_date') && Input::get('territory_sync_date') != '' && Input::get('territory_sync_date') != NULL) {
            $last_sync_date = Input::get('territory_sync_date');
            $territory_added_server = Territory::where('created_at', '>', $last_sync_date)->with('territorylocation')->get();
            $territory_response['territory_server_added'] = ($territory_added_server && count($territory_added_server) > 0) ? $territory_added_server : array();

            $inquiry_updated_server = Territory::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->with('territorylocation')->get();
            $territory_response['territory_server_updated'] = ($inquiry_updated_server && count($inquiry_updated_server) > 0) ? $inquiry_updated_server : array();
        } else {
            $territory_added_server = Territory::with('territorylocation')->get();
            $territory_response['territory_server_added'] = ($territory_added_server && count($territory_added_server) > 0) ? $territory_added_server : array();
        }

        if (isset($territories)) {
            foreach ($territories as $key => $value) {
                if ($value->teritory_server_id > 0) {
                    $territory = Territory::find($value->teritory_server_id);
                    $territory->teritory_name = $value->teritory_name;
                    $territory->save();
                    $delete_old_territory_location = TerritoryLocation::where('teritory_id', '=', $value->teritory_server_id)->delete();
                    foreach ($territorylocations as $product_data) {
                        $inquiry_products = array();
                        if ($product_data->local_territory_id == $value->local_territory_id) {
                            $territory_loc = new TerritoryLocation();
                            $territory_loc->teritory_id = $value->teritory_server_id;
                            $territory_loc->location_id = $product_data->location_id;
                            $territory_loc->save();
                        }
                    }
                    $territory_response[$value->teritory_server_id] = Territory::find($value->teritory_server_id);
//                    $territory_response[$value->teritory_server_id]['territory_locations'] = InquiryProducts::where('inquiry_id', '=', $value->teritory_server_id)->get();
                } else {

                    $territory = new Territory();
                    $territory->teritory_name = $value->teritory_name;
                    $territory->save();
                    $teritory_id = $territory->id;
                    foreach ($territorylocations as $product_data) {
                        $inquiry_products = array();
                        if ($product_data->local_territory_id == $value->local_territory_id) {
                            $territory_loc = new TerritoryLocation();
                            $territory_loc->teritory_id = $teritory_id;
                            $territory_loc->location_id = $product_data->location_id;
                            $territory_loc->save();
                        }
                    }
                    $territory_response[$value->local_territory_id] = $teritory_id;
                }
            }
        }
        if (Input::has('territory_sync_date') && Input::get('territory_sync_date') != '' && Input::get('territory_sync_date') != NULL) {
            $territory_response['territory_deleted'] = Territory::withTrashed()->where('deleted_at', '>=', Input::get('territory_sync_date'))->select('id')->get();
        }
        $territory_date = Territory::withTrashed()->select('updated_at')->orderby('updated_at', 'DESC')->first();
        $territory_date_tl = TerritoryLocation::select('updated_at')->
                        orderby('updated_at', 'DESC')->first();
        if (isset($territory_date_tl->updated_at) && $territory_date_tl->updated_at > $territory_date->updated_at) {
            $territory_date = $territory_date_tl;
        }

        if (!empty($territory_date))
            $territory_response['latest_date'] = $territory_date->updated_at->toDateTimeString();
        else
            $territory_response['latest_date'] = "";
        return json_encode($territory_response);
    }

    /**
     * App sync Territory delete
     */
    public function appdeleteterritory() {

        $data = Input::all();
        if (Input::has('territories')) {
            $territories = (json_decode($data['territories']));

            if (isset($territories)) {
                foreach ($territories as $key => $value) {
                    if ($value->teritory_server_id > 0) {
                        $territory = Territory::find($value->teritory_server_id);
                        if (count($territory) > 0)
                            $territory->delete();
                        $territory_loc = TerritoryLocation::where('teritory_id', '=', $value->teritory_server_id)->get();
                        foreach ($territory_loc as $loc) {
                            $territory_old = TerritoryLocation::find($loc->id);
                            if (count($territory_old) > 0)
                                $territory_old->delete();
                        }
                    }
                }
                return json_encode(array('result' => true, 'territory_id' => $territory->id, 'message' => 'Territory deleted successfully'));
            }
        } else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App sync Collection
     */
    public function appsynccollection() {

        $data = Input::all();
        if (Input::has('collections')) {
            $collections = (json_decode($data['collections']));
        }
        if (Input::has('collections_locations')) {
            $collectionslocations = (json_decode($data['collections_locations']));
        }
        $collection_response = [];
        if (Input::has('collection_sync_date') && Input::get('collection_sync_date') != '' && Input::get('collection_sync_date') != NULL) {
            $last_sync_date = Input::get('collection_sync_date');
            $collection_added_server = User::where('created_at', '>', $last_sync_date)->where('role_id', '6')->with('locations.location_data')->get();
            $collection_response['collection_server_added'] = ($collection_added_server && count($collection_added_server) > 0) ? $collection_added_server : array();

            $inquiry_updated_server = User::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->where('role_id', '6')->with('locations.location_data')->get();
            $collection_response['collection_server_updated'] = ($inquiry_updated_server && count($inquiry_updated_server) > 0) ? $inquiry_updated_server : array();
        } else {
            $collection_added_server = User::with('locations.location_data')->where('role_id', '6')->get();
            $collection_response['collection_server_added'] = ($collection_added_server && count($collection_added_server) > 0) ? $collection_added_server : array();
        }


        if (isset($collections)) {
            foreach ($collections as $key => $value) {
                if ($value->collection_server_id > 0) {
                    $collection_check = User::where('mobile_number', '=', $value->mobile_number)->where('id', '<>', $value->collection_server_id)->first();
                    if (isset($collection_check->id)) {
                        $collection_response[$value->local_collection_id] = 0;
                    } else {
                        $collection = User::find($value->collection_server_id);
                        if ($value->updated_at >= $collection->updated_at) {
                            $collection->role_id = 6;
                            if (isset($value->first_name) && $value->first_name <> "")
                                $collection->first_name = $value->first_name;
                            if (isset($value->last_name) && $value->last_name <> "")
                                $collection->last_name = $value->last_name;
                            if (isset($value->password) && $value->password <> "")
                                $collection->password = Hash::make($value->password);
                            if (isset($value->mobile_number) && $value->mobile_number <> "")
                                $collection->mobile_number = $value->mobile_number;
                            if (isset($value->email) && $value->email <> "")
                                $collection->email = $value->email;
                            $collection->save();

                            $delete_old_territory_location = CollectionUser::where('user_id', '=', $value->collection_server_id)->delete();

                            foreach ($collectionslocations as $product_data) {
                                if ($product_data->local_collection_id == $value->local_collection_id) {
                                    $collection_loc = new CollectionUser();
                                    $collection_loc->user_id = $value->collection_server_id;
                                    $collection_loc->location_id = $product_data->location_id;
                                    $collection_loc->teritory_id = $product_data->teritory_server_id;
                                    $collection_loc->save();
                                }
                            }
                        }

                        $collection_response[$value->local_collection_id] = User::with('locations.location_data')->find($value->collection_server_id);
                    }
                } else {

                    $collection_check = User::where('mobile_number', '=', $value->mobile_number)->first();
                    if (isset($collection_check->id)) {
                        $collection_response[$value->local_collection_id] = 0;
                    } else {
                        $Users_data = new User();
                        $Users_data->role_id = 6;
                        if (isset($value->first_name))
                            $Users_data->first_name = $value->first_name;
                        if (isset($value->last_name))
                            $Users_data->last_name = $value->last_name;
                        if (isset($value->password))
                            $Users_data->password = Hash::make($value->password);
                        if (isset($value->mobile_number))
                            $Users_data->mobile_number = $value->mobile_number;
                        if (isset($value->email))
                            $Users_data->email = $value->email;
                        $Users_data->save();
                        $collection_id = $Users_data->id;
                        foreach ($collectionslocations as $product_data) {
                            if ($product_data->local_collection_id == $value->local_collection_id) {
                                $collection_loc = new CollectionUser();
                                $collection_loc->user_id = $collection_id;
                                $collection_loc->location_id = $product_data->location_id;
                                $collection_loc->teritory_id = $product_data->teritory_server_id;
                                $collection_loc->save();
                            }
                        }
                        $collection_response[$value->local_collection_id] = $collection_id;
                    }
                }
            }
        }
        if (Input::has('collection_sync_date') && Input::get('collection_sync_date') != '' && Input::get('collection_sync_date') != NULL) {
            $collection_response['collection_non_deleted'] = User::where('role_id', '6')->select('id')->get();
            ;
        }
        $collection_date = User::select('updated_at')->where('role_id', '6')->
                        orderby('updated_at', 'DESC')->first();
        if (!empty($collection_date))
            $collection_response['latest_date'] = $collection_date->updated_at->toDateTimeString();
        else
            $collection_response['latest_date'] = "";
        return json_encode($collection_response);
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
        $collection_user_date = User::select('updated_at')->where('role_id', '6')->orderby('updated_at', 'DESC')->first();
        $terriroty_date = Territory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $labour_date = Labour::select('updated_at')->orderby('updated_at', 'DESC')->first();

        $loadedby_date = LoadedBy::select('updated_at')->orderby('updated_at', 'DESC')->first();
        $receipt_date = Receipt::select('updated_at')->orderby('updated_at', 'DESC')->first();

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

            if ($synckey == 'collection_user' && !empty($collection_user_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $collection_user_date->updated_at->toDateTimeString()];

            if ($synckey == 'territory' && !empty($terriroty_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $terriroty_date->updated_at->toDateTimeString()];

            if ($synckey == 'labour_list' && !empty($labour_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $labour_date->updated_at->toDateTimeString()];

            if ($synckey == 'loadedby_list' && !empty($loadedby_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $loadedby_date->updated_at->toDateTimeString()];

            if ($synckey == 'product_cat' && !empty($product_category))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $product_category->updated_at->toDateTimeString()];
            if ($synckey == 'product_sub_cat' && !empty($product_subcategory_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $product_subcategory_date->updated_at->toDateTimeString
                    ()];
            if ($synckey == 'location' && !empty($location_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $location_date->updated_at->toDateTimeString()];
            if ($synckey == 'city' && !empty($city_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $city_date->updated_at->
                            toDateTimeString()];
            if ($synckey == 'state' && !empty($state_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $state_date->updated_at->toDateTimeString()
                ];
            if ($synckey == 'inventory' && !empty($inventory_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $inventory_date->updated_at->toDateTimeString()];

            if ($synckey == 'receipt' && !empty($receipt_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $receipt_date->updated_at->toDateTimeString()];
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
        /* new code return if web sync date is less than or equal to app sync date */
        $real_sync_date = SyncTableInfo::where('table_name', 'customers')->select('sync_date')->first();
        if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {

            if ($real_sync_date->sync_date <= Input::get('customer_sync_date')) {
                $product_subcategory['all'] = [];
                $product_subcategory['latest_date'] = $real_sync_date->sync_date;
                return json_encode($product_subcategory);
            }
        }
        /* end of new code */

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

        /* new code return if web sync date is less than or equal to app sync date */
        $real_sync_date = SyncTableInfo::where('table_name', 'product_category')->select('sync_date')->first();
        if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {

            if ($real_sync_date->sync_date <= Input::get('product_category_sync_date')) {
                $product_subcategory['all'] = [];
                $product_subcategory['latest_date'] = $real_sync_date->sync_date;
                return json_encode($product_subcategory);
            }
        }
        /* end of new code */

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

            /* new code */
            $real_sync_date = SyncTableInfo::where('table_name', 'product_sub_category')->select('sync_date')->first();
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {

                if ($real_sync_date->sync_date <= Input::get('product_subcategory_sync_date')) {
                    $product_subcategory['all'] = [];
                    $product_subcategory['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($product_subcategory);
                }
            }
            /* end of new code */
            $product_subcategory['all'] = ProductSubCategory::with('product_category')->where('updated_at', '>', Input::get('product_subcategory_sync_date'))->orderBy('created_at', 'desc')->get();
        } else {
            $product_subcategory['all'] = ProductSubCategory::with('product_category')->orderBy('created_at', 'desc')->get();
        }
        $product_subcategory_date = ProductSubCategory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($product_subcategory_date)) {
            $product_subcategory['latest_date'] = $product_subcategory_date->updated_at->toDateTimeString();
        } else {
            $product_subcategory['latest_date'] = "";
        }
        return

                json_encode($product_subcategory);
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
        return json_encode($delivery_location);
    }

    /**
     * App get all common results
     */
    public function appallcommon() {
        /* new code return if web sync date is less than or equal to app sync date */
        $real_sync_date = SyncTableInfo::get();
        $used_table_name = array("customers", "product_category", "product_sub_category", "delivery_locations");

        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($used_table_name);

        $common_sync_date = [];
        foreach ($real_sync_date as $sync_date) {
            if (in_array($sync_date->table_name, $used_table_name)) {
                $common_sync_date[$sync_date->table_name] = $sync_date->sync_date;
            }
        }

        $all['customers'] = [];
        $all['customers']['latest_date'] = $common_sync_date['customers'];
        $all['product_category'] = [];
        $all['product_category']['latest_date'] = $common_sync_date['product_category'];
        $all['product_sub_category'] = [];
        $all['product_sub_category']['latest_date'] = $common_sync_date['product_sub_category'];
        $all['delivery_locations'] = [];
        $all['delivery_locations']['latest_date'] = $common_sync_date['delivery_locations'];

        if (Input::has('customer_sync_date') && Input::has('product_category_sync_date') && Input::has('product_subcategory_sync_date') && Input::has('delivery_location_sync_date')) {

            if ($common_sync_date['customers'] <= Input::get('customer_sync_date') && $common_sync_date['product_category'] <= Input::get('product_category_sync_date') && $common_sync_date['product_sub_category'] <= Input::get('product_subcategory_sync_date') && $common_sync_date['delivery_locations'] <= Input::get('delivery_location_sync_date')) {
                return json_encode($all);
            } else {

                if ($common_sync_date['customers'] > Input::get('customer_sync_date')) {
                    $all['customers'] = Customer::where('updated_at', '>', Input::get('customer_sync_date'))->orderBy('tally_name', 'asc')->get();
                    $all['customers']['latest_date'] = $common_sync_date['customers'];
                }
                if ($common_sync_date['product_category'] > Input::get('product_category_sync_date')) {
                    $all['product_category'] = $product_category['all'] = ProductCategory:: where('updated_at', '>', Input::get('product_category_sync_date'))->orderBy('created_at', 'desc')->get();
                    $all['product_category']['latest_date'] = $common_sync_date['product_category'];
                }
                if ($common_sync_date['product_sub_category'] > Input::get('product_subcategory_sync_date')) {
                    $all['product_sub_category'] = ProductSubCategory::with('product_category')->where('updated_at', '>', Input::get('product_subcategory_sync_date'))->orderBy('created_at', 'desc')->get();
                    $all['product_sub_category']['latest_date'] = $common_sync_date['product_sub_category'];
                }
                if ($common_sync_date['delivery_locations'] > Input::get('delivery_location_sync_date')) {
                    $all['delivery_locations'] = DeliveryLocation::with('city', 'states')->where('status', '=', 'permanent')->where('created_at', '>', Input::get('delivery_location_sync_date'))->orderBy('created_at', 'desc')->get();
                    $all['delivery_locations']['latest_date'] = $common_sync_date['delivery_locations'];
                }
            }
        } else {
            $all['customers'] = Customer::orderBy('tally_name', 'asc')->get();
            $all['customers']['latest_date'] = $common_sync_date['customers'];
            $all['product_category'] = ProductCategory::orderBy('created_at', 'desc')->get();
            $all['product_category']['latest_date'] = $common_sync_date['product_category'];
            $all['product_sub_category'] = ProductSubCategory::with('product_category')->orderBy('created_at', 'desc')->get();
            $all['product_sub_category']['latest_date'] = $common_sync_date['product_sub_category'];
            $all['delivery_locations'] = DeliveryLocation::with('city', 'states')->where('status', '=', 'permanent')->orderBy('created_at', 'desc')->get();
            $all['delivery_locations']['latest_date'] = $common_sync_date['delivery_locations'];
        }
        return json_encode($all);
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
        $server_id = json_decode($data['delivery_challan_id']);
        $delivery_data = [];
        
        if (Input::has('delivery_challan')) {
            $delivery_challans = (json_decode($data['delivery_challan']));
            foreach ($delivery_challans as $delivery_challan) {
                if (isset($delivery_challan->send_sms) && $delivery_challan->send_sms == 'true') {
                    $this->deliverychallan_sms();
                }
            }
        }

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
//                if ($update_delivery_challan->ref_delivery_challan_id == 0) {
//                    $modified_id = $id;
//                } else {
//                    $modified_id = $update_delivery_challan->ref_delivery_challan_id;
//                }
//                $date_letter = 'DC/' . $current_date . $modified_id . (($vat_applicable > 0) ? "P" : "A");

                $dc = DeliveryChallan::where('updated_at', 'like', date('Y-m-d') . '%')->withTrashed()->get();

                if (count($dc) <= 0) {
                    $number = '1';
                } else {
                    $serial_numbers = [];
                    foreach ($dc as $temp) {
                        $list = explode("/", $temp->serial_number);
                        $serial_numbers[] = chop(chop($list[count($list) - 1], "P"), "A");
                        $pri_id = max($serial_numbers);
                        $number = $pri_id + 1;
                    }
                }
                if ($update_delivery_challan->serial_number == "") {
                    if ($update_delivery_challan->ref_delivery_challan_id == 0) {
                        $connected_dc = DeliveryChallan::where('ref_delivery_challan_id', '=', $id)->first();
                        if (isset($connected_dc->serial_number)) {
                            if ($connected_dc->serial_number == "") {
                                $modified_id = $number;
                            } else {
                                $list = explode("/", $connected_dc->serial_number);
                                $modified_id = substr($list[count($list) - 1], 0, -1);
                            }
                        } else {
                            $modified_id = $number;
                        }
                    } else {

                        $connected_dc = DeliveryChallan::where('id', '=', $update_delivery_challan->ref_delivery_challan_id)->first();
                        if (isset($connected_dc->serial_number)) {
                            if ($connected_dc->serial_number == "") {
                                $modified_id = $number;
                            } else {
                                $list = explode("/", $connected_dc->serial_number);
                                $modified_id = substr($list[count($list) - 1], 0, -1);
                            }
                        } else {
                            $modified_id = $number;
                        }
                    }
                }

                $date_letter = 'DC/' . $current_date . $modified_id . (($vat_applicable > 0) ? "P" : "A");


                if ($update_delivery_challan->serial_number == '')
                    $update_delivery_challan->serial_number = $date_letter;
                $update_delivery_challan->challan_status = 'completed';
                $update_delivery_challan->save();
//                $delivery_challan_obj = new DeliveryChallanController();
                $this->checkpending_quantity_dc();
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
//                if (isset($exploded_value[1]) && strlen($exploded_value[1]) > 1 && $result_paisa != 0) {
//                    $convert_value = $delivery_challan_obj->convert_number_to_words($allorder->grand_price);
//                } else {
//                    $convert_value = $delivery_challan_obj->convert_number($allorder->grand_price);
//                }
//                $allorder['convert_value'] = $convert_value;

                $delivery_data = DeliveryChallan::where('id', '=', $id)
                                ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();
            }




            /* check for vat/gst items */
            $sms_flag = 0;
            foreach ($delivery_data->delivery_challan_products as $product_data) {
                if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] != '0.00') {
                    $sms_flag = 1;
                }
            }

            if ($sms_flag == 1) {
                if ($send_sms == 'true') {
                    $customer_id = $allorder->customer_id;
                    $customer = Customer::with('manager')->find($customer_id);
                    if (count($customer) > 0) {
                        $total_quantity = '';
                        $str = "Dear " . $customer->owner_name . "\nDT " . date("j M, Y") . "\nYour material has been dispatched as follows ";
                        foreach ($input_data as $product_data) {
                            $product = ProductSubCategory::find($product_data->product_category_id);
//                    $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                            $total_quantity = $total_quantity + $product_data->quantity;
                        }
                        $str .= $s = " Vehicle No. " . $allorder['delivery_order']->vehicle_number .
                                ", Drv No. " . $allorder['delivery_order']->driver_contact_no .
                                ", Quantity " . $allorder['delivery_challan_products']->sum('actual_quantity') .
                                ", Amount " . $allorder->grand_price .
                                ", Due by: " . date("j F, Y", strtotime($allorder['delivery_order']->expected_delivery_date)) .
                                "\nVIKAS ASSOCIATES";

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
                        $total_quantity = '';
                        $str = "Dear " . $customer['manager']->first_name . "\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . "  has dispatched material for  " . $customer->owner_name . " as follows\n " . $s;


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
                }
            }
            echo "<pre>";
            print_r($sms_flag);
            echo "</pre>";
            exit;
        } else {
            return '{}';
        }

        return json_encode($delivery_data);
    }

    function checkpending_quantity_dc() {
        $allorders = Order::get();
        $allorder_new = [];
        foreach ($allorders as $order) {
            $delivery_orders = DeliveryOrder::where('order_id', $order->id)->get();
            $gen_dc = 1;
            $pending_quantity = 0;
            foreach ($delivery_orders as $del_order) {
                $delivery_challans = DeliveryChallan::where('delivery_order_id', $del_order->id)->get();
                foreach ($delivery_challans as $del_challan) {
                    $gen_dc = 0;
                    $all_order_products = AllOrderProducts::where('order_id', $order->id)->where('order_type', 'delivery_order')->get();
                    foreach ($all_order_products as $products) {
                        $p_qty = $products['quantity'] - $products['present_shipping'];
                        $pending_quantity = $pending_quantity + $p_qty;
                    }
                }
            }
            if ($gen_dc != 1 && $pending_quantity == 0 && $order->order_status != 'completed') {
                Order::where('id', $order->id)->update(array(
                    'order_status' => "completed"
                ));
            }
        }
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

//    function appsyncinquiry_sms() {
//        $data = Input::all();
//        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product') && Input::has('sendsms') && Input::has('user')) {
//            $inquiries = (json_decode($data['inquiry']));
//            $customers = (json_decode($data['customer']));
//            $inquiryproduct = (json_decode($data['inquiry_product']));
//            $user = (json_decode($data['user']));
//
//            $customer_id = $customers[0]->id;
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nyour inq. has been logged for foll. ";
//                    foreach ($inquiryproduct as $product_data) {
//                        $product_details = InquiryProducts::with('inquiry_product_details')->find($product_data->id);
//
//                        if (isset($product_details['inquiry_product_details']->alias_name)) {
//                            $product_size = ProductSubCategory::find($product_data->id);
//
//                            $str .= $product_details['inquiry_product_details']->alias_name . '- ' . $product_data->quantity . ', ';
//                            if ($product_data->unit_id == 1) {
//                                $total_quantity = $total_quantity + $product_data->quantity;
//                            }
//                            if ($product_data->unit_id == 2) {
//                                $total_quantity = $total_quantity + $product_data->quantity * $product_size->weight;
//                            }
//                            if ($product_data->unit_id == 3) {
//                                $total_quantity = $total_quantity + ($product_data->quantity / $product_size->standard_length ) * $product_size->weight;
//                            }
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Inquiry not found.";
//                            return json_encode($result);
//                        }
//                    }
//                    $str .= " prices and avlblty will be qtd shortly \nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                }
//
//                if (count($customer->manager) > 0) {
//                    $str = "Dear '" . $customer->manager->first_name . "'\n'" . $user[0]->first_name . "' has logged an enquiry for '" . $customer->owner_name . "', '" . round($total_quantity, 2) . "'. Kindly chk and qt. Vikas Associates";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer['manager']->mobile_number;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
      |------------------------------------------------
      | SEND SMS TO THE CUSTOMER WITH UPDATED QUOTATIONS
      |------------------------------------------------
     */

//    function appsyncinquiryedit_sms() {
//        $input = Input::all();
//        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product') && Input::has('sendsms') && Input::has('user')) {
//            $inquiries = (json_decode($input['inquiry']));
//            $customers = (json_decode($input['customer']));
//            $inquiryproduct = (json_decode($input['inquiry_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//
//                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour inq. has been edited for foll. ";
//                    foreach ($inquiryproduct as $product_data) {
//                        $product_details = InquiryProducts::with('inquiry_product_details')->find($product_data->id);
//
//
//                        if ($product_details['inquiry_product_details']->alias_name != "") {
//                            $str .= $product_details['inquiry_product_details']->alias_name . ' - ' . $product_data->quantity . ', ';
//                            $total_quantity = $total_quantity + $product_data->quantity;
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Inquiry not found.";
//                            return json_encode($result);
//                        }
//                    }
//                    $str .= " prices and avlblty will be qtd shortly. \nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//
//                    if (count($customer['manager']) > 0) {
//                        $str = "Dear '" . $customer->manager->first_name . "'\nDT " . date("j M, Y") . "\n" . $user[0]->first_name . " has edited an enquiry for '" . $customer->owner_name . ", '" . $total_quantity . "' Kindly chk and qt.\nVIKAS ASSOCIATES";
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->manager->mobile_number;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user = " . PROFILE_ID . "&pwd = " . PASS . "&senderid = " . SENDER_ID . "&mobileno = " . $phone_number . "&msgtext = " . $msg . "&smstype = 0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
      |------------------------------------------------
      | SEND SMS TO THE CUSTOMER If Admin Approved Inquiry
      |------------------------------------------------
     */

//    function appsyncinquiryapproved_sms() {
//        $input = Input::all();
//        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product') && Input::has('sendsms') && Input::has('user')) {
//            $inquiries = (json_decode($input['inquiry']));
//            $customers = (json_decode($input['customer']));
//            $inquiryproduct = (json_decode($input['inquiry_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//
//                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nAdmin has approved your inquiry for following items. ";
//
//                    foreach ($inquiryproduct as $product_data) {
//                        $product_details = InquiryProducts::with('inquiry_product_details')->find($product_data->id);
//
//
//                        if (isset($product_details['inquiry_product_details']->alias_name) && $product_details['inquiry_product_details']->alias_name != "") {
//                            $str .= $product_details['inquiry_product_details']->alias_name . ' - ' . $product_data->quantity . ', ';
//                            $total_quantity = $total_quantity + $product_data->quantity;
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Inquiry not found.";
//                            return json_encode($result);
//                        }
//                    }
//                    $str .= " prices and avlblty will be qtd shortly. \nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//
//                    if (count($customer['manager']) > 0) {
//                        $str = "Dear '" . $customer->manager->first_name . "'\nDT " . date("j M, Y") . "\n" . $user[0]->first_name . " has Approved an enquiry for '" . $customer->owner_name . ", '" . $total_quantity . "' Kindly chk and qt.\nVIKAS ASSOCIATES";
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->manager->mobile_number;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user = " . PROFILE_ID . "&pwd = " . PASS . "&senderid = " . SENDER_ID . "&mobileno = " . $phone_number . "&msgtext = " . $msg . "&smstype = 0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
      |------------------------------------------------
      | SEND SMS TO THE CUSTOMER If Admin Reject Inquiry
      |------------------------------------------------
     */

//    function appsyncinquiryreject_sms() {
//        $input = Input::all();
//        if (Input::has('inquiry') && Input::has('customer') && Input::has('inquiry_product') && Input::has('sendsms') && Input::has('user')) {
//            $inquiries = (json_decode($input['inquiry']));
//            $customers = (json_decode($input['customer']));
//            $inquiryproduct = (json_decode($input['inquiry_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//
//                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nAdmin has rejected your inquiry for following items";
//
//                    foreach ($inquiryproduct as $product_data) {
//                        $product_details = InquiryProducts::with('inquiry_product_details')->find($product_data->id);
//
//
//                        if (isset($product_details['inquiry_product_details']->alias_name) && $product_details['inquiry_product_details']->alias_name != "") {
//                            $str .= $product_details['inquiry_product_details']->alias_name . ' - ' . $product_data->quantity . ', ';
//                            $total_quantity = $total_quantity + $product_data->quantity;
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Inquiry not found.";
//                            return json_encode($result);
//                        }
//                    }
//
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//
//                    if (count($customer['manager']) > 0) {
//                        $str = "Dear '" . $customer->manager->first_name . "'\nDT " . date("j M, Y") . "\n" . $user[0]->first_name . " has Approved an enquiry for '" . $customer->owner_name . ", '" . $total_quantity . "' Kindly chk and qt.\nVIKAS ASSOCIATES";
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->manager->mobile_number;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user = " . PROFILE_ID . "&pwd = " . PASS . "&senderid = " . SENDER_ID . "&mobileno = " . $phone_number . "&msgtext = " . $msg . "&smstype = 0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
     * ------------------- --------------
     * SEND SMS TO CUSTOMER FOR NEW ORDER
     * ----------------------------------
     */

//    function appsyncorder_sms() {
//        $input = Input::all();
//
//        if (Input::has('order') && Input::has('customer') && Input::has('order_product') && Input::has('sendsms') && Input::has('user')) {
//            $orders = (json_decode($input['order']));
//            $customers = (json_decode($input['customer']));
//            $orderproduct = (json_decode($input['order_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if ($customer->phone_number1 != "") {
//                    if (count($customer) > 0) {
//                        $total_quantity = '';
//                        $str = "Dear '" . $customer->owner_name . "'\n your order has been logged as following \n";
//                        foreach ($orderproduct as $product_data) {
//
//                            $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
//
//                            if (isset($product_details['order_product_details']) && $product_details['order_product_details']->alias_name != "") {
//                                $product = ProductSubCategory::find($product_data->product_category_id);
//
//                                $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ", \n";
//                                if ($product_data->unit_id == 1) {
//                                    $total_quantity = $total_quantity + $product_data->quantity;
//                                }
//                                if ($product_data->unit_id == 2) {
//                                    $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
//                                }
//                                if ($product_data->unit_id == 3) {
//                                    $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
//                                }
//                            } else {
//                                $result['send_message'] = "Error";
//                                $result['reasons'] = "Order not found.";
//                                return json_encode($result);
//                            }
//                        }
//
//
//                        $str .= " material will be dispatched by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
//
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->phone_number1;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                        if (count($customer['manager']) > 0) {
////                        $str = "Dear '" . $customer['manager']->first_name . "'\nDT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has logged an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk.\nVIKAS ASSOCIATES";
//                            $str = urlencode($str);
//                            if (App::environment('development')) {
//                                $phone_number = \Config::get('smsdata.send_sms_to');
//                            } else {
//                                $phone_number = $customer['manager']->mobile_number;
//                            }
//                            $msg = urlencode($str);
//                            $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                            if (SEND_SMS === true) {
//                                $ch = curl_init($url);
//                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                                $curl_scraped_page = curl_exec($ch);
//                                curl_close($ch);
//                            }
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
     * ------------------- --------------
     * SEND SMS TO CUSTOMER FOR UPDATE/EDIT ORDER
     * ----------------------------------
     */

//    function appsyncorderedit_sms() {
//
//        $input = Input::all();
//
//        if (Input::has('order') && Input::has('customer') && Input::has('order_product') && Input::has('sendsms') && Input::has('user')) {
//            $orders = (json_decode($input['order']));
//            $customers = (json_decode($input['customer']));
//            $orderproduct = (json_decode($input['order_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//                    $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nYour order has been edited and changed as following \n";
//                    foreach ($orderproduct as $product_data) {
//
//                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
//
//                        if (isset($product_details['order_product_details']) && $product_details['order_product_details']->alias_name != "") {
//                            $product = ProductSubCategory::find($product_data->id);
//                            $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
//                            if ($product_data->unit_id == 1) {
//                                $total_quantity = $total_quantity + $product_data->quantity;
//                            }
//                            if ($product_data->unit_id == 2) {
//                                $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
//                            }
//                            if ($product_data->unit_id == 3) {
//                                $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
//                            }
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Order not found.";
//                            return json_encode($result);
//                        }
//                    }
//                    $str .= " material will be dispatched by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                    if (count($customer->manager) > 0) {
////                    $str = "Dear '" . $customer->manager->first_name . "'\n'" . Auth::user()->first_name . "' has edited and changed an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk. \nVIKAS ASSOCIATES";
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->manager->mobile_number;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }
//
//    function appsyncorderapproved_sms() {
//
//        $input = Input::all();
//
//        if (Input::has('order') && Input::has('customer') && Input::has('order_product') && Input::has('sendsms') && Input::has('user')) {
//            $orders = (json_decode($input['order']));
//            $customers = (json_decode($input['customer']));
//            $orderproduct = (json_decode($input['order_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//                    $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nAdmin has approved your order for following items \n";
//                    foreach ($orderproduct as $product_data) {
//
//                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
//
//                        if (isset($product_details['order_product_details']) && $product_details['order_product_details']->alias_name != "") {
//                            $product = ProductSubCategory::find($product_data->id);
//                            $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
//                            if ($product_data->unit_id == 1) {
//                                $total_quantity = $total_quantity + $product_data->quantity;
//                            }
//                            if ($product_data->unit_id == 2) {
//                                $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
//                            }
//                            if ($product_data->unit_id == 3) {
//                                $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
//                            }
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Order not found.";
//                            return json_encode($result);
//                        }
//                    }
//                    $str .= " material will be dispatched by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                    if (count($customer->manager) > 0) {
////                    $str = "Dear '" . $customer->manager->first_name . "'\n'" . Auth::user()->first_name . "' has edited and changed an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk. \nVIKAS ASSOCIATES";
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->manager->mobile_number;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }
//
//    function appsyncorderreject_sms() {
//
//        $input = Input::all();
//
//        if (Input::has('order') && Input::has('customer') && Input::has('order_product') && Input::has('sendsms') && Input::has('user')) {
//            $orders = (json_decode($input['order']));
//            $customers = (json_decode($input['customer']));
//            $orderproduct = (json_decode($input['order_product']));
//            $user = (json_decode($input['user']));
//            $customer_id = $customers[0]->id;
//
//            if (Input::has('sendsms')) {
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//                    $str = "Dear " . strtoupper($customer->owner_name) . "\nDT " . date("j M, Y") . "\nAdmin has rejected your order for following items \n";
//                    foreach ($orderproduct as $product_data) {
//
//                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
//
//                        if (isset($product_details['order_product_details']) && $product_details['order_product_details']->alias_name != "") {
//                            $product = ProductSubCategory::find($product_data->id);
//                            $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
//                            if ($product_data->unit_id == 1) {
//                                $total_quantity = $total_quantity + $product_data->quantity;
//                            }
//                            if ($product_data->unit_id == 2) {
//                                $total_quantity = $total_quantity + $product_data->quantity * $product->weight;
//                            }
//                            if ($product_data->unit_id == 3) {
//                                $total_quantity = $total_quantity + ($product_data->quantity / $product->standard_length ) * $product->weight;
//                            }
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Order not found.";
//                            return json_encode($result);
//                        }
//                    }
//                    $str .= " material will be dispatched by " . date("jS F, Y", strtotime($orders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = \Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                    if (count($customer->manager) > 0) {
////                    $str = "Dear '" . $customer->manager->first_name . "'\n'" . Auth::user()->first_name . "' has edited and changed an order for '" . $customer->owner_name . ", '" . round($total_quantity, 2) . "'. Kindly chk. \nVIKAS ASSOCIATES";
//                        if (App::environment('development')) {
//                            $phone_number = \Config::get('smsdata.send_sms_to');
//                        } else {
//                            $phone_number = $customer->manager->mobile_number;
//                        }
//                        $msg = urlencode($str);
//                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                        if (SEND_SMS === true) {
//                            $ch = curl_init($url);
//                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                            $curl_scraped_page = curl_exec($ch);
//                            curl_close($ch);
//                        }
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
      |------------------- -----------------------
      | SEND SMS TO CUSTOMER FOR NEW DELIVERY ORDER
      | -------------------------------------------
     */

//    function appsyncdeliveryorder_sms() {
//
//        $data = Input::all();
//
//        if (Input::has('delivery_order') && Input::has('customer') && Input::has('delivery_order_product') && Input::has('user') && Input::has('sendsms')) {
//
//
//            $delivery_orders = (json_decode($data['delivery_order']));
//            $customers = (json_decode($data['customer']));
//            $deliveryorderproducts = (json_decode($data['delivery_order_product']));
//            $user = (json_decode($data['user']));
//            $customer_id = $customers[0]->id;
//
//            $send_sms = Input::get('sendsms');
//            if ($send_sms == 'true') {
//
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//                    $str = "Dear '" . $customer->owner_name . "'\nDT" . date("j M, Y") . "\nYour DO has been created as follows ";
//                    foreach ($deliveryorderproducts as $product_data) {
//
//                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
//
//
//                        if (isset($product_details['order_product_details']->alias_name) && $product_details['order_product_details']->alias_name != "") {
//                            $str .= $product_details['order_product_details']->alias_name . ' - ' . $product_data->quantity . ',';
//                        } else {
//                            $result['send_message'] = "Error";
//                            $result['reasons'] = "Delivery Order not found.";
//                            return json_encode($result);
//                        }
//
//                        $total_quantity = $total_quantity + $product_data->quantity;
//                    }
//                    $str .= " Trk No. " . $delivery_orders[0]->vehicle_number . ", Drv No. " . $delivery_orders[0]->driver_contact_no . ". \nVIKAS ASSOCIATES";
//                    if (App::environment('development')) {
//                        $phone_number = Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

    /*
      | ------------------- -----------------------
      | SEND SMS TO CUSTOMER FOR NEW DELIVERY CHALLAN
      | -------------------------------------------
     */

//    function appsyncdeliverychallan_sms() {
//        $data = Input::all();
//        if (Input::has('delivery_challan') && Input::has('customer') && Input::has('delivery_challan_product') && Input::has('user') && Input::has('sendsms')) {
//            $delivery_challans = (json_decode($data['delivery_challan']));
//            $customers = (json_decode($data['customer']));
//            $deliverychallanproducts = (json_decode($data['delivery_challan_product']));
//            $user = (json_decode($data['user']));
//            $customer_id = $customers[0]->id;
//
//
//            $send_sms = Input::get('sendsms');
//            if ($send_sms == 'true') {
//
//                $customer = Customer::with('manager')->find($customer_id);
//                if (count($customer) > 0) {
//                    $total_quantity = '';
//                    $total_actual_quantity = '';
//                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour material has been dispatched as follows ";
//                    foreach ($deliverychallanproducts as $product_data) {
//                        $product = ProductSubCategory::find($product_data->product_category_id);
//
//                        $product_details = AllOrderProducts::with('order_product_details')->find($product_data->id);
////                    $str .= $product->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
//                        $total_quantity = $total_quantity + $product_data->quantity;
//                        $total_actual_quantity = $total_actual_quantity + $product_data->actual_quantity;
//                    }
//
//                    $delivery_order = DeliveryOrder::find($delivery_challans[0]->delivery_order_id);
//
//                    if (isset($delivery_order)) {
//                        $str .= " Trk No. " . $delivery_order->vehicle_number .
//                                ", Drv No. " . $delivery_order->driver_contact_no .
////                            ", Qty " . $product_data->sum('actual_quantity') .
//                                ", Qty " . $total_actual_quantity .
//                                ", Amt " . $delivery_challans[0]->grand_price .
//                                ", Due by: " . date("jS F, Y", strtotime($delivery_order->expected_delivery_date)) .
//                                "\nVIKAS ASSOCIATES";
//                    } else {
//                        $result['send_message'] = "Error";
//                        $result['reasons'] = "Delivery Challan not found.";
//                        return json_encode($result);
//                    }
//
//                    if (App::environment('development')) {
//                        $phone_number = Config::get('smsdata.send_sms_to');
//                    } else {
//                        $phone_number = $customer->phone_number1;
//                    }
//                    $msg = urlencode($str);
//                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                    if (SEND_SMS === true) {
//                        $ch = curl_init($url);
//                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                        $curl_scraped_page = curl_exec($ch);
//                        curl_close($ch);
//                    }
//                }
//            }
//            $result['send_message'] = "Success";
//            $result['message_body'] = $str;
//        } else {
//            $result['send_message'] = "Error";
//        }
//
//        return json_encode($result);
//    }

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
                        $product_details = PurchaseProducts::with('purchase_product_details')->find($product_data->id);


                        if (isset($product_details['purchase_product_details']->alias_name) && $product_details['purchase_product_details']->alias_name != "") {
                            $str .= $product_details['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ', ';
                        } else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Purchase Advise not found.";
                            return json_encode($result);
                        }

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

    /*
     * ------------------- -----------------------
     * SEND SMS TO CUSTOMER FOR NEW PURCHASE CHALLAN
     * -------------------------------------------
     */

    function appsyncpurchasechallan_sms() {
        $input_data = Input::all();
//         $purchasechallan = PurchaseChallan::with('supplier','purchase_product')->find(2);
//        echo "<pre>";
//        print_r(json_encode($purchasechallan));
//        echo "</pre>";
//        exit;

        if (Input::has('purchase_challan') && Input::has('customer') && Input::has('purchase_challan_product') && Input::has('user') && Input::has('sendsms')) {
            $purchasechallan = (json_decode($input_data['purchase_challan']));
            $customers = (json_decode($input_data['customer']));
            $purchasechallanproducts = (json_decode($input_data['purchase_challan_product']));
            $user = (json_decode($input_data['user']));
            $customer_id = $customers[0]->id;


            $send_sms = Input::get('sendsms');
            if ($send_sms == 'true') {
//                $customer_id = $purchase_challan->supplier_id;
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour material has been dispatched as follows ";
                    foreach ($purchasechallanproducts as $product_data) {
                        $product = ProductSubCategory::find($product_data->product_category_id);

                        if (isset($product)) {
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
                            $result['reasons'] = "Purchase Challan not found.";
                            return json_encode($result);
                        }
                    }
                    $str .= " Trk No. " . $purchasechallan[0]->vehicle_number
//                            . ", Qty. " . round($input_data->sum('quantity'), 2)
                            . ", Qty. " . $total_quantity
                            . ", Amt. " . $purchasechallan[0]->grand_total
                            . ", Due by " . date("jS F, Y", strtotime($purchasechallan[0]->expected_delivery_date))
                            . ".\nVIKAS ASSOCIATES";
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

    /*
      | ------------------------------------------------------
      | SEND SMS TO SUPPLIER ON CREATE OF NEW PURCHASE ORDER
      | ------------------------------------------------------
     */

    function appsyncpurchaseorder_sms() {
        $input = Input::all();
        if (Input::has('purchase_order') && Input::has('customer') && Input::has('purchase_order_product') && Input::has('user') && Input::has('sendsms')) {
            $purchaseorders = (json_decode($input['purchase_order']));
            $customers = (json_decode($input['customer']));
            $purchaseorderproducts = (json_decode($input['purchase_order_product']));
            $user = (json_decode($input['user']));
            $customer_id = $customers[0]->id;

            if (isset($input['sendsms']) && $input['sendsms'] == "true") {
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour purchase order has been logged for following \n";
                    foreach ($purchaseorderproducts as $product_data) {
                        $product_details = PurchaseProducts::with('purchase_product_details')->find($product_data->id);

                        if (isset($product_details['purchase_product_details']->alias_name) && $product_details['purchase_product_details']->alias_name != "") {
                            $str .= $product_details['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                            $total_quantity = $total_quantity + $product_data->quantity;
                        } else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Purchase Order not found.";
                            return json_encode($result);
                        }
                    }

                    $str .= " material will be dispatched by " . date("jS F, Y", strtotime($purchaseorders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";
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
     * ------------------- ------------------------------------
     * SEND SMS TO SUPPLIER ON EDIT OF NEW PURCHASE ORDER
     * --------------------------------------------------------
     */

    function appsyncpurchaseorderedit_sms() {
        $input = Input::all();

        if (Input::has('purchase_order') && Input::has('customer') && Input::has('purchase_order_product') && Input::has('user') && Input::has('sendsms')) {
            $purchaseorders = (json_decode($input['purchase_order']));
            $customers = (json_decode($input['customer']));
            $purchaseorderproducts = (json_decode($input['purchase_order_product']));
            $user = (json_decode($input['user']));
            $customer_id = $customers[0]->id;

            if (isset($input['sendsms']) && $input['sendsms'] == "true") {
                $customer = Customer::with('manager')->find($customer_id);
                if (count($customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear '" . $customer->owner_name . "'\nDT " . date("j M, Y") . "\nYour purchase order has been edited and changed as follows \n";
                    foreach ($purchaseorderproducts as $product_data) {
                        $product_details = PurchaseProducts::with('purchase_product_details')->find($product_data->id);

                        if (isset($product_details['purchase_product_details']->alias_name) && $product_details['purchase_product_details']->alias_name != "") {
                            $str .= $product_details['purchase_product_details']->alias_name . ' - ' . $product_data->quantity . ' - ' . $product_data->price . ",\n";
                            $total_quantity = $total_quantity + $product_data->quantity;
                        } else {
                            $result['send_message'] = "Error";
                            $result['reasons'] = "Purchase Order not found.";
                            return json_encode($result);
                        }
                    }
                    $str .= " material will be dispatched by " . date("jS F, Y", strtotime($purchaseorders[0]->expected_delivery_date)) . ".\nVIKAS ASSOCIATES";

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

    public function appssyncgraph_inquiry() {
        for ($i = 1; $i <= 7; $i++) {
            $inquiries_stats_all[$i]['pipe'] = 0;
            $inquiries_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $inquiries_stats_all[$i]['day'] = $date_search;
            $inquiries_stats = Inquiry::with('inquiry_products.inquiry_product_details')
                    ->where('inquiry_status', '=', 'completed')
                    ->where('updated_at', 'like', $date_search . '%')
                    ->get();

            $dashboard = new DashboardController();

            foreach ($inquiries_stats as $inquiry) {

                foreach ($inquiry['inquiry_products'] as $inquiry_products) {
                    if (isset($inquiry_products['inquiry_product_details']['product_category']['product_type_id'])) {
                        if ($inquiry_products['inquiry_product_details']['product_category']['product_type_id'] == 1) {
                            if ($inquiry_products['unit_id'] == 1)
                                $inquiries_stats_all[$i]['pipe'] += $inquiry_products['quantity'];
                            elseif (($inquiry_products['unit_id'] == 2) || ($inquiry_products['unit_id'] == 3))
                                $inquiries_stats_all[$i]['pipe'] += $dashboard->checkpending_quantity($inquiry_products['unit_id'], $inquiry_products['product_category_id'], $inquiry_products['quantity']);
                        }else {
                            if ($inquiry_products['unit_id'] == 1)
                                $inquiries_stats_all[$i]['structure'] += $inquiry_products['quantity'];
                            elseif (($inquiry_products['unit_id'] == 2) || ($inquiry_products['unit_id'] == 3))
                                $inquiries_stats_all[$i]['structure'] += $dashboard->checkpending_quantity($inquiry_products['unit_id'], $inquiry_products['product_category_id'], $inquiry_products['quantity']);
                        }
                    }
                }
            }

            $inquiries_stats_all[$i]['pipe'] = round($inquiries_stats_all[$i]['pipe'] / 1000, 2);
            $inquiries_stats_all[$i]['structure'] = round($inquiries_stats_all[$i]['structure'] / 1000, 2);
        }

        foreach ($inquiries_stats_all as $key => $part) {
            $sort[$key] = strtotime($part['day']);
        }
        array_multisort($sort, SORT_ASC, $inquiries_stats_all);
        return json_encode($inquiries_stats_all);
    }

//    public function appssyncgraph_order() {
//        $dashboard = new DashboardController();
//        for ($i = 1; $i <= 7; $i++) {
//            $orders_stats_all[$i]['pipe'] = 0;
//            $orders_stats_all[$i]['structure'] = 0;
//            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
//            $orders_stats_all[$i]['day'] = $date_search;
//            $orders_stats = Order::with('all_order_products.order_product_details')
//                    ->where('order_status', '=', 'completed')
//                    ->where('updated_at', 'like', $date_search . '%')
//                    ->get();
//
//            foreach ($orders_stats as $order) {
//
//                foreach ($order['all_order_products'] as $order_products) {
//
//
//                    if (isset($order_products['order_product_details']['product_category']['product_type_id'])) {
//                        if ($order_products['order_product_details']['product_category']['product_type_id'] == 1) {
//                            if ($order_products['unit_id'] == 1)
//                                $orders_stats_all[$i]['pipe'] += $order_products['quantity'];
//                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
//                                $orders_stats_all[$i]['pipe'] += $dashboard->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
//                        }else {
//                            if ($order_products['unit_id'] == 1)
//                                $orders_stats_all[$i]['structure'] += $order_products['quantity'];
//                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
//                                $orders_stats_all[$i]['structure'] += $dashboard->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
//                        }
//                    }
//                }
//            }
//
//            $orders_stats_all[$i]['pipe'] = round($orders_stats_all[$i]['pipe'] / 1000, 2);
//            $orders_stats_all[$i]['structure'] = round($orders_stats_all[$i]['structure'] / 1000, 2);
//        }
//
//        foreach ($orders_stats_all as $key => $part) {
//            $sort[$key] = strtotime($part['day']);
//        }
//        array_multisort($sort, SORT_ASC, $orders_stats_all);
//        return ($orders_stats_all);
//    }


    public function appssyncgraph_order() {
        $date = new Carbon\Carbon;
        $date_search = $date->subDays(7);
        $orders_stats_all;

        $orders_stats = Order::with('aopwpsc', 'aopwpsc.order_product_details.product_category')->where('order_status', '=', 'completed')
                ->where('updated_at', '>', $date_search)
                ->orderBy('updated_at')
                ->get();

        for ($i = 1; $i <= 7; $i++) {
            $orders_stats_all[$i]['pipe'] = 0;
            $orders_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $orders_stats_all[$i]['day'] = $date_search;

            if (count($orders_stats) > 0) {
                foreach ($orders_stats as $order) {
                    if (date('Y-m-d', strtotime($order->updated_at)) == $date_search) {
                        foreach ($order['aopwpsc'] as $order_products) {

                            if (isset($order_products['order_product_details']['product_category']['product_type_id'])) {
                                if ($order_products['order_product_details']['product_category']['product_type_id'] == 1) {
                                    if ($order_products['unit_id'] == 1) {
                                        $orders_stats_all[$i]['pipe'] += $order_products['quantity'];
                                    } elseif (($order_products['unit_id'] == 2)) {
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] * $order_products['order_product_details']['weight']);
                                    } elseif (($order_products['unit_id'] == 3)) {
                                        $standard_length = $order_products['order_product_details']['standard_length'];
                                        if ($order_products['order_product_details']['standard_length'] == 0) {
                                            $standard_length = 1;
                                        }
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] / $standard_length * $order_products['order_product_details']['weight']);
                                    }
//                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
//                                $orders_stats_all[$i]['pipe'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
                                } else {
                                    if ($order_products['unit_id'] == 1) {
                                        $orders_stats_all[$i]['structure'] += $order_products['quantity'];
                                    } elseif (($order_products['unit_id'] == 2)) {
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] * $order_products['order_product_details']['weight']);
                                    } elseif (($order_products['unit_id'] == 3)) {
                                        $standard_length = $order_products['order_product_details']['standard_length'];
                                        if ($order_products['order_product_details']['standard_length'] == 0) {
                                            $standard_length = 1;
                                        }
                                        $orders_stats_all[$i]['pipe'] += ($order_products['quantity'] / $standard_length * $order_products['order_product_details']['weight']);
                                    }
//                            elseif (($order_products['unit_id'] == 2) || ($order_products['unit_id'] == 3))
//                                $orders_stats_all[$i]['structure'] += $this->checkpending_quantity($order_products['unit_id'], $order_products['product_category_id'], $order_products['quantity']);
                                }
                            }
                        }
                    }
                }
            }

            $orders_stats_all[$i]['pipe'] = round($orders_stats_all[$i]['pipe'] / 1000, 2);
            $orders_stats_all[$i]['structure'] = round($orders_stats_all[$i]['structure'] / 1000, 2);
        }

        foreach ($orders_stats_all as $key => $part) {
            $sort[$key] = strtotime($part['day']);
        }
        array_multisort($sort, SORT_ASC, $orders_stats_all);
        return ($orders_stats_all);
    }

    /* To get Delivery Challan stats for graph */

    public function appssyncgraph_delivery_challan() {
        $date = new Carbon\Carbon;
        $date_search = $date->subDays(7);
        $orders_stats_all;
        $delivery_challan_stats = DeliveryChallan::with('delivery_challan_products', 'delivery_challan_products.order_product_details.product_category')
                ->where('challan_status', '=', 'completed')
                ->where('updated_at', '>', $date_search)
                ->get();

        for ($i = 1; $i <= 7; $i++) {
            $delivery_challan_stats_all[$i]['pipe'] = 0;
            $delivery_challan_stats_all[$i]['structure'] = 0;
            $date_search = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - ($i - 1), date("Y")));
            $delivery_challan_stats_all[$i]['day'] = $date_search;


            foreach ($delivery_challan_stats as $delivery_challan) {
                if (date('Y-m-d', strtotime($delivery_challan->updated_at)) == $date_search) {
                    foreach ($delivery_challan['delivery_challan_products'] as $delivery_challan_products) {

                        if (isset($delivery_challan_products['order_product_details']['product_category']['product_type_id'])) {
                            if ($delivery_challan_products['order_product_details']['product_category']['product_type_id'] == 1) {
                                $delivery_challan_stats_all[$i]['pipe'] += $delivery_challan_products['actual_quantity'];
                            } else {
                                $delivery_challan_stats_all[$i]['structure'] += $delivery_challan_products['actual_quantity'];
//                           
                            }
                        }
                    }
                }
            }

            $delivery_challan_stats_all[$i]['pipe'] = round($delivery_challan_stats_all[$i]['pipe'] / 1000, 2);
            $delivery_challan_stats_all[$i]['structure'] = round($delivery_challan_stats_all[$i]['structure'] / 1000, 2);
        }
        foreach ($delivery_challan_stats_all as $key => $part) {
            $sort[$key] = strtotime($part['day']);
        }
        array_multisort($sort, SORT_ASC, $delivery_challan_stats_all);

        return ($delivery_challan_stats_all);
    }

    /**
     * App get all labours
     */
    public function appalllabours() {

        if (Input::has('labour_sync_date') && Input::get('labour_sync_date') != '') {
            $labours['all'] = Labour::where('updated_at', '>', Input::get('labour_sync_date'))->get();
        } else {
            $labours['all'] = Labour::get();
        }
        $labour_date = Labour::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($labour_date)) {
            $labours['latest_date'] = $labour_date->updated_at->toDateTimeString();
        } else {
            $labours['latest_date'] = "";
        }
        return

                json_encode($labours);
    }

    /**
     * App save labours
     */
    public function appaddlabour() {
        $labour_check = Labour::where('phone_number', '=', Input::get('phone_number'))
                ->where('first_name', '=', Input::get('first_name'))
                ->where('last_name', '=', Input::get('last_name'))
                ->first();
        if (isset($labour_check->id)) {
            return json_encode(array('result' => false, 'labour_id' => $labour_check->id, 'message' => 'Labour already exist'));
        }
        $labour = new Labour();
        if (Input::has('first_name'))
            $labour->first_name = Input::get('first_name');
        if (Input::has('last_name'))
            $labour->last_name = Input::get('last_name');
        if (Input::has('password'))
            $labour->password = Hash::make(Input::get('password'));
        if (Input::has('phone_number'))
            $labour->phone_number = Input::get('phone_number');

        if ($labour->save())
            return json_encode(array('result' => true, 'labour_id' => $labour->id, 'message' => 'Labour added successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function appupdatelabour() {

        $labour = Labour::find(Input::get('labour_id'));
        if (!isset($labour->id)) {
            return json_encode(array('result' => false, 'message' => 'Labour not found'));
        }
        if (Input::has('first_name') && Input::get('first_name') != "")
            $labour->first_name = Input::get('first_name');
        if (Input::has('last_name') && Input::get('last_name') != "")
            $labour->last_name = Input::get('last_name');
        if (Input::has('phone_number') && Input::get('phone_number') != "")
            $labour->phone_number = Input::get('phone_number');
        if (Input::has('password') && Input::get('password') != "")
            $labour->password = Hash::make(Input::get('password'));

        if ($labour->save())
            return json_encode(array('result' => true, 'labour_id' => $labour->id, 'message' => 'Labour details updated successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function appdeletelabour() {
        if (Input::has('labour_id')) {
            $id = Input::get('labour_id');

            $labour = Labour::find($id);
            $labour->delete();

            return json_encode(array('result' => true, 'labour_id' => $labour->id, 'message' => 'Labour deleted successfully'));
        } else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function applabourperformance() {

        $enddate = date("Y-m-d");
        $date = date('Y-m-01', time());
        $loader_array = array();
        $loaders_data = array();
        $var = 0;

        $labours = Labour::withTrashed()->get();

        $loader_arr = array();
//        $delivery_order_data = DeliveryChallan::
//                has('challan_labours.dc_delivery_challan.delivery_order.delivery_product')
//                ->with('challan_labours.dc_delivery_challan.delivery_order.delivery_product')
//                ->where('created_at', '>', "$date")
//                ->get();

        $labour_all = \App\DeliveryChallanLabours::get();

        $purchase_order_data = \App\PurchaseChallan::
                has('challan_labours.pc_delivery_challan.all_purchase_products')
                ->with('challan_labours')
                ->get();

        $temp1 = [];
        $pipe = [];
        $loader_arr = [];
        $summedArray = [];


        foreach ($labour_all as $loaded_by_key => $labour_value) {
            if ($labour_value['total_qty'] != 0) {
                $total_qty_temp = 0;
                $id = $labour_value['delivery_challan_id'];
                if (isset($summedArray[$id])) {
                    $total_qty_temp = $summedArray[$id];
                }
                if (!isset($loader_arr[$id]['pipe_labour'])) {
                    $temp_pipe = array();
                }
                if (!isset($loader_arr[$id]['structure_labour'])) {
                    $temp = array();
                }
                $summedArray[$id] = $total_qty_temp + $labour_value['total_qty'];
                $loader_arr[$id]['delivery_id'] = $id;
                $loader_arr[$id]['delivery_date'] = date('Y-m-d', strtotime($labour_value['created_at']));

                $loader_arr[$id]['tonnage'] = $total_qty_temp + $labour_value['total_qty'];
                array_push($temp_pipe, $labour_value['labours_id']);
                array_push($temp, $labour_value['labours_id']);
                $loader_arr[$id]['labours'] = $temp_pipe;
                if ($labour_value['product_type_id'] == 1) {
                    $loader_arr[$id]['pipe_labour'] = $temp_pipe;
                    $loader_arr[$id]['pipe_tonnage'] = $labour_value['total_qty'];
                } else if ($labour_value['product_type_id'] == 2) {
                    $loader_arr[$id]['structure_labour'] = $temp;
                    $loader_arr[$id]['structure_tonnage'] = $labour_value['total_qty'];
                }
            }
        }


        foreach ($loader_arr as $key => $value_temp) {
            if (isset($value_temp['pipe_labour'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['pipe_tonnage'] / 1000;
                $loaders_data[$var++]['labours'] = $value_temp['pipe_labour'];
            }
            if (isset($value_temp['structure_labour'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['structure_tonnage'] / 1000;
                $loaders_data[$var++]['labours'] = $value_temp['structure_labour'];
            }
            if (!isset($value_temp['pipe_labour']) && !isset($value_temp['structure_labour'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['tonnage'] / 1000;
                $loaders_data[$var++]['labours'] = $value_temp['labours'];
            }
        }


        foreach ($purchase_order_data as $delivery_order_info) {
            $arr = array();
            $arr_money = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_labours) && count($delivery_order_info->challan_labours) > 0 && !empty($delivery_order_info->challan_labours)) {
                foreach ($delivery_order_info->challan_labours as $challan_info) {
                    $deliver_sum = 0.00;
                    $money = 0.00;
                    array_push($loaders, $challan_info->labours_id);
                    foreach ($challan_info->pc_delivery_challan as $info) {
                        foreach ($info->all_purchase_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->quantity;
                        }
                    }


                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['labours'] = $loaders;
                    $loader_arr['tonnage'] = $all_tonnage;
                }
            }
            $loaders_data[$var] = $loader_arr;
            $var++;
        }

        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);

        $final_array = array();
        $k = 0;
        foreach ($labours as $key => $labour) {
            foreach ($loaders_data as $key_data => $data) {
                foreach ($data['labours'] as $key_value => $value) {
                    if ($value == $labour['id']) {
                        $final_array[$k++] = [
                            'delivery_id' => $data['delivery_id'],
                            'labour_id' => $value,
                            'date' => $data['delivery_date'],
                            'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : 0),
                            'delivery_sum_money' => isset($data['delivery_sum_money']) ? $data['delivery_sum_money'] : '0',
                        ];
                    }
                }
            }
        }



        return json_encode(array('result' => true,
            'labours' => $labours,
            'data' => $final_array,
            'enddate' => $enddate));
    }

    public function applabourperformance_temp() {

        $enddate = date("Y-m-d");
        $date = date('Y-m-01', time());
        $loader_array = array();
        $var = 0;

        $labours = Labour::all();

        $loader_arr = array();
        $delivery_order_data = DeliveryChallan::
                has('challan_labours.dc_delivery_challan.delivery_order.delivery_product')
                ->with('challan_labours.dc_delivery_challan.delivery_order.delivery_product')
                ->where('created_at', '>', "$date")
                ->get();

        $labour_all = \App\DeliveryChallanLabours::get();

        $temp1 = [];
        $pipe = [];
        $loader_arr = [];
        $summedArray = [];


        foreach ($labour_all as $loaded_by_key => $labour_value) {
            if ($labour_value['total_qty'] != 0) {
                $total_qty_temp = 0;
                $id = $labour_value['delivery_challan_id'];
                if (isset($summedArray[$id])) {
                    $total_qty_temp = $summedArray[$id];
                }
                if (!isset($loader_arr[$id]['pipe_labour'])) {
                    $temp_pipe = array();
                }
                if (!isset($loader_arr[$id]['structure_labour'])) {
                    $temp = array();
                }
                $summedArray[$id] = $total_qty_temp + $labour_value['total_qty'];
                $loader_arr[$id]['delivery_id'] = $id;
                $loader_arr[$id]['delivery_date'] = date('Y-m-d', strtotime($labour_value['created_at']));

                $loader_arr[$id]['tonnage'] = $total_qty_temp + $labour_value['total_qty'];
                array_push($temp_pipe, $labour_value['labours_id']);
                array_push($temp, $labour_value['labours_id']);
                $loader_arr[$id]['labours'] = $temp_pipe;
                if ($labour_value['product_type_id'] == 1) {
                    $loader_arr[$id]['pipe_labour'] = $temp_pipe;
                    $loader_arr[$id]['pipe_tonnage'] = $labour_value['total_qty'];
                } else if ($labour_value['product_type_id'] == 2) {
                    $loader_arr[$id]['structure_labour'] = $temp;
                    $loader_arr[$id]['structure_tonnage'] = $labour_value['total_qty'];
                }
            }
        }


        foreach ($loader_arr as $key => $value_temp) {
            if (isset($value_temp['pipe_labour'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['pipe_tonnage'] / 1000;
                $loaders_data[$var++]['labours'] = $value_temp['pipe_labour'];
            }
            if (isset($value_temp['structure_labour'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['structure_tonnage'] / 1000;
                $loaders_data[$var++]['labours'] = $value_temp['structure_labour'];
            }
        }



        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);




        $final_array = array();
        $k = 0;
        foreach ($labours as $key => $labour) {
            foreach ($loaders_data as $key_data => $data) {
                foreach ($data['labours'] as $key_value => $value) {
                    if ($value == $labour['id']) {
                        $final_array[$k++] = [
                            'delivery_id' => $data['delivery_id'],
                            'labour_id' => $value,
                            'date' => $data['delivery_date'],
                            'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : 0),
                            'delivery_sum_money' => isset($data['delivery_sum_money']) ? $data['delivery_sum_money'] : '0',
                        ];
                    }
                }
            }
        }




        return json_encode(array('result' => true,
            'labours' => $labours,
            'data' => $final_array,
            'enddate' => $enddate));

//            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App get all loadedby
     */
    public function appallloadedby() {

        if (Input::has('loadedby_sync_date') && Input::get('loadedby_sync_date') != '') {
            $loadedby['all'] = LoadedBy::where('updated_at', '>', Input::get('loadedby_sync_date'))->get();
        } else {
            $loadedby['all'] = LoadedBy::get();
        }
        $loadedby_date = LoadedBy::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($loadedby_date)) {
            $loadedby['latest_date'] = $loadedby_date->updated_at->toDateTimeString();
        } else {
            $loadedby['latest_date'] = "";
        }
        return

                json_encode($loadedby);
    }

    /**
     * App to save loadedby
     */
    public function appaddloadedby() {
        $loadedby_check = LoadedBy::where('phone_number', '=', Input::get('phone_number'))
                ->where('first_name', '=', Input::get('first_name'))
                ->where('last_name', '=', Input::get('last_name'))
                ->first();
        if (isset($loadedby_check->id)) {
            return json_encode(array('result' => false, 'loadedby_id' => $loadedby_check->id, 'message' => 'Loaded By user already exist'));
        }
        $loadedby = new LoadedBy();
        if (Input::has('first_name'))
            $loadedby->first_name = Input::get('first_name');
        if (Input::has('last_name'))
            $loadedby->last_name = Input::get('last_name');
        if (Input::has('password'))
            $loadedby->password = Hash::make(Input::get('password'));
        if (Input::has('phone_number'))
            $loadedby->phone_number = Input::get('phone_number');

        if ($loadedby->save())
            return json_encode(array('result' => true, 'loadedby_id' => $loadedby->id, 'message' => 'Loaded By User added successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function appupdateloadedby() {
        $loadedby = LoadedBy::find(Input::get('loadedby_id'));
        if (!isset($loadedby->id)) {
            return json_encode(array('result' => false, 'message' => 'LoadedBy not found'));
        }
        if (Input::has('first_name') && Input::get('first_name') != "")
            $loadedby->first_name = Input::get('first_name');
        if (Input::has('last_name') && Input::get('last_name') != "")
            $loadedby->last_name = Input::get('last_name');
        if (Input::has('phone_number') && Input::get('phone_number') != "")
            $loadedby->phone_number = Input::get('phone_number');
        if (Input::has('password') && Input::get('password') != "")
            $loadedby->password = Hash::make(Input::get('password'));

        if ($loadedby->save())
            return json_encode(array('result' => true, 'loadedby_id' => $loadedby->id, 'message' => 'Loaded by User details updated successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function apploadedbyperformance() {
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $loaded_by = LoadedBy::withTrashed()->get();
        $enddate = date("Y-m-d");
        $date = date('Y-03-01', time());

//        $delivery_order_data = DeliveryChallan::
//                        has('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')
//                        ->with('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')
//                        ->where('created_at', '>', "$date")->get();

        $purchase_order_data = \App\PurchaseChallan::
                has('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                ->with('challan_loaded_by.pc_delivery_challan.all_purchase_products')
                ->withTrashed()
                ->get();

        $loaded_by_all = \App\DeliveryChallanLoadedBy::get();

        $temp1 = [];
        $pipe = [];
        $loader_arr = [];
        $summedArray = [];
        foreach ($loaded_by_all as $loaded_by_key => $loaded_by_value) {
            if ($loaded_by_value['total_qty'] != 0) {
                $total_qty_temp = 0;
                $id = $loaded_by_value['delivery_challan_id'];
                if (isset($summedArray[$id])) {
                    $total_qty_temp = $summedArray[$id];
                }
                if (!isset($loader_arr[$id]['pipe_loaders'])) {
                    $temp_pipe = array();
                }
                if (!isset($loader_arr[$id]['structure_loaders'])) {
                    $temp = array();
                }
                $summedArray[$id] = $total_qty_temp + $loaded_by_value['total_qty'];
                $loader_arr[$id]['delivery_id'] = $id;
                $loader_arr[$id]['delivery_date'] = date('Y-m-d', strtotime($loaded_by_value['created_at']));

                $loader_arr[$id]['tonnage'] = $total_qty_temp + $loaded_by_value['total_qty'];
                array_push($temp_pipe, $loaded_by_value['loaded_by_id']);
                array_push($temp, $loaded_by_value['loaded_by_id']);
                $loader_arr[$id]['loaders'] = $temp_pipe;
                if ($loaded_by_value['product_type_id'] == 1) {
                    $loader_arr[$id]['pipe_loaders'] = $temp_pipe;
                    $loader_arr[$id]['pipe_tonnage'] = $loaded_by_value['total_qty'];
                } else if ($loaded_by_value['product_type_id'] == 2) {
                    $loader_arr[$id]['structure_loaders'] = $temp;
                    $loader_arr[$id]['structure_tonnage'] = $loaded_by_value['total_qty'];
                }
            }
        }



        foreach ($loader_arr as $key => $value_temp) {
            if (isset($value_temp['pipe_loaders'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['pipe_tonnage'] / 1000;
                $loaders_data[$var++]['loaders'] = $value_temp['pipe_loaders'];
            } else if (isset($value_temp['structure_loaders'])) {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['structure_tonnage'] / 1000;
                $loaders_data[$var++]['loaders'] = $value_temp['structure_loaders'];
            } else {
                $loaders_data[$var]['delivery_id'] = $value_temp['delivery_id'];
                $loaders_data[$var]['delivery_date'] = $value_temp['delivery_date'];
                $loaders_data[$var]['tonnage'] = $value_temp['tonnage'];
                $loaders_data[$var++]['loaders'] = $value_temp['loaders'];
            }
        }


        foreach ($purchase_order_data as $delivery_order_info) {
            $arr = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_loaded_by) && count($delivery_order_info->challan_loaded_by) > 0 && !empty($delivery_order_info->challan_loaded_by)) {
                foreach ($delivery_order_info->challan_loaded_by as $challan_info) {
                    $deliver_sum = 0;
                    array_push($loaders, $challan_info->loaded_by_id);
                    foreach ($challan_info->pc_delivery_challan as $info) {
                        foreach ($info->all_purchase_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->quantity;
                        }
                    }
                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['tonnage'] = round($deliver_sum / count($loaders, 2));
                    $loader_arr['loaders'] = $loaders;
                }
            }
            $loaders_data[$var++] = $loader_arr;
        }

        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);
        $final_array = array();
        $k = 0;
        foreach ($loaded_by as $key => $labour) {
            foreach ($loaders_data as $key_data => $data) {
                foreach ($data['loaders'] as $key_value => $value) {
                    if ($value == $labour['id']) {
                        $final_array[$k++] = [
                            'delivery_id' => $data['delivery_id'],
                            'loader_id' => $value,
                            'date' => $data['delivery_date'],
                            'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : 0)
                        ];
                    }
                }
            }
        }

        return json_encode(array('result' => true,
            'loaded_by' => $loaded_by,
            'data' => $final_array,
            'date' => $date,
            'enddate' => $enddate));
    }

    public function apploadedbyperformance_temp() {
        $var = 0;
        $loader_arr = array();
        $loader_array = array();
        $loaders_data = array();
        $loaded_by = LoadedBy::all();
        $enddate = date("Y-m-d");
        $date = date('Y-03-01', time());

        $delivery_order_data = DeliveryChallan::
                        has('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')
                        ->with('challan_loaded_by.dc_delivery_challan.delivery_order.delivery_product')
                        ->where('created_at', '>', "$date")->get();

        foreach ($delivery_order_data as $delivery_order_info) {
            $arr = array();
            $loaders = array();
            if (isset($delivery_order_info->challan_loaded_by) && count($delivery_order_info->challan_loaded_by) > 0 && !empty($delivery_order_info->challan_loaded_by)) {
                foreach ($delivery_order_info->challan_loaded_by as $challan_info) {
                    $deliver_sum = 0;
                    array_push($loaders, $challan_info->loaded_by_id);
                    foreach ($challan_info->dc_delivery_challan as $info) {
                        foreach ($info->delivery_challan_products as $delivery_order_productinfo) {
                            $deliver_sum += $delivery_order_productinfo->actual_quantity;
//                            if ($delivery_order_productinfo->unit_id == 1)
//                                $deliver_sum += $delivery_order_productinfo->quantity;
//                            elseif (($delivery_order_productinfo->unit_id == 2) || ($delivery_order_productinfo->unit_id == 3))
//                                $deliver_sum += $this->checkpending_quantity($delivery_order_productinfo->unit_id, $delivery_order_productinfo->product_category_id, $delivery_order_productinfo->quantity);
                        }
                    }
                    array_push($loader_array, $loaders);
                    $all_kg = $deliver_sum / count($loaders);
                    $all_tonnage = $all_kg / 1000;
                    $loader_arr['delivery_id'] = $delivery_order_info['id'];
                    $loader_arr['delivery_date'] = date('Y-m-d', strtotime($delivery_order_info['created_at']));
                    $loader_arr['tonnage'] = $all_tonnage;
//                    $loader_arr['tonnage'] = round($deliver_sum / count($loaders, 2));
                    $loader_arr['loaders'] = $loaders;
                }
            }
            $loaders_data[$var++] = $loader_arr;
        }
        $loaders_data = array_filter(array_map('array_filter', $loaders_data));
        $loaders_data = array_values($loaders_data);
        $final_array = array();
        $k = 0;
        foreach ($loaded_by as $key => $labour) {
            foreach ($loaders_data as $key_data => $data) {
                foreach ($data['loaders'] as $key_value => $value) {
                    if ($value == $labour['id']) {
                        $final_array[$k++] = [
                            'delivery_id' => $data['delivery_id'],
                            'loader_id' => $value,
                            'date' => $data['delivery_date'],
                            'tonnage' => (isset($data['tonnage']) ? round($data['tonnage'], 2) : 0)
                        ];
                    }
                }
            }
        }

        return json_encode(array('result' => true,
            'loaded_by' => $loaded_by,
            'data' => $final_array,
            'date' => $date,
            'enddate' => $enddate));
    }

    /**
     * App get all collection
     */
    public function appallcollection_admin() {

        if (Input::has('collection_sync_date') && Input::get('collection_sync_date') != '') {
            $collection['all'] = User::with('locations.location_data')
                    ->where('updated_at', '>', Input::get('loadedby_sync_date'))
                    ->where('role_id', '=', 6)
                    ->get();
        } else {
            $collection['all'] = User::with('locations.location_data')
                    ->where('role_id', '=', 6)
                    ->get();
        }
        $collection_date = LoadedBy::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($collection_date)) {
            $collection['latest_date'] = $collection_date->updated_at->toDateTimeString();
        } else {
            $collection['latest_date'] = "";
        }
        return

                json_encode($collection);
    }

    /**
     * App to save collection
     */
    public function appaddcollection_admin() {
        $collection_check = User::where('mobile_number', '=', Input::get('mobile_number'))
                ->where('first_name', '=', Input::get('first_name'))
                ->where('last_name', '=', Input::get('last_name'))
                ->where('role_id', '=', '6')
                ->first();
        if (isset($collection_check->id)) {
            return json_encode(array('result' => false, 'collection_id' => $collection_check->id, 'message' => 'Collection user already exist'));
        }

        $collection_check = User::where('mobile_number', '=', Input::get('mobile_number'))
                ->first();
        if (isset($collection_check->id)) {
            return json_encode(array('result' => false, 'collection' => $collection_check->id, 'message' => 'Mobile already exist'));
        }

        if (Input::has('location')) {
            $locations = (json_decode(Input::get('location')));
            $Users_data = new User();
            $Users_data->role_id = 6;
            if (Input::has('first_name'))
                $Users_data->first_name = Input::get('first_name');
            if (Input::has('last_name'))
                $Users_data->last_name = Input::get('last_name');
            if (Input::has('password'))
                $Users_data->password = Hash::make(Input::get('password'));
            if (Input::has('mobile_number'))
                $Users_data->mobile_number = Input::get('mobile_number');
            if (Input::has('email'))
                $Users_data->email = Input::get('email');

            if ($Users_data->save()) {

                foreach ($locations as $loc) {
                    if (isset($loc)) {
                        $CLocation = new CollectionUser();
                        $CLocation->user_id = $Users_data->id;
                        $CLocation->location_id = $loc;
                        $CLocation->save();
                    }
                }
            }


            return json_encode(array('result' => true, 'collection_id' => $Users_data->id, 'message' => 'Collection User added successfully'));
        } else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function appupdatecollection_admin() {
        $collection_check = User::where('mobile_number', '=', Input::get('mobile_number'))
                ->where('first_name', '=', Input::get('first_name'))
                ->where('last_name', '=', Input::get('last_name'))
                ->where('role_id', '=', '6')
                ->where('id', '<>', Input::get('collection_id'))
                ->first();
        if (isset($collection_check->id)) {
            return json_encode(array('result' => false, 'collection_id' => $collection_check->id, 'message' => 'Collection user already exist'));
        }

        $collection_check = User::where('mobile_number', '=', Input::get('mobile_number'))
                ->where('id', '<>', Input::get('collection_id'))
                ->first();
        if (isset($collection_check->id)) {
            return json_encode(array('result' => false, 'collection' => $collection_check->id, 'message' => 'Mobile already exist'));
        }

        if (Input::has('location')) {
            $locations = (json_decode(Input::get('location')));
            $id = Input::get('collection_id');
            $user = User::where('id', $id);
            if (isset($user)) {
                $user_res = User::where('id', $id)->update(['first_name' => Input::get('first_name'), 'last_name' => Input::get('last_name'), 'mobile_number' => Input::get('mobile_number'), 'email' => Input::get('email')]);
                if ($user_res) {

                    $del_res = CollectionUser::where('user_id', '=', $id)->delete();

                    foreach ($locations as $loc) {
                        if (isset($loc)) {
                            $collectionuser = new CollectionUser();
                            $collectionuser->user_id = $id;
                            $collectionuser->location_id = $loc;
                            $collectionuser->save();
                        }
                    }
                }
            }

            return json_encode(array('result' => true, 'collection_id' => $id, 'message' => 'Collection User Updated successfully'));
        } else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    public function appdeletecollection_admin() {
        if (Input::has('collection_id')) {
            $id = Input::get('collection_id');

            $del_res = User::destroy($id);
            if ($del_res) {
                CollectionUser::where('id', $id)->delete();
            }
            $del_res = CollectionUser::where('user_id', '=', $id)->delete();

            return json_encode(array('result' => true, 'collection_id' => $id, 'message' => 'Collection User deleted successfully'));
        } else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App get all territory
     */
    public function appallterritory_admin() {

        if (Input::has('territory_sync_date') && Input::get('territory_sync_date') != '') {
            $territories['all'] = Territory::with('territorylocation')->where('updated_at', '>', Input::get('territory_sync_date'))->get();
        } else {
            $territories['all'] = Territory::with('territorylocation')->get();
        }
        $territory_date = Territory::select('updated_at')->orderby('updated_at', 'DESC')->first();
        if (!empty($territory_date)) {
            $territories['latest_date'] = $territory_date->updated_at->toDateTimeString();
        } else {
            $territories['latest_date'] = "";
        }
        return json_encode($territories);
    }

    /**
     * App save territory
     */
    public function appaddterritory_admin() {

        if (Input::has('territory_name') && Input::has('location')) {
            $territory_check = Territory::where('teritory_name', '=', Input::get('territory_name'))->first();
            if (isset($territory_check->id)) {
                return json_encode(array('result' => false, 'territory_check' => $territory_check->id, 'message' => 'Territory already exist'));
            }

            $territory = new Territory();
            $locations = (json_decode(Input::get('location')));
            $territory->teritory_name = Input::get('territory_name');
            $territory->save();
            $teritory_id = $territory->id;

            if (isset($teritory_id)) {
                foreach ($locations as $loc) {

                    $territory_loc = new TerritoryLocation();
                    $territory_loc->teritory_id = $teritory_id;
                    $territory_loc->location_id = $loc;
                    $territory_loc->save();
                }
            } else {
                return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
            }
            return json_encode(array('result' => true, 'territory_id' => $territory->id, 'message' => 'Territory added successfully'));
        }

        return json_encode(array('result' => false, 'message' => 'Territory Name and Location are required. Please try again'));
    }

    /**
     * App update territory
     */
    public function appupdateterritory_admin() {
        if (Input::has('territory_name') && Input::has('location') && Input::has('territory_id')) {
            $id = Input::get('territory_id');


            $territory = Territory::find($id);
            if (!isset($territory->id)) {
                return json_encode(array('result' => false, 'message' => 'Territory not found'));
            }
            $territory->teritory_name = Input::get('territory_name');
            $territory->save();
            $locations = (json_decode(Input::get('location')));

            $territory_loc = TerritoryLocation::where('teritory_id', '=', $id)->get();
            foreach ($territory_loc as $loc) {
                $territory_old = TerritoryLocation::find($loc->id);
                $territory_old->delete();
            }
            foreach ($locations as $loc) {
                $territory_loc = new TerritoryLocation();
                $territory_loc->teritory_id = $id;
                $territory_loc->location_id = $loc;
                $territory_loc->save();
            }

            return json_encode(array('result' => true, 'labour_id' => $territory->id, 'message' => 'Territory details updated successfully'));
        } else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

    /**
     * App delete territory
     */
//    public function appdeleteterritory_admin() {
//        if (Input::has('territory_id')) {
//            $id = Input::get('territory_id');
//
//            $territory = Territory::find($id);
//            $territory->delete();
//            $territory_loc = TerritoryLocation::where('teritory_id', '=', $id)->get();
//            foreach ($territory_loc as $loc) {
//                $territory_old = TerritoryLocation::find($loc->id);
//                $territory_old->delete();
//            }
//
//            return json_encode(array('result' => true, 'labour_id' => $territory->id, 'message' => 'Territory deleted successfully'));
//        } else
//            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
//    }

    /**
     * App Inventory
     */
    public function appallinventory_admin() {

        if (Input::has('product_category_id')) {
            $product_id = Input::get('product_category_id');
            $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        } else {
            $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'asc')->limit(1)->get();
        }


        $product_price = $product_last[0]->price;
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;


        if ($product_type == 1) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                            } else {
                                $total_qnty = "-";
                            }
                            $report_arr[$size][$thickness] = $total_qnty;
                        }
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $inventory = $sub_cat['product_inventory'];
                    $total_qnty = 0;
                    if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                        $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                    } else {
                        $total_qnty = "-";
                    }
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_qnty;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;



        if (!empty($report_arr)) {
            return json_encode($report_arr);
        } else {
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
        }
    }

    /**
     * App Price show
     */
    public function appallprice_admin() {

        if (Input::has('product_category_id')) {
            $product_id = Input::get('product_category_id');
            $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        } else {
            $product_last = ProductCategory::with('product_sub_categories.product_inventory')->orderBy('created_at', 'asc')->limit(1)->get();
        }


        $product_price = $product_last[0]->price;
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    if ($sub_cat->thickness == $thickness) {
                        $inventory = $sub_cat['product_inventory'];
                        $total_price = $product_price + $sub_cat->difference;

                        $report_arr[$sub_cat->size][$sub_cat->thickness] = $total_price;
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    $inventory = $sub_cat['product_inventory'];
                    $total_price = $product_price + $sub_cat->difference;
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_price;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;


        if (!empty($report_arr)) {
            return json_encode($report_arr);
        } else {
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
        }
    }

    public function appgetproducttype() {

        if (Input::has('product_category_id')) {
            $product_id = Input::get('product_category_id');
            $product_last = ProductCategory::find($product_id);
            $product_type = \App\ProductType::find($product_last->product_type_id);
            return json_encode($product_type);
        }
    }

    /**
     * App Price update
     */
    public function appupdateprice() {

        if (Input::has('product_category_id')) {
            $product_id = Input::get('product_category_id');
            $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();

            $product_type = $product_last[0]->product_type_id;

            $size = Input::get('size');
            $thickness = Input::get('thickness');
            $new_price = Input::get('new_price');


            if ($product_type == 1) {
                if (isset($product_id) && !empty($size) && !empty($thickness)) {
                    $subproduct = ProductSubCategory::where('product_category_id', '=', $product_id)
                                    ->where('thickness', '=', $thickness)
                                    ->where('size', '=', $size)->get();
                    if (isset($subproduct))
                        $sub_prod_id = $subproduct[0]->id;
                } else {
                    return json_encode(array('result' => false, 'message' => 'Some error occured1. Please try again'));
                }
            }
            if ($product_type == 2) {
                if (isset($product_id) && !empty($product_id) && !empty($size)) {
                    $subproduct = ProductSubCategory::where('product_category_id', '=', $product_id)
                                    ->where('alias_name', '=', $size)->get();
                    $sub_prod_id = $subproduct[0]->id;
                } else {
                    return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
                }
            }

            $product_category = ProductCategory::where('id', '=', $product_id)->get();
            $product_base_price = $product_category[0]->price;
            $difference = $new_price - $product_base_price;
            $update_sub_prod = ProductSubCategory::find($sub_prod_id);
            $update_sub_prod->difference = $difference;
            $update_sub_prod->save();
            return json_encode(array('result' => true, 'product_category_id' => $update_sub_prod->id, 'message' => 'Price Updated successfully.'));
        } else {
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
        }
    }

    /*   API due payment show
     * 
     * */

    public function appduepaymentshow_admin() {

        $duepayment_response = [];
//        $customers = Customer::with('delivery_challan')->with('customer_receipt')->with('collection_user_location.collection_user')->with('delivery_location')->orderBy('created_at', 'desc')
//                        ->whereHas('delivery_challan', function ($query) {
//                            $query->where('challan_status', '=', 'completed');
//                        })->get();
        $customers = Customer::with(['delivery_challan' => function ($query) {
                        $query->where('delivery_challan.challan_status', 'completed');
                    }])
                ->with('customer_receipt')
                ->with('collection_user_location.collection_user')
                ->with('delivery_location')
                ->orderBy('created_at', 'desc')
                ->get();

        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();

        $territories = Territory::orderBy('created_at', 'DESC')->get();

        $duepayment_response['customers_details'] = ($customers && count($customers) > 0) ? $customers : array();
        $duepayment_response['delivery_location'] = ($delivery_location && count($delivery_location) > 0) ? $delivery_location : array();
        $duepayment_response['territories'] = ($territories && count($territories) > 0) ? $territories : array();

        return json_encode($duepayment_response);
    }

    /*   API due payment - change unsettle amout (alos can affect receipt master)
     * 
     * */

    public function appchangeunsettledamount_admin() {
        if (Input::has('customer')) {
            $customers = (json_decode(Input::get('customer')));
        }
        if (count($customers) > 0) {
            $customer_response = [];
            foreach ($customers as $customer) {
                $customer_id = $customer->customer_id;
                $old_amount = $customer->old_amount;
                $new_amount = $customer->new_amount;
                $difference = $new_amount - $old_amount;

                $receipts = Customer_receipts::where('customer_id', '=', $customer_id)->orderBy('created_at', 'DESC')->first();

                if (isset($receipts) && !empty($receipts)) {
                    $receipt_id = $receipts->id;
//                    $receipt = Customer_receipts::find($receipt_id);
                    $receipt_amount = $receipts->settled_amount;
                    $new_unsettle_amount = $receipt_amount + $difference;
                    $receipts->settled_amount = $new_unsettle_amount;
                    $receipts->save();
                } else {
                    $receiptObj = new Receipt();
                    if ($receiptObj->save()) {
                        $customerReceiptObj = new Customer_receipts();
                        $customerReceiptObj->customer_id = $customer_id;
                        $customerReceiptObj->settled_amount = $new_amount;
                        $customerReceiptObj->debited_by_type = 1;
                        $customerReceiptObj->receipt_id = $receiptObj->id;
                        $customerReceiptObj->save();
                    }
                }
                $customer_response['customer_details'] = ($receipts && count($receipts) > 0) ? Customer_receipts::where('customer_id', '=', $customer_id)->orderBy('created_at', 'DESC')->first() : array();
                return json_encode($customer_response);
            }
        } else {
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
        }
    }

    /*   API due payment - settle amount
     * 
     * */

    public function appsettleamount_admin() {
        if (Input::has('customer')) {
            $customers = (json_decode(Input::get('customer')));
        }
        if (count($customers) > 0) {
            $customer_response = [];
            foreach ($customers as $customer) {
                $customer_id = $customer->customer_id;
                $unsettle_amount = $customer->model_price;
                $challan_id = $customer->challan_id;

                if (isset($challan_id)) {
                    $challan_obj = DeliveryChallan::find($challan_id);
                    if ($challan_obj->settle_amount && $challan_obj->settle_amount != "") {
                        $pre_amount = $challan_obj->settle_amount;
                        $curr_amount = sprintf("%.2f", $unsettle_amount);
                        $total_amount = $pre_amount + $curr_amount;
                    } else {
                        $total_amount = sprintf("%.2f", $unsettle_amount);
                    }
                    $challan_obj->settle_amount = sprintf("%.2f", $total_amount);
                    ;
                    $challan_obj->save();
                    $customer_response['settle_details'] = ($challan_obj && count($challan_obj) > 0) ? $challan_obj = DeliveryChallan::find($challan_id) : array();
                }
                return json_encode($customer_response);
            }
        } else {
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
        }
    }

    /*   API due payment - unsettle amount
     * 
     * */

    public function appupdatesettleamount_admin() {
        if (Input::has('customer')) {
            $customers = (json_decode(Input::get('customer')));
        }
        if (count($customers) > 0) {
            $customer_response = [];
            foreach ($customers as $customer) {
                $customer_id = $customer->customer_id;
                $new_settle_amount = $customer->model_price;
                $challan_id = $customer->challan_id;

                if (isset($challan_id)) {
                    if (isset($challan_id)) {
                        $challan_obj = DeliveryChallan::find($challan_id);
                        $challan_obj->settle_amount = sprintf("%.2f", $new_settle_amount);
                        $challan_obj->save();
                        $customer_response['settle_details'] = ($challan_obj && count($challan_obj) > 0) ? $challan_obj = $challan_obj = DeliveryChallan::find($challan_id) : array();
                    }
                }
            }
            return json_encode($customer_response);
        } else {
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
        }
    }

    public function export_collection_users() {
        $search_field = Input::get('search');
        $location_id = Input::get('location');
        $territory_id = Input::get('territory');
        $loc_arr = [];
        $territory_arr = [];
        $collection_users = User::with('locations.location_data')->where('role_id', '=', 6);
        if (isset($search_field) && !empty($search_field)) {
            $collection_users->where(function($query) use ($search_field) {
                $query->where('first_name', 'like', '%' . $search_field . '%');
                $query->orwhere('last_name', 'like', '%' . $search_field . '%');
                $query->orwhere('mobile_number', 'like', '%' . $search_field . '%');
                $query->orwhere('email', 'like', '%' . $search_field . '%');
            });
        }
        if (isset($location_id) && !empty($location_id)) {
            $collection_users->whereHas('locations', function($query) use ($location_id) {
                $query->where('location_id', $location_id);
            });
        }
        if (isset($territory_id) && !empty($territory_id)) {
            $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
            foreach ($territory_locations as $loc) {
                if (!in_array($loc->teritory_id, $loc_arr)) {
                    array_push($territory_arr, $loc->teritory_id);
                }
                array_push($loc_arr, $loc->location_id);
            }
            $collection_users->whereHas('locations', function($query) use ($territory_arr) {
                $query->whereIn('teritory_id', $territory_arr);
            });
        }
        $collection_users = $collection_users->where('role_id', '=', 6)->orderBy('created_at', 'DESC')->get();

        $excel_name = 'Collectionuser-' . date('dmyhis');

        Excel::create($excel_name, function($excel) use($collection_users) {
            $excel->sheet('account', function($sheet) use($collection_users) {
                $sheet->loadView('excelView.collection_user.export_collection_user', array('users' => $collection_users));
            });
        })->export('xls');
    }

    function checkpending_quantity($unit_id, $product_category_id, $product_qty) {

        $kg_qty = 0;
        $product_info = ProductSubCategory::find($product_category_id);
        if ($unit_id == 1) {
            if (isset($product_info->quantity)) {
                $kg_qty = $product_info->quantity;
            } else {
                $kg_qty = 0;
            }
        } elseif ($unit_id == 2) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
            } else {
                $weight = 0;
            }
            $kg_qty = $kg_qty + ($product_qty * $weight);
        } elseif ($unit_id == 3) {
            if (isset($product_info->weight)) {
                $weight = $product_info->weight;
            } else {
                $weight = 1;
            }
            if (isset($product_info->standard_length)) {
                $std_length = $product_info->standard_length;
            } else {
                $std_length = 0;
            }
            $kg_qty = $kg_qty + (($product_qty / $std_length ) * $weight);
        }
        return $kg_qty;
    }

    public function current_time() {
        $date = date('m/d/Y h:i:s a', time());
        echo $date;
    }

}
