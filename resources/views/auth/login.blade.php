<?php echo
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Content-Type: text/html');?>
@extends('app')
@section('title','Sign In')
@include('layouts.includes')

<!-- Favicon -->
<meta http-equiv="cache-control" content="private, max-age=0, no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
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

                                <div id="login-box-inner">
                                    <form action="{{ url('/auth/login') }}" method="POST">
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
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                            <input class="form-control" id="mobile_number" name="mobile_number" type="text" placeholder="Mobile Number">
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-key"></span></span>
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
                                                <button type="submit" class="btn btn-success col-xs-12" >Login</button>
                                            </div>
                                        </div>
                                         
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
<div class="row">
    <div class="col-xs-3"></div>
                                            <div class="col-xs-6">
                                        <a class ="btn btn-default btn-primary btn-success website col-xs-12" href="http://www.vikasinfra.com/" target="_blank">Website </a>
  </div>
                                        </div>

                </div>
            </div>
        </div>
    </div>




</body>
</html>
<script language="javascript" >
history.go(1); /* Tested for IE Back Button) */
</script>