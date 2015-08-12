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
use App\City;
use App\DeliveryLocation;
use App\Customer;
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

        if (Input::hasFile('excel_file')) {
            $f = Input::file('excel_file');

            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
            var_dump($input);

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
            $f = Input::file('excel_file');

            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
//            var_dump($input);

            Excel::load($filename, function($reader) {
                $results = $reader->all();
                foreach ($results as $excel) {
                    foreach ($excel as $excel1) {
                        $customer = new Customer();
                        if (isset($excel1->owner_name))
                            $customer->owner_name = $excel1->owner_name;
                        if (isset($excel1->contact_person))
                            $customer->contact_person = $excel1->contact_person;
                        if (isset($excel1->company_name))
                            $customer->company_name = $excel1->company_name;
                        if (isset($excel1->address1)) {
                            $customer->address1 = $excel->address1;
                            $customer->address2 = $excel->address1;
                        }
                        $customer->city = 1;
                        $customer->state = 1;
                        if (isset($excel1->zip))
                            $customer->zip = $excel1->zip;
                        if (isset($excel1->email))
                            $customer->email = $excel1->email;
                        if (isset($excel1->tally_name))
                            $customer->tally_name = $excel1->tally_name;
                        if (isset($excel1->phone_number_1))
                            $customer->phone_number1 = $excel1->phone_number_1;
                        if (isset($excel1->phone_number_2))
                            $customer->phone_number2 = $excel1->phone_number_2;
                        if (isset($excel1->excise_number))
                            $customer->excise_number = $excel1->excise_number;
                        $location = "";
                        if (isset($excel1->delivery_location)) {
                            $location = DeliveryLocation::where('area_name', 'like', '%' . $excel1->delivery_location . '%')->first();
                            $customer->delivery_location_id = $location->id;
                        }
                        if (isset($excel1->user_name))
                            $customer->username = $excel1->user_name;
                        if (isset($excel1->password))
                            $customer->password = Hash::make($excel1->password);
                        if (isset($excel1->credit_period))
                            $customer->credit_period = $excel1->credit_period;
                        $customer->customer_status = 'permanent';
                        $customer->relationship_manager = 2;
                        if (isset($customer->owner_name) && $customer->owner_name != "") {
                            $customer->save();
                        }
                    }
                }
            });
            return redirect('excel_import_customer')->with('success', 'Customer details excel file successfully uploaded.');
        } else {
            return redirect('excel_import_customer')->with('wrong', 'Please select file to upload');
        }
    }

}
