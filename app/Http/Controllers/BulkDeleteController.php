<?php

namespace App\Http\Controllers;

use App\Http\Requests;
//use App\Http\Controllers\Redirect;
use App\Http\Controllers\Controller;
use App\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Input;
use App\Inquiry;
use App\Order;
use App\User;
use App\DeliveryOrder;
use App\DeliveryLocation;
use App\ProductSubCategory;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseProducts;
use App\PurchaseChallan;
use App\PurchaseAdvise;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Auth;
use Auth;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class BulkDeleteController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 10 ) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        return view('bulk_delete');
    }

    public function show_result() {

        $result_temp = [];
        $head = [];
        $tr_id = [];
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 10 ) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $module = Input::get('select_module');
        $password = Input::get('password_delete');
        $password_delete_all = Input::get('password_delete_all');
        $expected_date = Input::get('expected_date');
        $is_delete_all = Input::get('is_delete_all');
        $delete_seletected_module = Input::get('delete_seletected_module');
        if (Input::has('select_module') && (Input::get('select_module') == "0" || Input::get('select_module') == "")) {
            return redirect('bulk-delete')->with('flash_message_error', 'You have not selected any module.');
        }
        if($is_delete_all=='yes'){            
            if (!Hash::check($password_delete_all, Auth::user()->password)) {
                return back()->with('flash_message_error', 'You have entered wrong password. Please provide correct password.');
            }
        }else if (count((array)$delete_seletected_module) > 0) {
            if (!Hash::check($password, Auth::user()->password)) {
                return back()->with('flash_message_error', 'You have entered wrong password. Please provide correct password.');
            }
        }
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $result_data = [];

        switch ($module) {
//----------------------------------------------------           
            case 'inquiry-completed':
                $head[0] = 'TALLY NAME';
                $head[1] = 'TOTAL QUANTITY';
                $head[2] = 'PHONE NUMBER';
                $head[3] = 'DELIVERY LOCATION';
                /*
                 * Delete selected inquiries.
                 */
                //$newdate = @Input::get('expected_date');
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');

                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = Inquiry::where('inquiry_status','completed')->whereDate('created_at', '=', $newdate)->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        Inquiry::find($delete_module)->delete();
                        $delete = Inquiry::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }                
                /*
                 * Delete selected inquiries end.
                 */
                $q = Inquiry::query()->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details','inquiry_products.unit');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('inquiry_status', 'completed');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $qty = 0;
                    
                    foreach ($temp['inquiry_products'] as $prod) {
                        if (@$prod['unit']->unit_name == 'KG') {
                            $qty += $prod->quantity;
                        } elseif (@$prod['unit']->unit_name == 'Pieces') {
                            $qty += $prod->quantity * $prod['inquiry_product_details']->weight;
                        } elseif (@$prod['unit']->unit_name == 'Meter') {
                            $qty += ($prod->quantity / $prod['inquiry_product_details']->standard_length) * $prod['inquiry_product_details']->weight;
                        }
                    }
                    if (isset($temp['customer']->tally_name) && $temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    elseif (isset($temp['customer']->owner_name))
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $result_data[$key][1] = '';
                    if (isset($temp['inquiry_products'][0]))
                        $result_data[$key][1] = round($qty, 2);
                    if (isset($temp['customer']->phone_number1)) {
                        $result_data[$key][2] = $temp['customer']->phone_number1;
                    }

                    $result_data[$key][3] = '';
                    if (isset($temp['delivery_location']))
                        $result_data[$key][3] = $temp['delivery_location']->area_name;
                }
                break;

            case 'inquiry-pending':
                $head[0] = 'TALLY NAME';
                $head[1] = 'TOTAL QUANTITY';
                $head[2] = 'PHONE NUMBER';
                $head[3] = 'DELIVERY LOCATION';
                /*
                 * Delete selected inquiries.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');

                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = Inquiry::where('inquiry_status','pending')->whereDate('created_at', '=', $newdate)->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        Inquiry::find($delete_module)->delete();
                        $delete = Inquiry::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }                
                /*
                 * Delete selected inquiries end.
                 */
                 $q = Inquiry::query()->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details','inquiry_products.unit');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('inquiry_status', 'pending');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $qty = 0;
                    foreach ($temp['inquiry_products'] as $prod) {
                        if (@$prod['unit']->unit_name == 'KG') {
                            $qty += $prod->quantity;
                        } elseif (@$prod['unit']->unit_name == 'Pieces') {
                            $qty += $prod->quantity * $prod['inquiry_product_details']->weight;
                        } elseif (@$prod['unit']->unit_name == 'Meter') {
                            $qty += ($prod->quantity / $prod['inquiry_product_details']->standard_length) * $prod['inquiry_product_details']->weight;
                        }
                    }
                    if (isset($temp['customer']->tally_name) && $temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    elseif (isset($temp['customer']->owner_name))
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    else
                        $result_data[$key][0] = "";
                    $result_data[$key][1] = '';
                    if (isset($temp['inquiry_products'][0]))
                        $result_data[$key][1] = round($qty, 2);
                    if (isset($temp['customer']->phone_number1))
                        $result_data[$key][2] = $temp['customer']->phone_number1;
                    else
                        $result_data[$key][2] = "";
                    $result_data[$key][3] = '';
                    if (isset($temp['delivery_location']))
                        $result_data[$key][3] = $temp['delivery_location']->area_name;
                }
                break;
//----------------------------------------------------
            case 'order-pending':
                $head[0] = 'TALLY NAME';
                $head[1] = 'TOTAL QUANTITY';
                $head[2] = 'MOBILE';
                $head[3] = 'DELIVERY LOCATION';
                $head[4] = 'ORDER BY';
                /*
                 * Delete selected orders.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');

                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = Order::where('order_status','pending')                                
                                ->whereDate('created_at','=', $newdate)->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        Order::find($delete_module)->delete();
                        $delete = Order::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected orders end.
                 */                
                    $q = Order::query()->with('all_order_products','customer','delivery_location','order_cancelled');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status', 'pending');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $total_size_quantity = 0;
                    foreach ($temp->all_order_products as $order_product_array) {
                        $total_size_quantity+=$order_product_array->quantity;
                    }
                    $result_data[$key][1] = round($total_size_quantity, 2);
                    $result_data[$key][2] = $temp['customer']->phone_number1;
                    if ($temp->delivery_location_id != 0)
                        $result_data[$key][3] = $temp['delivery_location']->area_name;
                    elseif ($temp->delivery_location_id == 0)
                        $result_data[$key][3] = $temp['other_location'];
                    $users = User::all();
                    foreach ($users as $u) {
                        if ($u['id'] == $temp['created_by']) {
                            $result_data[$key][4] = $u['first_name'];
                        }
                    }
                }
                break;
            case 'order-completed':
                $head[0] = 'TALLY NAME';
                $head[1] = 'TOTAL QUANTITY';
                $head[2] = 'MOBILE';
                $head[3] = 'DELIVERY LOCATION';
                $head[4] = 'ORDER BY';
                /*
                 * Delete selected orders.
                 */
                
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');

                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = Order::where('order_status','completed')->whereDate('created_at', '=', $newdate)->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        Order::find($delete_module)->delete();
                        $delete = Order::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected orders end.
                 */                

                $q = Order::query()->with('all_order_products','customer','delivery_location','order_cancelled');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status', 'completed');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);


                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if (isset($temp['customer']->tally_name) && $temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = isset($temp['customer']->owner_name) ? $temp['customer']->owner_name : '';
                    $total_size_quantity = 0;
                    foreach ($temp->all_order_products as $order_product_array) {
                        $total_size_quantity+=$order_product_array->quantity;
                    }
                    $result_data[$key][1] = round($total_size_quantity, 2);
                    if (isset($temp['customer']->phone_number1)) {
                        $result_data[$key][2] = $temp['customer']->phone_number1;
                    } else {
                        $result_data[$key][2] = 0;
                    }

                    if ($temp->delivery_location_id != 0)
                        $result_data[$key][3] = $temp['delivery_location']->area_name;
                    elseif ($temp->delivery_location_id == 0)
                        $result_data[$key][3] = $temp['other_location'];
                    $users = User::all();
                    foreach ($users as $u) {
                        if ($u['id'] == $temp['created_by']) {
                            $result_data[$key][4] = $u['first_name'];
                        }
                    }
                }
                break;
//----------------------------------------------------                
            case 'delivery_order_completed':
                $head[0] = 'DATE';
                $head[1] = 'TALLY NAME';
                $head[2] = 'DELIVERY LOCATION';
                $head[3] = 'QUANTITY';
                $head[4] = 'PRESENT SHIPPING';
                $head[5] = 'VEHICLE NUMBER';
                /*
                 * Delete selected delivery orders.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');

                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = DeliveryOrder::whereDate('created_at', '=', $newdate)
                                ->where('order_status', 'completed')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        DeliveryOrder::find($delete_module)->delete();
                        $delete = DeliveryOrder::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected delivery orders end.
                 */                
                
                 $q = DeliveryOrder::query()->with('delivery_product', 'customer');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status', 'completed');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                $result_temp = $this->checkpending_quantity($result_temp);
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $result_data[$key][0] = date("F jS, Y", strtotime($temp->created_at));
                   if (@$temp['customer']->tally_name != '')
                        $result_data[$key][1] = $temp['customer']->tally_name;
                    elseif(@$temp['customer']->owner_name != '')
                        $result_data[$key][1] = $temp['customer']->owner_name;
                    else
                        $result_data[$key][1] ='Anonymous customer';
                    if ($temp->delivery_location_id != 0) {
                        foreach ($delivery_locations as $location) {
                            if ($location->id == $temp->delivery_location_id) {
                                $result_data[$key][2] = $location->area_name;
                            }
                        }
                    } else {
                        $result_data[$key][2] = $temp->other_location;
                    }
                    $result_data[$key][3] = round($temp->total_quantity, 2);
                    $result_data[$key][4] = round($temp->present_shipping, 2);
                    $result_data[$key][5] = $temp->vehicle_number;
                }
                break;
            case 'delivery_order_pending':
                $head[0] = 'DATE';
                $head[1] = 'TALLY NAME';
                $head[2] = 'DELIVERY LOCATION';
                $head[3] = 'QUANTITY';
                $head[4] = 'PRESENT SHIPPING';
                $head[5] = 'VEHICLE NUMBER';
                /*
                 * Delete selected delivery orders.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
               
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = DeliveryOrder::whereDate('created_at', '=', $newdate)
                                ->where('order_status', 'pending')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        DeliveryOrder::find($delete_module)->delete();
                        $delete = DeliveryOrder::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected delivery orders end.
                 */           
                $q = DeliveryOrder::query()->with('delivery_product', 'customer');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status', 'pending');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);
                
                $result_temp = $this->checkpending_quantity($result_temp);
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $result_data[$key][0] = date("F jS, Y", strtotime($temp->created_at));
                    if (@$temp['customer']->tally_name != '')
                        $result_data[$key][1] = $temp['customer']->tally_name;
                    elseif(@$temp['customer']->owner_name != '')
                        $result_data[$key][1] = $temp['customer']->owner_name;
                    else
                        $result_data[$key][1] ='Anonymous customer';
                    if ($temp->delivery_location_id != 0) {
                        foreach ($delivery_locations as $location) {
                            if ($location->id == $temp->delivery_location_id) {
                                $result_data[$key][2] = $location->area_name;
                            }
                        }
                    } else {
                        $result_data[$key][2] = $temp->other_location;
                    }
                    $result_data[$key][3] = round($temp->total_quantity, 2);
                    $result_data[$key][4] = round($temp->present_shipping, 2);
                    $result_data[$key][5] = $temp->vehicle_number;
                }
                break;
//----------------------------------------------------            
            case 'delivery_challan_completed':
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'PRESENT SHIPPING';
                /*
                 * Delete selected delivery challan.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');

                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = DeliveryChallan::where('challan_status','completed')
                                ->where('serial_number','like','%P')
                                ->whereDate('created_at', '=', $newdate)->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
//                        DeliveryChallan::find($delete_module)->delete();
                        $delete = DeliveryChallan::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected delivery challan end.
                 */                
               
                $q = DeliveryChallan::query()->with('customer', 'delivery_challan_products', 'delivery_order');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('challan_status', 'completed');
                    $q->where('serial_number','like','%P');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                $present_shipping = 0;
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if (isset($temp['customer']->tally_name)) {
                        if ($temp['customer']->tally_name != '')
                            $result_data[$key][0] = $temp['customer']->tally_name;
                        else
                            $result_data[$key][0] = $temp['customer']->owner_name;
                    }
                    else {
                        $result_data[$key][0] = "Anonymous User";
                    }
                    $result_data[$key][1] = $temp->serial_number;
                    foreach ($temp['delivery_challan_products'] as $delivery_challan_products) {
                        $present_shipping = $present_shipping + round($delivery_challan_products->present_shipping, 2);
                    }

                    $result_data[$key][2] = $present_shipping;
                    $present_shipping = 0;
                }


                break;
            case 'all_inclusive_completed':
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'PRESENT SHIPPING';
                /*
                 * Delete selected delivery challan.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = DeliveryChallan::where('challan_status','completed')
                                ->whereDate('created_at', '=', $newdate)
                                ->where('serial_number','like','%A')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = DeliveryChallan::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }

                 /* Delete selected delivery challan end. */
             
                $q = DeliveryChallan::query()->with('customer', 'delivery_challan_products', 'delivery_order');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('serial_number','like','%A');
                    $q->where('challan_status','completed');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                $present_shipping = 0;
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if (isset($temp['customer']->tally_name)) {
                        if ($temp['customer']->tally_name != '')
                            $result_data[$key][0] = $temp['customer']->tally_name;
                        else
                            $result_data[$key][0] = $temp['customer']->owner_name;
                    }
                    else {
                        $result_data[$key][0] = "Anonymous User";
                    }
                    $result_data[$key][1] = $temp->serial_number;
                    foreach ($temp['delivery_challan_products'] as $delivery_challan_products) {
                        $present_shipping = $present_shipping + round($delivery_challan_products->present_shipping, 2);
                    }

                    $result_data[$key][2] = $present_shipping;
                    $present_shipping = 0;
                }


                break;

            case 'delivery_challan_pending':
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'PRESENT SHIPPING';
                /*
                 * Delete selected delivery challan.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = DeliveryChallan::where('challan_status','pending')
                                ->whereDate('created_at', '=', $newdate)->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = DeliveryChallan::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected delivery challan end.
                 */                
                $q = DeliveryChallan::query()->with('customer', 'delivery_challan_products', 'delivery_order');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('challan_status','pending');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                $present_shipping = 0;
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                         
                    if (isset($temp['customer']->tally_name)&&$temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = isset($temp['customer']->owner_name)?$temp['customer']->owner_name:'';
                    $result_data[$key][1] = $temp->serial_number;

                    foreach ($temp['delivery_challan_products'] as $delivery_challan_products) {
                        $present_shipping = $present_shipping + round($delivery_challan_products->present_shipping, 2);
                    }

                    $result_data[$key][2] = $present_shipping;
                    $present_shipping = 0;
                }
                break;
//----------------------------------------------------
            case 'purchase_orders_completed':
                $head[0] = 'SUPPLIER NAME';
                $head[1] = 'MOBILE';
                $head[2] = 'DELIVERY LOCATION';
                $head[3] = 'ORDER BY';
                $head[4] = 'TOTAL QUANTITY';
                $head[5] = 'PENDING QUANTITY';
                /*
                 * Delete selected purchase orders.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = PurchaseOrder::whereDate('created_at','=', $newdate)
                    ->where('order_status','completed')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = PurchaseOrder::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected purchase orders end.
                 */                
                
                $q = PurchaseOrder::query()->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status','completed');
                    $purchase_orders =$q->orderBy('created_at', 'desc')->paginate(50);
                                    
                $result_temp = $this->quantity_calculation($purchase_orders);
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $result_data[$key][1] = $temp['customer']->phone_number1;
                    if ($temp->delivery_location_id != 0) {
                        foreach ($delivery_locations as $location) {
                            if ($location->id == $temp->delivery_location_id) {
                                $result_data[$key][2] = $location->area_name;
                            }
                        }
                    } else {
                        $result_data[$key][2] = $temp->other_location;
                    }
                    $result_data[$key][3] = $temp['user']->first_name;
                    $result_data[$key][4] = round($temp->total_quantity, 2);
                    $result_data[$key][5] = round($temp->pending_quantity, 2);
                }

                break;

            case 'purchase_orders_pending':
                $head[0] = 'SUPPLIER NAME';
                $head[1] = 'MOBILE';
                $head[2] = 'DELIVERY LOCATION';
                $head[3] = 'ORDER BY';
                $head[4] = 'TOTAL QUANTITY';
                $head[5] = 'PENDING QUANTITY';
                /*
                 * Delete selected purchase orders.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = PurchaseOrder::whereDate('created_at','=',$newdate)
                    ->where('order_status','pending')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = PurchaseOrder::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected purchase orders end.
                 */                
                $q = PurchaseOrder::query()->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status','pending');
                    $purchase_orders =$q->orderBy('created_at', 'desc')->paginate(50);

                $result_temp = $this->quantity_calculation($purchase_orders);
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['customer']->tally_name != '')
                        $result_data[$key][0] = $temp['customer']->tally_name;
                    else
                        $result_data[$key][0] = $temp['customer']->owner_name;
                    $result_data[$key][1] = $temp['customer']->phone_number1;
                    if ($temp->delivery_location_id != 0) {
                        foreach ($delivery_locations as $location) {
                            if ($location->id == $temp->delivery_location_id) {
                                $result_data[$key][2] = $location->area_name;
                            }
                        }
                    } else {
                        $result_data[$key][2] = $temp->other_location;
                    }
                    $result_data[$key][3] = isset($temp['user']->first_name)?$temp['user']->first_name:'';
                    $result_data[$key][4] = round($temp->total_quantity, 2);
                    $result_data[$key][5] = round($temp->pending_quantity, 2);
                }

                break;
//----------------------------------------------------            
            case 'purchase_advice_completed':
                $head[0] = 'DATE';
                $head[1] = 'TALLY NAME';
                $head[2] = 'VECHILE NUMBER';
                $head[3] = 'QUANTITY';
                $head[4] = 'SERIAL NUMBER';
                /*
                 * Delete selected purchase advice.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = PurchaseAdvise::whereDate('created_at','=',$newdate)
                                ->where('advice_status','delivered')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = PurchaseAdvise::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected purchase advice end.
                 */                
                $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('advice_status','delivered');
                    $purchase_advise =$q->orderBy('created_at', 'desc')->paginate(50);

                $result_temp = $this->checkpending_quantity($purchase_advise);
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $result_data[$key][0] = date("F jS, Y", strtotime($temp->purchase_advice_date));
                    if ($temp['supplier']->tally_name != '')
                        $result_data[$key][1] = $temp['supplier']->tally_name;
                    else
                        $result_data[$key][1] = $temp['supplier']->owner_name;
                    $result_data[$key][2] = $temp->vehicle_number;
                    $result_data[$key][3] = $temp['total_quantity'];
                    $result_data[$key][4] = $temp->serial_number;
                }

                break;

            case 'purchase_advice_pending':
                $head[0] = 'DATE';
                $head[1] = 'TALLY NAME';
                $head[2] = 'VECHILE NUMBER';
                $head[3] = 'QUANTITY';
                $head[4] = 'SERIAL NUMBER';
                /*
                 * Delete selected purchase advice.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = PurchaseAdvise::whereDate('created_at', '=', $newdate)
                                ->where('advice_status','in_process')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = PurchaseAdvise::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected purchase advice end.
                 */
                $q = PurchaseAdvise::query()->with('supplier', 'purchase_products');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('advice_status','in_process');
                    $purchase_advise =$q->orderBy('created_at', 'desc')->paginate(50);

                $result_temp = $this->checkpending_quantity($purchase_advise);
                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    $result_data[$key][0] = date("F jS, Y", strtotime($temp->purchase_advice_date));
                    if ($temp['supplier']->tally_name != '')
                        $result_data[$key][1] = $temp['supplier']->tally_name;
                    else
                        $result_data[$key][1] = $temp['supplier']->owner_name;
                    $result_data[$key][2] = $temp->vehicle_number;
                    $result_data[$key][3] = $temp['total_quantity'];
                    $result_data[$key][4] = $temp->serial_number;
                }

                break;
//----------------------------------------------------            
            case 'purchase_challan_completed':
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'BILL NUMBER';
                $head[3] = 'BILL DATE';
                $head[4] = 'TOTAL QUANTITY';
                /*
                 * Delete selected purchase challan.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = PurchaseChallan::whereDate('created_at','=', $newdate)
                    ->where('order_status','completed')->delete();
                }else if (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = PurchaseChallan::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected purchase challan end.
                 */                
                $q = PurchaseChallan::query()->with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details');
                    if(isset($newdate))
                    $q->whereDate('created_at', '', $newdate);
                    $q->where('order_status','completed');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['supplier']->tally_name != '')
                        $result_data[$key][0] = $temp['supplier']->tally_name;
                    else
                        $result_data[$key][0] = $temp['supplier']->owner_name;

                    $result_data[$key][1] = $temp->serial_number;
                    $result_data[$key][2] = $temp->bill_number;  
                    if(isset($temp['purchase_advice']) && count((array)$temp['purchase_advice'])>0){
                        $result_data[$key][3] = date("F jS, Y", strtotime($temp['purchase_advice']->purchase_advice_date));
                    }else{
                        $result_data[$key][3]="-";
                    }
                    $total_qty = 0;

                    foreach ($temp['all_purchase_products'] as $pc) {
                        if ($pc->unit_id == 1) {
                            $total_qty += $pc->quantity;
                        }
                        if ($pc->unit_id == 2) {
                            $total_qty += ($pc->quantity * $pc['purchase_product_details']->weight);
                        }
                        if ($pc->unit_id == 3) {
                            $total_qty += (($pc->quantity / $pc['purchase_product_details']->standard_length ) * $pc['purchase_product_details']->weight);
                        }
                    }
                    $result_data[$key][4] = round($temp['all_purchase_products']->sum('quantity'), 2);
                }
                break;

            case 'purchase_challan_pending':
                $head[0] = 'TALLY NAME';
                $head[1] = 'SERIAL NUMBER';
                $head[2] = 'BILL NUMBER';
                $head[3] = 'BILL DATE';
                $head[4] = 'TOTAL QUANTITY';
                /*
                 * Delete selected purchase challan.
                 */
                $newdate = Carbon::createFromFormat('d/m/Y', @Input::get('expected_date'))->format('Y-m-d');
                if (isset($is_delete_all) && !empty($is_delete_all) && $is_delete_all == 'yes') {
                    $result_temp = PurchaseChallan::whereDate('created_at','=', $newdate)
                        ->where('order_status','pending')->delete();
                }elseif (isset($delete_seletected_module) && !empty($delete_seletected_module)) {
                    foreach ($delete_seletected_module as $delete_module) {
                        $delete = PurchaseChallan::where('id', $delete_module)->first();
                        if ($delete != null) {
                            $delete->delete();
                        }
                    }
                }
                /*
                 * Delete selected purchase challan end.
                 */                
                $q = PurchaseChallan::query()->with('purchase_advice', 'supplier', 'all_purchase_products.purchase_product_details');
                    if(isset($newdate))
                    $q->whereDate('created_at', '=', $newdate);
                    $q->where('order_status','pending');
                    $result_temp =$q->orderBy('created_at', 'desc')->paginate(50);

                foreach ($result_temp as $key => $temp) {
                    $tr_id[$key] = $temp->id;
                    if ($temp['supplier']->tally_name != '')
                        $result_data[$key][0] = $temp['supplier']->tally_name;
                    else
                        $result_data[$key][0] = $temp['supplier']->owner_name;

                    $result_data[$key][1] = $temp->serial_number;
                    $result_data[$key][2] = $temp->bill_number;
                    $result_data[$key][3] = date("F jS, Y", strtotime($temp['purchase_advice']->purchase_advice_date));
                    $total_qty = 0;

                    foreach ($temp['all_purchase_products'] as $pc) {
                        if ($pc->unit_id == 1) {
                            $total_qty += $pc->quantity;
                        }
                        if ($pc->unit_id == 2) {
                            $total_qty += ($pc->quantity * $pc['purchase_product_details']->weight);
                        }
                        if ($pc->unit_id == 3) {
                            $total_qty += (($pc->quantity / $pc['purchase_product_details']->standard_length ) * $pc['purchase_product_details']->weight);
                        }
                    }
                    $result_data[$key][4] = round($temp['all_purchase_products']->sum('quantity'), 2);
                }
                break;
//----------------------------------------------------            
        }
        $msg = '';
        if (($module != '') && (count((array)$delete_seletected_module) > 0) || $is_delete_all == 'yes') {
            $msg = $module . " - Selected details removed successfully.";
        } else {
            $msg = '';
        }
        if (isset($result_temp) && !empty($result_temp)) {
            $result_temp->setPath('bulk-delete');
        }
        $bulk_searched_result = 'bulk_searched_result';
        if (count((array)$delete_seletected_module) > 0) {
            return redirect('bulk-delete?select_module=' . $module . '&expected_date=' . $expected_date)->with('msg', $msg);
        } else {
            return view('bulk_delete', compact('result_data', 'result_temp', 'bulk_searched_result', 'head', 'module', 'expected_date', 'tr_id', 'msg'));
        }
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

    /*
     * calculate the pending quantity and total quantity
     */

    function checkpending_quantity($delivery_orders) {

        if (count((array)$delivery_orders) > 0) {
            foreach ($delivery_orders as $key => $del_order) {
                $delivery_order_quantity = 0;
                $delivery_order_present_shipping = 0;
                if (count((array)$del_order['delivery_product']) > 0) {
                    foreach ($del_order['delivery_product'] as $popk => $popv) {
                        if (isset($popv['product_sub_category']) && count((array)$popv['product_sub_category'])) {
                            $product_size = $popv['product_sub_category'];
                        } else {
                            $product_size = ProductSubCategory::find($popv->product_category_id);
                        }

                        if ($popv->unit_id == 1) {
                            $delivery_order_quantity = $delivery_order_quantity + $popv->quantity;
                            $delivery_order_present_shipping = $delivery_order_present_shipping + $popv->present_shipping;
                        } elseif ($popv->unit_id == 2) {
                            $delivery_order_quantity = $delivery_order_quantity + ($popv->quantity * $product_size->weight);
                            $delivery_order_present_shipping = $delivery_order_present_shipping + ($popv->present_shipping * $product_size->weight);
                        } elseif ($popv->unit_id == 3) {
                            if ($product_size->standard_length) {
                                $delivery_order_quantity = $delivery_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                                $delivery_order_present_shipping = $delivery_order_present_shipping + (($popv->present_shipping / $product_size->standard_length ) * $product_size->weight);
                            }
                        }
                    }
                }
                $delivery_orders[$key]['total_quantity'] = $delivery_order_quantity;
                $delivery_orders[$key]['present_shipping'] = $delivery_order_present_shipping;
            }
        }
        return $delivery_orders;
    }

    function quantity_calculation($purchase_orders) {

        foreach ($purchase_orders as $key => $order) {
            $purchase_order_quantity = 0;
            $purchase_order_advise_quantity = 0;
            $purchase_order_advise_products = PurchaseProducts::with('product_sub_category')->where('from', '=', $order->id)->get();
            if (count((array)$purchase_order_advise_products) > 0) {
                foreach ($purchase_order_advise_products as $poapk => $poapv) {
                    if (isset($poapv['product_sub_category']) && count((array)$poapv['product_sub_category'])) {
                        $product_size = $poapv['product_sub_category'];
                    } else {
                        $product_size = ProductSubCategory::find($poapv->product_category_id);
                    }
                    if ($poapv->unit_id == 1) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity;
                    } elseif ($poapv->unit_id == 2) {
                        $purchase_order_advise_quantity = $purchase_order_advise_quantity + $poapv->quantity * $product_size->weight;
                    } elseif ($poapv->unit_id == 3) {
                        if ($product_size->standard_length) {
                            $purchase_order_advise_quantity = $purchase_order_advise_quantity + ($poapv->quantity / $product_size->standard_length ) * $product_size->weight;
                        }
                    }
                }
            }
            if (count((array)$order['purchase_products']) > 0) {
                foreach ($order['purchase_products'] as $popk => $popv) {
                    if (isset($popv['product_sub_category']) && count((array)$popv['product_sub_category'])) {
                        $product_size = $popv['product_sub_category'];
                    } else {
                        $product_size = ProductSubCategory::find($popv->product_category_id);
                    }
                    if ($popv->unit_id == 1) {
                        $purchase_order_quantity = $purchase_order_quantity + $popv->quantity;
                    } elseif ($popv->unit_id == 2) {
                        $purchase_order_quantity = $purchase_order_quantity + ($popv->quantity * $product_size->weight);
                    } elseif ($popv->unit_id == 3) {
                        if ($product_size->standard_length) {
                            $purchase_order_quantity = $purchase_order_quantity + (($popv->quantity / $product_size->standard_length ) * $product_size->weight);
                        }
                    }
                }
            }
            if ($purchase_order_advise_quantity >= $purchase_order_quantity)
                $purchase_orders[$key]['pending_quantity'] = 0;
            else
                $purchase_orders[$key]['pending_quantity'] = ($purchase_order_quantity - $purchase_order_advise_quantity);
            $purchase_orders[$key]['total_quantity'] = $purchase_order_quantity;
        }
        return $purchase_orders;
    }
    
}
