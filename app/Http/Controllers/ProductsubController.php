<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\User;
use Auth;
use App\ProductCategory;
use App\ProductType;
use App\Hsn;
use App\ProductSubCategory;
use App\Http\Requests\ProductSubCategoryRequest;
use Input;
use App;
use Config;
use App\Units;
use App\AllOrderProducts;
use App\PurchaseProducts;
use App\InquiryProducts;
use Maatwebsite\Excel\Facades\Excel;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer
class ProductsubController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        define('PROFILE_ID', Config::get('smsdata.profile_id'));
        define('PASS', Config::get('smsdata.password'));
        define('SENDER_ID', Config::get('smsdata.sender_id'));
        define('SMS_URL', Config::get('smsdata.url'));
        define('SEND_SMS', Config::get('smsdata.send'));
        $this->middleware('validIP');
    }

    /*
     * Export Product size list
     */

    public function exportProductSize() {
        $product_size_list = ProductSubCategory::with('product_category', 'product_unit')->get();
        Excel::create('Product Sizes', function($excel) use($product_size_list) {
            $excel->sheet('Product-Sizes-List', function($sheet) use($product_size_list) {
                $sheet->loadView('excelView.productsize', array('product_size_list' => $product_size_list));
            });
        })->export('xls');
        exit();
    }

    public function index() {
       
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id == 5 ) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
      
        $product_type = ProductType::all();
        $units = Units::all();
        $product_sub_cat = "";
        $input_data = Input::all();

        $q = ProductSubCategory::query();
        $q->with('product_category');
        if (Input::get('product_filter') != "") {
            $q->whereHas('product_category', function($query) {
                $query->where('product_type_id', Input::get('product_filter'));
            });
        }
        if (Input::get('search_text') != "") {

            $q->whereHas('product_category', function($query) {
                $query->where('product_category_name', 'like', '%' . Input::get('search_text') . '%');
            });
        }
        if (Input::get('product_size') != "") {
            if (strpos(Input::get('product_size'), '-') !== false) {
                $size_ar = explode("-", Input::get('product_size'));
                $size = $size_ar[0];
                $size2 = $size_ar[1];
                $q->whereHas('product_category', function($query) use ($size, $size2) {
//                    $query->where('size', 'like', '%' . trim($size) . '%')->orWhere('alias_name', 'like', '%' . trim($size2) . '%');
                    $query->where('alias_name', 'like', '%' . trim($size2) . '%');
                });
            } else {
                $blanck = Input::get('product_size');
                $q->whereHas('product_category', function($query) use ($blanck) {
                    $query->where('size', 'like', '%' . $blanck . '%')
                            ->orWhere('alias_name', 'like', '%' . $blanck . '%');
                });
            }
        }

        if (Input::has('export_data') && Input::get('export_data') == 'Export') {
           set_time_limit(0);
            $product_size_list = $q->orderBy('id', 'asc')->get();
            Excel::create('Product Sizes', function($excel) use($product_size_list) {
                $excel->sheet('Product-Sizes-List', function($sheet) use($product_size_list) {
                    $sheet->loadView('excelView.productsize', array('product_size_list' => $product_size_list));
                });
            })->export('xls');
            exit();
        }

        $product_sub_cat = $q->orderBy('id', 'DESC')->paginate(20);

        $filter = array(Input::get('product_size'), Input::get('search_text'), Input::get('product_filter'));
        $product_sub_cat->setPath('product_sub_category');
        return view('product_sub_category', compact('product_sub_cat', 'product_type', 'units', 'filter'));
    }

    /*
     * Show add new product sub category form
     *
     */

    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $product_type = ProductType::all();
        $units = Units::first();
        $hsn_code = Hsn::all();
        return view('add_product_sub_category', compact('product_type','hsn_code', 'units'));
    }

    /*
     * Show sub product category from product category
     *
     */

    public function get_product_category() {

        $product_cat = ProductCategory::where('product_type_id', Input::get('product_type_id'))->orderby('product_category_name', 'ASC')->get();
        $prod = array();
        $i = 0;
        foreach ($product_cat as $key => $val) {
            $prod[$i]['id'] = $product_cat[$key]->id;
            $prod[$i]['product_category_name'] = $product_cat[$key]->product_category_name;
            $i++;
        }
        echo json_encode(array('prod' => $prod));
        exit;
    }

    public function get_hsn_code(){
        $product_cat = ProductCategory::where('id', Input::get('id'))->first();
        echo $product_cat->hsn_code;
        exit;
    }

    public function get_product_type() {

        $product_cat = ProductType::get();
        $prod = array();
        $i = 0;
        foreach ($product_cat as $key => $val) {
            $prod[$i]['id'] = $product_cat[$key]->id;
            $prod[$i]['name'] = $product_cat[$key]->name;
            $i++;
        }
        echo json_encode(array('prod' => $prod));
        exit;
    }


    function quickbook_create_item($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = Item::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }
    function quickbook_update_item($quickbook_item_id,$data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getToken();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        // $dataService->throwExceptionOnError(true);
        $resultingObj  = $dataService->FindById('Item', $quickbook_item_id);
        $customerObj = Item::update($resultingObj,$data);
        // dd($dataService->Update($customerObj));
        $resultingCustomerObj = $dataService->Update($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }

    function getToken(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(4);
        
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130346851577266",
            'baseUrl' => "Production",
            'minorVersion'=>34
        ));
    }


    function refresh_token(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(4);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }
    function getTokenWihtoutGST(){

        require_once base_path('quickbook/vendor/autoload.php');
        // $quickbook = App\QuickbookToken::first();
        $quickbook = App\QuickbookToken::find(3);
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130346851582276",
            'baseUrl' => "Production",
            'minorVersion'=>34
        )); 

    }
    function refresh_token_Wihtout_GST(){
        require_once base_path('quickbook/vendor/autoload.php');
        // $quickbook = App\QuickbookToken::first();
        $quickbook = App\QuickbookToken::find(3);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);         
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }





/*This is start quickbook account All inclusive*/
    function quickbook_create_a_item($data){
        require_once base_path('quickbook/vendor/autoload.php');
        $dataService = $this->getTokenAll();
        $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
        $customerObj = Item::create($data);
        $resultingCustomerObj = $dataService->Add($customerObj);
        $error = $dataService->getLastError();
        if ($error) {
            return ['status'=>false,'message'=>$error->getResponseBody()];
        } else {
            return ['status'=>true,'message'=>$resultingCustomerObj];
        }
    }
    function getTokenAll(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(4);
      
        return $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $quickbook->client,
            'ClientSecret' => $quickbook->secret,
            'accessTokenKey' =>  $quickbook->access_token,
            'refreshTokenKey' => $quickbook->refresh_token,
            'QBORealmID' => "9130346851582276",
            'baseUrl' => "Production",
            'minorVersion'=>34
        ));
    }
    function refresh_token_all(){
        require_once base_path('quickbook/vendor/autoload.php');
        $quickbook = App\QuickbookToken::find(3);
        $oauth2LoginHelper = new OAuth2LoginHelper($quickbook->client,$quickbook->secret);
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($quickbook->refresh_token);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        App\QuickbookToken::where('id',$quickbook->id)->update(['access_token'=>$accessTokenValue,'refresh_token'=>$refreshTokenValue]);
    }
/*This is end quickbook account All inclusive*/

    /*
     * Add new product sub category data in to database
     *
     */

    public function store(ProductSubCategoryRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }




        $ProductSubCategory = new ProductSubCategory();

        $thickness = explode(':',$request->input('thickness'))[0];

        $pcat = ProductCategory::where('id',$request->input('sub_product_name'))->first();


       $Qdata = [
            "Name" => $request->input('alias_name'),
            "Active" => true,
            "FullyQualifiedName" => $request->input('alias_name'),
            "UnitPrice" => $pcat->price + $request->input('difference'),
            "Type" => "NonInventory",
            "TaxClassificationRef"=>$request->input('hsn_code'),
            "IncomeAccountRef"=> [
                "value"=> 3,
                "name" => "IncomRef"
            ],
            "TrackQtyOnHand"=>false,
           

        ];
        $inclusiveitemid ="";
        $gstitemid = "";
        $dataService = $this->getTokenWihtoutGST();
        $newItemObj = Item::create($Qdata);
        $newitem = $dataService->add($newItemObj);
        $error = $dataService->getLastError();
        
        if ($error) { 
            $this->refresh_token_Wihtout_GST();
            $dataService = $this->getTokenWihtoutGST();  
        }
        else{
            $inclusiveitemid =  $newitem->Id;
        }
        $nextdataservice = $this->getToken();
        $newiteminclusive = $nextdataservice->add($newItemObj);
        $error1 = $nextdataservice->getLastError();
        if ($error1) { 
            $this->refresh_token();
            $dataService = $this->getToken();  
        }
        else{
            $gstitemid  =  $newiteminclusive->Id;
        }
        $ProductSubCategory->quickbook_a_item_id = $gstitemid;
        $ProductSubCategory->quickbook_item_id  = $inclusiveitemid;

        

        /*$res = $this->quickbook_create_item($Qdata);
        if($res['status']){

            $ProductSubCategory->quickbook_item_id = $res['message']->Id;
        }
        else{
            $this->refresh_token();
            $res = $this->quickbook_create_item($Qdata);
            if($res['status']){
                $ProductSubCategory->quickbook_item_id = $res['message']->Id;
            }
        }

        $res = $this->quickbook_create_a_item($Qdata);
        if($res['status']){

            $ProductSubCategory->quickbook_a_item_id = $res['message']->Id;
        }
        else{
            $this->refresh_token_all();
            $res = $this->quickbook_create_a_item($Qdata);
            if($res['status']){
                $ProductSubCategory->quickbook_a_item_id = $res['message']->Id;
            }
        }
        */
        //dd($res);
       
        $ProductSubCategory->product_category_id = $request->input('sub_product_name');
        $ProductSubCategory->alias_name = $request->input('alias_name');
        $ProductSubCategory->hsn_code = $request->input('hsn_code');
        $ProductSubCategory->size = $request->input('size');
        $ProductSubCategory->weight = $request->input('weight');
        $ProductSubCategory->unit_id = $request->input('units');
        $ProductSubCategory->thickness = $thickness;
        $ProductSubCategory->standard_length = $request->input('standard_length');
        $ProductSubCategory->difference = $request->input('difference');

        if($request->input('product_category') == 3){
            $ProductSubCategory->length_unit = $request->input('length_unit');
        }

        // mm and ft
        //$ProductSubCategory->length_unit = $request->input('length_unit');

        if($ProductSubCategory->save() && isset($ProductSubCategory->id)){
            $inventory = new \App\Inventory();
            $inventory->product_sub_category_id = $ProductSubCategory->id;
            $inventory->save();
        }

        /*
         * ------------------- -----------------------
         * SEND SMS TO ALL ADMINS FOR NEW PRODUCT SIZE
         * -------------------------------------------
         */
        $input = Input::all();
        if (isset($input['sendsms']) && $input['sendsms'] == "true") {
            $admins = User::where('role_id', '=', 0)->get();
            if (count($admins) > 0) {
                foreach ($admins as $key => $admin) {
                    $product_category = ProductCategory::with('product_type')->find($request->input('select_product_categroy'));
                    $str = "Dear " . $admin->first_name . " \n" .
                            "DT " . date("j M, Y") . "\n" .
                            Auth::user()->first_name . " has created a new size as "
                            . "'" . $request->input('size') . "', "
                            . "'" . $request->input('hsn_code') . "', "
                            . "'" . $request->input('thickness') . "', "
                            . "'" . $request->input('weight') . "', "
                            . "'" . $request->input('alias_name') . "', "
                            . "'" . $request->input('difference') . "' "
                            . "under "
                            . "'" . $product_category->product_category_name . "' "
                            . "& "
                            . "'" . $product_category['product_type']->name . "' "
                            . "kindly check. \nVIKAS ASSOCIATES";


                    if (App::environment('development')) {
                        $phone_number = Config::get('smsdata.send_sms_to');
                    } else {
                        $phone_number = $admin->mobile_number;
                    }
                    $msg = urlencode($str);
                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                    if (SEND_SMS === true) {
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $curl_scraped_page = curl_exec($ch);
                        curl_close($ch);
                    }
                }
            }
        }

        return redirect('product_sub_category')->with('success', 'Product sub category successfully added.');
    }

    /*
     * Remove product sub category and also check for validation if data is associated with other order the do not delete
     *
     */

    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        if (Auth::attempt(['mobile_number' => Input::get('mobile'), 'password' => Input::get('model_pass')])) {
            $product_cat = ProductSubCategory::find($id);
            $order_count = AllOrderProducts::where('product_category_id', $id)->count();
            $purchase_count = PurchaseProducts::where('product_category_id', $id)->count();
            $inquery_count = InquiryProducts::where('product_category_id', $id)->count();

            if ($purchase_count == 0 && $order_count == 0 && $inquery_count == 0) {
                ProductSubCategory::destroy($id);
                if(isset($_GET['page']) && $_GET['page'] != ""){
                    $page = $_REQUEST['page'];
                    return redirect('product_sub_category?page='.$page)->with('success', 'Product sub category details successfully deleted.');
                }
                return redirect('product_sub_category')->with('success', 'Product sub category details successfully deleted.');
            } else {
                if(isset($_GET['page']) && $_GET['page'] != ""){
                    $page = $_REQUEST['page'];
                    return redirect('product_sub_category?page='.$page)->with('wrong', 'Product size has already added by user, you can not delete this record.');
                }
                return redirect('product_sub_category')->with('wrong', 'Product size has already added by user, you can not delete this record.');
            }
        } else {
            if(isset($_GET['page']) && $_GET['page'] != ""){
                $page = $_REQUEST['page'];
                return redirect('product_sub_category?page='.$page)->with('wrong', 'You have entered wrong credentials');
            }
            return redirect('product_sub_category')->with('wrong', 'You have entered wrong credentials');
        }
    }

    /*
     * Edit product sub category details
     *
     */

    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('product_sub_category')->with('error', 'You do not have permission.');
        }
        $prod_sub_cat = ProductSubCategory::with('product_category', 'product_unit')->find($id);
        if (count($prod_sub_cat) < 1) {
            return redirect('product_sub_category')->with('success', 'Product sub category does not exist.');
        }
        $product_type = ProductType::all();
        $prod_category = ProductCategory::all();
        $hsn_code = Hsn::all();
        $units = Units::first();
        return view('edit_product_sub_category', compact('product_type','hsn_code', 'prod_sub_cat', 'prod_category', 'units'));
    }

    /*
     * Update product sub category details
     *
     */

    public function update($id) {
        $validator = Validator::make(Input::all(), ProductSubCategory::$product_sub_category_rules);
 

        if ($validator->passes()) {
            $data = Input::all();
            $pro_sub_cat = array(
                'product_category_id' => $data['sub_product_name'],
                'size' => $data['size'],
                'hsn_code' => $data['hsn_code'],
                'weight' => $data['weight'],
                'unit_id' => $data['units'],
                'thickness' => $thickness = explode(':',$data['thickness'])[0],
                'standard_length' => $data['standard_length'],
                'difference' => $data['difference'],
            );
            if(isset($data['product_category']) && $data['product_category'] == 3){
                $pro_sub_cat['length_unit'] = $data['length_unit'];
            }

            $alias_count = ProductSubCategory::where('id', '!=', $id)->where('alias_name', '=', $data['alias_name'])->count();
            if ($alias_count > 0) {
                return Redirect::back()->withInput()->with('alias', 'Alias name already taken.');
            } else {
                $pro_sub_cat['alias_name'] = Input::get('alias_name');
            }
            $pcat = ProductCategory::where('id',$data['sub_product_name'])->first();
                $Qdata = [
                "Name" => Input::get('alias_name'),
                // "Active" => true,
                "sparse"=> false, 
                "Active"=> true, 
                "SyncToken"=> "3",
                "FullyQualifiedName" => Input::get('alias_name'),
                "UnitPrice" => $pcat->price + $data['difference'],
                "Type" => "NonInventory",
                "TaxClassificationRef"=>Input::get('hsn_code'),
                "QtyOnHand"=> 1,
                // "PurchaseCost"=> $pcat->price,                
                "IncomeAccountRef"=> [
                    "value"=> 3,
                    "name" => "IncomRef"
                ],
                "TrackQtyOnHand"=>false
                // "TaxClassificationRef"=>[
                //     "value"=>1204
                // ]
            ];

            // $Qdata = [
            //   "FullyQualifiedName" => "Rock Fountain", 
            //   "domain"=> "QBO", 
            //   "Id" => "28", 
            //   "Name" => "Rock Fountain", 
            //   "Type"=> "NonInventory", 
            //   "PurchaseCost"=> 125, 
            //   "ReverseChargeRate"=>1.0,
            //   "sparse" => false, 
            //   "Active" => true, 
            //   "SyncToken" => "2", 
            //   "UnitPrice" => 275, 
            //   "IncomeAccountRef"=> [
            //     "name" => "IncomRef", 
            //     "value" => "3"
            //   ], 
            //   "PurchaseDesc" => "Rock Fountain", 
            //   "Description" => "New, updated description for Rock Fountain"
            // ];

            // $ProductSubCategory = ProductSubCategory::where('id',$id)->first();
            $ProductSubCategory = ProductSubCategory::find($id);
            /*$quickbook_item_id=$ProductSubCategory->quickbook_item_id;
            if($quickbook_item_id)  
            {          
                $res = $this->quickbook_update_item($quickbook_item_id,$Qdata);
                if($res['status']){
                    // $ProductSubCategory->quickbook_item_id = $res['message']->Id;
                }
                else{
                    $this->refresh_token();
                    $res = $this->quickbook_update_item($quickbook_item_id,$Qdata);
                    if($res['status']){
                        // $ProductSubCategory->quickbook_item_id = $res['message']->Id;
                    }
                }
            }*/
            $inclusiveitemid ="";
            $gstitemid = "";
            $dataService = $this->getTokenWihtoutGST();
            print $productname=Input::get('alias_name');
            print $item_query = "select * from Item where Name ='NS'";
            $item_details = $dataService->Query($item_query);
            print_R($item_details);
            die();
            //$resultingObj  = $dataService->F$item_details[0]->Id;indById('Item', $quickbook_item_id);
            //$newItemObj = Item::update($Qdata);
            $newitem = $dataService->add($newItemObj);
            $error = $dataService->getLastError();
            if ($error) { 
                $this->refresh_token_Wihtout_GST();
                $dataService = $this->getTokenWihtoutGST();  
            }
            else{
                $inclusiveitemid =  $newitem->Id;
            }
            $nextdataservice = $this->getToken();
            $newiteminclusive = $nextdataservice->add($newItemObj);
            $error1 = $nextdataservice->getLastError();
            if ($error1) { 
                $this->refresh_token();
                $dataService = $this->getToken();  
            }
            else{
                $gstitemid  =  $newiteminclusive->Id;
            }
            $ProductSubCategory->quickbook_a_item_id = $gstitemid;
            $ProductSubCategory->quickbook_item_id  = $inclusiveitemid;
            ProductSubCategory::where('id', $id)->update($pro_sub_cat);
            /*
             * ------------------- -------------------------
             * SEND SMS TO ALL ADMINS ON UPDATE PRODUCT SIZE
             * ---------------------------------------------
             */
            $input = Input::all();
            if (isset($input['sendsms']) && $input['sendsms'] == "true") {
                $admins = User::where('role_id', '=', 0)->get();
                if (count($admins) > 0) {
                    foreach ($admins as $key => $admin) {
                        $product_category = ProductCategory::with('product_type')->find($data['select_product_categroy']);
                        $str = "Dear "
                                . "'" . $admin->first_name . "'\nDT " . date("j M, Y") . "\n"
                                . "'" . Auth::user()->first_name . "'"
                                . " has updated a size category as "
                                . "'" . $data['size'] . "' "
                                . "'" . $data['hsn_code'] . "' "
                                . "'" . $data['thickness'] . "' "
                                . "'" . $data['weight'] . "' "
                                . "'" . $data['alias_name'] . "' "
                                . "'" . $data['difference'] . "' "
                                . "under "
                                . "'" . $product_category->product_category_name . "' "
                                . "& "
                                . "'" . $product_category['product_type']->name . "' "
                                . "kindly check.\nVIKAS ASSOCIATES";

                        if (App::environment('development')) {
                            $phone_number = Config::get('smsdata.send_sms_to');
                        } else {
                            $phone_number = $admin->mobile_number;
                        }
                        $msg = urlencode($str);
                        $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                        if (SEND_SMS === true) {
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $curl_scraped_page = curl_exec($ch);
                            curl_close($ch);
                        }
                    }
                }
            }
            return redirect('product_sub_category')->with('success', 'Product sub category successfully updated.');
        } else {
            $error_msg = $validator->messages();
            return Redirect::back()->withInput()->withErrors($validator);
        }
    }

    public function update_difference() {
        ProductSubCategory::where('id', Input::get('id'))->update(array('difference' => Input::get('difference')));

        /*
         * ------------------- -----------------------
         * SEND SMS TO ALL ADMINS FOR NEW PRODUCT SIZE
         * -------------------------------------------
         */
        $input = Input::all();

        $admins = User::where('role_id', '=', 0)->get();
        if (count($admins) > 0) {
            foreach ($admins as $key => $admin) {
                $productsubcategory = ProductSubCategory::find(Input::get('id'));
                $product_category = ProductCategory::with('product_type')->find($productsubcategory->product_category_id);


                $str = "Dear " . $admin->first_name . " \n" .
                        "DT " . date("j M, Y") . "\n" .
                        Auth::user()->first_name . " has edited a new size as "
                        . "'" . $productsubcategory->size . "', "
                        . "'" . $productsubcategory->thickness . "', "
                        . "'" . $productsubcategory->weight . "', "
                        . "'" . $productsubcategory->alias_name . "', "
                        . "'" . $productsubcategory->difference . "' "
                        . "under "
                        . "'" . $product_category->product_category_name . "' "
                        . "& "
                        . "'" . $product_category['product_type']->name . "' "
                        . "kindly check. \nVIKAS ASSOCIATES";


                if (App::environment('development')) {
                    $phone_number = Config::get('smsdata.send_sms_to');
                } else {
                    $phone_number = $admin->mobile_number;
                }
                $msg = urlencode($str);
                $url = SMS_URL . "?user=" . PROFILE_ID . "&pwd=" . PASS . "&senderid=" . SENDER_ID . "&mobileno=" . $phone_number . "&msgtext=" . $msg . "&smstype=0";
                if (SEND_SMS === true) {
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                }
            }
        }


//        return redirect('product_sub_category')->with('success', 'Product sub category difference successfully updated.');
    }

    public function get_product_weight() {
        $product_id = Input::get('product_id');
        $product_cat = ProductSubCategory::find($product_id);
        $product_weight = $product_cat['weight'];
        return $product_weight;
    }

    public function fetch_product_size() {
        $term = '%' . Input::get('term') . '%';
        $product = ProductSubCategory::where('size', 'like', $term)
                ->orWhere('alias_name', 'like', $term)->orderBy('size', 'desc')->orderBy('alias_name', 'desc')
                ->get();

        if (count($product) > 0) {
            foreach ($product as $prod) {
                $data_array[] = [
                    'value' => $prod->size . " - " . $prod->alias_name,
                    'id' => $prod->size
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No size found',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    public function fetch_product_name() {
        $term = '%' . Input::get('term') . '%';
        $product = ProductCategory::where('product_category_name', 'like', $term)->get();
        if (count($product) > 0) {
            foreach ($product as $prod) {
                $data_array[] = [
                    'value' => $prod->product_category_name
                ];
            }
        } else {
            $data_array[] = [
                'value' => 'No Product found',
            ];
        }
        echo json_encode(array('data_array' => $data_array));
    }

    /*
     * ------------------- -------------------------
     * Description : UPDATE ALL PRODUCT SIZE
     * ---------------------------------------------
     */

    public function update_all_sizes_difference() {
        $data = Input::get('form_data');
        $unserialized_data = parse_str(Input::get('form_data'), $formfields);
        $currentpage = Input::get('pageid');
        $startrow = (20 * $currentpage) - 19;
        $endrow = 20 * $currentpage;
        for ($i = $startrow; $i <= $endrow; $i++) {
            if (isset($formfields['difference_' . $i])) {
                ProductSubCategory::where('id', $formfields['id_' . $i])->update(array('difference' => $formfields['difference_' . $i]));
            }
        }
    }

}
