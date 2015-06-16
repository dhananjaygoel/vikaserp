<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DeliveryLocation;

class Customer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = ['state_id', 'city_id', 'area_name'];

    public function deliverylocation() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id')->with('city', 'state');
    }

    public function manager() {
        return $this->hasOne('App\User', 'id', 'relationship_manager');
    }

    public function customerproduct() {
        return $this->hasMany('App\CustomerProductDifference', 'customer_id', 'id');
    }

    public static $new_customer_inquiry_rules = array(
        'customer_name' => 'required|min:2|max:100',
        'contact_person' => 'required|min:2|max:100',
        'mobile_number' => 'integer|digits_between:10,15|required',
        'credit_period' => 'integer|required',
    );
    public static $existing_customer_inquiry_rules = array(
        'autocomplete_customer_id' => 'required',
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
        'existing_customer_id' => 'required',
    );
    
    
    public static $new_supplier_rules = array(
        'supplier_name' => 'required|min:2|max:100',
        'mobile_number' => 'integer|digits_between:10,15|required',
        'credit_period' => 'integer|required',
    );
    public static $existing_supplier_rules = array(
        'supplier_id' => 'required',
    );

}
