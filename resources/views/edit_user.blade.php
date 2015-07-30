@extends('layouts.master')
@section('title','Edit Users')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/users">Users</a></li>
                    <li class="active"><span>Edit User</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <span id="validation_error"></span>
                    <div class="main-box-body clearfix">
                        {!!Form::open(array('method'=>'PUT','url'=>url('users/'.$user_data['id']),'id'=>'onenter_prevent'))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        @if (count($errors) > 0)
                        <div class="alert alert-warning">                          
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach                        
                        </div>
                        @endif  

                        @if (Session::has('email'))
                        <div class="alert alert-warning">
                            {{Session::get('email')}}                            
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="role">User Type<span class="mandatory">*</span></label>
                            <select class="form-control" name="user_type" id="add_user_type">
                                <option value="" selected="" disabled="">Select User Type</option>
                                @foreach($roles as $role_data)
                                <option <?php if ($role_data->role_id == $user_data['role_id']) echo 'selected="selected"'; ?> value="{{$role_data->role_id}}">{{$role_data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name<span class="mandatory">*</span></label>
                            <input id="first_name" class="form-control" placeholder="First Name" name="first_name" value="{{$user_data['first_name']}}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name<span class="mandatory">*</span></label>
                            <input id="last_name" class="form-control" placeholder="Last Name" name="last_name" value="{{$user_data['last_name']}}" type="text">
                        </div>                                               
                        <div class="form-group">
                            <label for="Phone_number">Phone number </label>
                            <input id="Phone_number" class="form-control" placeholder="Phone number " name="telephone_number" value="{{$user_data['phone_number']}}" type="tel">
                        </div>

                        <div class="form-group">
                            <label for="mobile_number">Mobile Number<span class="mandatory">*</span></label>
                            <input id="mobile_number" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{$user_data['mobile_number']}}" type="tel">
                        </div>

                        <div class="form-group">
                            <label for="email">Email<span class="mandatory">*</span></label>
                            <input id="email" class="form-control" placeholder="Email Id" name="email" value="{{$user_data['email']}}" type="email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password<span class="mandatory">*</span></label>
                            <input id="password" class="form-control" placeholder="Password" name="password" value="" type="password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password<span class="mandatory">*</span></label>

                            <input id="password_confirmation" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="" type="password">
                        </div>  
                        <hr>
                        <div >
                            <button type="submit" class="btn btn-primary form_button_footer">Submit</button>
                            <a href="{{url()}}/users" class="btn btn-default form_button_footer">Back</a>
                        </div>                            
                        <div class="clearfix"></div>
                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection