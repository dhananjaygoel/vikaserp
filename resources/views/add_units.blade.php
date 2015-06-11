@extends('layouts.master')
@section('title','Add Units')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('unit')}}">Unit</a></li>
                    <li class="active"><span>Add Unit</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Unit</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Add Unit </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        <form method="POST" action="{{URL::action('UnitController@store')}}">

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
                                <label for="unit_name">Unit</label>
                                <input id="unit_name" class="form-control" placeholder="Unit" name="unit_name" value="{{Input::old('unit_name')}}" type="text">
                            </div>
                            <hr>
                            <div >
                                <!--<button type="submit" class="btn btn-primary form_button_footer" >Submit</button>-->
                                <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                                <a href="{{URL::to('unit')}}" class="btn btn-default form_button_footer">Back</a>
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