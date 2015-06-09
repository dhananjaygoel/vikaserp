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
use App\UserRoles;
use App\Http\Requests\UserValidation;
use Input;
use DB;

class UsersController extends Controller {

    public function index() {
        $users_data = User::where('role_id', '!=', 0)->Paginate(5);
        $users_data->setPath('users_data');
        return view('users', compact('users_data'));
    }

    public function create() {

        $roles = UserRoles::where('role_id', '!=', 0)->get();
        return view('add_user', compact('roles'));
    }

    public function store() {

        $validator = Validator::make(Input::all(), User::$newuser_rules);

        if ($validator->passes()) {
            $Users_data = new User();
            $Users_data->role_id = Input::get('type');
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
        
        $count = User::where('mobile_number', Input::get('mobile'))
                        ->Where('password', Hash::make(Input::get('model_pass')))->get();

        echo '<pre>';
        print_r($count);
        echo '</pre>';
        exit;
 
        //User::destroy($id);
    }

}
