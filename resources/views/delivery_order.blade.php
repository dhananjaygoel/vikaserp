@extends('layouts.master')
@section('title','Delivery Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Delivery Orders</span></li>
                </ol>
                <h1 class="pull-left">Delivery Orders</h1>
                <div class="pull-right top-page-ui">
                    <a href="{{URL::action('DeliveryOrderController@create')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> Create Delivery order Independently
                    </a>
                    <div class="form-group pull-right">
                        <div class="col-md-12">
                            <form method="GET" action="{{URL::action('DeliveryOrderController@index')}}" id="filter_form">
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <select class="form-control" id="order_status" name="order_status" onchange="this.form.submit()">
                                    <option value="" selected="" disabled="">--Status--</option>
                                    <option <?php if (Input::get('order_status') == 'Delivered') echo 'selected=""'; ?> value="Delivered">Delivered</option>
                                    <option <?php if (Input::get('order_status') == 'Inprocess') echo 'selected=""'; ?> value="Inprocess">Inprocess</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(Session::has('error'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('error') }} </strong>
                        </div>
                        @endif
                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">
                            {{Session::get('success')}}                            
                        </div>
                        @endif

                        @if (Session::has('flash_message'))
                        <div class="alert alert-success alert-success1">
                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                            <strong>Well done!</strong> User details successfully added.
                        </div> <br/>
                        @endif

                        @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">
                            {{Session::get('wrong')}}                            
                        </div>
                        @endif

                        @if(sizeof($delivery_data) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="">#</th>
                                        <th>Date</th>
                                        <th>Party Name</th>
                                        <th>Delivery Location</th>
                                        <th>Quantity</th>
                                        <th>Vehicle Number</th>
                                        <th class="text-center">Create Delivery Challan</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>  
                                    <?php $i = ($delivery_data->currentPage() - 1 ) * $delivery_data->perPage() + 1; ?>
                                    @foreach($delivery_data as $delivery)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{date("d F, Y", strtotime($delivery->created_at)) }}</td>
                                        <td>       
                                            {{$delivery['customer']->owner_name}}
                                        </td> 
                                        <td>
                                            @if($delivery->delivery_location_id!=0)
                                            @foreach($delivery_locations as $location)
                                            @if($location->id == $delivery->delivery_location_id)
                                            {{$location->area_name}}
                                            @endif
                                            @endforeach
                                            @else
                                            {{$delivery->other_location}}
                                            @endif

                                        </td> 
                                        <td>
                                            @foreach($pending_orders as $pending)
                                            @if($pending['id'] == $delivery->id)
                                            {{$pending['total_quantity']}}
                                            @endif
                                            @endforeach

                                        </td> 
                                        <td>
                                            {{$delivery->vehicle_number}}
                                        </td> 
                                        <td class="text-center">
                                            @if($delivery->order_status == 'completed')
                                            <a href="{{url('create_delivery_challan/'.$delivery->id)}}" class="table-link" title="Delivery challan">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($delivery->order_status != 'completed')
                                            <span class="table-link" title="Delivery challan">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{URL::action('DeliveryOrderController@show',['id'=> $delivery->id])}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if($delivery->serial_no == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1 )
                                            <a href="{{URL::action('DeliveryOrderController@edit', ['id'=> $delivery->id])}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($delivery->serial_no != "")
                                            <span class="table-link" title="edit" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif

                                            @if($delivery->serial_no == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link" title="print" data-toggle="modal" data-target="#print_challan_{{$delivery->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($delivery->serial_no != "")
                                            <span class="table-link" title="print">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal{{$delivery->id}}" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                <div class="modal fade" id="myModal{{$delivery->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('route' => array('delivery_order.destroy', $delivery->id), 'method' => 'delete')) !!}
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <?php
                                                    $us = Auth::user();
                                                    $us['mobile_number']
                                                    ?>
                                                    <div><b>Mobile:</b>
                                                        {{$us['mobile_number']}}
                                                        <input type="hidden" name="mobile" value="{{$us['mobile_number']}}"/>
                                                        <input type="hidden" name="user_id" value="<?php echo $delivery->id; ?>"/>
                                                    </div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" id="model_pass<?php echo $delivery->id; ?>" name="model_pass" placeholder="" required="required" type="password"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="print_challan_{{$delivery->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row print_time "> 
                                                    <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="checksms" value=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div >
                                                    <button type="button" class="btn btn-primary form_button_footer print_delivery_order" id="{{$delivery->id}}">Print</button>

                                                    <a href="{{url('delivery_order')}}" class="btn btn-default form_button_footer">Cancel</a>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php echo $delivery_data->render(); ?>
                                </ul>
                            </span>
                        </div>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no delivery order data available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop