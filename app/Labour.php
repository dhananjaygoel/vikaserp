<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labour extends Model {

    protected $table = 'labours';
    protected $dates = ['deleted_at'];

    public function delivery_challan() {
        return $this->belongsToMany('App\DeliveryChallan');
    }

}
