<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomer;
use View;
use Hash;
use Auth;
use App;
use Redirect;
use App\User;
use Input;
use Validator;
use App\CollectionUser;
use App\DeliveryLocation;
use Maatwebsite\Excel\Facades\Excel;
use App\Territory;
use App\TerritoryLocation;
use Response;

class CollectionUserController extends Controller {

    public function __construct() {
        date_default_timezone_set("Asia/Calcutta");
        //Check authorization of user with current ip address
        $this->middleware('validIP');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0) {
            return Redirect::to('/')->with('error', 'You do not have permission.');
        }
        $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $search_field = Input::get('search');
        $location_id = Input::get('location');
        $territory_id = Input::get('territory_filter');
        $loc_arr = [];
        $territory_arr = [];
        $collection_users = User::with('locations.location_data');
        if (isset($search_field) && !empty($search_field)) {
            $collection_users->where(function($query) use ($search_field) {
                $query->where('first_name', 'like', '%' . $search_field . '%');
                $query->orwhere('last_name', 'like', '%' . $search_field . '%');
                $query->orwhere('mobile_number', 'like', '%' . $search_field . '%');
                $query->orwhere('email', 'like', '%' . $search_field . '%');
            });
        }
        if (isset($territory_id) && !empty($territory_id)) {
            $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
            foreach ($territory_locations as $loc) {
                if (!in_array($loc->teritory_id, $loc_arr)) {
                    array_push($territory_arr, $loc->teritory_id);
                }
                array_push($loc_arr, $loc->location_id);
            }
            $collection_users->whereHas('locations', function($query) use ($territory_arr) {
                $query->whereIn('teritory_id', $territory_arr);
            });
            $locations = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
//            $delivery_location = DeliveryLocation::whereIn('id',$loc_arr)->orderBy('area_name', 'ASC')->get();
        }
        if (isset($location_id) && !empty($location_id)) {
            $collection_users->whereHas('locations', function($query) use ($location_id) {
                $query->where('location_id', $location_id);
            });
        }
        $collection_users = $collection_users->where('role_id', '=', 6)->orderBy('created_at', 'DESC')->paginate(20);
        $collection_users->setPath('account');
        $territories = Territory::orderBy('created_at', 'DESC')->get();
        return View::make('collection_user.index', array('users' => $collection_users, 'locations' => $locations, 'territories' => $territories));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $territories = Territory::orderBy('created_at', 'DESC')->get();
        return View::make('collection_user.create', array('locations' => $delivery_locations, 'territories' => $territories));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0) {
            return Redirect::to('/')->with('error', 'You do not have permission.');
        }
        $data = array(
            "first_name" => Input::get('first_name'),
            "last_name" => Input::get('last_name'),
            "mobile_number" => Input::get('mobile_number'),
            "email" => Input::get('email'),
            "password" => Input::get('password'),
            "password_confirmation" => Input::get('password_confirmation'),
            "location" => Input::get('location'),
            "territory" => Input::get('territory')
        );
        $CLocation = new CollectionUser();

        if ($CLocation->user_validate($data)) {
            $Users_data = new User();
            $Users_data->role_id = 6;
            $Users_data->first_name = Input::get('first_name');
            $Users_data->last_name = Input::get('last_name');
            $Users_data->mobile_number = Input::get('mobile_number');
            $Users_data->email = Input::get('email');
            $Users_data->password = Hash::make(Input::get('password'));
            if ($Users_data->save()) {
                $locations = Input::get('location');
                $territory_id = Input::get('territory');
                foreach ($locations as $loc) {
                    if (isset($loc)) {
                        $CLocation = new CollectionUser();
                        $CLocation->user_id = $Users_data->id;
                        $CLocation->location_id = $loc;
                        $CLocation->teritory_id = $territory_id;
                        $CLocation->save();
                    }
                }
                return redirect('account')->with('flash_message', 'User details successfully added.');
            } else {
                return redirect('account')->with('wrong', 'Unable to store user at this moment');
            }
        } else {
            return redirect('account/create')
                            ->withErrors($CLocation->getvalidatorobj())
                            ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        if (Auth::user()->hasOldPassword()) {
            return redirect('change_password');
        }
        if (Auth::user()->role_id != 0) {
            return Redirect::to('/')->with('error', 'You do not have permission.');
        }
        $user = User::with('locations')->find($id);
        $territories = Territory::orderBy('created_at', 'DESC')->get();
        if ($user) {
            return View::make('collection_user.show', array('user' => $user, 'territories' => $territories));
        } else {
            
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $data = User::where("id", $id)->where('role_id', 6)->with('locations')->get();
        $delivery_locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        $territories = Territory::orderBy('created_at', 'DESC')->get();
        return View::make('collection_user.create', array('locations' => $delivery_locations, 'territories' => $territories, 'data' => $data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $user = User::where('id', $id);
        if (isset($user)) {
            $updateuser_rules = array(
                'first_name' => 'required|min:2|max:100',
                'last_name' => 'required|min:2|max:100',
                'email' => 'required|email|unique:users,email,' . $id,
                'mobile_number' => 'integer|digits_between:10,15|required|unique:users,mobile_number,' . $id,
                'location' => 'required|array|min:1',
                'territory' => 'required'
            );
            if (Input::has('password')) {
                $input_password['password'] = Input::get('password');
                $input_password['password_confirmation'] = Input::get('password_confirmation');

                $validation1 = Validator::make($input_password, User::$update_password);

                if ($validation1->fails()) {
                    return Redirect::back()->withErrors($validation1);
                } else {
                    $user_data['password'] = Hash::make(Input::get('password'));
                }
            }
            $v = Validator::make(Input::all(), $updateuser_rules);
            if ($v->fails()) {
                return back()->withErrors($v)->withInput();
            } else {
                if (Input::has('password') && Input::get('password') != "") {
                    $user_res = User::where('id', $id)->update(['first_name' => Input::get('first_name'), 'last_name' => Input::get('last_name'), 'mobile_number' => Input::get('mobile_number'), 'email' => Input::get('email'), 'password' => $user_data['password']]);
                } else {
                    $user_res = User::where('id', $id)->update(['first_name' => Input::get('first_name'), 'last_name' => Input::get('last_name'), 'mobile_number' => Input::get('mobile_number'), 'email' => Input::get('email')]);
                }

                if ($user_res) {
                    $locations = Input::get('location');
                    $territory_id = Input::get('territory');
                    $del_res = CollectionUser::where('user_id', '=', $id)->delete();
                    foreach ($locations as $loc) {
                        if (isset($loc)) {
                            $collectionuser = new CollectionUser();
                            $collectionuser->user_id = $id;
                            $collectionuser->location_id = $loc;
                            $collectionuser->teritory_id = $territory_id;
                            $collectionuser->save();
                        }
                    }
                }
                return redirect('account')->with('flash_message', 'User details successfully updated.');
            }
        } else {
            return back()->with('flash_message', 'Error while updating records please try again later')->withInput();
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
            return Redirect::to('orders')->with('wrong', 'You do not have permission.');
        }
        $password = Input::get('password');
        if ($password == '') {
            return back()->with('wrong', 'Please enter your password');
        }

        $current_user = User::find(Auth::id());
        if (Hash::check($password, $current_user->password)) {
            $del_res = User::destroy($id);
            if ($del_res) {
                CollectionUser::where('id', $id)->delete();
            }
            return redirect('account')->with('flash_message', 'Record deleted successfully.');
        } else {
            return back()->with('wrong', 'Invalid password');
        }
    }

    public function export_collection_users() {
        $search_field = Input::get('search');
        $location_id = Input::get('location');
        $territory_id = Input::get('territory');
        $loc_arr = [];
        $territory_arr = [];
        $collection_users = User::with('locations.location_data')->where('role_id', '=', 6);
        if (isset($search_field) && !empty($search_field)) {
            $collection_users->where(function($query) use ($search_field) {
                $query->where('first_name', 'like', '%' . $search_field . '%');
                $query->orwhere('last_name', 'like', '%' . $search_field . '%');
                $query->orwhere('mobile_number', 'like', '%' . $search_field . '%');
                $query->orwhere('email', 'like', '%' . $search_field . '%');
            });
        }
        if (isset($location_id) && !empty($location_id)) {
            $collection_users->whereHas('locations', function($query) use ($location_id) {
                $query->where('location_id', $location_id);
            });
        }
        if (isset($territory_id) && !empty($territory_id)) {
            $territory_locations = TerritoryLocation::where('teritory_id', '=', $territory_id)->get();
            foreach ($territory_locations as $loc) {
                if (!in_array($loc->teritory_id, $loc_arr)) {
                    array_push($territory_arr, $loc->teritory_id);
                }
                array_push($loc_arr, $loc->location_id);
            }
            $collection_users->whereHas('locations', function($query) use ($territory_arr) {
                $query->whereIn('teritory_id', $territory_arr);
            });
        }
        $collection_users = $collection_users->where('role_id', '=', 6)->orderBy('created_at', 'DESC')->get();

        $excel_name = 'Collectionuser-' . date('dmyhis');

        Excel::create($excel_name, function($excel) use($collection_users) {
            $excel->sheet('account', function($sheet) use($collection_users) {
                $sheet->loadView('excelView.collection_user.export_collection_user', array('users' => $collection_users));
            });
        })->export('xls');
    }

    public function get_territory_locations(Request $request) {
        $teritory_id = $request->input('teritory_id');
        $loc_arr = [];
        $territory_arr = [];
        $territory_locations = TerritoryLocation::where('teritory_id', '=', $teritory_id)->get();
        foreach ($territory_locations as $loc) {
            if (!in_array($loc->teritory_id, $loc_arr)) {
                array_push($territory_arr, $loc->teritory_id);
            }
            array_push($loc_arr, $loc->location_id);
        }
        if ($teritory_id != 0) {
            $locations = DeliveryLocation::whereIn('id', $loc_arr)->orderBy('area_name', 'ASC')->get();
        } else {
            $locations = DeliveryLocation::orderBy('area_name', 'ASC')->get();
        }
        $html = view('collection_user._territory_location')->with('locations', $locations)
                ->render();

        return Response::json(['success' => true, 'html' => $html]);
    }

}
