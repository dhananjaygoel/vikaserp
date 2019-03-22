@extends('layouts.master')
@section('title','Edit State')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('states')}}">State</a></li>
                    <li class="active"><span>Edit State</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">States</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Edit State </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('method'=>'PUT','url'=>url('states',$state->id), 'id'=>''))!!}
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
                            <label for="state_name">State<span class="mandatory">*</span></label>
                            <input id="state_name" class="form-control" placeholder="State Name" name="state_name" value="{{$state->state_name}}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="state_name">Local State<span class="mandatory">*</span></label>
                            <select class="form-control"  name="local_state" required>
                                <option value="0" {{($state->local_state==0)?'selected':''}}> No </option>
                                <option value="1" {{($state->local_state==1)?'selected':''}}> Yes </option>
                            </select>
                        </div>

                        <hr>
                        <div>
                            <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                            <a href="{{URL::to('states')}}" class="btn btn-default form_button_footer">Back</a>
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