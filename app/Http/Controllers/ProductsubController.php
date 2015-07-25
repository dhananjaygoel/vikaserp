<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use App\ProductCategory;
use App\ProductType;
use App\ProductSubCategory;
use App\Http\Requests\ProductSubCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;
use Config;
use App\Units;
use App\AllOrderProducts;
use App\PurchaseProducts;
use App\InquiryProducts;

class ProductsubController extends Controller {

    public function __construct() {
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    public function index() {//index
        $product_type = ProductType::all();
        $units = Units::all();
        $product_sub_cat = "";
        $input_data = Input::all();

        $q = ProductSubCategory::query();
        $q->with('product_category');
        if (Input::get('product_filter') != "") {
            $q->whereHas('product_category', function($query) {
                $query->where('product_type_id', Input::get('product_filter'));
            });
        }
        if (Input::get('search_text') != "") {

            $q->whereHas('product_category', function($query) {
                $query->where('product_category_name', 'like', '%' . Input::get('search_text') . '%');
            });
        }
        if (Input::get('product_size') != "") {
            if (strpos(Input::get('product_size'), '-') !== false) {
                $size_ar = explode("-", Input::get('product_size'));
                $size = $size_ar[0];
                $size2 = $size_ar[1];
                $q->whereHas('product_category', function($query) use ($size, $size2) {
                    $query->where('size', 'like', '%' . trim($size) . '%')
                            ->orWhere('alias_name', 'like', '%' . trim($size2) . '%');
                });
            } else {
                $blanck = Input::get('product_size');
                $q->whereHas('product_category', function($query) use ($blanck) {
                    $query->where('size', 'like', '%' . $blanck . '%')
                            ->orWhere('alias_name', 'like', '%' . $blanck . '%');
                });
            }
        }

        $product_sub_cat = $q->paginate(20);

        $filter = array(Input::get('product_size'), Input::get('search_text'), Input::get('product_filter'));
        $product_sub_cat->setPath('product_sub_category');
        return view('product_sub_category', compact('product_sub_cat', 'product_type', 'units', 'filter'));
    }

    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        $units = Units::first();

        return view('add_product_sub_category', compact('product_type', 'units'));
    }

    public function get_product_category() {

        $product_cat = ProductCategory::where('product_type_id', Input::get('product_type_id'))->get();

        $prod = array();
        $i = 0;
        foreach ($product_cat as $key => $val) {
            $prod[$i]['id'] = $product_cat[$key]->id;
            $prod[$i]['product_category_name'] = $product_cat[$key]->product_category_name;
            $i++;
        }
        echo json_encode(array('prod' => $prod));
        exit;
    }

    public function store(ProductSubCategoryRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $ProductSubCategory = new ProductSubCategory();
        $ProductSubCategory->product_category_id = $request->input('select_product_categroy');
        $ProductSubCategory->alias_name = $request->input('alias_name');
        $ProductSubCategory->size = $request->input('size');
        $ProductSubCategory->weight = $request->input('weight');
        $ProductSubCategory->unit_id = $request->input('units');
        $ProductSubCategory->thickness = $request->input('thickness');
        $ProductSubCategory->standard_length = $request->input('standard_length');
        $ProductSubCategory->difference = $request->input('difference');
        $ProductSubCategory->save();

        /*
         * ------------------- -----------------------
         * SEND SMS TO ALL ADMINS FOR NEW PRODUCT SIZE
         * -------------------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $admins = User::where('role_id', '=', 1)->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $product_category = ProductCategory::where('id', '=', $request->input('select_product_categroy'))->with('product_type')->first();
                    $str = "Dear " . $admin->first_name
                            . ",  " . Auth::user()->first_name
                            . " has created a new size catagory as "
                            . $request->input('size')
                            . ", " . $request->input('thickness')
                            . ", " . $request->input('weight')
                            . ", " . $request->input('alias_name')
                            . ", " . $request->input('difference')
                            . " under " . $product_category->product_category_name
                            . " & " . $product_category['product_type']->name
                            . " kindly chk. Vikas associates";
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

        return redirect('product_sub_category')->with('success', 'Product sub category successfully added.');
    }

    public function show($id) {
        
    }

    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {

            $product_cat = ProductSubCategory::where('id', $id)->first();

            $order_count = AllOrderProducts::where('product_category_id', $id)->count();
            $purchase_count = PurchaseProducts::where('product_category_id', $id)->count();
            $inquery_count = InquiryProducts::where('product_category_id', $id)->count();

            if ($purchase_count == 0 && $order_count == 0 && $inquery_count == 0) {
                ProductSubCategory::destroy($id);
                return redirect('product_sub_category')->with('success', 'Product sub category details successfully deleted.');
            } else {
                return redirect('product_sub_category')->with('wrong', 'Product size has already added by user, you can not delete this record.');
            }
        } else {
            return redirect('product_sub_category')->with('wrong', 'You have entered wrong credentials');
        }
    }

    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('product_sub_category')->with('error', 'You do not have permission.');
        }

        $prod_sub_cat = ProductSubCategory::with('product_category', 'product_unit')->where('id', $id)->first();
        if (count($prod_sub_cat) < 1) {
            return redirect('product_sub_category')->with('success', 'Product sub category does not exist.');
        }

        $product_type = ProductType::all();
        $prod_category = ProductCategory::all();
//        $units = Units::all();
        $units = Units::first();

        return view('edit_product_sub_category', compact('product_type', 'prod_sub_cat', 'prod_category', 'units'));
    }

    public function update($id) {


        $validator = Validator::make(Input::all(), ProductSubCategory::$product_sub_category_rules);

        if ($validator->passes()) {

            $data = Input::all();

            $pro_sub_cat = array(
                'product_category_id' => $data['select_product_categroy'],
//                'alias_name' => $data['alias_name'],
                'size' => $data['size'],
                'weight' => $data['weight'],
                'unit_id' => $data['units'],
                'thickness' => $data['thickness'],
                'standard_length' => $data['standard_length'],
                'difference' => $data['difference']
            );

            $alias_count = ProductSubCategory::where('id', '!=', $id)
                    ->where('alias_name', '=', $data['alias_name'])
                    ->count();

            if ($alias_count > 0) {
                return Redirect::back()->withInput()->with('alias', 'Alias name already taken.');
            } else {
                $pro_sub_cat['alias_name'] = Input::get('alias_name');
            }

            ProductSubCategory::where('id', $id)
                    ->update($pro_sub_cat);

            /*
             * ------------------- -------------------------
             * SEND SMS TO ALL ADMINS ON UPDATE PRODUCT SIZE
             * ---------------------------------------------
             */
            $input = Input::all();
            if (isset($input['sendsms']) && $input['sendsms'] == "true") {
                $admins = User::where('role_id', '=', 1)->get();
                if (count($admins) > 0) {
                    foreach ($admins as $key => $admin) {
                        $product_category = ProductCategory::where('id', '=', $request->input('select_product_categroy'))->with('product_type')->first();
                        $str = "Dear " . $admin->first_name
                                . ",  " . Auth::user()->first_name
                                . " has updated a size catagory as "
                                . $request->input('size')
                                . ", " . $request->input('thickness')
                                . ", " . $request->input('weight')
                                . ", " . $request->input('alias_name')
                                . ", " . $request->input('difference')
                                . " under " . $product_category->product_category_name
                                . " & " . $product_category['product_type']->name
                                . " kindly chk. Vikas associates";
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
            return redirect('product_sub_category')->with('success', 'Product sub category successfully updated.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function update_difference() {

        ProductSubCategory::where('id', Input::get('id'))
                ->update(array('difference' => Input::get('difference')));
        return redirect('product_sub_category')->with('success', 'Product sub category difference successfully updated.');
    }

    public function get_product_weight() {
        $product_id = Input::get('product_id');
        $product_cat = ProductSubCategory::where('product_category_id', $product_id)->first();
        $product_weight = $product_cat['weight'];
        return $product_weight;
    }

    public function fetch_product_size() {
        $term = '%' . Input::get('term') . '%';
        $product = ProductSubCategory::where('size', 'like', $term)
                ->orWhere('alias_name', 'like', $term)->orderBy('size', 'desc')->orderBy('alias_name', 'desc')
                ->get();

        if (count($product) > 0) {
            foreach ($product as $prod) {
                $data_array[] = [
                    'value' => $prod->size . " - " . $prod->alias_name
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No size found',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    public function fetch_product_name() {
        $term = '%' . Input::get('term') . '%';
        $product = ProductCategory::where('product_category_name', 'like', $term)->get();
        if (count($product) > 0) {
            foreach ($product as $prod) {
                $data_array[] = [
                    'value' => $prod->product_category_name
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No Product found',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

}
