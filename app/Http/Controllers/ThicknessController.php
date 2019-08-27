<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Thickness;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ThicknessController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
	    $thickness = Thickness::orderBy('id','DESC')->paginate(10);
        return view('thickness', compact('thickness'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function create()
	{
        return view('thickness_add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$thick = $request->input('thickness');
		$diff = $request->input('difference');
        // $this->validate($request, [
        //     'thickness' => 'required|integer|unique:thickness,thickness'. ($thick ? ",$thick" : ''),
        //     'difference' => 'required|integer',($diff ? ",$diff" : ''),
		// ]);
		if(Thickness::where('thickness','=',$thick)->where('diffrence','=',$diff)->count() > 0)
		{ 
			return redirect('thickness')->with('flash_message', 'Thickness and Difference combination has already been taken.');
		}
		else
		{
			$thickness = new Thickness();
			$thickness->thickness = $request->thickness;
			$thickness->diffrence = $request->difference;
			$thickness->save();

			return redirect('thickness')->with('flash_success_message', 'Thickness successfully added.');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return redirect('thickness');
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
            return Redirect::to('thickness')->with('error', 'You do not have permission.');
        }
        $state = Thickness::find($id);
        return view('thickness_edit', compact('state'));
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
            return Redirect::to('thickness')->with('error', 'You do not have permission.');
		}
		$thick = $request->input('thickness');
		$diff = $request->input('difference');
        // $this->validate($request, [
        //     'thickness' => 'required|integer|unique:thickness,thickness',
        //     'difference' => 'required|integer',
        // ]);
		if(Thickness::where('thickness',$thick)->where('diffrence',$diff)->count() == 0)
		{
       		Thickness::where('id',$id)->update([
            	'thickness' => $request->thickness,
            	'diffrence' => $request->difference
        	]);

			return redirect('thickness')->with('flash_success_message', 'Thickness updated successfully');

		}
		else 
		{
			return redirect('thickness')->with('flash_message', 'Thickness and Difference combination has already been taken.');
		}
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
            return Redirect::to('thickness')->with('error', 'You do not have permission.');
        }

        if (Hash::check(Input::get('password'), Auth::user()->password)) {
            Thickness::find($id)->delete();
            return redirect('thickness')->with('flash_success_message', 'Thickness details successfully deleted.');
        } else
            return redirect('thickness')->with('flash_message', 'Please enter a correct password');
	}

}
