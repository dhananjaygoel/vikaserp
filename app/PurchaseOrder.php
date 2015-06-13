<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['supplier_id', 'created_by', 'is_view_all', 'delivery_location_id', 'order_for', 'expected_delivery_date', 'total_price', 'vat_percentage', 'remarks', 'order_status'];

    public function purchase_products() {
        return $this->hasMany('App\PurchaseProducts', 'purchase_order_id', 'id');
    }

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'supplier_id');
    }

}
