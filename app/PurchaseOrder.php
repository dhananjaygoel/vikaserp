<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order';

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['supplier_id', 'created_by', 'is_view_all', 'delivery_location_id', 'order_for', 'expected_delivery_date', 'total_price', 'vat_percentage', 'remarks', 'order_status', 'other_location', 'other_location_difference'];

    public function purchase_products() {
        return $this->hasMany('App\PurchaseProducts', 'purchase_order_id', 'id')->with('product_sub_category')->where('order_type', '=', 'purchase_order')->where('product_category_id', '>', '0');
    }

//********************
    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'product_type_id', 'id');
    }

//**************
    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'supplier_id');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function purchase_advice() {
        return $this->hasOne('App\PurchaseAdvise', 'purchase_order_id', 'id');
    }

    public function purchase_product_has_from(){
        return $this->hasMany('App\PurchaseProducts', 'from', 'id')->with('product_sub_category'); 
    }

}
