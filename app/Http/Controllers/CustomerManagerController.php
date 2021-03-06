<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use View;
use Input;
use Auth;
use App\User;
use Hash;
use Redirect;
use App\CustomerManager;
use App\Http\Requests\StoreCustomerManager;

class CustomerManagerController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        if (Auth::user()->role_id != 0) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $manager = CustomerManager::all();
        return View::make('customer_manager', array('manager' => $manager));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        return View::make('add_customer_manager');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerManager $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $customer_manager = new CustomerManager();
        $customer_manager->name = Input::get('manager_name');
        $customer_manager->phone_number = Input::get('phone_number');
        if ($customer_manager->save()) {
            return redirect('customer_manager')->with('success', 'Customer Manager Succesfully added');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $manager = CustomerManager::find($id);
        return View::make('edit_customer_manager', array('manager' => $manager));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerManager $request, $id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $customer_manager = CustomerManager::find($id);
        $customer_manager->name = Input::get('manager_name');
        $customer_manager->phone_number = Input::get('phone_number');
        if ($customer_manager->save()) {
            return redirect('customer_manager')->with('success', 'Manager details succesfully updated');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('customer_manager')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $customer_manager = CustomerManager::find($id);
            $customer_manager->delete();
            return Redirect::to('customer_manager')->with('success', 'Customer manager Successfully deleted');
        } else {
            return Redirect::to('customer_manager')->with('error', 'Invalid password');
        }
    }

}
