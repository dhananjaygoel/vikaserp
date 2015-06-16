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




Route::get('doMigrate', function () {
    define('STDIN', fopen("php://stdin", "r"));
    Artisan::call('migrate', ['--quiet' => true, '--force' => true]);
});
Route::get('dataSeeding', function () {
    define('STDIN', fopen("php://stdin", "r"));
    Artisan::call('db:seed', array('--force' => true));
});

Route::get('rollback', function() {
    define('STDIN', fopen("php://stdin", "r"));
    Artisan::call('migrate:refresh', ['--quiet' => true, '--force' => true]);
});








Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
Route::get('logout', 'DashboardController@logout');
Route::get('/', 'Auth\AuthController@getLogin');
Route::group(['middleware' => ['auth']], function() {
    Route::resource('security', 'SecurityController');
    Route::get('dashboard', 'DashboardController@index');
    Route::get('home', 'DashboardController@homeredirect');
    Route::resource('unit', 'UnitController');
    Route::resource('states', 'StatesController');
    Route::resource('city', 'CityController');
    Route::resource('location', 'DeliveryLocationController');
    Route::resource('customers', 'CustomerController');
    Route::resource('pending_customers', 'PendingCustomerController');
    Route::resource('customer_manager', 'CustomerManagerController');
    Route::post('add_pending_customers/{id}', 'PendingCustomerController@add_pending_customers');

    Route::resource('users', 'UsersController');
    Route::resource('product_category', 'ProductController');
    Route::post('update_price', 'ProductController@update_price');
    Route::resource('product_sub_category', 'ProductsubController');
    Route::get('get_product_category', 'ProductsubController@get_product_category');
    Route::post('update_difference', 'ProductsubController@update_difference');
    Route::resource('delivery_order', 'DeliveryOrderController');

    Route::get('change_password', 'PasswordController@getPassword');
    Route::post('change_password', 'PasswordController@postPassword');

    Route::resource('inquiry', 'InquiryController');
    Route::get('fetch_existing_customer', 'InquiryController@fetch_existing_customer');
    Route::get('fetch_products', 'InquiryController@fetch_products');
    Route::post('store_price', 'InquiryController@store_price');
    Route::get('get_units', 'UnitController@get_units');
    Route::resource('purchase_orders', 'PurchaseOrderController');
    Route::resource('purchaseorder_advise', 'PurchaseAdviseController');
    Route::resource('orders', 'OrderController');
    Route::post('order_cancelled', 'OrderController@manual_complete_order');
    Route::resource('create_purchase_advice', 'PurchaseOrderController@create_purchase_advice');
    Route::post('store_advise', 'PurchaseAdviseController@store_advise');
    Route::get('get_cities', 'CityController@get_cities');
    Route::get('create_delivery_order/{id}', 'OrderController@create_delivery_order');
    Route::post('create_delivery_order/{id}', 'OrderController@store_delivery_order');
    Route::get('pending_order_report', 'PendingOrderReportController@index');
    Route::resource('delivery_challan', 'DeliveryChallanController');
    Route::post('manual_complete', 'PurchaseOrderController@manual_complete');
    Route::get('purchase_order_report', 'PurchaseOrderController@purchase_order_report');
    Route::resource('purchase_challan', 'PurchaseChallanController');

    Route::get('pending_inquiry', function() {
        return 'Pending Inquiry ';
    });
    Route::get('delivery_orders', function() {
        return 'Delivery Orders';
    });
    Route::get('pending_delivery_orders', function() {
        return 'Pending Delivery Orders';
    });
    Route::get('delivery_order_challan', function() {
        return 'Delivery Order Challan';
    });
});






