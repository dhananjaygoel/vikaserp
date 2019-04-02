<?php namespace App\Http\Controllers;

use App\Gst;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class GstController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $gst = Gst::orderBy('id','DESC')->paginate(10);
        return view('gst', compact('gst'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('gst_add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
        $this->validate($request, [
            'gst' => 'required|integer',
            'sgst' => 'required|integer',
            'cgst' => 'required|integer',
            'igst' => 'required|integer',
        ]);

        $thickness = new Gst();
        $thickness->gst = $request->gst;
        $thickness->sgst = $request->sgst;
        $thickness->cgst = $request->cgst;
        $thickness->igst = $request->igst;
        $thickness->save();


        return redirect('gst')->with('flash_success_message', 'GSt successfully added.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
       return \redirect('gst');
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
        $gst = Gst::find($id);
        return view('gst_edit', compact('gst'));
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
            'gst' => 'required|integer',
            'sgst' => 'required|integer',
            'cgst' => 'required|integer',
            'igst' => 'required|integer',
        ]);

        Gst::where('id',$id)->update([
            'gst' => $request->gst,
            'sgst' => $request->sgst,
            'cgst' => $request->cgst,
            'igst' => $request->igst
        ]);

        return redirect('gst')->with('flash_success_message', 'Gst updated successfully');
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
            Gst::find($id)->delete();
            return redirect('gst')->with('flash_success_message', 'Gst details successfully deleted.');
        } else
            return redirect('gst')->with('flash_message', 'Please enter a correct password');
	}

}
