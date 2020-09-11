<?php

namespace App\Http\Middleware;

use Closure;
use App\Security;
use App\Http\Requests\Request;
use Auth;
use Illuminate\Support\Facades\Session;

class validIpMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        $ip = Security::all();
        $ip_array = [];
        if (isset($ip) && !$ip->isEmpty()) {
            foreach ($ip as $key => $value) {
                $ip_array[$key] = $value->ip_address;
            }

            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if (getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if (getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if (getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if (getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if (getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';

            if ($ipaddress != 'UNKNOWN') {
                $otp_validate = Session::has('otp_validate')?Session::has('otp_validate'):false;
                // if (!in_array($ipaddress, $ip_array) && (Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4 && Auth::user()->role_id != 8 && Auth::user()->role_id != 9 && Auth::user()->role_id != 2 && Auth::user()->role_id != 7)) {
                if (in_array($ipaddress, $ip_array) ){
					Session::put('send_otp', false);
                    return redirect('otp_verification');
                    // return redirect('dashboard');
                    //return $next($request);
                }elseif (in_array($ipaddress, $ip_array) && Auth::user()->role_id == 0){
					Session::put('send_otp', false);
                    return redirect('otp_verification');
                    //return $next($request);
                }else if(in_array($ipaddress, $ip_array) && Auth::user()->role_id == 10){
					Session::put('send_otp', false);
                    return redirect('otp_verification');
                    //return redirect('bulk-delete');
                }else if(!in_array($ipaddress, $ip_array) && Auth::user()->role_id == 2){
                    if($_SERVER['REQUEST_URI'] == '/dashboard' || $request->is('inquiry/*') || $request->is('orders/*') || $request->is('fetch_existing_customer*') || $request->is('fetch_products*')){
                        return $next($request);
                    }else{
                        return redirect()->back()->with(['error'=>'You are not Autherized to access with this IP Address.']);
                    }
                }
                else{
                    if($otp_validate == true && Auth::user()->role_id == 0){
                        return $next($request);
                    }elseif(!in_array($ipaddress, $ip_array) && $otp_validate == true && (Auth::user()->role_id == 8 || Auth::user()->role_id == 9)){
                        if($_SERVER['REQUEST_URI'] == '/dashboard' || $_SERVER['REQUEST_URI'] == '/delivery_order' || $request->is('delivery_order/*') || $request->is('create_load_truck/*') || $request->is('save_empty_truck*') || $request->is('save_product*') || $request->is('save_truck_weight*') || $request->is('del_boy_reload*') || $request->is('loaded_assign1')){
                            return $next($request);
                        }else{
                            return redirect('delivery_order')->with('error','You are not Autherized to access with this IP Address.');
                        }
                    }elseif($otp_validate == true){
                        return redirect('ip_invalid')->with('flash_message','You are not Autherized to access with this IP Address.');
                    }else{
                        Session::put('send_otp', false);
                        return redirect('otp_verification');
                    }
                }
            }
        }
        // if(Auth::user()->role_id != 0){
            $logged_in = Session::has('logged_in')?Session::get('logged_in'):false;
            $otp_validate = Session::has('otp_validate')?Session::has('otp_validate'):false;
            if($logged_in == true){
                Session::put('send_otp', false);
                Session::forget('logged_in');
                return redirect('otp_verification');
            }elseif($otp_validate == false){
                Session::put('send_otp', false);
                return redirect('otp_verification');
            }else {
                return $next($request);
            }
        // }else{
        //     return $next($request);
        // }   
    }
}
