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
                <input type="hidden" id="module" value="deliveryorder">
                <h1 class="pull-left">Delivery Orders</h1>
                <div class="pull-right top-page-ui">
<!--                    <a href="{{URL::action('DeliveryOrderController@create')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> Create Delivery order Independently
                    </a>-->
                    <div class="form-group pull-right">
                        <div class="col-md-12">
                            <form method="GET" action="{{URL::action('DeliveryOrderController@index')}}" id="filter_form">
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <?php
                                $session_sort_type_order = Session::get('order-sort-type');
                                $qstring_sort_type_order = Input::get('order_status');

                                if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                                    $qstring_sort_type_order = $qstring_sort_type_order;
                                } else {
                                    $qstring_sort_type_order = $session_sort_type_order;
                                }
                                ?>
                                <select class="form-control" id="order_status" name="order_status" onchange="this.form.submit()">
                                    <option <?php if ($qstring_sort_type_order == 'Inprocess') echo 'selected=""'; ?> value="Inprocess">Inprocess</option>
                                    <option <?php if ($qstring_sort_type_order == 'Delivered') echo 'selected=""'; ?> value="Delivered">Delivered</option>
                                    
                                </select>
                                <?php
                                if (isset($session_sort_type_order)) {
                                    Session::put('order-sort-type', "");
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                    @if(isset($qstring_sort_type_order) && $qstring_sort_type_order =='Delivered' )
                                <a href="{{URL::action('DeliveryOrderController@exportDeliveryOrderBasedOnStatus',['delivery_order_status'=>'Delivered'])}}" class="btn btn-primary pull-right">
                                    Export
                                </a>
                                @endif 
                                @if(($qstring_sort_type_order =='') || isset($qstring_sort_type_order) && $qstring_sort_type_order =='Inprocess')
                                <a href="{{URL::action('DeliveryOrderController@exportDeliveryOrderBasedOnStatus',['delivery_order_status'=>'Inprocess'])}}" class="btn btn-primary pull-right">
                                    Export
                                </a>
                                @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div id="flash_message" class="alert no_data_msg_container"></div>
                        @if(Session::has('error'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('error') }} </strong>
                        </div>
                        @endif
                       
                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">{{Session::get('success')}}</div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div class="alert alert-success alert-success1">
                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                            <strong>Well done!</strong> User details successfully added.
                        </div><br/>
                        @endif
                        @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">{{Session::get('wrong')}}</div>
                        @endif
                        @if(sizeof($delivery_data) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        @if(Input::has('flag') && Input::get('flag') == 'true')
                                        <th><a href="{{url('delivery_order?flag=false')}}">Flag</a></th>
                                        @else
                                        <th><a href="{{url('delivery_order?flag=true')}}">Flag</a></th>
                                        @endif
                                        <th class="">#</th>
                                        <th>Date</th>
                                        <th>Tally Name</th>
                                        <th>Delivery Location</th>
                                        <th>Quantity</th>
                                        <th>Present Shipping</th>
                                        <th>Vehicle Number</th>
                                        @if(Input::get('order_status') == 'Inprocess' || Input::get('order_status') == '')
                                        <th class="text-center">Create Delivery Challan</th>
                                        @endif
                                        <th class="text-center col-md-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($delivery_data->currentPage() - 1 ) * $delivery_data->perPage() + 1; ?>
                                    @foreach($delivery_data as $delivery)
                                    <tr id="delivery_order_row_{{$delivery->id}}">
                                        <td>
                                            <span class="{{($delivery->flaged==true)?'filled_star flags':'empty_star flags'}}" data-orderid="{{$delivery->id}}"></span>
                                        </td>
                                        <td>{{ $i++ }}</td>
                                        <td>{{date("F jS, Y", strtotime($delivery->created_at)) }}</td>
                                        <td>
                                            @if($delivery['customer']->tally_name != "")
                                            {{$delivery['customer']->tally_name}}
                                            @else
                                            {{$delivery['customer']->owner_name}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($delivery->delivery_location_id > 0)
                                            {{$delivery->location->area_name}}
                                            @else
                                            {{$delivery->other_location}}
                                            @endif
                                        </td>
                                        <td>
                                            {{ round($delivery->total_quantity, 2) }}
                                        </td>
                                        <td>
                                            {{ round($delivery->present_shipping, 2) }}
                                        </td>
                                        <td>
                                            {{$delivery->vehicle_number}}
                                        </td>
                                        @if(Input::get('order_status') == 'Inprocess' || Input::get('order_status') == '')
                                        <td class="text-center">
                                            @if($delivery->serial_no != "")
                                            <a href="{{url('create_delivery_challan/'.$delivery->id)}}" class="table-link" title="Delivery challan">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @else
                                            <span class="table-link normal_cursor" title="Delivery challan">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <a href="{{URL::action('DeliveryOrderController@show',['id'=> $delivery->id])}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            @if($delivery->order_status == 'pending')
                                            @if(($delivery->serial_no == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1 || Auth::user()->role_id == 2))
                                            <a href="{{URL::action('DeliveryOrderController@edit', ['id'=> $delivery->id])}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($delivery->serial_no != "" && Auth::user()->role_id == 0  || Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                            <span class="table-link normal_cursor" title="edit" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                            @endif


                                            @if($delivery->serial_no == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link" title="print" data-toggle="modal" data-target="#print_challan" onclick="print_challan({{$delivery->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($delivery->serial_no != "" && Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <span class="table-link normal_cursor" title="print">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModalDeleteDeliveryOrder" title="delete" onclick='delete_delivery_order({{$delivery->id}})'>
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                <div class="modal fade" id="myModalDeleteDeliveryOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <form method="POST" accept-charset="UTF-8" action="#" id="delete_delivery_order">
                                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                                <div class="modal-body">
                                                    <div class="delete">
                                                        <div><b>Mobile:</b> {{auth()->user()->mobile_number}}
                                                            <input type="hidden" name="mobile" value="{{auth()->user()->mobile_number}}"/>
                                                            <input type="hidden" name="user_id" id="user_id"/>
                                                        </div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" name="password" placeholder="" id="pwdr delivery_order_password" required="required" type="password"></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                    <button type="submit" class="btn btn-default delete_delivery_order_submit">Yes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="print_challan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row print_time ">
                                                    <div class="col-md-12"> Print By <br>
                                                        <span class="current_time"></span>
                                                    </div>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" id="checksms" value=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div>
                                                    <button type="button" class="btn btn-primary form_button_footer print_delivery_order" id="print_delivery_order" >Print</button>
                                                    <button type="button" class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                                                   
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php
                                    if (!isset($_GET)) {
                                        echo $delivery_data->render();
                                    } else {
                                        echo $delivery_data->appends($_GET)->render();
                                    }
                                    ?>
                                </ul>
                            </span>
                            <div class="clearfix"></div>
                            @if($delivery_data->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('delivery_order')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $delivery_data->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
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