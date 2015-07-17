@extends('layouts.master')
@section('title','Purchase Orders')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Purchase Orders</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Purchase Orders</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('PurchaseOrderController@create')}}"  class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Place Purchase Order
                        </a>


                        <div class="filter-block pull-right">
                            <div class="form-group pull-left">
                                <div class="col-md-12">
                                    <form method="GET" action="{{url('purchase_orders')}}">
                                        <select class="form-control" id="user_filter" name="pending_purchase_order" onchange="this.form.submit();">
                                            <option value="" selected="">Select Party</option>
                                            @foreach($all_customers as $customer)
                                            <option value="{{$customer->id}}" <?php if ((isset($_GET['pending_purchase_order'])) && $_GET['pending_purchase_order'] == $customer->id) echo "selected=''"; ?>>{{$customer->owner_name}}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <div class="form-group pull-left">
                                <div class="col-md-12">
                                    <select class="form-control" id="user_filter4" name="user_filter">
                                        <option value="" selected="">Select size</option>
                                        <option value="2">20 kg</option>
                                        <option value="2">30 kg</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group pull-left">
                                <div class="col-md-12">
                                    <form method="GET" action="{{url('purchase_orders')}}">
                                        <select class="form-control" id="order_for_filter" name="order_for_filter" onchange="this.form.submit();">
                                            <option value="" selected="">Order For</option>
                                            <option value="warehouse">Warehouse</option>
                                            <option value="direct">Direct</option>
                                        </select>
                                    </form>
                                </div>
                            </div>


                            <div class="form-group pull-left">
                                <div class="col-md-12">
                                    <form method="GET" action="{{url('purchase_orders')}}">
                                        <select class="form-control" id="purchase_order_filter" name="purchase_order_filter" onchange="this.form.submit();">
                                            <option value="">Status</option>
                                            <option value="pending" <?php if (isset($_GET['purchase_order_filter']) && ($_GET['purchase_order_filter'] == "pending")) echo "selected=''"; ?>>Pending</option>
                                            <option value="completed" <?php if (isset($_GET['purchase_order_filter']) && ($_GET['purchase_order_filter'] == "completed")) echo "selected=''"; ?>>Completed</option>
                                            <option value="canceled" <?php if (isset($_GET['purchase_order_filter']) && ($_GET['purchase_order_filter'] == "canceled")) echo "selected=''"; ?>>Canceled</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($purchase_orders) ==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no purchase orders have been added.
                        </div>
                        @else
                        @if (Session::has('error'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('error') }}</div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        <div class="table-responsive tablepending">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Supplier Name</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Order By</th>
                                        <th>Total Quantity</th>
                                        <th>Pending Quantity</th>
                                        @if(Input::get('purchase_order_filter') == 'pending'  || Input::get('purchase_order_filter') == '')
                                        <th class="text-center">Create Purchase Advice</th>
                                        @endif
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($purchase_orders->currentPage() - 1) * $purchase_orders->perPage() + 1; ?>
                                    @foreach($purchase_orders as $purchase_order)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$purchase_order['customer']->owner_name}}</td>
                                        <td>{{$purchase_order['customer']->phone_number1}}</td>
                                        <td>{{$purchase_order['delivery_location']['area_name']}}</td>
                                        <td>{{$purchase_order['user']->first_name}}</td>
                                        <td>
                                            {{$purchase_order->total_quantity}}
                                        </td>
                                        <td>                                        
                                            {{$purchase_order->pending_quantity}}
                                        </td>
                                        @if(Input::get('purchase_order_filter') == 'pending'  || Input::get('purchase_order_filter') == '')
                                        <td class="text-center">
                                            <a href="{{ url('create_purchase_advice'.'/'.$purchase_order->id)}}" class="table-link" title="Create Purchase Advice">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <a href="{{ Url::action('PurchaseOrderController@show', ['id' => $purchase_order->id]) }}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if($purchase_order->order_status!='completed' || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="{{ Url::action('PurchaseOrderController@edit', ['id' => $purchase_order->id]) }}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a class="table-link" title="manually complete" data-toggle="modal" data-target="#manual_complete_{{$purchase_order->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a class="table-link danger" data-toggle="modal" data-target="#delete_purchase_order_{{$purchase_order->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            @endif
                                        </td>
                                    </tr>
                                <div class="modal fade" id="delete_purchase_order_{{$purchase_order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(array('method'=>'DELETE','url'=>url('purchase_orders',$purchase_order->id), 'id'=>'delete_purchase_order_form'))!!}
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="password" name="password" required=""></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="manual_complete_{{$purchase_order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{url('manual_complete')}}" method="POST">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <input type="hidden" name="module_name" value="purchase_order">
                                                    <input type="hidden" name="purchase_order_id" value="{{$purchase_order->id}}">
                                                    <p>Are you sure to complete the Order? </p>
                                                    <div class="form-group">
                                                        <label for="reason"><b>Reason</b></label>
                                                        <textarea class="form-control" id="inquiry_remark" name="reason"  rows="2" placeholder="Reason" required=""></textarea>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label class="marginsms"><input type="checkbox" name="send_email" value="true"><span class="checksms">Send Email to Party</span></label>
                                                        <label><input type="checkbox" value="true" name="sendsms"><span title="SMS would be sent to Party" class="checksms smstooltip">SMS</span></label>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $purchase_orders->render(); ?>
                            </span>
                            @if($purchase_orders->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('purchase_orders')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $purchase_orders->lastPage()}} </b></label>
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
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <p>Are you sure to complete the Order? </p>
                <div class="form-group">
                    <label for="reason"><b>Reason</b></label>
                    <textarea class="form-control" id="inquiry_remark" name="reason"  rows="2" placeholder="Reason"></textarea>
                </div>
                <div class="checkbox">
                    <label class="marginsms"><input type="checkbox" value=""><span class="checksms">Email</span></label>
                    <label><input type="checkbox" value=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
            </div>
        </div>
    </div>
</div>
@stop