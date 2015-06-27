<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreCustomer extends Request {

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
			'owner_name' => 'required|max:100',
			'city' => 'required',
			'state' => 'required',
			'tally_name' => 'required|max:100',
			'phone_number1' => 'required|integer|digits_between:10,15',
                        'email' => 'required|email|unique:users',
                        'delivery_location' => 'required',
                        'password' => 'min:6|max:100',
                        'confirm_password' => 'min:6|max:100|same:password',
		];
	}

}
