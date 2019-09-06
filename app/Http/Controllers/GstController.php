<?php namespace App\Http\Controllers;

use App\Gst;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\QuickbookToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

class GstController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $gst = Gst::orderBy('id','DESC')->paginate(10);
        return view('gst', compact('gst'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

    function getToken(){
        require_once base_path('quickbook/vendor/autoload.php');        
        $quickbook = QuickbookToken::find(3);

        // without gst
        $config = array(
            'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2',
            'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
            'client_id' => 'ABB6G2Da7EJLem4XfeXEn6vgdisRSVijSzdWNRKExRHVwssNVH',
            'client_secret' => 'dUOLrdPi7J0hNzndtc7uUEconleZa9BeuoknEFay',
            'oauth_scope' => 'com.intuit.quickbooks.accounting',
            'oauth_redirect_uri' => 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl',
        );

        // with gst
        // $config = array(
        //         'authorizationRequestUrl' => 'https://appcenter.intuit.com/connect/oauth2',
        //         'tokenEndPointUrl' => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',
        //         'client_id' => 'ABoIBAIbfSTIWlk33WVrUrHExZZYGohkVdsTsOylDuQeSGAqfO',
        //         'client_secret' => 'RYYM8gPpTIRmF3HIjTM1MQOCsJ8Hld0D22Uv6iub',
        //         'oauth_scope' => 'com.intuit.quickbooks.accounting',
        //         'oauth_redirect_uri' => 'https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl',
        //     );
        // $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
        //     'auth_mode' => 'oauth2',
        //     'ClientID' => $config['client_id'],
        //     'ClientSecret' =>  $config['client_secret'],
        //     'RedirectURI' => $config['oauth_redirect_uri'],
        //     'scope' => $config['oauth_scope'],
        //     'baseUrl' => "production"
        // ));
    
        // $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        // $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

        // without gst
        // $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken("AB11564734804jpXqkxC7OwfIpXZ0m4O24b6BXfAd38yGBUrgx", "9130346686571536");
        // $accessTokenValue = $accessTokenObj->getAccessToken();
        // $refreshTokenValue = $accessTokenObj->getRefreshToken();

        // with gst
        // $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken("AB11564734043Np6ZBfvYQAxqCc0kmISUM3Fq9VMcoyrPMSYkF", "9130346686579506");
        // $accessTokenValue = $accessTokenObj->getAccessToken();
        // $refreshTokenValue = $accessTokenObj->getRefreshToken();
        
    
    
        // echo '<pre>';
        // print_r($authUrl);
        // print_r($accessTokenValue);
        // echo '<br>';
        // echo '<br>';
        // print_r($refreshTokenValue);
        // exit;
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130346851582276",
            'baseUrl' => "production"));
        // return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
        //         'auth_mode' => 'oauth2',
        //         'ClientID' => $quickbook->client,
        //         'ClientSecret' => $quickbook->secret,
        //         'accessTokenKey' =>  $quickbook->access_token,
        //         'refreshTokenKey' => $quickbook->refresh_token,
        //         'QBORealmID' => "4611809164061438748",
        //         'baseUrl' => "Development"));
    }


    function refresh_token(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = QuickbookToken::find(3);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }

	public function create()
	{
       /* require_once base_path('quickbook/vendor/autoload.php');
	    //$quickgst = [];
        $dataService = $this->getToken();

       
        $quickgst = $dataService->Query('select * From TaxCode');
        $error = $dataService->getLastError();
        $CompanyInfo = $dataService->getCompanyInfo();
        // $firstTenInvoices = $dataService->Query("SELECT * FROM Invoice", 1, 10);
        // echo '<pre>';
        // print_r( $quickgst);
        // exit;
        
        if ($error) {
            $this->refresh_token();
            $dataService = $this->getToken();           
            $quickgst = $dataService->Query('select * From TaxCode');            
        }
        // echo '<pre>';
        // print_r($quickgst);
        // exit;
        */
		return view('gst_add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        // $this->validate($request, [
        //     'gst' => 'required|integer|unique:gst,gst',
        //     'sgst' => 'required|integer',
        //     'cgst' => 'required|integer',
        //     'igst' => 'required|integer',
        //     'quick_gst_id'=>'required'
        // ]);
         $this->validate($request, [
            'gst' => 'required|numeric|between:0,99.99|unique:gst',
            'sgst' => 'required|numeric|between:0,99.99',
            'cgst' => 'required|numeric|between:0,99.99',
            'igst' => 'required|numeric|between:0,99.99',
           // 'quick_gst_id'=>'required'
        ]);


        $thickness = new Gst();
        $thickness->gst = $request->gst;
        $thickness->sgst = $request->sgst;
        $thickness->cgst = $request->cgst;
        $thickness->igst = $request->igst;
        //$thickness->quick_gst_id = $request->quick_gst_id;
        $thickness->save();


        return redirect('gst')->with('flash_success_message', 'GST successfully added.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
       return \redirect('gst');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

        /*require_once base_path('quickbook/vendor/autoload.php');
        //$quickgst = [];
        $dataService = $this->getToken();
        $quickgst = $dataService->Query('select * From TaxCode');
        $error = $dataService->getLastError();
        if ($error) {
            $this->refresh_token();
            $dataService = $this->getToken();
            $quickgst = $dataService->Query('select * From TaxCode');
        }*/

        if (Auth::user()->role_id != 0) {
            return Redirect::to('gst')->with('error', 'You do not have permission.');
        }
        $gst = Gst::find($id);
        return view('gst_edit', compact('gst'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request)
	{
        if (Auth::user()->role_id != 0) {
            return Redirect::to('gst')->with('error', 'You do not have permission.');
        }
        $this->validate($request, [
            'gst' => 'required|numeric|between:0,99.99|unique:gst,gst',
            'sgst' => 'required|numeric|between:0,99.99',
            'cgst' => 'required|numeric|between:0,99.99',
            'igst' => 'required|numeric|between:0,99.99',
            //'quick_gst_id'=>'required'
        ]);

        Gst::where('id',$id)->update([
            'gst' => $request->gst,
            'sgst' => $request->sgst,
            'cgst' => $request->cgst,
            'igst' => $request->igst,
            //'quick_gst_id'=>$request->quick_gst_id
        ]);

        return redirect('gst')->with('flash_success_message', 'Gst updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if (Auth::user()->role_id != 0) {
            return Redirect::to('gst')->with('error', 'You do not have permission.');
        }

        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            Gst::find($id)->delete();
            return redirect('gst')->with('flash_success_message', 'Gst details successfully deleted.');
        } else
            return redirect('gst')->with('flash_message', 'Please enter a correct password');
	}

}
