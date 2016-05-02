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
use App\PurchaseOrderCanceled;
use App\PurchaseProducts;
use App\Inquiry;
use App\Order;
use App\Units;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\Http\Controllers\DeliveryOrderController;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\Input;
use Illuminate\Support\Facades\Auth;

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
        $this->middleware('auth');
    }

    // All Functions added by user 157 for app //
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

        if ((isset($_GET['inquiry_filter'])) && $_GET['inquiry_filter'] != '') {

            $inquiries = Inquiry::where('inquiry_status', '=', $_GET['inquiry_filter'])
                            ->with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details')
                            ->orderBy('created_at', 'desc')->get();
        } else {

            $inquiries = Inquiry::with('customer', 'delivery_location', 'inquiry_products.inquiry_product_details', 'inquiry_products.unit')
                            ->where('inquiry_status', 'pending')
                            ->orderBy('created_at', 'desc')->get();
        }
        return json_encode($inquiries);
    }

    public function apporders() {
        $q = Order::query();
        if (isset($_GET['order_filter']) && $_GET['order_filter'] != '') {
            $q->where('order_status', '=', $_GET['order_filter']);
        } else {
            $q->where('order_status', '=', 'pending');
        }
        $allorders = $q->with('all_order_products')
                        ->with('customer', 'delivery_location', 'order_cancelled')
                        ->orderBy('created_at', 'desc')->get();

        return json_encode($allorders);
    }

    public function appdelivery_order() {

        $delivery_data = 0;
        $delivery_data = DeliveryOrder::orderBy('created_at', 'desc')
                        ->where('order_status', 'pending')
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
        $allorders = DeliveryChallan::where('challan_status', '=', 'pending')
                        ->with('customer', 'delivery_challan_products', 'delivery_order')
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
        $customers = Customer::orderBy('tally_name', 'asc')
                ->where('customer_status', '=', 'permanent')
                ->get();
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
        $delivery_data = DeliveryOrder::
                where('order_status', 'pending')
                ->with('user', 'customer')
                ->get();
        return json_encode($delivery_data);
    }

    public function appallpurchaseorders() {
        $q = PurchaseOrder::query();
        $purchase_orders = $q->orderBy('created_at', 'desc')
                        ->with('customer', 'delivery_location', 'user', 'purchase_products.purchase_product_details', 'purchase_products.unit')->get();
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
                ->where('order_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        return json_encode($purchase_challan);
    }

    public function appallpurchase_order_daybook() {
        $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier', 'all_purchase_products.purchase_product_details')
                ->where('order_status', 'completed')
                ->orderBy('created_at', 'desc')
                ->get();
        return json_encode($purchase_daybook);
    }

    public function applocation() {
        $delivery_location = DeliveryLocation::where('status', '=', 'permanent')
                        ->with('city.states')
                        ->orderBy('created_at', 'desc')->get();
        return json_encode($delivery_location);
    }

    // All Functions added by user 157 for app ends here //
    public function applogin() {

        $username = $_POST['username'];
        $password = $_POST['password'];

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
        $product_data = \App\ProductCategory::all();
        foreach ($product_data as $value) {
            $product = \App\ProductCategory::find($value->id);
            $product->price_new = $product->price;
            $product->save();
        }
    }

    public function showupdatedata() {
        $product_data = \App\ProductCategory::all();
        echo "<table>";
        foreach ($product_data as $value) {
            echo "<tr><td>" . $value->id . "</td><td>" . $value->product_type_id . "</td><td>" . $value->product_category_name . "</td><td>" . $value->price . "</td><td>" . $value->price_new . "</td></tr>";
        }
        echo "</table>";
    }

    public function update_delivery_location() {
        $product_data = \App\Customer::where('delivery_location_id', '=', 0)->update(['delivery_location_id' => 32]);
    }

    /**
     * Written by : AMit GupTA
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
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";

        passthru($cmd);

        exit(0);
    }

    /**
     * Written by : AMit GupTA
     * This function takes backup of whole datbase of test server machine
     */
    public function database_backup_test() {
        $DBUSER = "agstechn_vauser";
        $DBPASSWD = "vikasuser23210";
        $DATABASE = "agstechn_vaoas";

        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";

        passthru($cmd);

        exit(0);
    }

    /**
     * Written by : AMit GupTA
     * This function takes backup of whole datbase of production server machine
     */
    public function database_backup_live() {
        $DBUSER = "vikaserp_agsus";
        $DBPASSWD = "passags756";
        $DATABASE = "vikaserp_ags";
        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";
        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";
        passthru($cmd);
        exit(0);
    }

    public function updatecity_delievrylocation() {
        $city_data = City::where('state_id', 4)->update(array('state_id' => 1));
    }

}
