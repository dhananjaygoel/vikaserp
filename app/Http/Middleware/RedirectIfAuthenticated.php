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
			//return new RedirectResponse(url('/home'));
                    if(Auth::user()->role_id == 0){
                        return redirect('dashboard');
                    }
                    if(Auth::user()->role_id == 1){
                        return redirect('admin');
                    }
                    if(Auth::user()->role_id == 2){
                        return redirect('sales_staff');
                    }
                    if(Auth::user()->role_id == 3){
                        return redirect('delivery_staff');
                    }
		}

		return $next($request);
	}

}
