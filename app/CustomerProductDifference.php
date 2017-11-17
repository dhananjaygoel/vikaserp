<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerProductDifference extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_product_difference';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = ['state_id', 'city_id', 'area_name'];
    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }
}
