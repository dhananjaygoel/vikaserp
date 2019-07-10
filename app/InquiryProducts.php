<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InquiryProducts extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inquiry_products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['inquiry_id', 'product_category_id', 'unit_id', 'quantity', 'length', 'price', 'vat_percentage', 'remarks','app_product_id'];

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

    public function inquiry_product_details() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id')->with('product_category');
    }
    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'id', 'product_category_id');
    }

}
