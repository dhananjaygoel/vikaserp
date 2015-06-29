<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LocationRequest extends Request {

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
            'city' => 'required',
            'state' => 'required',
            'area_name' => 'required|unique:delivery_locations'
        ];
    }
    protected $fillable = ['city','state','area_name','difference'];

}
