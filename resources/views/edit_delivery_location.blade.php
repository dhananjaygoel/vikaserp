@extends('layouts.master')
@section('title','Edit Location')
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
                        <h2><i class="fa fa-user"></i> &nbsp; Edit Location </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('method'=>'PUT','url'=>url('location',$delivery_location->id), 'id'=>'edit_location_form'))!!}

                        <input type="hidden" name="id" value="{{$delivery_location->id}}">
                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-warning">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif

                        <div class="form-group">
                            <label for="state">State Name</label>
                            <select name="state" class="form-control">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                @if($state->id == $delivery_location->state_id)
                                <option value="{{$state->id}}" selected="selected">{{$state->state_name}}</option>
                                @else
                                <option value="{{$state->id}}">{{$state->state_name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city">City Name</label>
                            <select name="city" class="form-control">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                @if($city->id == $delivery_location->city_id)
                                <option value="{{$city->id}}" selected="selected">{{$city->city_name}}</option>
                                @else
                                <option value="{{$city->id}}">{{$city->city_name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="area_name">Area Name</label>
                            <input id="location_name" class="form-control" placeholder="Area Name" name="area_name" value="{{$delivery_location->area_name}}" type="text">
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