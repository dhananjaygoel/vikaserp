<?php

namespace App\Http\Controllers;

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
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
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

}
