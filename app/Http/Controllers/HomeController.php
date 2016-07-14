<?php

namespace App\Http\Controllers;

use App\City;
use App\DeliveryLocation;
use App\States;
use App\Customer;
use App\User;
use App\ProductCategory;
use App\ProductSubCategory;
use App\ProductType;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;
use App\Inventory;
use App\Inquiry;
use App\Order;
use App\Units;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\Http\Controllers\DeliveryOrderController;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\InquiryProducts;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        
    }

    public function appCustomerLogin() {

        $customer = Customer::where('phone_number1', '=', Input::get('username'))->first();
        if ($customer) {
            if (Hash::check(Input::get('password'), $customer->password)) {
                return json_encode(array('result' => true,
                    'customer_id' => $customer->id,
                    'mobile_status' => true,
                    'message' => 'Login Successfully Done'));
            } else {
                return json_encode(array('result' => false, 'reason' => 'Password does not match', 'message' => 'Login Failed.'));
            }
        } else {
            return json_encode(array('result' => false, 'reason' => 'Customer not found', 'message' => 'Login Failed.'));
        }
    }

    public function customerResetPassword() {

        if (Input::get('otp') == '123456' || Input::get('otp') == 123456) {
            $customer = Customer::where('phone_number1', '=', Input::get('username'))->first();
            if ($customer) {
                $customer = Hash::make(Input::get('password'));
                $customer->save();
                return json_encode(array('result' => true, 'customer_id' => $customer->id, 'mobile_status' => true, 'message' => 'Password reset successfuly.'));
            } else {
                return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'Customer not found'));
            }
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'OTP does not match'));
        }
    }

    public function generateOtp() {

        $customer = Customer::where('phone_number1', '=', Input::get('username'))->first();
        if ($customer) {
            return json_encode(array('result' => true, 'message' => 'Customer found'));
        } else {
            return json_encode(array('result' => true, 'message' => 'Customer not found'));
        }
    }

    public function verifyOtp() {

        if (Input::get('otp') == '123456' || Input::get('otp') == 123456) {
            $customer = Customer::where('phone_number1', '=', Input::get('username'))->first();
            if ($customer) {
                return json_encode(array('result' => true, 'customer_id' => $customer->id, 'mobile_status' => true, 'message' => 'OTP Verifed'));
            } else {
                return json_encode(array('result' => true, 'mobile_status' => false, 'message' => 'Customer not found'));
            }
        } else {
            return json_encode(array('result' => false, 'mobile_status' => false, 'message' => 'OTP does not match'));
        }
    }

    public function trackOrder($id) {

        $order_details = Order::find($id);
        return json_encode($order_details->order_status);
    }

    public function trackInquiry($id) {

        $inquiry_details = Inquiry::find($id);
        return json_encode($inquiry_details->inquiry_status);
    }

    public function customerOrders($id) {

        $order_details = Order::where('customer_id', '=', $id)->with('all_order_products', 'customer', 'delivery_location', 'order_cancelled')->orderBy('created_at', 'desc')->get();
        return json_encode($order_details);
    }

    public function customerInquiry($id) {

        $inquiry_details = Inquiry::where('customer_id', '=', $id)->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')->orderBy('created_at', 'desc')->get();
        return json_encode($inquiry_details);
    }

    public function customerInfo($id) {

        $customer_details = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
        return json_encode($customer_details);
    }

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

    public function addCustomer() {

        $customer = new Customer();
        $customer->owner_name = Input::get('customer_name');
        $customer->contact_person = Input::get('contact_person');
        $customer->address1 = (Input::get('address1')) ? Input::get('address1') : '';
        $customer->phone_number1 = Input::get('mobile');
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
        $customer->delivery_location_id = (Input::has('delivery_location')) ? Input::get('delivery_location') : '';
        if ($customer->save())
            return json_encode(array('result' => true, 'customer_id' => $customer->id, 'message' => 'Customer added successfully'));
        else
            return json_encode(array('result' => false, 'message' => 'Some error occured. Please try again'));
    }

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

    // All Functions added by user 157 for android request //
    public function appsync1() {

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

        $inquiry_response = [];
        $customer_list = [];

        foreach ($inquiries as $key => $value) {

            if ($value->serverId > 0) {
                $add_inquiry = Inquiry::find($value->serverId);
                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expDelDate);
                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                $datetime = new DateTime($date);
                if ($value->vatPerc == "" || empty($value->vatPerc))
                    $add_inquiry->vat_percentage = 0;
                else
                    $add_inquiry->vat_percentage = $value->vatPerc;

                if (($value->otherLocation == "") || empty($value->otherLocation)) {
                    $add_inquiry->delivery_location_id = $value->delLocId;
                    $add_inquiry->location_difference = $value->delLocDiff;
                } else {
                    $add_inquiry->delivery_location_id = 0;
                    $add_inquiry->other_location = $value->otherLocation;
                    $add_inquiry->location_difference = $value->otherLocationDifference;
                }
                $add_inquiry->customer_id = $value->custServId;
                $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                $add_inquiry->remarks = ($value->remark != '') ? $value->remark : '';
                $add_inquiry->inquiry_status = $value->inquiryStatus;
                $add_inquiry->save();

                $inquiry_products = array();
                $delete_old_inquiry_products = InquiryProducts::where('inquiry_id', '=', $value->serverId)->delete();
                foreach ($inquiryproduct as $product_data) {
                    if ($product_data->maxInqId == $value->id) {
                        $inquiry_products = [
                            'inquiry_id' => $value->serverId,
                            'product_category_id' => $product_data->inqProId,
                            'unit_id' => $product_data->unitId,
                            'quantity' => $product_data->qty,
                            'price' => $product_data->price,
                            'remarks' => '',
                        ];
                    }
                    $add_inquiry_products = InquiryProducts::create($inquiry_products);
                }
                $inquiry_response[$value->serverId] = Inquiry::find($value->serverId);
                $inquiry_response[$value->serverId]['products'] = InquiryProducts::where('inquiry_id', '=', $value->serverId)->get();
            } else {
                if ($value->custServId == 0 || $value->custServId == '0') {
                    $add_customers = new Customer();
                    $add_customers->owner_name = $value->custTallyname;
                    $add_customers->contact_person = $value->custContactPeron;
                    $add_customers->phone_number1 = $value->customerMobile;
                    $add_customers->credit_period = $value->custCredit;
                    $add_customers->customer_status = 'pending';
                    $add_customers->save();
                    $customer_list[$value->id] = $add_customers->id;
                }
                $date_string = preg_replace('~\x{00a0}~u', ' ', $value->expDelDate);
                $date = date("Y/m/d", strtotime(str_replace('-', '/', $date_string)));
                $datetime = new DateTime($date);
                $add_inquiry = new Inquiry();
                $add_inquiry->customer_id = (!empty($value->custServId)) ? $value->custServId : $customer_list[$value->id];
//                $add_inquiry->customer_id = $value->custServId;
                $add_inquiry->created_by = 1;
                if (($value->otherLocation == "") || empty($value->otherLocation)) {
                    $add_inquiry->delivery_location_id = $value->delLocId;
                    $add_inquiry->location_difference = $value->delLocDiff;
                } else {
                    $add_inquiry->delivery_location_id = 0;
                    $add_inquiry->other_location = $value->otherLocation;
                    $add_inquiry->location_difference = $value->otherLocationDifference;
                }
                if ($value->vatPerc == "" || empty($value->vatPerc))
                    $add_inquiry->vat_percentage = 0;
                else
                    $add_inquiry->vat_percentage = $value->vatPerc;
                $add_inquiry->expected_delivery_date = $datetime->format('Y-m-d');
                $add_inquiry->remarks = ($value->remark != '') ? $value->remark : '';
                $add_inquiry->inquiry_status = "Pending";
                $add_inquiry->save();
                $inquiry_id = $add_inquiry->id;
                $inquiry_products = array();
                foreach ($inquiryproduct as $product_data) {
                    if ($product_data->maxInqId == $value->id) {
                        $inquiry_products = [
                            'inquiry_id' => $inquiry_id,
                            'product_category_id' => $product_data->inqProId,
                            'unit_id' => $product_data->unitId,
                            'quantity' => $product_data->qty,
                            'price' => $product_data->price,
                            'remarks' => '',
                        ];
                    }
                    $add_inquiry_products = InquiryProducts::create($inquiry_products);
                }
                $inquiry_response[$value->id] = $inquiry_id;
            }
        }
        if (count($customer_list) > 0)
            $inquiry_response['customer_new'] = $customer_list;

        return json_encode($inquiry_response);
    }

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
        $syncdata = (json_decode(Input::get('sync_info'), true));
        foreach ($syncdata as $synckey => $syncvalue) {
            if ($synckey == 'inquiry' && !empty($inquiry_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $inquiry_date->updated_at->toDateTimeString()];
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
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $purchase_challan_date->updated_at->toDateTimeString()];
            if ($synckey == 'customer' && !empty($customer_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $customer_date->updated_at->toDateTimeString()];
            if ($synckey == 'user' && !empty($user_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $user_date->updated_at->toDateTimeString()];
            if ($synckey == 'product_cat' && !empty($product_category))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $product_category->updated_at->toDateTimeString()];
            if ($synckey == 'product_sub_cat' && !empty($product_subcategory_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $product_subcategory_date->updated_at->toDateTimeString()];
            if ($synckey == 'location' && !empty($location_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $location_date->updated_at->toDateTimeString()];
            if ($synckey == 'city' && !empty($city_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $city_date->updated_at->toDateTimeString()];
            if ($synckey == 'state' && !empty($state_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $state_date->updated_at->toDateTimeString()];
            if ($synckey == 'inventory' && !empty($inventory_date))
                $sync[$synckey] = ['app_updated_date' => $syncvalue, 'server_updated_date' => $inventory_date->updated_at->toDateTimeString()];
        }
        return json_encode($sync);
    }

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

        $allcounts = [];
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

    public function appinquiry() {

        $data = Input::all();
        if ((isset($data['inquiry_filter'])) && $data['inquiry_filter'] != '') {
            $inquiries = Inquiry::where('inquiry_status', '=', $data['inquiry_filter'])
                            ->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details')
                            ->orderBy('created_at', 'desc')->get();
        } else {

            $inquiries = Inquiry::with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')
//                            ->where('inquiry_status', 'pending')
                            ->orderBy('created_at', 'desc')->get();
        }
        return json_encode($inquiries);
    }

    public function apporders() {
        $data = Input::all();
        $q = Order::query();
        if (isset($data['order_filter']) && $data['order_filter'] != '') {
            $q->where('order_status', '=', $data['order_filter']);
        }
        $allorders = $q->with('all_order_products')->with('customer', 'delivery_location', 'order_cancelled')->orderBy('created_at', 'desc')->get();
        return json_encode($allorders);
    }

    public function appinventory() {

        $allinventory = Inventory::with('product_sub_category')->get();
        return json_encode($allinventory);
    }

    public function appdelivery_order() {

        $delivery_data = 0;
        $delivery_data = DeliveryOrder::orderBy('created_at', 'desc')
//                        ->where('order_status', 'pending')
                        ->with('delivery_product', 'customer')->get();

        $do_obj = new DeliveryOrderController();
        $delivery_data = $do_obj->checkpending_quantity($delivery_data);
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();

        $data = [];
        $data['delivery_details'] = $delivery_data;
        $data['delivery_location'] = $delivery_locations;
        return json_encode($data);
    }

    public function appalldelivery_challan() {
        $allorders = DeliveryChallan::with('customer', 'delivery_challan_products', 'delivery_order')
//                        ->where('challan_status', '=', 'pending')
                        ->orderBy('created_at', 'desc')->get();
        return json_encode($allorders);
    }

    public function appallunit() {
        $units = Units::orderBy('created_at', 'desc')->get();
        return json_encode($units);
    }

    public function appallcity() {
        $cities = City::with('states')->orderBy('created_at', 'desc')->get();
        return json_encode($cities);
    }

    public function appallstate() {
        $states = States::orderBy('created_at', 'desc')->get();
        return json_encode($states);
    }

    public function appallcustomers() {
        $customers = Customer::orderBy('tally_name', 'asc')->where('customer_status', '=', 'permanent')->get();
        return json_encode($customers);
    }

    public function appallproduct_category() {
        $product_cat = ProductCategory::orderBy('created_at', 'desc')->get();
        return json_encode($product_cat);
    }

    public function appallproduct_sub_category() {
        $product_type = ProductType::all();
        $units = Units::all();
        $product_sub_cat = "";
        $q = ProductSubCategory::query();
        $q->with('product_category');
        $product_sub_cat = $q->orderBy('created_at', 'desc')->get();
        return json_encode($product_sub_cat);
    }

    public function appallusers() {
        $users_data = User::where('role_id', '!=', 0)->with('user_role')->orderBy('created_at', 'desc')->get();
        return json_encode($users_data);
    }

    public function appallpending_customers() {
        $customers = Customer::orderBy('created_at', 'desc')->where('customer_status', '=', 'pending')->get();
        return json_encode($customers);
    }

    public function appallpending_delivery_order() {
        $delivery_data = DeliveryOrder::where('order_status', 'pending')->with('user', 'customer')->get();
        return json_encode($delivery_data);
    }

    public function appallpurchaseorders() {
        $q = PurchaseOrder::query();
        $purchase_orders = $q->orderBy('created_at', 'desc')->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')->get();
        return json_encode($purchase_orders);
    }

    public function appallpurchaseorder_advise() {
        $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');
        $purchase_advise = $q->orderBy('created_at', 'desc')->get();
        return json_encode($purchase_advise);
    }

    public function appallpending_purchase_advice() {
        $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');
        $q->where('advice_status', '=', 'in_process');
        $purchase_advise = $q->orderBy('created_at', 'desc')->get();
        return json_encode($purchase_advise);
    }

    public function appallpurchase_challan() {
        $purchase_challan = PurchaseChallan::with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details')
//                ->where('order_status', 'pending')
                        ->orderBy('created_at', 'desc')->get();
        return json_encode($purchase_challan);
    }

    public function appallpurchase_order_daybook() {
        $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details')
                        ->where('order_status', 'completed')->orderBy('created_at', 'desc')->get();
        return json_encode($purchase_daybook);
    }

    public function applocation() {
        $delivery_location = DeliveryLocation::where('status', '=', 'permanent')->with('city', 'states')->orderBy('created_at', 'desc')->get();
        return json_encode($delivery_location);
    }

    // All Functions added by user 157 for app ends here //
    public function applogin() {
        $data = Input::all();
        $username = $data['username'];
        $password = $data['password'];

        if (Auth::validate(['mobile_number' => $username, 'password' => $password])) {
            return json_encode(array(
                'result' => true,
                'message' => 'Login Successfully Done')
            );
        } else {
            return json_encode(array(
                'result' => false,
                'message' => 'Login Failed.')
            );
        }
    }

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

    /**
     * Show the application dashboard to the user.
     */
    public function index() {
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
            echo "<tr><td>" . $value->id . "</td><td>" . $value->product_type_id . "</td><td>" . $value->product_category_name . "</td><td>" . $value->price . "</td><td>" . $value->price_new . "</td></tr>";
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
        $DBUSER = "agstechn_vauser";
        $DBPASSWD = "vikasuser23210";
        $DATABASE = "agstechn_vaoas";
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

}
