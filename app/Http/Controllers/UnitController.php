<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitsRequest;
use App\Units;
use Input;
use Illuminate\Support\Facades\DB;

class UnitController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $units = Units::Paginate(2);
        $units->setPath('unit');
        return view('units', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view('add_units');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        $add_units = Units::create([
                    'unit_name' => Input::get('unit_name')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('unit/' . $id . '/edit')->with('flash_message', 'Unit details successfully added.');
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
        $unit = Units::find($id);
        return view('edit_units', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $affectedRows = Units::where('id', '=', $id)->update(['unit_name' => Input::get('edit_unit_name')]);
        return redirect('unit/' . $id . '/edit')->with('flash_message', 'Unit details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
//        if(Auth::user()->password == Hash::make(Input::get('password'))){
        $delete_unit = Units::find($id)->delete();
        return redirect('unit')->with('flash_message', 'Unit details successfully deleted.');
        //        }
    }

}
