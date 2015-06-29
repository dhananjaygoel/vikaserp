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
//        Route::group(['middleware' => ['admin_mw','super_admin_mw']], function() {
//        Route::get('customers', 'CustomerController',['only' => ['index','show','edit']]);
//    });
//    Route::group(['middleware' => 'super_admin_mw'], function() {
//        Route::resource('customers', 'CustomerController');
//    });
    Route::resource('security', 'SecurityController');
    Route::get('dashboard', 'DashboardController@index');
    Route::get('home', 'DashboardController@homeredirect');
    Route::resource('unit', 'UnitController');
    Route::resource('states', 'StatesController');
    Route::resource('city', 'CityController');
    Route::resource('location', 'DeliveryLocationController');
    Route::post('delivery_difference', 'DeliveryLocationController@delivery_difference');
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
    Route::resource('pending_delivery_order', 'DeliveryOrderController@pending_delivery_order');
    Route::resource('purchase_order_daybook', 'PurchaseDaybookController');
    Route::post('purchase_order_daybook/{id}', 'PurchaseDaybookController@destroy');
    Route::post('delete_all_daybook', 'PurchaseDaybookController@delete_all_daybook');
    Route::resource('expert_purchase_daybook', 'PurchaseDaybookController@expert_purchase_daybook');

    Route::get('change_password', 'PasswordController@getPassword');
    Route::post('change_password', 'PasswordController@postPassword');

    Route::resource('inquiry', 'InquiryController');
    Route::get('fetch_existing_customer', 'InquiryController@fetch_existing_customer');
    Route::get('fetch_products', 'InquiryController@fetch_products');
    Route::post('store_price', 'InquiryController@store_price');    
    Route::get('get_product_sub_category', 'InquiryController@get_product_sub_category');
    
    Route::get('get_units', 'UnitController@get_units');
    Route::resource('purchase_orders', 'PurchaseOrderController');
    Route::resource('purchaseorder_advise', 'PurchaseAdviseController');
    Route::get('pending_purchase_advice', 'PurchaseAdviseController@pending_purchase_advice');
    Route::resource('orders', 'OrderController');
    Route::post('manual_complete_order', 'OrderController@manual_complete_order');
    Route::resource('create_purchase_advice', 'PurchaseOrderController@create_purchase_advice');
    Route::post('store_advise', 'PurchaseAdviseController@store_advise');
    Route::resource('purchaseorder_advise_challan', 'PurchaseAdviseController@purchaseorder_advise_challan');

    Route::get('get_cities', 'CityController@get_cities');
    Route::get('create_delivery_order/{id}', 'OrderController@create_delivery_order');
    Route::post('create_delivery_order/{id}', 'OrderController@store_delivery_order');
    Route::get('pending_order_report', 'PendingOrderReportController@index');
    Route::resource('delivery_challan', 'DeliveryChallanController');
    Route::post('manual_complete', 'PurchaseOrderController@manual_complete');
    Route::get('purchase_order_report', 'PurchaseOrderController@purchase_order_report');
    Route::resource('purchase_challan', 'PurchaseChallanController');
    Route::get('create_delivery_challan/{id}', 'DeliveryOrderController@create_delivery_challan');
    Route::post('create_delivery_challan/{id}', 'DeliveryOrderController@store_delivery_challan');

    Route::get('sales_daybook','SalesDaybookController@index');
    Route::post('delete_sales_daybook/{id}','SalesDaybookController@delete_challan');
    Route::post('delete_multiple_challan','SalesDaybookController@delete_multiple_challan');
    Route::post('sales_daybook_date','SalesDaybookController@challan_date');   
    
    Route::post('print_delivery_order/{id}','DeliveryOrderController@print_delivery_order');
    Route::post('print_delivery_challan/{id}','DeliveryChallanController@print_delivery_challan');
    Route::get('place_order/{id}','InquiryController@place_order');
    Route::post('store_order/{id}','InquiryController@store_place_order');
    Route::resource('export_sales_daybook', 'SalesDaybookController@export_sales_daybook'); 
    Route::post('get_product_weight','ProductsubController@get_product_weight');
    
});






Route::get('export/{type}', 'WelcomeController@exportExcel');