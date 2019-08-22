<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Vendor;
use View;
use Hash;
use Auth;
use App;
use Redirect;
use App\User;
use App\DeliveryLocation;
use App\ProductCategory;
use App\CustomerProductDifference;
use App\Customer;
use Input;
use App\States;
use App\QuickbookToken;
use App\City;
use App\Inquiry;
use App\Order;
use App\DeliveryOrder;
use App\DeliveryChallan;
use App\PurchaseOrder;
use App\PurchaseAdvise;
use App\PurchaseChallan;
use Config;
use App\ProductType;
use App\Territory;
use App\TerritoryLocation;
use App\Repositories\DropboxStorageRepository;
use App\CollectionUser;
use Illuminate\Support\Facades\DB;
use App\Customer_receipts;
use App\Receipt;
use App\ProductSubCategory;
use Response;
use App\CustomerReceiptsDebitedTo;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use QuickBooksOnline\API\Facades\Item;

class CommonController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }


    function quickbook_create_customer($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        // $dataService->throwExceptionOnError(true);
        // dd($resultingCustomerObj);
        $customerObj = \QuickBooksOnline\API\Facades\Customer::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }

    function quickbook_create_supplier($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = Vendor::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }

    function getToken(){
       require_once base_path('quickbook/vendor/autoload.php');
       $quickbook = App\QuickbookToken::find(2);
       return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "193514891354844",
            'baseUrl' => "Production"
       ));
    }


    function refresh_token(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(2);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }

    function quickbook_create_item($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = Item::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }


    
    /**
     * Store a newly created customer in database.
     */
    public function index() {        
       $customer_data = Customer::where('quickbook_a_customer_id', '=', NULL)->get();
       // $customer = Customer::where('id', 772)->where('quickbook_customer_id', '=', NULL)->first();
        foreach ($customer_data as $customer) {

            $users = new User();
            $users->first_name = $customer->owner_name;
            $users->role_id = '5';
            if($customer->is_supplier=="no" || $customer->is_supplier== "")
                $status ='no';
            else
                $status ='yes';
            $Qdata = [       
                "GivenName"=>  $customer->owner_name,
                "FullyQualifiedName"=> $customer->owner_name,
                "CompanyName"=>  $customer->company_name,
                "DisplayName"=>  $customer->owner_name,
                "PrimaryPhone"=>  [
                    "FreeFormNumber"=>  $customer->phone_number1
                ]                
            ];         

            if($status == 'yes'){
                $res_q = $this->quickbook_create_supplier($Qdata);
                if($res_q['status']){
                    $customer->quickbook_a_supplier_id = $res_q['message']->Id;
                }
            } else{
                $res = $this->quickbook_create_customer($Qdata);
                if($res['status']){
                    $customer->quickbook_a_customer_id = $res['message']->Id;
                    if($status == 'yes'){
                        $res_q = $this->quickbook_create_supplier($Qdata);
                        if($res_q['status']){
                            $customer->quickbook_a_supplier_id = $res_q['message']->Id;
                        }
                    }
                }
                else{
                    $this->refresh_token();
                    $res = $this->quickbook_create_customer($Qdata);
                    if($res['status']){
                        $customer->quickbook_a_customer_id = $res['message']->Id;
                        if($status == 'yes'){
                            $res_q = $this->quickbook_create_supplier($Qdata);
                            if($res_q['status']){
                                $customer->quickbook_a_supplier_id = $res_q['message']->Id;
                            }
                        }
                    }
                }
            }

           $customer->save();
            
        }   //This is end forecah loop
        // if ($customer->save() && $users->save()) {
        if ($customer) {
            $product_category_id = Input::get('product_category_id');
            if (isset($product_category_id)) {
                foreach ($product_category_id as $key => $value) {
                    if (Input::get('product_differrence')[$key] != '') {
                        $product_difference = new CustomerProductDifference();
                        $product_difference->product_category_id = $value;
                        $product_difference->customer_id = $customer->id;
                        $product_difference->difference_amount = Input::get('product_differrence')[$key];
                        $product_difference->save();
                    }
                }
            }

            //         update sync table         
            $tables = ['customers', 'users'];
            $ec = new WelcomeController();
            $ec->set_updated_date_to_sync_table($tables);
            /* end code */
            return redirect('customers')->with('success', 'Customer Successfully added');
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
        }
    }

    public function product_store() {
       
        // $ProductSubCategory = ProductSubCategory::with('product_category')->where('id',1759)->first();
        $product_category = ProductSubCategory::with('product_category')->where('quickbook_a_item_id','=',NULL)->get();
        foreach ($product_category as $ProductSubCategory) {
            // dd($ProductSubCategory);
        $Qdata = [
            "Name" => $ProductSubCategory->alias_name,
            "Active" => true,
            "FullyQualifiedName" => $ProductSubCategory->alias_name,
            "UnitPrice" => $ProductSubCategory->product_category->price + $ProductSubCategory->difference,
            "Type" => "NonInventory",
            "IncomeAccountRef"=> [
                "value"=> 3,
                "name" => "IncomRef" 
            ],
            "TrackQtyOnHand"=>false,            

        ];
        $res = $this->quickbook_create_item($Qdata);
        if($res['status']){
            $ProductSubCategory->quickbook_a_item_id = $res['message']->Id;
        }
        else{
            $this->refresh_token();
            $res = $this->quickbook_create_item($Qdata);
            if($res['status']){
                $ProductSubCategory->quickbook_a_item_id = $res['message']->Id;
            }
        }
        $ProductSubCategory->save();
        } //  end foreach
        return redirect('customers')->with('success', 'Customer Successfully added');
    }   
    public function customer_update() {
        
       $customer_data = Customer::get();       
        foreach ($customer_data as $customer) {
                    Customer::where('id', $customer->id)->update(array(
                    'quickbook_a_customer_id' =>  NULL,
                    'quickbook_customer_id'   =>  NULL,                  
                    'quickbook_supplier_id'   =>  NULL,                  
                    'quickbook_a_supplier_id'   =>  NULL                  
                ));            
        } //  end foreach
        return redirect('customers')->with('success', 'Customer Succesfully Update');
    } 
    public function product_update() {
       
       $product_category = ProductSubCategory::get();       
        foreach ($product_category as $ProductSubCategory) {
                    ProductSubCategory::where('id', $ProductSubCategory->id)->update(array(
                    'quickbook_item_id' =>  NULL,
                    'quickbook_a_item_id'   =>  NULL                 
                ));            
        } //  end foreach
        return redirect('customers')->with('success', 'Product Succesfully Update');
    } 
    public function delivery_challan_update() {
        
       $delivery_challan = DeliveryChallan::get();       
        foreach ($delivery_challan as $deliverychallan) {
                    DeliveryChallan::where('id', $deliverychallan->id)->update(array(
                    'doc_number' =>  NULL                
                ));            
        } //  end foreach
        return redirect('customers')->with('success', 'Delivery Challan Succesfully Update');
    } 
    // public function quickbook_token_update() {
    //      QuickbookToken::where('id', 1)->update(array(
    //                 'client' =>  "Q0B4zFncEB9WyejnuKSFdpNJvKxPIYnaT4EoXWyciapotvMyPk",                
    //                 'secret' =>  "04ZEtCjxboVVVlDec2i6jIQ0za6PokA3I66WPfsw",                
    //                 'access_token' =>  "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..bC2HZNMopNOIhCH8pTo2ZA.itVglq0j_i5CKK-LVzUKyJIYTI1p05tAKt44M5FS4WSBUd_qT3G1wHy-J6TjCIgLVXThphAFTK8MRFEl40LEJLJOIwpel4U0lAmCoUykBHlYQCActH_O51Mf4xX5ZCazKMGfYAzD23-AycM7ZjvUdCGvQUBl05SBf49QzKNtL1WxE-ofvduZDOCXL8-CHdiA8A_h8Ect28BT05LC0p9NE9hKbj3FWDN4bJxXci-E3LuOvNwBXXc2pxQmZ7ToRbQ9l-G8-GYC7WIS6jX2ycyctSG0xPLg_Zmq9YbcrVLrB6nJvNS7DaViUZ45o6YBrioKfVp1D_1bFbvxPW2enRrbiEi4ImMSf-SyCrc8gM0YAc0Hk859KBmkNddrXkFEHmvg6rAx6gp90dYknVy2NdIM1zcaAK6TJuMEvdWFZdkMGajtTreG9YezFjtjZQJKoi-nQdB2CLIMUsY2jEyA1SVMJ4-A_LN8-LaFmHuPDGnV-6oix4DjlH3nEUqYSLPfrxYN4nfNQLZk87K2OMZqxJ2QgZYEYpzqY-Yghk5Ycsj0pCeWAT4BH9u7F7rmXvLewzZBHganvOZbt5qVxP4FN9SLjzn-5lD2C_Na5MuPYKAquzigcncTapecWJ7MLZ8tX6oVnF7uDb6KYGEk8zP7wmX6Ay15-QwJxTRjywix5Xr9oqfy9RUokcOfvyOuRp5tGpGn.GeLoODs_arMKrnNmSZgiPA",                
    //                 'refresh_token' =>  "Q011567932481PYMO8ERDHT9H09MXSwDvpX0n97F3Odr7VXiVL"                
    //         ));            
        
    //     return redirect('customers')->with('success', 'Quickbook Token Succesfully Update');
    // }
    // public function quickbook_token_update_two() {
    //      QuickbookToken::where('id', 2)->update(array(
    //                 'client' =>  "Q0GZydihrT71SxVEGVR2KwUPYdzlkAbkoN48r6hFvvPDwBAbla",                
    //                 'secret' =>  "zqKUsOgtdL2dGOsijs75NxylxluOaBAm14PwlZDS",                
    //                 'access_token' =>  "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..g30DzZcAJucLmrmJDrDSzw.GeI8vDAfVEV1GHEe4wHuYwnGh9Sq1ZiNTfcsSrv64BZGxqXli8XuFzZMUx_SPMS_ztKWybPu7nmLIZ_Wj8Fb2EVyS1Tg7cK8DIY9I-AcOX2ul8_-qu2UIlnygb15Z5_tKhzyvdGTMwTYNZ8UqVaCjKsPgmL2p2bU87mCxNJRUiHvNPcLzDUetH-Ce_RU_yah_VO2Ab7vsrpYty8FCILf7vcqP-ef2stzX_-8IAtZkc7oSTcKl_RKn2IPWWnhyeNIm5iNSNzluPd87u7N8t_QPLHkVb4MpxxBLdufRk2zBUt3cgPzQ8DYlxyXLavGx8nbh1Cdw0OY9g2WxFVSRGd2x0YIZn_MMl-p_cT6-DSPccIuQ-pDLN7LamU4DjyYAYIgg7YQLHxKgpniEYZehS2cbSoz4cRWrQ6IG35pjbDydbRoLm5e9DWW_A6w61ZjRYVsvlBNwUlGsA8gdVM2nV5tWoEcQOtjmJ8TKbt9CcOEtMJfTPzwqbtPD_z72oKZgJYB-Qvanlnil73Nhz4EvPRz0uhGQG5QeEahY0Ojttc6-dyPmm8cw_IWPHjoidTs_5wZ2iKGBuc-bRh4Vf7ZV3TnGG-4g0IefmpJgaYiI3ecByKbRzuT4Z37FM5vi6EL75_5WnYMlCnJPBSw0ZuFtfEKrrTYou-wL1-gPvn-Yv1pk-nOK6WT_mgknvfzCitVgFQf.hT3Qhw2PcU9tuWJ6AvNc8Q",                
    //                 'refresh_token' =>  "Q011567932641rrwwZnueazCUOxWjj7WhjhaRpfxezOQKZAGCn"                
    //         ));            
        
    //     return redirect('customers')->with('success', 'Quickbook Token Succesfully Update');
    // }

}
