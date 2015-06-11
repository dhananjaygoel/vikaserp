@extends('layouts.master')

@section('content')

            <div class="row">
                <div class="col-lg-12">

                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="breadcrumb">
                                <li><a href="#">Home</a></li>
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
                                    @if(Session::has('errors'))
                                    <div class="alert alert-danger">                                        
                                        <ul>                    
                                            @foreach($errors as $error)
                                            <li> {{$error}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    @if(Session::has('error'))
                                    <div class="alert alert-danger">                                        
                                        <ul>                    
                                            <li> {{Session::get('error')}}</li>
                                        </ul>
                                    </div>
                                    @endif
                                    @if(Session::has('message'))
                                    <div class="alert alert-success">                                        
                                        <ul>                    
                                            
                                            <li> {{Session::get('message')}}</li>
                                            
                                        </ul>
                                    </div>
                                    @endif
                                    <hr>

                                    <form method="POST" action="{{ url('change_password') }}" accept-charset="UTF-8" >
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        

                                        <div class="form-group">
                                            <label for="old_password">Old Password</label>
                                            <input id="old_password" class="form-control" placeholder="Old Password" name="old_password" value="" type="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="newpassword">New Password</label>
                                            <input id="newpassword" class="form-control" placeholder="New Password" name="newpassword" value="" type="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="confirm_password">Confirm Password</label>
                                            <input id="confirm_password" class="form-control" placeholder="Confirm Password" name="confirm_password" value="" type="password">
                                        </div>




                                        <hr>
                                        <div >
                                            <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                            <a href="{{url('change_password')}}" class="btn btn-default form_button_footer">Back</a>
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


