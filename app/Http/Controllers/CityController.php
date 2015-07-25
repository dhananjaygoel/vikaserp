<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
use App\States;
use App\DeliveryLocation;
use App\Http\Requests\CityRequest;
use App\Http\Requests\EditCityRequest;
use Input;
use Illuminate\Support\Facades\DB;
use Hash;
use Auth;

class CityController extends Controller {

    public function __construct() {
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $cities = City::with('states')->orderBy('created_at', 'desc')->Paginate(20);
        $cities->setPath('city');
        return view('cities', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('city')->with('error', 'You do not have permission.');
        }
        $states = States::all();
        return view('add_city', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(CityRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('city')->with('error', 'You do not have permission.');
        }
        $add_city = City::create([
                    'city_name' => $request->input('city_name'),
                    'state_id' => $request->input('state')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('city')->with('flash_success_message', 'City details successfully added.');
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
        if (Auth::user()->role_id != 0) {
            return Redirect::to('city')->with('error', 'You do not have permission.');
        }
        $city = City::find($id);
        $states = States::all();
        return view('edit_city', compact('city', 'states'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, EditCityRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('city')->with('error', 'You do not have permission.');
        }
        $check_city_exists = City::where('city_name', '=', $request->input('city_name'))->where('id', '!=', $id)->count();
        if ($check_city_exists == 0) {
            $affectedRows = City::where('id', '=', $id)->update([
                'state_id' => $request->input('state'),
                'city_name' => $request->input('city_name'),
            ]);
            return redirect('city')->with('flash_success_message', 'City details successfully modified.');
        } else
            return redirect('city/' . $id . '/edit')->with('flash_message', 'City name already exists.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('city')->with('error', 'You do not have permission.');
        }
        $location_association = DeliveryLocation::where('city_id', '=', $id)->count();
        if ($location_association == 0) {
            if (Hash::check(Input::get('password'), Auth::user()->password)) {
                $delete_city = City::find($id)->delete();
                return redirect('city')->with('flash_success_message', 'City details successfully deleted.');
            } else
                return redirect('city')->with('flash_message', 'Please enter a correct password');
        } else
            return redirect('city')->with('flash_message', 'City details cannot be deleted as it is associated with a location.');
    }

    public function get_cities() {
        $state_id = Input::get('state_id');
        $cities = City::where('state_id', '=', $state_id)->get();
        echo json_encode(array('cities' => $cities));
    }

}
