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
use App\ProductCategory;
use App\ProductType;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;

class ProductController extends Controller {

    public function index() {
        $product_cat = ProductCategory::all();

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
        $product_category->price = $request->input('product_category_name');
        $product_category->save();

        return redirect('product_category')->with('success', 'Product category successfully added.');
    }

    public function destroy($id) {
        
    }

    public function edit($id) {
        
    }

    public function update($id) {
        
    }

}
