<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {

    use SoftDeletes;

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
    protected $fillable = ['order_source', 'supplier_id', 'customer_id', 'created_by', 'delivery_location_id', 'vat_percentage', 'estimated_delivery_date', 'expected_delivery_date', 'remarks', 'order_status', 'other_location', 'location_difference','discount_type','discount_unit','discount'];
    protected $dates = ['deleted_at'];

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function all_order_products() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id')->with('product_sub_category')->where('order_type', '=', 'order')->where('product_category_id', '>', '0');
    }
    //all_order_products_without_product_subcategory => aopwpsc
    public function aopwpsc() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id')->where('order_type', '=', 'order')->where('product_category_id', '>', '0');
    }
    
     /* new */
    public function delivery_orders() {
        return $this->hasMany('App\DeliveryOrder', 'order_id', 'id');
    }    
    /* new */
    public function delivery_orders_all_products() {
        return $this->hasMany('App\DeliveryOrder', 'order_id', 'id')->with('delivery_product');
    } 
    
    public function createdby() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function order_cancelled() {
        return $this->hasOne('App\OrderCancelled', 'order_id', 'id');
    }

    public static $order_to_delivery_order_rules = array(
        'driver_contact' => 'min:8|max:20'
    );

    public function setProductCategoryId($product_category_id) {
        $this->product_category_id = $product_category_id;
    }

    public function getProductCategoryId() {
        return $this->product_category_id;
    }

    public function flagOrder($order_details) {
        $this->flaged = ($order_details->flaged == true) ? false : true;
        $this->save();
    }
   
}
