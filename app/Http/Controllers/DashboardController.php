<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        return view('dashboard');
    }

    public function logout() {


        Auth::logout(); // logout user
        return redirect(\URL::previous());
    }
    public function homeredirect(){
        return redirect('dashboard');
    }

}
