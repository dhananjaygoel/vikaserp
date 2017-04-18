<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Territory;
use App\DeliveryLocation;
use App\TerritoryLocation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Hash;
use Illuminate\Support\Facades\Auth;

class TerritoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            $territories = '';
            if (Input::get('search') != '') {
                $term = '%' . Input::get('search') . '%';
                $territories = \App\Territory::where('teritory_name', 'like', $term)->orderBy('created_at', 'DESC')->paginate(20);
            } else {
                $territories = Territory::with('territorylocation')->orderBy('created_at', 'DESC')->paginate(20);
            }
            $territories->setPath('territory');
            $locations = DeliveryLocation::all();
            return view('territory.territory_index',compact('territories','locations'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            $locations = DeliveryLocation::all();
            return view('territory.add_territory')->with('locations',$locations);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{            
            $input = $request->input();
            $message = array('territory_name.required' => 'Territory Name is required',
            'location.required' => 'Location is required');

            $rules = ['territory_name' => 'required',
                'location' => 'required'];

            $validator = Validator::make($input, $rules, $message);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            
            $territory = new Territory();
            $territory->teritory_name = $request->input('territory_name');                 
            $territory->save();
            $teritory_id=$territory->id; 
            
            if(isset($teritory_id)){            
                foreach ($request->location as $loc) {
                    $territory_loc = new TerritoryLocation();
                    $territory_loc->teritory_id = $teritory_id;
                    $territory_loc->location_id = $loc;
                    $territory_loc->save();
                }
            }            
            return redirect('territory')->with('flash_success_message', 'Territory successfully added.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
            $territory= Territory::with('territorylocation')->find($id);            
            $locations = DeliveryLocation::all();
//            dd($territories);
            return view('territory.view_territory',compact('territory','locations'));            
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{                       
            $locations = DeliveryLocation::all();
            $territory = Territory::with('territorylocation')->find($id);            
            return view('territory.edit_territory')->with('territory',$territory)->with('locations',$locations);        
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
            $input = $request->input();
            $message = array('territory_name.required' => 'Territory Name is required',
            'location.required' => 'Location is required');

            $rules = ['territory_name' => 'required',
                'location' => 'required'];

            $validator = Validator::make($input, $rules, $message);
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }
            $territory= Territory::find($id);
            $territory->teritory_name = $request->input('territory_name');            
            $territory->save();    
            
            $territory_loc=  TerritoryLocation::where('teritory_id','=', $id)->get();
            foreach ($territory_loc as $loc) {
                $territory_old = TerritoryLocation::find($loc->id);                                
                $territory_old->delete();
            }
            
            foreach ($request->location as $loc) {
                $territory_loc = new TerritoryLocation();
                $territory_loc->teritory_id = $id;
                $territory_loc->location_id = $loc;
                $territory_loc->save();
            }
            
            return redirect('territory')->with('flash_success_message', 'Territory successfully modified.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, Request $request)
	{
            if(Hash::check(Input::get('model_pass'), Auth::user()->password)){                
                $territory = Territory::find($id);
                $territory->delete();
                return redirect('territory')->with('flash_success_message', 'Territory successfully deleted.');
            }else{
                return redirect('territory')->with('flash_message', 'Please enter a correct password');
            }                    
            
	}
}
