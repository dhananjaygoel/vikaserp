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
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if(Auth::user()->role_id != 0 && Auth::user()->role_id != 4){
            return redirect()->back();
        }
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
            $q = $q->orderBy('id', 'desc');
            $receipts = $q->paginate(20);
            $receipts->setPath('receipt-master');
            return view('receipt_master.index')->with('receipts', $receipts)->with('from_date', $from_date)->with('to_date', $to_date);
        } else {
            if (Session::has('succcess_msg')) {
                Session::flash('flash_message', 'Receipt deleted succesfully.');
                Session::forget('succcess_msg');
            }
            if (Session::has('create_msg')) {
                Session::flash('flash_message', 'Receipt created succesfully.');
                Session::forget('create_msg');
            }
            if (Session::has('succcess_flag')) {
                Session::flash('flash_message', 'Receipt updated succesfully.');
                Session::forget('succcess_flag');
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
        if(Auth::user()->role_id != 0 && Auth::user()->role_id != 4){
            return redirect()->back();
        }
        if (Auth::user()->role_id == 0) {
            $user_type = 'admin';
        }else{
            $user_type = 'account';
        }
        $type = 1; //journal
        $tally_users = Customer::where('customer_status','permanent')->get();
        $debited_users = Customer::where('customer_status','permanent')->get();
        return view('receipt_master.create_journal', compact('tally_users', 'type', 'debited_users','user_type'));
    }

    /**
     * Show the form for creating a new Bank Receipt.
     *
     * @return Response
     */
    public function create_bank_receipt() {
        $type = 2; //bank
        if(Auth::user()->role_id != 0 && Auth::user()->role_id != 4){
            return redirect()->back();
        }
        if (Auth::user()->role_id == 0) {
            $user_type = 'admin';
        }else{
            $user_type = 'account';
        }
        $debited_to = Debited_to::where('debited_to_type', '=', 2)->get();
        $tally_users = Customer::where('customer_status','permanent')->get();
        return view('receipt_master.create_journal', compact('tally_users', 'type', 'debited_to','user_type'));
    }

    /**
     * Show the form for creating a new Cash Receipt.
     *
     * @return Response
     */
    public function create_cash_receipt() {
        $type = 3; //cash
        if(Auth::user()->role_id != 0 && Auth::user()->role_id != 4){
            return redirect()->back();
        }
        if (Auth::user()->role_id == 0) {
            $user_type = 'admin';
        }else{
            $user_type = 'account';
        }
        $debited_to = Debited_to::where('debited_to_type', '=', 3)->get();
        $tally_users = Customer::where('customer_status','permanent')->get();
        return view('receipt_master.create_journal', compact('tally_users', 'type', 'debited_to','user_type'));
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
                            $validator->errors()->add('settle amount', 'Please enter the amount.');
                        });
                    }
                }
            } else {
                $validator->after(function($validator) {
                    $validator->errors()->add('settle amount', 'Please enter the amount.');
                });
            }
            if ($validator->fails()) {
                return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
//                return redirect()->back()->withInput()->withErrors($validator->errors());
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
                    Session::set('create_msg', true);
                    return Response::json(['success' => true, 'receipt' => true]);
                } else
                    return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured while saving receipt']);
            } else
                return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured while saving receipt']);
//                return redirect('receipt-master')->with('error', 'Some error occoured while saving receipt');
        }else {
            return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured. Please try after sometime.']);
//            return Redirect::back()->withInput()->with('error', 'Some error occoured while saving receipt');
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
        if(Auth::user()->role_id != 0 && Auth::user()->role_id != 4){
            return redirect()->back();
        }
        if (isset($id) && !empty($id)) {
            $receiptObj = Receipt::where('id', '=', $id)->with('customer_receipts')->get();
            if (Auth::user()->role_id == 0) {
                $user_type = 'admin';
            }else{
                $user_type = 'account';
            }
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
                                        ->with('user_type', $user_type)
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
                                        ->with('user_type', $user_type)
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
                if (isset($tally_users) && !empty($tally_users)) {
                    foreach ($tally_users as $key => $tallyuser) {
                        if ($tallyuser == '') {
                            $validator->after(function($validator) {
                                $validator->errors()->add('tally_users', 'Please select tally user.');
                            });
                        }
                    }
                }
                if (Input::has('customer_ids_array')) {
                    $old_customers = Input::get('customer_ids_array');
                    if (isset($old_customers) && !empty($old_customers)) {
                        $old_customers = str_replace("[", '', $old_customers);
                        $old_customers = str_replace("]", '', $old_customers);
                        $old_customers = explode(",", $old_customers);
//                        foreach ($old_customers as $old_customer) {
                        if (Auth::user()->role_id == 1 || Auth::user()->role_id == 0) {
                            foreach ($old_customers as $old_customer) {
                                $customer_receipt = Customer_receipts::where('customer_id', '=', $old_customer)
                                                ->where('receipt_id', '=', $id)->first();
                                if (isset($customer_receipt)) {
                                    $customer_receipt->delete();
                                }
                            }
                        } elseif (Auth::user()->role_id == 4) {
                            foreach ($old_customers as $old_customer) {
                                $customer_receipt = Receipt::with('customer_receipts')->find($id);
                                if (count($customer_receipt->customer_receipts) > 1) {
                                    $customer_receipt_obj = Customer_receipts::where('customer_id', '=', $old_customer)
                                                    ->where('receipt_id', '=', $id)->first();
                                    if (isset($customer_receipt_obj)) {
                                        $customer_receipt_obj->delete();
                                    }
                                } else {
                                    if (isset($settle_amount) && !empty($settle_amount)) {
                                        foreach ($settle_amount as $key => $settleamount) {
                                            if ($settleamount == '') {
                                                $validator->after(function($validator) {
                                                    $validator->errors()->add('settle amount', 'Please enter the amount.');
                                                });
                                            }
                                        }
                                    } else {
                                        $validator->after(function($validator) {
                                            $validator->errors()->add('settle amount', 'Please enter the amount.');
                                        });
                                    }
                                    if ($validator->fails()) {
                                        return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
                                    }
                                }
                            }
                        }
//                        }
                    }
                }
                if (isset($settle_amount) && !empty($settle_amount)) {
                    foreach ($settle_amount as $key => $settleamount) {
                        if ($settleamount == '') {
                            $validator->after(function($validator) {
                                $validator->errors()->add('settle amount', 'Please enter the amount.');
                            });
                        }
                    }
                } else {
                    $validator->after(function($validator) {
                        $validator->errors()->add('settle amount', 'Please enter the amount.');
                    });
                }
                if ($validator->fails()) {
                    return Response::json(['success' => false, 'errors' => $validator->getMessageBag()->toArray()]);
//                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }
                $receiptObj = Receipt::with('customer_receipts')->find($id);
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
                    if ($customerReceiptObj) {
                        Session::set('succcess_flag', true);
//                        Session::set('succcess_flag', true);
                        return Response::json(['success' => true, 'receipt' => true]);
//                        return redirect('receipt-master')->with('success', 'Receipt succesfully updated.');
                    } else {
                        return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured while updating receipt']);
//                        return redirect('receipt-master')->with('error', 'Some error occoured while updating receipt');
                    }
                } else
                    return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured while updating receipt']);
//                    return redirect('receipt-master')->with('error', 'Some error occoured while updating receipt');
            } else
                return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured while updating receipt']);
//                return redirect('receipt-master')->with('error', 'Some error occoured while updating receipt');
        }else {            
            return Response::json(['success' => false, 'receipt' => true, 'flash_message'=>'Some error occoured. Please try after sometime.']);
//            return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after sometime');
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
            if (Input::has('model_pass')) {
                if (Hash::check(Input::get('model_pass'), Auth::user()->password)) {
                    $receipt = Receipt::find($id);
                    if ($receipt) {
                        $customer_recripts = Customer_receipts::where('receipt_id', '=', $id)->get();
                        foreach ($customer_recripts as $customer_recript) {
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
            }else{
                $receipt = Receipt::find($id);
                if ($receipt) {
                    $customer_recripts = Customer_receipts::where('receipt_id', '=', $id)->get();
                    foreach ($customer_recripts as $customer_recript) {
                        $customer_recript->delete();
                    }
                    $receipt->delete();
                    Session::set('succcess_msg', true);
                    return Response::json(['success' => true, 'receipt' => true]);
                } else {
                    return Response::json(['success' => false]);
//                    return Redirect::back()->withInput()->with('error', 'Some error occoured. Please try after somtime.');
                }
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
    
    public function update_settle_amount(Request $request) {
        if (Input::has('model_price') && Input::has('challan_id') && Input::get('customer_id')) {
            $new_settle_amount = Input::get('model_price');
            $challan_id = Input::get('challan_id');
            $customer_id = Input::get('customer_id');
            if (isset($challan_id)) {
                $challan_obj = DeliveryChallan::find($challan_id);                
                $challan_obj->settle_amount = sprintf("%.2f", $new_settle_amount);                
                $challan_obj->save();
            }
        }
        return Redirect::back()->withInput();
    }

}
