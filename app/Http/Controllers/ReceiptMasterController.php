<?php

namespace App\Http\Controllers;

use View;
use App\User;
use App\Receipt;
use App\Customer;
use App\Debited_to;
use App\Http\Requests;
use App\DeliveryChallan;
use App\Customer_receipts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ReceiptMasterController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        //Check authorization of user with current ip address
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Input::has('search_from_date') && Input::has('search_to_date')) {
            $date1 = \DateTime::createFromFormat('m-d-Y', Input::get('search_from_date'))->format('Y-m-d');
            $date2 = \DateTime::createFromFormat('m-d-Y', Input::get('search_to_date'))->format('Y-m-d');
            $q = Receipt::query();
            if ($date1 == $date2) {
                $q->where('created_at', 'like', $date1 . '%');
            } else {
                $q->where('created_at', '>=', $date1);
                $q->where('created_at', '<=', $date2 . ' 23:59:59');
            }
            $from_date = Input::get('search_from_date');
            $to_date = Input::get('search_to_date');
//            $search_dates = [
//                'export_from_date' => Input::get('search_from_date'),
//                'export_to_date' => Input::get('search_to_date')
//            ];
            $receipts = $q->paginate(20);
            $receipts->setPath('receipt-master');
            return view('receipt_master.index')->with('receipts', $receipts)->with('from_date', $from_date)->with('to_date', $to_date);
        } else {
            if(Session::has('succcess_msg')){
               Session::flash('flash_message','Receipt deleted succesfully.');
               Session::forget('succcess_msg');
            }
            $receipts = Receipt::orderBy('id', 'desc');
            $receipts = $receipts->paginate(20);
            $receipts->setPath('receipt-master');
            return view('receipt_master.index')->with('receipts', $receipts);
        }
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

        $type = 1; //journal
        $tally_users = Customer::where('tally_name', '!=', '')->whereNotNull('tally_name')->select('id', 'tally_name')->get();
        $debited_users = Customer::where('tally_name', '!=', '')->whereNotNull('tally_name')->select('id', 'tally_name')->get();
        return view('receipt_master.create_journal', compact('tally_users', 'type', 'debited_users'));
    }

    /**
     * Show the form for creating a new Bank Receipt.
     *
     * @return Response
     */
    public function create_bank_receipt() {
        $type = 2; //bank
        $debited_to = Debited_to::where('debited_to_type', '=', 2)->get();
        $tally_users = Customer::where('tally_name', '!=', '')->whereNotNull('tally_name')->select('id', 'tally_name')->get();
        return view('receipt_master.create_journal', compact('tally_users', 'type', 'debited_to'));
    }

    /**
     * Show the form for creating a new Cash Receipt.
     *
     * @return Response
     */
    public function create_cash_receipt() {
        $type = 3; //cash
        $debited_to = Debited_to::where('debited_to_type', '=', 3)->get();
        $tally_users = Customer::select('id', 'tally_name')->get();
        return view('receipt_master.create_journal', compact('tally_users', 'type', 'debited_to'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        if (Input::has('tally_users')) {
            $tally_users = Input::get('tally_users');
            $settle_amount = Input::get('settle_amount');
            $debited_to = Input::get('debited_to');
            $receipt_type = Input::get('receipt_type');
            $validator = Validator::make($request->input(), Customer_receipts::$ValidateNewReceipt, Customer_receipts::$validatorMessages);
            foreach ($tally_users as $key => $tallyuser) {
                if ($tallyuser == '') {
                    $validator->after(function($validator) {
                        $validator->errors()->add('tally_users', 'Please select tally user.');
                    });
                }
            }
            if (isset($settle_amount) && !empty($settle_amount)) {
                foreach ($settle_amount as $key => $settleamount) {
                    if ($settleamount == '') {
                        $validator->after(function($validator) {
                            $validator->errors()->add('settle amount', 'Please enter settle amount.');
                        });
                    }
                }
            } else {
                $validator->after(function($validator) {
                    $validator->errors()->add('settle amount', 'Please enter settle amount.');
                });
            }
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            if (isset($settle_amount) && count($settle_amount) > 0) {
                $receiptObj = new Receipt();
                if ($receiptObj->save()) {
                    foreach ($settle_amount as $key => $user) {
                        if ($key != '') {
                            $customerReceiptObj = new Customer_receipts();
                            $customerReceiptObj->customer_id = $key;
                            $customerReceiptObj->settled_amount = $user;
                            $customerReceiptObj->debited_to = $debited_to;
                            $customerReceiptObj->receipt_id = $receiptObj->id;
                            if ($receipt_type == 1)
                                $customerReceiptObj->debited_by_type = 1;
                            elseif ($receipt_type == 2)
                                $customerReceiptObj->debited_by_type = 2;
                            elseif ($receipt_type == 3)
                                $customerReceiptObj->debited_by_type = 3;

                            $customerReceiptObj->save();
                        }
                    }
                } else
                    return redirect('receipt-master')->with('error', 'Some error occoured while saving receipt');
                
                if ($customerReceiptObj)
                    return redirect('receipt-master')->with('success', 'Receipt succesfully generated.');
                else
                    return redirect('receipt-master')->with('error', 'Some error occoured while saving receipt');
            } else
                return redirect('receipt-master')->with('error', 'Some error occoured while saving receipt');
        }else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving receipt');
        }
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
        if (isset($id) && !empty($id)) {
            $receiptObj = Receipt::where('id', '=', $id)->with('customer_receipts')->get();
            if (isset($receiptObj) && $receiptObj != '' && !empty($receiptObj) && count($receiptObj)) {
                $customer_arr = [];
                foreach ($receiptObj as $obj) {
                    if (!isset($obj->customer_receipts) || count($obj->customer_receipts) <= 0) {
                        return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after sometime.');
                    } else {
                        $receipt_id = $obj['id'];
                        foreach ($obj->customer_receipts as $key => $customer) {
                            $debited_id = $customer['debited_to'];
                            $receipt_type = $customer['debited_by_type'];
                            $customer_arr[$customer['customer_id']] = $customer['settled_amount'];
                        }
                    }
                }
                if (isset($receipt_type)) {
                    if ($receipt_type == 1) {
                        $tally_users = Customer::where('tally_name', '!=', '')->select('id', 'tally_name')->get();
                        return view('receipt_master.edit_receipt')->with('tally_users', $tally_users)
                                        ->with('receiptObj', $receiptObj)
                                        ->with('customer_arr', $customer_arr)
                                        ->with('type', $receipt_type)->with('debited_id', $debited_id)->with('receipt_id', $receipt_id);
                    } else {
                        if ($receipt_type == 2)
                            $debited_to = Debited_to::where('debited_to_type', '=', 2)->get();
                        if ($receipt_type == 3)
                            $debited_to = Debited_to::where('debited_to_type', '=', 3)->get();

                        $tally_users = Customer::where('tally_name', '!=', '')->select('id', 'tally_name')->get();
                        return view('receipt_master.edit_receipt')
                                        ->with('tally_users', $tally_users)->with('receiptObj', $receiptObj)
                                        ->with('type', $receipt_type)->with('debited_to', $debited_to)
                                        ->with('customer_arr', $customer_arr)
                                        ->with('debited_id', $debited_id)->with('receipt_id', $receipt_id);
                    }
                }
            } else {
                return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after sometime.');
            }
        } else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after sometime.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        if (isset($id) && !empty($id)) {
            $receiptObj = Receipt::with('customer_receipts')->find($id);
            if (isset($receiptObj)) {
                $tally_users = Input::get('tally_users');
                $settle_amount = Input::get('settle_amount');
                $debited_to = Input::get('debited_to');
                $receipt_type = Input::get('receipt_type');
                $validator = Validator::make($request->input(), Customer_receipts::$ValidateNewReceipt, Customer_receipts::$validatorMessages);
                foreach ($tally_users as $key => $tallyuser) {
                    if ($tallyuser == '') {
                        $validator->after(function($validator) {
                            $validator->errors()->add('tally_users', 'Please select tally user.');
                        });
                    }
                }
                if (isset($settle_amount) && !empty($settle_amount)) {
                    foreach ($settle_amount as $key => $settleamount) {
                        if ($settleamount == '') {
                            $validator->after(function($validator) {
                                $validator->errors()->add('settle amount', 'Please enter settle amount.');
                            });
                        }
                    }
                } else {
                    $validator->after(function($validator) {
                        $validator->errors()->add('settle amount', 'Please enter settle amount.');
                    });
                }
                if ($validator->fails()) {
                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
                if (isset($receiptObj->customer_receipts)) {
                    foreach ($receiptObj->customer_receipts as $customers) {
                        $customerObj = Customer_receipts::find($customers->id);
                        $customerObj->delete();
                    }
                }
                if (isset($settle_amount) && count($settle_amount) > 0) {
                    foreach ($settle_amount as $key => $user) {
                        if ($key != '') {
                            $customerReceiptObj = new Customer_receipts();
                            $customerReceiptObj->customer_id = $key;
                            $customerReceiptObj->settled_amount = $user;
                            $customerReceiptObj->debited_to = $debited_to;
                            $customerReceiptObj->receipt_id = $receiptObj->id;
                            if ($receipt_type == 1)
                                $customerReceiptObj->debited_by_type = 1;
                            elseif ($receipt_type == 2)
                                $customerReceiptObj->debited_by_type = 2;
                            elseif ($receipt_type == 3)
                                $customerReceiptObj->debited_by_type = 3;

                            $customerReceiptObj->save();
                        }
                    }
                    if ($customerReceiptObj)
                        return redirect('receipt-master')->with('success', 'Receipt succesfully updated.');
                    else
                        return redirect('receipt-master')->with('error', 'Some error occoured while updating receipt');
                } else
                    return redirect('receipt-master')->with('error', 'Some error occoured while updating receipt');
            } else
                return redirect('receipt-master')->with('error', 'Some error occoured while updating receipt');
        }else {
            return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after sometime');
        }
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
                if ($receipt) {
                    $customer_recripts = Customer_receipts::where('receipt_id','=',$id)->get();
                    foreach($customer_recripts as $customer_recript){
                        $customer_recript->delete();
                    }
                    $receipt->delete();
                    return redirect('receipt-master')->with('success', 'Receipt deleted succesfully.');
                } else {
                    return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after somtime.');
                }
            } else {
                return redirect('receipt-master')->with('error', 'Please enter a correct password.');
            }
        }
    }

    public function delete_customer_receipt(Request $request, $id) {
        if (isset($id) && !empty($id)) {
            if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
                $customer_id = Input::get('customer_id');
                if (Auth::user()->role_id == 4) {
                    $receipt = Receipt::with('customer_receipts')->find($id);
                    if (count($receipt->customer_receipts) > 1) {
                        $receipt = Customer_receipts::where('customer_id', '=', $customer_id)
                                        ->where('receipt_id', '=', $id)->first();
                        $receipt->delete();
                        return Response::json(['success' => true]);
                    } else {
                        return Response::json(['success' => true, 'error'=>'Receipt could not delete.']);
                    }
                } else if (Auth::user()->role_id == 1 || Auth::user()->role_id == 0) {
                    $receipt = Customer_receipts::where('customer_id', '=', $customer_id)
                                    ->where('receipt_id', '=', $id)->first();
                    if ($receipt->delete()) {
                        $receipt_id = Customer_receipts::where('receipt_id', '=', $id)->get();
                        if (count($receipt_id) > 0) {
                            return Response::json(['success' => true,'receipt' => true]);
                        } else {
                            $receiptObj = Receipt::find($id);
                            $receiptObj->delete();
                            Session::set('succcess_msg', true);
                            return Response::json(['success' => true,'receipt' => true]);
//                            return redirect('receipt-master')->with('success', 'Receipt deleted succesfully.');
                        }
                    } else {
                        return Response::json(['success' => false,'flash_message' => 'Receipt could not delete.']);
                    }
                }
            } else {
                return Response::json(['success' => false,'flash_message' => 'Please enter a correct password.']);
            }
        }
    }

    public function settle_amount(Request $request) {
        if (Input::has('model_price') && Input::has('challan_id')) {
            $unsettle_amount = Input::get('model_price');
            $challan_id = Input::get('challan_id');
            $customer_id = Input::get('customer_id');
            if (isset($challan_id)) {
                $challan_obj = DeliveryChallan::find($challan_id);
                if ($challan_obj->settle_amount && $challan_obj->settle_amount != "") {
                    $pre_amount = $challan_obj->settle_amount;
                    $curr_amount = sprintf("%.2f", $unsettle_amount);
                    $total_amount = $pre_amount + $curr_amount;
                } else {
                    $total_amount = sprintf("%.2f", $unsettle_amount);
                }
                $challan_obj->settle_amount = sprintf("%.2f", $total_amount);
                ;
                $challan_obj->save();
            }
        }
        return Redirect::back()->withInput();
    }

}
