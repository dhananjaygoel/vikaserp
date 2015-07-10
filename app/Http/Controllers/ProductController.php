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
use App\ProductSubCategory;
use App\ProductType;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller {

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
//        $product_category = new ProductCategory();
//        $product_category->product_type_id = $request->input('product_type');
//        $product_category->product_category_name = $request->input('product_category_name');
//        $product_category->price = $request->input('price');
//        $product_category->save();


        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $admins = User::where('role_id', '=', 1)->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $product_type = ProductType::find($request->input('product_type'));
                    $str = "Dear ".$admin->first_name.", <br/> ".Auth::user()->first_name." has created a new product catagory as ".$request->input('product_category_name')." under ".$product_type->name." kindly chk. <br />Vikas associates";

                    $msg = urlencode($str);
                    $url = "http://bulksmspune.mobi/sendurlcomma.aspx?user=20064486&pwd=its1782&senderid=Hvikas&mobileno=+918276803247&msgtext=" . $msg;
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                    echo $curl_scraped_page;
                }
            }
        }

        exit();
        return redirect('product_category')->with('success', 'Product category successfully added.');
    }

    public function show($id) {
        $product_cat = ProductCategory::where('id', $id)->with('product_sub_category','product_type')->first();
     
        return view('view_product_category', compact('product_cat'));
    }

    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
            ProductCategory::destroy($id);
            return redirect('product_category')->with('success', 'Product details successfully deleted.');
        } else {
            return redirect('product_category')->with('wrong', 'You have entered wrong credentials');
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

        ProductCategory::where('id', Input::get('id'))
                ->update(array('price' => Input::get('price')));
        return redirect('product_category')->with('success', 'Product category price successfully updated.');
    }

}
