<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\User;
use Auth;
use App\ProductCategory;
use App\ProductSubCategory;
use App\ProductType;
use App\Http\Requests\ProductCategoryRequest;
use Input;
use App;
use Config;
use Session;

class ProductController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /*
     * load the view of the product category view.
     */

    public function index() {

        if (Auth::user()->role_id == 5) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        /* client want to delete 2 record so just elimated from query */
        // $product_cat = ProductCategory::orderBy('created_at', 'desc')->whereNotIn('product_category_name',['Local Coil- Light','Local Coil'])->Paginate(20);
        $product_cat = ProductCategory::orderBy('created_at', 'desc')->Paginate(20);
        $product_cat->setPath('product_category');
        return view('product_category', compact('product_cat'));
    }

    /*
     * load the add product category form
     */

    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        return view('add_product_category', compact('product_type'));
    }

    /*
     * store the product category form data as well se send the sms.
     */

    public function store(ProductCategoryRequest $request) {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Session::has('forms_product_category')) {
            $session_array = Session::get('forms_product_category');
            if (count($session_array) > 0) {
                if (in_array($request->form_key, $session_array)) {
                    return Redirect::back()->with('flash_message', 'This product category is already saved. Please refresh the page');
                } else {
                    array_push($session_array, $request->form_key);
                    Session::put('forms_product_category', $session_array);
                }
            }
        } else {
            $forms_array = [];
            array_push($forms_array, $request->form_key);
            Session::put('forms_product_category', $forms_array);
        }
        $product_category = new ProductCategory();
        $product_category->product_type_id = $request->input('product_type');
        $product_category->product_category_name = $request->input('product_category_name');
        $product_category->price = $request->input('price');
        $product_category->save();

        /*
         * ------------------- ---------------------------
         * SEND SMS TO ALL ADMINS FOR NEW PRODUCT CATEGORY
         * -----------------------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $admins = User::where('role_id', '=', 0)->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $product_type = ProductType::find($request->input('product_type'));
                    $str = "Dear " . $admin->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new product category as " . $request->input('product_category_name') . " under " . $product_type->name . " kindly check.\nVIKAS ASSOCIATES";
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
        }
        return redirect('product_category')->with('success', 'Product category successfully added.');
    }

    /*
     * show the product category details
     */

    public function show($id) {
        $product_cat = ProductCategory::with('product_sub_category', 'product_type')->find($id);
        if (count($product_cat) < 1) {
            return redirect('product_category')->with('success', 'Product category does not exist.');
        }
        $product_cat = ProductCategory::with('product_sub_category', 'product_type')->find($id);
        return view('view_product_category', compact('product_cat'));
    }

    /*
     * delete the product category details
     */

    public function destroy($id) {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
            $cat = ProductSubCategory::where('product_category_id', $id)->count();
            if ($cat == 0) {
                ProductCategory::destroy($id);
                return redirect('product_category')->with('success', 'Product details successfully deleted.');
            } else {
                return redirect('product_category')->with('wrong', 'Product has already child category, you can not delete this record.');
            }
        } else {
            return redirect('product_category')->with('wrong', 'Please enter the valid credential to delete the records.');
        }
    }

    /*
     * loads the product category edit form
     */

    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $product_cat = ProductCategory::where('id', $id)->get();
        if (count($product_cat) < 1) {
            return redirect('product_category')->with('success', 'Product category does not exist.');
        }

        $product_type = ProductType::all();

        return view('edit_product_category', compact('product_cat', 'product_type'));
    }

    /*
     * update the product category details.
     */

    public function update($id, ProductCategoryRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_data = array(
            'product_type_id' => $request->input('product_type'),
            'product_category_name' => $request->input('product_category_name'),
            'price' => $request->input('price'),
        );
        ProductCategory::where('id', $id)->update($product_data);

        /*
         * ------------------- ---------------------------
         * SEND SMS TO ALL ADMINS FOR UPDATE PRODUCT CATEGORY
         * -----------------------------------------------
         */

        $admins = User::where('role_id', '=', 0)->get();
      
        if (count($admins) > 0) {
            foreach ($admins as $key => $admin) {
                $product_type = ProductType::find($request->input('product_type'));
//                $str = "Dear " . $admin->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited a product category as " . $request->input('product_category_name') . " under " . $product_type->name . " kindly check.\nVIKAS ASSOCIATES";
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
        
     

        return redirect('product_category')->with('success', 'Product category successfully updated.');
    }

    /*
     * update the price of the single product category
     */

    public function update_price() {

        $val = Input::get('price');
        $key = Input::get('product_id');
        ProductCategory::where('id', $key)->update(array('price' => $val));


        $id = $key;
        /*
         * ------------------- ---------------------------
         * SEND SMS TO ALL ADMINS FOR UPDATE PRODUCT PRICE CATEGORY
         * -----------------------------------------------
         */

//        $admins = User::where('role_id', '=', 0)->get();
//
//        if (count($admins) > 0) {
//            foreach ($admins as $key => $admin) {
//                $productcategory = ProductCategory::find($id);
//                $str = "Dear " . $admin->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited a product category price as " . $productcategory->product_category_name . "-" . $val . " kindly check.\nVIKAS ASSOCIATES";
//                if (App::environment('development')) {
//                    $phone_number = Config::get('smsdata.send_sms_to');
//                } else {
//                    $phone_number = $admin->mobile_number;
//                }
//                $msg = urlencode($str);
//                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
//                if (SEND_SMS === true) {
//                    $ch = curl_init($url);
//                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    $curl_scraped_page = curl_exec($ch);
//                    curl_close($ch);
//                }
//            }
//        }
    }

    /*
     * update the price in bulk for the product category.
     */

    public function update_all_price() {

        $price = Input::get('price');
        foreach ($price as $key => $value) {
            foreach ($value as $val) {
                ProductCategory::where('id', $key)->update(array('price' => $val));
            }
        }
    }

}
