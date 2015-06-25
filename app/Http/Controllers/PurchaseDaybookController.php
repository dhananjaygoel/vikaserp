<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\PurchaseChallan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeliveryLocation;
use App\Http\Requests\CityRequest;
use App\Http\Requests\EditCityRequest;
use Input;
use Illuminate\Support\Facades\DB;
use Hash;
use Auth;
use App\Vendor\Phpoffice\Phpexcel\Classes;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseDaybookController extends Controller {

    public function index() {
if (Auth::user()->role_id != 0 && Auth::user()->role_id != 1) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $purchase_daybook = 0;
        if (Input::get('date') != "") {

            $purchase_daybook = PurchaseChallan::with('orderedby', 'supplier')
                            ->whereHas('purchase_advice', function($query) {
                                $query->where('purchase_advice_date', '=', date("Y-m-d", strtotime(Input::get('date'))));
                            })->Paginate(10);
        } else {

            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier')->Paginate(10);
            $purchase_daybook->setPath('purchase_order_daybook');
        }
        return view('purchase_order_daybook', compact('purchase_daybook'));
    }

    public function delete_all_daybook() {
        if (Auth::user()->role_id != 0 ) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $id = Input::all();

        if (Hash::check(Input::get('delete_all_password'), Auth::user()->password)) {

            foreach ($id['daybook'] as $key) {

                PurchaseChallan::find($key)->delete();
            }

            return redirect('purchase_order_daybook')->with('success', 'purchase day book details successfully deleted.');
        } else {

            return redirect('purchase_order_daybook')->with('error', 'Please enter a correct password');
        }
    }

    public function expert_purchase_daybook() {

        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {

                $sheet->fromArray(array(
                    array('data1', 'data2'),
                    array('data3', 'data4')
                ));
            });
        })->export('xls');
        
        exit;
    }

    public function destroy($id) {
        if (Auth::user()->role_id != 0 ) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_challan = PurchaseChallan::find($id)->delete();
            return redirect('purchase_order_daybook')->with('success', 'purchase day book details successfully deleted.');
        } else {
            return redirect('purchase_order_daybook')->with('error', 'Please enter a correct password');
        }
    }

}
