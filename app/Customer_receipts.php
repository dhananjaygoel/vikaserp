<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer_receipts extends Model {

    //
    protected $table = 'customer_receipts';

    public function receipts() {
        return $this->hasMany('App\Receipt', 'id', 'receipt_id');
//        return $this->hasMany('App\Receipt', 'id', 'receipt_id');
    }

    public function customers() {
        return $this->hasMany('App\Customer', 'id', 'customer_id');
    }
    public function debited_to(){
        return $this->hasMany('App\Customer', 'id', 'customer_id');
    }
}
