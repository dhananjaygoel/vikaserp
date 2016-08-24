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
    public static $order_to_delivery_challan_rules = array(
    );

    public function delivery_product() {
        return $this->hasMany('App\AllOrderProducts', 'order_id', 'id')->where('order_type', '=', 'delivery_order')->where('product_category_id', '>', '0');
    }

    public function customer() {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }

    public function order_details() {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'created_by');
    }

    public function location() {
        return $this->hasOne('App\DeliveryLocation', 'id', 'delivery_location_id');
    }

    public function unit() {
        return $this->hasOne('App\Units', 'id', 'unit_id');
    }

    public function flagDelievryOrder($delivery_order_details) {
        $this->flaged = ($delivery_order_details->flaged == true) ? false : true;
        $this->save();
    }

}
