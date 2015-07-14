@extends('layouts.master')
@section('title','Add Location')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('location')}}">Location</a></li>
                    <li class="active"><span>Add Location</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Location</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Add Location </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        <form id="onenter_prevent" method="POST" action="{{URL::action('DeliveryLocationController@store')}}">

                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label for="state_name">State Name<span class="mandatory">*</span></label>
                                <select name="state" class="form-control" onchange="fetch_city();" id="select_state">
                                    <option value="" selected="">Select State</option>
                                    @foreach($states as $state)
                                    <option value="{{$state->id}}">{{$state->state_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city_name">City Name<span class="mandatory">*</span></label>
                                <select name="city" class="form-control" id="select_city">
                                    <option value="" selected="">Select City</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="area_name">Area Name<span class="mandatory">*</span></label>
                                <input id="area_name" class="form-control" placeholder="Area Name" name="area_name" value="{{Input::old('area_name')}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="area_name">Difference</label>
                                <input id="difference" class="form-control" placeholder="Difference" name="difference" value="{{Input::old('difference')}}" type="text">
                            </div>
                            <hr>
                            <div>
                                <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                                <a href="{{URL::to('location')}}" class="btn btn-default form_button_footer">Back</a>
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
@stop