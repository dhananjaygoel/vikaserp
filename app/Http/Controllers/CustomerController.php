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
use Response;
use App\CustomerReceiptsDebitedTo;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }
    // exit;

    /**
     * Display a listing of the customer.
     */
    public function index(Request $request) {

       /* App\QuickbookToken::where('id',1)->update([
            'access_token' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..WEZNZ4Z0BxPl20eA7PfyAQ.5xt8_Duz0313SZ7Lt0Ov8GmAw3Fq3RIWJFYqqKrpzPEw9rTcB1UEtOaWHhlPqsAfpJeXGhHI8HD5JC2RsCEm5xe2wYH6ABvA7eW4n_e5fC0K78PnsMI6dENc9OJ1j4ZxgIrPRV_N-YI7eUeboapnnzmbJ1BV131UMlfRPOadWu_oBSElXlbC_iSKozHhksFA0QIEPl1UNveVJclqYGZem28186kaCbNRRHKuNH_w1HSKO_OhTlQusA_n12LqThgx8Xf_oL0rwr2Er67ixeYQU17AGJxoh99TJ3PbnXKN9tv_Xkc5G8PuVip9HOq2kC3z8YSu2rQ2xF26Vg-f5ffqpseIP7QFZxe1WwdLZdl8HCRmXyBdICBATmmrvQlbnb7pLdtBssDQtjlUOsujLDYDFzdvTZO0obk-uXgjGbKjDdUizWlEvhwshFfEcTRyueMADGRO0wcDTLZgkvbcnIRyglNcrWJSRURbO2QJNdOlM4rf2OYr4LdcoQIIziy2UV1lTxw08UjB-o7MdeYx1C1wIyPLW4f0KCv7Vx5fKO2hEcBDUJ50GVxVOwAiGtXgoRHo0j3iPsvzyAVU9GEcwfqhWlu9SPyX2EJ99mdemaor9MP669LUSc0MF67yEVyYZKWf5Y6ccECvx5gTq6SMSViCXuINcW6GANhE_6otJM-pGeP2BmP74_H_qjQGmECugDsT.pFD-KvwXGW93-7aqx8tiRA',
            'refresh_token'=>'Q011565329117rhfOkSbEDbtI6cEqojHbO33pbVtyFDdYyE8SG',
        ]);*/

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders');
        }

        $customers = '';

        $customer_filter = Input::get('customer_filter');
        $customers = Customer::orderBy('tally_name', 'asc');

        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';

            $customers = $customers->where(function($query) use($term) {
                        $query->whereHas('city', function($q) use ($term) {
                            $q->where('city_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('deliverylocation', function($q) use ($term) {
                            $q->where('area_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('manager', function($q) use ($term) {
                            $q->where('first_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function ($query1) use($term){
                        $query1->Where('tally_name', 'like', $term)
                            ->orWhere('phone_number1', 'like', $term)
                            ->orWhere('phone_number2', 'like', $term);
                    })
                    ->where('customer_status', '=', 'permanent');

        }
        if (isset($customer_filter) && !empty($customer_filter)) {
            if($customer_filter=='supplier'){
                $customers = $customers->where('is_supplier', '=', 'yes');
            }
            elseif($customer_filter=='customer'){
                $customers = $customers->where('is_supplier', '!=', 'yes');
            }
        }


        $customers = $customers->where('customer_status', '=', 'permanent');
        $customers = $customers->paginate(20);
        $customers->setPath('customers');
        $city = City::all();

        $parameters = parse_url($request->fullUrl());
        $parameters = isset($parameters['query']) ? $parameters['query'] : '';
        Session::put('parameters', $parameters);

        return View::make('customers', array('customers' => $customers, 'city' => $city));
    }

    function quickbook_create_customer($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = \QuickBooksOnline\API\Facades\Customer::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        // print_r($resultingCustomerObj);
        // exit;
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
/* This will start update customer*/

    function quickbook_update_customer($quickbook_id,$data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        // $dataService->throwExceptionOnError(true);
        $resultingObj  = $dataService->FindById('Customer', $quickbook_id);
        $customerObj = \QuickBooksOnline\API\Facades\Customer::update($resultingObj,$data);
        // dd($customerObj);
        $resultingCustomerObj = $dataService->Update($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }

    function quickbook_update_supplier($quickbook_id,$data){
        // dd($quickbook_id);
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        // $dataService->throwExceptionOnError(true);
        $resultingObj  = $dataService->FindById('vendor', $quickbook_id);
        $customerObj = Vendor::update($resultingObj,$data);
        $resultingCustomerObj = $dataService->Update($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }
    /*This is start 2 account All inclusive update the data*/
        function quickbook_update_a_customer($quickbook_a_id,$data){
            require_once base_path('quickbook/vendor/autoload.php');
            $dataService = $this->getTokenAll();
            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
            // $dataService->throwExceptionOnError(true);
            $resultingObj  = $dataService->FindById('Customer', $quickbook_a_id);
            // dd($customerObj);
            $customerObj = \QuickBooksOnline\API\Facades\Customer::update($resultingObj,$data);
            $resultingCustomerObj = $dataService->Update($customerObj);
            $error = $dataService->getLastError();
            if ($error) {
                return ['status'=>false,'message'=>$error->getResponseBody()];
            } else {
                return ['status'=>true,'message'=>$resultingCustomerObj];
            }
        }

        function quickbook_update_a_supplier($quickbook_a_id,$data){
            require_once base_path('quickbook/vendor/autoload.php');
            $dataService = $this->getTokenAll();
            $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
            // $dataService->throwExceptionOnError(true);
            // dd($dataService);
            $resultingObj  = $dataService->FindById('vendor', $quickbook_a_id);
            $customerObj = Vendor::update($resultingObj,$data);
            $resultingCustomerObj = $dataService->Update($customerObj);
            $error = $dataService->getLastError();
            if ($error) {
                return ['status'=>false,'message'=>$error->getResponseBody()];
            } else {
                return ['status'=>true,'message'=>$resultingCustomerObj];
            }
        }
    /*This is end 2 account All inclusive update the data*/
/* This is End for Update */

    function getToken(){
       require_once base_path('quickbook/vendor/autoload.php');
       $quickbook = App\QuickbookToken::find(2);

        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130347495075906",
            'baseUrl' => "Production",
            'minorVersion'=>34
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

/* This is start from 2 account inserted data all inclusive*/

    function quickbook_create_a_customer($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getTokenAll();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = \QuickBooksOnline\API\Facades\Customer::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }
    function getTokenWihtoutGST(){

        require_once base_path('quickbook/vendor/autoload.php');
        // $quickbook = App\QuickbookToken::first();
        $quickbook = App\QuickbookToken::find(1);
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130347492555586",
            'baseUrl' => "Production",
            'minorVersion'=>34
        ));

    }
    function refresh_token_Wihtout_GST(){
        require_once base_path('quickbook/vendor/autoload.php');
        // $quickbook = App\QuickbookToken::first();
        $quickbook = App\QuickbookToken::find(1);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }

    function quickbook_create_a_supplier($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getTokenAll();
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
    function getTokenAll(){
       require_once base_path('quickbook/vendor/autoload.php');
       $quickbook = App\QuickbookToken::find(4);
       return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130347257645096",
            'baseUrl' => "Production"
       ));
    // return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
    //     'auth_mode' => 'oauth2',
    //     'ClientID' => $quickbook->client,
    //     'ClientSecret' => $quickbook->secret,
    //     'accessTokenKey' =>  $quickbook->access_token,
    //     'refreshTokenKey' => $quickbook->refresh_token,
    //     // 'QBORealmID' => "193514891354844",
    //     'QBORealmID' => "4611809164061438748",
    //     'baseUrl' => "Development"
    // ));
    }


    function refresh_token_all(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(4);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }
/* This is end from 2 account inserted data all inclusive*/


public function update_cust_plus_gst(){
    set_time_limit(0);
    $this->refresh_token();
    $dataService = $this->getToken();
    $error = $dataService->getLastError();
    if ($error) {
        $this->refresh_token();
        $dataService = $this->getToken();
    }
    $sr = 1;
    $updateCust = App\Customer::all();
    foreach($updateCust as $cust){
        $cust->update(['quickbook_customer_id' => null]);
    }
    $cust = "select count(*) from Customer";
    $count = $dataService->Query($cust);
    for($i = 1; $i<=$count; $i+=1000){
        $cust_det = "select * from Customer order by Id asc startposition $i maxresults $count";
        $det = $dataService->Query($cust_det);
        // dd($det);
        foreach($det as $key => $cust_id){
            if(isset($cust_id->DisplayName) && $cust_id->DisplayName != ''){
                App\Customer::where('company_name',$cust_id->CompanyName)->update(['quickbook_customer_id'=>$cust_id->Id]);
                echo $sr.".\n";
                echo nl2br($cust_id->Id."\n");
                $sr++;
            }
        }
    }
}

public function update_cust_all_inc(){
    set_time_limit(0);
    $this->refresh_token_Wihtout_GST();
    $dataService = $this->getTokenWihtoutGST();
    $error = $dataService->getLastError();
    if ($error) {
        $this->refresh_token_Wihtout_GST();
        $dataService = $this->getTokenWihtoutGST();
    }
    $sr = 1;
    $updateCust = App\Customer::all();
    foreach($updateCust as $cust){
        $cust->update(['quickbook_a_customer_id' => null]);
    }
    $cust = "select count(*) from Customer";
    $count = $dataService->Query($cust);
    for($i = 1; $i<=$count; $i+=1000){
        $cust_det = "select * from Customer order by Id asc startposition $i maxresults $count";
        $det = $dataService->Query($cust_det);
        // dd($det);
        foreach($det as $key=>$cust_id){
            // dd($cust_id->Name);
            if(isset($cust_id->DisplayName) && $cust_id->DisplayName != ''){
                App\Customer::where('company_name',$cust_id->CompanyName)->update(['quickbook_a_customer_id'=>$cust_id->Id]);
                echo $sr.".\n";
                echo nl2br($cust_id->Id."\n");
                $sr++;
            }
        }
    }
}

    /**
     * Show the form for creating a new customer.
     */
    public function create() {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
//            return Redirect::to('orders')->with('error', 'You do not have permission.');
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $managers = User::where('role_id', '=', 0)->get();
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $states = States::orderBy('state_name', 'ASC')->get();
        $cities = City::orderBy('city_name', 'ASC')->get();
        $product_category = ProductCategory::all();
        return View::make('add_customers', array('managers' => $managers, 'locations' => $locations, 'product_category' => $product_category, 'states' => $states, 'cities' => $cities));
    }

    /**
     * Store a newly created customer in database.
     */
    public function store(Request $request) {
// dd('hello this is store');
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = new Customer();
        $users = new User();
        $customer->owner_name = Input::get('owner_name');
        $users->first_name = Input::get('owner_name');

        $users->role_id = '5';
        $validator = Validator::make(Input::all(), Customer::$customers_rules);
        if ($validator->passes()) {
            if(Input::get('phone_number1') != ""){
                $already_exists_mobile_number = Customer::where('phone_number1', Input::get('phone_number1'))->count();
                if ($already_exists_mobile_number > 0) {
                    return Redirect::back()->with('error', 'Mobile number is already associated with another account.');
                }
            }
            $status = Input::get('status');

            $state = States::where('id',Input::get('state'))->first();
            $city = City::where('id',Input::get('city'))->where('state_id',Input::get('state'))->first();

            $Qdata = [
                "GivenName"=>  Input::get('owner_name'),
                "FullyQualifiedName"=> Input::get('tally_name'),
                "CompanyName"=>  Input::get('company_name'),
                "DisplayName"=>  Input::get('tally_name'),
                "PrimaryEmailAddr" => [
                    "Address" => Input::get('email')
                ],
                "PrimaryPhone"=>  [
                    "FreeFormNumber"=>  Input::get('phone_number1')
                ],
                "BillAddr"=> [
                    "Country"=> "India",
                    "CountrySubDivisionCode"=> $state->state_name,
                    "City"=> $city->city_name,
                    "PostalCode"=> Input::get('zip'),
                    "Line1" => Input::get('address1'),
                    "Line2" => Input::get('address2'),
                ],
            ];
            $inclusivecustomerid ="";
            $gstcustomerid = "";
            $dataService = $this->getTokenWihtoutGST();
            // $newCustomerObj = Vendor::create($Qdata);
            $newCustomerObj = \QuickBooksOnline\API\Facades\Customer::create($Qdata);
            // dd($newCustomerObj);
            $newcus = $dataService->add($newCustomerObj);
            $error = $dataService->getLastError();
            if ($error) {
                $this->refresh_token_Wihtout_GST();
                $dataService = $this->getTokenWihtoutGST();
            }
            else{
                $inclusivecustomerid =  $newcus->Id;
            }
            $nextdataservice = $this->getToken();
            $newcustoinclusive = $nextdataservice->add($newCustomerObj);
            $error1 = $nextdataservice->getLastError();
            if ($error1) {
                $this->refresh_token();
                $dataService = $this->getToken();
            }
            else{
                $gstcustomerid =  $newcustoinclusive->Id;
            }
            $customer->quickbook_a_customer_id  = $inclusivecustomerid;
            $customer->quickbook_customer_id  = $gstcustomerid;

        /*if(isset($status) && Input::get('status') == 'yes'){
                $res_q = $this->quickbook_create_supplier($Qdata);
                if($res_q['status']){
                    $customer->quickbook_supplier_id = $res_q['message']->Id;
                }
            } else{
                $res = $this->quickbook_create_customer($Qdata);
                if($res['status']){
                    $customer->quickbook_customer_id = $res['message']->Id;
                    $res_q = $this->quickbook_create_supplier($Qdata);
                    if($res_q['status']){
                        $customer->quickbook_supplier_id = $res_q['message']->Id;
                    }
                }
                else{
                    $this->refresh_token();
                    $res = $this->quickbook_create_customer($Qdata);
                    if($res['status']){
                        $customer->quickbook_customer_id = $res['message']->Id;
                        $res_q = $this->quickbook_create_supplier($Qdata);
                        if($res_q['status']){
                            $customer->quickbook_supplier_id = $res_q['message']->Id;
                        }
                    }
                }
            }
            if(isset($status) && Input::get('status') == 'yes'){
                $res_q = $this->quickbook_create_a_supplier($Qdata);
                if($res_q['status']){
                    $customer->quickbook_a_supplier_id = $res_q['message']->Id;
                }
            } else{
                $res = $this->quickbook_create_a_customer($Qdata);
                if($res['status']){
                    $customer->quickbook_a_customer_id = $res['message']->Id;
                    $res_q = $this->quickbook_create_a_supplier($Qdata);
                    if($res_q['status']){
                        $customer->quickbook_a_supplier_id = $res_q['message']->Id;
                    }
                }
                else{
                    $this->refresh_token_all();
                    $res = $this->quickbook_create_a_customer($Qdata);
                    if($res['status']){
                        $customer->quickbook_a_customer_id = $res['message']->Id;
                        $res_q = $this->quickbook_create_a_supplier($Qdata);
                        if($res_q['status']){
                            $customer->quickbook_a_supplier_id = $res_q['message']->Id;
                        }
                    }
                }
            }*/
            if (Input::has('status') && Input::get('status') != "") {
                $customer->is_supplier = Input::get('status');
            }
            if (Input::has('company_name') && Input::get('company_name') != "") {
                $customer->company_name = Input::get('company_name', false);
            }
            if (Input::has('gstin_number') && Input::get('gstin_number') != "") {
                $customer->gstin_number = Input::get('gstin_number');
            }
            if (Input::has('contact_person') && Input::get('contact_person') != "") {
                $customer->contact_person = Input::get('contact_person', false);
            }
            if (Input::has('address1') && Input::get('address1') != "") {
                $customer->address1 = Input::get('address1', false);
            }
            if (Input::has('address2') && Input::get('address2') != "") {
                $customer->address2 = Input::get('address2', false);
            }
            $customer->city = Input::get('city');
            $customer->state = Input::get('state');
            if (Input::has('zip') && Input::get('zip') != "") {
                $customer->zip = Input::get('zip', false);
            }
            if (Input::has('email') && Input::get('email') != "") {
                $customer->email = Input::get('email', false);
                $users->email = Input::get('email', false);
            }
            $customer->tally_name = Input::get('tally_name');
            $customer->phone_number1 = Input::get('phone_number1');
            $users->mobile_number = Input::get('phone_number1');


            if (Input::has('phone_number2') && Input::get('phone_number2') != "") {
                $customer->phone_number2 = Input::get('phone_number2', false);
                $users->phone_number = Input::get('phone_number2', false);
            }
            if (Input::has('username') && Input::get('username') != "") {
                $customer->username = Input::get('username', false);
            }
            if (Input::has('credit_period') && Input::get('credit_period') != "") {
                $customer->credit_period = Input::get('credit_period');
            } else {
                $customer->credit_period = 0;
            }

            if (Input::has('relationship_manager') && Input::get('relationship_manager') != "") {
                $customer->relationship_manager = Input::get('relationship_manager');
            }
            
            $customer->delivery_location_id = Input::get('delivery_location', false);

            if (Input::has('password') && Input::get('password') != '') {
                $customer->password = Hash::make(Input::get('password'));
                $users->password = Hash::make(Input::get('password'));
            }
            $customer->customer_status = 'permanent';

            if ($customer->save() && $users->save()) {
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

                $customer_id = $customer->id;


                /*
                | ----------------------
                | SEND SMS TO ALL ADMINS
                | ----------------------
                */
                $input = Input::all();
                $admins = User::where('role_id', '=', 4)->get();
                $customer = Customer::with('manager')->find($customer_id);

                if (count((array)$admins) > 0) {
                    foreach ($admins as $key => $admin) {
                        $product_type = ProductType::find($request->input('product_type'));
                        $str = "Dear " . $admin->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";
                        if (App::environment('development')) {
                            $phone_number = Config::get('smsdata.send_sms_to');
                        } else {
                            $phone_number = $admin->mobile_number;
                        }
                        $msg = urlencode($str);
                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                        if (SEND_SMS === true) {
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $curl_scraped_page = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }

                if (count((array)$customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->owner_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer->phone_number1;
                    }

                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (count((array)$customer['manager']) > 0) {
                    $str = "Dear " . $customer['manager']->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has created a new customer as " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer['manager']->mobile_number;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
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
        }else{
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Display the specific customer.
     */
    public function show($id) {

        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::with('deliverylocation', 'customerproduct', 'manager')->find($id);
        $states = States::all();
        $cities = City::all();
        $product_category = ProductCategory::all();
        return View::make('customer_details', array('customer' => $customer, 'states' => $states, 'cities' => $cities, 'product_category' => $product_category));
    }

    /**
     * Show the form for editing the specific customer.
     */
    public function edit($id) {

        $states = States::all();
        $cities = City::all();
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $customer = Customer::with('customerproduct')->find($id);
        if (count((array)$customer) < 1) {
            return redirect('customers/')->with('error', 'Trying to access an invalid customer');
        }
        $managers = User::where('role_id', '=', 0)->get();
        $locations = DeliveryLocation::all();
        $product_category = ProductCategory::all();
        //$product_category = ProductCategory::whereNotIn('product_category_name',['Local Coil- Light','Local Coil'])->get();
        return View::make('edit_customers', array('customer' => $customer, 'managers' => $managers, 'locations' => $locations, 'product_category' => $product_category, 'states' => $states, 'cities' => $cities));
    }

    /**
     * Update the specific customer in database.
     */
    public function update($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $validator = Validator::make(Input::all(), Customer::$customers_rules);
        // dd(Input::get('status'));

        if ($validator->passes()) {
            $customer = Customer::find($id);
            $already_exists_mobile_number = Customer::where('phone_number1', '=', Input::get('phone_number1'))
                    ->where('id', '<>', $id)->count();
                    
            if ($already_exists_mobile_number > 0) {
                return Redirect::back()->with('error', 'Mobile number is already associated with another account.');
            }


    //
            $users = User::where('role_id', '=', '5')
                    ->where('email', '=', $customer->email)
                    ->where('mobile_number', '=', $customer->phone_number1)
                    ->where('phone_number', '=', $customer->phone_number2)
                    ->where('created_at', '=', $customer->created_at)
                    ->first();


            if (count((array)$customer) < 1 && count((array)$users) < 1) {
                return redirect('customers/')->with('error', 'Trying to access an invalid customer');
            }

            $customer->owner_name = Input::get('owner_name');
            // if (Input::has('status')) {
                $customer->is_supplier = Input::get('status');
            // }

            if (Input::has('owner_name')) {
                $users->first_name = Input::get('owner_name');
            }


            $users->role_id = '5';

            if (Input::has('company_name')) {
                $customer->company_name = Input::get('company_name');
            }
            if (Input::has('gstin_number')) {
                $customer->gstin_number = Input::get('gstin_number');
            }
            if (Input::has('contact_person')) {
                $customer->contact_person = Input::get('contact_person');
            }
            if (Input::has('address1')) {
                $customer->address1 = Input::get('address1');
            }
            if (Input::has('address2')) {
                $customer->address2 = Input::get('address2');
            }
            $customer->city = Input::get('city');
            $customer->state = Input::get('state');
            if (Input::has('zip')) {
                $customer->zip = Input::get('zip');
            }
    //        if (Input::has('email')) {
            $customer->email = Input::get('email');
            $users->email = Input::get('email');
    //        }

            $customer->tally_name = Input::get('tally_name');
            $customer->tally_category = Input::get('tally_category');
            $customer->tally_sub_category = Input::get('tally_sub_category');
            $customer->phone_number1 = Input::get('phone_number1');
            $users->mobile_number = Input::get('phone_number1');
            if (Input::has('phone_number2')) {
                $customer->phone_number2 = Input::get('phone_number2');
                $users->phone_number = Input::get('phone_number2');
            }
            if (Input::has('vat_tin_number')) {
                $customer->vat_tin_number = Input::get('vat_tin_number');
            }
            if (Input::has('excise_number')) {
                $customer->excise_number = Input::get('excise_number');
            }
            if (Input::has('username')) {
                $customer->username = Input::get('username');
            }
            if (Input::has('credit_period')) {
                $customer->credit_period = Input::get('credit_period');
            }
            if (Input::has('relationship_manager')) {
                $customer->relationship_manager = Input::get('relationship_manager');
            }

            $customer->delivery_location_id = Input::get('delivery_location');

            if (Input::has('password') && Input::get('password') != '') {
                $customer->password = Hash::make(Input::get('password'));
                $users->password = Hash::make(Input::get('password'));
            }

            $state = States::where('id',Input::get('state'))->first();
            $city = City::where('id',Input::get('city'))->where('state_id',Input::get('state'))->first();

            $status = Input::get('status');
            $quickbook_id=$customer->quickbook_customer_id;
            $quickbook_a_id=$customer->quickbook_a_customer_id;

            if($quickbook_id)
            {
                $Qdata = [
                    "GivenName"=>  Input::get('owner_name'),
                    "FullyQualifiedName"=> Input::get('tally_name'),
                    "CompanyName"=>  Input::get('company_name'),
                    "DisplayName"=>  Input::get('tally_name'),
                    "PrimaryEmailAddr" => [
                        "Address" => Input::get('email')
                    ],
                    "PrimaryPhone"=>  [
                        "FreeFormNumber"=>  Input::get('phone_number1')
                    ],
                    "BillAddr"=> [
                          "Country"=> "India",
                          "CountrySubDivisionCode"=> $state->state_name,
                          "City"=> $city->city_name,
                          "PostalCode"=> Input::get('zip'),
                          "Line1" => Input::get('address1'),
                          "Line2" => Input::get('address2'),
                    ],
                ];
                $this->refresh_token_Wihtout_GST();
                $dataService = $this->getTokenWihtoutGST();
                $resultingObj = $dataService->FindById('Customer', $quickbook_a_id);
                // dd($resultingObj);
                $customerObj = \QuickBooksOnline\API\Facades\Customer::update($resultingObj,$Qdata);
                $resultingCustomerObj = $dataService->Update($customerObj);
                $error = $dataService->getLastError();
                if ($error) {
                    $this->refresh_token_Wihtout_GST();
                    $dataService = $this->getTokenWihtoutGST();
                }
                else{
                    $resultingCustomerObj = $dataService->Update($customerObj);
                }
                $this->refresh_token();
                $nextdataservice = $this->getToken();
                $nextresultingObj = $nextdataservice->FindById('Customer', $quickbook_id);
                $nextcustomerObj = \QuickBooksOnline\API\Facades\Customer::update($nextresultingObj,$Qdata);
                $nextresultingCustomerObj = $nextdataservice->Update($nextcustomerObj);
                $error1 = $nextdataservice->getLastError();
                if ($error1) {
                    $this->refresh_token();
                    $nextdataservice = $this->getToken();
                }
                else{
                    $nextresultingCustomerObj = $nextdataservice->Update($nextcustomerObj);
                }
                    // $Qdata = [
                    //     // "Id"=> $quickbook_id ,
                    //     "GivenName"=>  Input::get('tally_name'),
                    //     "FullyQualifiedName"=> Input::get('tally_name'),
                    //     "CompanyName"=>  Input::get('company_name'),
                    //     "DisplayName"=>  Input::get('tally_name'),
                    //     "PrimaryPhone"=>  [
                    //         "FreeFormNumber"=>  Input::get('phone_number1')
                    //     ],
                    //         "BillAddr"=> [
                    //           "City"=> Input::get('city'),
                    //           "Line1"=> Input::get('address1')
                    //         ]
                    // ];
                // dd(Input::get('status'));
                    // if(Input::get('status') == 'yes'){
                    //     $res_q = $this->quickbook_update_supplier($quickbook_id,$Qdata);
                        // if($res_q['status']){
                        //     $customer->quickbook_supplier_id = $res_q['message']->Id;
                        // }
                    // } else{
                    //     $res = $this->quickbook_update_customer($quickbook_id,$Qdata);
                    //     if($res['status']){
                           // $customer->quickbook_customer_id = $res['message']->Id;
                           // if(Input::get('status') == 'yes')
                           // {
                           //      $res_q = $this->quickbook_update_supplier($quickbook_id,$Qdata);
                           //      // if($res_q['status']){
                           //      //     $customer->quickbook_supplier_id = $res_q['message']->Id;
                           //      // }
                           //  }
                        // }
                        // else{
                        //     $this->refresh_token();
                        //     $res = $this->quickbook_update_customer($quickbook_id,$Qdata);
                        //     if($res['status']){
                               // $customer->quickbook_customer_id = $res['message']->Id;
                               // if(Input::get('status') == 'yes')
                               // {
                               //      $res_q = $this->quickbook_update_supplier($quickbook_id,$Qdata);
                               //      // if($res_q['status']){
                               //      //     $customer->quickbook_supplier_id = $res_q['message']->Id;
                               //      // }
                               //  }
                        //     }
                        // }
                    // }
                    //start all inclusive
                    // if(Input::get('status') == 'yes'){
                    //     $res_q = $this->quickbook_update_a_supplier($quickbook_a_id,$Qdata);
                    //     if($res_q['status']){
                    //         $customer->quickbook_supplier_id = $res_q['message']->Id;
                    //     }
                    // } else{
                    //     $res = $this->quickbook_update_a_customer($quickbook_a_id,$Qdata);
                    //     if($res['status']){
                           // $customer->quickbook_customer_id = $res['message']->Id;
                            // if(Input::get('status') == 'yes')
                            // {
                            //     // $res_q = $this->quickbook_update_a_supplier($quickbook_a_id,$Qdata);
                            //     // if($res_q['status']){
                            //     //     // $customer->quickbook_supplier_id = $res_q['message']->Id;
                            //     // }
                            // }
                        // }
                        // else{
                        //     $this->refresh_token_all();
                        //     $res = $this->quickbook_update_a_customer($quickbook_a_id,$Qdata);
                        //     if($res['status']){
                               // $customer->quickbook_customer_id = $res['message']->Id;
                               //  if(Input::get('status') == 'yes')
                               // {
                               //      // $res_q = $this->quickbook_update_a_supplier($quickbook_a_id,$Qdata);
                               //      // if($res_q['status']){
                               //      //     // $customer->quickbook_supplier_id = $res_q['message']->Id;
                               //      // }
                               // }
                    //         }
                    //     }
                    // }
                    //end all inclusive
            }
            // dd($customer);
            if ($customer->save() && $users->save()) {
                $product_category_id = Input::get('product_category_id');
                if (isset($product_category_id)) {
                    foreach ($product_category_id as $key => $value) {
                        if (Input::get('product_differrence')[$key] != '') {
                            $product_difference = CustomerProductDifference::where('product_category_id', '=', $value)->first();
                            if (count((array)$product_difference) > 0) {
                                $product_difference = $product_difference;
                            } else {
                                $product_difference = new CustomerProductDifference();
                            }
                            $product_difference->product_category_id = $value;
                            $product_difference->customer_id = $customer->id;
                            $product_difference->difference_amount = Input::get('product_differrence')[$key];
                            $product_difference->save();
                        } else {
                            $product_difference1 = CustomerProductDifference::where('product_category_id', '=', $value)->first();
                            if (count((array)$product_difference1) > 0) {
                                $product_difference1->delete();
                            }
                        }
                    }
                }


                /*
                  | ----------------------
                  | SEND SMS TO  ADMIN AND CUSTOMER
                  | ----------------------
                 */

                $customer = Customer::with('manager')->find($id);

                if (count((array)$customer) > 0) {
                    $total_quantity = '';
                    $str = "Dear " . $customer->owner_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited your profile - " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer->phone_number1;
                    }

                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }

                if (count((array)$customer['manager']) > 0) {
                    $str = "Dear " . $customer['manager']->first_name . "\n" . "DT " . date("j M, Y") . "\n" . Auth::user()->first_name . " has edited a customer - " . Input::get('owner_name') . " kindly check. \nVIKAS ASSOCIATES";

                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $customer['manager']->mobile_number;
                    }
                    $msg = urlencode($str);
                    $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }


                //         update sync table
                $tables = ['customers', 'users'];
                $ec = new WelcomeController();
                $ec->set_updated_date_to_sync_table($tables);
                /* end code */

                $parameter = Session::get('parameters');
                $parameters = (isset($parameter) && !empty($parameter)) ? '?' . Session::get('parameters') : '';
                /* end code */

                return redirect('customers'. $parameters)->with('success', 'Customer details updated successfully');
            } else {
                return Redirect::back()->with('error', 'Some error occoured while saving customer');
            }
        }else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specific customer from database.
     */
    public function destroy($id) {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('customers')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            $customer = Customer::find($id);


            $customer_exist = array();
            $customer_exist['customer_inquiry'] = "";
            $customer_exist['customer_order'] = "";
            $customer_exist['customer_delivery_order'] = "";
            $customer_exist['customer_delivery_challan'] = "";
            $customer_exist['customer_purchase_order'] = "";
            $customer_exist['customer_purchase_advice'] = "";
            $customer_exist['customer_purchase_challan'] = "";

            $customer_inquiry = Inquiry::where('customer_id', $customer->id)->count();
            $customer_order = Order::where('customer_id', $customer->id)->count();
            $customer_delivery_order = DeliveryOrder::where('customer_id', $customer->id)->count();
            $customer_delivery_challan = DeliveryChallan::where('customer_id', $customer->id)->count();
            $customer_purchase_order = PurchaseOrder::where('supplier_id', $customer->id)->count();
            $customer_purchase_advice = PurchaseAdvise::where('supplier_id', $customer->id)->count();
            $customer_purchase_challan = PurchaseChallan::where('supplier_id', $customer->id)->count();

            $cust_msg = 'Customer can not be deleted as details are associated with one or more ';
            $cust_flag = "";

            if ($customer_inquiry > 0) {
                $customer_exist['customer_inquiry'] = 1;
                $cust_msg .= "Inquiry";
                $cust_flag = 1;
            }

            if (($customer_order) > 0) {
                $customer_exist['customer_order'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Order";
                } else {
                    $cust_msg .= "Order";
                }
                $cust_flag = 1;
            }

            if (($customer_delivery_order) > 0) {
                $customer_exist['customer_delivery_order'] = 1;

                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Delievry Order";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Delievry Order";
                } else {
                    $cust_msg .= "Delievry Order";
                }
                $cust_flag = 1;
            }

            if (($customer_delivery_challan) > 0) {
                $customer_exist['customer_delivery_challan'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Delievry Challan";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Delievry Challan";
                } elseif ($customer_exist['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Delievry Challan";
                } else {
                    $cust_msg .= "Delievry Challan";
                }
                $cust_flag = 1;
            }

            if (($customer_purchase_order) > 0) {
                $customer_exist['customer_purchase_order'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } elseif (['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } elseif ($customer_exist['customer_delivery_challan'] == 1) {
                    $cust_msg .= ", Purchase Order";
                } else {
                    $cust_msg .= "Purchase Order";
                }
                $cust_flag = 1;
            }

            if (($customer_purchase_advice) > 0) {
                $customer_exist['customer_purchase_advice'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_delivery_challan'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } elseif ($customer_exist['customer_purchase_order'] == 1) {
                    $cust_msg .= ", Purchase Advice";
                } else {
                    $cust_msg .= "Purchase Advice";
                }
                $cust_flag = 1;
            }

            if (($customer_purchase_challan) > 0) {
                $customer_exist['customer_purchase_challan'] = 1;
                if ($customer_exist['customer_inquiry'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_order'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_delivery_order'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_delivery_challan'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_purchase_order'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } elseif ($customer_exist['customer_purchase_advice'] == 1) {
                    $cust_msg .= ", Purchase Challan";
                } else {
                    $cust_msg .= "Purchase Challan";
                }
                $cust_flag = 1;
            }

            if ($cust_flag == 1) {
                $parameter = Session::get('parameters');
                $parameters = (isset($parameter) && !empty($parameter)) ? '?' . Session::get('parameters') : '';

                return redirect('customers'. $parameters)->with('error', $cust_msg);
            } else {
                $quickbook_id=$customer->quickbook_customer_id;
                $quickbook_a_id=$customer->quickbook_a_customer_id;
                if(!empty($quickbook_id) && !empty($quickbook_a_id)){
                    $Qdata = [
                        "Active"=> false,
                        "SyncToken" => "0",
                    ];
                    $this->refresh_token_Wihtout_GST();
                    $dataService = $this->getTokenWihtoutGST();
                    $custom_query = "select * from Customer where Id='".$quickbook_a_id."'";
                    $customer_details = $dataService->Query($custom_query);
                    $customerObj = \QuickBooksOnline\API\Facades\Customer::update($customer_details[0],$Qdata);
                    $resultingCustomerObj = $dataService->Update($customerObj);
                    $error = $dataService->getLastError();
                    if ($error) {
                        $this->refresh_token_Wihtout_GST();
                        $dataService = $this->getTokenWihtoutGST();
                    }
                    else{
                        $resultingCustomerObj = $dataService->Update($customerObj);
                    }
                    // for plus gst account
                    $this->refresh_token();
                    $nextdataservice = $this->getToken();
                    $nextcustom_query = "select * from Customer where ID='".$quickbook_id."'";
                    $nextcust_details = $nextdataservice->Query($nextcustom_query);
                    $nextcustomerObj = \QuickBooksOnline\API\Facades\Customer::update($nextcust_details[0],$Qdata);
                    $nextresultingCustomerObj = $nextdataservice->Update($nextcustomerObj);
                    $error1 = $nextdataservice->getLastError();
                    if ($error1) {
                        $this->refresh_token();
                        $nextdataservice = $this->getToken();
                    }
                    else{
                        $nextresultingCustomerObj = $nextdataservice->Update($nextcustomerObj);
                    }
                }

                $customer->delete();
                $user = User::where('email', '=', $customer->email)
                        ->where('first_name', '=', $customer->owner_name)
                        ->where('mobile_number', '=', $customer->phone_number1)
                        ->where('created_at', '=', $customer->created_at)
                        ->delete();

                //         update sync table
                $tables = ['customers', 'users'];
                $ec = new WelcomeController();
                $ec->set_updated_date_to_sync_table($tables);
                /* end code */

                $parameter = Session::get('parameters');
                $parameters = (isset($parameter) && !empty($parameter)) ? '?' . Session::get('parameters') : '';
            /* end code */

                return redirect('customers'. $parameters)->with('success', 'Customer deleted successfully.');
            }
        } else {
            return Redirect::to('customers')->with('error', 'Invalid password');
        }
    }

    /**
     * App track order for customer
     */
    public function trackOrderStatus($order_id) {
        $input_data = Input::all();
        if (isset($input_data['order_id'])) {
            $order_info = (json_decode($input_data['order_id']));
            if (isset($order_info[0])) {
                $order_id = $order_info[0]->order_id;
            } else
                $order_id = 0;
        }
        else {
            return json_encode(array('result' => false, 'track_order_status' => false, 'message' => 'Order not found'));
        }


        if (isset($input_data['customer_id'])) {
            $customer_info = (json_decode($input_data['customer_id']));
            if (isset($customer_info[0]))
                $customer_id = $customer_info[0]->customer_id;
            else
                $customer_id = 0;
        }
        $order_status_responase = array();
        if (isset($order_id) && $order_id > 0 && isset($customer_id) && $customer_id > 0) {

            $order_status_responase['order_details'] = Order::with('all_order_products')->where('id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();

            $order_status_responase['delivery_order_details'] = DeliveryOrder::with('delivery_product')->where('order_id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();

            $order_status_responase['delivery_challan_details'] = DeliveryChallan::with('delivery_challan_products')->where('order_id', '=', $order_id)->where('customer_id', '=', $customer_id)->get();
        } else {
            return json_encode(array('result' => false, 'track_order_status' => false, 'message' => 'Order not found'));
        }

        return json_encode($order_status_responase);
    }

    /*
      | Get city list per state for forms
      | @return Json Responce
     */

    public function get_city() {

        $state_id = Input::get('state');
        if($state_id ==0){
           $data = City::orderBy('city_name', 'ASC')->get();
        }else{
           $data = City::where('state_id', $state_id)->get();
        }
        $city = array();
        $i = 0;
        foreach ($data as $key => $val) {
            $city[$i]['id'] = $data[$key]->id;
            $city[$i]['city_name'] = $data[$key]->city_name;
            $i++;
        }
        echo json_encode(array('city' => $city));
        exit;
    }

    /*
      | Show list of all products category
      | Used to set price per customer
     */

    public function set_price($id = "") {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }

        $customer_id = array('id' => $id);
        $cutomer_difference = CustomerProductDifference::where('customer_id', $id)->get();
        $product_category = ProductCategory::all();
        return view('set_price', compact('cutomer_difference', 'product_category', 'customer_id'));
    }

    /*
      | Update Individual customer product category
      | Difference
     */

    public function update_set_price() {

        $customer_id = Input::get('customer_id');
        $product_differrence = Input::get('product_differrence');
        if (Input::get('product_differrence') != '') {
            $product_difference1 = CustomerProductDifference::where('customer_id', $customer_id)->delete();
            $product_category_id = Input::get('product_category_id');
            if (isset($product_category_id)) {
                foreach ($product_category_id as $key => $value) {
                    if (Input::get('product_differrence')[$key] != '') {
                        $product_difference = new CustomerProductDifference();
                        $product_difference->product_category_id = $value;
                        $product_difference->customer_id = $customer_id;
                        $product_difference->difference_amount = Input::get('product_differrence')[$key];
                        $product_difference->save();
                    }
                }
            }
            return redirect('set_price/' . $customer_id)->with('success', 'Customer Set price successfully updated');
        } else {
            return redirect('set_price/' . $customer_id)->with('error', 'Please enter the customer set price please');
        }
    }

    /*
      | to update bulk price of the product categories
      | Populate forms
     */

    public function bulk_set_price() {


        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $product_type = 1;
        if (Input::get('product_filter') != "") {
            $product_type = Input::get('product_filter');
        }

        if (Input::get('search') != "") {

            $term = '%' . Input::get('search') . '%';

            $customer = Customer::with('customerproduct.product_category')->orderBy('tally_name', 'ASC')
                    ->where(function($query) use($term) {
                        $query->whereHas('city', function($q) use ($term) {
                            $q->where('city_name', 'like', $term)
                            ->orWhere('tally_name', 'like', $term);
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('deliverylocation', function($q) use ($term) {
                            $q->where('area_name', 'like', $term)
                            ->orWhere('tally_name', 'like', $term);
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('manager', function($q) use ($term) {
                            $q->where('first_name', 'like', $term)
                            ->orWhere('tally_name', 'like', $term);
                        });
                    })
                    ->with('city')
                    ->where('customer_status', ' = ', 'permanent')
                    ->paginate(20);
        } else {
            $customer = Customer::with('customerproduct.product_category')->where('customer_status', 'permanent')->orderBy('tally_name', 'ASC')->paginate(20);
        }

        $product_category = ProductCategory::where('product_type_id', $product_type)->get();
        $pipe_category_count = ProductCategory::where('product_type_id', 1)->count();
        $struct_category_count = ProductCategory::where('product_type_id', 2)->count();
        $profile_category_count = ProductCategory::where('product_type_id', 3)->count();
        $customer->setPath('bulk_set_price');
        $product_type = ProductType::all();
        $filter = array(Input::get('product_filter'), Input::get('search'));

        return view('bulk_set_price', compact('customer', 'product_category', 'product_type', 'filter','pipe_category_count','struct_category_count','profile_category_count'));
    }

    /*
      | Update Individual customer product category
      | Difference
     */

    public function save_all_set_price() {

        $data = Input::all();
        $page = "";
        if (isset($data['page']) && $data['page'] != '') {
            $page = $data['page'];
        }
        $product_filter = "";
        if (isset($data['product_filter']) && $data['product_filter'] != '') {
            $product_filter = $data['product_filter'];
        }
        $product_pipe_category = ProductCategory::where('product_type_id', 1)->get();
        $product_structure_category = ProductCategory::where('product_type_id', 2)->get();
        $product_profile_category = ProductCategory::where('product_type_id', 3)->get();
        $product_category = ProductCategory::all();

        foreach ($data['set_diff'] as $key => $value) {

            if (isset($value['cust_id']) && $value['cust_id'] != "") {
                $pipe = $value['pipe'];
                $custid = $value['cust_id'];
                $structure = $value['structure'];
                $profile = $value['profile'];
                $count = CustomerProductDifference::where('customer_id', $custid)->count();
                if ($count == 0) {
                    foreach ($product_category as $value) {
                        if ($value->product_type_id == 1 && isset($pipe) && $pipe != "") {
                            $diff = new CustomerProductDifference();
                            $diff->product_category_id = $value->id;
                            $diff->difference_amount = $pipe;
                            $diff->customer_id = $custid;
                            $diff->save();
                        }
                        if ($value->product_type_id == 2 && isset($structure) && $structure != "") {
                            $diff = new CustomerProductDifference();
                            $diff->product_category_id = $value->id;
                            $diff->difference_amount = $structure;
                            $diff->customer_id = $custid;
                            $diff->save();
                        }
                        if ($value->product_type_id == 3 && isset($profile) && $profile != "") {
                            $diff = new CustomerProductDifference();
                            $diff->product_category_id = $value->id;
                            $diff->difference_amount = $profile;
                            $diff->customer_id = $custid;
                            $diff->save();
                        }
                    }
                } else {

                    if (isset($value['pipe']) && !empty($value['pipe']) && $value['pipe'] != "") {

                        foreach ($product_pipe_category as $curr_category) {
                            $count = CustomerProductDifference::where('product_category_id', $curr_category->id)
                                            ->where('customer_id', $custid)->count();
                            if ($count == 0) {
                                $diff = new CustomerProductDifference();
                                $diff->product_category_id = $curr_category->id;
                                $diff->difference_amount = $pipe;
                                $diff->customer_id = $custid;
                                $diff->save();
                            } else {
                                CustomerProductDifference::where('product_category_id', $curr_category->id)
                                        ->where('customer_id', $custid)->update(array('difference_amount' => $pipe));
                            }
                        }
                    }
                    if (isset($value['structure']) && !empty($value['structure']) && $value['structure'] != "") {
                        foreach ($product_structure_category as $curr_category) {
                            $count = CustomerProductDifference::where('product_category_id', $curr_category->id)
                                            ->where('customer_id', $custid)->count();
                            if ($count == 0) {
                                $diff = new CustomerProductDifference();
                                $diff->product_category_id = $curr_category->id;
                                $diff->difference_amount = $structure;
                                $diff->customer_id = $custid;
                                $diff->save();
                            } else {
                                CustomerProductDifference::where('product_category_id', $curr_category->id)
                                        ->where('customer_id', $custid)
                                        ->update(array('difference_amount' => $structure));
                            }
                        }
                    }
                    if (isset($value['profile']) && !empty($value['profile']) && $value['profile'] != "") {
                        foreach ($product_profile_category as $curr_category) {
                            $count = CustomerProductDifference::where('product_category_id', $curr_category->id)
                                            ->where('customer_id', $custid)->count();
                            if ($count == 0) {
                                $diff = new CustomerProductDifference();
                                $diff->product_category_id = $curr_category->id;
                                $diff->difference_amount = $profile;
                                $diff->customer_id = $custid;
                                $diff->save();
                            } else {
                                CustomerProductDifference::where('product_category_id', $curr_category->id)
                                        ->where('customer_id', $custid)
                                        ->update(array('difference_amount' => $profile));
                            }
                        }
                    }
                }
            }
        }

        return redirect('bulk_set_price?page = ' . $page . '&product_filter = ' . $product_filter)->with('success', 'Customer Set price for the product successfully updated');
    }

    public function get_customers_list() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 6) {
            return redirect()->back();
        }
        $customers = '';
        $loc_arr = [];
        $search = Input::get('search');
        $territory_id = Input::get('territory_filter');
        $location_id = Input::get('location_filter');
        $date_filter = Input::get('date_filter');
        if (Auth::user()->role_id == 0) {
            /* old code */
//            $customers = Customer::with('delivery_challan')
//                            ->with('customer_receipt')
//                            ->with('customer_receipt_debit')
//                            ->with('collection_user_location.collection_user')
//                            ->with('delivery_location')
//                            ->with('collection_user_location')
//                            ->orderBy('created_at', 'desc')
//                            ->whereHas('delivery_challan', function ($query) {
//                                $query->where('challan_status', '=', 'completed');
//                            });

            $customers = Customer::has('customer_receipt')
                    ->orHas('customer_receipt_debit')
                    ->orHas('delivery_challan')
                    ->with('customer_receipt')
                    ->with('customer_receipt_debit')
                    ->with('delivery_challan')
                    ->with('collection_user_location.collection_user')
                    ->with('delivery_location')
                    ->with('collection_user_location')
                    ->orderBy('created_at', 'desc');

            /* new code */

//            $customers = Customer::whereHas('delivery_challan', function ($query) {
//                        $query->where('delivery_challan.challan_status', 'completed');
//                    })
//                    ->with('customer_receipt')
//                    ->with('customer_receipt_debit')
//                    ->with('collection_user_location.collection_user')
//                    ->with('delivery_location')
//                    ->orderBy('created_at', 'desc');
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                        $query->where('challan_status', '=', 'completed');
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                        $query->where('challan_status', '=', 'completed');
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                        $query->where('challan_status', '=', 'completed');
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    $query->where('challan_status', '=', 'completed');
                });

//               dd($customers->toSql());
//                dd($customers->get());
            }
        }
        if (Auth::user()->role_id == 6) {
            $territory_id = Input::get('territory_filter');
            $user_id = Auth::user()->id;
            $user_loc_arr = [];
            $user_territory_arr = [];
            $collection_user_locations = CollectionUser::where('user_id', '=', $user_id)->get();
            foreach ($collection_user_locations as $loc) {
                array_push($user_loc_arr, $loc->location_id);
                if (!in_array($loc->teritory_id, $user_territory_arr)) {
                    array_push($user_territory_arr, $loc->teritory_id);
                }
            }

            $customers = Customer::with(['delivery_challan' => function ($query) {
                            $query->where('delivery_challan.challan_status', 'completed');
                        }])
                    ->with('customer_receipt', 'customer_receipt_debit')
                    ->orderBy('created_at', 'desc')
                    ->whereIn('delivery_location_id', $user_loc_arr);

            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')
                            ->whereIn('id', $user_loc_arr)->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                });
            }
        }




        $customers = $customers->paginate(20)->setPath('due-payment');

        if (isset($user_territory_arr)) {
            $territories = Territory::whereIn('id', $user_territory_arr)->orderBy('created_at', 'DESC')->get();
        } else {
            $territories = Territory::orderBy('created_at', 'DESC')->get();
        }
        return View('customer_list')->with('customers', $customers)
                        ->with('delivery_location', $delivery_location)
                        ->with('territories', $territories);
    }

//
//    public function get_customers_list() {
//        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 6) {
//            return redirect()->back();
//        }
//        $customers = '';
//        $loc_arr = [];
//        $search = Input::get('search');
//        $territory_id = Input::get('territory_filter');
//        $location_id = Input::get('location_filter');
//        $date_filter = Input::get('date_filter');
//        if (Auth::user()->role_id == 0) {
//            /* old code */
////            $customers = Customer::with('delivery_challan')->with('customer_receipt')->with('collection_user_location.collection_user')->with('delivery_location')->with('collection_user_location')->orderBy('created_at', 'desc')
////                                    ->whereHas('delivery_challan', function ($query) {
////                                    $query->where('challan_status','=', 'completed');
////                                    });
//
//            /* new code */
//
//            $customers = Customer::whereHas('delivery_challan', function ($query) {
//                        $query->where('delivery_challan.challan_status', 'completed');
//                    })
//                    ->with('customer_receipt')
//                    ->with('customer_receipt_debit')
//                    ->with('collection_user_location.collection_user')
//                    ->with('delivery_location')
//                    ->orderBy('created_at', 'desc');
//            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
//            if (isset($search) && !empty($search)) {
//                $term = '%' . $search . '%';
//                $customers->Where('tally_name', 'like', $term);
//            }
//            if (isset($territory_id) && !empty($territory_id)) {
//                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
//                foreach ($territory_locations as $loc) {
//                    array_push($loc_arr, $loc->location_id);
//                }
//                $customers->whereIn('delivery_location_id', $loc_arr);
//                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
//            }
//            if (isset($location_id) && !empty($location_id)) {
//                $customers->where('delivery_location_id', '=', $location_id);
//            }
//            if (isset($date_filter) && !empty($date_filter)) {
//                if ($date_filter == 1) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                    });
//                } else
//                if ($date_filter == 3) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
//                    });
//                } else
//                if ($date_filter == 7) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
//                    });
//                }
//            } else {
//                $customers->whereHas('delivery_challan', function ($query) {
//                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                });
//
////               dd($customers->toSql());
////                dd($customers->get());
//            }
//        }
//        if (Auth::user()->role_id == 6) {
//            $territory_id = Input::get('territory_filter');
//            $user_id = Auth::user()->id;
//            $user_loc_arr = [];
//            $user_territory_arr = [];
//            $collection_user_locations = CollectionUser::where('user_id', '=', $user_id)->get();
//            foreach ($collection_user_locations as $loc) {
//                array_push($user_loc_arr, $loc->location_id);
//                if (!in_array($loc->teritory_id, $user_territory_arr)) {
//                    array_push($user_territory_arr, $loc->teritory_id);
//                }
//            }
//
//            $customers = Customer::with(['delivery_challan' => function ($query) {
//                            $query->where('delivery_challan.challan_status', 'completed');
//                        }])
//                    ->with('customer_receipt', 'customer_receipt_debit')
//                    ->orderBy('created_at', 'desc')
//                    ->whereIn('delivery_location_id', $user_loc_arr);
//
//            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')
//                            ->whereIn('id', $user_loc_arr)->get();
//            if (isset($search) && !empty($search)) {
//                $term = '%' . $search . '%';
//                $customers->Where('tally_name', 'like', $term);
//            }
//            if (isset($territory_id) && !empty($territory_id)) {
//                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
//                foreach ($territory_locations as $loc) {
//                    array_push($loc_arr, $loc->location_id);
//                }
//                $customers->whereIn('delivery_location_id', $loc_arr);
//                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
//            }
//            if (isset($location_id) && !empty($location_id)) {
//                $customers->where('delivery_location_id', '=', $location_id);
//            }
//            if (isset($date_filter) && !empty($date_filter)) {
//                if ($date_filter == 1) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                    });
//                } else
//                if ($date_filter == 3) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
//                    });
//                } else
//                if ($date_filter == 7) {
//                    $customers->whereHas('delivery_challan', function ($query) {
//                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
//                    });
//                }
//            } else {
//                $customers->whereHas('delivery_challan', function ($query) {
//                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
//                });
//            }
//        }
//        $customers = $customers->paginate(20)->setPath('due-payment');
//
//        if (isset($user_territory_arr)) {
//            $territories = Territory::whereIn('id', $user_territory_arr)->orderBy('created_at', 'DESC')->get();
//        } else {
//            $territories = Territory::orderBy('created_at', 'DESC')->get();
//        }
//        return View('customer_list')->with('customers', $customers)
//                        ->with('delivery_location', $delivery_location)
//                        ->with('territories', $territories);
//    }





    public function get_customer_details($id) {
        $date_filter = Input::get('date_filter');
        $customer = '';
        $customer = Customer::with(['delivery_challan' => function ($query) {
                        $query->where('delivery_challan.challan_status', 'completed');
                    }])
                ->with('customer_receipt', 'customer_receipt_debit')
                ->find($id);

        $credit_period = $customer->credit_period;
        $settle_filter = Input::get('settle_filter');
        $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                ->where('challan_status', 'completed')
//                ->whereRaw('grand_price!=settle_amount');
                ->whereRaw('CAST(grand_price AS DECIMAL(10,2)) != CAST(settle_amount AS DECIMAL(10,2))');
        if (isset($settle_filter) && $settle_filter != '' && $settle_filter == 'Settled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
//                    ->whereRaw('grand_price=settle_amount');
                    ->whereRaw('CAST(grand_price AS DECIMAL(10,2)) = CAST(settle_amount AS DECIMAL(10,2))');
        }
        if (isset($settle_filter) && $settle_filter == 'Unsettled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
//                    ->whereRaw('grand_price != settle_amount');
                    ->whereRaw('CAST(grand_price AS DECIMAL(10,2)) != CAST(settle_amount AS DECIMAL(10,2))');
        }

        if (isset($date_filter) && !empty($date_filter)) {
            if ($date_filter == 1) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
            } else
            if ($date_filter == 3) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
            } else
            if ($date_filter == 7) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
            }
        } else {
            $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
        }
        $delivery_challans = $delivery_challans->get();

        $discount_user = Customer::where('owner_name', 'like', '%Discount User%')
                        ->orWhere('tally_name', 'like', '%Discount User%')
                        ->select('id')->first();


        if (count((array)$discount_user) && $discount_user->id == $id) {
            $is_discount_user = 'true';
        } else {
            $is_discount_user = 'false';
        }

//        dd(DB::getQueryLog());
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        return View('customer_details_view')->with('customer', $customer)
                        ->with('delivery_challans', $delivery_challans)
                        ->with('delivery_location', $delivery_location)
                        ->with('is_discount_user', $is_discount_user);
    }

    public function print_account_customers(DropboxStorageRepository $connection) {
        $customers = '';
        $loc_arr = [];
        $search = Input::get('search');
        $territory_id = Input::get('territory_filter');
        $location_id = Input::get('location_filter');
        $date_filter = Input::get('date_filter');
        if (Auth::user()->role_id == 0) {
            $customers = Customer::with(['delivery_challan' => function ($query) {
                            $query->where('delivery_challan.challan_status', 'completed');
                        }])
                    ->with('customer_receipt')
                    ->with('collection_user_location.collection_user')
                    ->with('delivery_location')
                    ->with('collection_user_location')
                    ->orderBy('created_at', 'desc');
//            $customers = Customer::with('delivery_challan')->with('customer_receipt')->with('collection_user_location.collection_user')->with('delivery_location')->with('collection_user_location')->orderBy('created_at', 'desc')
//                    ->whereHas('delivery_challan', function ($query) {
//                $query->where('challan_status', '=', 'completed');
//            });
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                });

//               dd($customers->toSql());
//                dd($customers->get());
            }
        }
        if (Auth::user()->role_id == 6) {
            $territory_id = Input::get('territory_filter');
            $user_id = Auth::user()->id;
            $user_loc_arr = [];
            $user_territory_arr = [];
            $collection_user_locations = CollectionUser::where('user_id', '=', $user_id)->get();
            foreach ($collection_user_locations as $loc) {
                array_push($user_loc_arr, $loc->location_id);
                if (!in_array($loc->teritory_id, $user_territory_arr)) {
                    array_push($user_territory_arr, $loc->teritory_id);
                }
            }

            $customers = Customer::with(['delivery_challan' => function ($query) {
                            $query->where('delivery_challan.challan_status', 'completed');
                        }])
                    ->with('customer_receipt')
                    ->orderBy('created_at', 'desc')
                    ->whereIn('delivery_location_id', $user_loc_arr);
//            $customers = Customer::with('delivery_challan')->with('customer_receipt')->orderBy('created_at', 'desc')
//                            ->whereHas('delivery_challan', function ($query) {
//                                $query->where('challan_status', '=', 'completed');
//                            })->whereIn('delivery_location_id', $user_loc_arr);
            $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
            if (isset($search) && !empty($search)) {
                $term = '%' . $search . '%';
                $customers->Where('tally_name', 'like', $term);
            }
            if (isset($territory_id) && !empty($territory_id)) {
                $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
                foreach ($territory_locations as $loc) {
                    array_push($loc_arr, $loc->location_id);
                }
                $customers->whereIn('delivery_location_id', $loc_arr);
                $delivery_location = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
            }
            if (isset($location_id) && !empty($location_id)) {
                $customers->where('delivery_location_id', '=', $location_id);
            }
            if (isset($date_filter) && !empty($date_filter)) {
                if ($date_filter == 1) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                    });
                } else
                if ($date_filter == 3) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
                    });
                } else
                if ($date_filter == 7) {
                    $customers->whereHas('delivery_challan', function ($query) {
                        $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
                    });
                }
            } else {
                $customers->whereHas('delivery_challan', function ($query) {
                    $query->whereRaw("Date(DATE_ADD(delivery_challan.created_at,INTERVAL customers.credit_period DAY)) <= CURDATE()");
                });

//               dd($customers->toSql());
//                dd($customers->get());
            }
        }
        $customers = $customers->get();
        $city = City::all();
        $territories = Territory::orderBy('created_at', 'DESC')->get();
        return View('print_account_customers')->with('customers', $customers)->with('city', $city)
                        ->with('delivery_location', $delivery_location)
                        ->with('territories', $territories);
    }

    public function print_customer_details(DropboxStorageRepository $connection) {
        $customer = '';
        $id = Input::get('customer_id');
        $customer = Customer::with(['delivery_challan' => function ($query) {
                        $query->where('delivery_challan.challan_status', 'completed');
                    }])->with('customer_receipt')->find($id);
//        $customer = Customer::with('delivery_challan')->with('customer_receipt')->find($id);
        $credit_period = $customer->credit_period;
        $settle_filter = Input::get('settle_filter');
        $date_filter = Input::get('date_filter');
        $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                ->where('challan_status', 'completed')
                ->whereRaw('grand_price!=settle_amount');
        if (isset($settle_filter) && $settle_filter != '' && $settle_filter == 'Settled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
                    ->whereRaw('grand_price=settle_amount');
        }
        if (isset($settle_filter) && $settle_filter == 'Unsettled') {
            $delivery_challans = DeliveryChallan::where('customer_id', '=', $id)
                    ->where('challan_status', 'completed')
                    ->whereRaw('grand_price != settle_amount');
        }

        if (isset($date_filter) && !empty($date_filter)) {
            if ($date_filter == 1) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
            } else
            if ($date_filter == 3) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 3 DAY)");
            } else
            if ($date_filter == 7) {
                $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= DATE_ADD(CURDATE(),INTERVAL 7 DAY)");
            }
        } else {
            $delivery_challans->whereRaw("Date(DATE_ADD(created_at,INTERVAL $credit_period DAY)) <= CURDATE()");
        }
        $delivery_challans = $delivery_challans->get();
        $delivery_location = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        return View('print_customer_details_view')->with('customer', $customer)
                        ->with('delivery_challans', $delivery_challans)
                        ->with('delivery_location', $delivery_location);
    }

    public function change_unsettled_amount() {
        $customer_id = Input::get('customer_id');
        $old_amount = Input::get('old_amount');
        $new_amount = Input::get('new_amount');
        $difference = $new_amount - $old_amount;


        $receipts = Customer_receipts::where('customer_id', '=', $customer_id)->orderBy('created_at', 'DESC')->first();
        if (isset($receipts) && !empty($receipts)) {
            $receipt_id = $receipts->id;
            $receipt = Customer_receipts::find($receipt_id);
            $receipt_amount = $receipt->settled_amount;
            $new_unsettle_amount = $receipt_amount + $difference;
            $receipt->settled_amount = $new_unsettle_amount;
            $receipt->save();
        } else {
            $receiptObj = new Receipt();
            if ($receiptObj->save()) {
                $customerReceiptObj = new Customer_receipts();
                $customerReceiptObj->customer_id = $customer_id;
                $customerReceiptObj->settled_amount = $new_amount;
                $customerReceiptObj->debited_by_type = 1;
//                $customerReceiptObj->debited_to = 1;
//                $customerReceiptObj->debited_to = 1;
                $customerReceiptObj->receipt_id = $receiptObj->id;
                $customerReceiptObj->save();
            }
        }

        return Response::json(['success' => true]);
    }

    public function pass_journal_entry() {
        $customer_id = Input::get('customer_id');
        $old_amount = Input::get('old_amount');
        $new_amount = Input::get('new_amount');
        $due_amount = Input::get('due_amount');

        if ($old_amount <> '0' && $due_amount == '0') {


            $cust_id = Customer::where('owner_name', 'like', '%Discount User%')
                            ->orWhere('tally_name', 'like', '%Discount User%')
                            ->select('id')->first();

            if ($old_amount > 0) {
                $customer_dt_id = $customer_id;
                $customer_cr_id = $cust_id->id;
            } else if ($old_amount < 0) {
                $customer_dt_id = $cust_id->id;
                $customer_cr_id = $customer_id;
                $old_amount = abs($old_amount);
            }

            $receiptObj = new Receipt();
            if ($receiptObj->save()) {
                $customerReceiptObj = new Customer_receipts();
                $customerReceiptObj->customer_id = $customer_cr_id;
                $customerReceiptObj->settled_amount = $old_amount;
                $customerReceiptObj->debited_by_type = 1;
                $customerReceiptObj->receipt_id = $receiptObj->id;
                $customerReceiptObj->save();

                $customerReceiptObj_debit = new CustomerReceiptsDebitedTo();
                $customerReceiptObj_debit->customer_id = $customer_dt_id;
                $customerReceiptObj_debit->settled_amount = $old_amount;
                $customerReceiptObj_debit->debited_by_type = 1;
                $customerReceiptObj_debit->receipt_id = $receiptObj->id;
                $customerReceiptObj_debit->save();
            }

            Session::flash('success', 'Journal entry has been created');
            return Response::json(['success' => true]);
        }

        Session::flash('error', 'Something went wrong.');
        return Response::json(['success' => false]);
    }

}
