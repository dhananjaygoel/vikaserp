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
use Config;

class PasswordController extends Controller {
    
    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('auth');
    }
    
    public function getInfo() {
        phpinfo();
    }
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
            $new_password = Hash::make($request->input('new_password'));
            User::where('id', $id)->update(array(
                'password' => $new_password,
                'password_updated_at' => $current_time = \Carbon\Carbon::now()->toDateTimeString()
                    ));
            return redirect('change_password')->with('message', 'Your password is successfully changed.');
        } else {
            return Redirect::back()->with('error', 'Password mis-matched. Please re-enter old password');
        }
    }

}
