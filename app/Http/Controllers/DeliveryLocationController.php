<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
use App\States;
use App\DeliveryLocation;
use Input;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LocationRequest;
use App\Http\Requests\EditLocationRequest;
use Hash;
use Illuminate\Support\Facades\Auth;
use Redirect;

class DeliveryLocationController extends Controller {

    public function __construct() {
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $delivery_location = DeliveryLocation::where('status', '=', 'permanent')->with('city.states')->orderBy('created_at', 'desc')->Paginate(20);

        $delivery_location->setPath('location');
        return view('delivery_location', compact('delivery_location'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('location')->with('error', 'You do not have permission.');
        }
        $states = States::orderBy('state_name', 'ASC')->get();
        $cities = City::orderBy('city_name', 'ASC')->get();
        return view('add_delivery_location', compact('states', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request) {

        if (Auth::user()->role_id != 0) {
            return Redirect::to('location')->with('error', 'You do not have permission.');
        }

        $location = new DeliveryLocation();
        $location->area_name = $request->input('area_name');
        $location->state_id = $request->input('state');
        $location->city_id = $request->input('city');
        $location->difference = $request->input('difference');
        $location->status = 'permanent';
        $location->save();

        return redirect('location')->with('flash_success_message', 'Location details successfully added.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('location')->with('error', 'You do not have permission.');
        }
        $delivery_location = DeliveryLocation::find($id);
        $states = States::orderBy('state_name', 'ASC')->get();
        $cities = City::orderBy('city_name', 'ASC')->get();
        return view('edit_delivery_location', compact('cities', 'states', 'delivery_location'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, EditLocationRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('location')->with('error', 'You do not have permission.');
        }
        $check_location_exists = DeliveryLocation::where('area_name', '=', $request->input('area_name'))->where('id', '!=', $id)->count();
        if ($check_location_exists == 0) {
            $affectedRows = DeliveryLocation::where('id', '=', $id)->update([
                'state_id' => $request->input('state'),
                'city_id' => $request->input('city'),
                'area_name' => $request->input('area_name'),
                'status' => 'permanent',
                'difference' => $request->input('difference')
            ]);
            return redirect('location')->with('flash_success_message', 'Location details successfully modified.');
        } else
            return redirect('location/' . $id . '/edit')->with('flash_message', 'Location name already exists.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('location')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_location = DeliveryLocation::find($id)->delete();
            return redirect('location')->with('flash_success_message', 'Location details successfully deleted.');
        } else
            return redirect('location')->with('flash_message', 'Please enter a correct password');
    }

    /*
      | Update the delivery location
      | difference price
     */

    public function delivery_difference() {
        $data = Input::all();

        if ($data['difference'] != "") {
            $del = DeliveryLocation::where('id', '=', $data['id'])->update([
                'difference' => $data['difference']
            ]);
            return redirect('location')->with('flash_success_message', 'Location difference successfully modified.');
        } else
            return redirect('location')->with('flash_message', 'Unable to update the delivery location');
    }

}
