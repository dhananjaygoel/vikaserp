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
                    'empty_truck_weight' => isset($value->empty_truck_weight)?$value->empty_truck_weight:'0',
                    'final_truck_weight' => isset($value->final_truck_weight)?$value->final_truck_weight:'0',
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
//                $delivery_challan_response[$value->id] = DeliveryChallan::find($value->server_id);
//                $delivery_challan_response[$value->id]['delivery_challan_products'] = AllOrderProducts::where('order_type', '=', 'delivery_challan')->where('order_id', '=', $value->server_id)->get();
            } else {
//                $delivery_challan_response[$value->id] = $delivery_challan_id;
            }
            $delivery_challan->save();
        }
        if (count($customer_list) > 0) {
            $delivery_challan_response['customer_new'] = $customer_list;
        }
//        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '' && Input::get('delivery_challan_sync_date') != NULL) {
//            $delivery_challan_response['delivery_challan_deleted'] = DeliveryChallan::withTrashed()->where('deleted_at', '>=', Input::get('delivery_challan_sync_date'))->select('id')->get();
//        }
//        $delivery_challan_date = DeliveryChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($delivery_challan_date))
//            $delivery_challan_response['latest_date'] = $delivery_challan_date->updated_at->toDateTimeString();
//        else
//            $delivery_challan_response['latest_date'] = "";

        $tables = ['delivery_challan', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);

        $real_sync_date = SyncTableInfo::where('table_name', 'delivery_challan')->select('sync_date')->first();


        if (Input::has('delivery_challan_sync_date') && Input::get('delivery_challan_sync_date') != '') {
            //         update sync table
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('delivery_challan_sync_date')) {
                    $delivery_challan_response['delivery_challan_server_added'] = [];
                    $delivery_challan_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($delivery_challan_response);
                }
            }
            /* end of new code */
            $last_sync_date = Input::get('delivery_challan_sync_date');
            $delivery_challan_server = DeliveryChallan::with('delivery_challan_products', 'challan_loaded_by.dc_loaded_by', 'challan_labours.dc_labour', 'delivery_order')->where('challan_status', 'pending')->get();
            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $delivery_challan_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $delivery_challan_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $delivery_challan_server = DeliveryChallan::with('delivery_challan_products', 'challan_loaded_by.dc_loaded_by', 'challan_labours.dc_labour', 'delivery_order')->where('challan_status', 'pending')->get();
            $delivery_challan_response['delivery_challan_server_added'] = ($delivery_challan_server && count($delivery_challan_server) > 0) ? $delivery_challan_server : array();
        }
        $delivery_challan_response['latest_date'] = $real_sync_date->sync_date;
        return json_encode($delivery_challan_response);
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
                $delivery_order->empty_truck_weight = isset($value->empty_truck_weight)?$value->empty_truck_weight:'0';
                $delivery_order->final_truck_weight = isset($value->final_truck_weight)?$value->final_truck_weight:'0';
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
//                $delivery_order_response[$value->id] = $delivery_order_id;
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
                 $delivery_order->empty_truck_weight = isset($value->empty_truck_weight)?$value->empty_truck_weight:'0';
                $delivery_order->final_truck_weight = isset($value->final_truck_weight)?$value->final_truck_weight:'0';
                $delivery_order->save();
//                $delivery_order_response[$value->server_id] = DeliveryOrder::find($delivery_order->id);
//                $delivery_order_response[$value->server_id]['delivery_product'] = AllOrderProducts::where('order_type', '=', 'delivery_order')->where('order_id', '=', $delivery_order->id)->get();
            }
        }
        if (count($customer_list) > 0) {
            $delivery_order_response['customer_new'] = $customer_list;
        }
//        if (Input::has('delivery_order_sync_date') && Input::get('delivery_order_sync_date') != '' && Input::get('delivery_order_sync_date') != NULL) {
//            $delivery_order_response['delivery_order_deleted'] = DeliveryOrder::withTrashed()->where('deleted_at', '>=', Input::get('delivery_order_sync_date'))->select('id')->get();
//        }
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
//                $order_response[$value->id] = $order_id;
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
//                $order_response[$value->server_id] = Order::find($value->server_id);
//                $order_response[$value->server_id]['all_order_products'] = AllOrderProducts::where('order_type', '=', 'order')->where('order_id', '=', $order->id)->get();
            }
        }
        if (count($customer_list) > 0) {
            $order_response['customer_new'] = $customer_list;
        }
//        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '' && Input::get('order_sync_date') != NULL) {
//            $order_response['order_deleted'] = Order::withTrashed()->where('deleted_at', '>=', Input::get('order_sync_date'))->select('id')->get();
//        }
//        $order_date = Order::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($order_date))
//            $order_response['latest_date'] = $order_date->updated_at->toDateTimeString();
//        else
//            $order_response['latest_date'] = "";

        $tables = ['orders', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);

        $real_sync_date = SyncTableInfo::where('table_name', 'orders')->select('sync_date')->first();

        if (Input::has('order_sync_date') && Input::get('order_sync_date') != '') {
            //update sync table 
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('order_sync_date')) {
                    $order_response['order_server_added'] = [];
                    $order_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($order_response);
                }
            }
            /* end of new code */


            $last_sync_date = Input::get('order_sync_date');
            $order_added_server = Order::with('all_order_products','delivery_orders')
                    ->where('order_status', 'pending')
                    ->get();
            $order_added_server = $this->checkpending_quantity($order_added_server);
            $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();

            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
            $order_added_server = Order::with('all_order_products','delivery_orders')
                    ->where('order_status', 'pending')
                    ->get();
            
            $order_added_server = $this->checkpending_quantity($order_added_server);

            $order_response['order_server_added'] = ($order_added_server && count($order_added_server) > 0) ? $order_added_server : array();
        }
        $order_response['latest_date'] = $real_sync_date->sync_date;
        return json_encode($order_response);
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
                    $add_inquiry->is_approved = 'yes';
                    $add_inquiry->save();
//                    $inquiry_response[$value->server_id] = Inquiry::find($value->server_id);
//                    $inquiry_response[$value->server_id]['inquiry_products'] = InquiryProducts::where('inquiry_id', '=', $value->server_id)->get();
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
//                    $inquiry_response[$value->id] = $inquiry_id;
                }
            }
        }


        if (count($customer_list) > 0) {
            $inquiry_response['customer_new'] = $customer_list;
        }


        $tables = ['inquiry', 'inquiry_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);

        $real_sync_date = SyncTableInfo::where('table_name', 'inquiry')->select('sync_date')->first();


        if (Input::has('inquiry_sync_date') && Input::get('inquiry_sync_date') != '' && Input::get('inquiry_sync_date') != NULL) {
            //         update sync table         

            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('inquiry_sync_date')) {
                    $inquiry_response['inquiry_server_added'] = [];
                    $inquiry_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($inquiry_response);
                }
            }
            /* end of new code */



            $last_sync_date = Input::get('inquiry_sync_date');
            $inquiry_added_server = Inquiry::where('inquiry_status', 'pending')->with('inquiry_products')->get();
            $inquiry_response['inquiry_server_added'] = ($inquiry_added_server && count($inquiry_added_server) > 0) ? $inquiry_added_server : array();

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
        $inquiry_response['latest_date'] = $real_sync_date->sync_date;

        return json_encode($inquiry_response);
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
//                $purchase_order_response[$value->id] = PurchaseOrder::find($value->server_id);
//                $purchase_order_response[$value->id]['purchase_products'] = PurchaseProducts::where('order_type', '=', 'purchase_order')->where('purchase_order_id', '=', $value->server_id)->get();
            } else {
//                $purchase_order_response[$value->id] = $purchase_order_id;
            }
            $purchase_order->save();
        }
        if (count($customer_list) > 0) {
            $purchase_order_response['customer_new'] = $customer_list;
        }
//        if (Input::has('purchase_order_sync_date') && Input::get('purchase_order_sync_date') != '' && Input::get('purchase_order_sync_date') != NULL) {
//            $purchase_order_response['purchase_order_deleted'] = PurchaseOrder::withTrashed()->where('deleted_at', '>=', Input::get('purchase_order_sync_date'))->select('id')->get();
//        }
//        $purchase_order_date = PurchaseOrder::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($purchase_order_date))
//            $purchase_order_response['latest_date'] = $purchase_order_date->updated_at->toDateTimeString();
//        else
//            $purchase_order_response['latest_date'] = "";

        $tables = ['purchase_order', 'all_purchase_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);

        $real_sync_date = SyncTableInfo::where('table_name', 'purchase_order')->select('sync_date')->first();


        if (Input::has('purchase_order_sync_date') && Input::get('purchase_order_sync_date') != '') {

//         update sync table 
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('purchase_order_sync_date')) {
                    $purchase_order_response['purchase_order_server_added'] = [];
                    $purchase_order_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($purchase_order_response);
                }
            }
            /* end of new code */

            $last_sync_date = Input::get('purchase_order_sync_date');
            $purchase_order_server = PurchaseOrder::where('order_status', 'pending')->with('purchase_products','purchase_product_has_from')->get();
            $purchase_order_server = $this->quantity_calculation($purchase_order_server);
            $purchase_order_response['purchase_order_server_added'] = ($purchase_order_server && count($purchase_order_server) > 0) ? $purchase_order_server : array();


            /* Send Updated customers */
            $customer_updated_server = Customer::where('updated_at', '>', $last_sync_date)->whereRaw('updated_at > created_at')->get();
            $purchase_order_response['customer_server_updated'] = ($customer_updated_server && count($customer_updated_server) > 0) ? $customer_updated_server : array();
            /* Send New customers */
            $customer_added_server = Customer::where('created_at', '>', $last_sync_date)->get();
            $purchase_order_response['customer_server_added'] = ($customer_added_server && count($customer_added_server) > 0) ? $customer_added_server : array();
        } else {
//            $purchase_order_server = PurchaseOrder::with('purchase_products')->get();
            $purchase_order_server = PurchaseOrder::with('purchase_products','purchase_product_has_from')
                    ->where('order_status', 'pending')
                    ->get();
            $purchase_order_server = $this->quantity_calculation($purchase_order_server);
            $purchase_order_response['purchase_order_server_added'] = ($purchase_order_server && count($purchase_order_server) > 0) ? $purchase_order_server : array();
        }
        $purchase_order_response['latest_date'] = $real_sync_date->sync_date;
        return json_encode($purchase_order_response);
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
//                $purchase_advice_response[$value->id] = PurchaseAdvise::find($value->server_id);
//                $purchase_advice_response[$value->id]['purchase_products'] = PurchaseProducts::where('order_type', '=', 'purchase_advice')->where('purchase_order_id', '=', $value->server_id)->get();
            } else {
//                $purchase_advice_response[$value->id] = $purchase_advise_id;
            }
            $purchase_advice->save();
        }
        if (count($customer_list) > 0) {
            $purchase_advice_response['customer_new'] = $customer_list;
        }
//        if (Input::has('purchase_advice_sync_date') && Input::get('purchase_advice_sync_date') != '' && Input::get('purchase_advice_sync_date') != NULL) {
//            $purchase_advice_response['purchase_advise_deleted'] = PurchaseAdvise::withTrashed()->where('deleted_at', '>=', Input::get('purchase_advice_sync_date'))->select('id')->get();
//        }
//        $purchase_advice_date = PurchaseAdvise::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($purchase_advice_date))
//            $purchase_advice_response['latest_date'] = $purchase_advice_date->updated_at->toDateTimeString();
//        else
//            $purchase_advice_response['latest_date'] = "";

        $tables = ['purchase_advice', 'all_purchase_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);

        $real_sync_date = SyncTableInfo::where('table_name', 'purchase_advice')->select('sync_date')->first();

        if (Input::has('purchase_advice_sync_date') && Input::get('purchase_advice_sync_date') != '') {

            //         update sync table  
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('purchase_advice_sync_date')) {
                    $purchase_advice_response['purchase_advice_server_added'] = [];
                    $purchase_advice_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($purchase_advice_response);
                }
            }
            /* end of new code */
            $last_sync_date = Input::get('purchase_advice_sync_date');
            $purchase_advice_server = PurchaseAdvise::where('advice_status', 'in_process')->with('purchase_products')->get();
            $purchase_advice_response['purchase_advice_server_added'] = ($purchase_advice_server && count($purchase_advice_server) > 0) ? $purchase_advice_server : array();

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
        $purchase_advice_response['latest_date'] = $real_sync_date->sync_date;
        return json_encode($purchase_advice_response);
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
//                $purchase_challan_response[$value->id] = PurchaseChallan::find($value->server_id);
//                $purchase_challan_response[$value->id]['purchase_products'] = PurchaseProducts::where('order_type', '=', 'purchase_challan')->where('purchase_order_id', '=', $value->server_id)->get();
            } else {
//                $purchase_challan_response[$value->id] = $purchase_challan_id;
            }
            $purchase_challan->save();
        }
        if (count($customer_list) > 0) {
            $purchase_challan_response['customer_new'] = $customer_list;
        }
//        if (Input::has('purchase_challan_sync_date') && Input::get('purchase_challan_sync_date') != '' && Input::get('purchase_challan_sync_date') != NULL) {
//            $purchase_challan_response['purchase_challan_deleted'] = PurchaseChallan::withTrashed()->where('deleted_at', '>=', Input::get('purchase_challan_sync_date'))->select('id')->get();
//        }
//        $purchase_challan_date = PurchaseChallan::select('updated_at')->orderby('updated_at', 'DESC')->first();
//        if (!empty($purchase_challan_date))
//            $purchase_challan_response['latest_date'] = $purchase_challan_date->updated_at->toDateTimeString();
//        else
//            $purchase_challan_response['latest_date'] = "";

        $tables = ['purchase_challan', 'all_purchase_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);

        $real_sync_date = SyncTableInfo::where('table_name', 'purchase_challan')->select('sync_date')->first();


        if (Input::has('purchase_challan_sync_date') && Input::get('purchase_challan_sync_date') != '') {

            //         update sync table 
            if ($real_sync_date->sync_date <> "0000-00-00 00:00:00") {
                if ($real_sync_date->sync_date <= Input::get('purchase_challan_sync_date')) {
                    $purchase_challan_response['purchase_challan_server_added'] = [];
                    $purchase_challan_response['latest_date'] = $real_sync_date->sync_date;
                    return json_encode($purchase_challan_response);
                }
            }
            /* end of new code */

            $last_sync_date = Input::get('purchase_challan_sync_date');
            $purchase_challan_server = PurchaseChallan::where('order_status', 'pending')->with('all_purchase_products')->get();
            $purchase_challan_response['purchase_challan_server_added'] = ($purchase_challan_server && count($purchase_challan_server) > 0) ? $purchase_challan_server : array();
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
        $purchase_challan_response['latest_date'] = $real_sync_date->sync_date;
        return json_encode($purchase_challan_response);
    }
    
    
    
    
    
    
    /*
     * To calculate pending qty for order 
     * 
     */
    
    function checkpending_quantity($allorders) {

        foreach ($allorders as $key => $order) {
            $order_quantity = 0;
            $delivery_order_quantity = 0;

            /* new */
            $delivery_order_products = NULL;
            if (isset($order['delivery_orders']) && count($order['delivery_orders'])) {
                $delivery_order_products = AllOrderProducts::with('product_sub_category')->where('from', '=', $order->id)->where('order_type', '=', 'delivery_order')->get();
            }
            /* new */
            /* old */
            // $delievry_order_details = DeliveryOrder::where('order_id', '=', $order->id)->first();
            // if (!empty($delievry_order_details)) {
            //     $delivery_order_products = AllOrderProducts::where('from', '=', $delievry_order_details->order_id)->where('order_type', '=', 'delivery_order')->get();
            // } else {
            //     $delivery_order_products = NULL;
            // }
            /* old */

            if (count($delivery_order_products) > 0) {
                foreach ($delivery_order_products as $dopk => $dopv) {
                    //new 
                    $product_size = $dopv['product_sub_category'];
                    //new
                    /* old */
                    //$product_size = ProductSubCategory::find($dopv->product_category_id);

                    /* old */
//                   $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity;

                    if ($dopv->unit_id == 1) {
                        $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity;
                    } elseif ($dopv->unit_id == 2) {
                        $delivery_order_quantity = $delivery_order_quantity + $dopv->quantity * $product_size->weight;
                    } elseif ($dopv->unit_id == 3) {
                        if ($product_size->standard_length) {
                            $delivery_order_quantity = $delivery_order_quantity + ($dopv->quantity / $product_size->standard_length ) * $product_size->weight;
                        } else {
                            $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                        }
                    }
                }
            }
            if (count($order['all_order_products']) > 0) {
                foreach ($order['all_order_products'] as $opk => $opv) {
                    /* new */
                    if (isset($opv['product_sub_category'])) {
                        $product_size = $opv['product_sub_category'];
                    } else {
                        $product_size = ProductSubCategory::find($opv->product_category_id);
                    }
                    /* new */
                    /* old */
                    //$product_size = ProductSubCategory::find($opv->product_category_id);
//                    $order_quantity = $order_quantity + $opv->quantity;

                    /* old */
                    if ($opv->unit_id == 1) {
                        $order_quantity = $order_quantity + $opv->quantity;
                    } elseif ($opv->unit_id == 2) {
                        $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                    } elseif ($opv->unit_id == 3) {
                        if ($product_size->standard_length) {
                            $order_quantity = $order_quantity + (($opv->quantity / $product_size->standard_length ) * $product_size->weight);
                        } else {
                            $order_quantity = $order_quantity + ($opv->quantity * $product_size->weight);
                        }
                    }
                }
            }
            $allorders[$key]['pending_quantity'] = ($delivery_order_quantity >= $order_quantity) ? 0 : ($order_quantity - $delivery_order_quantity);
            $allorders[$key]['total_quantity'] = $order_quantity;
        }
        return $allorders;
    }
    
    
    
     /*
     * First get all orders
     * 1 if delevery order is generated from order then only calculate
     * pending order from delivery order
     * else take order details in pending order
     * 2 if delivery order is generated then take those products only
     * which has there in order rest skip
     */

    function quantity_calculation($purchase_orders) {

        foreach ($purchase_orders as $key => $order) {

            $purchase_order_quantity = 0;
            $purchase_order_advise_quantity = 0;
            //$purchase_order_advise_products = PurchaseProducts::where('from', '=', $order->id)->get();
            $purchase_order_advise_products = $order['purchase_product_has_from'];
            if (count($purchase_order_advise_products) > 0) {
                foreach ($purchase_order_advise_products as $poapk => $poapv) {
                    $product_size = $poapv['product_sub_category'];
                    //$product_size = ProductSubCategory::find($poapv->product_category_id);
                    if ($poapv->unit_id == 1) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity;
                    }
                    if ($poapv->unit_id == 2) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity * $product_size->weight;
                    }
                    if ($poapv->unit_id == 3) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + ($poapv->quantity / $product_size->standard_length ) * $product_size->weight;
                    }
                }
            }

            if (count($order['purchase_products']) > 0) {
                foreach ($order['purchase_products'] as $popk => $popv) {
                    $product_size = $popv['product_sub_category'];
                    //$product_size = ProductSubCategory::find($popv->product_category_id);
                    if ($popv->unit_id == 1) {
                        $purchase_order_quantity = $purchase_order_quantity + $popv->quantity;
                    }
                    if ($popv->unit_id == 2) {
                        $purchase_order_quantity = $purchase_order_quantity + ($popv->quantity * $product_size->weight);
                    }
                    if ($popv->unit_id == 3) {
                        $purchase_order_quantity = $purchase_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                    }
                }
            }

            if ($purchase_order_advise_quantity >= $purchase_order_quantity) {
                $purchase_orders[$key]['pending_quantity'] = 0;
            } else {
                $purchase_orders[$key]['pending_quantity'] = ($purchase_order_quantity - $purchase_order_advise_quantity);
            }
            
            if( $purchase_orders[$key]['pending_quantity'] == 0){                
               $purchase_orders[$key]['order_status'] = 'completed';   
               PurchaseOrder::where('id', $purchase_orders[$key]['id'])->update(['order_status' => 'completed']);
            }
            $purchase_orders[$key]['total_quantity'] = $purchase_order_quantity;
        }
        return $purchase_orders;
    }


}
