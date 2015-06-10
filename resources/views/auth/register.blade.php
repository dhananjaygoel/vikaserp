@extends('app')

@include('layouts.includes')

<!-- Favicon -->

<body id="login-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div id="login-box">
                    <div id="login-box-holder">
                        <div class="row">
                            <div class="col-xs-12">
                                <header id="login-header">
                                    <div id="login-logo">
                                        {!! HTML::image('/resources/assets/img/logo.png' , 'Logo') !!}
                                        
                                    </div>
                                </header>


                                @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                                @endif

                                @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                <div id="login-box-inner" class="with-heading">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" placeholder="First Name">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="text" class="form-control" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="Mobile Number">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number">
                        </div>
                        
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <select  class="form-control" name="role_id" value="{{ old('role_id') }}">
                                    <option value="-1">Select User Role</option>
                                    <option value="1">Admin</option>
                                    <option value="2">Sales Staff</option>
                                    <option value="3">Delivery Staff</option>
                                </select>
                        </div>
                        
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Enter password">
                        </div>
                        
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
                           <input type="password" class="form-control" name="password_confirmation" placeholder="Re-enter password">
                        </div>
                        
                        
                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
              
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>




</body>
</html>
