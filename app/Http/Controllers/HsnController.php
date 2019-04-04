<?php namespace App\Http\Controllers;

use App\Hsn;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class HsnController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
        $hsn = Hsn::orderBy('id','DESC')->paginate(10);
        return view('hsn', compact('hsn'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('hsn_add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, [
            'hsn_code' => 'required|unique:hsn,hsn_code',
            'gst' => 'required',
            'hsn_desc' => 'required'
        ]);

        $thickness = new Hsn();
        $thickness->hsn_code = $request->hsn_code;
        $thickness->gst = $request->gst;
        $thickness->hsn_desc = $request->hsn_desc;
        $thickness->save();

        return redirect('hsn')->with('flash_success_message', 'Hsn successfully added.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return redirect('hsn');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

        if (Auth::user()->role_id != 0) {
            return Redirect::to('gst')->with('error', 'You do not have permission.');
        }
        $hsn = Hsn::find($id);
        return view('hsn_edit', compact('hsn'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request)
	{
        if (Auth::user()->role_id != 0) {
            return Redirect::to('gst')->with('error', 'You do not have permission.');
        }

        $this->validate($request, [
            'hsn_code' => 'required|unique:hsn,hsn_code',
            'gst' => 'required',
            'hsn_desc' => 'required'
        ]);

        Hsn::where('id',$id)->update([
            'hsn_code' => $request->hsn_code,
            'hsn_desc' => $request->hsn_desc,
            'gst' => $request->gst
        ]);

        return redirect('hsn')->with('flash_success_message', 'Hsn updated successfully');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if (Auth::user()->role_id != 0) {
            return Redirect::to('gst')->with('error', 'You do not have permission.');
        }

        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            Hsn::find($id)->delete();
            return redirect('hsn')->with('flash_success_message', 'Gst details successfully deleted.');
        } else
            return redirect('hsn')->with('flash_message', 'Please enter a correct password');
	}

}
