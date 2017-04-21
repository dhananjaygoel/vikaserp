<?php

namespace App\Http\Controllers;

use App\User;
use App\Receipt;
use App\Customer;
use App\Debited_to;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use View;

class ReceiptMasterController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $receipts = Receipt::orderBy('id', 'desc')->get();
//        $receipts = $receipts->paginate(1);
//        $receipts->setPath('receipt-master');
        return view('receipt_master.index')->with('receipts', $receipts);
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
     * Show the form for creating a new Journal Receipt.
     *
     * @return Response
     */
    public function create_journal_receipt() {
//        $tally_users  = [];        
//        $customers = Customer::where('tally_name','!=','')->select('id', 'tally_name', 'phone_number1')->get();
//        foreach($customers as $customer){
//            $tally_users[$customer->id] = $customer->tally_name.'+'.$customer->phone_number1;
//        }
        $tally_users = Customer::where('tally_name', '!=', '')->select('id', 'tally_name', 'phone_number1')->get();
        return view('receipt_master.create_journal', compact('tally_users'));
    }

    /**
     * Show the form for creating a new Bank Receipt.
     *
     * @return Response
     */
    public function create_bank_receipt() {
        return View::make('receipt_master.createb');
    }

    /**
     * Show the form for creating a new Cash Receipt.
     *
     * @return Response
     */
    public function create_cash_receipt() {
        return View::make('receipt_master.createc');
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
     * Store a newly created journal receipt resource in storage.
     *
     * @return Response
     */
    public function store_journal(Request $request) {
        if (Input::has('tally_users')) {
            $tally_users = Input::get('tally_users');
            $settle_amount = Input::get('settle_amount');
            $debited_to = Input::get('debited_to');
            $users = [];
            foreach ($tally_users as $tally_user) {
                foreach ($settle_amount as $amount) {
                    if(array_key_exists($tally_user, $settle_amount)){
                        $users[$tally_user] = $amount;
                    }
                }
            }
            if(isset($users)){
                foreach($users as $key => $user){
                    $userObj = new Receipt();
                    $userObj->user_id = $key;
                    $userObj->settled_amount = $user;
                    $userObj->type_id = 1;
                    $userObj->debited_to = $debited_to;
                    $userObj->save();
                }
                if($userObj)
                    return redirect('receipt-master')->with('success', 'Receipt succesfully generated.');
                else
                    return redirect('receipt-master')->with('error', 'Some error occoured while saving customer');
            }
        }else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Store a newly created journal receipt resource in storage.
     *
     * @return Response
     */
    public function store_bank() {
        //
    }

    /**
     * Store a newly created journal receipt resource in storage.
     *
     * @return Response
     */
    public function store_cash() {
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
    public function destroy(Request $request, $id) {
        if (isset($id) && !empty($id)) {
            if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
                $receipt = Receipt::find($id);
                if ($receipt->delete()) {
                    return redirect('receipt-master')->with('success', 'Receipt deleted succesfully.');
                } else {
                    return Redirect::back()->withInput()->with('error', 'Some error occoured while saving customer.');
                }
            } else {
                return redirect('receipt-master')->with('error', 'Please enter a correct password.');
            }
        }
    }

}
