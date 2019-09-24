<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
Use Cache;
use Illuminate\Http\Request;
use App\Customer;
use Input;
use App\Units;
use App\DeliveryLocation;
use App\Hsn;
use App\Gst;
use App\InquiryProducts;
use DB;
use Config;
use Auth;
use Mail;
use View;
use App;
use App\Http\Requests\InquiryRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Hash;
use App\ProductSubCategory;
use App\Order;
use App\AllOrderProducts;
use DateTime;
use App\CustomerProductDifference;
use Session;
use Illuminate\Support\Facades\Event;
use Memcached;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Vendor\Phpoffice\Phpexcel\Classes;

/**
 * Description of DBController
 *
 * @author Sammy Guergachi <sguergachi at gmail.com>
 */
class DBController extends Controller {
     public function index(InquiryRequest $request) {
          return view('process_selection_view');
     }
     
     public function store(){
      if (Input::hasFile('excel_file')) {
          $input = Input::file('excel_file');
          $filename = $input->getRealPath();
          Excel::load($filename, function($reader) {
                $results = $reader->all();
                foreach ($results as $excel) {
                    $hsn = new Hsn();
                    $hsn_result = Hsn::where('hsn_code', $excel->hsn_code)->pluck('id');
                    if(!$hsn_result>0){
                        $hsn->hsn_code = $excel->hsn_code;
                        $hsn->gst = $excel->gst;
                        $hsn->hsn_desc = $excel->hsn_desc;
                        $hsn->save();
                    }
                }
            });
            return redirect('process')->with('success', 'HSN excel file successfully uploaded.');
      } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }
     public function storeGST(){
      if (Input::hasFile('excel_file')) {
          $input = Input::file('excel_file');
          $filename = $input->getRealPath();
          Excel::load($filename, function($reader) {
                $results = $reader->all();
                foreach ($results as $excel) {
                    $gst = new Gst();
                    if(Gst::where('gst',$excel->gst)->where('igst',$excel->igst)->where('deleted_at',NULL)->count() == 0){
                    $gst->gst = $excel->gst;
                    $gst->sgst = $excel->sgst;
                    $gst->cgst = $excel->cgst;
                    $gst->igst = $excel->igst;
                    $gst->save();
                    }
                }
            });
            return redirect('process')->with('success', 'GST excel file successfully uploaded.');
      } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }
    //put your code here
}
