@extends('layouts.master')
@section('title','Add Thickness')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('thickness')}}">Thickness</a></li>
                        <li class="active"><span>Add Thickness</span></li>
                    </ol>
                    <div class="clearfix">
                        <h1 class="pull-left">Thickness</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <header class="main-box-header clearfix">
                            <h2><i class="fa fa-user"></i> &nbsp; Add Thickness </h2>
                        </header>
                        <div class="main-box-body clearfix">
                            <hr>
                            <form id="" method="POST" action="{{URL::action('ThicknessController@store')}}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                @if (count($errors) > 0)
                                    <div role="alert" class="alert alert-warning">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <label for="state_name">Thickness<span class="mandatory">*</span></label>
                                    <input id="state_name" class="form-control" placeholder="Thickness" name="thickness" value="{{Input::old('thickness')}}" type="number">
                                </div>


                                <div class="form-group">
                                    <label for="difference">Difference<span class="mandatory">*</span></label>
                                    <input id="difference" class="form-control" placeholder="Difference" name="difference" value="{{Input::old('difference')}}" type="number">
                                </div>

                                <hr/>

                                <div>
                                    <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                                    <a href="{{URL::to('thickness')}}" class="btn btn-default form_button_footer">Back</a>
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