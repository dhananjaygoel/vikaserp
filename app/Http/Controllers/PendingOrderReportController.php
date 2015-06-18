<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use App\Customer;
use App\Units;
use App\DeliveryLocation;
use App\Order;
use App\AllOrderProducts;
use App\Http\Requests\PlaceOrderRequest;
use App\ProductCategory;
use Input;
use DB;
use Auth;
use App\User;
use Hash;
use App\OrderCancelled;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ManualCompleteOrderRequest;
use App\DeliveryOrder;

class PendingOrderReportController extends Controller {

    /**
     * Display a listing of the resource.
     * 
     * @return Response
     */
    public function index() {
        if ((isset($_GET['party_filter'])) && $_GET['party_filter'] != '') {

            $allorders = Order::where('customer_id', '=', $_GET['party_filter'])
                    ->where('order_status','=','pending')
                            ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
        }elseif((isset($_GET['fulfilled_filter'])) && $_GET['fulfilled_filter'] != '') { 
            if($_GET['fulfilled_filter'] == '0') { 
                $allorders = Order::where('order_status', '=', 'pending')
                            ->where('order_source','=','warehouse')
                        ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
            }else{
                if($_GET['fulfilled_filter'] == 'all') { 
                $allorders = $allorders = Order::where('order_status', '=', 'pending')
                        ->where('order_source','=','supplier')
                        ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
            }else{
                $allorders = Order::where('order_status', '=', 'pending')
                        ->where('order_source','=','supplier')
                        ->where('supplier_id','=',$_GET['fulfilled_filter'])
                        ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
            }
                
            }
        }elseif((isset($_GET['location_filter'])) && $_GET['location_filter'] != '') { 
            if($_GET['location_filter'] != '0') { 
                $allorders = Order::where('order_status', '=', 'pending')
                            ->where('delivery_location_id','=',$_GET['location_filter'])
                        ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
            }else{
                $allorders = Order::where('order_status', '=', 'pending')
                        ->where('other_location','=',$_GET['location_filter'])
                        ->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
            }
        }
        else {
            
            $allorders = Order::where('order_status', '=', 'pending')->with('customer', 'delivery_location', 'all_order_products')->orderBy('created_at', 'desc')->Paginate(2);
        }

        $users = User::all();
        $customers = Customer::all();
        $delivery_location = DeliveryLocation::all();
        $delivery_order = DeliveryOrder::all();
//        $allorder_products = AllOrderProducts::all()->order ;
        $allorders->setPath('pending_order_report');
        return View::make('pending_order_report', compact('allorders', 'users', 'customers', 'delivery_location','delivery_order'));
    }

}
