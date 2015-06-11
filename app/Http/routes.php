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







Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
Route::get('/', function() {
    return view('auth.login');
});
Route::group(['middleware' => ['auth']], function() {
    Route::resource('security', 'SecurityController');
    Route::get('dashboard', 'DashboardController@index');
    Route::resource('unit', 'UnitController');
    Route::resource('states', 'StatesController');
    Route::resource('city', 'CityController');
    Route::resource('location', 'DeliveryLocationController');
    Route::resource('customers', 'CustomerController');
    Route::resource('pending_customers', 'PendingCustomerController');
    Route::post('add_pending_customers/{id}', 'PendingCustomerController@add_pending_customers');

    Route::resource('users', 'UsersController');
    Route::resource('product_category', 'ProductController');
    Route::resource('product_sub_category', 'ProductsubController');
    Route::get('get_product_category', 'ProductsubController@get_product_category');
    Route::post('update_difference', 'ProductsubController@update_difference');

    Route::get('change_password', 'PasswordController@getPassword');
    Route::post('change_password', 'PasswordController@postPassword');

    Route::resource('inquiry', 'InquiryController');
    Route::get('fetch_existing_customer', 'InquiryController@fetch_existing_customer');





    Route::get('orders', function() {
        return 'Order';
    });
    Route::get('pending_orders', function() {
        return 'Pending Orders';
    });
    Route::get('order', function() {
        return 'Order ';
    });
    Route::get('pending_inquiry', function() {
        return 'Pending Inquiry ';
    });
    Route::get('inquiry', function() {
        return 'Inquiry';
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
    Route::get('purchase_orders', function() {
        return 'Purchase Orders';
    });
});






