<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class DeliveryOrder extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_order';

//    public static $newuser_rules = array(
//        'first_name' => 'required|min:2|max:100',
//        'last_name' => 'required|min:2|max:100',
//        'email' => 'required|email|unique:users',
//        'password' => 'required|min:6|max:100',
//        'password_confirmation' => 'required|min:6|max:100|same:password',
//        'telephone_number' => 'integer|digits_between:8,15',
//        'mobile_number' => 'integer|digits_between:10,15|required|unique:users',
//        'user_type' => 'required'
//    );

}
