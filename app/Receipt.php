<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model {

    protected $table = 'receipts';

    public function customer_receipts() {
        return $this->hasMany('App\Customer_receipts', 'receipt_id', 'id');
    }

}
