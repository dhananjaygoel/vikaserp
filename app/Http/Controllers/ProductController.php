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
use App\AllOrderProducts;
use App\PurchaseProducts;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;
use Config;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller {

    public function __construct() {
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    public function index() {

        $product_cat = ProductCategory::Paginate(10);
        $product_cat->setPath('product_category');
        return view('product_category', compact('product_cat'));
    }

    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        return view('add_product_category', compact('product_type'));
    }

    public function store(ProductCategoryRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
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
            $admins = User::where('role_id', '=', 1)->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $product_type = ProductType::find($request->input('product_type'));
                    $str = "Dear " . $admin->first_name . "  " . Auth::user()->first_name . " has created a new product catagory as " . $request->input('product_category_name') . " under " . $product_type->name . " kindly chk. Vikas associates";
                    $phone_number = $admin->mobile_number;
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=4";
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

    public function show($id) {
        $product_cat = ProductCategory::where('id', $id)->with('product_sub_category', 'product_type')->first();

        return view('view_product_category', compact('product_cat'));
    }

    public function destroy($id) {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {


            $order_count = AllOrderProducts::where('product_category_id', $id)->count();
            $purchase_count = PurchaseProducts::where('product_category_id', $id)->count();

            if ($purchase_count == 0 && $order_count == 0) {

                ProductCategory::destroy($id);
                return redirect('product_category')->with('success', 'Product details successfully deleted.');
            } else {
                return redirect('product_category')->with('wrong', 'Product has already added by user, you can not delete this record.');
            }
        } else {
            return redirect('product_category')->with('wrong', 'Please enter the valid credential to delete the records.');
        }
    }

    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        $product_cat = ProductCategory::where('id', $id)->get();
        return view('edit_product_category', compact('product_cat', 'product_type'));
    }

    public function update($id, ProductCategoryRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_data = array(
            'product_type_id' => $request->input('product_type'),
            'product_category_name' => $request->input('product_category_name'),
            'price' => $request->input('price'),
        );

        ProductCategory::where('id', $id)
                ->update($product_data);

        return redirect('product_category')->with('success', 'Product category successfully updated.');
    }

    public function update_price() {
        $val = Input::get('price');
        $key = Input::get('product_id');

        ProductCategory::where('id', $key)
                ->update(array('price' => $val));
    }

    public function update_all_price() {

        $price = Input::get('price');
        foreach ($price as $key => $value) {
            foreach ($value as $val) {

                ProductCategory::where('id', $key)
                        ->update(array('price' => $val));
            }
        }
        return redirect('product_category')->with('success', 'Product category price successfully updated.');
    }

}
