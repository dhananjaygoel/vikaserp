@extends('layouts.master')
@section('title','Change Password')
@section('content')

<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Change Password</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Change Password</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-envelope-o"></i> &nbsp; Change Password</h2>
                    </header>            

                    <div class="main-box-body clearfix">
                        <hr>
                        @if(Session::has('message'))
                        <div class="alert alert-info">                                        
                            {{Session::get('message')}}                           
                        </div>
                        @endif
                        @if (Session::has('messages'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('messages') }}</div>
                        @endif
                        
                        <form method="POST" action="{{ url('change_password') }}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="alert alert-warning">                                        
                                <ul>                    
                                    <li> {{Session::get('error')}}</li>
                                </ul>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="old_password">Old Password</label>
                                <input id="old_password" class="form-control" placeholder="Old Password" name="old_password" value="" type="password">
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input id="password" class="form-control" placeholder="New Password" name="new_password" value="" type="password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input id="confirm_password" class="form-control" placeholder="Confirm Password" name="new_password_confirmation" value="" type="password">
                            </div>




                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
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


