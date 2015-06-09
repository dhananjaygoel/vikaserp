<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Security;
use Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use App\User;
use Illuminate\Support\Facades\Redirect;
class SecurityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

                $sec = Security::paginate(10);
                $sec->setPath('security');
		return view('security',  compact('sec'));
	}
        
        //Show security add form
        public function get_security(){
            
        }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('add-security');
	}
        
        
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{   
		$security = new Security;
                $ipaddress= $request->input('ip_address');
                $data = array(
                    'ip_address' => $ipaddress
                );
                
                $validator = Validator::make($data, ['ip_address' => 'required|unique:security|ip']);
                if ($validator->fails()) {
                    return Redirect::back()->with('error','Please enter valid IP Address');
                }
                $security->ip_address = $ipaddress;
                $security->save();
                $sec = Security::paginate(10);
                $sec->setPath('security');

		return view('security',  compact('sec'))->with('message','One record added to Security.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$security = Security::find($id);
                
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return or_wheResponse
	 */
	public function edit($id)
	{

		$security = Security::find($id);
//                $ipaddress= Input::input('ip_address');
                return view('edit-security',compact('security'));
                
	}

	/**
	 * Update the specified ronsubmit="validation('password_{{$security->id}}')"esource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,$id)
	{
                $ipaddress = $request->input('ip_address');
                //parameter for validation
                $data = array(
                    'ip_address' => $ipaddress
                );
                //Validation rules for ip address
                $validator = Validator::make($data, ['ip_address' => 'required|unique:security|ip']);
                if ($validator->fails()) {
                    return Redirect::back()->with('error','Please enter valid IP Address');
                }
		Security::where('id','=',$id)->update(array('ip_address'=>$ipaddress));   
                
                $sec = Security::paginate(10);
                $sec->setPath('security');

		return view('security',  compact('sec'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
                $mobile_number= Input::get('mobile_number');
                $pass = Input::get('password');
                if (Auth::attempt(['mobile_number' => $mobile_number, 'password' => $pass]))
                {
                    $security = Security::where('id',$id);
                    $security->delete();
                    $sec = Security::paginate(10);
                    $sec->setPath('security');
                    return redirect('security')->with('message','One record is deleted.');
                }else{
                    $sec = Security::paginate(10);
                $sec->setPath('security');
                    return view('security',  compact('sec'))->with('error','Password entered is not valid.');
                }
            
	}

}
