@extends('layouts.master')
@section('title','Loaded By')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('performance/loaded-by')}}">Loaded By</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <div class="form-group clearfix">
                            <div class="col-md-5 col-sm-12">
                                <label for="first_name">First Name:</label>
                            </div>
                            <div class="col-md-7 col-sm-12" >{{$loader->first_name}}</div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-5 col-sm-12">
                                <label for="last_name">Last Name:</label>
                            </div>
                            <div class="col-md-7 col-sm-12" >{{$loader->last_name}}</div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-5 col-sm-12">
                                <label for="mobile_number">Mobile Number:</label>
                            </div>
                            <div class="col-md-7 col-sm-12" >{{$loader->phone_number}}</div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-5 col-sm-12">
                                <label for="loader_type">Type:</label>
                            </div>
                            <div class="col-md-7 col-sm-12" >{{ucwords($loader->type)}}</div>
                        </div>
                        <div class="clearfix"></div>
                        <div>
                            <a href="{{url('performance/loaded-by')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop