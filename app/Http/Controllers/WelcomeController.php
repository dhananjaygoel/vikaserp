<?php

namespace App\Http\Controllers;

use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;
use App\States;
use App\ProductCategory;
use App\ProductSubCategory;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;
use Redirect;
use App\City;
use App\DeliveryLocation;
use App\Customer;
use Session;
use Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Dropbox\Client;
use Dropbox\WriteMode;
use Illuminate\Filesystem\Filesystem;
use Dropbox;
use Auth;
use Carbon\Carbon;
use SmsBump;

class WelcomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Welcome Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders the "marketing page" for the application and
      | is configured to only allow guests. Like most of the other sample
      | controllers, you are free to modify or remove it as you desire.
      |
     */

    /**
     * Create a new controller instance.
     *
     */
    public function dropboxfile() {

//        $Client = new Client(config('filesystems.dropbox.key'), config('filesystems.dropbox.secret'));
//        $file = fopen(public_path('images/angular_crud.png'), 'rb');
//        $size = filesize(public_path('images/angular_crud.png'));
//        $dropboxFileName = '/dropboxfile-name.png';
//        $Client->uploadFile($dropboxFileName, WriteMode::add(), $file, $size);
//        $client = new Client(Config::get('filesystems.disks.dropbox.token'), Config::get('filesystems.disks.dropbox.app_secret'));
        $client = new Client(Config::get('filesystems.disks.dropbox.token'), Config::get('filesystems.disks.dropbox.app_secret'));
        echo "<pre>";
        print_r($client);
        echo "<pre>";
        exit();
//        $this->filesystem = new Filesystem(new Dropbox($client, '/'));
//        $file = $this->filesystem->getAccountInfo("demo.txt");
//        $file = $client->getClientIdentifier();



        $f = fopen("demo.txt", "w+b");
        $fileMetadata = $client->getFile("/working-draft.txt", $f);
        fclose($f);
        print_r($fileMetadata);

        exit();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
//		$this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     */
    public function index() {
        return view('welcome');
    }

    public function test() {

//        $post_data = array(
//            // 'From' doesn't matter; For transactional, this will be replaced with your SenderId;
//            // For promotional, this will be ignored by the SMS gateway
//            'From' => '<your virtual number goes here>',
//            'To' => array('8983370270'),
//            'Body' => 'Test'
//        );
//
//        $exotel_sid = "agstech"; // Your Exotel SID - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
//        $exotel_token = "fba7e689fb8742f8e398f1ee1e58ebaf3c0c47ed"; // Your exotel token - Get it from here: http://my.exotel.in/Exotel/settings/site#api-settings
//
////        $url = "https://" . $exotel_sid . ":" . $exotel_token . "@twilix.exotel.in/v1/Accounts/" . $exotel_sid . "/Sms/send";
//        $url = "'https://api.smsbump.com/send/1B9mOYKhejDQ.json?type=whatsapp&to=918983370270&message=Happy birthday!'";
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_VERBOSE, 1);
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
//
//        $http_result = curl_exec($ch);
//        $error = curl_error($ch);
//        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//
//        curl_close($ch);
//
//        print "Response = " . print_r($http_result);
//        $callback = NULL;
//        $postData = array(
////            'from' => '917276393635',
//            'to' => '918983370270',
//            'message' => 'test',
//            'type' => 'whatsapp'
//        );
//        $APIKey ='1B9mOYKhejDQ';
//        $postString = http_build_query($postData);
//
//        $ch = curl_init('https://api.smsbump.com/send/1B9mOYKhejDQ.json?');
//        curl_setopt($ch, CURLOPT_HEADER, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, count($postData));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
//        
//        $result = curl_exec($ch);
//        var_dump($postString);
//        curl_close($ch);
//
//        if (is_callable($callback)) {
//            call_user_func($callback, json_decode($result, true));
//        }
//        var_dump($result);


        $postData = array(
//            'from' => '917276393635',
            'to' => '917276393635',
            'message' => 'test',
//            'type' => ''
        );

        $headers = array(
            'Content-Type: application/json',
        );
        $url = 'https://api.smsbump.com/send/1B9mOYKhejDQ.json?type=whatsapp&to=918983370270&message=Happy birthday!';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        echo "Response: " . $response;
        curl_close($ch);
    }

    public function whatsapp() {


        $CLIENT_ID = "FREE_TRIAL_ACCOUNT";
        $CLIENT_SECRET = "PUBLIC_SECRET";
        $postData = array(
            'number' => '918983370270', // Specify the recipient's number (NOT the gateway number) here.
            'message' => 'Have a nice day! Loving you:)'  // FIXME
        );
        $headers = array(
            'Content-Type: application/json',
            'X-WM-CLIENT-ID: ' . $CLIENT_ID,
            'X-WM-CLIENT-SECRET: ' . $CLIENT_SECRET
        );
        $url = 'http://api.whatsmate.net/v1/telegram/single/message/0';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        $response = curl_exec($ch);
        echo "Response: " . $response;
        curl_close($ch);







//$token = ""; // PHPHive WhatsAPI Token, Get it from http://wapi.phphive.info
//$wa_uid = ""; // WhatsApp Username
//$wa_pwd = ""; // WhatsApp Password
//$wa_recp = ""; // Recipient
//$wa_msg  = ""; // Message You want to Send
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL,"http://wapi.phphive.info/api/message/send.php");
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS,"token=".$token."&wa_uid=".$wa_uid."&wa_pwd=".$wa_pwd."&wa_recp=".$wa_recp."&wa_msg=".urlencode($wa_msg));
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$server_output = curl_exec ($ch);
//curl_close ($ch);
//echo $server_output;
//
//
//$token = ""; // PHPHive WhatsAPI Token, Get it from http://wapi.phphive.info
//$wa_uid = ""; // WhatsApp Username
//$wa_pwd = ""; // WhatsApp Password
//$wa_recp = ""; // Recipient
//$wa_msg  = ""; // Message You want to Send
// 
//$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL,"http://wapi.phphive.info/api/message/send.php");
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS,"token=".$token."&wa_uid=".$wa_uid."&wa_pwd=".$wa_pwd."&wa_recp=".$wa_recp."&wa_msg=".urlencode($wa_msg));
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$server_output = curl_exec ($ch);
//curl_close ($ch);
//echo $server_output;
    }

    public function exportExcel($type) {
        if ($type == 'state') {
            Excel::create('State', function($excel) {
                $excel->sheet('Sheet1', function($sheet) {
                    $sheet->with(
                            array('state_name')
                    );
                });
            })->export('xls');
        }
        if ($type == 'city') {
            Excel::create('City', function($excel) {
                $excel->sheet('Sheet 1', function($sheet) {
                    $sheet->with(
                            array('state_name',
                                'city_name'
                            )
                    );
                });
            })->export('xls');
        }
        if ($type == 'location') {
            Excel::create('Location', function($excel) {
                $excel->sheet('Sheet 1', function($sheet) {
                    $sheet->with(
                            array('state_name',
                                'city_name',
                                'area_name'
                            )
                    );
                });
            })->export('xls');
        }
        if ($type == 'product_category') {
            Excel::create('Product Category', function($excel) {
                $excel->sheet('Sheet 1', function($sheet) {
                    $sheet->with(
                            array('category_type',
                                'sub_product_category_name',
                                'price'
                            )
                    );
                });
            })->export('xls');
        }
        if ($type == 'product_size') {
            Excel::create('Product Size', function($excel) {
                $excel->sheet('Sheet 1', function($sheet) {
                    $sheet->with(
                            array('sub_product_category_name',
                                'alias_name',
                                'size',
                                'unit',
                                'weight',
                                'thickness',
                                'difference',
                            )
                    );
                });
            })->export('xls');
        }
        if ($type == 'user') {
            Excel::create('User', function($excel) {
                $excel->sheet('Sheet 1', function($sheet) {
                    $sheet->with(
                            array('first_name',
                                'last_name',
                                'phone_number',
                                'mobile_number',
                                'email',
                                'password',
                            )
                    );
                });
            })->export('xls');
        }
        if ($type == 'customer') {
            Excel::create('Customer', function($excel) {
                $excel->sheet('Sheet 1', function($sheet) {
                    $sheet->with(
                            array('owner_name',
                                'company_name',
                                'contact_person',
                                'address 1',
                                'address 2',
                                'state_name',
                                'city_name',
                                'zip',
                                'email',
                                'tally_name',
                                'phone_number 1',
                                'phone_number 2',
                                'excise_number',
                                'delivery_location',
                                'user_name',
                                'password',
                                'credit_period',
                                'relationship_manager',
                            )
                    );
                });
            })->export('xls');
        }

        exit();
    }

    public function excel_import() {

        return view('excel_import');
    }

    public function upload_excel() {

        if (Input::hasFile('excel_file')) {
            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
            $msg = "";
            Excel::load($filename, function($reader) {
                ini_set('max_execution_time', 720);

                $sheet = $reader->getSheet(0);
                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                for ($row = 1; $row <= 1; $row++) {
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                    $result_validation = $this->checkvalidation_size($rowData[0]);
                }

                if ($result_validation == "success") {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        ini_set('max_execution_time', 720);
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $result_save = $this->savesize($rowData);
                    }
                    $msg = "success";
                    Session::set('resultmsg', $msg);
                } else {
                    $msg = $result_validation;
                    Session::set('resultmsg', $msg);
                }
            });
            $msg = Session::get('resultmsg');
            if ($msg == "success") {
                Session::forget('resultmsg');
                return redirect('excel_import')->with('success', 'Customer details excel file successfully uploaded.');
            } else {
                Session::forget('resultmsg');
                return redirect('excel_import')->with('wrong', $msg);
            }
        } else {
            return redirect('excel_import')->with('wrong', 'Please select file to upload');
        }



//
//
//
//
//
//
//        ini_set('max_execution_time', 720);
//        if (Input::hasFile('excel_file')) {
//            $f = Input::file('excel_file');
//
//            $input = Input::file('excel_file');
//            $filename = $input->getRealPath();
////            var_dump($input);
//
//            Excel::load($filename, function($reader) {
//                $results = $reader->all();
//                foreach ($results as $excel) {
//
//                }
//            });
//            return redirect('excel_import')->with('success', 'Product excel file successfully uploaded.');
//        } else {
//            return redirect('excel_import')->with('wrong', 'Please select file to upload');
//        }
    }

    public function get_server_data() {
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
        echo '<pre>';
        print_r($ipaddress);
        echo '</pre>';
//        if (App::environment('development')) {
        var_dump(getenv('APP_ENV'));
        dd(\App::environment());
//        }else{
//            var_dump(getenv('APP_ENV'));
//        }
    }

    public function import_delivery_location() {
        return view('import_delivery_location');
    }

    public function process_import_delivery_location() {

//        $States = new States();
//        $States->state_name = 'Maharashtra';
//        $States->save();
//
//        $States_id = DB::getPdo()->lastInsertId();
//        $City = new City();
//        $City->state_id = $States_id;
//        $City->city_name = 'Pune';
//        $City->save();


        if (Input::hasFile('excel_file')) {
            $f = Input::file('excel_file');

            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
            var_dump($input);

            Excel::load($filename, function($reader) {

                $results = $reader->all();
                foreach ($results as $excel) {

                    $delivery = new DeliveryLocation();

                    $state_details = States::where('state_name', "Maharashtra")->pluck('id');
                    if (isset($state_details) && ($state_details != "")) {
                        $delivery->state_id = $state_details;
                    } else {
                        $States = new States();
                        $States->state_name = 'Maharashtra';
                        $States->save();
                        $delivery->state_id = DB::getPdo()->lastInsertId();
                    }
                    $city_details = City::where('city_name', $excel->city)->where('state_id', $delivery->state_id)->pluck('id');
                    if (isset($city_details) && ($city_details != "")) {
                        $delivery->city_id = $city_details;
                    } else {
                        $City = new City();
                        $City->state_id = $delivery->state_id;
                        $City->city_name = $excel->city;
                        $City->save();
                        $delivery->city_id = DB::getPdo()->lastInsertId();
                    }
                    $delivery->difference = $excel->freight;
                    $delivery->area_name = $excel->location;
                    $delivery->status = 'permanent';
                    $delivery->save();
                }
            });

            return redirect('import_delivery_location')->with('success', 'Delivery location excel file successfully uploaded.');
        } else {
            return redirect('import_delivery_location')->with('wrong', 'Please select file to upload');
        }
    }

    /*
     * Show export form for customer import
     */

    public function excel_import_customer() {

        if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        return view('excel_import_customer');
    }

    /*
     * Import customer list into database from excel file
     */

    public function upload_customer_excel() {

        if (Input::hasFile('excel_file')) {
            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
            $extension = $input->getClientOriginalExtension();
            $msg = "";
            if (in_array($extension, array('xlsx', 'xls'))) {


                Excel::load($filename, function($reader) {
                    ini_set('max_execution_time', 720);
                    $sheet = $reader->getSheet(0);
                    $highestColumn = $sheet->getHighestColumn();
                    $highestRow = $sheet->getHighestRow();

                    for ($row = 1; $row <= 1; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $result_validation = $this->checkvalidation($rowData[0]);
                    }
                    if ($result_validation == "success") {
                        for ($row = 2; $row <= $highestRow; $row++) {
                            ini_set('max_execution_time', 720);
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                            $result_save = $this->savecustomer($rowData);
                        }
                        $this->copy_customers();
                        $msg = "success";
                        Session::set('resultmsg', $msg);
                    } else {
                        $msg = $result_validation;
                        Session::set('resultmsg', $msg);
                    }
                });
                $msg = Session::get('resultmsg');
                if ($msg == "success") {
                    Session::forget('resultmsg');
                    return redirect('excel_import_customer')->with('success', 'Customer details excel file successfully uploaded.');
                } else {
                    Session::forget('resultmsg');
                    return redirect('excel_import_customer')->with('wrong', $msg);
                }
            } else {
                return redirect('excel_import_customer')->with('wrong', 'File format is invalid.');
            }
        } else {
            return redirect('excel_import_customer')->with('wrong', 'Please select file to upload');
        }
    }

    /**
     * Functionality: Check validation of excel file before importing customers
     */
    public function checkvalidation($rowData) {
        $error_list_invalid = array();
        $missing_colname = array();

        $org_col = array(
            0 => "owner_name",
            1 => "company_name",
            2 => "contact_person",
            3 => "address1",
            4 => "address2",
            5 => "state_name",
            6 => "city_name",
            7 => "zip",
            8 => "email",
            9 => "tally_name",
            10 => "phone_number_1",
            11 => "phone_number_2",
            12 => "excise_number",
            13 => "delivery_location",
            14 => "user_name",
            15 => "password",
            16 => "credit_period",
            17 => "relationship_manager"
        );
        for ($i = 0; $i < 18; $i++) {
            if ($org_col[$i] != $rowData[$i]) {
                return "Please arrange column name same as given file.";
            }
        }

        if (isset($rowData[0])) {
            if (trim($rowData[0] == "")) {
//                $error_list_invalid[] = "owner_name";
                return "Column name - owner_name is invalid please please check excel file.";
            }
        } else {
//            $missing_colname[] = "owner_name";
            return "Column name - owner_name is missing please check excel file.";
        }

        if (isset($rowData[8])) {
            if (trim($rowData[8] == "")) {
//                $error_list_invalid[] = "email";
                return "Column name - email is invalid please check excel file.";
            }
        } else {
//            $missing_colname[] = "email";
            return "Column name - email is missing please check excel file.";
        }

        if (isset($rowData[9])) {
            if (trim($rowData[9] == "")) {
//                $error_list_invalid[] = "tally_name";
                return "Column name - tally_name is invalid please check excel file.";
            }
        } else {
//            $missing_colname[] = "tally_name";
            return "Column name - tally_name is missing please check excel file.";
        }

        if (isset($rowData[10])) {
            if (trim($rowData[10] == "")) {
//                $error_list_invalid[] = "phone_number_1";
                return "Column name - phone_number_1 is invalid please please check excel file.";
            }
        } else {
//            $missing_colname[] = "phone_number_1";
            return "Column name - phone_number_1 is missing please check excel file.";
        }

        if (isset($rowData[5])) {
            if (trim($rowData[5] == "")) {
//                $error_list_invalid[] = "state_name";
                return "Column name - state_name is invalid please please check excel file.";
            }
        } else {
//            $missing_colname[] = "state_name";
            return "Column name - state_name is missing please please check excel file.";
        }
        if (isset($rowData[6])) {
            if (trim($rowData[6] == "")) {
//                $error_list_invalid[] = "city_name";
                return "Column name - city_name is invalid please please check excel file.";
            }
        } else {
//            $missing_colname[] = "city_name";
            return "Column name - city_name is missing please please check excel file.";
        }
        if (isset($rowData[13])) {
            if (trim($rowData[13] == "")) {
//                $error_list_invalid[] = "delivery_location";
                return "Column name - delivery_location is invalid please please check excel file.";
            }
        } else {
//            $missing_colname[] = "delivery_location";
            return "Column name - delivery_location is missing please please check excel file.";
        }

        if (isset($missing_colname) && count($missing_colname) > 0) {
            return "Some Column are missing please add column same as given file.";
        } else if (isset($error_list_invalid) && count($error_list_invalid) > 0) {
            return "Some Column are not as per given file. Please try again.";
        } else {
            return "success";
        }
    }

    /**
     *
     * Check validation size
     */
    public function checkvalidation_size($rowData) {
        $error_list_invalid = array();
        $missing_colname = array();

        $org_col = array(
            0 => "Type",
            1 => "Category",
            2 => "Size",
            3 => "Thickness",
            4 => "Weight",
            5 => "Alias",
            6 => "Standard Meter",
            7 => "Diff",
        );
        for ($i = 0; $i < 7; $i++) {
            if ($org_col[$i] != $rowData[$i]) {
                return "Please arrange column name same as given file.";
            }
        }
        return "success";
    }

    /**
     * Functionality: Save imported customer data into database
     */
    public function savecustomer($row) {

        foreach ($row as $rowData) {

            $check_customer = Customer::where('tally_name', $rowData[9])->where('phone_number1', $rowData[10])->first();
            if (isset($check_customer)) {
                $customer = $check_customer;
            } else {
                $customer = new Customer();
            }

            if (isset($rowData[0]) && trim($rowData[0]) != "") {
                $customer->owner_name = $rowData[0];
                if (isset($rowData[1])) {
                    $customer->company_name = $rowData[1];
                }
                if (isset($rowData[2])) {
                    $customer->contact_person = $rowData[2];
                }
                if (isset($rowData[3])) {
                    $customer->address1 = $rowData[3];
                }
                if (isset($rowData[4])) {
                    $customer->address2 = $rowData[4];
                }
                $customer->city = 1;
                $customer->state = 1;
                if (isset($rowData[7])) {
                    $customer->zip = $rowData[7];
                }
                if (isset($rowData[8])) {
                    $customer->email = $rowData[8];
                }
                if (isset($rowData[9])) {
                    $customer->tally_name = $rowData[9];
                }
                if (isset($rowData[10])) {
                    $customer->phone_number1 = $rowData[10];
                }
                if (isset($rowData[11])) {
                    $customer->phone_number2 = $rowData[11];
                }
                if (isset($rowData[12])) {
                    $customer->excise_number = $rowData[12];
                }
                $location = "";
                if (isset($rowData[13])) {
                    $location = DeliveryLocation::where('area_name', 'like', '%' . $rowData [13] . '%')->first();
                    $customer->delivery_location_id = $location->id;
                }
                if (isset($rowData[14])) {
                    $customer->username = $rowData[14];
                }
                if (isset($rowData[15])) {
                    $customer->password = Hash::make((string) $rowData[15]);
                }
                if (isset($rowData[16])) {
                    $customer->credit_period = $rowData[16];
                }
                $customer->customer_status = 'permanent';
                $customer->relationship_manager = 2;
                $customer->save();
            }
            return "success_data";
        }
    }

    /**
     *
     * save size into database
     */
    public function savesize($row) {

        foreach ($row as $excel) {

            if ($excel[0] == 'Pipe') {

                $exits_cat = ProductCategory::where('product_type_id', 1)->where('product_category_name', $excel[1])->first();

                if (sizeof($exits_cat) > 0) {
                    $exits_cat->id;

                    $exits_sub_cat = ProductSubCategory::where('product_category_id', $exits_cat->id)
                            ->where('alias_name', $excel[5])
                            ->where('size', $excel[2])
                            ->where('weight', $excel[4])
                            ->where('thickness', $excel[3])
                            ->where('standard_length', $excel[6])
//                            ->where('difference', $excel->diff)
                            ->first();

                    if (sizeof($exits_sub_cat) == 0) {

                        $product_sub = new ProductSubCategory();
                        $product_sub->product_category_id = $exits_cat->id;
                        $product_sub->alias_name = $excel[5];
                        $product_sub->size = $excel[2];
                        $product_sub->weight = $excel[4];
                        $product_sub->thickness = $excel[3];
                        $product_sub->standard_length = $excel[6];
                        $product_sub->difference = $excel[7];
                        $product_sub->unit_id = 1;
                        $product_sub->save();
                    }
                } else {


                    $product_cat = new ProductCategory();
                    $product_cat->product_type_id = 1;
                    $product_cat->product_category_name = $excel[1];
                    $product_cat->save();
                    $product_category_id = DB::getPdo()->lastInsertId();
                    $exits_sub_cat = ProductSubCategory::where('product_category_id', $product_category_id)
                            ->where('alias_name', $excel[5])
                            ->where('size', $excel[2])
                            ->where('weight', $excel[4])
                            ->where('thickness', $excel[3])
                            ->where('standard_length', $excel[6])
//                            ->where('difference', $excel->diff)
                            ->first();

                    if (sizeof($exits_sub_cat) == 0) {
                        $product_sub = new ProductSubCategory();
                        $product_sub->product_category_id = $product_category_id;
                        $product_sub->alias_name = $excel[5];
                        $product_sub->size = $excel[2];
                        $product_sub->weight = $excel[4];
                        $product_sub->thickness = $excel[3];
                        $product_sub->standard_length = $excel[6];
                        $product_sub->difference = $excel[7];
                        $product_sub->unit_id = 1;
                        $product_sub->save();
                    }
                }
            }

            if ($excel[0] == 'Structure') {
                $exits_cat = ProductCategory::where('product_type_id', 2)->where('product_category_name', $excel[1])->first();

                if (sizeof($exits_cat) > 0) {
                    $exits_cat->id;
                    $exits_sub_cat = ProductSubCategory::where('product_category_id', $exits_cat->id)
                            ->where('alias_name', $excel[5])
                            ->where('size', $excel[2])
                            ->where('weight', $excel[4])
                            ->where('thickness', $excel[3])
                            ->where('standard_length', $excel[6])
//                                    ->where('difference', $excel->diff)
                            ->first();

                    if (sizeof($exits_sub_cat) == 0) {

                        $product_sub = new ProductSubCategory();
                        $product_sub->product_category_id = $exits_cat->id;
                        $product_sub->alias_name = $excel[5];
                        $product_sub->size = $excel[2];
                        $product_sub->weight = $excel[4];
                        $product_sub->thickness = $excel[3];
                        $product_sub->standard_length = $excel[6];
                        $product_sub->difference = $excel[7];
                        $product_sub->unit_id = 1;
                        $product_sub->save();
                    }
                } else {


                    $product_cat = new ProductCategory();
                    $product_cat->product_type_id = 2;
                    $product_cat->product_category_name = $excel[1];
                    $product_cat->save();
                    $product_category_id = DB::getPdo()->lastInsertId();

                    $exits_sub_cat = ProductSubCategory::where('product_category_id', $product_category_id)
                            ->where('alias_name', $excel[5])
                            ->where('size', $excel[2])
                            ->where('weight', $excel[4])
                            ->where('thickness', $excel[3])
                            ->where('standard_length', $excel[6])
//                                    ->where('difference', $excel->diff)
                            ->first();

                    if (sizeof($exits_sub_cat) == 0) {

                        $product_sub = new ProductSubCategory();
                        $product_sub->product_category_id = $product_category_id;
                        $product_sub->alias_name = $excel[5];
                        $product_sub->size = $excel[2];
                        $product_sub->weight = $excel[4];
                        $product_sub->thickness = $excel[3];
                        $product_sub->standard_length = $excel[6];
                        $product_sub->difference = $excel[7];
                        $product_sub->unit_id = 1;
                        $product_sub->save();
                    }
                }
            }
        }
        return "success_data";
    }

    /**
     * Functionality: Export exisitng customer list into excel file
     */
    public function excel_export_customer() {

        $allcustomers = Customer::where('customer_status', 'permanent')->with('states', 'getcity', 'deliverylocation', 'manager')->get();
        Excel::create('Customer List', function($excel) use($allcustomers) {
            $excel->sheet('Customers List', function($sheet) use($allcustomers) {
                $sheet->loadView('excelView.customer', array('allcustomers' => $allcustomers));
            });
        })->export('xls');
    }

    public function excel_export_labours() {

        $alllabours = \App\Labour::get();
        Excel::create('Labours List', function($excel) use($alllabours) {
            $excel->sheet('Labours List', function($sheet) use($alllabours) {
                $sheet->loadView('excelView.labours', array('alllabours' => $alllabours));
            });
        })->export('xls');
    }

    /**
     * This function displays all the php configuration info
     */
    public function phpversion() {
        print(phpinfo());
    }

    /**
     * This function takes table name as argument and displays all the data of that table
     */
    public function showdata($table_name) {
        $pdo = DB::table($table_name)->get();
        print('<pre>');
        print_r($pdo);
    }

    /**
     * This function takes tablename as argument and deletes that table
     */
    public function removedata($table_name) {
        $pdo = DB::table($table_name)->delete();
        print('<pre>');
        print_r($pdo);
    }

    /**
     * This function takes tablename as argument and truncates that table data
     */
    public function emptydata($table_name) {
        $pdo = DB::table($table_name)->truncate();
        echo $table_name . ' - Table truncated successfully';
    }

    /**
     * This function takes tablename, columnname, and column values as argument and updates the data accordingly
     */
    public function updatecolumndata($table_name, $column, $cvalue) {
        DB::table($table_name)->update(array($column => $cvalue));
        echo "Table name - " . $table_name . " Values are update for column name - " . $column . " to new value - " . $cvalue;
    }

    public function updatecolumndatavalue($table_name, $column, $cvalue, $wherekey, $wherevalue) {
        DB::table($table_name)->where($wherekey, $wherevalue)->update(array($column => $cvalue));
    }

    /**
     * This function takes tablename as argument and displays all the column along with data type of that column
     */
    public function showtableinfo($tablename) {
        $column_details = Schema::getColumnListing($tablename);
        $total_info = array();
        $total_info['tablename'] = $tablename;
        foreach ($column_details as $column) {
            $col_type = DB::connection()->getDoctrineColumn($tablename, $column)->getType()->getName();
            $total_info[$tablename][$column] = $col_type;
        }
        echo '<br>======================<br>';
        echo "Table name : <strong>";
        print_r($total_info['tablename']);
        echo "</strong>";
        echo '<br>======================<br>';
        echo '<pre>';
        print_r($total_info);
        echo '</pre>';
    }

    /**
     * This function takes tablename as argument and displays all the column along with data type of that column including enum
     */
    public function showtableinformation($tablename) {
        $column_details = Schema::getColumnListing($tablename);
        $total_info = array();
        $total_info['tablename'] = $tablename;
        $columns = DB::select('show columns from ' . $tablename);
        echo '<br>======================<br>';
        echo "Table name : <strong>";
        print_r($total_info['tablename']);
        echo "</strong>";
        echo '<br>======================<br>';
        foreach ($columns as $value) {

            echo '<pre>';
            echo "'" . $value->Field . "' => '" . $value->Type . "|" . ( $value->Null == "NO" ? 'required' : '' ) . "', <br/>";
            echo '</pre>';
        }
    }

    /* Created by Amit Gupta to resolve the order error issue on 05-10-2015 */

    public function delete_order_data($tablename = NULL, $columnname = NULL, $value = NULL) {

        //DB::table('all_order_products')->where('product_category_id', '0')->delete();//commented on 12-10-2015
        //DB::table('all_order_products')->where('id', '2377')->delete(); //Added on delivery prder empty product 12-10-2015
        //        DB::table('all_order_products')->where('id', '2377')->delete(); //Added on delivery order delete product empty product 14-10-2015
        //        DB::table('all_order_products')->where('id', '2387')->delete(); //Added on delivery order delete product empty product 14-10-2015
        //        DB::table('all_order_products')->where('id', '1831')->delete(); //Added on order delete product empty product 20-05-2016

        DB::table($tablename)->where($columnname, $value)->delete(); //Added on for delivery order delete id - 6077 empty product 28-10-2015

        echo "Order deleted from table name " . $tablename . " for column " . $columnname . " having value of " . $value;
    }

    /* To deleted test entries from DB, No soft delete */

    public function delete_test_data($tablename = NULL, $columnname = NULL, $value = NULL) {
        $temp = DB::table($tablename)->where($columnname, 'like', '%' . $value . '%')
                ->where('deleted_at', '<>', '')
                ->delete();

        echo "Records deleted from table name " . $tablename . " for column " . $columnname . " having value of " . $value . '--' . $temp;
    }

    public function update_user_role() {
        DB::table('user_roles')->where('role_id', '1')->update(array('role_id' => 0));
        DB::table('users')->where('role_id', '1')->update(array('role_id' => 0));
    }

    public function checkdatabaseinfo() {
        $db_hostname = Config::get("database.connections.mysql.host");
        $db_username = Config::get("database.connections.mysql.username");
        $db_password = Config::get("database.connections.mysql.password");
        $database = Config::get("database.connections.mysql.database");
        $timezone = Config::get("app.timezone");


        echo '<pre>';
        print_r("Database Host Name : " . $db_hostname);
        echo '</pre>';
        echo '<pre>';
        print_r("Database User Name : " . $db_username);
        echo '</pre>';
        echo '<pre>';
        print_r("Database Password : " . $db_password);
        echo '</pre>';
        echo '<pre>';
        print_r("Database Name : " . $database);
        echo '</pre>';
        echo '<pre>';
        var_dump($timezone);
        echo '</pre>';
        exit();
    }

    public function delete_reports() {
        DB::table('inquiry')->truncate();
        DB::table('inquiry_products')->truncate();
        DB::table('orders')->truncate();
        DB::table('all_order_products')->truncate();
        DB::table('order_cancelled')->truncate();
        DB::table('delivery_order')->truncate();
        DB::table('delivery_challan')->truncate();
        DB::table('purchase_order')->truncate();
        DB::table('all_purchase_products')->truncate();
        DB::table('purchase_order_canceled')->truncate();
        DB::table('purchase_advice')->truncate();
        DB::table('purchase_challan')->truncate();
        echo 'truncate all data';
    }

    /*
      function used to copy customer data inot users' table
     *      */

    public function copy_customers() {
        $cust_count = DB::table('customers')->where('customer_status', '=', 'permanent')->count();
        $cust = DB::table('customers')->select('id', 'owner_name', 'phone_number1', 'phone_number2', 'email', 'password', 'created_at')->where('customer_status', '=', 'permanent')->get();
        $user_count = DB::table('users')->count();
        $user = DB::table('users')->get();

        $flag = 0;

        for ($i = 0; $i < $cust_count; $i ++) {

            if ($user = DB::table('users')->where('email', '=', $cust[$i]->email)
                    ->where('mobile_number', '=', $cust[$i]->phone_number1)
                    ->where('password', '=', $cust[$i]->password)
                    ->get()) {
                /* Do Nothing */
            } else {
                DB::table('users')->insert(['email' => $cust[$i]->email,
                    'first_name' => $cust[$i]->owner_name,
                    'mobile_number' => $cust[$i]->phone_number1,
                    'phone_number' => $cust[$i]->phone_number2,
                    'password' => $cust[$i]->password,
                    'created_at' => $cust[$i]->created_at,
                    'role_id' => '5',
                ]);
                $flag ++;
            }
        }

        echo $flag . " Records copied to users' table.";
    }

    /*
      function used to delete customer data from users' table
     *      */

    public function delete_cust_from_user() {
        $user_count = DB::table('users')->where('role_id', '=', '5')->count();
        $user = DB::table('users')->where('role_id', '=', '5')->delete();
        print_r($user_count . " records deleted.");
    }

    public function database_backup_local() {

        $db_username = "root";
        $db_password = "root123";
        $database = "erp";
        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = "mysqldump -u $db_username --password=$db_password $database | gzip --best";

        passthru($cmd);

        exit(0);
    }

    public function database_backup_test() {

        $db_username = "vikasags_vikuser";
        $db_password = "CFpNH.#JblZe";
        $database = "vikasags_vikasdb";
        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = "mysqldump -u $db_username --password=$db_password $database | gzip --best";

        passthru($cmd);

        exit(0);
    }

    public function database_backup_live() {

        $db_username = "vikaserp_agsus";
        $db_password = "passags756";
        $database = "vikaserp_ags";
        $filename = "backup-" . date("d-m-Y") . ".sql.gz";
        $mime = "application/x-gzip";

        header("Content-Type: " . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $cmd = "mysqldump -u $db_username --password=$db_password $database | gzip --best";

        passthru($cmd);

        exit(0);
    }

    public function make_approved() {

        $order = \App\Order::
                where('order_status', 'completed')
                ->update(['is_approved' => 'yes']);

        $order = \App\Order::with(['createdby'])
                ->where('order_status', 'pending')
                ->whereHas('createdby', function($query) {
                    $query->where('role_id', '=', '0');
                })
                ->update(['is_approved' => 'yes']);


        $inquiry = \App\Inquiry::
                where('inquiry_status', 'completed')
                ->update(['is_approved' => 'yes']);

        $inquiry = \App\Inquiry::with(['createdby'])
                ->where('inquiry_status', 'pending')
                ->whereHas('createdby', function($query) {
                    $query->where('role_id', '=', '0');
                })
                ->update(['is_approved' => 'yes']);

        echo "<pre>";
        print_r($order);
        echo "</pre>";
        exit;
    }

//    public function get_set_labours() {
////        
////        $labours = \App\DeliveryChallan::select('loaded_by')
////        ->withTrashed()
////                ->groupBy('loaded_by')
////                ->get();
//        
//        $labours = \App\PurchaseChallan::select('unloaded_by')
//                ->withTrashed()
//                ->groupBy('unloaded_by')
//                ->get();
//
//
//        echo "<pre>";
//        print_r($labours->toArray());
//        echo "</pre>";
//        exit;
//    }



    public function getMyIP() {

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

        echo "<pre>";
        print_r($ipaddress);
        echo "</pre>";
        exit;
    }

}
