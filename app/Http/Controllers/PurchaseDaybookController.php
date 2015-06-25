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
                            ->where('order_status', 'completed')
                            ->whereHas('purchase_advice', function($query) {
                                $query->where('purchase_advice_date', '=', date("Y-m-d", strtotime(Input::get('date'))));
                            })->Paginate(10);
        } else {

            $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier')
                    ->where('order_status', 'completed')
                    ->Paginate(10);
            $purchase_daybook->setPath('purchase_order_daybook');
        }
        return view('purchase_order_daybook', compact('purchase_daybook'));
    }

    public function delete_all_daybook() {
        if (Auth::user()->role_id != 0) {
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

        $purchase_daybook = PurchaseChallan::with('purchase_advice', 'orderedby', 'supplier')
                ->where('order_status', 'completed')
                ->get();


        $sheet_data = array();
        foreach ($purchase_daybook as $key => $value) {

            $sheet_data[$key]['date'] = date("d F, Y", strtotime($value['purchase_advice']->purchase_advice_date));
            $sheet_data[$key]['Party_name'] = $value['supplier']->owner_name;
            $sheet_data[$key]['vehicle_number'] = $value->vehicle_number;
            $sheet_data[$key]['orderedby'] = $value['orderedby']->first_name;
            $sheet_data[$key]['unloaded_by'] = $value->unloaded_by;
            $sheet_data[$key]['labours'] = $value->labours;
            $sheet_data[$key]['amount'] = $value->amount;
            $sheet_data[$key]['bill_number'] = $value->bill_number;
            $sheet_data[$key]['remarks'] = $value->remarks;
            $sheet_data[$key]['created_at'] = $value->created_at;
            $sheet_data[$key]['updated_at'] = $value->updated_at;
        }


        Excel::create('Purchase-Daybook-list', function($excel) use($sheet_data) {

            $excel->sheet('Order List', function($sheet) use($sheet_data) {
                $sheet->fromArray($sheet_data);
            });
        })->export('xls');

        exit;
    }

    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
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
