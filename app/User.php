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

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    public static $newuser_rules = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|max:100',
        'password_confirmation' => 'required|min:6|max:100|same:password',
        'telephone_number' => 'integer|digits_between:8,15',
        'mobile_number' => 'integer|digits_between:10,15|required|unique:users',
        'type' => 'required'
    );

}
