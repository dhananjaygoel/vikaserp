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

class DeliveryLocationController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $delivery_location = DeliveryLocation::with('states.city')->Paginate(2);
        $delivery_location->setPath('delivery_location');
        return view('delivery_location', compact('delivery_location'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $states = States::all();
        $cities = City::all();
        return view('add_delivery_location', compact('states', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
//        echo '<pre>';
//        print_r(Input::get('location_name'));
//        echo '</pre>';
//        exit;
        $add_delivery_location = DeliveryLocation::create([
                    'area_name' => Input::get('location_name'),
                    'state_id' => Input::get('state'),
                    'city_id' => Input::get('city')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('location/' . $id . '/edit')->with('flash_message', 'Delivery_location details successfully added.');
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
        $delivery_location = DeliveryLocation::find($id);
        $states = States::all();
        $cities = City::all();
        return view('edit_delivery_location', compact('cities', 'states', 'delivery_location'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $affectedRows = DeliveryLocation::where('id', '=', $id)->update([
            'state_id' => Input::get('state'),
            'city_id' => Input::get('city'),
            'area_name' => Input::get('edit_location_name'),
        ]);
        return redirect('location/' . $id . '/edit')->with('flash_message', 'Delivery location details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //        if(Auth::user()->password == Hash::make(Input::get('password'))){
        $delete_location = DeliveryLocation::find($id)->delete();
        return redirect('location')->with('flash_message', 'Delivery_location details successfully deleted.');
        //        }
    }

}
