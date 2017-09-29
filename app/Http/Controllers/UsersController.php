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
use App\Http\Requests\UpdateUser;
use Illuminate\Database\Eloquent\Collection;

//use Jenssegers\Agent\Agent;

class UsersController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        //Check authorization of user with current ip address
        $this->middleware('validIP');
    }

    /*
      | Poplulate User list with all users except
      | Superadmin
     */

    public function index() {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
       
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
//        $users_data = User::where('role_id', '!=', 0)->with('user_role')->orderBy('created_at', 'desc')->Paginate(20);
        $users_data = User::with('user_role')->where('first_name', '!=', 'Super')
                ->Where('role_id', '!=', '5')->Where('role_id', '!=', '6')
                ->orderBy('created_at', 'desc')->Paginate(20);
        $users_data->setPath('users');
        return view('users', compact('users_data'));
    }

    /*
      | Opens create page to
      | add User
     */

    public function create() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        //$roles = UserRoles::where('role_id', '!=', 0)->get();
        $roles = UserRoles::where('name', '!=', 'Super Admin')->get();
        return view('add_user', compact('roles'));
    }

    /*
      | Store User
      | Get the post request and store it
      | to the database.
     */

    public function store(UserRequest $request) {


//        if ($agent->isAndroidOS()) {
//            $Users_data = new User();
//            $Users_data->role_id = Input::get('user_type');
//            $Users_data->first_name = Input::get('first_name');
//            $Users_data->last_name = Input::get('last_name');
//            $Users_data->phone_number = Input::get('telephone_number');
//            $Users_data->mobile_number = Input::get('mobile_number');
//            $Users_data->email = Input::get('email');
//            $Users_data->password = Hash::make(Input::get('password'));
//            if ($Users_data->save()) {
//                return json_encode(array(
//                    'result' => true,
//                    'message' => 'User details successfully added.'), 200
//                );
//            } else {
//                return json_encode(array(
//                    'result' => false,
//                    'message' => 'Unable to store user at this moment'), 200
//                );
//            }
//        } else {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $Users_data = new User();
        $Users_data->role_id = Input::get('user_type');
        $Users_data->first_name = Input::get('first_name');
        $Users_data->last_name = Input::get('last_name');
        $Users_data->phone_number = Input::get('telephone_number');
        $Users_data->mobile_number = Input::get('mobile_number');
        $Users_data->email = Input::get('email');
        $Users_data->password = Hash::make(Input::get('password'));
        if ($Users_data->save()) {
            return redirect('users')->with('flash_message', 'User details successfully added.');
        } else {
            return redirect('users')->with('error', 'Unable to store user at this moment');
        }
    }

    /*
      | Delete User from the system
      | permanently
     */

    public function destroy($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
            if (Input::get('mobile') == Auth::user()->mobile_number) {
                //        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
                if (User::destroy($id)) {
                    return redirect('users')->with('flash_message', 'User deleted successfully.');
                } else {
                    return redirect('users')->with('flash_message', 'Unable to delete the user details.');
                }
            }
        } else {
            return redirect('users')->with('wrong', 'You have entered wrong credentials');
        }
    }

    /*
      | Opens create page to
      | edit User
     */

    public function edit($id) {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

       // $roles = UserRoles::where('role_id', '!=', 0)->get();
        $roles = UserRoles::where('name', '!=', 'Super Admin')->get();
        $user_data = User::find($id);
        return view('edit_user', compact('user_data', 'roles'));
    }

    /*
      | Update User
      | Get the post request and update it
      | to the database.
     */

    public function update(UpdateUser $request, $id) {
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

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
    }
    
    public function get_do_vehicle_list() {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
       
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 7) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';
            $do_vehicle_list = \App\DeliveryOrder::where('order_status','=',"pending")
                                                  ->where('vehicle_number','!=',"")->select('vehicle_number')
                                                  ->where('vehicle_number','like', $term)->paginate(20);
            $pa_vehicle_list = \App\PurchaseAdvise::where('advice_status','=',"in_process")->select('vehicle_number')->paginate(20);
        }else{
            $do_vehicle_list = \App\DeliveryOrder::where('order_status','=',"pending")
                                                 ->where('vehicle_number','!=',"")                                                 
                                                 ->select('vehicle_number')->paginate(20);
            $pa_vehicle_list = \App\PurchaseAdvise::where('advice_status','=',"in_process")
                                                 ->select('vehicle_number')->paginate(20);
        }       
//        dd($pa_vehicle_list);       
        $do_vehicle_list->setPath('vehicle-list');
//        $pa_vehicle_list->setPath('vehicle-list');              
        
        return view('do_vehicle_list', compact('do_vehicle_list','pa_vehicle_list'));
    }
    
    public function get_pa_vehicle_list() {
        
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
       
        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 7) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';            
            $pa_vehicle_list = \App\PurchaseAdvise::where('advice_status','=',"in_process")->select('vehicle_number')
                                                    ->where('vehicle_number','like', $term)    
                                                    ->where('vehicle_number','!=',"")
                                                    ->select('vehicle_number')->paginate(20);
        }else{            
            $pa_vehicle_list = \App\PurchaseAdvise::where('advice_status','=',"in_process")
                                                    ->select('vehicle_number')->paginate(20);
        }       
//        dd($pa_vehicle_list);       
        $pa_vehicle_list->setPath('pa-vehicle-list');
//        $pa_vehicle_list->setPath('vehicle-list');              
        
        return view('pa_vehicle_list', compact('pa_vehicle_list'));
    }
    
}
