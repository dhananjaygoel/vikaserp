<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ManualCompleteOrderRequest extends Request {

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
			'reason_type' => 'required',                        
                        'reason'=>'required'
		];
	}

}
