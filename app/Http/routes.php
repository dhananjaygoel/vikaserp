<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
//Route::get('login',function(){
//    return 'auth.login';
//});
Route::group(['middleware' => ['auth']], function() {
    Route::resource('security', 'SecurityController');
    Route::get('dashboard', function() {
        return view('home');
    });
    Route::resource('unit', 'UnitController');
    Route::resource('states', 'StatesController');
    Route::resource('city', 'CityController');
    Route::resource('location', 'DeliveryLocationController');
    Route::resource('customers', 'CustomerController');

    Route::resource('users', 'UsersController');
    Route::resource('product_category', 'ProductController');
    
    
    
    Route::get('orders',function(){
        return 'Order';
    });
    Route::get('pending_orders',function(){
        return 'Pending Orders';
    });
    Route::get('order',function(){
        return 'Order ';
    });
    Route::get('pending_inquiry',function(){
        return 'Pending Inquiry ';
    });
    Route::get('inquiry',function(){
        return 'Inquiry';
    });
    Route::get('delivery_orders',function(){
        return 'Delivery Orders';
    });
    Route::get('pending_delivery_orders',function(){
        return 'Pending Delivery Orders';
    });
    Route::get('delivery_order_challan',function(){
        return 'Delivery Order Challan';
    });
    Route::get('purchase_orders',function(){
        return 'Purchase Orders';
    });
});






