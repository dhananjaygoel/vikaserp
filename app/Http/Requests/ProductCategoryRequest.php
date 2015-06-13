<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductCategoryRequest extends Request {

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
            'product_category_name' => 'required|min:2|max:100',
            'alias_name' => 'required|min:2|max:100',
            'price' => 'required|numeric'
        ];
    }

}
