<?php

namespace App;

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use Input;
use App;
use App\City;
use App\States;
use App\QuickbookToken;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

//    protected $fillable = ['state_id', 'city_id', 'area_name'];

function getToken(){
    require_once base_path('quickbook/vendor/autoload.php');
    $quickbook = App\QuickbookToken::find(2);
    return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $quickbook->client,
        'ClientSecret' => $quickbook->secret,
        'accessTokenKey' =>  $quickbook->access_token,
        'refreshTokenKey' => $quickbook->refresh_token,
        'QBORealmID' => "9130347328068306",
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

//function for All Inclusive Account 
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
        'QBORealmID' => "9130347328054516",
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
    public function deliverylocation() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id')->with('city', 'states');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id')->select(array('id', 'difference'));
    }

    public function only_city() {
        return $this->hasOne('App\City', 'id', 'city')->select(array('id', 'city_name'));
    }

    public function manager() {
        return $this->hasOne('App\User', 'id', 'relationship_manager');
    }

    public function customerproduct() {
        return $this->hasMany('App\CustomerProductDifference', 'customer_id', 'id');
    }  
    
    public function customer_receipt() {
        return $this->hasMany('App\Customer_receipts', 'customer_id', 'id');
    }
    
    public function customer_receipt_debit() {
        return $this->hasMany('App\CustomerReceiptsDebitedTo', 'customer_id', 'id');
    }
    
    public function delivery_challan() {
        return $this->hasMany('App\DeliveryChallan', 'customer_id', 'id');
    }
    
    public function collection_user_location() {
        return $this->hasMany('App\CollectionUser', 'location_id', 'delivery_location_id');
    }

    public function city() {
        return $this->hasOne('App\City', 'id', 'city');
    }

    public function getcity() {
        return $this->hasOne('App\City', 'id', 'city');
    }

    public function states() {
        return $this->hasOne('App\States', 'id', 'state');
    }

    public static $new_customer_inquiry_rules = array(
        'customer_name' => 'required|min:2|max:100',
        'contact_person' => 'required|min:2|max:100',
        'mobile_number' => 'numeric|digits:10|required|unique:customers,phone_number1',
        'credit_period' => 'integer|required',        
    );
    public static $new_customer_edit_inquiry_rules = array(
        'customer_name' => 'required|min:2|max:100',
        'contact_person' => 'required|min:2|max:100',
        'mobile_number' => 'numeric|digits:10|required',
        'credit_period' => 'integer|required',        
    );
    public static $existing_customer_inquiry_rules = array(
        'existing_customer_name' => 'required',
    );
    public static $new_supplier_inquiry_rules = array(
        'supplier_name' => 'required|min:2|max:100',
        'mobile_number' => 'integer|digits:10|required',
        'credit_period' => 'integer|required',
    );
    public static $existing_supplier_inquiry_rules = array(
        'autocomplete_supplier_id' => 'required',
    );
    public static $existing_customer_order_rules = array(
        'existing_customer_name' => 'required',
    );
    public static $new_supplier_rules = array(
        'supplier_name' => 'required|min:2|max:100',
        'mobile_number' => 'integer|digits:10|required',
        'credit_period' => 'integer|required',
    );
    public static $existing_supplier_rules = array(
        'supplier_id' => 'required',
    );
    public static $decimal_value = array(
        'price' => 'required|max:6|min:1'
    );

    public function addNewCustomer($owner_name, $contact_person, $phone_number1, $credit_period, $devlivery_location_id = '444') {

        $this->owner_name = $owner_name;
        $this->contact_person = $contact_person;
        $this->phone_number1 = $phone_number1;
        $this->credit_period = $credit_period;
        $this->customer_status = 'pending';
        $this->delivery_location_id = $devlivery_location_id;

        $state = States::where('id',1)->first();
        $city = City::where('id',1)->where('state_id',1)->first();
        $customer = Customer::where('owner_name',$owner_name)->where('customer_status','pending')->first();
        $quickbook_id=$customer->quickbook_customer_id;
        $quickbook_a_id=$customer->quickbook_a_customer_id;
        $Qdata = [
            "GivenName"=>  $owner_name,
            "FullyQualifiedName"=> $contact_person,
            "DisplayName"=>  $owner_name,
            "PrimaryPhone"=>  [
                "FreeFormNumber"=>  $phone_number1
            ],
            "BillAddr"=> [
                "Country"=> "India",
                "CountrySubDivisionCode"=> $state->state_name,
                "City"=> $city->city_name,
            ],
        ];
        if(empty($quickbook_id) && empty($quickbook_a_id)){
            $inclusivecustomerid ="";
            $gstcustomerid = "";
            $this->refresh_token_Wihtout_GST();
            $dataService = $this->getTokenWihtoutGST();
            $newCustomerObj = \QuickBooksOnline\API\Facades\Customer::create($Qdata);
            $newcus = $dataService->add($newCustomerObj);
            $error = $dataService->getLastError();
            if ($error) { 
                $this->refresh_token_Wihtout_GST();
                $dataService = $this->getTokenWihtoutGST();  
            }
            else{
                $inclusivecustomerid =  $newcus->Id;
            }
            $this->refresh_token();
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
            $this->quickbook_a_customer_id  = $inclusivecustomerid;
            $this->quickbook_customer_id  = $gstcustomerid;
        }else{
            $this->refresh_token_Wihtout_GST();
            $dataService = $this->getTokenWihtoutGST();
            $resultingObj = $dataService->FindById('Customer', $quickbook_a_id);
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
            $this->quickbook_a_customer_id  = $quickbook_a_id;
            $this->quickbook_customer_id  = $quickbook_id;
        }
        /* Added Customer to the Quickbook Account */
        $this->save();
        return $this;
    }
    public function addNewSupplier($owner_name, $phone_number1, $credit_period, $devlivery_location_id = '444') {

        $this->owner_name = $owner_name;
        $this->phone_number1 = $phone_number1;
        $this->credit_period = $credit_period;
        $this->customer_status = 'pending';
        $this->delivery_location_id = $devlivery_location_id;
        $this->save();
        return $this;
    }
    
    public static $customers_rules = array(
        'owner_name' => 'required|max:100',                        
        'gstin_number' => 'required|min:2',                                        
        'city' => 'required',
        'state' => 'required',
        'tally_name' => 'required|max:100',
        'phone_number1' => 'required|digits:10',
//       'email' => 'required|email|unique:users',
        'delivery_location' => 'required',
        'password' => 'min:6|max:100',
        'confirm_password' => 'min:6|max:100|same:password',
    );
    
    

}
