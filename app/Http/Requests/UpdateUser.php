<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateUser extends Request {

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
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'telephone_number' => 'integer|digits_between:8,15',
            'user_type' => 'required'
        ];
    }

}
