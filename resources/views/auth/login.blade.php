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
                                        <img src="{{ asset('assets/backend/img/logo.png')}}" alt=""/>
                                    </div>
                                </header>
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
                                <div id="login-box-inner">
                                    <form action="{{ url('/auth/login') }}" method="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input class="form-control" id="mobile_number" name="mobile_number" type="text" placeholder="Mobile Number">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                        </div>
                                        <div id="remember-me-wrapper">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="checkbox-nice">
                                                        <input type="checkbox" id="remember-me" checked="checked" />
                                                        <label for="remember-me">
                                                            Remember me
                                                        </label>
                                                    </div>
                                                </div>
                                                <a href="{{ url('/password/email') }}" id="login-forget-link" class="col-xs-6">
                                                    Forgot password?
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <button type="submit" class="btn btn-success col-xs-12" onClick="location.href = 'index.html'">Login</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="login-box-footer">
                        <div class="row">
                            <div class="col-xs-12">
                                Do not have an account? 
                                <a href="{{ url('/auth/register') }}">
                                    Register now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</body>
</html>
