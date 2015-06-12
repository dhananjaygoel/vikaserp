<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Auth;
class RedirectIfAuthenticated {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->check())
		{
			
//                    if(Auth::user()->role_id == 0){
//                        return new RedirectResponse(url('/dashboard'));    
//                    }
//                    if(Auth::user()->role_id == 1){
//                        return new RedirectResponse(url('/dashboard'));    
//                    }
//                    if(Auth::user()->role_id == 2){
//                        return new RedirectResponse(url('/dashboard'));    
//                    }
//                    if(Auth::user()->role_id == 3){
//                        return new RedirectResponse(url('/dashboard'));    
//                    }
                        return new RedirectResponse(url('/dashboard'));                    
		}
		return $next($request);
	}

}
