<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labour extends Model {

    protected $table = 'labours';
    protected $dates = ['deleted_at'];

    public function delivery_challan() {
        return $this->belongsToMany('App\DeliveryChallan');
    }
    
    public static $new_labours_inquiry_rules = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'phone_number' => 'required|numeric|digits:10',
       
    );

}
