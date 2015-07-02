<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DeliveryChallan extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_challan';
    
        use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','delivery_order_id', 'customer_id', 'created_by',  'bill_number','loaded_by', 'labours','	discount', 'freight', '	loading_charge', 'vat_percentage', 'grand_total', 'challan_status', 'remarks'];

    protected $dates = ['deleted_at'];
    
    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
    
    public function delivery_order() {
        return $this->hasOne('App\DeliveryOrder', 'id', 'delivery_order_id');
    }
    
    public function all_order_products() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id');
    }
    
    public function user() {
        return $this->hasMany('App\User', 'id', 'created_by');
    }
    
    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_order_id');
    }
    
    
    
    //for sales Daybook Date filter
    public static $challan_date_rules = array(
        'challan_date' => 'required|date'        
    );
}
