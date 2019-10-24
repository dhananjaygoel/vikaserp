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
use App\Thickness;
use App\States;
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
    
     public function storethickness(){
      if (Input::hasFile('excel_file')) {
          $input = Input::file('excel_file');
          $filename = $input->getRealPath();
          Excel::load($filename, function($reader) {
            ini_set('max_execution_time', 720);
                $results = $reader->all();
                foreach ($results as $excel) {
                    // echo '<pre>';
                    // print_r($excel);
                    $thickness = new Thickness();
                    $thickness_result = Thickness::where('thickness', $excel->thickness)->pluck('id');
                    // print_r($thickness_result);
                    if(!$thickness_result>0){
                        $thickness->thickness = $excel->thickness;
                        $thickness->diffrence = $excel->difference;
                        $thickness->save();
                    }
                }
            });
            return redirect('process')->with('success', 'Excel rows for Thickness successfully inserted.');
      } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }

     public function storestate(){
      if (Input::hasFile('excel_file')) {
          $input = Input::file('excel_file');
          $filename = $input->getRealPath();
          Excel::load($filename, function($reader) {
            ini_set('max_execution_time', 720);
                $results = $reader->all();
                DB::table('state')->truncate();
                foreach ($results as $excel) {
                    $state = new States();
                    $state_result = States::where('state_name', $excel->state_name)->pluck('id');
                    // print_r($thickness_result);
                    if(!$state_result>0){
                        $state->state_name = $excel->state_name;
                        $state->local_state = $excel->local_state;
                        $state->save();
                    }
                }
            });
            return redirect('process')->with('success', 'Excel rows for States successfully inserted.');
      } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }
     
      function update_hsn(){
        if (Input::hasFile('excel_file')) {
            $input = Input::file('excel_file');
            $filename = $input->getRealPath();
            Excel::load($filename, function($reader) {
                  $excel = $reader->all();
                //   $t = $excel->only(['category_id'])->unique();
                //   echo '<pre>';
                //   print_r($t);
                //   $excel1 = $excel->distinct('category_name');
                $array=array();
                
                  foreach ($excel as $hsn_result) {
                    $array=[$hsn_result->category_id,$hsn_result->hsn_code];
                    $hsn_id = array_unique($array);
                  echo '<pre>';
                    print_r(isset($hsn_id->category_id)?$hsn_id->category_id:'');
                    //   $array2[]=$hsn_result->hsn_code;
// $array[]=array_combine($array1,$array2);
                    //   $t = $hsn_result->only(['category_id'])->unique();
                    //   echo '<pre>';
                    // print_r($t);
                    // // dd((int)$hsn_result->category_id);
                    // $product_category_id = (int)$hsn_result->category_id;
                    // $t1 = $hsn_result->array_unique($product_category_id);
                    // print($product_category_id);
                    // App\ProductSubCategory::where('product_category_id',(int)$hsn_result->category_id)->update(['hsn_code',(int)$hsn_result->hsn_code]);
                      
                  }
                //   $hsn_id = array_unique($array);
                //   echo '<pre>';
                //     print_r($hsn_id);
                foreach ($hsn_id as $cat_id){
                    // dd($cat_id);
                    // App\ProductSubCategory::where('product_category_id',(int)$cat_id)->update(['hsn_code',(int)$hsn_result->hsn_code]);
                }
            });
            
              exit;
            // $results = App\ProductSubCategory::select('product_category_id','hsn_code')->orderBy('product_category_id', 'asc')->get();
            // foreach ($results as  $result) {
            //     App\ProductCategory::where('id',$result->product_category_id)->update(['hsn_code'=>$result->hsn_code ]);
            // }

            // $hsnresults = App\Hsn::orderBy('hsn_code', 'asc')->get();
            // foreach ($hsnresults as $hsnresult) {
            //     App\ProductCategory::where('hsn_code',$hsnresult->hsn_code)->update(['gst'=>$hsnresult->gst,'hsn_desc' =>$hsnresult->hsn_desc ]);
            // }
            
            return redirect('process')->with('success', 'HSN successfully updated.');
        }
        else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
    }
    
    function update_hsn_test() {
        
            App\ProductCategory::where('product_type_id',1)->update(['hsn_code' => '7210']);

            App\ProductCategory::where('product_type_id',2)->update(['hsn_code' => '7310']);
        
            App\ProductCategory::where('product_type_id',3)->update(['hsn_code' => '7410']);

            $hsnresults = App\Hsn::orderBy('hsn_code', 'asc')->get();
            foreach ($hsnresults as $hsnresult) {
                App\ProductCategory::where('hsn_code',$hsnresult->hsn_code)->update(['gst'=>$hsnresult->gst,'hsn_desc' =>$hsnresult->hsn_desc ]);
            }

            $prod_cat = App\ProductCategory::select('id','hsn_code')->distinct()->get();
            // dd($prod_cat);
            foreach($prod_cat as $cat){
                App\ProductSubCategory::where('product_category_id',$cat->id)->update(['hsn_code' => $cat->hsn_code]);
            }
            return redirect('process')->with('success', 'HSN successfully updated.');

     }

     function updatethickness(){
           if (Input::hasFile('excel_file')) {
          $input = Input::file('excel_file');
          $filename = $input->getRealPath();
          Excel::load($filename, function($reader) {
            ini_set('max_execution_time', 720);
                $results = $reader->all();
                foreach ($results as $excel) {
                  ProductSubCategory::where('alias_name',$excel->alias_name)->update([
                        'thickness'=>$excel->thickness,
                        'difference'=>$excel->difference ]); 
                }
            });
            return redirect('process')->with('success', 'Thickness and Difference successfully update.');
      } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }
   


}
