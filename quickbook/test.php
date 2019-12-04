<?php
require_once 'vendor/autoload.php';

$dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => "Q0exOpk5ncmFxPbI1HFYYXimHZm5H13bBQtJW9xzkiyKgHymL4",
    'ClientSecret' => "sPtMjs8xByXgxdG5IqgQqSAw94aPXmUOo8YAPU0R",
    'RedirectURI' => "https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl",
    'scope' => "com.intuit.quickbooks.accounting",
    'baseUrl' => "Production" ,//Production
));
$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
$authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
echo $authorizationCodeUrl;


//header('Location: '. $authorizationCodeUrl);


/*$dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => "Q0exOpk5ncmFxPbI1HFYYXimHZm5H13bBQtJW9xzkiyKgHymL4",
    'ClientSecret' => "sPtMjs8xByXgxdG5IqgQqSAw94aPXmUOo8YAPU0R",
    'accessTokenKey' =>  "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..f0_j7oiEI2aGIUtFG0VwXA.e0d6EuAIw7ET7lBnREBgtkcN6e4BIjcaS7HAzFgUhiTkec7-TmQxk1_EQverYYH8bfdrDa4mKpo3zbo-O6MGdZF7kCYloJGOT0zsj6MwYU_EgyzR98jz4hfCx6sWkHUHx0OEFnXINkNu2o-YG0rQK7dha3DZ5PDt4sopUPgCMOS5GrsWllo5DiSORY_gKCbP73-QMA4f_jXJBajnxalyoqCbI5V-P57xVYUJTIrtn_3Zn6zI7hDTag6u2DnL31a53Pey1PpDtryngyQhSAHri_mYyQHrpaEEypHbSgDp3GVsm_SCEExfTnnlyB4B9UoCLDIYZLLpljBhwZ7RCE2rfOeeMOYTn3rmo-jZ5RPphTyInFS6N4UHjwe9KzbmNHmzopOwvu4W9B8dMhq4DyGntkHypUeAYMxDhaD7pLri5KaMNr2YVmXkdBNOSsp2TJ0tqMHueAGR8WDMQnYwKxO_AupO9zZjFNOnWE7fxehjoszxQerlA4HfRYFv6EuemWGy47RtFcR9ZJVCBEmOCtl3_Juc8fiuwcO0bT1ffcmuTGwGfTrBmSEKsHlusAJXosmnKEXTYfYhNJ9YnMtDxtsfr20GHoAdp66JsANOCPYZp8ZnF_DN5gk4gk1viSWynLCFV6LOVjpli54209rI_cXEIRq19bfcqloHpC7WpCN2b6oeevUJ-4ObrBD7L_G-EtDg.h3SwrSPK1m7ghsK8_lZGQg",
    'refreshTokenKey' => 'Q011564219230BnqI4WVWO4YxoI7d2IZx6fG27Et1xjPtXs0Ws',
    'QBORealmID' => "123146439616474",
    'baseUrl' => "Production"
));

$dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
// Add a customer
$customerObj = QuickBooksOnline\API\Facades\Customer::create([
    "CompanyName"=>  "janak harsora",
    "DisplayName"=>  "Jk harsora"
]);
$resultingCustomerObj = $dataService->Add($customerObj);
$error = $dataService->getLastError();
if ($error) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
} else {
    var_dump($resultingCustomerObj);
}*/