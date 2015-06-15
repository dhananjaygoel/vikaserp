<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductSubCategoryRequest extends Request {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'product_type' => 'required',
            'select_product_categroy' => 'required',            
            'alias_name' => 'required|min:2|max:100',
            'size' => 'required',
            'weight' => 'required',
            'difference' => 'required'
        ];
    }

}
