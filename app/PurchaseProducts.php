<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseProducts extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'all_purchase_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_order_id', 'from', 'order_type', 'product_category_id', 'unit_id', 'actual_pieces', 'quantity', 'price', 'present_shipping', 'remarks', 'parent'];

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'product_category_id', 'product_category_id');
    }

    public function purchase_product_details() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id')->with('product_category');
    }

}
