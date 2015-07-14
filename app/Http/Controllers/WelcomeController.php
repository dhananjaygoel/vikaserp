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
        if (getenv('HTTP_CLIENT_IP')) {
            echo '1';
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            echo '2';
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            echo '3';
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            echo '4';
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            echo '5';
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            echo '6';
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            echo '7';
            $ipaddress = 'UNKNOWN';
        }
        echo '<pre>';
        print_r($ipaddress);
        echo '</pre>';
    }

}
