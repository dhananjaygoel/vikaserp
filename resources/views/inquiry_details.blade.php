@extends('layouts.master')
@section('title','Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Inquiry</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Inquiry</h1>
                    <div class="pull-right top-page-ui">
                        @if($inquiry->inquiry_status != 'completed')
                        @if( Auth::user()->role_id != 2)
                        <a href="{{ url('inquiry/'.$inquiry->id.'/edit') }}" class="btn btn-primary pull-right">
                            Edit Inquiry
                        </a>
                        @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div style="display:none;" id="inquire_msg" class="alert alert-success no_data_msg_container">Inquiry difference price successfully updated</div>
                        @if(isset($message) && $message != '')
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ $message }} </strong>
                        </div>
                        @endif
                        <form>
                            <div class="table-responsive">
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        <tr>
                                            <td><span>Tally Name:</span>
                                                @if(isset($inquiry['customer']->owner_name) && isset($inquiry['customer']->tally_name) && $inquiry['customer']->owner_name != "" && $inquiry['customer']->tally_name != "")
                                                {{$inquiry['customer']->owner_name}}{{'-'.$inquiry['customer']->tally_name}}
                                                @else
                                                {{isset($inquiry['customer']->owner_name)? $inquiry['customer']->owner_name:""}}
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>Contact Person: </span>{{isset($inquiry['customer']->contact_person) ? $inquiry['customer']->contact_person: ""}}</td>
                                        </tr>
                                        <tr>
                                            <td><span>Phone Number: </span>{{isset($inquiry['customer']->phone_number1) ? $inquiry['customer']->phone_number1:""}}</td>
                                        </tr>
                                        @if(isset($inquiry['customer']->credit_period))
                                        @if($inquiry['customer']->credit_period !='' || $inquiry['customer']->credit_period > 0)
                                        <tr>
                                            <td><span>Credit Period(Days): </span>{{$inquiry['customer']->credit_period}}</td>
                                        </tr>
                                        @endif
                                        @else
                                            <tr>
                                            <td><span>Credit Period(Days): </span>{{"0"}}</td>
                                        </tr>
                                        @endif
                                        @if($inquiry->delivery_location_id != 0)
                                        @foreach($delivery_location as $location)
                                        @if($inquiry->delivery_location_id == $location->id)
                                        <tr>
                                            <td><span class="underline">Delivery Location: </span> {{$location->area_name}} </td>
                                        </tr>
                                        <tr>
                                            <td><span class="underline">Freight: </span> {{$inquiry->location_difference}} </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @else
                                        <tr><td><span class="underline">Other Location: </span> {{$inquiry->other_location}} </td></tr>
                                        <tr><td><span class="underline">Freight: </span> {{$inquiry->location_difference}} </td></tr>
                                        @endif
                                        <tr><td><span class="underline">Product Details </span></td></tr>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span> Product(Alias)</span></td>
                                            <td><span> Quantity</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>length</span></td>
                                            <td><span>Price</span></td>
                                            <td><span>GST</span></td>
                                            <td class="widthtable"><span>Update Price</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        @foreach($inquiry['inquiry_products'] as $product_data)
                                        <tr>
                                            <td>{{isset($product_data['inquiry_product_details'])?$product_data['inquiry_product_details']->alias_name: ''}}</td>
                                            <td>{{$product_data->quantity}}</td>
                                            <td>{{isset($product_data['unit']->unit_name)?$product_data['unit']->unit_name:''}}</td>
                                            <td>{{$product_data->length}}</td>
                                            <td><div id='price_{{$product_data->id}}'>{{$product_data->price}}</div></td>
                                            <td>
                                                <div id='vat_{{$product_data->id}}'>
                                                    <input type="checkbox" disabled="" {{($product_data->vat_percentage>0)?'checked':''}} >
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="tel" class="form-control" id="difference_{{$product_data->id}}" placeholder="Price" value='{{$product_data->price}}' required="">
                                                        <input type="hidden"name="product_id" value='{{$product_data->id}}' id='hidden_inquiry_product_id_{{$product_data->id}}'>
                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">
                                                        <div id="save_btn_{{$product_data->id}}">
                                                            <input type="button" name="save_price" value="Save" class="btn btn-primary" id="save_price_inquiry_view_{{$product_data->id}}" onclick="save_price_inquiry_view({{$product_data->id}},{{$inquiry->id}});">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$product_data->remarks}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        @if($inquiry->vat_percentage == 0)
                                        <tr><td><span>Plus GST: </span>No</td></tr>
                                        @elseif($inquiry->vat_percentage != 0)
                                        <!--<tr><td><span>Plus GST: </span>Yes</td></tr>-->
                                        <tr><td><span>GST Percentage: </span> {{isset($inquiry->vat_percentage)?$inquiry->vat_percentage:'0.00'}}% </td></tr>
                                        @endif
                                        <tr>
                                            <td><span>Expected Delivery Date: </span>{{date('F jS, Y',strtotime($inquiry->expected_delivery_date))}}</td>
                                        </tr>
                                        <tr>
                                            <td><span>Remark: </span>{{$inquiry->remarks}}</td>
                                        </tr>
                                        <tr>
                                            
                                            <td><span>Created By: </span>{{$inquiry['createdby']->first_name}} {{$inquiry['createdby']->last_name}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <div>
                                 <?php 
                                        if(isset($is_approval['way']) && $is_approval['way'] == 'approval'){ ?>
                               {{-- <a href="{{URL::to('inquiry?inquiry_filter=Approval')}}" class="btn btn-default form_button_footer">Back</a> --}}
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a> 
                                
                                        <?php }else{  ?> 
                                  <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>                                  
                                        <?php } ?>                               
                                
                                <!--<a href="{{url('inquiry/'.$inquiry->id.'?sendsms=true' )}}" title="SMS would be sent to Party and Relationship Manager" type="button" class="btn btn-primary smstooltip" >Send SMS</a><span title="SMS has been sent 5 times" class="badge enquirybadge smstooltip">0</span>-->
                                <span id="send_sms_button">
                                    <span title="You can not click unless you save all prices" type="button" class="btn btn-default smstooltip normal_cursor" >Send SMS</span>
                                </span>
                                <span title="SMS has been sent {{$inquiry->sms_count}} times" class="badge enquirybadge smstooltip">{{$inquiry->sms_count}}</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop