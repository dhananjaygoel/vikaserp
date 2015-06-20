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

class PurchaseDaybookController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

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
//        echo '<pre>';
//        print_r($purchase_daybook->toArray());
//        echo '</pre>';
//        exit;  
        return view('purchase_order_daybook', compact('purchase_daybook'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function delete_all_daybook() {
       
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

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {

        echo 'hi';
        exit;

        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_challan = PurchaseChallan::find($id)->delete();
            return redirect('purchase_order_daybook')->with('success', 'purchase day book details successfully deleted.');
        } else {
            return redirect('purchase_order_daybook')->with('error', 'Please enter a correct password');
        }
    }

}
