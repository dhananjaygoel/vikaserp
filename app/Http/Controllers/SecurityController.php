<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Security;
use Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\EditSecurityRequest;

class SecurityController extends Controller {

    public function __construct() {
//        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }

        $sec = Security::orderBy('created_at', 'desc')->Paginate(20);
        $sec->setPath('security');
        return view('security', compact('sec'));
    }

    //Show security add form
    public function get_security() {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        return view('add-security');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $security = new Security;
        $ipaddress = $request->input('ip_address');
        $data = array(
            'ip_address' => $ipaddress
        );

        $validator = Validator::make($data, ['ip_address' => 'required|unique:security|ip']);
        if ($validator->fails()) {
            return Redirect::back()->with('error', 'Please enter valid IP Address');
        }
        $security->ip_address = $ipaddress;
        $security->save();
        return redirect('security')->with('message', 'One record added to security');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $security = Security::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return or_wheResponse
     */
    public function edit($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $security = Security::find($id);

        return view('edit-security', compact('security'));
    }

    /**
     * Update the specified ronsubmit="validation('password_{{$security->id}}')"esource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(EditSecurityRequest $request, $id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $ipaddress = $request->input('ip_address');
        //parameter for validation
        $data = array(
            'ip_address' => $ipaddress
        );
        //Validation rules for ip address


        $security = Security::where('id', '!=', $id)->where('ip_address', $ipaddress)->count();

        if ($security > 0) {
            return Redirect::back()->with('error', 'Please enter another IP Address, current IP Address is present.');
        } else {
            Security::where('id', '=', $id)->update(array('ip_address' => $ipaddress));
            return redirect('security')->with('message', 'One record updated to security');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        if (Auth::user()->role_id != 0) {
            return Redirect::to('orders')->with('error', 'You do not have permission.');
        }
        $password = Input::get('password');
        if ($password == '') {
            return Redirect::to('security')->with('error', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());

        if (Hash::check($password, $current_user->password)) {
            $security = Security::find($id);
            $security->delete();
            return redirect('security')->with('message', 'One record is deleted.');
        } else {
            return Redirect::back()->with('error', 'Password entered is not valid.');
        }
    }

}
