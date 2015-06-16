<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StorePurchaseAdvise extends Request {

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
			'bill_date' => 'required',
			'total_price' => 'required|integer',
			'vehicle_number' => 'required',
			'expected_delivery_date' => 'required',
			'remarks' => 'required',
			'delivery_location_id' => 'required',
		];
	}

}
