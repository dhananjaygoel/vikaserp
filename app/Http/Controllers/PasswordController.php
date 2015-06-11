<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;
class PasswordController extends Controller {
    /*
     * Get change password form
     */

    public function getPassword() {
        return view('change_password');
    }

    /*
     * Get change password form
     */

    public function postPassword(Request $request) {

//        $inputs = $request->except('_token');     
//        $opass = $request->input('old_password');
//        $npass = $request->input('newpassword');
//        $cpass = $request->input('confirm_password');
//        $inputs = array([                       
//                        'newpassword' => $npass,
//                        'confirm_password' => $cpass
//        ]);
//
//        
//        $rules = array([
//                        'newpassword' => 'min:6|max:100',
//                        'confirm_password' => 'min:6|max:100'
//        ]);
//
//        $validator = Validator::make($inputs,$rules);
//
//        if ($validator->fails()) {
////            $errors = $validator->errors()->all();
//            return Redirect::back()->with('error','Please re-enter old password, and new password must be at least 6 characters.');
////            return Redirect::back()->with('errors',$errors);
//        }else{
            $old_password = $request->input('old_password');

            $user = Auth::user();
            $id = $user->id;
            $mobile_number = $user->mobile_number;
            
//            $users= User::where('id',$id)->first();
//            echo '<pre>';
//            print_r($users);
//            echo '</pre>';
//            
//            $password = bcrypt($request->input('old_password'));
//            echo '<br>password : '.$old_password.'<br>password : '.$password." auth pass : ".$user->password." <br> u p ".$users['password'];
//            if($password == ($users['password'])){
//                echo 'same';
//            }else{
//                echo 'different';
//            }
//            exit;
            if (Auth::attempt(['mobile_number' => $mobile_number, 'password' => $old_password])) {
//                $new_password = $request->input('new_password');
                $new_password = bcrypt($request->input('new_password'));

                User::where('id', $id)->update(array('password' => $new_password));
                return redirect('change_password')->with('message', 'Password changed.');
            } else {
                return Redirect::back()->with('error', 'Password mis-matched. Please re-enter old password');
            }      
//        }        
    }

}
