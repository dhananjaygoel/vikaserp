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
use App\Units;

class ProductsubController extends Controller {

    public function index() {

        $product_type = ProductType::all();
        $units = Units::all();
        $product_sub_cat = "";

        if (Input::get('product_filter') != "") {

            $product_sub_cat = ProductSubCategory::with(['product_category' =>
                        function($query) {
                            $query->where('product_type_id', Input::get('product_filter'));
                        }])
                    ->Paginate(10);
        } elseif (Input::get('search_text') != "") {

            $product_sub_cat = ProductSubCategory::with(['product_category' =>
                        function($query) {
                            $query->where('product_category_name', 'like', '%' . Input::get('search_text') . '%');
                        }])
                    ->Paginate(10);
        } else {

            $product_sub_cat = ProductSubCategory::with('product_category')->Paginate(10);
        }

        $product_sub_cat->setPath('product_sub_category');
        return view('product_sub_category', compact('product_sub_cat', 'product_type', 'units'));
    }

    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        $units = Units::first();
//        echo '<pre>';
//        print_r($units);
//        echo '</pre>';
//        exit;
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
        $ProductSubCategory->difference = $request->input('difference');
        $ProductSubCategory->save();

        return redirect('product_sub_category')->with('success', 'Product sub category successfully added.');
    }

    public function show($id) {
        
    }

    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
            ProductSubCategory::destroy($id);
            return redirect('product_sub_category')->with('flash_message', 'Product sub category details successfully deleted.');
        } else {
            return redirect('product_sub_category')->with('wrong', 'You have entered wrong credentials');
        }
    }

    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        $prod_category = ProductCategory::all();
//        $units = Units::all();
        $units = Units::first();
        $prod_sub_cat = ProductSubCategory::with('product_category', 'product_unit')->where('id', $id)->first();
        return view('edit_product_sub_category', compact('product_type', 'prod_sub_cat', 'prod_category', 'units'));
    }

    public function update($id, ProductSubCategoryRequest $request) {

        $pro_sub_cat = array(
            'product_category_id' => $request->input('select_product_categroy'),
            'alias_name' => $request->input('alias_name'),
            'size' => $request->input('size'),
            'weight' => $request->input('weight'),
            'unit_id' => $request->input('units'),
            'thickness' => $request->input('thickness'),
            'difference' => $request->input('difference')
        );

        ProductSubCategory::where('id', $id)
                ->update($pro_sub_cat);

        return redirect('product_sub_category')->with('success', 'Product sub category successfully updated.');
    }

    public function update_difference() {

        ProductSubCategory::where('id', Input::get('id'))
                ->update(array('difference' => Input::get('difference')));
        return redirect('product_sub_category')->with('success', 'Product sub category difference successfully updated.');
    }
    public function get_product_weight(){
        $product_id = Input::get('product_id');
        $product_cat = ProductSubCategory::where('product_category_id',$product_id)->first();
        $product_weight= $product_cat['weight'];
        return $product_weight;
    }

}
