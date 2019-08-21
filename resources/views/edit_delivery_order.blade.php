@extends('layouts.master')
@section('title','Edit Delivery Order')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                        <li class="active"><span>Edit Delivery Order</span></li>
                    </ol>
                    <div class="clearfix">
                        <h1 class="pull-left"></h1>
                    </div>
                </div>
            </div>
            <div  class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <div class="main-box-body clearfix">
                            @if (Session::has('validation_message'))
                                <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('validation_message') }}</div>
                            @endif
                            @if (count($errors) > 0)
                                <div role="alert" class="alert alert-warning">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                            <div class="form-group">Date : {{date('d F, Y')}}</div>
                            {!!Form::open(array('data-button'=>'btn_edit_delivery_order','method'=>'PUT','url'=>url('delivery_order/'.$delivery_data['id']),'id'=>'onenter_prevent'))!!}
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            @if($delivery_data->order_source == 'supplier')
                                <div class="form-group">
                                    <label for="cn"><b>Supplier Name: </b>
                                    @foreach($customers as $customer)
                                        @if($customer->id == $delivery_data->supplier_id)
                                            {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @if($delivery_data['customer']->customer_status =="pending")
                                <div class="form-group">
                                    <label>Customer<span class="mandatory">*</span></label>
                                    <div class="radio">
                                        <input <?php if ($delivery_data['customer']->customer_status == 'permanent') echo 'checked=""'; ?> value="existing_customer" id="exist_customer" name="customer_status"  type="radio">
                                        <label for="exist_customer">Existing</label>
                                        <input <?php if ($delivery_data['customer']->customer_status == 'pending') echo 'checked=""'; ?>  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                        <label for="new_customer">New</label>
                                    </div>
                                    <div class="customer_select" style="display: none">
                                        <div class="col-md-4">
                                            <div class="form-group searchproduct">
                                                <input class="form-control" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name">
                                                <input type="hidden" id="existing_customer_id" name="autocomplete_customer_id">
                                                <!--<i class="fa fa-search search-icon"></i>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="exist_field">
                                    <input type="hidden" id='pending_user_id' name="pending_user_id" value='{{$delivery_data['customer']->id}}'/>
                                    <div class="form-group">
                                        <label for="name">Customer Name<span class="mandatory">*</span></label>
                                        <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{ $delivery_data['customer']->owner_name }}" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Contact Person<span class="mandatory">*</span></label>
                                        <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ $delivery_data['customer']->contact_person }}" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                        <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{ $delivery_data['customer']->phone_number1 }}" type="tel">
                                    </div>
                                    <div class="form-group">
                                        <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                        <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" onkeypress=" return numbersOnly(this,event,false,false);" value="{{ $delivery_data['customer']->credit_period }}" type="tel">
                                    </div>
                                </div>
                            @elseif($delivery_data['customer']->customer_status == "permanent")
                                <div class="form-group">
                                    <label>Customer<span class="mandatory">*</span></label>
                                    <div class="radio">
                                        <input <?php if ($delivery_data['customer']->customer_status == 'permanent') echo 'checked=""'; ?> value="existing_customer" id="exist_customer" name="customer_status"  type="radio">
                                        <label for="exist_customer">Existing</label>
                                        <input <?php if ($delivery_data['customer']->customer_status == 'pending') echo 'checked=""'; ?>  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                        <label for="new_customer">New</label>
                                    </div>
                                    <div class="customer_select">
                                        <div class="col-md-4">
                                            <div class="form-group searchproduct">
                                                <input value="{{ $delivery_data['customer']->tally_name }}" class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name" tabindex="1">
                                                <input type="hidden" value="{{ $delivery_data['customer']->id }}" id="existing_customer_id" name="autocomplete_customer_id">
                                                <!--<i class="fa fa-search search-icon"></i>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="exist_field" style="display: none">
                                    <div class="form-group">
                                        <label for="name">Customer Name<span class="mandatory">*</span></label>
                                        <input id="name" class="form-control" placeholder="Name" name="customer_name" value="" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Contact Person<span class="mandatory">*</span></label>
                                        <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                        <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="tel">
                                    </div>
                                    <div class="form-group">
                                        <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                        <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="" type="tel">
                                    </div>
                                </div>
                            @endif
                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                        <select class="form-control focus_on_enter" name="add_order_location" id="add_order_location" tabindex="2" class="ui-dform-select">
                                            <option value="0" selected="">Delivery Location</option>
                                            @foreach($delivery_locations as $delivery_location)
                                                @if($delivery_location->status=='permanent' && $delivery_location->id!= 0)
                                                    <option  data-location-difference="{{$delivery_location->difference}}" <?php if ($delivery_location->id == $delivery_data->delivery_location_id) echo 'selected=""'; ?>  value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
                                                @endif
                                            @endforeach
                                            <option {{($delivery_data->delivery_location_id == 0)?'selected':''}} id="other_location" value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="location">Freight</label>
                                        <input id="location_difference" class="form-control focus_on_enter" placeholder="Freight " onkeypress=" return numbersOnly(this,event,true,true);" name="location_difference" value="{{ $delivery_data->location_difference}}" type="tel" tabindex="3" >
                                    </div>

                                </div>
                            </div>
                            @if($delivery_data->discount > 0)
                                <div class="form-group">
                                    <label><b>Discount/Premium :</b> </label>
                                    {{$delivery_data->discount_type}}
                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$delivery_data->discount_type}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Fixed/Percentage :</b> </label>
                                    {{$delivery_data->discount_unit}}
                                    <input type="hidden" id="discount_unit" name="discount_unit" value="{{$delivery_data->discount_unit}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Amount :</b> </label>
                                    {{$delivery_data->discount}}
                                    <input type="hidden" id="discount_amount" name="discount" value="{{$delivery_data->discount}}" >
                                </div>
                            @else
                                <div class="form-group">
                                    <label><b>Discount/Premium :</b> </label>
                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$delivery_data->discount_type}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Fixed/Percentage :</b> </label>
                                    <input type="hidden" id="discount_unit" name="discount_unit" value="{{$delivery_data->discount_unit}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Amount :</b> </label>
                                    <input type="hidden" id="discount_amount" name="discount" value="{{$delivery_data->discount}}" >
                                </div>
                            @endif
                            <div class="clearfix"></div>
                            <div class="locationtext"<?php if ($delivery_data->delivery_location_id == 0) echo 'style="display:block;"'; ?>>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location Name" name="location" value="{{ $delivery_data->other_location}}" type="text">
                                    </div>
                                </div>
                            </div>