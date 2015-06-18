<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'delivery_order';

    use SoftDeletes;

    protected $dates = ['deleted_at'];
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



    public static $order_to_delivery_challan_rules = array(
        'billno' => 'required',
        'discount' => 'required',
        'freight' => 'required',
        'loading' => 'required',
        'loadedby' => 'required',
        'labour' => 'required',
        'challan_remark' => 'required'
    );
    

    public function delivery_product() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id');
    }

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

}
