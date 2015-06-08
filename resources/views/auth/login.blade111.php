@extends('app')

@section('content')
@include('layouts.includes')
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div id="login-box">
                <div id="login-box-holder">
                    <div class="row">
                        <div class="col-xs-12">
                            <header id="login-header">
                                <div id="login-logo">
                                    {!! HTML::image('/resources/assets/backend/img/logo.png' , 'Logo') !!}
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
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input class="form-control" type="text" placeholder="Email address" name="email" value="{{ old('email') }}">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input type="password" class="form-control" placeholder="Password" name="password">
                                    </div>
                                    <div id="remember-me-wrapper">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="checkbox-nice">
                                                    <input type="checkbox" id="remember-me" checked="checked" name="remember" />
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
                                            <button type="submit" class="btn btn-primary col-xs-12">Login</button>
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
    @endsection