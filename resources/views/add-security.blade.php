@extends('layouts.master')
@section('title','Add Security')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('security')}}">Security</a></li>
                    <li class="active"><span>Add Security</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Add Security</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Add IP Address </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        @if(Session::has('error'))
                        <div class="alert alert-warning">
                            <ul>
                                <li> {{Session::get('error')}}</li>
                            </ul>
                        </div>
                        @endif
                        <hr>
                        <form method="POST" action="{{url('security')}}" accept-charset="UTF-8" id="" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">

                            <div class="form-group">
                                <label for="ip">IP Address</label>
                                <input id="ip_address" class="form-control" placeholder="IP Address" name="ip_address" value="{{Input::old('ip_address')}}" type="tel">
                            </div>
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('security')}}" class="btn btn-default form_button_footer">Back</a>
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


