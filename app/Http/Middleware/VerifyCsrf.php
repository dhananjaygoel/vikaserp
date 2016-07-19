<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrf extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken {

    /**
     * Routes we want to exclude.
     *
     * @var array
     */
    protected $routes = [
        'auth/login',
        'applogin',
        'appsync',
        'appsync1',
        'app_customer_login',
        'app_contactus',
        'app_addcustomer',
        'app_customer_profile',
        'customer_resetpassword',
        'generate_otp',
        'verify_otp',
        'app_updatecustomer',
        'appsyncorder',
        'appsyncdeliveryorder',
        'appsyncdeliverychallan',
        'appsyncpurchaseorder',
        'appsyncpurchaseadvise',
        'appsyncpurchasechallan',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, \Closure $next) {
        if ($this->isReading($request) || $this->excludedRoutes($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        throw new \TokenMismatchException;
    }

    /**
     * This will return a bool value based on route checking.

     * @param  Request $request
     * @return boolean
     */
    protected function excludedRoutes($request) {
        foreach ($this->routes as $route)
            if ($request->is($route))
                return true;

        return false;
    }

}
