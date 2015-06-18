<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_source','supplier_id','customer_id', 'created_by', 'delivery_location_id', 'vat_percentage', 'estimated_delivery_date','expected_delivery_date', 'remarks', 'order_status', 'other_location'];

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function all_order_products() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }
    public function order_cancelled(){
        return $this->hasOne('App\OrderCancelled', 'order_id', 'id');
    }
    
    public static $order_to_delivery_order_rules = array(
        'vehicle_number' => 'required',
        'driver_name' => 'required',
        'driver_contact' => 'required|min:10|max:20'
    );
}
