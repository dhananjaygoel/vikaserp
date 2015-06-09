<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Security;
use Validator;
use Illuminate\Support\Facades\Input;
use Auth;
use App\User;
class SecurityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
//                echo 'comes here';
//                exit;
                $sec = Security::all();
//                
//                echo count($security);
//                echo '<pre>';
//                print_r($security->toArray());
//                echo '</pre>';
//                exit;
		return view('security',  compact('sec'));//, compact($security))->with('msg','One record is added');
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
                
//                $validator = Validator::make($ipaddress,'required');
//                if ($validator->fails()) {
//                    return view('security')->withError('Please enter Security IP Address');
//                }
                $security->ip_address = $ipaddress;
                $security->save();
                return view('security')->with('message','One record added to Security.');
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
//            echo 'comes here'.$id;
//            exit;
		$security = Security::find($id);
//                $ipaddress= Input::input('ip_address');
                return view('edit-security',compact('security'));
                
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
//            echo 'comes here';
            
		Security::where('id','=',$id)->update('ip_address','=',$ipaddress); 
                
                return view('security');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
//                echo 'id '.$id." pass ";
//		$security = Security::where('id',$id);
//                $security->delete();
            
                $mobile_number= Input::get('mobile_number');
                $pass = Input::get('password');
//                echo $mobile_number.' '.$pass;
//                exit;
//                    $user = Auth::user();
//                    echo '<pre>';
//                    print_r($user);
//                    echo '</pre>';
//                    exit
                    $password = bcrypt($pass);
                    $users = User::where('mobile_number',$mobile_number)->where('password',$password);
                    foreach($users as $user)
                    {
                        echo 'found';
                    }
//                    if($password == $user['password']){
//                        echo 'same password';
//                        exit
//                    }exit{
//                        echo 'wrong password';
//                        exit
//                    }
                    
//                    echo $pass;
//                    exit;
//                $user->password = bcrypt($data['password']);
//                Security::destroy($id);
//                return redirect('security')->with('message','One record deleted.');
	}

}
