@extends('layouts.master')
@section('title','Add Customer Manager')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customer_manager')}}">Customer Manager</a></li>
                    <li class="active"><span>Add Customer Manager</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{url('customer_manager')}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors->all()) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="manager_name">Name<span class="mandatory">*</span></label>
                                <input id="manager_name" class="form-control" placeholder="Manager Name" name="manager_name" value="{{ Input::old('manager_name')}}" type="text">
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone number<span class="mandatory">*</span></label>
                                <input id="phone_number" class="form-control" placeholder="Phone number " name="phone_number" value="{{ Input::old('phone_number')}}" type="tel">
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('customer_manager')}}" class="btn btn-default form_button_footer">Back</a>
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