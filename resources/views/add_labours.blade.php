@extends('layouts.master')
@section('title','Add Labour')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('performance/labours')}}">Labours</a></li>
                    <li class="active"><span>Add Labour</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form id="" method="POST" action="{{url('performance/labours')}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors->all()) > 0)
                            <div role="alert" class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">&times;</span></button>
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
                                <label for="first_name">First Name<span class="mandatory">*</span></label>
                                <input id="first_name" class="form-control" placeholder="First Name" name="first_name" value="{{ Input::old('first_name')}}" type="text" maxlength="30" oninput="validateAlpha(this);">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name<span class="mandatory">*</span></label>
                                <input id="last_name" class="form-control" placeholder="Last Name" name="last_name" value="{{ Input::old('last_name')}}" type="text" maxlength="30" oninput="validateAlpha(this);">
                            </div>


                            <div class="form-group">
                                <label for="phone_number">Phone number<span class="mandatory">*</span></label>
                                <input id="phone_number" class="form-control" placeholder="Phone number " name="phone_number" value="{{ Input::old('phone_number')}}" type="tel" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" >
                            </div>

                            <div class="form-group ">
                                <label for="labour_type">Type<span class="mandatory">*</span></label>
                                <select class="form-control" name="labour_type" id="labour_type">
                                  
                                    <option value="sale">Sale</option>
                                    <option value="purchase">Purchase</option>
                                    <!--<option value="both">Both</option>-->
                                    
                                </select>
                            </div>
                            
                           
                            <br>


                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('performance/labours')}}" class="btn btn-default form_button_footer">Back</a>
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