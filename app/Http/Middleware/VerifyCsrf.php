<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrf extends \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken {

    /**
     * Routes we want to exclude.
     *
     * @var array
     */
    protected $routes = [
        'auth/login',
        'applogin',
        'appallcommon',
        'appsync',
        'appsyncinquiry',
        'appsyncinquirypagination',
        'appsyncinquiry_customer',
        'appSyncLabours',
        'appSyncLaboursdelete',
        'appSyncLoadedby',
        'appSyncLoadedbydelete',
        'appsyncterritory',
        'appsyncreceipt',
        'appsyncreceiptdelete',
        'app_customer_login',
        'app_contactus',
        'app_addcustomer',
        'app_customer_profile',
        'customer_resetpassword',
        'generate_otp',
        'verify_otp',
        'app_updatecustomer',
        'appsyncorder',
        'appsyncorderpagination',
        'appsyncorder_customer',
        'appsyncdeliveryorder',
        'appsyncdeliveryorderpagination',
        'appsyncdeliverychallan',
        'appsyncdeliverychallanpagination',
        'appsyncpurchaseorder',
        'appsyncpurchaseorderpagination',
        'appsyncpurchaseadvise',
        'appsyncpurchaseadvisepagination',
        'appsyncpurchasechallan',
        'appsyncpurchasechallanpagination',
        'appupdateuser',
        'appuserprofile',
        'appverifyuserotp',
        'appuserresetpassword',
        'appgenerateuserotp',
        'apporderstatus',
        'appdeleteinquiry',
        'appdeleteorder',
        'appdeletedelivery_order',
        'appdeletedelivery_challan',
        'appdeletepurchase_order',
        'appdeletepurchase_advise',
        'appdeletepurchase_challan',
        'appcustomerdeleteinquiry',
        'appcustomerdeleteorder',
        'appsync_customerorder',
        'appsync_customerinquiry',
        'app_customer_status',
        'app_track_order_status',
        'test_sms',
        'userotp_sms',
        'appsyncinquiry_sms',
        'appsyncinquiryedit_sms',
        'appsyncinquiryapproved_sms',
        'appsyncinquiryreject_sms',
        'appsyncorder_sms',
        'appsyncorderedit_sms',
        'appsyncorderapproved_sms',
        'appsyncorderreject_sms',
        'appsyncdeliveryorder_sms',
        'appsyncdeliverychallan_sms',
        'appsyncpurchaseorder_sms',
        'appsyncpurchaseorderedit_sms',
        'appsyncpurchasechallan_sms',
        'appsyncpurchaseadvise_sms',
        'appaddlabour',
        'appupdatelabour',        
        'appdeletelabour',        
        'appaddloadedby',
        'appupdateloadedby',        
        'appdeleteloadedby',        
        'appaddcollection_admin',        
        'appupdatecollection_admin',        
        'appdeletecollection_admin',        
        'appsynccollection',        
        'appaddterritory_admin',        
        'appupdateterritory_admin',        
        'appdeleteterritory',        
        'appupdateprice',        
        'appchangeunsettledamount_admin',        
        'appsettleamount_admin',        
        'appupdatesettleamount_admin',        
        'graph_order_temp',        
        'graph-order',        
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

//        throw new \TokenMismatchException;
        throw new TokenMismatchException;
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
