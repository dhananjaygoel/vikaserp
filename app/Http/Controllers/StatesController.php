<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//use App\Http\Requests\Request;
use App\Http\Requests\StatesRequest;
use App\States;
use Input;
use Illuminate\Support\Facades\DB;

class StatesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $states = States::Paginate(2);
        $states->setPath('states');
        return view('states', compact('states'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return view('add_states');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StatesRequest $staterequest) {
        $add_states = States::create([
                    'state_name' => Input::get('state_name')
        ]);
        $id = DB::getPdo()->lastInsertId();
        return redirect('states/' . $id . '/edit')->with('flash_message', 'State details successfully added.');
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
        $state = States::find($id);
        return view('edit_state', compact('state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $affectedRows = States::where('id', '=', $id)->update(['state_name' => Input::get('edit_state_name')]);
        return redirect('states/' . $id . '/edit')->with('flash_message', 'State details successfully modified.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //        if(Auth::user()->password == Hash::make(Input::get('password'))){
        $delete_state = States::find($id)->delete();
        return redirect('states')->with('flash_message', 'State details successfully deleted.');
        //        }
    }

}
