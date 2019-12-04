<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class TerritoryLocation extends Model {
	
    protected $table = 'territory_locations';
    
    public function deliverylocation() {
        return $this->hasMany('App\DeliveryLocation', 'location_id', 'id');
    }
}
