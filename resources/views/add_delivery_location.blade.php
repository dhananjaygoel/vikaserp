@extends('layouts.master')
@section('title','Add Location')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Location</span></li>
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
                        <form method="POST" action="{{URL::action('DeliveryLocationController@store')}}">

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
                                <label for="state_name">State Name</label>
                                <select name="state" class="form-control">
                                    @foreach($states as $state)
                                    <option value="{{$state->id}}">{{$state->state_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city_name">City Name</label>
                                <select name="city" class="form-control">
                                    @foreach($cities as $city)
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="location_name">Area Name</label>
                                <input id="location_name" class="form-control" placeholder="Area Name" name="location_name" value="" type="text">
                            </div>
                            <hr>
                            <div >
                                <!--<button type="submit" class="btn btn-primary form_button_footer" >Submit</button>-->
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