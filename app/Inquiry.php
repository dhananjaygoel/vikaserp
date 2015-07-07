<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inquiry';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'created_by', 'delivery_location_id', 'vat_percentage', 'expected_delivery_date', 'remarks', 'inquiry_status', 'other_location', 'other_location_difference'];

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function inquiry_products() {
        return $this->hasMany('App\InquiryProducts', 'inquiry_id', 'id');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_category_id');
    }

    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'product_type_id', 'id');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

}
