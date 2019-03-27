@extends('layouts.master')
@section('title','Edit Thickness')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('thickness')}}">Thickness</a></li>
                        <li class="active"><span>Edit Thickness</span></li>
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
                            <h2><i class="fa fa-user"></i> &nbsp; Edit Thickness </h2>
                        </header>
                        <div class="main-box-body clearfix">
                            <hr>
                            {!! Form::open(array('method'=>'PUT','url'=>url('thickness',$state->id), 'id'=>''))!!}
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
                            @if (Session::has('flash_message'))
                                <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                            @endif

                            <input type="hidden" name="id" value="{{$state->id}}">


                            <div class="form-group">
                                <label for="state_name">Thickness<span class="mandatory">*</span></label>
                                <input id="state_name" class="form-control" placeholder="Thickness" name="thickness" value="{{$state->thickness}}" type="number">
                            </div>


                            <div class="form-group">
                                <label for="difference">Difference<span class="mandatory">*</span></label>
                                <input id="difference" class="form-control" placeholder="Difference" name="difference" value="{{$state->diffrence}}" type="number">
                            </div>

                            <hr>
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