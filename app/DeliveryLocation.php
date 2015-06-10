<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryLocation extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['state_id', 'city_id', 'area_name'];

    public function city() {
        return $this->hasOne('App\City', 'id', 'city_id');
    }
    public function state() {
        return $this->hasOne('App\States', 'id', 'state_id');
    }

}
