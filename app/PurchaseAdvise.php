<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseAdvise extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_advice';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['supplier_id', 'created_by', 'purchase_advice_date', 'serial_number', 'delivery_location_id', 'order_by', 'vat_percentage', 'expected_delivery_date', 'total_price', 'remarks', 'advice_status', 'vehicle_number'];

}
