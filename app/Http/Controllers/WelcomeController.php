<?php

namespace App\Http\Controllers;

use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;
use App\States;

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
                                'city',
                                'state',
                                'zip',
                                'email',
                                'tally_name',
                                'tally_category',
                                'tally_sub_category',
                                'phone_number 1',
                                'phone_number 2',
                                'vat_tin_number',
                                'excise_number',
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

}
