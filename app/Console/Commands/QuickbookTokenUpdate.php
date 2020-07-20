<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twilio\Rest\Client;
use App\QuickbookToken;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

class QuickbookTokenUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quickbooks_token:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update token in both Quickbooks account';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        require_once base_path('quickbook/vendor/autoload.php');
        //refresh token for all inc account
        $quickbook = QuickbookToken::find(1);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    

        //refresh token for plus gst account
        $quickbook = QuickbookToken::find(2);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    
        $this->info('Token has been successfully updated.');
    }
}
