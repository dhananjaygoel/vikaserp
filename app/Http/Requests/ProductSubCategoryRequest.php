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
     * not deployed
     * @return array
     */
    public function rules() {
        return [
            'product_category' => 'required',
            'sub_product_name' => 'required',
            'alias_name' => 'unique:product_sub_category|required|min:2|max:100',
            'size' => 'required',
            'weight' => 'required',
            'standard_length' => 'required',
            'units' => 'required',
            'difference' => 'required',
            'hsn_code' => 'required|min:2',
        ];
    }

}
