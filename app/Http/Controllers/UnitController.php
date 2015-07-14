<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitsRequest;
use App\Http\Requests\EditUnitRequest;
use App\Units;
use Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Auth;
use Redirect;

class UnitController extends Controller {

    public function __construct() {
        $this->middleware('validIP');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        $units = Units::orderBy('created_at', 'desc')->Paginate(10);
        $units->setPath('unit');
        return view('units', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('unit')->with('error', 'You do not have permission.');
        }
        return view('add_units');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(UnitsRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('unit')->with('error', 'You do not have permission.');
        }
        $add_units = Units::create([
                    'unit_name' => $request->input('unit_name')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('unit')->with('flash_success_message', 'Unit details successfully added.');
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
            return Redirect::to('unit')->with('error', 'You do not have permission.');
        }
        $unit = Units::find($id);
        return view('edit_units', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, EditUnitRequest $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('unit')->with('error', 'You do not have permission.');
        }
        $check_unit_exists = Units::where('unit_name', '=', $request->input('unit_name'))->where('id', '!=', $id)->count();
        if ($check_unit_exists != 0)
            return redirect('unit/' . $id . '/edit')->with('flash_message', 'Unit name already exists');
        $affectedRows = Units::where('id', '=', $id)->update(['unit_name' => $request->input('unit_name')]);
        return redirect('unit')->with('flash_success_message', 'Unit details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('unit')->with('error', 'You do not have permission.');
        }
        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            $delete_unit = Units::find($id)->delete();
            return redirect('unit')->with('flash_success_message', 'Unit details successfully deleted.');
        }
        return redirect('unit')->with('flash_message', 'Please enter a correct password');
    }

    public function get_units() {
        $units = Units::all();
        echo json_encode(array('units' => $units));
    }

}
