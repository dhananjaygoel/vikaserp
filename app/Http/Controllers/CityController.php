<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;
use App\States;
use Input;
use Illuminate\Support\Facades\DB;

class CityController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $cities = City::with('states')->Paginate(2);
        $cities->setPath('cities');
        return view('cities', compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $states = States::all();
        return view('add_city', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $add_city = City::create([
                    'city_name' => Input::get('city_name'),
                    'state_id' => Input::get('state')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('city/' . $id . '/edit')->with('flash_message', 'City details successfully added.');
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
    public function update($id) {
        $affectedRows = City::where('id', '=', $id)->update([
            'state_id' => Input::get('state'),
            'city_name' => Input::get('edit_city_name'),
        ]);
        return redirect('city/' . $id . '/edit')->with('flash_message', 'City details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //        if(Auth::user()->password == Hash::make(Input::get('password'))){
        $delete_city = City::find($id)->delete();
        return redirect('city')->with('flash_message', 'City details successfully deleted.');
        //        }
    }

}
