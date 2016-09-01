@extends('layouts.master')
@section('title','Orders')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Orders</span></li>
                </ol>
                <input type="hidden" id="module" value="order">
                <div class="filter-block">
                    
                    <form action="{{url('orders')}}" method="GET" id="orderForm">
                        <h1 class="pull-left">Orders</h1>
                        <div class="pull-right">
                            @if(Auth::user()->role_id != 4 && Auth::user()->role_id != 3 )
                            <a href="{{url('orders/create')}}" class="btn btn-primary">
                                <i class="fa fa-plus-circle fa-lg"></i> Place Order
                            </a>
                            @endif
                        </div>
                        <div class="col-md-1 pull-right" style="padding: 0;">
                            <?php
                            $session_sort_type_order = Session::get('order-sort-type');
                            $qstring_sort_type_order = Input::get('order_filter');
                            if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                                $qstring_sort_type_order = $qstring_sort_type_order;
                            } else {
                                $qstring_sort_type_order = $session_sort_type_order;
                            }
                            ?>
                            <select class="form-control" id="user_filter3" name="order_filter" onchange="this.form.submit();">
                                <option <?php if ($qstring_sort_type_order == 'pending') echo 'selected=""'; ?> value="pending">Pending</option>
                                <option <?php if ($qstring_sort_type_order == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                                <option <?php if ($qstring_sort_type_order == 'cancelled') echo 'selected=""'; ?> value="cancelled">Canceled</option>
                            </select>
                            <?php
                            if (isset($session_sort_type_order)) {
                                Session::put('order-sort-type', "");
                            }
                            ?>
                        </div>
                        <div class="col-md-2 pull-right">
                            <select class="form-control" id="user_filter3" name="party_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Select Party--</option>
                                @foreach($customers as $customer)
                                @if($customer->customer_status == 'permanent')
                                <option <?php if (Input::get('party_filter') == $customer->id) echo 'selected=""'; ?> value="{{$customer->id}}">{{$customer->tally_name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 pull-right">                            
                            <select class="form-control" id="user_filter3" name="fulfilled_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Fulfilled by--</option>
                                <option {{(Input::get('fulfilled_filter') != 'all') ? 'selected' :'' }} value="0" >Warehouse</option>
                                <option {{(Input::get('fulfilled_filter') == 'all') ? 'selected' :'' }} value="all" >Direct</option>
                            </select>
                        </div>
                        <div class="col-md-2 pull-right">
                            <select class="form-control" id="user_filter3" name="location_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Select Location--</option>
                                @foreach($delivery_location as $location)
                                @if($location->status=='permanent' && $location->id!=0)
                                <option <?php if (Input::get('location_filter') == $location->id) echo 'selected=""'; ?> value="{{$location->id}}">{{$location->area_name}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group col-md-2 pull-right">
                            <input class="form-control order_filter ui-autocomplete-input" placeholder="Size" value="{{Input::get('size_filter')}}" id="order_size" autocomplete="off"  type="text">
                            <input type='hidden' placeholder="Size" value="{{Input::get('size_filter')}}" id="order_size_temp" autocomplete="off" name="size_filter" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" onclick="javascript:document.getElementById('orderForm').submit();">
                                    <i class="fa fa-search" id="search_icon"></i>
                                </button>
                            </span>
                        </div>
                         <div class="input-group col-md-1 pull-right">
                    @if(sizeof($allorders)!=0 && ($qstring_sort_type_order=='pending' || $qstring_sort_type_order=='' ))
                    <a href="{{URL::action('OrderController@exportOrderBasedOnStatus',['order_status'=>'pending'])}}" class="btn btn-primary">
                        Export
                    </a>
                    @endif
                    @if(sizeof($allorders)!=0 && $qstring_sort_type_order=='completed')
                    <a href="{{URL::action('OrderController@exportOrderBasedOnStatus',['order_status'=>'completed'])}}" class="btn btn-primary">
                        Export
                    </a>
                    @endif
                    @if(sizeof($allorders)!=0 && $qstring_sort_type_order=='cancelled')
                    <a href="{{URL::action('OrderController@exportOrderBasedOnStatus',['order_status'=>'cancelled'])}}" class="btn btn-primary">
                        Export
                    </a>
                    </div>
                    @endif
                    </form>
                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div id="flash_message" class="alert no_data_msg_container"></div>
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if(sizeof($allorders)==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no orders have been added.
                        </div>
                        @else                        
                        <div class="table-responsive tablepending">
                            <table id="table-example" class="table table-hover">
                                <?php
                                $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1;
                                ?>
                                @foreach($allorders as $order)
                                @if($order->order_status == 'pending')
                                @if($k==1)
                                <thead>
                                    <tr>
                                        @if(Input::has('flag') && Input::get('flag') == 'true')
                                        <th><a href="{{url('orders?flag=false')}}">Flag</a></th>
                                        @else
                                        <th><a href="{{url('orders?flag=true')}}">Flag</a></th>
                                        @endif
                                        <th>#</th>
                                        <th>Tally Name</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Total Quantity</th>
                                        <th>Pending Quantity</th>
                                        <th class="text-center">Create Delivery Order</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @endif
                                    <tr id="order_row_{{$order->id}}">
                                        <td>
                                            <span class="{{($order->flaged==true)?'filled_star flags':'empty_star flags'}}" data-orderid="{{$order->id}}" ></span>
                                        </td>
                                        <td>{{$k++}}</td>
                                        <td>{{($order["customer"]->tally_name != "")? $order["customer"]->tally_name : $order["customer"]->owner_name}}</td>
                                        <td>{{$order['customer']['phone_number1']}}</td>
                                        @if($order->delivery_location_id !=0)
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order->delivery_location_id ==0 )
                                        <td class="text">{{$order['other_location']}}</td>
                                        @endif
                                        <td>{{ round($order->total_quantity, 2) }}</td>
                                        <td>{{ round($order->pending_quantity, 2) }}</td>                                        
                                        <td class="text-center">
                                            <a href="{{url('create_delivery_order/'.$order->id)}}" class="table-link" title="Create Delivery order">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id == 0 ||Auth::user()->role_id == 1 || Auth::user()->role_id == 2 )
                                            <a href="{{url('orders/'.$order->id.'/edit')}}" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link" title="manual complete" data-toggle="modal" data-target="#cancel_order_modal" onclick="cancel_order_row({{$order->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal" onclick="delete_order_row({{$order->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @if($order->order_status == 'completed')
                                    @if($k==1)
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tally Name</th>
                                        <th>Total Quantity</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Order By</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                @endif
                                <tr id="order_row_{{$order->id}}">
                                    <td>{{$k++}}</td>                                    
                                    <td>{{($order["customer"]->tally_name != "")? $order["customer"]->tally_name : $order["customer"]->owner_name}}</td>
                                    @if(count($pending_orders) > 0)
                                    @foreach($pending_orders as $porder)
                                    @if($porder['id'] == $order->id)
                                    <td>{{ round($porder['total_quantity'], 2) }}</td>
                                    @endif
                                    @endforeach
                                    @else
                                    <td></td>
                                    @endif
                                    <td>{{$order['customer']['phone_number1']}}</td>
                                    @if(isset($order['delivery_location']) && $order['delivery_location']['area_name'] !="")
                                    <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                    @else
                                    <td class="text">{{$order['other_location']}}</td>
                                    @endif
                                    <td class="text"><?php
                                        foreach ($users as $u) {
                                            if ($u['id'] == $order['created_by']) {
                                                echo $u['first_name'];
                                            }
                                        }
                                        ?></td>
                                    <td class="text-center">
                                        <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                                        <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal" onclick="delete_order_row({{$order->id}})">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @if($order->order_status == 'cancelled')
                                @if($k==1)
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tally Name</th>
                                        <th>Total Quantity</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Order By</th>
                                        <th>Cancel By</th>
                                        <th>Reason</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @endif
                                    <tr id="order_row_{{$order->id}}">
                                        <td>{{$k++}}</td>
                                        <td>{{($order["customer"]->tally_name != "")? $order["customer"]->tally_name : $order["customer"]->owner_name}}</td>
                                        <td><?php
//                                            $total_quantity = 0;
//                                            foreach ($order['all_order_products'] as $key => $product) {
//                                                $total_quantity = $total_quantity + $product['quantity'];
//                                            }
//                                            echo $total_quantity;
                                            ?>
                                            {{ round($order['total_quantity'], 2) }}
                                        </td>
                                        <td>{{$order['customer']['phone_number1']}}</td>
                                        @if($order['delivery_location']['area_name'] !="")
                                        <td class="text-center">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order['delivery_location']['area_name'] =="")
                                        <td class="text-center">{{$order['other_location']}}</td>
                                        @endif
                                        <td>
                                            <?php
                                            foreach ($users as $u) {
                                                if ($u['id'] == $order['created_by']) {
                                                    echo $u['first_name'];
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            foreach ($users as $canceluser) {
                                                if ($canceluser['id'] == $order['order_cancelled']['cancelled_by']) {
                                                    echo $canceluser['first_name'];
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td>{{$order['order_cancelled']['reason']}}</td>
                                        <td class="text-center">
                                            <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal" onclick="delete_order_row({{$order->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="modal fade" id="cancel_order_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                        </div>
                                        {!! Form::open(array('method'=>'POST','url'=>url('manual_complete_order'), 'id'=>'cancel_order_form'))!!}
                                        <input type="hidden" name="order_id" id="order_id">
                                        <div class="modal-body">
                                            <p> Are you sure to complete the Order?</p>
                                            <div class="radio">
                                                <input  id="overprice" value="overprice" name="reason_type" type="radio">
                                                <label for="overprice">Over Pricing</label>
                                            </div>
                                            <div class="radio">
                                                <input  id="delivery" value="delivery" name="reason_type" type="radio">
                                                <label for="delivery">Late Delivery</label>
                                            </div>
                                            <div class="radio">
                                                <input  id="quality" value="quality" name="reason_type" type="radio">
                                                <label for="quality">Undesired Quality</label>
                                            </div>
                                            <div class="form-group">
                                                <label for="reason"><b>Reason</b></label>
                                                <textarea class="form-control" id="inquiry_remark" name="reason"  rows="2" placeholder="Reason"></textarea>
                                            </div>
                                            <div class="checkbox">
                                                <label class="marginsms"><input type="checkbox" name="send_email" value="true"><span class="checksms">Send Email to Party</span></label>
                                                <label><input type="checkbox" value="true" name="sendsms"><span title="SMS would be sent to Party" class="checksms smstooltip">SMS</span></label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                            <button type="button" class="btn btn-default cancel_orders_modal_submit" >Yes</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="delete_orders_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                        </div>
                                        <form method="post" class="delete_order_form" >
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
<!--                                            <input name="_method" type="hidden" value="DELETE">-->
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr" id="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="button" class="btn btn-default delete_orders_modal_submit">Yes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <span class="pull-right">
                                <?php
                                if (Input::get('order_filter') != '') {
                                    $allorders->appends(array('order_filter' => Input::get('order_filter')))->render();
                                }
                                if (Input::get('location_filter') != '') {
                                    $allorders->appends(array('location_filter' => Input::get('location_filter')))->render();
                                }
                                if (Input::get('party_filter') != '') {
                                    $allorders->appends(array('party_filter' => Input::get('party_filter')))->render();
                                }
                                if (Input::get('fulfilled_filter') != '') {
                                    $allorders->appends(array('fulfilled_filter' => Input::get('fulfilled_filter')))->render();
                                }
                                if (Input::get('size_filter') != '') {
                                    $allorders->appends(array('size_filter' => Input::get('size_filter')))->render();
                                }
                                if (Input::has('flag') && Input::get('flag') == 'true') {
                                    $allorders->appends(array('flag' => Input::get('flag')))->render();
                                }
                                echo $allorders->render();
                                ?>
                            </span>
                            <div class="clearfix"></div>
                            @if($allorders->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('orders')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $allorders->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop