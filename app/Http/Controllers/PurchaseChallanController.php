<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PurchaseChallan;

class PurchaseChallanController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $purchase_challan = PurchaseChallan::with('supplier', 'purchase_advice')->Paginate(2);
        $purchase_challan->setPath('purchase_challan');
        return view('purchase_challan', compact('purchase_challan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_purchase_challan = PurchaseChallan::find($id)->delete();
            return redirect('purchase_challan')->with('flash_success_message', 'Purchase challan details successfully deleted.');
        } else
            return redirect('purchase_challan')->with('flash_message', 'Please enter a correct password');
    }

}
