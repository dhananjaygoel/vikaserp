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
        
        $units = Units::orderBy('created_at', 'desc')->Paginate(20);
        $units->setPath('unit');
        return view('units', compact('units'));
    }

    /*
     * get the unit list
     */

    public function get_units() {
        $units = Units::all();
        echo json_encode(array('units' => $units));
    }

}
