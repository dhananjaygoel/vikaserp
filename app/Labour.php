<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Labour extends Model {

    protected $table = 'labours';
    protected $dates = ['deleted_at'];

    public function delivery_challan() {
        return $this->belongsToMany('App\DeliveryChallan');
    }
    
    public static $validatorMessages = array(
        'first_name.required' => 'First name is required.',
        'first_name.min' => 'First name must be at least :min characters.',
        'first_name.max' => 'First name may not be greater than :max characters.',
        'last_name.min' => 'Last name must be at least :min characters.',
        'last_name.max' => 'Last name may not be greater than :max characters.',
        'phone_number.required' => 'Mobile number is required.',
        'phone_number.digits' => 'Mobile number must be 10 digits.',
        'phone_number.numeric' => 'Mobile number must contain numbers',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least :min characters.',
        'password.max' => 'Password may not be greater than :max characters.',
        'confirm_password.required' => 'Confirm password is required.',
        'confirm_password.min' => 'Confirm password must be at least :min characters.',
        'confirm_password.max' => 'Confirm password may not be greater than :max characters.',
        'confirm_password.same' => 'Confirm password must match with password'
    );
    
    public static $new_labours_inquiry_rules = array(
        'first_name' => 'required|min:2|max:100',
        'last_name' => 'required|min:2|max:100',
        'phone_number' => 'required|numeric|digits:10',
       
    );

}
