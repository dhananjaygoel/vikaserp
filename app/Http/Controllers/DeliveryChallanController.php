<?php

namespace App\Http\Controllers;

use App\Exports\DCExport;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Data\IPPTaxCode;
use QuickBooksOnline\API\Facades\Invoice;
use View;
use App\DeliveryChallan;
use App\Order;
use App\AllOrderProducts;
use App\DeliveryOrder;
use Input;
use Redirect;
use App\User;
use Auth;
use App;
use URL;
use Hash;
use Config;
use Carbon;
use App\Units;
use App\DeliveryLocation;
use App\Customer;
use App\Hsn;
use App\ProductSubCategory;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use App\Repositories\DropboxStorageRepository;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Storage;
use DB;
use QuickBooksOnline\API\Data\IPPSalesItemLineDetail;
use QuickBooksOnline\API\Data\IPPTaxLineDetail;
use Twilio\Rest\Client;

class DeliveryChallanController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        define('TWILIO_SID', Config::get('smsdata.twilio_sid'));
        define('TWILIO_TOKEN', Config::get('smsdata.twilio_token'));
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
//    public function index() {
//
//        if (Auth::user()->role_id == 5) {
//            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
//        }
//        if (Auth::user()->role_id == 6) {
//            return Redirect::to('due-payment');
//        }
//
//
//
//        $data = Input::all();
//        $search_dates = [];
//        $allorders = 0;
//        $session_sort_type_order = Session::get('order-sort-type');
//        if (isset($data['status_filter']))
//            $qstring_sort_type_order = $data['status_filter'];
//        elseif (isset($data['delivery_order_status']))
//            $qstring_sort_type_order = $data['delivery_order_status'];
//
//        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
//            $qstring_sort_type_order = $qstring_sort_type_order;
//        } else {
//            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
//                $qstring_sort_type_order = $session_sort_type_order;
//            } else {
//                $qstring_sort_type_order = "";
//            }
//        }
//
//        if ((isset($qstring_sort_type_order)) && ($qstring_sort_type_order != '')) {
//            if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
//                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
//                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
//                if ($date1 == $date2) {
//                    $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
//                                    ->where('updated_at', 'like', $date1 . '%')
//                                    ->with('customer', 'delivery_challan_products', 'delivery_order')
//                                    ->orderBy('updated_at', 'desc')->Paginate(20);
//                } else {
//                    $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
//                                    ->where('updated_at', '>=', $date1)
//                                    ->where('updated_at', '<=', $date2 . ' 23:59:59')
//                                    ->with('customer', 'delivery_challan_products', 'delivery_order')
//                                    ->orderBy('updated_at', 'desc')->Paginate(20);
//                }
//                $search_dates = [
//                    'export_from_date' => $data["export_from_date"],
//                    'export_to_date' => $data["export_to_date"]
//                ];
//            } else {
//                $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)->with('customer', 'delivery_challan_products', 'delivery_order')
//                                ->orderBy('updated_at', 'desc')->Paginate(20);
//            }
//        } else {
//            if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
//                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
//                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
//                if ($date1 == $date2) {
//                    $allorders = DeliveryChallan::where('challan_status', '=', 'pending')
//                                    ->where('updated_at', 'like', $date1 . '%')
//                                    ->with('customer', 'delivery_challan_products', 'delivery_order')
//                                    ->orderBy('updated_at', 'desc')->Paginate(20);
//                } else {
//                    $allorders = DeliveryChallan::where('challan_status', '=', 'pending')
//                                    ->where('updated_at', '>=', $date1)
//                                    ->where('updated_at', '<=', $date2)
//                                    ->with('customer', 'delivery_challan_products', 'delivery_order')
//                                    ->orderBy('updated_at', 'desc')->Paginate(20);
//                }
//                $search_dates = [
//                    'export_from_date' => $data["export_from_date"],
//                    'export_to_date' => $data["export_to_date"]
//                ];
//            } else {
//                $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'delivery_challan_products', 'delivery_order')
//                                ->orderBy('updated_at', 'desc')->Paginate(20);
//            }
//        }
////
////        if (count((array)$allorders) > 0) {
////            foreach ($allorders as $key => $order) {
////                $order_quantity = 0;
////                $order_quantity_pending =0;
////
////
////                $all_dc_details = DeliveryChallan::with('delivery_order','order_details','delivery_challan_products')->find($order->id);
////
//////                $all_do_details = DeliveryOrder::with('delivery_product')->find($all_dc_details[0]['delivery_order']->id);
////
////
////            foreach($all_dc_details['delivery_challan_products'] as $delivery_challan_products){
////
////                  $order_quantity = $order_quantity + $delivery_challan_products->present_shipping;
////                  $all_do_details = DeliveryOrder::with('delivery_product')->find($all_dc_details['delivery_order']->id);
////
////                  foreach($all_do_details['delivery_product'] as $delivery_product)
////                  {
////                      $delivery_product->product_category_id;
////                      $delivery_challan_products->product_category_id;
////
////                      if($delivery_product->product_category_id == $delivery_challan_products->product_category_id)
////                      {
////                          echo "<pre>";
////                          print_r($delivery_product->quantity);
////                          echo "</pre>";
////
////
////
////                      }
////
////
////                  }
////
////            }
////
////
////                echo "<pre>";
////                print_r($delivery_challan_products->product_category_id);
////                echo "</pre>";
////                exit;
////
////
////            }
////        }
////
//
//
//
//
//        if (count((array)$allorders) > 0) {
//            foreach ($allorders as $key => $order) {
//                $order_quantity = 0;
//                $order_quantity_pending = 0;
//                $product_for_order_do_pending = 0;
//                $previous_dc_quantity = 0;
//                $previous_dc_quantity_parent = 0;
//
//
//                if (count((array)$order['delivery_challan_products']) > 0) {
//                    $order_quantity = $order['delivery_challan_products']->sum('present_shipping');
//                }
//                $allorders[$key]['total_quantity'] = $order_quantity;
//                foreach ($order['delivery_challan_products'] as $delivery_challan_products) {
//
//
//
//                    $product_for_order = AllOrderProducts::where('order_type', '=', 'order')
//                            ->where('order_id', '=', $order->order_id)
//                            ->where('product_category_id', '=', $delivery_challan_products->product_category_id)
//                            ->get();
//
//                    $product_for_deliveryorder = DeliveryOrder::where('order_id', '=', $order->order_id)->get();
//
//                    if (count((array)$product_for_deliveryorder) > 0) {
//                        foreach ($product_for_deliveryorder as $deliveryorder) {
//                            $product_for_order_do = AllOrderProducts::where('order_type', '=', 'delivery_order')
//                                    ->where('order_id', '=', $deliveryorder->id)
//                                    ->where('product_category_id', '=', $delivery_challan_products->product_category_id)
//                                    ->get();
//
//
//                            $dc_temp = DeliveryChallan::find($delivery_challan_products->order_id);
//                            $dc = DeliveryChallan::where('order_id', '=', $dc_temp->order_id)
//                                    ->get();
//                            foreach ($dc as $dc1) {
//                                $prod = AllOrderProducts::where('order_id', '=', $dc1->id)
//                                        ->where('product_category_id', '=', $delivery_challan_products->product_category_id)
//                                        ->where('order_id', '<>', $order->id)
//                                        ->get();
//
//                                foreach ($prod as $t) {
//
//                                    $previous_dc_quantity = $t->present_shipping;
//                                    $previous_dc_quantity_parent = $t->parent;
//                                }
//                            }
//
//                            $product_for_order_do_pending = $product_for_order_do->sum('quantity');
//                        }
//                    }
//
////                   echo "<pre>";
////                       print_r($product_for_order_do_pending ."--" .$previous_dc_quantity."--".$product_for_order[0]->quantity."--".$previous_dc_quantity_parent."--".$product_for_order_do[0]->id);
////                       echo "</pre>";
////
//
//                    foreach ($product_for_order as $product_order_pending) {
//
//                        if ($previous_dc_quantity > 0) {
//                            if (isset($product_for_order_do[0])) {
//                                if ($previous_dc_quantity_parent == $product_for_order_do[0]->id) {
//                                    $order_quantity_pending = $order_quantity_pending + $product_order_pending->quantity;
//                                }
//                            } else {
//                                $order_quantity_pending = $product_order_pending->quantity - $previous_dc_quantity + $order_quantity - $product_for_order_do_pending;
//                            }
//                        } else {
//                            $order_quantity_pending = $order_quantity_pending + $product_order_pending->quantity;
//                        }
//                    }
//                }
//
//                $allorders[$key]['total_quantity_pending'] = $order_quantity_pending - $order_quantity;
//
////                 if($order_quantity_pending > $order_quantity){
////
////                    $allorders[$key]['total_quantity_pending'] = $order_quantity_pending -$order_quantity;
////                }else {
////                         $allorders[$key]['total_quantity_pending'] = '0';
////                    }
//            }
//        }
//
//        $allorders->setPath('delivery_challan');
//        return view('delivery_challan', compact('allorders', 'search_dates'));
//    }


    public function index(Request $request) {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        // if (Auth::user()->role_id == 5) {
        //     return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        // }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        $data = Input::all();
        // print_r($data);
        // exit;
        $search_dates = [];
        $allorders = 0;
        $session_sort_type_order = Session::get('order-sort-type');
        if (isset($data['status_filter']))
            $qstring_sort_type_order = $data['status_filter'];
        elseif (isset($data['delivery_order_status']))
            $qstring_sort_type_order = $data['delivery_order_status'];

        if (isset($qstring_sort_type_order) && ($qstring_sort_type_order != "")) {
            $qstring_sort_type_order = $qstring_sort_type_order;
        } else {
            if (isset($session_sort_type_order) && ($session_sort_type_order != "")) {
                $qstring_sort_type_order = $session_sort_type_order;
            } else {
                $qstring_sort_type_order = "";
            }
        }

        if ((isset($qstring_sort_type_order)) && ($qstring_sort_type_order != '')) {
            if($qstring_sort_type_order == 'completed') {
                if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
                    $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                    $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                    if ($date1 == $date2) {
                        $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
                                        ->where('updated_at', 'like', $date1 . '%')
                                       //  ->where('vat_percentage', '>', '0.00')
                                        ->where('serial_number', 'like', '%P%')
                                        ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                        ->orderBy('updated_at', 'desc')->Paginate(20);
                    } else {
                        $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
                                      //  ->where('vat_percentage', '>', '0.00')
                                        ->where('serial_number', 'like', '%P%')
                                        ->where('updated_at', '>=', $date1)
                                        ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                        ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                        ->orderBy('updated_at', 'desc')->Paginate(20);
                    }
                    $search_dates = [
                        'export_from_date' => $data["export_from_date"],
                        'export_to_date' => $data["export_to_date"]
                    ];
                } else {
                    $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
                   // ->where('vat_percentage', '>', '0.00')
                    ->where('serial_number', 'like', '%P%')
                    ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                    ->orderBy('updated_at', 'desc')->Paginate(20);
                }
            }
            if($qstring_sort_type_order == 'pending') {
                if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
                    $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                    $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                    if ($date1 == $date2) {
                        $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
                                        ->where('updated_at', 'like', $date1 . '%')
                                        ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                        ->orderBy('updated_at', 'desc')->Paginate(20);
                    } else {
                        $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
                                        ->where('updated_at', '>=', $date1)
                                        ->where('updated_at', '<=', $date2 . ' 23:59:59')
                                        ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                        ->orderBy('updated_at', 'desc')->Paginate(20);
                    }
                    $search_dates = [
                        'export_from_date' => $data["export_from_date"],
                        'export_to_date' => $data["export_to_date"]
                    ];
                } else {
                    $allorders = DeliveryChallan::where('challan_status', '=', $qstring_sort_type_order)
                    ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                    ->orderBy('updated_at', 'desc')->Paginate(20);
                }
            }

        } else {
            if (isset($data["export_from_date"]) && isset($data["export_to_date"])) {
                $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
                $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
                if ($date1 == $date2) {
                    $allorders = DeliveryChallan::where('challan_status', '=', 'pending')
                                    ->where('updated_at', 'like', $date1 . '%')
                                    ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                    ->orderBy('updated_at', 'desc')->Paginate(20);
                } else {
                    $allorders = DeliveryChallan::where('challan_status', '=', 'pending')
                                    ->where('updated_at', '>=', $date1)
                                    ->where('updated_at', '<=', $date2)
                                    ->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                    ->orderBy('updated_at', 'desc')->Paginate(20);
                }
                $search_dates = [
                    'export_from_date' => $data["export_from_date"],
                    'export_to_date' => $data["export_to_date"]
                ];
            } else {
                $allorders = DeliveryChallan::where('challan_status', '=', 'pending')->with('customer', 'delivery_challan_products.product_sub_category', 'delivery_order_products', 'order_products', 'delivery_order')
                                ->orderBy('updated_at', 'desc')->Paginate(20);
            }
        }

        if (count((array)$allorders) > 0) {
            foreach ($allorders as $key => $order) {
                // dd($order);
                $order_quantity = 0;
                $total_quantity = 0;
                $order_quantity_do = 0;
                $order_quantity_o = 0;
                $order_quantity_pending = 0;
                $product_for_order_do_pending = 0;
                $previous_dc_quantity = 0;
                $previous_dc_quantity_parent = 0;
                $is_gst = 0;

                if (count((array)$order['delivery_challan_products']) > 0) {
                    $order_quantity = $order['delivery_challan_products']->sum('present_shipping');
                }
                if (count((array)$order['delivery_challan_products']) > 0) {
                    $actual_quantity = $order['delivery_challan_products']->sum('actual_quantity');
                }
                if (count((array)$order['delivery_order_products']) > 0) {
                    $order_quantity_do = $order['delivery_order_products']->sum('quantity');
                }
                if (count((array)$order['order_products']) > 0) {
                    $order_quantity_o = $order['order_products']->sum('quantity');
                }

                foreach ($order['delivery_challan_products'] as $product_data) {

                    $product_size = $product_data['product_sub_category'];

                    if (isset($product_data)) {
                        $total_quantity = (float)$total_quantity + (float)$product_data->actual_quantity;
                        // if ($product_data->unit_id == 1) {
                        //     $total_quantity = (float)$total_quantity + (float)$product_data->actual_quantity;
                        // }
                        // if ($product_data->unit_id == 2) {
                        //     $total_quantity = (float)$total_quantity + (float)$product_data->actual_quantity * (float)$product_size->weight;
                        // }
                        // if ($product_data->unit_id == 3) {
                        //     $total_quantity = (float)$total_quantity + (float)($product_data->actual_quantity / $product_size->standard_length ) * (float)$product_size->weight;
                        // }
                        // if ($product_data->unit_id == 4) {
                        //     $total_quantity = (float)$total_quantity + (float)($product_data->actual_quantity * $product_size->weight  * $product_data->length);
                        //     // dd($product_data->actual_quantity.'*'.$product_size->weight.'*'.$product_data->length);
                        // }
                        // if ($product_data->unit_id == 5) {
                        //     $total_quantity = (float)$total_quantity + ((float)$product_data->actual_quantity * (float)$product_size->weight * (float)($product_data->length/305));
                        // }
                    } else {
                        $result['send_message'] = "Error";
                        $result['reasons'] = "Order not found.";
//                            return json_encode($result);
                    }
                    if(isset($product_data->vat_percentage) && $product_data->vat_percentage != "0.00"){
                        $is_gst = 1;
                    }
                }
                $allorders[$key]['is_gst'] = $is_gst;
                $allorders[$key]['total_quantity'] = $order_quantity;
                $allorders[$key]['actual_quantity'] = $total_quantity;

                if (($order_quantity == $order_quantity_do) && ($order_quantity_do == $order_quantity_o)) {
                    $allorders[$key]['total_quantity_pending'] = 0;
                } else {

                    $allorders[$key]['total_quantity_pending'] = (float)$order_quantity - (float)$order_quantity_do;

//                    $product_for_order = $order['order_products'];
//                    $product_for_order_do = $order['delivery_order_products'];
                }
            }
        }

        $parameters = parse_url($request->fullUrl());
        $parameters = isset($parameters['query']) ? $parameters['query'] : '';
        Session::put('parameters', $parameters);

        $allorders->setPath('delivery_challan');
        return view('delivery_challan', compact('allorders', 'search_dates'));
    }

    /**
     * Display the specified Delivery Challan Details.
     */
    public function show($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 5) {
            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        $allorder = DeliveryChallan::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby', 'challan_loaded_by.dc_loaded_by', 'challan_labours.dc_labour')->find($id);
        $allorder = $this->calc_hsn_wise($allorder);

        if (count((array)$allorder) < 1) {
            return redirect('delivery_challan')->with('success', 'Invalid challan or challan not found');
        }

        $order_product = Order::with('all_order_products')->find($allorder->order_id);
        if (count((array)$order_product) < 1) {
            $order_product = 0;
        }
        $product_type = $this->check_product_type($allorder);
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        $page_title=" Delivery Challan";
        $url="delivery_challan";
        return view('delivery_challan_details', compact('allorder', 'order_product', 'product_type', 'customers','page_title','url'));
    }
    public function show_daily_pro($id) {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        $allorder = DeliveryChallan::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby', 'challan_loaded_by.dc_loaded_by', 'challan_labours.dc_labour')->find($id);

        if (count((array)$allorder) < 1) {
            return redirect('delivery_challan')->with('success', 'Invalid challan or challan not found');
        }

        $order_product = Order::with('all_order_products')->find($allorder->order_id);
        if (count((array)$order_product) < 1) {
            $order_product = 0;
        }
        $product_type = $this->check_product_type($allorder);
        $customers = Customer::orderBy('tally_name', 'ASC')->get();
        $page_title="Daily Pro Forma Invoice";
        $url="daily_pro_forma_invoice";
        return view('delivery_challan_details', compact('allorder', 'order_product', 'product_type', 'customers','page_title','url'));
    }

    public function check_product_type($delivery_data) {
        $produc_type['pipe'] = "0";
        $produc_type['structure'] = "0";
        $produc_type['sheet'] = "0";
        foreach ($delivery_data['all_order_products'] as $key => $value) {
            if (isset($value['order_type']) && $value['order_type'] == "delivery_challan") {
                if (isset($value['order_product_details']['product_category']->product_type_id) && $value['order_product_details']['product_category']->product_type_id == 1) {
                    $produc_type['pipe'] = "1";
                }
                if (isset($value['order_product_details']['product_category']->product_type_id) && $value['order_product_details']['product_category']->product_type_id == 2) {
                    $produc_type['structure'] = "1";
                }
                if (isset($value['order_product_details']['product_category']->product_type_id) && $value['order_product_details']['product_category']->product_type_id == 3) {
                    $produc_type['sheet'] = "1";
                }
            }
        }
        return $produc_type;
    }

    /**
     * Show Edit Delivery Challan Details
     */
    public function edit($id = "") {
        $idata = Input::all();
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        if (array_key_exists("task",$idata)) {
            $data['task'] = $idata['task'];
        } else {
            $data['task'] = 'del_ch';
        }

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id == 5 | $id == "") {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $allorder = DeliveryChallan::with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'challan_loaded_by.dc_loaded_by', 'challan_labours.dc_labour')
//                ->where('challan_status', '=', 'pending')
                ->find($id);
        $allorder = $this->calc_hsn_wise($allorder);
        if (count((array)$allorder) < 1) {
            return redirect('delivery_challan')->with('validation_message', 'Inavalid delivery challan.');
        }

        $product_type = $this->check_product_type($allorder);
        $units = Units::all();
        $delivery_locations = DeliveryLocation::all();
        return view('edit_delivery_challan', compact('allorder', 'data','units', 'delivery_locations', 'product_type'));
    }

    /**
     * Update Delivery Challan Details
     */
    public function update($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 5) {
            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        $input_data = Input::all();
        $tasked = $input_data['task'];

        // echo '<pre>';
        // print_r($tasked);
        // exit;
        $sms_flag = 1;
        $whatsapp_error = '';
        if (!isset($input_data['grand_total']) || $input_data['grand_total'] == '') {
            return Redirect::back()->with('validation_message', 'No Value Updated. Please update something');
        }
        if (Session::has('forms_edit_delivery_challan')) {
            $session_array = Session::get('forms_edit_delivery_challan');
            if (count((array)$session_array) > 0) {
                if (in_array($input_data['form_key'], (array)$session_array)) {
                    if(Session::has('flash_message') == 'Delivery Challan details updated successfuly .'){
                        return redirect('delivery_challan')->with('flash_message', 'Delivery Challan details updated successfuly .');
                    }else{
                        return Redirect::back()->with('validation_message', 'This delivery challan is already Updated. Please refresh the page');
                    }
                } else {
                    array_push($session_array, $input_data['form_key']);
                    Session::put('forms_edit_delivery_challan', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $input_data['form_key']);
            Session::put('forms_edit_delivery_challan', $forms_array);
        }
        $i = 0;
        $j = count((array)$input_data['product']);
        foreach ($input_data['product'] as $product_data) {
            if ($product_data['name'] == "") {
                $i++;
            }
        }
        if ($i == $j) {
            return Redirect::back()->with('validation_message', 'Please enter at least one product details');
        }
        $delivery_challan = DeliveryChallan::find($id);
        $delivery_order_id = $delivery_challan->delivery_order_id;
        $delivery_order = DeliveryOrder::find($delivery_order_id);

        if (isset($delivery_order)) {
            if (isset($input_data['challan_driver_contact'])) {
                $delivery_order->driver_contact_no = $input_data['challan_driver_contact'];
            }

            if (isset($input_data['challan_vehicle_number'])) {
                $delivery_order->vehicle_number = $input_data['challan_vehicle_number'];
            }
            if (isset($input_data['empty_truck_weight'])) {
                $delivery_order->empty_truck_weight = $input_data['empty_truck_weight'];
            }
            if (isset($input_data['final_truck_weight'])) {
                $delivery_order->final_truck_weight = $input_data['final_truck_weight'];
            }
            $delivery_order->save();
        }
        if (isset($input_data['tcs_applicable'])){
            $delivery_challan_update = DeliveryChallan::where('id', $id)->update([
                'tcs_applicable' => $input_data['tcs_applicable'] == 'yes' ? 1 : 0,
                'tcs_percentage' => isset($input_data['tcs_percentage']) ? $input_data['tcs_percentage'] : '0.1',
            ]);
        }

        // if (isset($delivery_challan->vat_percentage) && $delivery_challan->vat_percentage > 0) {
        //     $input_data['grand_total'] = (float)$input_data['grand_total'] + (float)($input_data['grand_total'] * $delivery_challan->vat_percentage / 100);

        //     $input_data['grand_total'] = number_format((float) $input_data['grand_total'], 2, '.', '');
        // }
        
        $delivery_challan->bill_number = $input_data['billno'];
        $delivery_challan->loaded_by = (isset($input_data['loadedby']) ? $input_data['loadedby'] : '');
//        $delivery_challan->labours = $input_data['labour'];
        $delivery_challan->discount = $input_data['discount'];
        $delivery_challan->freight = $input_data['freight'];
        $delivery_challan->loading_charge = $input_data['loading'];
        $delivery_challan->round_off = $input_data['round_off'];
        $delivery_challan->grand_price = $input_data['grand_total'];
        $delivery_challan->remarks = trim($input_data['challan_remark']);
//        if (isset($input_data['loading_vat_percentage'])) {
//            $delivery_challan->loading_vat_percentage = $input_data['loading_vat_percentage'];
//        } else {
//            $delivery_challan->loading_vat_percentage = 0;
//        }
        if (isset($input_data['freight_vat_percentage'])) {
            $delivery_challan->freight_vat_percentage = $input_data['freight_vat_percentage'];
        } else {
            $delivery_challan->freight_vat_percentage = 0;
        }
        if (isset($input_data['discount_vat_percentage'])) {
            $delivery_challan->discount_vat_percentage = $input_data['discount_vat_percentage'];
        } else {
            $delivery_challan->discount_vat_percentage = 0;
        }
//        $update_challan = $delivery_challan->update([
//            'bill_number' => $input_data['billno'],
//            'freight' => $input_data['freight'],
//            'loading_charge' => $input_data['loading'],
//            'loaded_by' => $input_data['loadedby'],
//            'labours' => $input_data['labour'],
//            'grand_price' => $input_data['grand_total'],
//            'remarks' => $input_data['challan_remark'],
//            'challan_status' => "Pending"
//        ]);
//
//        if (isset($input_data['discount'])) {
//            $delivery_challan->update([
//                'discount' => $input_data['discount']]);
//        }
//
//        if (isset($input_data['vat_percentage'])) {
//            $delivery_challan->update([
//                'vat_percentage' => $input_data['vat_percentage']]);
//        }
//
//        if (isset($input_data['billno'])) {
//            $delivery_challan->update([
//                "bill_number" => $input_data['billno']]);
//        }

        AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
        if ($j != 0) {
            $order_products = array();
            foreach ($input_data['product'] as $product_data) {
                if ($product_data['name'] != "" && $product_data['actual_quantity'] != "") {
                    $order_products = [
                        'order_id' => $id,
                        'order_type' => 'delivery_challan',
                        'product_category_id' => $product_data['id'],
                        'unit_id' => $product_data['units'],
                        'length' => (isset($product_data['length'])?$product_data['length']:'0'),
                        'actual_pieces' => $product_data['actual_pieces'],
                        'actual_quantity' => $product_data['actual_quantity'],
                        'quantity' => $product_data['actual_quantity'],
                        'present_shipping' => $product_data['actual_quantity'],
                        'price' => $product_data['price'],
                        'vat_percentage' => (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == 'yes') ? 1 : 0,
                        'from' => $input_data['order_id'],
                        'parent' => $input_data['order'],
                    ];
                    AllOrderProducts::create($order_products);
                }
                /* check for vat/gst items */
                if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] == '1') {
                    $sms_flag = 1;
                }
                /**/
            }
            $delivery_challan_prod = AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->first();
            $delivery_challan->updated_at = $delivery_challan_prod->updated_at;
            $delivery_challan->save();

            $delivery_challan_id = $delivery_challan->id;
            $created_at = $delivery_challan->created_at;
            $updated_at = $delivery_challan->updated_at;
            \App\DeliveryChallanLabours::
                    where('delivery_challan_id', $delivery_challan_id)
//                    ->forceDelete();
                    ->delete();
            \App\DeliveryChallanLoadedBy::
                    where('delivery_challan_id', $delivery_challan_id)
//                    ->forceDelete();
                    ->delete();

            $actual_qty = $this->calc_actual_qty($id, $input_data);
            if (isset($input_data['loaded_by_pipe'])) {
                $loaders = $input_data['loaded_by_pipe'];
                $loaders_info = [];
                foreach ($loaders as $loader) {
                    $loaders_info[] = [
                        'delivery_challan_id' => $delivery_challan_id,
                        'loaded_by_id' => $loader,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'type' => 'sale',
                        'product_type_id' => '1',
                        'total_qty' => $actual_qty['loaded_by_pipe'],
                    ];
                }
                $add_loaders_info = \App\DeliveryChallanLoadedBy::insert($loaders_info);
            }
            if (isset($input_data['loaded_by_structure'])) {
                $loaders = $input_data['loaded_by_structure'];
                $loaders_info = [];
                foreach ($loaders as $loader) {
                    $loaders_info[] = [
                        'delivery_challan_id' => $delivery_challan_id,
                        'loaded_by_id' => $loader,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'type' => 'sale',
                        'product_type_id' => '2',
                        'total_qty' => $actual_qty['loaded_by_structure'],
                    ];
                }
                $add_loaders_info = \App\DeliveryChallanLoadedBy::insert($loaders_info);
            }
            if (isset($input_data['loaded_by_profile'])) {
                $loaders = $input_data['loaded_by_profile'];
                $loaders_info = [];
                foreach ($loaders as $loader) {
                    $loaders_info[] = [
                        'delivery_challan_id' => $delivery_challan_id,
                        'loaded_by_id' => $loader,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'type' => 'sale',
                        'product_type_id' => '3',
                        'total_qty' => $actual_qty['loaded_by_profile'],
                    ];
                }
                $add_loaders_info = DeliveryChallanLoadedBy::insert($loaders_info);
            }
            if (isset($input_data['labour_pipe'])) {
                $labours = $input_data['labour_pipe'];
                $labours_info = [];
                foreach ($labours as $labour) {
                    $labours_info[] = [
                        'delivery_challan_id' => $delivery_challan_id,
                        'labours_id' => $labour,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'type' => 'sale',
                        'product_type_id' => '1',
                        'total_qty' => $actual_qty['labour_pipe'],
                    ];
                }
                $add_loaders_info = \App\DeliveryChallanLabours::insert($labours_info);
            }
            if (isset($input_data['labour_structure'])) {
                $labours = $input_data['labour_structure'];
                $labours_info = [];
                foreach ($labours as $labour) {
                    $labours_info[] = [
                        'delivery_challan_id' => $delivery_challan_id,
                        'labours_id' => $labour,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'type' => 'sale',
                        'product_type_id' => '2',
                        'total_qty' => $actual_qty['labour_structure'],
                    ];
                }
                $add_loaders_info = \App\DeliveryChallanLabours::insert($labours_info);
            }
            if (isset($input_data['labour_profile'])) {
                $labours = $input_data['labour_profile'];
                $labours_info = [];
                foreach ($labours as $labour) {
                    $labours_info[] = [
                        'delivery_challan_id' => $delivery_challan_id,
                        'labours_id' => $labour,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                        'type' => 'sale',
                        'product_type_id' => '3',
                        'total_qty' => $actual_qty['labour_profile'],
                    ];
                }
                $add_loaders_info = App\DeliveryChallanLabours::insert($labours_info);
            }


            /* inventory code */
            $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'delivery_challan')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }
            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);


            /*
              | ------------------- -----------------------
              | SEND SMS TO CUSTOMER FOR EDIT DELIVERY CHALLAN
              | -------------------------------------------
             */
            $allorder = DeliveryChallan::where('id', '=', $id)
                            ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();

            $input_data = $allorder['delivery_challan_products'];
            $loading_charge = isset($delivery_challan['loading_charge']) && $delivery_challan['loading_charge'] != ""? "₹".$delivery_challan['loading_charge']:"N/A";
            $discount = isset($delivery_challan['discount']) && $delivery_challan['discount'] != ""? "₹".$delivery_challan['discount']:"N/A";
            $freight = isset($delivery_challan['freight']) && $delivery_challan['freight'] != ""? "₹".$delivery_challan['freight']:"N/A";
            $vehicle_number = isset($delivery_order->vehicle_number) && $delivery_order->vehicle_number != "" ? $delivery_order->vehicle_number : "N/A";
            $driver_number = isset($delivery_order->driver_contact_no) && $delivery_order->driver_contact_no != "" ? $delivery_order->driver_contact_no : "N/A";
            $send_sms = Input::get('send_msg');
            $send_whatsapp = Input::get('send_whatsapp');
            $product_string = '';
            $customer_id = $allorder->customer_id;
            $customer = Customer::with('manager')->find($customer_id);
            $cust_count = Customer::with('manager')->where('id',$customer_id)->count();
            if ($sms_flag == 1) {
                $total_quantity = '';
                $i = 1;
                foreach ($input_data as $product_data) {
                    if ($product_data['order_product_details']->alias_name != "") {
                        $product_string .= $i++ . ") " . $product_data['order_product_details']->alias_name . ", " . round($product_data['quantity'],2) . "KG, ₹". $product_data['price'] . " ";
                    }
                }
                if (count((array)$cust_count) > 0) {
                    $str = "Dear Customer,\n\nYour delivery challan has been updated.\n\nCustomer Name: ".ucwords($customer->owner_name)."  \nDelivery Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\n\nUpdated Products:\n".$product_string."\nVehicle No: " .$vehicle_number. "\nDriver No: " .$driver_number. "\nLoading charge: ".$loading_charge."\nDiscount: ".$discount."\nFreight: ".$freight."\n\nVIKAS ASSOCIATES."; 
                    if (App::environment('local')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer->phone_number1;
                    }
                    $msg = urlencode($str);
                    if(SEND_SMS === true && isset($send_sms) && $send_sms == "yes") {
                        $send_msg = new WelcomeController();
                        $send_msg->send_sms($phone_number,$msg);
                    }
                    if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "yes"){
                        $send_msg = new WelcomeController();
                        $send_msg->send_whatsapp($phone_number,$str);                    
                    }
                }
                if (count((array)$customer['manager']) > 0) {
                    $str = "Dear Manager,\n\nDelivery challan has been updated.\n\nCustomer Name: ".ucwords($customer->owner_name)."  \nDelivery Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\n\nUpdated Products:\n".$product_string."\nVehicle No: " .$vehicle_number. "\nDriver No: " .$driver_number. "\nLoading charge: ".$loading_charge."\nDiscount: ".$discount."\nFreight: ".$freight."\n\nVIKAS ASSOCIATES."; 
                    if (App::environment('local')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer['manager']->mobile_number;
                    }
                    $msg = urlencode($str);
                    if(SEND_SMS === true && isset($send_sms) && $send_sms == "yes") {
                        $send_msg = new WelcomeController();
                        $send_msg->send_sms($phone_number,$msg);
                    }
                    if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "yes"){
                        $send_msg = new WelcomeController();
                        $send_msg->send_whatsapp($phone_number,$str);                    
                    }
                }
            }
        }
        //         update sync table
        $tables = ['delivery_challan', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */
        $parameter = Session::get('parameters');
        $parameters = (isset($parameter) && !empty($parameter)) ? '?' . $parameter : '';

        foreach($allorder['delivery_challan_products'] as $prod){
            // dd($prod);
            if($prod->vat_percentage == 0){
                $this->refresh_token_Wihtout_GST();
                $dataService = $this->getTokenWihtoutGST();
            }
            else{
                $this->refresh_token();
                $dataService = $this->getToken();
            }
        }

        $inv_id = DeliveryChallan::where('id',$id)->first();
        if(!empty($inv_id->doc_number)){
            $invoice = Invoice::create([
                "Id" => $inv_id->doc_number,
                "SyncToken" => "0"
            ]);
            $inv = $dataService->Delete($invoice);
            DeliveryChallan::where('id',$id)->update(['doc_number'=>null]);
        }

        DeliveryChallan::where('id',$id)->update(['is_editable'=>1]);
        if($tasked == 'dailyProforma'){
            return redirect('daily_pro_forma_invoice' . $parameters)->with('flash_message', 'Delivery Challan details updated successfuly'.$whatsapp_error);
        }else {
            return redirect('delivery_challan' . $parameters)->with('flash_message', 'Delivery Challan details updated successfuly '.$whatsapp_error);
        }
    }

    public function calc_actual_qty($dc_id = 0, $input_data = []) {
        $actual_qty['pipe'] = "0";
        $actual_qty['structure'] = "0";
        $actual_qty['sheet'] = "0";
        $actual_qty['loaded_by_pipe'] = "0";
        $actual_qty['loaded_by_structure'] = "0";
        $actual_qty['loaded_by_profile'] = "0";
        $actual_qty['labour_pipe'] = "0";
        $actual_qty['labour_structure'] = "0";
        $actual_qty['labour_profile'] = "0";

        if ($dc_id != 0 && $input_data != []) {
            $allorder = DeliveryChallan::with('delivery_challan_products.order_product_details')->find($dc_id);

            foreach ($allorder['delivery_challan_products'] as $key => $value) {
                if ($value['order_product_details']['product_category']->product_type_id == 1) {
                    $actual_qty['pipe'] += $value->actual_quantity;
                } else if ($value['order_product_details']['product_category']->product_type_id == 2) {
                    $actual_qty['structure'] += $value->actual_quantity;
                }else if ($value['order_product_details']['product_category']->product_type_id == 3) {
                    $actual_qty['sheet'] += $value->actual_quantity;
                }
            }

            if (isset($input_data['loaded_by_pipe'])) {
                $actual_qty['loaded_by_pipe'] = $actual_qty['pipe'] / count((array)$input_data['loaded_by_pipe']);
            }
            if (isset($input_data['loaded_by_structure'])) {
                $actual_qty['loaded_by_structure'] = $actual_qty['structure'] / count((array)$input_data['loaded_by_structure']);
            }
            if (isset($input_data['loaded_by_profile'])) {
                $actual_qty['loaded_by_profile'] = $actual_qty['sheet'] / count((array)$input_data['loaded_by_profile']);
            }
            if (isset($input_data['labour_pipe'])) {
                $actual_qty['labour_pipe'] = $actual_qty['pipe'] / count((array)$input_data['labour_pipe']);
            }
            if (isset($input_data['labour_structure'])) {
                $actual_qty['labour_structure'] = $actual_qty['structure'] / count((array)$input_data['labour_structure']);
            }
            if (isset($input_data['labour_profile'])) {
                $actual_qty['labour_profile'] = $actual_qty['sheet'] / count((array)$input_data['labour_profile']);
            }
        }

        return $actual_qty;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 5) {
            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        $inputData = Input::get('formData');
        parse_str($inputData, $formFields);
        $password = $formFields['password'];
        $order_sort_type = $formFields['order_sort_type'];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if ($password == '') {
            return Redirect::to('delivery_challan')->with('error', 'Please enter your password');
        }
        if (Hash::check($password, Auth::user()->password)) {
            /* inventory code */
            $product_categories = AllOrderProducts::select('product_category_id')->where('order_id', $id)->where('order_type', 'delivery_challan')->get();
            foreach ($product_categories as $product_categoriy) {
                $product_category_ids[] = $product_categoriy->product_category_id;
            }

            AllOrderProducts::where('order_id', '=', $id)->where('order_type', '=', 'delivery_challan')->delete();
            DeliveryChallan::find($id)->delete();

            $calc = new InventoryController();
            $calc->inventoryCalc($product_category_ids);

            Session::put('order-sort-type', $order_sort_type);
            //         update sync table
            $tables = ['delivery_challan', 'all_order_products'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */
            return array('message' => 'success');
        } else {
            return array('message' => 'failed');
        }
    }

    /*
     * Generate Serial number and print Delivery Challan
     */

    function quickbook_create_item($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = Item::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }

    function getTokenWihtoutGST(){

        require_once base_path('quickbook/vendor/autoload.php');
        // $quickbook = App\QuickbookToken::first();
        $quickbook = App\QuickbookToken::find(1);
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => env('QBORealmID_All_Inc'),
            'baseUrl' => "Production",
            'minorVersion'=>34
        ));
    }
    function refresh_token_Wihtout_GST(){
        require_once base_path('quickbook/vendor/autoload.php');
        // $quickbook = App\QuickbookToken::first();
        $quickbook = App\QuickbookToken::find(1);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }

    function getToken(){

        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(2);
        // echo '<pre>';
        // print_r($quickbook);
        // exit;
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => env('QBORealmID_Plus_GST'),
            'baseUrl' => "Production",
            'minorVersion'=>34
        ));
    }

    function refresh_token(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(2);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }

    public function generate_invoice($id){
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 11) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 5) {
            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        set_time_limit(0);
       $update_delivery_challan = DeliveryChallan::with('delivery_challan_products.order_product_all_details.product_category', 'customer', 'delivery_order.location')->find($id);
        require_once base_path('quickbook/vendor/autoload.php');
        if($update_delivery_challan->delivery_challan_products[0]->vat_percentage==0){
            $this->refresh_token_Wihtout_GST();
            $dataService = $this->getTokenWihtoutGST();
        }
        else{
            $this->refresh_token();
            $dataService = $this->getToken();
        }

        $error = $dataService->getLastError();
        if ($error) {
            if($update_delivery_challan->delivery_challan_products[0]->vat_percentage==0)
            {
                $this->refresh_token_Wihtout_GST();
                $dataService = $this->getTokenWihtoutGST();
            }
            else{
                $this->refresh_token();
                $dataService = $this->getToken();
            }
        }

        if(Auth::user()->role_id != 0 && Auth::user()->role_id != 11){
            if($update_delivery_challan->is_print_user != 0){
                // \Illuminate\Support\Facades\Session::flash('flash_message_err', 'You can not print many time, please contact your administrator');
                // return redirect('delivery_challan?status_filter=completed');
                return Redirect::back()->withInput()->with('error', 'You can not print many time, please contact your administrator');
            }
        }
        if($update_delivery_challan->doc_number){

            $invoice = $dataService->Query("select * from Invoice where Id = '".$update_delivery_challan->doc_number."' ");

            $error = $dataService->getLastError();
            if ($error) {
                if($update_delivery_challan->delivery_challan_products[0]->vat_percentage==0)
                {
                    $this->refresh_token_Wihtout_GST();
                    $dataService = $this->getTokenWihtoutGST();
                }
                else{
                    $this->refresh_token();
                    $dataService = $this->getToken();

                }

                $invoice = $dataService->Query("select * from Invoice where Id = '".$update_delivery_challan->doc_number."' ");

                $pdf = $dataService->DownloadPDF($invoice[0],base_path('public/upload/invoice/'));
            }
            else{
                $pdf = $dataService->DownloadPDF($invoice[0],base_path('public/upload/invoice/'));
            }
            $pdfNAme = explode('invoice/',$pdf)[1];

            if(Auth::user()->role_id != 0 && Auth::user()->role_id != 11){
                DeliveryChallan::where('id',$id)->update(['is_print_user'=>1]);
            }

            return redirect()->away(asset('upload/invoice/'.$pdfNAme));
        }
        else{
            $cust_id = $update_delivery_challan->customer_id;
            $order_id = $update_delivery_challan->order_id;
            $loc_id = \App\DeliveryOrder::where('customer_id',$cust_id)->where('order_id',$order_id)->first();
            $state = \App\DeliveryLocation::where('id',isset($loc_id->delivery_location_id)?$loc_id->delivery_location_id:0)->first();
            $local = \App\States::where('id',isset($state->state_id)?$state->state_id:0)->first();
            $local_state = isset($local->local_state)?$local->local_state:0;
            $total_price = 0;
            $total_vat = 0;
            
            $final_vat_amount = 0; 
            $final_total_amt = 0;
            $total_amount = 0;

            $line = [];
            $i = 0;
            $vat_clc=0;
            $gst_percentage=0;

            foreach ($update_delivery_challan->delivery_challan_products as  $del_products){
                if($del_products->actual_quantity != 0){
                $TaxCodeRef = 0;
                $grand_total = 0;
                $sgst = 0;
                $cgst = 0;
                $igst = 0;
                $gst_val = 0;
                $price = ((isset($del_products->price) && $del_products->price != '0.00')?$del_products->price:$del_products['order_product_all_details']->product_category['price']);
                $amount = (float)$del_products->actual_quantity * (float)$price;
                $total_amount = round($amount + $total_amount, 2);
                $hsncode = $del_products->order_product_all_details->hsn_code;
                $hsn = Hsn::where('hsn_code',$hsncode)->first();
                $state = \App\DeliveryLocation::where('id',$update_delivery_challan->delivery_order->delivery_location_id)->first();
                $local = \App\States::where('id',(isset($state->state_id)?$state->state_id:0))->first();
                $local_state = isset($local->local_state)?$local->local_state:0;
                if((!empty($local->state_name)) && (!empty($state->area_name))){
                    $state_value = $local->state_name;
                    $city_value = $state->area_name;
                }else{
                    $state_value = 'Maharashtra';
                    $city_value = 'Pune';
                }

                if($hsn){
                    $gst = App\Gst::where('gst',$hsn->gst)->first();
                    if($gst){
                        if(isset($gst->quick_gst_id) && $gst->quick_gst_id){
                            if($del_products->vat_percentage > 0){
                                if($local_state == 1){
                                    $TaxCodeRef = $gst->quick_gst_id;
                                    $sgst = isset($gst->sgst)?$gst->sgst:0;
                                    $cgst = isset($gst->cgst)?$gst->cgst:0;
                                    $gst_val = isset($gst->gst)?$gst->gst:0;
                                    $total_sgst_amount = ((float)$amount * (float)$sgst) / 100;
                                    $total_cgst_amount = ((float)$amount * (float)$cgst) / 100;
                                    $total_vat_amount1 = (round($total_sgst_amount,2) + round($total_cgst_amount,2));
                                }else {
                                    $TaxCodeRef = $gst->quick_igst_id;
                                    $igst = isset($gst->igst)?$gst->igst:0;
                                    $gst_val = isset($gst->gst)?$gst->gst:0;
                                    $total_igst_amount = ((float)$amount * (float)$igst) / 100;
                                    $total_vat_amount1 = round($total_igst_amount,2);
                                }
                                $total_vat_amount = $total_vat_amount1;
                                $total_price += round($total_vat_amount,2);
                            }
                        }
                    }
                }
                $i++;
                if($del_products->vat_percentage==0){
                    $quickbook_item_id=$del_products->order_product_all_details->quickbook_a_item_id;
                    $productname = $del_products->order_product_all_details->alias_name;
                }
                else{
                    $quickbook_item_id=$del_products->order_product_all_details->quickbook_item_id;
                    $productname = $del_products->order_product_all_details->alias_name;
                }
                $productname = ltrim($productname);
                 $item_query = "select * from Item where Name ='".$productname."'";
                $item_details = $dataService->Query($item_query);

                if(!empty($item_details)){
                    $quickbook_item_id = $item_details[0]->Id;
                }
                /*else{
                   $quickbook_item_id =$quickbook_item_id;
                }*/
                //print $item_details[0]->Id;

                /*if($del_products->vat_percentage!=0){
                    $line[] = [
                        // "HSN/SAC" => $del_products->hsn_code,
                        "Description" => $del_products->order_product_all_details->product_category->product_type->name,
                        "Amount" => $del_products->quantity * ((isset($del_products->price) && $del_products->price != '0.00') ? $del_products->price : $del_products->order_product_all_details->product_category->price),
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "name" => $productname,
                                "value" => $quickbook_item_id
                            ],
                            // "UQCId" => $del_products->unit_id,
                            "UnitPrice" => ((isset($del_products->price) && $del_products->price != '0.00') ? $del_products->price : $del_products->order_product_all_details->product_category->price),
                            "Qty" => $del_products->quantity,
                            "TaxCodeRef" => [
                                "value" => $TaxCodeRef
                            ],
                            "TaxClassificationRef" => $del_products->order_product_all_details->hsn_code
                        ]
                    ];
                }else{
                    $line[] = [
                        // "HSN/SAC" => $del_products->hsn_code,
                        "Description" => $del_products->order_product_all_details->product_category->product_type->name,
                        "Amount" => $del_products->quantity * ((isset($del_products->price) && $del_products->price != '0.00') ? $del_products->price : $del_products->order_product_all_details->product_category->price),
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "name" => $productname,
                                "value" => $quickbook_item_id
                            ],
                            // "UQCId" => $del_products->unit_id,
                            "UnitPrice" => ((isset($del_products->price) && $del_products->price != '0.00') ? $del_products->price : $del_products->order_product_all_details->product_category->price),
                            "Qty" => $del_products->quantity,
                            "TaxCodeRef" => [
                                "value" => 9
                            ],
                            "TaxClassificationRef" => $del_products->order_product_all_details->hsn_code
                        ]
                    ];
                }*/
            }
            }
            if(isset($update_delivery_challan->delivery_challan_products[0]->vat_percentage) && $update_delivery_challan->delivery_challan_products[0]->vat_percentage > 0){
                $loading_vat = 18;
            }else{
                $loading_vat = 0;
            }
            if($local_state == 1) {
                $loading_vat_amount_sgst = ((float)$update_delivery_challan->loading_charge * (float)$sgst) / 100;
                $freight_vat_amount_sgst = ((float)$update_delivery_challan->freight * (float)$sgst) / 100;
                $discount_vat_amount_sgst = ((float)$update_delivery_challan->discount * (float)$sgst) / 100;
                
                $loading_vat_amount_cgst = ((float)$update_delivery_challan->loading_charge * (float)$cgst) / 100;
                $freight_vat_amount_cgst = ((float)$update_delivery_challan->freight * (float)$cgst) / 100;
                $discount_vat_amount_cgst = ((float)$update_delivery_challan->discount * (float)$cgst) / 100;
                
                $loading_vat_amount = round($loading_vat_amount_sgst,2) + round($loading_vat_amount_cgst,2);
                $freight_vat_amount = round($freight_vat_amount_sgst,2) + round($freight_vat_amount_cgst,2);
                $discount_vat_amount = round($discount_vat_amount_sgst,2) + round($discount_vat_amount_cgst,2);
            } else {
                $loading_vat_amount = ((float)$update_delivery_challan->loading_charge * (float)$loading_vat) / 100;
                $freight_vat_amount = ((float)$update_delivery_challan->freight * (float)$loading_vat) / 100;
                $discount_vat_amount = ((float)$update_delivery_challan->discount * (float)$loading_vat) / 100;
            }

            $hsn_data = $this->calc_hsn_wise($update_delivery_challan);
            $hsn_array = $hsn_data->hsn;
            if($del_products->vat_percentage!=0){
                foreach($hsn_array as $hsn_products){
                    if($hsn_products['actual_quantity'] != 0){
                        if($gst){
                            if($local_state == 1){
                                $TaxCodeRef = 15;
                                $sgst = isset($gst->sgst)?$gst->sgst:0;
                                $cgst = isset($gst->cgst)?$gst->cgst:0;
                                $total_sgst_amount = round(($hsn_products['amount'] * $sgst / 100),2);
                                $total_cgst_amount = round(($hsn_products['amount'] * $cgst / 100),2);
                                $vat_clc += (round($total_sgst_amount,2) + round($total_cgst_amount,2));
                            }else {
                                $TaxCodeRef = 22;
                                $igst = isset($gst->igst)?$gst->igst:0;
                                $vat_clc += round(($hsn_products['amount'] * $igst / 100),2);
                            }
                        }else{
                            $gst_percentage = $hsn_products['vat_percentage'];
                            $vat_clc += round(($hsn_products['amount'] * $gst_percentage / 100),2);
                        }

                        $productname = ltrim($hsn_products['id']);
                        $item_query = "select * from Item where Name ='".$productname."'";
                        $item_details = $dataService->Query($item_query);

                        if(!empty($item_details)){
                            $quickbook_item_id = $item_details[0]->Id;
                        }

                        $line[] = [
                            "Description" => $hsn_products['actual_quantity']." KG",
                            "Amount" => $hsn_products['amount'],
                            "DetailType" => "SalesItemLineDetail",
                            "SalesItemLineDetail" => [
                                "ItemRef" => [
                                    "name" => $productname,
                                    "value" => $quickbook_item_id
                                ],
                                "UnitPrice" => $hsn_products['amount']/$hsn_products['count'],
                                "Qty" => $hsn_products['count'],
                                "TaxCodeRef" => [
                                    "value" => $TaxCodeRef
                                ],
                                "TaxClassificationRef" => $hsn_products['id']
                            ]
                        ];
                    }
                }
            }else{
                foreach($hsn_array as $hsn_products){
                    if($hsn_products['actual_quantity'] != 0){
                        $gst_percentage = $hsn_products['vat_percentage'];
                        $vat_clc += round(($hsn_products['amount'] * $gst_percentage / 100),2);

                        $productname = ltrim($hsn_products['id']);
                        $item_query = "select * from Item where Name ='".$productname."'";
                        $item_details = $dataService->Query($item_query);

                        if(!empty($item_details)){
                            $quickbook_item_id = $item_details[0]->Id;
                        }
                        $line[] = [
                            "Description" => $hsn_products['actual_quantity']." KG",
                            "Amount" => $hsn_products['amount'],
                            "DetailType" => "SalesItemLineDetail",
                            "SalesItemLineDetail" => [
                                "ItemRef" => [
                                    "name" => $productname,
                                    "value" => $quickbook_item_id
                                ],
                                "UnitPrice" => $hsn_products['amount'],
                                "Qty" => 1,
                                "TaxCodeRef" => [
                                    "value" => 9
                                ],
                                "TaxClassificationRef" => $hsn_products['id']
                            ]
                        ];
                    }
                }
            }

            if($del_products->vat_percentage==0)
            {
                $quickbook_customer_id=$update_delivery_challan->customer->quickbook_a_customer_id;
                $tally_name = $update_delivery_challan->customer->tally_name;
                $owner_name = $update_delivery_challan->customer->owner_name;
            }
            else
            {
                $quickbook_customer_id=$update_delivery_challan->customer->quickbook_customer_id;
                $tally_name = $update_delivery_challan->customer->tally_name;
                $owner_name = $update_delivery_challan->customer->owner_name;
            }
            if(isset($update_delivery_challan->freight) && $update_delivery_challan->freight != "0.00"){
                $freight_item = ProductSubCategory::where('alias_name','Freight Charges')->first();

                if($del_products->vat_percentage==0){
                      $freight_id=$freight_item->quickbook_a_item_id;
                      $frieghtname = $freight_item->alias_name;
                      $tax = 9;
                }
                else{
                    $freight_id=$freight_item->quickbook_item_id;
                    $frieghtname = $freight_item->alias_name;
                    if($local_state == 1){
                        $tax = 15;
                    } else{
                        $tax = 22;
                    }
                }

               $line[] = [
                        "Amount" => $update_delivery_challan->freight,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "name" => $frieghtname,
                                "value" => $freight_id
                               // "name" => "Services",
                               //  "value" => 1
                            ],
                            "TaxCodeRef"=>[
                                "value" => $tax
                            ],
                        ]
                    ];
            }
            if(isset($update_delivery_challan->loading_charge) && $update_delivery_challan->loading_charge != "0.00"){ 
                // $TaxCodeRef = 26;
                $loading_item = ProductSubCategory::where('alias_name','Loading Charges')->first();
                if($del_products->vat_percentage==0){
                    $loading_id=$loading_item->quickbook_a_item_id;
                    $loadingname = $loading_item->alias_name;
                    $tax = 9;
                }
                else{
                    $loading_id=$loading_item->quickbook_item_id;
                     $loadingname = $loading_item->alias_name;
                     if($local_state == 1){
                        $tax = 15;
                    } else{
                        $tax = 22;
                    }
                }


                 $line[] = [
                        "Amount" => $update_delivery_challan->loading_charge,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "name" => $loadingname,
                                "value" => $loading_id
                            ],
                            "TaxCodeRef"=>[
                                "value" => $tax
                            ],
                        ]
                    ];
            }
             if(!empty($update_delivery_challan->discount) && $update_delivery_challan->discount != 0){
                 $discount_item = ProductSubCategory::where('alias_name','Discount')->first();
                 if($del_products->vat_percentage==0){
                     $discount_a_id=$discount_item->quickbook_a_item_id;
                    $discountname = $discount_item->alias_name;
                    $tax = 9;
                 }

                 else{
                      $discount_a_id=$discount_item->quickbook_item_id;
                    $discountname = $discount_item->alias_name;
                    if($local_state == 1){
                        $tax = 15;
                    } else{
                        $tax = 22;
                    }
                 }

                 $line[] = [
                        "Amount" => floatval($update_delivery_challan->discount),
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                            "ItemRef" => [
                                "name" => $discountname,
                                 "value" => $discount_a_id
                            ],
                            "TaxCodeRef"=>[
                                "value" => $tax
                            ],
                        ]
                    ];
            }
            $tcs_amount = 0;
            $total = (float)$total_amount + (float)$update_delivery_challan->freight + (float)$update_delivery_challan->loading_charge + (float)$update_delivery_challan->discount;
            $total_vat = round($vat_clc,2) + round($loading_vat_amount,2) + round($freight_vat_amount,2) + round($discount_vat_amount,2);
            $tot = $total + $total_vat;
            $tcs_item = ProductSubCategory::where('alias_name','TCS')->first();
            $tcs_applicable = $update_delivery_challan->tcs_applicable;
            if($del_products->vat_percentage!=0 && $tcs_applicable == 1){
                $tcs_id = $tcs_item->quickbook_item_id;
                $tax = 9;
                $tcs_amount = ($tot * $update_delivery_challan->tcs_percentage) / 100;
                $line[] = [
                    "Amount" => round($tcs_amount,2),
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "ItemRef" => [
                            "name" => "TCS",
                            "value" => $tcs_id
                        ],
                        "TaxCodeRef"=>[
                            "value" => $tax
                        ],
                    ]
                ];
            }
            $roundoff_item = ProductSubCategory::where('alias_name','Round Off')->first();
            if($del_products->vat_percentage==0){
                $roundoff_id=$roundoff_item->quickbook_a_item_id;
            }else{
                $roundoff_id=$roundoff_item->quickbook_item_id;
            }
                $tax = 9;
                $tot = $total + $total_vat + $tcs_amount;
                $roundoff = round($tot,0) - $tot;

                $line[] = [
                "Amount" => $roundoff,
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "ItemRef" => [
                        "name" => "Round Off",
                        "value" => $roundoff_id
                    ],
                    "TaxCodeRef"=>[
                        "value" => $tax
                    ],
                ]
            ];
            
           /* if($del_products->vat_percentage==0)
            {
                $quickbook_customer_id=$update_delivery_challan->customer->quickbook_a_customer_id;
                $tally_name = $update_delivery_challan->customer->tally_name;

            }
            else
            {
                $quickbook_customer_id=$update_delivery_challan->customer->quickbook_customer_id;
                $tally_name = $update_delivery_challan->customer->tally_name;
            } */


             $custom_query = "select * from Customer where CompanyName='".$update_delivery_challan->customer->company_name."'";
            //  $custom_query = "select * from Customer maxresults 2";
             //echo $custom_query;
            $customer_details = $dataService->Query($custom_query);
            //    dd($customer_details);
             if(!empty($customer_details)){
                    $quickbook_customer_id = $customer_details[0]->Id;
                }
                else{
                   $quickbook_customer_id =$quickbook_customer_id;
                }

           // $quickbook_customer_id = $customer_details[0]->Id;
          // print_R($customer_details);
            $theResourceObj = Invoice::create([
                "Line" => $line,
                "CustomerRef" => [
                    "name" => $owner_name,
                    "value" => $quickbook_customer_id
                ],
                "ShipAddr" => [
                    "City" =>  $city_value,
                    "CountrySubDivisionCode" => $state_value
                ]
            ]);

            $inv = $dataService->add($theResourceObj);
            $error = $dataService->getLastError();
            if ($error) {
                if($del_products->vat_percentage==0)
                {
                    $this->refresh_token_Wihtout_GST();
                    $dataService = $this->getTokenWihtoutGST();
                    // $inv = $dataService->add($theResourceObj);
                }
                else{
                    $this->refresh_token();
                    $dataService = $this->getToken();
                }

                $inv = $dataService->add($theResourceObj);
                $error1 = $dataService->getLastError();
                if($error1){
                    if($del_products->vat_percentage==0)
                {
                    $this->refresh_token_Wihtout_GST();
                    $dataService = $this->getTokenWihtoutGST();
                    // $inv = $dataService->add($theResourceObj);
                }
                else{
                    $this->refresh_token();
                    $dataService = $this->getToken();
                }
                    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    echo "The Response message is: " . $error->getResponseBody() . "\n";
                    die;
                }
                else{
                    $doc_num =  $inv->Id;
                }
            }
            else {
                $doc_num =  $inv->Id;
            }

            DeliveryChallan::where('id',$id)->update(['doc_number'=>$doc_num]);
            if(Auth::user()->role_id != 0 && Auth::user()->role_id != 11){
                DeliveryChallan::where('id',$id)->update(['is_print_user'=>1]);
            }
            $pdf = $dataService->DownloadPDF($inv,base_path('public/upload/invoice/'));

            $pdfNAme = explode('invoice/',$pdf)[1];
            return redirect()->away(asset('upload/invoice/'.$pdfNAme));



        }

    }


    public function print_delivery_challan($id, DropboxStorageRepository $connection) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 4 && Auth::user()->role_id != 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 5) {
            return Redirect::to('inquiry')->with('error', 'You do not have permission.');
        }
        if (Auth::user()->role_id == 6) {
            return Redirect::to('due-payment');
        }
        $serial_number_delivery_order = Input::get('serial_number');
        $current_date = date("m/d/");
        $sms_flag = 1;
        $vat_applicable = 0;
//      $update_delivery_challan = DeliveryChallan::with('delivery_challan_products.order_product_details', 'customer', 'delivery_order.location')->find($id);
        $update_delivery_challan = DeliveryChallan::with('delivery_challan_products.order_product_all_details.product_category', 'customer', 'delivery_order.location')->find($id);
        // echo '<pre>';
        // print_r($update_delivery_challan);
        if (isset($update_delivery_challan->serial_number) && $update_delivery_challan->challan_status == 'completed') {
//            $update_delivery_challan = $this->calc_qty_product_type_wise($update_delivery_challan);
            $allorder = $update_delivery_challan;
//            $allorder = DeliveryChallan::where('id', '=', $id)->where('challan_status', '=', 'completed')
//                            ->with('delivery_challan_products.order_product_details', 'customer', 'delivery_order.location')->first();

            $total_vat_amount = 0;

            foreach ($update_delivery_challan->delivery_challan_products as $key => $delivery_challan_products) {
                if ($delivery_challan_products->vat_percentage > 0) {
                    $vat_applicable = 1;
                    if ($delivery_challan_products->vat_percentage != '' && $delivery_challan_products->vat_percentage > 0) {
                        $total_vat_amount = (float)$total_vat_amount + ((float)($delivery_challan_products->present_shipping * $delivery_challan_products->price * $delivery_challan_products->vat_percentage) / 100);
                    }
                }
            }
            $allorder['is_gst'] = $vat_applicable;
            $date_letter = $update_delivery_challan->serial_number;
            $hsn_data = $this->calc_hsn_wise($update_delivery_challan);
            $viewhtml = View::make('delivery_challan_pdf', [
                'allorder' => $allorder,
                'total_vat_amount' => $total_vat_amount
                ])->render();
                
            $pdf = App::make('dompdf.wrapper');
            // $pdf->loadHTML('delivery_challan_pdf', [
            //     'allorder' => $allorder,
            //     'total_vat_amount' => $total_vat_amount
            // ]);
            $pdf->loadHTML($viewhtml);
            $file_name = str_replace('/', '-', $date_letter);
            $uuid = uniqid();
            if($vat_applicable>0){
                Storage::put(getcwd() . "/upload/invoices/dc/" . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());
                $pdf->save(getcwd() . "/upload/invoices/dc/" . str_replace('/', '-', $date_letter) . '.pdf');
                chmod(getcwd() . "/upload/invoices/dc/" . str_replace('/', '-', $date_letter) . '.pdf', 0777);
            }
           $connection->getConnection()->put('Delivery Challan/' . date('d-m-Y') . '/' . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());

        } else {
            $vat_applicable = 0;
            $profile_present = 0;
            $total_vat_amount = 0;
            $product_type_id = "";
            if (isset($update_delivery_challan->delivery_challan_products) && count((array)$update_delivery_challan->delivery_challan_products) > 0) {
                foreach ($update_delivery_challan->delivery_challan_products as $key => $delivery_challan_products) {
//                   dd($delivery_challan_products['order_product_all_details']);
                    if(isset($delivery_challan_products['order_product_all_details']) && isset($delivery_challan_products['order_product_all_details']['product_category'])){
                        $product_cat = $delivery_challan_products['order_product_all_details']['product_category'];
                        $product_type_id = $product_cat->product_type_id;
                    }
//                dd($product_type_id);
                if(isset($product_type_id) && $product_type_id==3){

                    if ($delivery_challan_products->vat_percentage != '' && $delivery_challan_products->vat_percentage > 0) {
                        $profile_present = 1;
                        $total_vat_amount = (float)$total_vat_amount + ((float)($delivery_challan_products->present_shipping * $delivery_challan_products->price * $delivery_challan_products->vat_percentage) / 100);
                    }
                    else{
                        if($delivery_challan_products->vat_percentage > 0){
                            $vat_applicable = 1;
                            if ($delivery_challan_products->vat_percentage != '' && $delivery_challan_products->vat_percentage > 0) {
                                $total_vat_amount = (float)$total_vat_amount + ((float)($delivery_challan_products->present_shipping * $delivery_challan_products->price * $delivery_challan_products->vat_percentage) / 100);
                            }
                        }
                    }
                }
                elseif ($delivery_challan_products->vat_percentage > 0) {
                        $vat_applicable = 1;
                        if ($delivery_challan_products->vat_percentage != '' && $delivery_challan_products->vat_percentage > 0) {
                            $total_vat_amount = (float)$total_vat_amount + ((float)($delivery_challan_products->present_shipping * $delivery_challan_products->price * $delivery_challan_products->vat_percentage) / 100);
                        }
                    }
                }
            }

            $dc = DeliveryChallan::where('updated_at', 'like', date('Y-m-d') . '%')->withTrashed()->get();
            $number = '1';
            if (count((array)$dc) <= 0) {
                $number = '1';
            } else {
                $serial_numbers = [];
                foreach ($dc as $temp) {
                    $list = explode("/", $temp->serial_number);
                    $serial_numbers[] = chop(chop($list[count((array)$list) - 1], "P"), "A");
                    $pri_id = max($serial_numbers);
                    $number = (int)$pri_id + 1;
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
                            $modified_id = substr($list[count((array)$list) - 1], 0, -1);
                            $modified_str = explode("V", $modified_id);
                            $modified_id = $modified_str[0];
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
                            $modified_id = substr($list[count((array)$list) - 1], 0, -1);
                            $modified_str = explode("V", $modified_id);
                            $modified_id = $modified_str[0];
                        }
                    } else {
                        $modified_id = $number;
                    }
                }
            }

//            if ($update_delivery_challan->ref_delivery_challan_id == 0) {
//                $modified_id = $id;
//            } else {
//                $modified_id = $update_delivery_challan->ref_delivery_challan_id;
//            }
            if($profile_present>0){
                $suffix = 'VP';
            }
            elseif($vat_applicable>0){
                $suffix = 'P';
            }else{
                $suffix = 'A';
            }
//            $date_letter = 'DC/' . $current_date . $modified_id . (($vat_applicable > 0) ? "P" : "A");
            $date_letter = 'DC/' . $current_date . $modified_id . $suffix;
            $update_delivery_challan->serial_number = $date_letter;
            $update_delivery_challan->challan_status = 'completed';
            $update_delivery_challan->save();
//            $update_delivery_challan = $this->calc_qty_product_type_wise($update_delivery_challan);
// //            $this->checkpending_quantity();
//            $allorder = DeliveryChallan::where('id', '=', $id)->where('challan_status', '=', 'completed')
////                            ->with('delivery_challan_products.unit', 'delivery_challan_products.order_product_details', 'customer', 'customer_difference', 'delivery_order.location')->first();
//                            ->with('delivery_challan_products.order_product_details', 'customer','delivery_order.location')->first();

            $allorder = $update_delivery_challan;

            $number = $allorder->grand_price;
            $exploded_value = explode(".", $number);

            if (!isset($exploded_value[1])) {
                $number = number_format($number, 2, '.', '');
                $allorder->grand_price = $number;
                $allorder->save();
                $exploded_value = explode(".", $number);
            }

            $result_paisa = $exploded_value[1] % 10;
//            if (isset($exploded_value[1]) && strlen($exploded_value[1]) > 1 && $result_paisa != 0) {
//                $convert_value = $this->convert_number_to_words($allorder->grand_price);
//            } else {
//                $convert_value = $this->convert_number($allorder->grand_price);
//            }
//            $allorder['convert_value'] = $convert_value;
            $hsn_data = $this->calc_hsn_wise($update_delivery_challan);
            
            $allorder['is_gst'] = $vat_applicable;
            $viewhtml = View::make('delivery_challan_pdf', [
                'allorder' => $allorder,
                'total_vat_amount' => $total_vat_amount]
                )->render();
            $pdf = App::make('dompdf.wrapper');
            // $pdf->loadHTML('delivery_challan_pdf', [
            //     'allorder' => $allorder,
            //     'total_vat_amount' => $total_vat_amount
            // ]);
            $pdf->loadHTML($viewhtml);
            $file_name = str_replace('/', '-', $date_letter);
            $uuid = uniqid();
            if($vat_applicable>0){
                Storage::put(getcwd() . "/upload/invoices/dc/" . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output());
                $pdf->save(getcwd() . "/upload/invoices/dc/" . str_replace('/', '-', $date_letter) . '.pdf');
                chmod(getcwd() . "/upload/invoices/dc/" . str_replace('/', '-', $date_letter) . '.pdf', 0777);
            }
            $connection->getConnection()->put('Delivery Challan/' . date('d-m-Y') . '/' . str_replace('/', '-', $date_letter) . '.pdf', $pdf->output()); 
        }

        /* inventory code */
        // $product_categories = AllOrderProducts::where('order_id', $id)->where('order_type', 'delivery_challan')->get();
        $product_categories = DB::table('all_order_products')->where('order_id', $id)->where('order_type', 'delivery_challan')->get();
        foreach ($product_categories as $product_categoriy) {
            $product_category_ids[] = $product_categoriy->product_category_id;
        }

        $calc = new InventoryController();
        $calc->inventoryCalc($product_category_ids);

//        $update_delivery_challan = $this->calc_qty_product_type_wise($update_delivery_challan);

        /*
          | ------------------- -----------------------
          | SEND SMS TO CUSTOMER FOR NEW DELIVERY CHALLAN
          | -------------------------------------------
         */
        $input_data = $allorder['delivery_challan_products'];
        $delivery_order = $allorder['delivery_order'];
        $vehicle_number = isset($delivery_order->vehicle_number) && $delivery_order->vehicle_number != "" ? $delivery_order->vehicle_number : "N/A";
        $driver_number = isset($delivery_order->driver_contact_no) && $delivery_order->driver_contact_no != "" ? $delivery_order->driver_contact_no : "N/A";  
        $product_string = '';
        $send_sms = Input::get('send_sms');
        $send_whatsapp = Input::get('send_whatsapp');
        if ($sms_flag == 1) {
            $customer_id = $allorder->customer_id;
            $customer = Customer::with('manager')->find($customer_id);
            $cust_count = Customer::with('manager')->where('id',$customer_id)->count();
            $total_quantity = 0;
            $tot_quantity = 0;
            $i = 1;
            foreach ($input_data as $product_data) {
                if ($product_data['order_product_all_details']->alias_name != "") {
                    $product = ProductSubCategory::find($product_data['product_category_id']);
                    if ($product_data['unit_id'] == 1) {
                        $total_quantity = (float)$product_data['quantity'];
                        $tot_quantity = $tot_quantity + (float)$product_data['quantity'];
                    }
                    if ($product_data['unit_id'] == 2) {
                        $total_quantity = (float)$product_data['quantity'] * (float)$product->weight;
                        $tot_quantity = $tot_quantity + (float)$product_data['quantity'];
                    }
                    if ($product_data['unit_id'] == 3) {
                        $total_quantity = ((float)$product_data['quantity'] / (float)isset($product->standard_length)?$product->standard_length:1 ) * (float)$product->weight;
                        $tot_quantity = $tot_quantity + (float)$product_data['quantity'];
                    }
                    if ($product_data['unit_id'] == 4) {
                        $total_quantity = ((float)$product_data['quantity'] * (float)(isset($product->weight)?$product->weight:'') * (float)$product_data['length']);
                        $tot_quantity = $tot_quantity + (float)$product_data['quantity'];
                    }
                    if ($product_data['unit_id'] == 5) {
                        $total_quantity = ((float)$product_data['quantity'] * (float)(isset($product->weight)?$product->weight:'') * ((float)$product_data['length'] / 305));
                        $tot_quantity = $tot_quantity + (float)$product_data['quantity'];
                    }
                    $product_string .= $i++ . ") " . $product_data['order_product_details']->alias_name . ", " . round((float)$product_data['quantity'],2) . "KG, ₹". $product_data['price'] . " ";
                }
                /* check for vat/gst items */
                if (isset($product_data['vat_percentage']) && $product_data['vat_percentage'] != '0.00') {
                    $gst_link = 1;
                }
                /**/
            }
            if ($cust_count > 0) {
                $str = "Dear Customer,\n\nYour delivery challan is ready.\n\nCustomer Name: ".ucwords($customer->owner_name)."  \nDelivery Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: " .$vehicle_number. "\nDriver No: " .$driver_number. "\nTotal quantity: ".$tot_quantity."KG\nAmount: ₹".round($allorder->grand_price,0)."\n\nVIKAS ASSOCIATES."; 
                if (App::environment('local')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer->phone_number1;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "true") {
                    $send_msg = new WelcomeController();
                    $send_msg->send_sms($phone_number,$msg);
                }
                $date = new Carbon\Carbon;
                if(isset($gst_link) && $gst_link == 1){
                    DB::table('file_info')->insert(array(
                        'file_name' => $file_name.".pdf",
                        'file_path' => "/upload/invoices/dc/".$file_name.".pdf",
                        'uuid' => $uuid,
                        'status' => 0,
                        'created_at' => $date,
                    ));
                    $link = URL::to("/download_dc/".$uuid);
                    $str = "Dear Customer,\n\nYour delivery challan is ready.\n\nCustomer Name: ".ucwords($customer->owner_name)."  \nDelivery Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: " .$vehicle_number. "\nDriver No: " .$driver_number. "\nTotal quantity: ".$tot_quantity."KG\nAmount: ₹".round($allorder->grand_price,0)."\n\nDownload your Delivery Challan from following link:\n".$link."\n\nVIKAS ASSOCIATES."; 
                    if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                        $send_msg = new WelcomeController();
                        $send_msg->send_whatsapp($phone_number,$str);                    
                    }
                }else{
                    $str = "Dear Customer,\n\nYour delivery challan is ready.\n\nCustomer Name: ".ucwords($customer->owner_name)."  \nDelivery Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: " .$vehicle_number. "\nDriver No: " .$driver_number. "\nTotal quantity: ".$tot_quantity."KG\nAmount: ₹".round($allorder->grand_price,0)."\n\nVIKAS ASSOCIATES."; 
                    if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                        $send_msg = new WelcomeController();
                        $send_msg->send_whatsapp($phone_number,$str);                    
                    }
                }
            }
            if (count((array)$customer['manager']) > 0) {
                $str = "Dear Manager,\n\nDelivery challan is ready.\n\nCustomer Name: ".ucwords($customer->owner_name)."  \nDelivery Challan No: #".$id."\nOrder Date: ".date("j F, Y")."\nProducts:\n".$product_string."\nVehicle No: " .$vehicle_number. "\nDriver No: " .$driver_number. "\nTotal quantity: ".$tot_quantity."KG\nAmount: ₹".round($allorder->grand_price,0)."\n\nVIKAS ASSOCIATES."; 
                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $customer['manager']->mobile_number;
                }
                $msg = urlencode($str);
                if(SEND_SMS === true && isset($send_sms) && $send_sms == "true") {
                    $send_msg = new WelcomeController();
                    $send_msg->send_sms($phone_number,$msg);
                }
                // if(SEND_SMS === true && isset($send_whatsapp) && $send_whatsapp == "true"){
                //     $send_msg = new WelcomeController();
                //     $send_msg->send_whatsapp($phone_number,$str);                    
                // }
            }
        }
        //         update sync table
        $tables = ['delivery_challan', 'all_order_products'];
        $ec = new WelcomeController();
        $ec->set_updated_date_to_sync_table($tables);
        /* end code */

        return view('print_delivery_challan', compact('allorder', 'total_vat_amount'));
    }

    function calc_hsn_wise($update_delivery_challan) {
        $hsn_list = array();
        $hsn_data = array();
        $count = 1;
        $hsn_count[] = 0;
        foreach ($update_delivery_challan['delivery_challan_products'] as $key => $delivery_challan_products) {
            if(isset($delivery_challan_products['order_product_all_details']->hsn_code) && !empty($delivery_challan_products['order_product_all_details']->hsn_code)){
                $temp_var = $delivery_challan_products['order_product_all_details']->hsn_code;
            }else{
                $temp_var = $delivery_challan_products['order_product_all_details']->alias_name;
            }
            if (isset($temp_var)) {
                $hsn_detail = App\Hsn::where('hsn_code',$temp_var)->first();
                $gst = (isset($delivery_challan_products->vat_percentage) && $delivery_challan_products->vat_percentage != '0.00')?$hsn_detail->gst:0;

                if(isset($delivery_challan_products->price) && $delivery_challan_products->price != '0.00'){
                    $price = $delivery_challan_products->price;
                }else{
                    $price = $delivery_challan_products['order_product_all_details']->product_category['price'];
                }
                $amont = (float)$delivery_challan_products->actual_quantity * (float)$price;

                if (in_array($temp_var, $hsn_list)) {
                    foreach ($hsn_data as $key => $value) {
                        if ($temp_var == $value['id']) {

                            $actual_quantity = $value['actual_quantity']+$delivery_challan_products->actual_quantity;
                            $final_amount = $value['amount']+$amont;
                            if(isset($delivery_challan_products['order_product_all_details']['product_category']->product_type_id) && $delivery_challan_products['order_product_all_details']['product_category']->product_type_id==3){
                                if($delivery_challan_products->vat_percentage == 1){
                                    $vat_amount = (float)$value['vat_amount']+(float)($amont * $gst / 100);
                                }
                                else{
                                    $vat_amount = $value['vat_amount'];
                                }
                            }
                            else{
                                $vat_amount = (float)$value['vat_amount']+(float)($amont * $gst / 100);
                            }

                            $hsn_data[$key] = [
                                'id' => $temp_var,
                                'vat_percentage' => (int)$gst,
                                'actual_quantity' => $actual_quantity,
                                'amount' => $final_amount,
                                'vat_amount' => $vat_amount,
                                'count' => $count + 1,
                            ];
                        }
                    }
                } else {
                    $count = 1;
                    if(isset($delivery_challan_products['order_product_all_details']['product_category']->product_type_id) && $delivery_challan_products['order_product_all_details']['product_category']->product_type_id==3){
                        $hsn_list[] = $temp_var;
                        if($delivery_challan_products->vat_percentage == '1.00'){
                            $hsn_data[] = [
                                'id' => $temp_var,
                                'vat_percentage' => (int)$gst,
                                'actual_quantity' => $delivery_challan_products->actual_quantity,
                                'amount' => $amont,
                                'vat_amount' => (float)$amont * (float)$gst / 100,
                                'count' => $count,
                            ];
                        }else{
                            $hsn_data[] = [
                                'id' => $temp_var,
                                'vat_percentage' => 0,
                                'actual_quantity' => $delivery_challan_products->actual_quantity,
                                'amount' => $amont,
                                'vat_amount' => 0,
                                'count' => $count,
                            ];
                        }

                    }else{
                        $hsn_list[] = $temp_var;
                        $hsn_data[] = [
                            'id' => $temp_var,
                            'vat_percentage' => (int)$gst,
                            'actual_quantity' => $delivery_challan_products->actual_quantity,
                            'amount' => $amont,
                            'vat_amount' => (float)$amont * (float)$gst / 100,
                            'count' => $count,
                        ];
                    }
                   
                }
            }
        }

        $update_delivery_challan['hsn'] = $hsn_data;
        return $update_delivery_challan;

    }

    function calc_qty_product_type_wise($update_delivery_challan) {
        $pipe_amount = 0;
        $structure_amount = 0;
        $sheet_amount = 0;
        $pipe_qty = 0;
        $structure_qty = 0;
        $sheet_qty = 0;
        $pipe_vat = 0;
        $structure_vat = 0;
        $sheet_vat = 0;
        $pipe_vat_amount = 0;
        $structure_vat_amount = 0;
        $sheet_vat_amount = 0;

        foreach ($update_delivery_challan['delivery_challan_products'] as $key => $delivery_challan_products) {
            if (isset($delivery_challan_products['order_product_all_details']['product_category']['product_type']->id)) {
                $amont = $delivery_challan_products->actual_quantity * $delivery_challan_products->price;
                if ($delivery_challan_products['order_product_all_details']['product_category']['product_type']->id == 1) {
                    $pipe_vat = $update_delivery_challan->vat_percentage;
                    $pipe_qty += $delivery_challan_products->actual_quantity;
                    $pipe_amount += $delivery_challan_products->actual_quantity * $delivery_challan_products->price;
                    $pipe_vat_amount += $amont * $pipe_vat / 100;
                } else if ($delivery_challan_products['order_product_all_details']['product_category']['product_type']->id == 2) {
                    $structure_vat = $update_delivery_challan->vat_percentage;
                    $structure_qty += $delivery_challan_products->actual_quantity;
                    $structure_amount += $delivery_challan_products->actual_quantity * $delivery_challan_products->price;
                    $structure_vat_amount += $amont * $structure_vat / 100;
                } else if ($delivery_challan_products['order_product_all_details']['product_category']['product_type']->id == 3) {
                    $sheet_vat = $update_delivery_challan->vat_percentage;
                    $sheet_qty += $delivery_challan_products->actual_quantity;
                    $sheet_amount += $delivery_challan_products->actual_quantity * $delivery_challan_products->price;
                    $sheet_vat_amount += $amont * $sheet_vat / 100;
                }
            }
        }

        $update_delivery_challan['pipe_qty'] = $pipe_qty;
        $update_delivery_challan['pipe_amount'] = $pipe_amount;
        $update_delivery_challan['pipe_vat'] = $pipe_vat;
        $update_delivery_challan['pipe_vat_amount'] = $pipe_amount + $pipe_vat_amount;

        $update_delivery_challan['structure_qty'] = $structure_qty;
        $update_delivery_challan['structure_amount'] = $structure_amount;
        $update_delivery_challan['structure_vat'] = $structure_vat;
        $update_delivery_challan['structure_vat_amount'] = $structure_amount + $structure_vat_amount;

        $update_delivery_challan['sheet_qty'] = $sheet_qty;
        $update_delivery_challan['sheet_amount'] = $sheet_amount;
        $update_delivery_challan['sheet_vat'] = $sheet_vat;
        $update_delivery_challan['sheet_vat_amount'] = $sheet_amount + $sheet_vat_amount;

        return $update_delivery_challan;
    }

    /*
     * Convert numbers into words
     */

    function convert_number_to_words($all_orders) {

//      $number = $all_orders->grand_price;
        $number = $all_orders;
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    function convert_number($all_orders) {

//        $number = round($all_orders->grand_price, 2);
//        $number = $all_orders->grand_price;
        $number = $all_orders;
        $exploded_value = explode(".", $number);
        $no = $exploded_value[0];
        $point = $number;

        $result_paisa = $exploded_value[1] % 10;

        if (isset($exploded_value[1]) && strlen($exploded_value[1]) > 1 && $result_paisa != 0) {

//            $number = $all_orders->grand_price;
            $number = $all_orders;
            $no = round($number);
            $point = round($number - $no, 2) * 100;
            $hundred = null;
            $digits_1 = strlen($no);
            $i = 0;
            $str = array();
            $words = array('0' => '', '1' => 'one', '2' => 'two',
                '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                '7' => 'seven', '8' => 'eight', '9' => 'nine',
                '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                '13' => 'thirteen', '14' => 'fourteen',
                '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
                '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                '60' => 'sixty', '70' => 'seventy',
                '80' => 'eighty', '90' => 'ninety');
            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
            while ($i < $digits_1) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += ($divider == 10) ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count((array)$str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number] .
                            " " . $digits[$counter] . $plural . " " . $hundred :
                            $words[floor($number / 10) * 10]
                            . " " . $words[$number % 10] . " "
                            . $digits[$counter] . $plural . " " . $hundred;
                } else
                    $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);
            $points = ($point) ?
                    "." . $words[$point / 10] . " " .
                    $words[$point = $point % 10] : '';
            return $result . "Rupees  " . $points . " Paise";
        } else {

//            $number = $all_orders->grand_price;
            $number = $all_orders;
            $exploded_value = explode(".", $number);
            $no = $exploded_value[0];
            $point = $exploded_value[1];
            $hundred = null;
            $digits_1 = strlen($exploded_value[0]);
            $i = 0;
            $str = array();
            $words = array('0' => '', '1' => 'one', '2' => 'two',
                '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                '7' => 'seven', '8' => 'eight', '9' => 'nine',
                '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                '13' => 'thirteen', '14' => 'fourteen',
                '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
                '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                '60' => 'sixty', '70' => 'seventy',
                '80' => 'eighty', '90' => 'ninety');
            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
            while ($i < $digits_1) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);

                $no = floor($no / $divider);
                $i += ($divider == 10) ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count((array)$str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number] .
                            " " . $digits[$counter] . $plural . " " . $hundred :
                            $words[floor($number / 10) * 10]
                            . " " . $words[$number % 10] . " "
                            . $digits[$counter] . $plural . " " . $hundred;
                } else
                    $str[] = null;
            }
            $str = array_reverse($str);
            $result = implode('', $str);

            if ($point != 0) {
                if (strlen($point) == 1)
                    $points = $words[$point * 10];
                else
                    $points = $point; //$points = $words[$point];
                $strs = preg_replace('/\W\w+\s*(\W*)$/', '$1', $result);

                if ($point % 10 == 0) {
                    $convert_value = ucfirst($result . " point " . $words[$points]);
                } else {
                    $points = ($point) ? ($words[$point / 10] . " " . $words[$point = $point % 10]) : '';
                    $convert_value = ucfirst($result . " rupees and " . $points . " paise");
                }
            } else
                $convert_value = ucfirst($result);
            return $convert_value;
        }
    }

    /*
     * Find total pending quantity
     */

    function checkpending_quantity() {
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

    /* Function used to export delivery challan  list based on order status */

    // public function exportDeliveryChallanBasedOnStatus() {
    //     $data = Input::all();
    //     set_time_limit(0);
    //     ini_set('max_execution_time', 1000);
    //     if ($data['delivery_order_status'] == 'pending') {
    //         $delivery_order_status = 'pending';
    //         $excel_sheet_name = 'Pending';
    //         $excel_name = 'DeliveryChallan-InProgress-' . date('dmyhis');

    //         if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
    //         $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
    //         $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
    //         if ($date1 == $date2) {
    //             $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
    //                     ->where('updated_at', 'like', $date1 . '%')
    //                     ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
    //                     ->orderBy('updated_at', 'desc')
    //                     ->get();
    //         } else {
    //             $delivery_challan_objects = DeliveryChallan::where('challan_status',$delivery_order_status)
    //                     ->where('updated_at', '>=', $date1)
    //                     ->where('updated_at', '<=', $date2 . ' 23:59:59')
    //                     ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
    //                     ->orderBy('updated_at', 'desc')
    //                     ->get();
    //             }
    //         } else {
    //             $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
    //                 ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')->orderBy('updated_at', 'desc')->get();
    //         }

    //     } elseif ($data['delivery_order_status'] == 'completed') {
    //         $delivery_order_status = 'completed';
    //         $excel_sheet_name = 'Completed';
    //         $excel_name = 'DeliveryChallan-Completed-' . date('dmyhis');

    //         if (isset($data["export_from_date"]) && isset($data["export_to_date"]) && !empty($data["export_from_date"]) && !empty($data["export_to_date"])) {
    //         $date1 = \DateTime::createFromFormat('m-d-Y', $data["export_from_date"])->format('Y-m-d');
    //         $date2 = \DateTime::createFromFormat('m-d-Y', $data["export_to_date"])->format('Y-m-d');
    //         if ($date1 == $date2) {
    //             $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
    //                     ->where('serial_number', 'like', '%P')
    //                     ->where('updated_at', 'like', $date1 . '%')
    //                     ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
    //                     ->orderBy('updated_at', 'desc')
    //                     ->get();
    //         } else {
    //             $delivery_challan_objects = DeliveryChallan::where('challan_status',$delivery_order_status)
    //                     ->where('serial_number', 'like', '%P')
    //                     ->where('updated_at', '>=', $date1)
    //                     ->where('updated_at', '<=', $date2 . ' 23:59:59')
    //                     ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')
    //                     ->orderBy('updated_at', 'desc')
    //                     ->get();
    //         }
    //         } else {
    //             $delivery_challan_objects = DeliveryChallan::where('challan_status', $delivery_order_status)
    //                 ->where('serial_number', 'like', '%P')
    //                 ->with('all_order_products.unit', 'all_order_products.order_product_details', 'customer', 'delivery_order', 'delivery_order.user', 'user', 'order_details', 'order_details.createdby')->orderBy('updated_at', 'desc')->get();
    //         }
    //     }


    //     if (count((array)$delivery_challan_objects) == 0) {
    //         return redirect::back()->with('flash_message', 'No data found');
    //     } else {
    //         Excel::store($excel_name, function($excel) use($delivery_challan_objects, $excel_sheet_name) {
    //             $excel->sheet('DeliveryChallan-' . $excel_sheet_name, function($sheet) use($delivery_challan_objects, $excel_sheet_name) {
    //                 $sheet->loadView('excelView.delivery_challan', array('delivery_challan_objects' => $delivery_challan_objects));
    //             });
    //         })->export('xls');
    //         $sheet->loadView('excelView.delivery_challan', array('delivery_challan_objects' => $delivery_challan_objects));

    //     }
    // }

    public function exportDeliveryChallanBasedOnStatus() {
        $data = Input::all();
        if ($data['delivery_order_status'] == 'pending') {
            $excel_name = '-Pending-' . date('dmyhis');
        } elseif ($data['delivery_order_status'] == 'completed') {
            $excel_name = '-Completed-' . date('dmyhis');
        }
        return Excel::download(new DCExport, 'DeliveryChallan'.$excel_name.'.xls');
    }

    function checkpending_quantity1($delivery_orders) {

        if (count((array)$delivery_orders) > 0) {
            foreach ($delivery_orders as $key => $del_order) {
                $delivery_order_quantity = 0;
                $delivery_order_present_shipping = 0;
                $pending_order_temp = 0;
                $pending_order = 0;
                if (count((array)$del_order['delivery_product']) > 0) {
                    foreach ($del_order['delivery_product'] as $popk => $popv) {
                        $product_size = ProductSubCategory::find($popv->product_category_id);
                        if ($popv->unit_id == 1) {
                            $delivery_order_quantity = $delivery_order_quantity + $popv->quantity;
                            $delivery_order_present_shipping = $delivery_order_present_shipping + $popv->present_shipping;

                            $do = DeliveryOrder::find($popv->order_id);
                            $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();

                            $pending_order_temp = $prd_details[0]->quantity - $popv->quantity;
                            if ($pending_order == 0) {
                                $pending_order = $pending_order_temp;
                            } else {
                                $pending_order = $pending_order + $pending_order_temp;
                            }
                        } elseif ($popv->unit_id == 2) {
                            $delivery_order_quantity = $delivery_order_quantity + ($popv->quantity * $product_size->weight);
                            $delivery_order_present_shipping = $delivery_order_present_shipping + ($popv->present_shipping * $product_size->weight);

                            $do = DeliveryOrder::find($popv->order_id);
                            $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();

                            if ($prd_details[0]->quantity > $popv->quantity)
                                $remaining = $prd_details[0]->quantity - $popv->quantity;
                            else
                                $remaining = 0;

                            $pending_order_temp = ($remaining * $product_size->weight);

                            if ($pending_order == 0) {
                                $pending_order = $pending_order_temp;
                            } else {
                                $pending_order = $pending_order + $pending_order_temp;
                            }
                        } elseif ($popv->unit_id == 3) {

                            $delivery_order_quantity = $delivery_order_quantity + (($popv->quantity / isset($product_size->standard_length)?$product_size->standard_length:1 ) * $product_size->weight);
                            $delivery_order_present_shipping = $delivery_order_present_shipping + (($popv->present_shipping / isset($product_size->standard_length)?$product_size->standard_length:1 ) * $product_size->weight);

                            $do = DeliveryOrder::find($popv->order_id);

                            $prd_details = AllOrderProducts::where('order_id', '=', $do->order_id)->where('order_type', '=', 'order')->where('product_category_id', '=', $popv->product_category_id)->get();

                            if ($prd_details[0]->quantity > $popv->quantity)
                                $remaining = $prd_details[0]->quantity - $popv->quantity;
                            else
                                $remaining = 0;
                            $pending_order_temp = (($remaining / isset($product_size->standard_length)?$product_size->standard_length:1 ) * $product_size->weight);

                            if ($pending_order == 0) {
                                $pending_order = $pending_order_temp;
                            } else {
                                $pending_order = $pending_order + $pending_order_temp;
                            }
                        }
                    }
                }
                $delivery_orders[$key]['total_quantity'] = $delivery_order_quantity;
                $delivery_orders[$key]['present_shipping'] = $delivery_order_present_shipping;
                $delivery_orders[$key]['pending_order'] = $pending_order;
            }
        }

//        echo "<pre>";
//        print_r($delivery_orders->toArray());
//        echo "</pre>";
//        exit;
//
//        exit;
        return $delivery_orders;
    }

}
