<?php

namespace App\Http\Controllers;

use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;
use App\States;
use App\ProductCategory;
use App\ProductSubCategory;
use App\ProductType;
use App\Http\Requests\ProductCategoryRequest;
use App\Http\Requests\UserValidation;
use Input;
use DB;
use App;
use Redirect;
use App\City;
use App\DeliveryLocation;
use App\Customer;
use Session;
use Illuminate\Support\Facades\Hash;

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
     * @return void
     */
    public function __construct() {
//		$this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index() {
        return view('welcome');
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
        ini_set('max_execution_time', 480);
        ini_set('memory_limit', '768M');
        if (Input::hasFile('excel_file')) {
            $f = Input::file('excel_file');

            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
//            var_dump($input);

            Excel::load($filename, function($reader) {
                $results = $reader->all();
                foreach ($results as $excel) {

                    if ($excel->type == 'Pipe') {
                        $exits_cat = ProductCategory::where('product_type_id', 1)
                                        ->where('product_category_name', $excel->category)->first();

                        if (sizeof($exits_cat) > 0) {
                            $exits_cat->id;

                            $exits_sub_cat = ProductSubCategory::where('product_category_id', $exits_cat->id)
                                    ->where('alias_name', $excel->alias)
                                    ->where('size', $excel->size)
                                    ->where('weight', $excel->weight)
                                    ->where('thickness', $excel->thickness)
                                    ->where('standard_length', $excel->meter)
                                    ->where('difference', $excel->diff)
                                    ->first();

                            if (sizeof($exits_sub_cat) == 0) {

                                $product_sub = new ProductSubCategory();
                                $product_sub->product_category_id = $exits_cat->id;
                                $product_sub->alias_name = $excel->alias;
                                $product_sub->size = $excel->size;
                                $product_sub->weight = $excel->weight;
                                $product_sub->thickness = $excel->thickness;
                                $product_sub->standard_length = $excel->meter;
                                $product_sub->difference = $excel->diff;
                                $product_sub->save();
                            }
                        } else {


                            $product_cat = new ProductCategory();
                            $product_cat->product_type_id = 1;
                            $product_cat->product_category_name = $excel->category;
                            $product_cat->save();

                            $product_category_id = DB::getPdo()->lastInsertId();



                            $exits_sub_cat = ProductSubCategory::where('product_category_id', $product_category_id)
                                    ->where('alias_name', $excel->alias)
                                    ->where('size', $excel->size)
                                    ->where('weight', $excel->weight)
                                    ->where('thickness', $excel->thickness)
                                    ->where('standard_length', $excel->meter)
                                    ->where('difference', $excel->diff)
                                    ->first();

                            if (sizeof($exits_sub_cat) == 0) {

                                $product_sub = new ProductSubCategory();
                                $product_sub->product_category_id = $product_category_id;
                                $product_sub->alias_name = $excel->alias;
                                $product_sub->size = $excel->size;
                                $product_sub->weight = $excel->weight;
                                $product_sub->thickness = $excel->thickness;
                                $product_sub->standard_length = $excel->meter;
                                $product_sub->difference = $excel->diff;
                                $product_sub->save();
                            }
                        }
                    }

                    if ($excel->type == 'Structure') {
                        $exits_cat = ProductCategory::where('product_type_id', 2)
                                        ->where('product_category_name', $excel->category)->first();

                        if (sizeof($exits_cat) > 0) {

                            $exits_cat->id;


                            $exits_sub_cat = ProductSubCategory::where('product_category_id', $exits_cat->id)
                                    ->where('alias_name', $excel->alias)
                                    ->where('size', $excel->size)
                                    ->where('weight', $excel->weight)
                                    ->where('thickness', $excel->thickness)
                                    ->where('standard_length', $excel->meter)
//                                    ->where('difference', $excel->diff)
                                    ->first();

                            if (sizeof($exits_sub_cat) == 0) {

                                $product_sub = new ProductSubCategory();
                                $product_sub->product_category_id = $exits_cat->id;
                                $product_sub->alias_name = $excel->alias;
                                $product_sub->size = $excel->size;
                                $product_sub->weight = $excel->weight;
                                $product_sub->thickness = $excel->thickness;
                                $product_sub->standard_length = $excel->meter;
//                                $product_sub->difference = $excel->diff;
                                $product_sub->save();
                            }
                        } else {


                            $product_cat = new ProductCategory();
                            $product_cat->product_type_id = 2;
                            $product_cat->product_category_name = $excel->category;
                            $product_cat->save();

                            $product_category_id = DB::getPdo()->lastInsertId();



                            $exits_sub_cat = ProductSubCategory::where('product_category_id', $product_category_id)
                                    ->where('alias_name', $excel->alias)
                                    ->where('size', $excel->size)
                                    ->where('weight', $excel->weight)
                                    ->where('thickness', $excel->thickness)
                                    ->where('standard_length', $excel->meter)
//                                    ->where('difference', $excel->diff)
                                    ->first();

                            if (sizeof($exits_sub_cat) == 0) {

                                $product_sub = new ProductSubCategory();
                                $product_sub->product_category_id = $product_category_id;
                                $product_sub->alias_name = $excel->alias;
                                $product_sub->size = $excel->size;
                                $product_sub->weight = $excel->weight;
                                $product_sub->thickness = $excel->thickness;
                                $product_sub->standard_length = $excel->meter;
//                                $product_sub->difference = $excel->diff;
                                $product_sub->save();
                            }
                        }
                    }
                }
            });
            return redirect('excel_import')->with('success', 'Product excel file successfully uploaded.');
        } else {
            return redirect('excel_import')->with('wrong', 'Please select file to upload');
        }
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

        $States = new States();
        $States->state_name = 'Maharashtra';
        $States->save();

        $States_id = DB::getPdo()->lastInsertId();
        $City = new City();
        $City->state_id = $States_id;
        $City->city_name = 'Pune';
        $City->save();

        if (Input::hasFile('excel_file')) {
            $f = Input::file('excel_file');

            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
            var_dump($input);

            Excel::load($filename, function($reader) {

                $results = $reader->all();
                foreach ($results as $excel) {
                    $delivery = new DeliveryLocation();
                    $delivery->state_id = 1;
                    $delivery->city_id = 1;
                    $delivery->difference = $excel->diff;
                    $delivery->area_name = $excel->area_name;
                    $delivery->status = 'permanent';
                    $delivery->save();
                }
            });

            return redirect('import_delivery_location')->with('success', 'Delivery location excel file successfully uploaded.');
        } else {
            return redirect('import_delivery_location')->with('wrong', 'Please select file to upload');
        }
    }

    public function excel_import_customer() {
        return view('excel_import_customer');
    }

    public function upload_customer_excel() {

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
                    $result_validation = $this->checkvalidation($rowData[0]);
                }
                if ($result_validation == "success") {
                    for ($row = 2; $row <= $highestRow; $row++) {
                        ini_set('max_execution_time', 720);
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                        $result_save = $this->savecustomer($rowData);
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
                return redirect('excel_import_customer')->with('success', 'Customer details excel file successfully uploaded.');
            } else {
                Session::forget('resultmsg');
                return redirect('excel_import_customer')->with('wrong', $msg);
            }
        } else {
            return redirect('excel_import_customer')->with('wrong', 'Please select file to upload');
        }
    }

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

    public function excel_export_customer() {
        $allcustomers = Customer::where('relationship_manager', '=', 2)->where('customer_status', 'permanent')->with('states', 'getcity', 'deliverylocation', 'manager')->get();
        Excel::create('Customer List', function($excel) use($allcustomers) {
            $excel->sheet('Customers List', function($sheet) use($allcustomers) {
                $sheet->loadView('excelView.customer', array('allcustomers' => $allcustomers));
            });
        })->export('xls');
    }

    public function phpversion() {
        print(phpinfo());
    }

    public function showdata($table_name) {
        $pdo = DB::table($table_name)->get();
        print('<pre>');
        print_r($pdo);
    }

    public function removedata($table_name) {
        $pdo = DB::table($table_name)->delete();
        print('<pre>');
        print_r($pdo);
    }

}
