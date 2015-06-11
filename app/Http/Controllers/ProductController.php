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
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;

class ProductController extends Controller {

    public function index() {
        $product_cat = ProductCategory::Paginate(10);
        $product_cat->setPath('product_category');
        return view('product_category', compact('product_cat'));
    }

    public function create() {
        $product_type = ProductType::all();
        return view('add_product_category', compact('product_type'));
    }

    public function store(ProductCategoryRequest $request) {

        $product_category = new ProductCategory();
        $product_category->product_type_id = $request->input('product_type');
        $product_category->product_category_name = $request->input('product_category_name');
        $product_category->price = $request->input('price');
        $product_category->save();

        return redirect('product_category')->with('success', 'Product category successfully added.');
    }

    public function show($id) {
        $product_cat = ProductCategory::where('id', $id)->get();

        return view('view_product_category', compact('product_cat'));
    }

    public function destroy($id) {

        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
            ProductCategory::destroy($id);
            return redirect('product_category')->with('success', 'Product details successfully deleted.');
        } else {
            return redirect('product_category')->with('wrong', 'You have entered wrong credentials');
        }
    }

    public function edit($id) {
        $product_type = ProductType::all();
        $product_cat = ProductCategory::where('id', $id)->get();
        return view('edit_product_category', compact('product_cat', 'product_type'));
    }

    public function update($id, ProductCategoryRequest $request) {

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
