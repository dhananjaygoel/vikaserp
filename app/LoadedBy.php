<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoadedBy extends Model {

    protected $table = 'loaded_bies';

    use SoftDeletes;

    public function delivery_challans() {
        return $this->belongsTo('App\DeliveryChallanLoadedBy', 'id', 'loaded_by_id');
//        return $this->hasManyThrough('App\DeliveryChallanLoadedBy', 'App\DeliveryChallan');
////        return $this->hasManyThrough('App\LoadedBy', 'App\DeliveryChallanLoadedBy');
//        return $this->hasMany('App\DeliveryChallanLoadedBy', 'id', 'loaded_by_id');
    }

//    public function delivery_challan_loaded_by() {
////        return $this->hasManyThrough('App\DeliveryChallanLoadedBy', 'App\DeliveryChallan');
//////        return $this->hasManyThrough('App\LoadedBy', 'App\DeliveryChallanLoadedBy');
//        return $this->hasMany('App\DeliveryChallan', 'foreign_key', 'local_key');
////        return $this->hasOne('App\Phone');
//    }
    public static $validatorMessages = array(
        'first_name.required' => 'First name is required.',
        'first_name.min' => 'First name must be at least :min characters.',
        'first_name.max' => 'First name may not be greater than :max characters.',
        'last_name.min' => 'Last name must be at least :min characters.',
        'last_name.max' => 'Last name may not be greater than :max characters.',
        'mobile_number.required' => 'Mobile number is required.',
        'mobile_number.digits' => 'Mobile number must be 10 digits.',
        'mobile_number.numeric' => 'Mobile number must contain numbers',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least :min characters.',
        'password.max' => 'Password may not be greater than :max characters.',
        'confirm_password.required' => 'Confirm password is required.',
        'confirm_password.min' => 'Confirm password must be at least :min characters.',
        'confirm_password.max' => 'Confirm password may not be greater than :max characters.',
        'confirm_password.same' => 'Confirm password must match with password'
    );
    public static $ValidateNewLoader = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'min:2|max:100',
        'mobile_number' => 'required|numeric|digits:10',
        'password' => 'required|min:6|max:10',
        'confirm_password' => 'required|min:6|max:10|same:password'
    );
    public static $ValidateUpdateLoader = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'min:2|max:100',
        'mobile_number' => 'required|numeric|digits:10'
    );
    public static $validatePassword = array(
        'password' => 'required|min:6|max:10',
        'confirm_password' => 'required|min:6|max:10|same:password'
    );

}
