<?php namespace App\Services;

use App\User;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		return Validator::make($data, [
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'phone_number' => 'required|numeric',
			'mobile_number' => 'required|numeric|unique:users',
			'role_id' => 'required|numeric',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{

                $user = new User;
                $user->first_name = $data['first_name'];
                $user->last_name = $data['first_name'];
                $user->email = $data['email'];
                $user->phone_number = $data['phone_number'];
                $user->mobile_number = $data['mobile_number'];
                $user->role_id = $data['role_id'];
                $user->password = bcrypt($data['password']);
                
                $user->save();
                     
                return $user;
//		return User::create([
//			'first_name' => $data['first_name'],
//                        'last_name' => $data['last_name'],                        
//                        'email' => $data['email'],
//                        'phone_number' => $data['phone_number'],
//                        'mobile_number' => $data['mobile_number'],
//                        'role_id' => $data['role_id'],
//			'password' => bcrypt($data['password'])
//		]);
	}

}
