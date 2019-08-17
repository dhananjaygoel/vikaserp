<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoadDelboy extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'truckdelbys';

    
       public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
    
 

}
