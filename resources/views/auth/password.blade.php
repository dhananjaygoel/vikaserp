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
                                    <h4>Forgot your password?</h4>
                                    <p>
                                        Enter your email address to recover your password.
                                    </p>
                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="input-group reset-pass-input">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address">
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success col-xs-12">Reset password</button>
                                            </div>
                                            <div class="col-xs-12">
                                                <br/>
                                                <a href="{{url('auth/login')}}" id="login-forget-link" class="forgot-link col-xs-12">Back to login</a>
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
