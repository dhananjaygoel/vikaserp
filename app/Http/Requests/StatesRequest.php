<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StatesRequest extends Request {

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
            'state_name' => 'required|unique:state,state_name,NULL,id,deleted_at,NULL'
        ];
    }

}
