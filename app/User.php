<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

//    protected $dates = ['created_at', 'updated_at', 'password_updated_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['remember_token'];
    public static $newuser_rules = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|max:100',
        'password_confirmation' => 'required|min:6|max:100|same:password',
        'telephone_number' => 'integer|digits_between:8,15',
        'mobile_number' => 'integer|digits_between:10,15|required|unique:users',
        'user_type' => 'required'
    );
    public static $updateuser_rules = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'email' => 'required|email|unique:users',
        'telephone_number' => 'integer|digits_between:8,15',
        'user_type' => 'required'
    );
    public static $update_password = array(
        'password' => 'required|min:6|max:20|confirmed ',
        'password_confirmation' => 'required'
    );

    public function user_role() {
        return $this->hasOne('App\UserRoles', 'role_id', 'role_id');
    }

    public function locations() {
        return $this->hasMany('App\CollectionUser', 'user_id', 'id');
    }

    public function hasOldPassword() {
        date_default_timezone_set("Asia/Calcutta");
        $pri_date = \Carbon\Carbon::now()->subDays(30)->toDateTimeString();

        if ($this->role_id == 0) {
            if ($this->password_updated_at < $pri_date) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

}
