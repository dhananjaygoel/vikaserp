<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderCanceled extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'purchase_order_canceled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['purchase_order_id', 'purchase_type', 'reason'];

}
