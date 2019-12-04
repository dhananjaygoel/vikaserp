<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerReceiptsDebitedTo extends Model {

    protected $table = 'customer_receipts_debited_tos';

    public function receipts() {
        return $this->hasMany('App\Receipt', 'id', 'receipt_id');
//        return $this->hasMany('App\Receipt', 'id', 'receipt_id');
    }

    public function customers() {
        return $this->hasMany('App\Customer', 'id', 'customer_id');
    }

    public static $validatorMessages = array(
        'tally_users.required' => 'Tally user is required.',
//        'settle_amount.required' => 'Settled amount is required.',
        'tally_users_d.required' => 'Debited to is required.',
    );
    public static $ValidateNewReceipt = array(
        'tally_users' => 'required',
//        'settle_amount' => 'required',
        'tally_users_d' => 'required',
    );

}
