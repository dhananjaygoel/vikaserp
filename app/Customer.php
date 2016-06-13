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
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id')->with('city', 'state');
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
        'mobile_number' => 'integer|digits_between:10,15|required',
        'credit_period' => 'integer|required',
    );
    public static $existing_customer_inquiry_rules = array(
        'existing_customer_name' => 'required',
    );
    public static $new_supplier_inquiry_rules = array(
        'supplier_name' => 'required|min:2|max:100',
        'mobile_number' => 'integer|digits_between:10,15|required',
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
        'mobile_number' => 'integer|digits_between:10,15|required',
        'credit_period' => 'integer|required',
    );
    public static $existing_supplier_rules = array(
        'supplier_id' => 'required',
    );
    public static $decimal_value = array(
        'price' => 'required|max:6|min:1'
    );

}
