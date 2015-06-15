<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreCustomerManager extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'manager_name' => 'required|max:100',
			'phone_number' => 'required|integer|digits_between:10,15',
                        
		];
	}

}
