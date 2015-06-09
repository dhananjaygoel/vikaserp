<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LoggedinMiddleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::check()) {
            if (Auth::user()->role_id == 0) {
                return redirect('dashboard');
            } else if (Auth::user()->role_id == 1) {
                return redirect('dashboard');                
            }else if (Auth::user()->role_id == 2) {
                return redirect('dashboard');                
            }else if (Auth::user()->role_id == 3) {
                return redirect('dashboard');                
            }            
        } else {

            return view('auth.login');
        }

//        return $next($request);
    }

}
