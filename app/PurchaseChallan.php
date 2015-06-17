<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseChallan extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_challan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_order_id', 'purchase_advice_id', 'bill_number', 'serial_number', 'supplier_id', 'created_by', 'delivery_location_id', 'order_for', 'expected_delivery_date', 'vat_percentage', 'vehicle_number', 'amount', 'unloaded_by', 'labours', 'discount', 'frieght', 'loading_charge', 'grand_total', 'remarks', 'order_status', 'unloading'];

}
