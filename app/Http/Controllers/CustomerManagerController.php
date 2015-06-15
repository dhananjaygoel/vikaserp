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

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $manager = CustomerManager::all();
        return View::make('customer_manager', array('manager' => $manager));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('add_customer_manager');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreCustomerManager $request) {
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
        $manager = CustomerManager::find($id);
        return View::make('edit_customer_manager', array('manager' => $manager));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(StoreCustomerManager $request, $id) {
        $customer_manager = CustomerManager::find($id);
        $customer_manager->name = Input::get('manager_name');
        $customer_manager->phone_number = Input::get('phone_number');
        if ($customer_manager->save()) {
            return redirect('customer_manager/' . $customer_manager->id . '/edit')->with('success', 'Manager details succesfully updated');
        } else {
            return Redirect::back()->with('error', 'Some error occoured while saving customer');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
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
