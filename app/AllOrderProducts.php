<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllOrderProducts extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'all_order_products';
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id', 'order_type', 'from', 'product_category_id', 'unit_id', 'quantity', 'actual_pieces', 'price', 'vat_percentage', 'present_shipping', 'remarks', 'parent', 'actual_quantity','app_product_id','length'];
    protected $dates = ['deleted_at'];

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id')->with('product_category');
    }

    public function order_product_details() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id')->with('product_category');
    }
    public function order_product_all_details() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id')->with('product_category.product_type');
    }
    public function sum_quntity() {
        return $this->hasMany('App\AllOrderProducts', 'parent', 'id');
    }  
    
     
    public function inventory() {
        return $this->hasOne('App\Inventory', 'product_sub_category_id', 'product_category_id');
    } 
    
//    public function order_product_details_pipe() {
//        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id')->whereHas('product_category', function($q)
//{
//    $q->where('product_type_id', '1');
//
//});
//    }

}
