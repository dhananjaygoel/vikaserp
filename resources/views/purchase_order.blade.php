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
                        <div class="form-group pull-right">
                            <div class="col-md-12">
                                <select class="form-control" id="user_filter3" name="purchase_order_filter">
                                    <option value="" selected="">Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Canceled">Canceled</option>
                                </select>
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
                                        <td>{{$purchase_order['delivery_location']->area_name}}</td>
                                        <td>{{$purchase_order['user']->first_name}}</td>
                                        <td>{{$purchase_order['purchase_products']->sum('quantity')}}</td>
                                        <td>35</td>
                                        <td class="text-center">
                                            <a href="{{ Url::action('PurchaseOrderController@show', ['id' => $purchase_order->id]) }}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="{{ Url::action('PurchaseOrderController@edit', ['id' => $purchase_order->id]) }}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a class="table-link" title="manually complete" data-toggle="modal" data-target="#myModal1">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="{{ url('create_purchase_advice'.'/'.$purchase_order->id)}}" class="table-link" title="Create Purchase Advice">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a class="table-link danger" data-toggle="modal" data-target="#delete_purchase_order_{{$purchase_order->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
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
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $purchase_orders->render(); ?>
                            </span>
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