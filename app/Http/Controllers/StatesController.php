<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;
use App\Http\Requests\Request;
use App\Http\Requests\StatesRequest;
use App\Http\Requests\EditStatesRequest;
use App\States;
use App\City;
use App\DeliveryLocation;
use Input;
use Illuminate\Support\Facades\DB;
use Hash;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Validator;

class StatesController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        if (Auth::user()->role_id == 5 ) {
           return Redirect::back()->withInput()->with('error', 'You do not have permission.');
        }
        $states = States::orderBy('created_at', 'desc')->Paginate(20);
        $states->setPath('states');
        return view('states', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        return view('add_states');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StatesRequest $staterequest) {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('states')->with('error', 'You do not have permission.');
        }

        $input = $staterequest->input();
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
        $trimmed_array = array_map('trim', $input);

        $message = array('state_name.without_spaces' => 'Please enter the name without spaces.');

        $rules = ['state_name' => 'required|regex:/^[A-Za-z\s-]+$/|unique:state,state_name'];

        $validator = Validator::make($trimmed_array, $rules, $message);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        // $this->validate($staterequest, [
        //     'state_name' => 'required|without_spaces|regex:/^[A-Za-z\s-_]+$/',
        // ]);

        $add_states = States::create([
                    'state_name' => $staterequest->input('state_name'),
                    'local_state' => $staterequest->input('local_state')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('states')->with('flash_success_message', 'State details successfully added.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        return redirect('states');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('states')->with('error', 'You do not have permission.');
        }
        $state = States::find($id);
        return view('edit_state', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, EditStatesRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('states')->with('error', 'You do not have permission.');
        }
        // $this->validate($request, [
        //     'state_name' => 'required|regex:/^[A-Za-z\s-_]+$/',
        // ]);

        $input = $request->input();
        Validator::extend('without_spaces', function($attr, $value){
            return preg_match('/^\S*$/u', $value);
        });
        $trimmed_array = array_map('trim', $input);

        $message = array('state_name.without_spaces' => 'Please enter the name without spaces.');

        $rules = ['state_name' => 'required|regex:/^[A-Za-z\s-]+$/'];

        $validator = Validator::make($input, $rules, $message);
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $check_state_exists = States::where('state_name', '=', $request->input('state_name'))->where('id', '!=', $id)->count();
        if ($check_state_exists == 0) {
            $affectedRows = States::where('id', '=', $id)->update(['state_name' => Input::get('state_name'),'local_state' => Input::get('local_state')]);
            return redirect('states')->with('flash_message', 'State details successfully modified.');
        }
        return redirect('states')->with('flash_error_message', 'State name already exists.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('states')->with('error', 'You do not have permission.');
        }
        $state_association = City::where('state_id', '=', $id)->count();
        $location_association = DeliveryLocation::where('state_id', '=', $id)->count();
        if (($state_association == 0) && ($location_association == 0)) {
            if (Hash::check(Input::get('password'), Auth::user()->password)) {
                $delete_state = States::find($id)->delete();
                return redirect('states')->with('flash_success_message', 'State details successfully deleted.');
            } else
                return redirect('states')->with('flash_message', 'Please enter a correct password');
        } else
            return redirect('states')->with('flash_message', 'State details cannot be deleted as it is associated with a city or a location.');
    }

}
