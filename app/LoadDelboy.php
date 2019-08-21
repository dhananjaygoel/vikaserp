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
        return $this->hasOne('App\Customer', 'id', 'del_boy');
    }
    
   public function users() {
        return $this->hasOne('App\User', 'id', 'del_boy');
    }

}
