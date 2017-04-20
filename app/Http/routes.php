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


Route::post('applogin', 'HomeController@applogin');
Route::post('appuserresetpassword', 'HomeController@appUserResetPassword');
Route::post('apporderstatus', 'HomeController@appOrderStatus');
Route::post('appverifyuserotp', 'HomeController@appVerifyUserOtp');
Route::post('appuserprofile', 'HomeController@appUserProfile');
Route::post('appupdateuser', 'HomeController@appUpdateUser');
Route::post('appgenerateuserotp', 'HomeController@generateUserOtp');
Route::post('appsync', 'HomeController@appsync');
Route::post('appsyncinquiry', 'HomeController@appsyncinquiry');
Route::post('appsyncinquiry_customer', 'HomeController@appsyncinquiry_customer');
Route::post('appsyncorder', 'HomeController@appSyncOrder');
Route::post('appsyncorder_customer', 'HomeController@appSyncOrder_customer');
Route::post('appsyncdeliveryorder', 'HomeController@appSyncDeliveryOrder');
Route::post('appsyncdeliverychallan', 'HomeController@appSyncDeliveryChallan');
Route::post('appsyncpurchaseadvise', 'HomeController@appSyncPurchaseAdvise');
Route::post('appsyncpurchasechallan', 'HomeController@appSyncPurchaseChallan');
Route::post('appsyncpurchaseorder', 'HomeController@appSyncPurchaseOrder');
Route::post('appcustomerdeleteinquiry', 'HomeController@appcustomerdeleteinquiry');
Route::post('appcustomerdeleteorder', 'HomeController@appcustomerdeleteorder');
Route::post('appsync_customerinquiry', 'HomeController@appsync_customerinquiry');
Route::post('appsync_customerorder', 'HomeController@appsync_customerorder');
Route::post('appdeleteinquiry', 'HomeController@appdeleteinquiry');
Route::post('appdeleteorder', 'HomeController@appdeleteorder');
Route::post('appdeletedelivery_order', 'HomeController@appdeletedelivery_order');
Route::post('appdeletedelivery_challan', 'HomeController@appdeletedelivery_challan');
Route::post('appdeletepurchase_order', 'HomeController@appdeletepurchase_order');
Route::post('appdeletepurchase_advise', 'HomeController@appdeletepurchase_advise');
Route::post('appdeletepurchase_challan', 'HomeController@appdeletepurchase_challan');
Route::get('appcount', 'HomeController@appcount');
Route::get('appinquiry', 'HomeController@appinquiry');
Route::get('apporders', 'HomeController@apporders');
Route::get('appdelivery_order', 'HomeController@appdelivery_order');
Route::get('appalldelivery_challan', 'HomeController@appalldelivery_challan');
Route::get('appallunit', 'HomeController@appallunit');
Route::get('appallcity', 'HomeController@appallcity');
Route::get('appallstate', 'HomeController@appallstate');
Route::get('appallcustomers', 'HomeController@appallcustomers');
Route::get('appallproduct_category', 'HomeController@appallproduct_category');
Route::get('appallproduct_sub_category', 'HomeController@appallproduct_sub_category');
Route::get('appallusers', 'HomeController@appallusers');
Route::get('appallpending_customers', 'HomeController@appallpending_customers');
Route::get('appallpending_delivery_order', 'HomeController@appallpending_delivery_order');
Route::get('appallpurchaseorders', 'HomeController@appallpurchaseorders');
Route::get('appallpurchaseorder_advise', 'HomeController@appallpurchaseorder_advise');
Route::get('appallpurchase_challan', 'HomeController@appallpurchase_challan');
Route::get('appallpending_purchase_advice', 'HomeController@appallpending_purchase_advice');
Route::get('appallpurchase_order_daybook', 'HomeController@appallpurchase_order_daybook');
Route::get('appinventory', 'HomeController@appinventory');
Route::get('app_export_inventory', 'InventoryController@export_inventory');
Route::get('app_export_salesdaybook', 'SalesDaybookController@export_sales_daybook');
Route::get('app_export_purchasedaybook', 'PurchaseDaybookController@expert_purchase_daybook');

Route::get('appall_relationship_manager', 'HomeController@appAllRelationshipManager');
Route::get('applocation', 'HomeController@applocation');
/* All customer app routes */
Route::post('app_customer_login', 'HomeController@appCustomerLogin');
Route::post('app_contactus', 'HomeController@appContactUs');
Route::post('app_addcustomer', 'HomeController@addCustomer');
Route::post('app_updatecustomer', 'HomeController@updateCustomer');
Route::post('app_customer_profile', 'HomeController@appCustomerProfile');
Route::post('customer_resetpassword', 'HomeController@customerResetPassword');
Route::post('generate_otp', 'HomeController@generateOtp');
Route::post('verify_otp', 'HomeController@verifyOtp');
Route::get('app_track_order/{id}', 'HomeController@trackOrder');
Route::post('app_track_order_status', 'HomeController@trackOrderStatus');
Route::get('app_track_inquiry/{id}', 'HomeController@trackInquiry');
Route::get('app_customer_info/{id}', 'HomeController@customerInfo');
Route::post('app_customer_status', 'HomeController@customerStatus');
Route::get('app_customer_inquiry/{id}', 'HomeController@customerInquiry');
Route::get('app_customer_orders/{id}', 'HomeController@customerOrders');
Route::get('appprintdeliveryorder', 'HomeController@appprintdeliveryorder');
Route::get('appprintdeliverychallan', 'HomeController@appprintdeliverychallan');
Route::get('get_inventory_report', 'InventoryController@getInventoryReport');
Route::get('get_inventory_price_list', 'InventoryController@getInventoryPriceList');
Route::get('set_inventory_price', 'InventoryController@setInventoryPrice');
Route::post('export_inventory_price_list', 'InventoryController@exportinventoryPriceList');
Route::post('export_inventory_report', 'InventoryController@exportinventoryReport');
//app sms
Route::post('test_sms', 'HomeController@test_sms');
Route::post('appsyncinquiry_sms', 'HomeController@appsyncinquiry_sms');
Route::post('appsyncinquiryedit_sms', 'HomeController@appsyncinquiryedit_sms');
Route::post('appsyncorder_sms', 'HomeController@appsyncorder_sms');
Route::post('appsyncorderedit_sms', 'HomeController@appsyncorderedit_sms');
Route::post('appsyncdeliveryorder_sms', 'HomeController@appsyncdeliveryorder_sms');
Route::post('appsyncdeliverychallan_sms', 'HomeController@appsyncdeliverychallan_sms');
Route::post('appsyncpurchaseorder_sms', 'HomeController@appsyncpurchaseorder_sms');
Route::post('appsyncpurchaseorderedit_sms', 'HomeController@appsyncpurchaseorderedit_sms');
Route::post('appsyncpurchasechallan_sms', 'HomeController@appsyncpurchasechallan_sms');
Route::post('appsyncpurchaseadvise_sms', 'HomeController@appsyncpurchaseadvise_sms');
Route::post('userotp_sms', 'HomeController@userotp_sms');

//app graphs
Route::get('appssyncgraph_inquiry', 'HomeController@appssyncgraph_inquiry');
Route::get('appssyncgraph_order', 'HomeController@appssyncgraph_order');
Route::get('appssyncgraph_delivery_challan', 'HomeController@appssyncgraph_delivery_challan');


//app aproval process - inquiry



//performance module- labours
Route::get('appalllabours', 'HomeController@appalllabours');
Route::post('appaddlabour', 'HomeController@appaddlabour');
Route::post('appupdatelabour', 'HomeController@appupdatelabour');
Route::get('applabourperformance', 'HomeController@applabourperformance');



Route::get('getinfo', 'PasswordController@getinfo');
/* All customer app routes ends here */
/*
  Route::get('demorouteandroid', 'HomeController@demorouteandroid');
  Route::get('androidtesting', 'HomeController@androidtesting');
  Route::get('devicename', 'HomeController@devicename');
  Route::get('devicetesting', 'HomeController@devicetesting');
  Route::get('phonetesting', 'HomeController@phonetesting');
  Route::get('robottesting', 'HomeController@robottesting');
  Route::get('platformname', 'HomeController@platformname');
  Route::get('platformversion', 'HomeController@platformversion');
  Route::get('browserversion', 'HomeController@browserversion');
 */
Route::get('updatedata', 'HomeController@updatedata');
Route::get('phpversion', 'WelcomeController@phpversion');
Route::get('whatsapp', 'WelcomeController@whatsapp');
Route::get('test', 'WelcomeController@test');
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
Route::get('update_opening_stock', 'InventoryController@updateOpeningStock');
Route::get('flag_order', 'OrderController@flagOrder');
Route::get('dropboxfile', 'WelcomeController@dropboxfile');
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
    Route::get('graph-inquiry', 'DashboardController@graph_inquiry');
    Route::get('graph-order', 'DashboardController@graph_order');
    Route::get('graph-delivery-challan', 'DashboardController@graph_delivery_challan');
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
    Route::resource('territory', 'TerritoryController');
    Route::get('inventory_report', 'InventoryController@inventoryReport');
    Route::get('inventory_price_list', 'InventoryController@inventoryPriceList');

    Route::resource('pending_customers', 'PendingCustomerController');
    Route::resource('customer_manager', 'CustomerManagerController');
    Route::post('add_pending_customers/{id}', 'PendingCustomerController@add_pending_customers');

    Route::resource('users', 'UsersController');

    Route::resource('product_category', 'ProductController');
    Route::get('update_price', 'ProductController@update_price');
    Route::post('update_all_price', 'ProductController@update_all_price');

    Route::resource('product_sub_category', 'ProductsubController');
    Route::get('get_product_category', 'ProductsubController@get_product_category');
    Route::get('get_product_type', 'ProductsubController@get_product_type');
    Route::resource('inventory', 'InventoryController');
    Route::get('fillinventorylist', 'InventoryController@fillInventoryList');
    Route::get('fetch_inventory_product_name', 'InventoryController@fetchInventoryProductName');
    Route::post('update_difference', 'ProductsubController@update_difference');
    Route::get('update_inventory', 'InventoryController@update_inventory');
    Route::get('export_inventory', 'InventoryController@export_inventory');
    Route::get('fetch_product_size', 'ProductsubController@fetch_product_size');
    Route::get('fetch_product_name', 'ProductsubController@fetch_product_name');
    Route::post('delivery_order/{id}-delete', 'DeliveryOrderController@destroy');
    Route::resource('delivery_order', 'DeliveryOrderController');
    Route::resource('pending_delivery_order', 'DeliveryOrderController@pending_delivery_order');
    Route::resource('purchase_order_daybook', 'PurchaseDaybookController');
    Route::post('purchase_order_daybook/{id}', 'PurchaseDaybookController@destroy');
    Route::post('delete_all_daybook', 'PurchaseDaybookController@delete_all_daybook');
    Route::post('export_purchasedaybook', 'PurchaseDaybookController@expert_purchase_daybook');
    Route::get('print_purchase_daybook', 'PurchaseDaybookController@print_purchase_daybook');
    Route::get('change_password', 'PasswordController@getPassword');
    Route::post('change_password', 'PasswordController@postPassword');
    Route::post('inquiry/delete', 'InquiryController@destroy');
    Route::get('getenviroment', 'InquiryController@getenviroment');
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
    Route::any('order/{id}-track', 'OrderController@track');
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
    Route::resource('sales_daybook', 'SalesDaybookController');
    Route::post('delete_sales_daybook/{id}', 'SalesDaybookController@delete_challan');
    Route::post('delete_multiple_challan', 'SalesDaybookController@delete_multiple_challan');
    Route::post('sales_daybook_date', 'SalesDaybookController@challan_date');
    Route::get('print_purchase_challan/{id}', 'PurchaseChallanController@print_purchase_challan');
    Route::get('print_inventory_report/{id}', 'InventoryController@print_inventory_report');
    Route::get('print_inventory_price_list/{id}', 'InventoryController@print_inventory_price_list');
    Route::get('print_delivery_order/{id}', 'DeliveryOrderController@print_delivery_order');
    Route::get('print_delivery_challan/{id}', 'DeliveryChallanController@print_delivery_challan');
    Route::get('place_order/{id}', 'InquiryController@place_order');
    Route::post('store_order/{id}', 'InquiryController@store_place_order');
    Route::post('export_sales_daybook', 'SalesDaybookController@export_sales_daybook');
//    Route::post('export_purchase_orders', 'PurchaseOrderController@export_purchase_orders');
    Route::get('export_product_size', 'ProductsubController@exportProductSize');
    Route::get('print_sales_order_daybook', 'SalesDaybookController@print_sales_order_daybook');
    Route::post('get_product_weight', 'ProductsubController@get_product_weight');
    Route::get('fetch_product_price', 'DeliveryOrderController@product_price');
    Route::post('upload_excel', 'WelcomeController@upload_excel');
    Route::resource('excel_import', 'WelcomeController@excel_import');
    Route::resource('excel_import_customer', 'WelcomeController@excel_import_customer');
    Route::any('excel_export_customer', 'WelcomeController@excel_export_customer');
    Route::post('upload_customer_excel', 'WelcomeController@upload_customer_excel');
    Route::any('excel_export_labours', 'WelcomeController@excel_export_labours');
    Route::any('excel_export_territory', 'WelcomeController@excel_export_territory');
    Route::any('excel_export_loaded_by', 'WelcomeController@excel_export_loaded_by');
    Route::resource('import_delivery_location', 'WelcomeController@import_delivery_location');
    Route::post('process_import_delivery_location', 'WelcomeController@process_import_delivery_location');
    Route::get('getMyIP', 'WelcomeController@getMyIP');
    
    Route::get('performance/loaded-by/loaded-by-performance', 'LoadByController@performance');
    Route::resource('performance/loaded-by', 'LoadByController');
    
    /*Labour*/
     Route::get('performance/labours/labour-performance', 'LabourController@labourPerformance');

    Route::resource('performance/labours', 'LabourController');
        Route::get('performance', 'LabourController@index');
    
    
    
    
    /* Helpful routes for developers */
    Route::get('delete_reports', 'WelcomeController@delete_reports');
    Route::get('removedata/{tablename}', 'WelcomeController@removedata');
    Route::get('emptydata/{tablename}', 'WelcomeController@emptydata');
    Route::get('showdata/{tablename}', 'WelcomeController@showdata');

    Route::get('copy_customers', 'WelcomeController@copy_customers');
    Route::get('delete_cust_from_user', 'WelcomeController@delete_cust_from_user');

    Route::get('showtableinfo/{tablename}', 'WelcomeController@showtableinfo');
    Route::get('showtableinformation/{tablename}', 'WelcomeController@showtableinformation');
    Route::get('updatecolumndata/{tablename}/{column}/{value}', 'WelcomeController@updatecolumndata');
    Route::get('updatecolumndatavalue/{tablename}/{column}/{value}/{wherekey}/{wherevalue}', 'WelcomeController@updatecolumndatavalue');
    Route::get('checkdatabaseinfo', 'WelcomeController@checkdatabaseinfo');

    /* Helpful routes for developers ends here */
    Route::any('database_backup_test', 'WelcomeController@database_backup_test');
    Route::any('database_backup_live', 'WelcomeController@database_backup_live');
    Route::any('database_backup_local', 'WelcomeController@database_backup_local');
    
    /*Use ones approved orders and inquiry module*/
    Route::get('make_approved', 'WelcomeController@make_approved');
    Route::get('get_set_labours', 'WelcomeController@get_set_labours');
    
});

Route::get('export/{type}', 'WelcomeController@exportExcel');
Route::get('get_server_data', 'WelcomeController@get_server_data');
Route::get('delete_order_data/{table}/{col}/{cvalue}', 'WelcomeController@delete_order_data');
Route::get('delete_test_data/{table}/{col}/{cvalue}', 'WelcomeController@delete_test_data');
Route::get('clear_completed_records', 'CronDeleteRecordsController@index');
Route::post('update_all_sizes', 'ProductsubController@update_all_sizes_difference');
Route::any('update_user_role', 'WelcomeController@update_user_role');
Route::any('updatecity_delievrylocation', 'HomeController@updatecity_delievrylocation');

Route::post('export-delivery-order', 'DeliveryOrderController@exportDeliveryOrderBasedOnStatus');
Route::post('export-delivery-challan', 'DeliveryChallanController@exportDeliveryChallanBasedOnStatus');
Route::post('export-order', 'OrderController@exportOrderBasedOnStatus');
Route::post('export-purchase-order', 'PurchaseOrderController@exportPurchaseOrderBasedOnStatus');
Route::post('export-purchase-advise', 'PurchaseAdviseController@exportPurchaseAdviseBasedOnStatus');
Route::post('export-purchase-challan', 'PurchaseChallanController@exportPurchaseChallanBasedOnStatus');
Route::get('export-inquiry/{inquiry_status}', 'InquiryController@exportinquiryBasedOnStatus');
Route::get('get-data', 'DeliveryOrderController@get_data');

Route::get('reponse/dropbox/callback', function() {
    echo "Comes";
});

Route::get('recover', 'SalesDaybookController@recover');
Route::get('dropbax-demo-functionality', function() {
    $url = 'https://www.dropbox.com/oauth2/authorize';
    $url = '?response_type=';
    $curl_object = curl_init();
    curl_setopt($curl_object, CURLOPT_URL, $url);
    curl_setopt($curl_object, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl_object, CURLOPT_POSTFIELDS, $json_request);
    curl_setopt($curl_object, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_object, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    /* Get response from QPX API */
    $response_output = curl_exec($curl_object);
    /* Close CRUL connection */
    curl_close($curl_object);
});


//Route::get('whatsappRegistration', function() {
//    $username = "919429786848";
//    $debug = true;
//    $r = new Registration($username, $debug);
//    $r->codeRequest('sms'); // could be 'voice' too
//});

Route::get('receipt-master/journal', 'ReceiptMasterController@create_journal_receipt');
Route::get('receipt-master/bank', 'ReceiptMasterController@create_bank_receipt');
Route::get('receipt-master/cash', 'ReceiptMasterController@create_cash_receipt');
Route::get('receipt-master/store-journal', 'ReceiptMasterController@store-journal');
Route::get('receipt-master/store-bank', 'ReceiptMasterController@store_bank');
Route::get('receipt-master/store-cash', 'ReceiptMasterController@store_cash');

Route::resource('receipt-master', 'ReceiptMasterController');

Route::resource('account', 'CollectionUserController');
Route::post('account/export_collection_user','CollectionUserController@export_collection_users');




