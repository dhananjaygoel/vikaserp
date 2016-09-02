@extends('layouts.master')
@section('title','Edit Pending Customers')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('pending_customers')}}">Pending Customers</a></li>
                    <li class="active"><span> Edit Pending Customer</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form id="" method="POST" action="{{url('pending_customers/'.$customer->id)}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input name="_method" type="hidden" value="PUT">
                            @if (count($errors) > 0)
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
                                <label for="owner_name">Customer Name<span class="mandatory"></span></label>
                                <input id="owner_name" class="form-control" placeholder="Customer Name" name="owner_name" value="{{$customer->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="contact_person">Contact Person<span class="mandatory"></span></label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person " name="contact_person" value="{{$customer->contact_person}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="phone_number1">Phone<span class="mandatory"></span></label>
                                <input id="phone_number1" class="form-control" placeholder="Phone number" name="phone_number1" value="{{$customer->phone_number1}}" type="tel">
                            </div>
                            <div class="form-group col-md-4 del_loc ">
                                <label for="delivery_location">Delivery Location:<span class="mandatory"></span></label>
                                <select class="form-control" name="delivery_location" id="delivery_location">
                                    @foreach($locations as $l)
                                    @if($l->id == $customer->delivery_location_id)
                                    <option value="{{$l->id}}" selected="selected">{{$l->area_name}}</option>                                    
                                    @else
                                    <option value="{{$l->id}}">{{$l->area_name}}</option>                                    
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="clearfix"></div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('pending_customers')}}" class="btn btn-default form_button_footer">Back</a>
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