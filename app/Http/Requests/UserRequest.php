<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request {

    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'password_confirmation' => 'required|min:6|max:100|same:password',
            'telephone_number' => 'integer|digits_between:8,15',
            'mobile_number' => 'integer|digits_between:10,15|required|unique:users',
            'user_type' => 'required'
        ];
    }

}
