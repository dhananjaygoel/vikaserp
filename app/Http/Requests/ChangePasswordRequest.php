<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ChangePasswordRequest extends Request {

    protected $redirect = 'change_password';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'old_password' => 'required',
//            'new_password' => 'required|min:6|max:100',
//            'confirm_password' => 'required|min:6|max:100|same:password',
            'new_password' => 'required|min:6|max:100|confirmed ',
            'new_password_confirmation' => 'required'
        ];
    }

//    public function messages(){
//        return [
//            'old_password.required' => 'Please enter old password.',
//            'password.required' => 'Please enter new password.',
//            'password.min' => 'Please enter new password.',
//            'confirm_password.same' => 'Please enter same new password and confirm password.'
//        ];
//    }
}
