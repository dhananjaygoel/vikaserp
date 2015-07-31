<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use App\UserRoles;
use App\Http\Requests\UserValidation;
use Input;
use DB;
use App\Http\Requests\UserRequest;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware('validIP');
    }

    public function index() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $users_data = User::where('role_id', '!=', 0)->with('user_role')->orderBy('created_at', 'desc')->Paginate(20);
        $users_data->setPath('users');
        return view('users', compact('users_data'));
    }

    public function create() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $roles = UserRoles::where('role_id', '!=', 0)->get();
        return view('add_user', compact('roles'));
    }

    public function store() {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $validator = Validator::make(Input::all(), User::$newuser_rules);

        if ($validator->passes()) {
            $Users_data = new User();
            $Users_data->role_id = Input::get('user_type');
            $Users_data->first_name = Input::get('first_name');
            $Users_data->last_name = Input::get('last_name');
            $Users_data->phone_number = Input::get('telephone_number');
            $Users_data->mobile_number = Input::get('mobile_number');
            $Users_data->email = Input::get('email');
            $Users_data->password = Hash::make(Input::get('password'));
            $Users_data->save();
            return redirect('users')->with('flash_message', 'User details successfully added.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function destroy($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
            if (Input::get('mobile') == Auth::user()->mobile_number) {
                //        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
                if (User::destroy($id)) {
                    return redirect('users')->with('flash_message', 'User details successfully deleted.');
                } else {
                    return redirect('users')->with('flash_message', 'Unable to delete the user details.');
                }
            }
        } else {
            return redirect('users')->with('wrong', 'You have entered wrong credentials');
        }
    }

    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $roles = UserRoles::where('role_id', '!=', 0)->get();
        $user_data = User::find($id);
        return view('edit_user', compact('user_data', 'roles'));
    }

    public function update($id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $validator = Validator::make(Input::all(), User::$updateuser_rules);

        if ($validator->passes()) {

            $user_data = array(
                'role_id' => Input::get('user_type'),
                'first_name' => Input::get('first_name'),
                'last_name' => Input::get('last_name'),
                'phone_number' => Input::get('telephone_number'),
                'role_id' => Input::get('user_type')
            );
            
            if (Input::has('password')) {

                $input_password['password'] = Input::get('password');
                $input_password['password_confirmation'] = Input::get('password_confirmation');

                $validation1 = Validator::make($input_password, User::$update_password);

                if ($validation1->fails()) {
                    return Redirect::back()->withErrors($validation1);
                } else {
                    $user_data['password'] = Hash::make(Input::get('password'));
                }
            }

            $email_count = User::where('id', '!=', $id)
                    ->where('email', '=', Input::get('email'))
                    ->count();

            if ($email_count > 0) {
                return Redirect::back()->withInput()->with('email', 'Email address already taken.');
            } else {
                $user_data['email'] = Input::get('email');
            }

            $mobile_count = User::where('id', '!=', $id)
                    ->where('mobile_number', '=', Input::get('mobile_number'))
                    ->count();

            if ($mobile_count > 0) {
                return Redirect::back()->withInput()->with('email', 'Mobile number already taken.');
            } else {
                $user_data['mobile_number'] = Input::get('mobile_number');
            }

            $user = User::where('id', $id)
                    ->update($user_data);
            if ($user) {
                return redirect('users')->with('success', 'User details successfully updated.');
            } else {
                return redirect('users')->with('error', 'Unable to update the user details ');
            }
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

}
