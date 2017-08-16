<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

class APIsController extends Controller {

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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
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
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
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
//        $delivery_order_date = DeliveryOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($delivery_order_date))
//            $delivery_order_response['latest_date'] = $delivery_order_date->updated_at->toDateTimeString();
//        else
//            $delivery_order_response['latest_date'] = "";


        $tables = ['delivery_order', 'all_order_products'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            
            $real_sync_date = SyncTableInfo::where('table_name', 'delivery_order')->select('sync_date')->first();
        
        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '') {
            //         update sync table         
            
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('delivery_order_sync_date')) {
                    $delivery_order_response['delivery_order_server_added'] = [];
                    $delivery_order_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($delivery_order_response);
                }
            }
            /* end of new code */


            $last_sync_date = Input::get('delivery_order_sync_date');
            $delivery_order_server = DeliveryOrder::where('order_status', 'pending')->with('delivery_product')->get();
            $delivery_order_response['delivery_order_server_added'] = ($delivery_order_server && count($delivery_order_server) > 0) ? $delivery_order_server : array();


            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $delivery_order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $delivery_order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
            $delivery_order_response['latest_date'] = $real_sync_date->sync_date;
        } else {
//            $delivery_order_server = DeliveryOrder::with('delivery_product')->get();
            $delivery_order_server = DeliveryOrder::with('delivery_product')
                    ->where('order_status', 'pending')
                    ->get();
            $delivery_order_response['delivery_order_server_added'] = ($delivery_order_server && count($delivery_order_server) > 0) ? $delivery_order_server : array();
            $delivery_order_response['latest_date'] = $real_sync_date->sync_date;
        }



        return json_encode($delivery_order_response);
    }

}
