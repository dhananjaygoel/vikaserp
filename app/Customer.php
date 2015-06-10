<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DeliveryLocation;

class Customer extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = ['state_id', 'city_id', 'area_name'];
    
    public function deliverylocation() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id')->with('city', 'state');
    }
    
    public function manager() {
        return $this->hasOne('App\User', 'id', 'relationship_manager');
    }

}
