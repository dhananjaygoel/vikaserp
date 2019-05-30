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
        $customerObj = \QuickBooksOnline\API\Facades\Customer::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        // dd($resultingCustomerObj);
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
            'QBORealmID' => "123146504590899",
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
    public function index(Request $request) {
        // StoreCustomer
        
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
       $customer_data = Customer::where('quickbook_a_customer_id', '=', NULL)->get();
       // $customer = Customer::where('id', 773)->where('quickbook_a_customer_id', '=', NULL)->first();
        foreach ($customer_data as $customer) {
       // dd($customer);
        // if(count($customer)!=10){

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
                    $customer->quickbook_supplier_id = $res_q['message']->Id;
                }
            } else{
                $res = $this->quickbook_create_customer($Qdata);
                if($res['status']){
                    $customer->quickbook_customer_id = $res['message']->Id;
                    if($status == 'yes'){
                        $res_q = $this->quickbook_create_supplier($Qdata);
                        if($res_q['status']){
                            $customer->quickbook_supplier_id = $res_q['message']->Id;
                        }
                    }
                }
                else{
                    $this->refresh_token();
                    $res = $this->quickbook_create_customer($Qdata);
                    if($res['status']){
                        $customer->quickbook_customer_id = $res['message']->Id;
                        if($status == 'yes'){
                            $res_q = $this->quickbook_create_supplier($Qdata);
                            if($res_q['status']){
                                $customer->quickbook_supplier_id = $res_q['message']->Id;
                            }
                        }
                    }
                }
            }

           $customer->save();
            // }
            // else
            //     break;

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
            return redirect('customers')->with('success', 'Customer Succesfully added');
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
        }
    }


  

   
    /**
     * Display the specific Product.
     */
    public function product_store(Request $request) {
        
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        // 2782, 2671, 3684, 1759
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
            /*"TaxClassificationRef"=>[
                "value"=>1204
            ]*/

        ];
        $res = $this->quickbook_create_item($Qdata);
        if($res['status']){
            $ProductSubCategory->quickbook_item_id = $res['message']->Id;
        }
        else{
            $this->refresh_token();
            $res = $this->quickbook_create_item($Qdata);
            if($res['status']){
                $ProductSubCategory->quickbook_item_id = $res['message']->Id;
            }
        }
        $ProductSubCategory->save();
        } //  end foreach
        return redirect('customers')->with('success', 'Customer Succesfully added');
    }   
    public function customer_update(Request $request) {
        // dd($request);
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
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
    public function product_update(Request $request) {
        dd($request);
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
       $product_category = ProductSubCategory::get();       
        foreach ($product_category as $ProductSubCategory) {
                    ProductSubCategory::where('id', $ProductSubCategory->id)->update(array(
                    'quickbook_item_id' =>  NULL,
                    'quickbook_a_item_id'   =>  NULL                 
                ));            
        } //  end foreach
        return redirect('customers')->with('success', 'Product Succesfully Update');
    } 
    public function delivery_challan_update(Request $request) {
        
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
       $delivery_challan = DeliveryChallan::get();       
        foreach ($delivery_challan as $deliverychallan) {
                    DeliveryChallan::where('id', $deliverychallan->id)->update(array(
                    'doc_number' =>  NULL                
                ));            
        } //  end foreach
        return redirect('customers')->with('success', 'Delivery Challan Succesfully Update');
    } 

}
