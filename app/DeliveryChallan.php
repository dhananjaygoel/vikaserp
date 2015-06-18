<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallan extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_challan';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','delivery_order_id', 'customer_id', 'created_by',  'bill_number','loaded_by', 'labours','	discount', 'freight', '	loading_charge', 'vat_percentage', 'grand_total', 'challan_status', 'remarks'];

    
    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
    
    public function delivery_order() {
        return $this->hasOne('App\DeliveryOrder', 'id', 'delivery_order_id');
    }
    
    public function all_order_products() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id');
    }

}
