<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inquiry';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'created_by', 'delivery_location_id', 'vat_percentage', 'expected_delivery_date', 'remarks', 'inquiry_status', 'other_location', 'location_difference'];

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function createdby() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function inquiry_products() {
        return $this->hasMany('App\InquiryProducts', 'inquiry_id', 'id')->where('product_category_id', '>', '0');
    }

    public function product_category() {
        return $this->hasOne('App\ProductCategory', 'id', 'product_type_id');
    }

    public function product_sub_category() {
        return $this->hasOne('App\ProductSubCategory', 'product_category_id', 'id');
    }

    public function delivery_location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

}
