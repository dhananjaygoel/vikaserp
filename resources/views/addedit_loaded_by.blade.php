@extends('layouts.master')
@section('title','Loaded By')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('loaded-by')}}">Loaded By</a></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        @if(isset($loader))
                            {!! Form::open(array('id'=>'edit_loaded_by','method'=>'put','action'=>array('LoadByController@update',$loader->id)))!!}
                        @else
                            {!! Form::open(array('id'=>'add_loaded_by','method'=>'post','action'=>'LoadByController@store'))!!}
                        @endif
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors) > 0)
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
                                <label for="first_name">First Name <span class="mandatory">*</span></label>
                                <input id="first_name" class="form-control" placeholder="First Name" name="first_name" value="{{isset($loader->first_name) ? $loader->first_name : Input::old('first_name')}}" type="text" maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" class="form-control" placeholder="Last Name" name="last_name" value="{{isset($loader->last_name) ? $loader->last_name : Input::old('last_name')}}" type="text" maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile number " name="mobile_number" value="{{isset($loader->phone_number) ? $loader->phone_number : Input::old('mobile_number')}}" type="tel" onkeypress=" return numbersOnly(this,event,false,false);" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" class="form-control" placeholder=" Password" name="password" value="" type="password">
                            </div> 
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input id="confirm_password" class="form-control" placeholder="Confirm Password" name="confirm_password" value="" type="password">
                            </div>                            
                            <div class="clearfix"></div>
                            <hr>
                            <div>
                                <input type="submit" name="submit"  value="Submit" class="btn btn-primary form_button_footer" />
                                <a href="{{url('loaded-by')}}" class="btn btn-default form_button_footer">Back</a>
                            </div>
                            <div class="clearfix"></div>
                        <!--</form>-->
                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop