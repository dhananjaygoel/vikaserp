@extends('layouts.master')
@section('title','Edit City')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>City</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">City</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Edit City </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('method'=>'PUT','url'=>url('city',$city->id), 'id'=>'edit_city_form'))!!}
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
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="id" value="{{$city->id}}">
                        <div class="form-group">
                            <label for="state">State</label>
                            <select name="state" class="form-control">
                                @foreach($states as $state)
                                <option value="{{$state->id}}">{{$state->state_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="city_name">City</label>
                            <input id="city_name" class="form-control" placeholder="City" name="edit_city_name" value="{{$city->city_name}}" type="text">
                        </div>
                        <hr>
                        <div>
                            <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                            <a href="{{URL::to('city')}}" class="btn btn-default form_button_footer">Back</a>
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