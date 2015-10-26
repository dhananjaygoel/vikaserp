<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseAdvise extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_advice';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_order_id', 'supplier_id', 'created_by', 'purchase_advice_date', 'serial_number', 'delivery_location_id', 'order_by', 'vat_percentage', 'expected_delivery_date', 'total_price', 'remarks', 'advice_status', 'vehicle_number', 'other_location', 'other_location_difference'];

    public function supplier() {
        return $this->hasOne('App\Customer', 'id', 'supplier_id');
    }

    public function party() {
        return $this->hasOne('App\Customer', 'id', 'order_for');
    }

    public function location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function purchase_products() {
        return $this->hasMany('App\PurchaseProducts', 'purchase_order_id', 'id')->where('order_type', '=', 'purchase_advice');
    }

    public static $store_purchase_validation = array(
        'bill_date' => 'required',
        'vehicle_number' => 'required'
    );

}
