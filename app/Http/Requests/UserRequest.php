<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class UserRequest extends FormRequest {

    public function rules() {
        return [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|min:6|max:100|same:password',
            'phone_number2' => 'integer|digits_between:8,15',
            'mobile' => 'integer|digits_between:10,15|required|unique:users',
            'user_role' => 'required'
        ];
    }

}
