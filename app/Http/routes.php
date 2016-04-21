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

Route::get('demorouteandroid', 'HomeController@demorouteandroid');
Route::get('androidtesting', 'HomeController@androidtesting');
Route::get('updatedata', 'HomeController@updatedata');
Route::get('phpversion', 'WelcomeController@phpversion');
Route::get('showupdatedata', 'HomeController@showupdatedata');
Route::get('update_delivery_location', 'HomeController@update_delivery_location');

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
    Artisan::call('migrate:rollback', ['--quiet' => true, '--force' => true]);
});


Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
Route::get('/', 'Auth\AuthController@getLogin');
Route::get('logout', 'Auth\AuthController@getLogout');
Route::group(['middleware' => ['auth']], function() {
//        Route::group(['middleware' => ['admin_mw','super_admin_mw']], function() {
//        Route::get('customers', 'CustomerController',['only' => ['index','show','edit']]);
//    });
//    Route::group(['middleware' => 'super_admin_mw'], function() {
//        Route::resource('customers', 'CustomerController');
//    });
    Route::resource('bulk-delete', 'BulkDeleteController');
    Route::get('bulk-delete', 'BulkDeleteController@show_result');
//    Route::post('bulk-delete', 'BulkDeleteController@show_result');

    Route::resource('security', 'SecurityController');
    Route::get('dashboard', 'DashboardController@index');
    Route::get('dashboard', 'DashboardController@index');
    Route::get('home', 'DashboardController@homeredirect');
    Route::resource('unit', 'UnitController');
    Route::resource('states', 'StatesController');
    Route::resource('city', 'CityController');
    Route::resource('location', 'DeliveryLocationController');
    Route::post('delivery_difference', 'DeliveryLocationController@delivery_difference');
    Route::resource('customers', 'CustomerController');
    Route::get('get_city', 'CustomerController@get_city');
    Route::resource('set_price', 'CustomerController@set_price');
    Route::resource('update_set_price', 'CustomerController@update_set_price');
    Route::get('bulk_set_price', 'CustomerController@bulk_set_price');
    Route::post('save_all_set_price', 'CustomerController@save_all_set_price');


    Route::resource('pending_customers', 'PendingCustomerController');
    Route::resource('customer_manager', 'CustomerManagerController');
    Route::post('add_pending_customers/{id}', 'PendingCustomerController@add_pending_customers');

    Route::resource('users', 'UsersController');

    Route::resource('product_category', 'ProductController');
    Route::get('update_price', 'ProductController@update_price');
    Route::post('update_all_price', 'ProductController@update_all_price');

    Route::resource('product_sub_category', 'ProductsubController');
    Route::get('get_product_category', 'ProductsubController@get_product_category');
    Route::post('update_difference', 'ProductsubController@update_difference');
    Route::get('fetch_product_size', 'ProductsubController@fetch_product_size');
    Route::get('fetch_product_name', 'ProductsubController@fetch_product_name');
    Route::post('delivery_order/{id}-delete', 'DeliveryOrderController@destroy');
    Route::resource('delivery_order', 'DeliveryOrderController');
    Route::resource('pending_delivery_order', 'DeliveryOrderController@pending_delivery_order');
    Route::resource('purchase_order_daybook', 'PurchaseDaybookController');
    Route::post('purchase_order_daybook/{id}', 'PurchaseDaybookController@destroy');
    Route::post('delete_all_daybook', 'PurchaseDaybookController@delete_all_daybook');
    Route::resource('expert_purchase_daybook', 'PurchaseDaybookController@expert_purchase_daybook');
    Route::get('print_purchase_daybook', 'PurchaseDaybookController@print_purchase_daybook');
    Route::get('change_password', 'PasswordController@getPassword');
    Route::post('change_password', 'PasswordController@postPassword');
    Route::post('inquiry/{id}-delete', 'InquiryController@destroy');
    Route::resource('inquiry', 'InquiryController');

//    Route::get('fetch_existing_supplier', 'InquiryController@fetch_existing_supplier');
    Route::get('fetch_existing_customer', 'InquiryController@fetch_existing_customer');
    Route::get('fetch_products', 'InquiryController@fetch_products');
    Route::get('recalculate_product_price', 'InquiryController@recalculate_product_price');
    Route::post('store_price', 'InquiryController@store_price');
    Route::get('get_product_sub_category', 'InquiryController@get_product_sub_category');
    Route::get('get_units', 'UnitController@get_units');
    Route::post('purchase_orders/{id}-delete', 'PurchaseOrderController@destroy');
    Route::resource('purchase_orders', 'PurchaseOrderController');
    Route::post('purchaseorder_advise/{id}-delete', 'PurchaseAdviseController@destroy');
    Route::resource('purchaseorder_advise', 'PurchaseAdviseController');
    Route::get('print_purchase_advise/{id}', 'PurchaseAdviseController@print_purchase_advise');
    Route::get('pending_purchase_advice', 'PurchaseAdviseController@pending_purchase_advice');
    Route::post('order/{id}-delete', 'OrderController@destroy');
    Route::resource('orders', 'OrderController');
    Route::post('manual_complete_order', 'OrderController@manual_complete_order');
    Route::get('fetch_order_size', 'ProductsubController@fetch_order_size');
    Route::resource('create_purchase_advice', 'PurchaseOrderController@create_purchase_advice');
    Route::post('store_advise', 'PurchaseAdviseController@store_advise');
    Route::resource('purchaseorder_advise_challan', 'PurchaseAdviseController@purchaseorder_advise_challan');
    Route::get('get_cities', 'CityController@get_cities');
    Route::get('create_delivery_order/{id}', 'OrderController@create_delivery_order');
    Route::post('create_delivery_order/{id}', 'OrderController@store_delivery_order');
    Route::get('pending_order_report', 'PendingOrderReportController@index');
    Route::post('delivery_challan/{id}-delete', 'DeliveryChallanController@destroy');
    Route::resource('delivery_challan', 'DeliveryChallanController');
    Route::post('manual_complete', 'PurchaseOrderController@manual_complete');
    Route::get('purchase_order_report', 'PurchaseOrderController@purchase_order_report');
    Route::post('purchase_challan/{id}-delete', 'PurchaseChallanController@destroy');
    Route::resource('purchase_challan', 'PurchaseChallanController');
    Route::get('create_delivery_challan/{id}', 'DeliveryOrderController@create_delivery_challan');
    Route::post('create_delivery_challan/{id}', 'DeliveryOrderController@store_delivery_challan');
    Route::resource('sales_daybook', 'SalesDaybookController@index');
    Route::post('delete_sales_daybook/{id}', 'SalesDaybookController@delete_challan');
    Route::post('delete_multiple_challan', 'SalesDaybookController@delete_multiple_challan');
    Route::post('sales_daybook_date', 'SalesDaybookController@challan_date');
    Route::get('print_purchase_challan/{id}', 'PurchaseChallanController@print_purchase_challan');
    Route::get('print_delivery_order/{id}', 'DeliveryOrderController@print_delivery_order');
    Route::get('print_delivery_challan/{id}', 'DeliveryChallanController@print_delivery_challan');
    Route::get('place_order/{id}', 'InquiryController@place_order');
    Route::post('store_order/{id}', 'InquiryController@store_place_order');
    Route::resource('export_sales_daybook', 'SalesDaybookController@export_sales_daybook');
    Route::get('print_sales_order_daybook', 'SalesDaybookController@print_sales_order_daybook');
    Route::post('get_product_weight', 'ProductsubController@get_product_weight');
    Route::get('fetch_product_price', 'DeliveryOrderController@product_price');
    Route::post('upload_excel', 'WelcomeController@upload_excel');
    Route::resource('excel_import', 'WelcomeController@excel_import');
    Route::resource('excel_import_customer', 'WelcomeController@excel_import_customer');
    Route::any('excel_export_customer', 'WelcomeController@excel_export_customer');
    Route::post('upload_customer_excel', 'WelcomeController@upload_customer_excel');
    Route::resource('import_delivery_location', 'WelcomeController@import_delivery_location');
    Route::post('process_import_delivery_location', 'WelcomeController@process_import_delivery_location');
    /* Helpful routes for developers */
    Route::get('delete_reports', 'WelcomeController@delete_reports');
    Route::get('removedata/{tablename}', 'WelcomeController@removedata');
    Route::get('emptydata/{tablename}', 'WelcomeController@emptydata');
    Route::get('showdata/{tablename}', 'WelcomeController@showdata');
    Route::get('showtableinfo/{tablename}', 'WelcomeController@showtableinfo');
    Route::get('updatecolumndata/{tablename}/{column}/{value}', 'WelcomeController@updatecolumndata');
    Route::get('checkdatabaseinfo', 'WelcomeController@checkdatabaseinfo');
    /* Helpful routes for developers ends here */
    Route::any('database_backup_test', 'HomeController@database_backup_test');
    Route::any('database_backup_live', 'HomeController@database_backup_live');
    Route::any('database_backup_local', 'HomeController@database_backup_local');
});


Route::get('export/{type}', 'WelcomeController@exportExcel');
Route::get('get_server_data', 'WelcomeController@get_server_data');
Route::get('delete_order_data/{table}/{col}/{cvalue}', 'WelcomeController@delete_order_data');
Route::get('clear_completed_records', 'CronDeleteRecordsController@index');
Route::post('update_all_sizes', 'ProductsubController@update_all_sizes_difference');
Route::any('update_user_role', 'WelcomeController@update_user_role');
Route::any('updatecity_delievrylocation', 'HomeController@updatecity_delievrylocation');
