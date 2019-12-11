<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Imports\HsnImport;
use App\Imports\GstImport;
use App\Imports\StatesImport;
use App\Imports\ThicknessImport;
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
            $path1 = Input::file('excel_file')->store('temp'); 
            $path = storage_path('app').'/'.$path1;  
            $data = Excel::toArray(new HsnImport, $path);
            $newdata = $data[0];
            end($newdata);$endkey = key($newdata);reset($newdata);

            DB::table('hsn')->truncate();
            for($key = 1; $key<=$endkey; $key++ ){
                echo '<pre>';print_r($newdata[$key]);
                $hsn = new Hsn();
                $hsn_result = Hsn::where('hsn_code', $newdata[$key][0])->count();
                if($hsn_result == 0){
                    $hsn->hsn_code = $newdata[$key][0];
                    $hsn->gst = $newdata[$key][2];
                    $hsn->hsn_desc = $newdata[$key][1];
                    $hsn->save();
                }
            }
            return redirect('process')->with('success', 'HSN excel file successfully uploaded.');
        } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }
     public function storeGST(){
        if (Input::hasFile('excel_file')) {
            $path1 = Input::file('excel_file')->store('temp'); 
            $path = storage_path('app').'/'.$path1;  
            $data = Excel::toArray(new GstImport, $path);
            $newdata = $data[0];
            end($newdata);$endkey = key($newdata);reset($newdata);
            // dd($newdata);
            
            DB::table('gst')->truncate();
            for($key = 1; $key<=$endkey; $key++ ){
                // echo '<pre>';print_r($newdata[$key]);
                $gst = new Gst();
                if(Gst::where('gst',$newdata[$key][0])->where('igst',$newdata[$key][3])->where('deleted_at',NULL)->count() == 0){
                    $gst->gst = $newdata[$key][0];
                    $gst->sgst = $newdata[$key][1];
                    $gst->cgst = $newdata[$key][2];
                    $gst->igst = $newdata[$key][3];
                    $gst->quick_gst_id = $newdata[$key][4];
                    $gst->quick_igst_id = $newdata[$key][5];
                    $gst->save();
                }
            }
            return redirect('process')->with('success', 'GST excel file successfully uploaded.');
        } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
     }
    //put your code here
    
    public function storethickness(){
        if (Input::hasFile('excel_file')) {
            ini_set('max_execution_time', 720);
            $path1 = Input::file('excel_file')->store('temp'); 
            $path = storage_path('app').'/'.$path1;  
            $data = Excel::toArray(new ThicknessImport, $path);
            $newdata = $data[0];
            end($newdata);$endkey = key($newdata);reset($newdata);
            // dd($data);
    
            for($key = 1; $key<=$endkey; $key++ ){
                // echo '<pre>';
                // print_r($newdata[$key]);
                $thickness = new Thickness();
                $thickness_result = Thickness::where('thickness', $newdata[$key][0])->count();
                // print_r($thickness_result);
                if($thickness_result == 0){
                    $thickness->thickness = $newdata[$key][0];
                    $thickness->diffrence = $newdata[$key][1];
                    $thickness->save();
                }
            }
            return redirect('process')->with('success', 'Excel rows for Thickness successfully inserted.');
        } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
    }

    public function storestate(){
        if (Input::hasFile('excel_file')) {
            ini_set('max_execution_time', 720);
            $path1 = Input::file('excel_file')->store('temp'); 
            $path = storage_path('app').'/'.$path1;  
            $data = Excel::toArray(new StatesImport, $path);
            $newdata = $data[0];
            end($newdata);$endkey = key($newdata);reset($newdata);
            // dd($data);

            DB::table('state')->truncate();
            for($key = 1; $key<=$endkey; $key++ ){
                $state = new States();
                $state_result = States::where('state_name', $newdata[$key][0])->count();
                // print_r($thickness_result);
                if($state_result == 0){
                    $state->state_name = $newdata[$key][0];
                    $state->local_state = $newdata[$key][1];
                    $state->save();
                }
            }
            return redirect('process')->with('success', 'Excel rows for States successfully inserted.');
        } else {
            return redirect('process')->with('error', 'Please select file to upload');
        }
    }
     
    function update_hsn(){
        if (Input::hasFile('excel_file')) {
            ini_set('max_execution_time', 720);
            $path1 = Input::file('excel_file')->store('temp'); 
            $path = storage_path('app').'/'.$path1;  
            $data = Excel::toArray(new StatesImport, $path);
            $newdata = $data[0];
            end($newdata);$endkey = key($newdata);reset($newdata);

            for($key = 1; $key<=$endkey; $key++ ){
                // echo '<pre>';
                // print_r($newdata[$key]);
                App\ProductCategory::where('product_category_name',$newdata[$key][0])->update(['hsn_code'=>$newdata[$key][9]]);

                App\ProductSubCategory::where('alias_name',$newdata[$key][3])->update(['hsn_code'=>$newdata[$key][9]]);
            }
            $hsnresults = App\Hsn::orderBy('hsn_code', 'asc')->get();
            foreach ($hsnresults as $hsnresult) {
                echo '<pre>';
                print_r($hsnresult['hsn_desc']);
                App\ProductCategory::where('hsn_code',$hsnresult['hsn_code'])->update(['gst'=>$hsnresult['gst'],'hsn_desc' =>$hsnresult['hsn_desc'] ]);
            }
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
