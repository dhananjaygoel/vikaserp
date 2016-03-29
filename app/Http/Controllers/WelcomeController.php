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
use App\AllOrderProducts;
use Session;
use Schema;
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
     * Code added by Amit Gupta
     * Show export form for customer import
     *
     */

    public function excel_import_customer() {
	return view('excel_import_customer');
    }

    /*
     * Code added by Amit Gupta
     * Import customer list into database from excel file
     *
     */

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

    /**
     * Code added by Amit Gupta
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
     * Code added by Amit Gupta
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
     * Code added by Amit Gupta
     * Functionality: Export exisitng customer list into excel file
     */
    public function excel_export_customer() {
	$allcustomers = Customer::where('relationship_manager', '=', 2)->where('customer_status', 'permanent')->with('states', 'getcity', 'deliverylocation', 'manager')->get();
	Excel::create('Customer List', function($excel) use($allcustomers) {
	    $excel->sheet('Customers List', function($sheet) use($allcustomers) {
		$sheet->loadView('excelView.customer', array('allcustomers' => $allcustomers));
	    });
	})->export('xls');
    }

    /**
     * Written by : AMit GupTA
     * This function displays all the php configuration info
     */
    public function phpversion() {
	print(phpinfo());
    }

    /**
     * Written by : AMit GupTA
     * This function takes table name as argument and displays all the data of that table
     */
    public function showdata($table_name) {
//        $pdo = DB::table($table_name)->get();
	$pdo = DB::table($table_name)->get();
	print('<pre>');
	print_r($pdo);
    }

    /**
     * Written by : AMit GupTA
     * This function takes tablename as argument and deletes that table
     */
    public function removedata($table_name) {
	$pdo = DB::table($table_name)->delete();
	print('<pre>');
	print_r($pdo);
    }

    /**
     * Written by : AMit GupTA
     * This function takes tablename as argument and truncates that table data
     */
    public function emptydata($table_name) {
	$pdo = DB::table($table_name)->truncate();
	echo $table_name . ' - Table truncated successfully';
    }

    /**
     * Written by : AMit GupTA
     * This function takes tablename, columnname, and column values as argument and updates the data accordingly
     */
    public function updatecolumndata($table_name, $column, $cvalue) {

	$customer_list = Customer::all();
	foreach ($customer_list as $eachcustomer) {
	    $eachcustomer->$column = $cvalue;
	    $eachcustomer->save();
	}
	echo "Table name - " . $table_name . " Values are update for column name - " . $column . " to new value - " . $cvalue;
    }

    /**
     * Written by : AMit GupTA
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

    /* Created by Amit Gupta to resolve the order error issue on 05-10-2015 */

    public function delete_order_data($tablename = NULL, $columnname = NULL, $value = NULL) {

	//DB::table('all_order_products')->where('product_category_id', '0')->delete();//commented on 12-10-2015
	//DB::table('all_order_products')->where('id', '2377')->delete(); //Added on for delivery prder empty product 12-10-2015
//        DB::table('all_order_products')->where('id', '2377')->delete(); //Added on for delivery order delete product empty product 14-10-2015
//        DB::table('all_order_products')->where('id', '2387')->delete(); //Added on for delivery order delete product empty product 14-10-2015

	DB::table($tablename)->where($columnname, $value)->delete(); //Added on for delivery order delete id - 6077 empty product 28-10-2015

	echo "Order deleted from table name " . $tablename . " for column " . $columnname . " having value of " . $value;
    }

    public function update_user_role() {
	DB::table('user_roles')->where('role_id', '1')->update(array('role_id' => 0));
	DB::table('users')->where('role_id', '1')->update(array('role_id' => 0));
    }

    public function delete_reports() {
	DB::table('inquiry')->truncate();
	DB::table('orders')->truncate();
	DB::table('delivery_order')->truncate();
	DB::table('delivery_challan')->truncate();
	DB::table('delivery_order')->truncate();
	DB::table('purchase_order')->truncate();
	DB::table('purchase_advice')->truncate();
	DB::table('purchase_challan')->truncate();
	DB::table('purchase_order_canceled')->truncate();
	DB::table('order_cancelled')->truncate();


	echo 'truncate all data';
    }

}
