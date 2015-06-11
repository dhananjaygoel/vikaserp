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
    protected $fillable = ['inquiry_id', 'product_category_id', 'unit_id', 'quantity', 'price', 'remarks'];

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

}
