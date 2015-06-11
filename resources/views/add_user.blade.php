@extends('layouts.master')
@section('title','Users')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/users">Users</a></li>
                    <li class="active"><span>Add Users</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Users</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Add User </h2>
                    </header>             
                    <div class="main-box-body clearfix">
                        <hr>
                        <form method="POST" action="{{URL::action('UsersController@store')}}" accept-charset="UTF-8" >

                            @if (count($errors) > 0)
                            <div class="alert alert-warning">
                                @foreach ($errors->all() as $error)
                                {{ $error }}
                                @endforeach
                            </div>
                            @endif                            
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label for="role">User Type*</label>
                                <select class="form-control" name="type" id="add_user_type">
                                    <option value="" selected="" disabled="">Select User Type</option>
                                    @foreach($roles as $role_data)
                                    <option value="{{$role_data->role_id}}">{{$role_data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name*</label>
                                <input id="first_name" class="form-control" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name*</label>
                                <input id="last_name" class="form-control" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" type="text">
                            </div>                   
                            <div class="form-group">
                                <label for="Phone_number">Phone number </label>
                                <input id="Phone_number" class="form-control" placeholder="Phone number " name="telephone_number" value="{{ old('telephone_number') }}" type="text">
                            </div>

                            <div class="form-group">
                                <label for="mobile_number">Mobile Number*</label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number') }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" class="form-control" placeholder="Email Id" name="email" value="{{ old('email') }}" type="email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password*</label>
                                <input id="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}" type="password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password*</label>
                                <input id="password_confirmation" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}" type="password">
                            </div>    
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url()}}/users" class="btn btn-default form_button_footer">Back</a>
                            </div>
                            <div class="clearfix"></div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection