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

    public function postPassword(ChangePasswordRequest $request) {
        $old_password = $request->input('old_password');

        $user = Auth::user();
        $id = $user->id;
        $mobile_number = $user->mobile_number;
        if (Auth::attempt(['mobile_number' => $mobile_number, 'password' => $old_password])) {

            $new_password = Hash::make($request->input('password'));
            User::where('id', $id)->update(array('password' => $new_password));
            return redirect('change_password')->with('message', 'Your password is successfully changed.');
        } else {
            return Redirect::back()->with('error', 'Password mis-matched. Please re-enter old password');
        }
    }

}
