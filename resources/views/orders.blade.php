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
                <div class="filter-block">
                    <h1 class="pull-left">Orders</h1>                                 
                    <div class="pull-right top-page-ui">

                        <div class="form-group pull-right">
                            <div class="col-md-12">
                                <form action="{{url('orders')}}" method="GET">
                                    <div class="col-md-2"> 

                                        <select class="form-control" id="user_filter3" name="order_filter" onchange="this.form.submit();">
                                            <option disabled="" value="" selected="">Status</option>
                                            <option <?php if (Input::get('order_filter') == 'pending') echo 'selected=""'; ?> value="pending">Pending</option>
                                            <option <?php if (Input::get('order_filter') == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                                            <option <?php if (Input::get('order_filter') == 'cancelled') echo 'selected=""'; ?> value="cancelled">Canceled</option>
                                        </select>

                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" id="user_filter3" name="party_filter" onchange="this.form.submit();">
                                            <option value="" selected="">Select Party</option>
                                            @foreach($customers as $customer)
                                            <option <?php if (Input::get('party_filter') == $customer->id) echo 'selected=""'; ?> value="{{$customer->id}}">{{$customer->owner_name}}</option>
                                            @endforeach                                        
                                        </select> 
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" id="user_filter3" name="fulfilled_filter" onchange="this.form.submit();">
                                            <option value="" selected="">Fulfilled</option>
                                            <option <?php if (Input::get('fulfilled_filter') == '0') echo 'selected=""'; ?>value="0" >Warehouse</option>
                                            <option <?php if (Input::get('fulfilled_filter') == 'all') echo 'selected=""'; ?>value="all" >Direct</option>
                                            @foreach($customers as $customer)
                                            <option <?php if (Input::get('fulfilled_filter') == $customer->id) echo 'selected=""'; ?> value="{{$customer->id}}">{{$customer->owner_name}}</option>
                                            @endforeach
                                        </select> 
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" id="user_filter3" name="location_filter" onchange="this.form.submit();">
                                            <option value="" selected="">Select Location</option>
                                            @foreach($delivery_location as $location) 
                                            @if($location->status=='permanent' && $location->id!=0)
                                            <option <?php if (Input::get('location_filter') == $location->id) echo 'selected=""'; ?> value="{{$location->id}}">{{$location->area_name}}</option>
                                            @endif
                                            @endforeach 

                                        </select> 
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control" id="user_filter3" name="size_filter" onchange="this.form.submit();">
                                            <option value="" selected="">Select Size</option>
                                            @foreach($product_size as $product)
                                            
                                            <option <?php if (Input::get('size_filter') == $product->size) echo 'selected=""'; ?> value="{{$product->size}}">{{$product->size}}</option>
                                            
                                            @endforeach                                        
                                        </select>
                                    </div>
                                </form>
                                <div class="col-md-2">
                                    @if( Auth::user()->role_id != 3 )
                                    <a href="{{url('orders/create')}}" class="btn btn-primary pull-right">
                                        <i class="fa fa-plus-circle fa-lg"></i> Place Order
                                    </a>
                                    @endif
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
                        @if(sizeof($allorders)==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no orders have been added.
                        </div>
                        @else
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        <div class="table-responsive tablepending">
                            <table id="table-example" class="table table-hover">
                                <?php $k = 1; ?>
                                @foreach($allorders as $order)
                                @if($order->order_status == 'pending')
                                @if($k==1)
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
<!--                                        <th>Order By</th>                                        -->
                                        <th>Pending Quantity</th>
                                        <th class="text-center">Create Delivery Order</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead><tbody>
                                    @endif


                                    <tr>

                                        <td>{{$k++}}</td>
                                        <td>{{$order['customer']->owner_name}}</td>
                                        <td>{{$order['customer']['phone_number1']}}</td>
                                        @if($order->delivery_location_id !=0)
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order->delivery_location_id ==0 )
                                        <td class="text">{{$order['other_location']}}</td>
                                        @endif
<!--                                        <td class="text"><?php
                                            foreach ($users as $u) {
                                                if ($u['id'] == $order['created_by']) {
                                                    echo $u['first_name'];
                                                }
                                            }
                                            ?></td>-->
                                        @if(count($pending_orders) > 0)
                                        @foreach($pending_orders as $porder)
                                        @if($porder['id'] == $order->id)                                       
                                        <td>{{$porder['total_pending_quantity']}}</td>
                                        @endif
                                        @endforeach
                                        @else
                                        <td></td>
                                        <td></td>
                                        @endif
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
                                            <a href="#" class="table-link" title="manual complete" data-toggle="modal" data-target="#cancel_order_modal_{{$order->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif

                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal_{{$order->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                <div class="modal fade" id="delete_orders_modal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('method'=>'POST','url'=>url('orders',$order->id), 'id'=>'delete_order_form'))!!}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>     
                                <div class="modal fade" id="cancel_order_modal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('method'=>'POST','url'=>url('manual_complete_order'), 'id'=>'cancel_order_form'))!!}

                                            <input type="hidden" name="order_id" value="{{$order->id}}">
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
                                                    <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email to Party</span></label>
                                                    <label><input type="checkbox" value=""><span title="SMS would be sent to Party" class="checksms smstooltip">SMS</span></label>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" >Yes</button>
                                            </div>
                                            {!! Form::close() !!}    
                                        </div>
                                    </div>
                                </div> 



                                @endif
                                @if($order->order_status == 'completed')
                                @if($k==1)
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Total Quantity</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Order By</th>                                  

                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>

                                @endif

                                <tr>

                                    <td>{{$k++}}</td>
                                    <td>{{$order['customer']->owner_name}}</td>
                                    @if(count($pending_orders) > 0)
                                    @foreach($pending_orders as $porder)
                                    @if($porder['id'] == $order->id)
                                    <td>{{$porder['total_quantity']}}</td>                                   
                                    @endif
                                    @endforeach
                                    @else
                                    <td></td>
                                    @endif
                                    <td>{{$order['customer']['phone_number1']}}</td>
                                    @if($order['delivery_location']['area_name'] !="")
                                    <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                    @elseif($order['delivery_location']['area_name'] =="")
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

                                        <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal_{{$order->id}}">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="delete_orders_modal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('method'=>'POST','url'=>url('orders',$order->id), 'id'=>'delete_order_form'))!!}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>     




                                @endif
                                @if($order->order_status == 'cancelled')
                                @if($k==1)
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
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


                                    <tr>

                                        <td>{{$k++}}</td>
                                        <td>{{$order['customer']->owner_name}}</td>
                                        <td><?php
                                            $total_quantity = 0;
                                            foreach ($order['all_order_products'] as $key => $product) {
                                                $total_quantity = $total_quantity + $product['quantity'];
                                            }
                                            echo $total_quantity;
                                            ?></td>
                                        <td>{{$order['customer']['phone_number1']}}</td>
                                        @if($order['delivery_location']['area_name'] !="")
                                        <td class="text-center">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order['delivery_location']['area_name'] =="")
                                        <td class="text-center">{{$order['other_location']}}</td>
                                        @endif
                                        <td><?php
                                            foreach ($users as $u) {
                                                if ($u['id'] == $order['created_by']) {
                                                    echo $u['first_name'];
                                                }
                                            }
                                            ?></td>
                                        <td><?php
                                            foreach ($users as $canceluser) {
                                                if ($canceluser['id'] == $order['order_cancelled']['cancelled_by']) {
                                                    echo $canceluser['first_name'];
                                                }
                                            }
                                            ?></td>

                                        <td>{{$order['order_cancelled']['reason']}}</td>
                                        <td class="text-center">
                                            <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal_{{$order->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                <div class="modal fade" id="delete_orders_modal_{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('method'=>'POST','url'=>url('orders',$order->id), 'id'=>'delete_order_form'))!!}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>     


                                @endif
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $allorders->render(); ?>
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop