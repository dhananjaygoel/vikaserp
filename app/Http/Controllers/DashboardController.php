<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Auth;
use App\Order;
use App\Inquiry;

class DashboardController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        
        $order = Order::all()->count();
        $pending_order = Order::where('order_status','pending')->count();
        $inquiry = Inquiry::all()->count();
        $pending_inquiry = Inquiry::where('inquiry_status','pending')->count();
        
        return view('dashboard', compact('order','pending_order','inquiry','pending_inquiry'));
    }

    public function logout() {
        
        Auth::logout(); // logout user
        return redirect(\URL::previous());
        
    }
    
    public function homeredirect(){
        
        return redirect('dashboard');
        
    }

}
