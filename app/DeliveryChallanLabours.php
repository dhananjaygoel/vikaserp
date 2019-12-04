<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryChallanLabours extends Model {

    protected $table = 'delivery_challan_labours';

    //
    public function dc_labour() {
        return $this->hasMany('App\Labour', 'id', 'labours_id');
//        return $this->belongsTo('App\LoadedBy', 'id', 'loaded_by_id');
    }

    public function dc_delivery_challan() {
        return $this->hasMany('App\DeliveryChallan', 'id', 'delivery_challan_id');
//        return $this->belongsTo('App\DeliveryChallan', 'id', 'delivery_challan_id');
    }   

    
    public function pc_delivery_challan() {
        return $this->hasMany('App\PurchaseChallan', 'id', 'delivery_challan_id');
//        return $this->belongsTo('App\DeliveryChallan', 'id', 'delivery_challan_id');
    }

}
