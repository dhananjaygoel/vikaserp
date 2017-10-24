<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

//    protected $fillable = ['state_id', 'city_id', 'area_name'];

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
        'mobile_number' => 'integer|digits:10|required|unique:customers,phone_number1',
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
        $this->save();
        return $this;
    }
    
    public static $customers_rules = array(
        'owner_name' => 'required|max:100',                        
        'gstin_number' => 'required|min:2',                                        
        'city' => 'required',
        'state' => 'required',
        'tally_name' => 'required|max:100',
        'phone_number1' => 'required|integer|digits:10',
//       'email' => 'required|email|unique:users',
        'delivery_location' => 'required',
        'password' => 'min:6|max:100',
        'confirm_password' => 'min:6|max:100|same:password',
    );
    
    

}
