<?php

namespace App\Http\Middleware;

use Closure;
use App\Security;
use App\Http\Requests\Request;
use Auth;

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
        if (count((array)$ip) > 0) {
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
                //  && Auth::user()->role_id != 2
                // if (!in_array($ipaddress, $ip_array) && (Auth::user()->role_id = 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 4 && Auth::user()->role_id != 8 && Auth::user()->role_id != 9 && Auth::user()->role_id != 2 && Auth::user()->role_id != 7)) {
                if (!in_array($ipaddress, $ip_array) && Auth::user()->role_id != 0 && Auth::user()->role_id != 1 && Auth::user()->role_id != 5) {
                    return redirect('dashboard');
                }

                if(Auth::user()->role_id == 10){
                    return redirect('bulk-delete');
                }
            }
        }
        return $next($request);
    }

}
