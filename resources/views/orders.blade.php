@extends('layouts.master')
@section('title','Orders')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <?php
                $session_sort_type_order = Session::get('order-sort-type');
                if ((Input::get('order_filter') != "") || (Input::get('order_status') != "")) {
                    if (Input::get('order_filter') != "") {
                        $qstring_sort_type_order = Input::get('order_filter');
                    } elseif (Input::get('order_status') != "") {
                        $qstring_sort_type_order = Input::get('order_status');
                    }
                }
                if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                    $qstring_sort_type_order = $qstring_sort_type_order;
                } else {
                    $qstring_sort_type_order = $session_sort_type_order;
                }
                ?>

                <ol class="breadcrumb pull-left">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Orders</span></li>
                </ol>
                <div class="search_form_wrapper orders_search_wrapper col-lg-12" style="width:70%">                        
                    <div class="col-lg-4">
                        @if(Auth::user()->role_id != 5)
                        <form method="GET" action="{{url()}}/orders">
                            <select class="form-control" id="user_filter3" name="territory_filter" onchange="this.form.submit();">
                                <option value="" selected="">Select Territory</option>
                                @if(isset($all_territories) && !empty($all_territories))
                                @foreach($all_territories as $territory)
                                <option value="{{$territory->id}}" @if(Input::get('territory_filter') == $territory->id) selected @endif> {{ucwords($territory->teritory_name)}}</option>
                                @endforeach
                                @endif
                            </select>
                        </form>
                        @endif
                    </div>                        
                    <div class="col-lg-8">
                        <form class="search_form" method="GET" action="{{URL::action('OrderController@index')}}">
                            <input type="text" placeholder="From" name="export_from_date" class="form-control export_from_date" id="export_from_date" <?php
                            if (Input::get('export_from_date') != "") {
                                echo "value='" . Input::get('export_from_date') . "'";
                            }
                            ?>>
                            <input type="text" placeholder="To" name="export_to_date" class="form-control export_to_date" id="export_to_date" <?php
                            if (Input::get('export_to_date') != "") {
                                echo "value='" . Input::get('export_to_date') . "'";
                            }
                            ?>>
                            @if($qstring_sort_type_order=='pending' || $qstring_sort_type_order=='' )
                            <input type="hidden" name="order_status" value="pending">
                            @elseif($qstring_sort_type_order == 'approval')
                            <input type="hidden" name="order_status" value="approval">
                            @elseif($qstring_sort_type_order == 'completed')
                            <input type="hidden" name="order_status" value="completed">
                            @elseif($qstring_sort_type_order == 'cancelled')
                            <input type="hidden" name="order_status" value="cancelled">
                            @else
                            <input type="hidden" name="order_status" value="pending">
                            @endif
                            @if(Input::has('territory_filter'))
                            <input type="hidden" name="territory_filter" value="{{Input::get('territory_filter')}}">
                            @else

                            @if(Input::has('order_filter'))
                            <input type="hidden" name="order_filter" value="{{Input::get('order_filter')}}">
                            @endif
                            @if(Input::has('fulfilled_filter'))
                            <input type="hidden" name="fulfilled_filter" value="{{Input::get('fulfilled_filter')}}">
                            @endif
                            @if(Input::has('party_filter'))
                            <input type="hidden" name="party_filter" value="{{Input::get('party_filter')}}">
                            @endif
                            @if(Input::has('location_filter'))
                            <input type="hidden" name="location_filter" value="{{Input::get('location_filter')}}">
                            @endif
                            @if(Input::has('size_filter'))
                            <input type="hidden" name="size_filter" value="{{Input::get('size_filter')}}">
                            @endif


                            @endif


                            <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                        </form>
                        <form class="pull-left" method="get" action="{{URL::action('OrderController@index')}}">
                            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="export_from_date" id="export_from_date" <?php
                            if (Input::get('export_to_date') != "") {
                                echo "value='" . Input::get('export_from_date') . "'";
                            }
                            ?>>
                            <input type="hidden"  name="export_to_date" id="export_to_date" <?php
                            if (Input::get('export_to_date') != "") {
                                echo "value='" . Input::get('export_to_date') . "'";
                            }
                            ?>>
                            @if($qstring_sort_type_order=='pending' || $qstring_sort_type_order=='' )
                            <input type="hidden" name="order_status" value="pending">
                            @elseif($qstring_sort_type_order == 'approval')
                            <input type="hidden" name="order_status" value="approval">
                            @elseif($qstring_sort_type_order == 'completed')
                            <input type="hidden" name="order_status" value="completed">
                            @elseif($qstring_sort_type_order == 'cancelled')
                            <input type="hidden" name="order_status" value="cancelled">
                            @else
                            <input type="hidden" name="order_status" value="pending">
                            @endif


                            @if(Input::has('territory_filter'))
                            <input type="hidden" name="territory_filter" value="{{Input::get('territory_filter')}}">

                            @else
                            @if(Input::has('order_filter'))
                            <input type="hidden" name="order_filter1" value="{{Input::get('order_filter')}}">
                            @endif
                            @if(Input::has('fulfilled_filter'))
                            <input type="hidden" name="fulfilled_filter" value="{{Input::get('fulfilled_filter')}}">
                            @endif
                            @if(Input::has('party_filter'))
                            <input type="hidden" name="party_filter" value="{{Input::get('party_filter')}}">
                            @endif
                            @if(Input::has('location_filter'))
                            <input type="hidden" name="location_filter" value="{{Input::get('location_filter')}}">
                            @endif
                            @if(Input::has('size_filter'))
                            <input type="hidden" name="size_filter" value="{{Input::get('size_filter')}}">
                            @endif

                            @endif

                            <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right">
                        </form>
                    </div>
                </div>
                <input type="hidden" id="module" value="order">
                <div class="filter-block">
                    <form action="{{url('orders')}}" method="GET" id="orderForm">
                        <h1 class="pull-left">Orders</h1>
                        <input type="hidden" placeholder="From" name="export_from_date" class="form-control export_from_date" id="export_from_date" <?php
                            if (Input::get('export_from_date') != "") {
                                echo "value='" . Input::get('export_from_date') . "'";
                            }
                            ?>>
                            <input type="hidden" placeholder="To" name="export_to_date" class="form-control export_to_date" id="export_to_date" <?php
                            if (Input::get('export_to_date') != "") {
                                echo "value='" . Input::get('export_to_date') . "'";
                            }
                            ?>>
                        <div class="pull-right">
                            @if(Auth::user()->role_id != 3 )
                            <a href="{{url('orders/create')}}" class="btn btn-primary">
                                <i class="fa fa-plus-circle fa-lg"></i> Place Order
                            </a>
                            @endif
                        </div>
                        <div class="col-md-2 pull-right" style="padding: 0;">
                            @if(Auth::user()->role_id <> 5)
                            <select class="form-control" id="user_filter3" name="order_filter" onchange="this.form.submit();">
                                <option <?php if ($qstring_sort_type_order == 'pending') echo 'selected=""'; ?> value="pending">Pending</option>
                                <option <?php if ($qstring_sort_type_order == 'approval') echo 'selected=""'; ?> value="approval">Pending Approval</option>
                                <option <?php if ($qstring_sort_type_order == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                                <option <?php if ($qstring_sort_type_order == 'cancelled') echo 'selected=""'; ?> value="cancelled">Canceled</option>
                            </select>
                            @endif
                            <?php
                            if (isset($session_sort_type_order)) {
                                Session::put('order-sort-type', "");
                            }
                            ?>
                        </div>
                        <div class="col-md-2 pull-right">
                            @if(Auth::user()->role_id <> 5)
                            <select class="form-control" id="user_filter3" name="party_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Select Party--</option>
                                @foreach($customers as $customer)
                                @if($customer->customer_status == 'permanent')
                                <option <?php if (Input::get('party_filter') == $customer->id) echo 'selected=""'; ?> value="{{$customer->id}}">{{$customer->tally_name}}</option>
                                @endif
                                @endforeach
                            </select>
                            @endif 
                        </div>
                        <div class="col-md-2 pull-right">  
                            @if(Auth::user()->role_id <> 5)
                            <select class="form-control" id="user_filter3" name="fulfilled_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Fulfilled by--</option>
                                <option {{(Input::get('fulfilled_filter') == '0') ? 'selected' :'' }} value="0" >Warehouse</option>
                                <option {{(Input::get('fulfilled_filter') == 'all') ? 'selected' :'' }} value="all" >Direct</option>
                            </select>
                            @endif
                        </div>
                        <div class="col-md-2 pull-right">
                            @if(Auth::user()->role_id <> 5)
                            <select class="form-control" id="user_filter3" name="location_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Select Location--</option>
                                @foreach($delivery_location as $location)
                                @if($location->status=='permanent' && $location->id!=0)
                                <option <?php if (Input::get('location_filter') == $location->id) echo 'selected=""'; ?> value="{{$location->id}}">{{$location->area_name}}</option>
                                @endif
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="input-group col-md-2 pull-right" style="width: 11%">
                            @if(Auth::user()->role_id <> 5)
                            <input class="form-control order_filter ui-autocomplete-input" placeholder="Size" value="{{Input::get('size_filter')}}" id="order_size" autocomplete="off"  type="text">
                            <input type='hidden' placeholder="Size" value="{{Input::get('size_filter')}}" id="order_size_temp" autocomplete="off" name="size_filter" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" onclick="javascript:document.getElementById('orderForm').submit();">
                                    <i class="fa fa-search" id="search_icon"></i>
                                </button>
                            </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <hr style="border-color: #ddd -moz-use-text-color -moz-use-text-color;">
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
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif

                        @if(sizeof($allorders)==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no orders have been added.
                        </div>
                        @else  
                        @if( Auth::user()->role_id <> 5) 
                        <div class="table-responsive tablepending">
                            <table id="table-example" class="table table-hover order-data-table">
                                <?php
                                $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1;
                                ?>
                                @if(Input::get('order_filter') == 'pending' | Input::get('order_status') == 'pending' | (Input::get('order_status') == '' && Input::get('order_filter') == '' && Input::get('territory_filter') == '') && $qstring_sort_type_order<>'completed')
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
                                        @if( Auth::user()->role_id <> 5)
                                        <th class="text-center">Create Delivery Order</th>
                                        @endif
                                        <th class="text-center" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @endif
                                    @if(Input::get('order_filter') == 'approval')
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tally Name</th><th>Alias Name</th>
                                        <th>Mobile</th>
                                        <th>Delivery Location</th>
                                        <th>Total Quantity</th>
                                        <th>Created By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @endif
                                    @if((Input::get('order_filter') == 'completed' | Input::get('order_status') == 'completed') || Input::get('territory_filter') != '' || $qstring_sort_type_order == 'completed')
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tally Name</th><th>Alias Name</th>
                                        <th>Total Quantity</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Order By</th>
                                        <th class="text-center">Actions</th>
                                        @if(Auth::user()->role_id == 5)
                                        <th class="text-center">Track Order</th>
                                        @endif
                                    </tr>
                                </thead>
                                @endif
                                @if(Input::get('order_filter') == 'cancelled')
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



                                    @foreach($allorders as  $key =>$order)                              
                                    @if(isset($order->order_status) && $order->order_status == 'pending' && $order->is_approved =='yes' &&  Input::get('territory_filter') == '')

                                    <tr id="order_row_{{$order->id}}">
                                        <td>
                                            <span class="{{($order->flaged==true)?'filled_star flags':'empty_star flags'}}" data-orderid="{{$order->id}}" ></span>
                                        </td>
                                        <td>{{$k++}}</td>
                                        <td>
                                            @if(isset($order["customer"]))
                                            {{($order["customer"]->tally_name != "")? $order["customer"]->tally_name : $order["customer"]->owner_name}}
                                            @else
                                            {{"Anonymous User"}}
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if(isset($order["customer"]))
                                            {{$order['customer']['phone_number1']}}
                                            @else
                                            {{"Anonymous User"}}
                                            @endif
                                        </td>
                                        @if($order->delivery_location_id !=0)
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order->delivery_location_id ==0 )
                                        <td class="text">{{$order['other_location']}}</td>
                                        @else
                                        <td class="text">{{Other}}</td>

                                        @endif
                                        
                                       
                                        <td>{{ round($order->total_quantity, 2) }}</td>
                                        <td>{{ round($order->pending_quantity, 2) }}</td>                                        
                                        @if( Auth::user()->role_id <> 5)
                                        <td class="text-center">
                                            <a href="{{url('create_delivery_order/'.$order->id)}}" class="table-link" title="Create Delivery order">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id == 0 ||Auth::user()->role_id == 1  || Auth::user()->role_id == 5 || Auth::user()->role_id == 4)
                                            <a href="{{url('orders/'.$order->id.'/edit')}}" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if(Auth::user()->role_id <> 5)
                                            <?php
                                             $is_allinclusive = 0;
                                             foreach ($order->all_order_products as $dord){
                                                 if(isset($dord->vat_percentage) && $dord->vat_percentage != "" && $dord->vat_percentage > 0){
                                                     $is_allinclusive = 1;
                                                     break;
                                                 }
                                             }
                                            ?>
                                            <a href="#" class="table-link" title="manual complete" data-toggle="modal" data-target="#cancel_order_modal" onclick="cancel_order_row({{$order->id}},{{$is_allinclusive}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
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
                                    @endforeach

                                    @foreach($allorders as $order)  
                                    @if(isset($order->order_status) && $order->order_status == 'pending' && $order->is_approved =='no' && Input::get('territory_filter') == '')
                                    @if($k==1)                                   

                                    @endif
                                    <tr id="order_row_{{$order->id}}">
                                    <tr id="order_row_{{$order->id}}">
                                        <td>{{$k++}}</td>
                                        <td>
                                            @if(isset($order["customer"]))
                                            {{($order["customer"]->tally_name != "")? $order["customer"]->tally_name : $order["customer"]->owner_name}}
                                            @else
                                            {{"Anonymous User"}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($order["customer"]))
                                            {{$order['customer']['phone_number1']}}
                                            @else
                                            {{"Anonymous User"}}
                                            @endif
                                        </td>
                                        @if($order->delivery_location_id !=0)
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order->delivery_location_id ==0 )
                                        <td class="text">{{$order['other_location']}}</td>
                                        @endif
                                        <td>{{ round($order->total_quantity, 2) }}</td>
                                        <td>{{ ($order['createdby']->id  !== null? $order['createdby']->first_name." ".$order['createdby']->last_name:'' ) }}</td>
                                        <td> 
                                            @if( Auth::user()->role_id == 0)
                                            <a href="{{ Url::action('OrderController@show', ['id' => $order->id,'way' => 'approval']) }}" class="/*table-link*/ btn btn-primary btn-sm" title="view">View
    <!--                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>-->
                                            </a>
                                            <a href="{{ Url::action('OrderController@edit', ['id' => $order->id,'way' => 'approval']) }}" class="btn btn-primary btn-sm" href="" title="Approve" > Approve </a>

                                            <a href="#" class="btn btn-danger btn-sm" title="Reject" data-toggle="modal" data-target="#delete_orders_modal" onclick="reject_order_row({{$order->id}})">
                                                Reject </a>
                                            @else
                                            <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id != 2 && Auth::user()->role_id != 3)
                                            <a href="{{url('orders/'.$order->id.'/edit')}}" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                             @endif
                                            @endif
                                        </td>

                                    </tr> 


                                    </tr>

                                    @endif
                                    @endforeach



                                    @foreach($allorders as $order)
                                    @if((isset($order->order_status) && $order->order_status == 'completed') || (Input::get('territory_filter') != ''))

                                    <tr id="order_row_{{isset($order->id) ? $order->id:''}}">
                                        <td>{{isset($k)?$k++:''}}</td>                                    
                                        <td>{{(isset($order["customer"]->tally_name) && $order["customer"]->tally_name != "")? $order["customer"]->tally_name : "Anonymous User"}} </td>
                                        @if(count($pending_orders) > 0)
                                        @foreach($pending_orders as $porder)
                                        @if($porder['id'] == $order->id)
                                        <td>{{ isset($porder['total_quantity']) ? round($porder['total_quantity'], 2):'0.00' }}</td>
                                        @endif
                                        @endforeach
                                        @else
                                        <td></td>
                                        @endif
                                        <td>{{isset($order['customer']['phone_number1']) ? $order['customer']['phone_number1']:''}}</td>
                                        @if(isset($order['delivery_location']) && $order['delivery_location']['area_name'] !="")
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @else
                                        <td class="text">{{isset($order['other_location']) ? $order['other_location']:''}}</td>
                                        @endif
                                        <td class="text"><?php
                                            foreach ($users as $u) {
                                                if ($u['id'] == $order['created_by']) {
                                                    echo $u['first_name'];
                                                }
                                            }
                                            ?></td>
                                        <td class="text-center">
                                            <?php $order_id = isset($order->id) ? $order->id : ''; ?>
                                            <a href="{{url('orders/'.$order_id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal" onclick="delete_order_row({{$order_id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <?php $order_id = isset($order->id) ? $order->id : ''; ?>

                                            @if(Auth::user()->role_id == 5 )
                                            <!--                                        <a href="#" class="table-link" title="track_order" data-toggle="modal" data-target="#track_orders_modal" onclick="track_order_row({{$order_id}})">
                                                                                        <span class="fa-stack">
                                                                                            <i class="fa fa-square fa-stack-2x"></i>
                                                                                            <i class="fa fa-truck fa-stack-1x fa-inverse"></i>
                                                                                        </span>
                                                                                    </a>-->

                                            <a href="{{url('order/'.$order->id.'-track')}}" class="table-link" title="Track Order">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-truck fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach


                                    @foreach($allorders as $order)
                                    @if(isset($order->order_status) && $order->order_status == 'cancelled' && Input::get('territory_filter') == '')
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
                                            @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
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
                                                <label><input type="checkbox" id="is_sendsms" value="true" name="sendsms" checked><span title="SMS would be sent to Party" class="checksms smstooltip">SMS</span></label>
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
                                        <form method="post" class="delete_order_form" action="#" id="delete_order_row">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input class="form-control" name="way" id="way" type="hidden"/>
<!--                                            <input name="_method" type="hidden" value="DELETE">-->
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <input type="hidden" name="mobile" value="{{auth()->user()->mobile_number}}"/>
                                                    <input type="hidden" name="user_id" id="user_id"/>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password" id="pwdr"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default delete_orders_modal_submit">Yes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" id="track_orders_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                        </div>
                                        <form method="post" class="track_order_form" action="#" id="track_order_row">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
<!--                                            <input name="_method" type="hidden" value="DELETE">-->
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <input type="hidden" name="mobile" value="{{auth()->user()->mobile_number}}"/>
                                                    <input type="hidden" name="user_id" id="user_id"/>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password" id="pwdr"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default delete_orders_modal_submit">Yes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




                            <span class="pull-right">
                                <?php
//                                if (Input::get('order_filter') != '') {
//                                    $allorders->appends(array('order_filter' => Input::get('order_filter')))->render();
//                                }
//                                if (Input::get('location_filter') != '') {
//                                    $allorders->appends(array('location_filter' => Input::get('location_filter')))->render();
//                                }
//                                if (Input::get('party_filter') != '') {
//                                    $allorders->appends(array('party_filter' => Input::get('party_filter')))->render();
//                                }
//                                if (Input::get('fulfilled_filter') != '') {
//                                    $allorders->appends(array('fulfilled_filter' => Input::get('fulfilled_filter')))->render();
//                                }
//                                if (Input::get('size_filter') != '') {
//                                    $allorders->appends(array('size_filter' => Input::get('size_filter')))->render();
//                                }
//                                if (Input::has('flag') && Input::get('flag') == 'true') {
//                                    $allorders->appends(array('flag' => Input::get('flag')))->render();
//                                }
                                echo $allorders->appends(Input::except('page'))->render();
//                                echo $allorders->render();
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
                        @if( Auth::user()->role_id == 5)
                        <div class="table-responsive tablepending">
                            <table id="table-example" class="table table-hover">
                                <?php
                                $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1;
                                ?>
                                <thead>
                                    <tr>
                                        @if(Input::has('flag') && Input::get('flag') == 'true')
                                        <th><a href="{{url('orders?flag=false')}}">Flag</a></th>
                                        @else
                                        <th><a href="{{url('orders?flag=true')}}">Flag</a></th>
                                        @endif
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Tally Name</th>
                                        <th>Mobile </th>
                                        <th>Delivery Location</th>
                                        <th>Total Quantity</th>
                                        <th>Pending Quantity</th>
                                        @if( Auth::user()->role_id <> 5)
                                        <th class="text-center">Create Delivery Order</th>
                                        @endif
                                        <th class="text-center" style="width: 15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($allorders as $order)                              
                                    @if(isset($order->order_status))
                                    @if($k==1)

                                    @endif
                                    <tr id="order_row_{{$order->id}}">
                                        <td>
                                            <span class="{{($order->flaged==true)?'filled_star flags':'empty_star flags'}}" data-orderid="{{$order->id}}" ></span>
                                        </td>
                                        <td>{{$k++}}</td>
                                        <td>{{date("F jS, Y", strtotime($order->updated_at)) }}</td>
                                        <td>
                                            @if(isset($order["customer"]))
                                            {{($order["customer"]->tally_name != "")? $order["customer"]->tally_name : $order["customer"]->owner_name}}
                                            @else
                                            {{"Anonymous User"}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($order["customer"]))
                                            {{$order['customer']['phone_number1']}}
                                            @else
                                            {{"Anonymous User"}}
                                            @endif
                                        </td>

                                        @if($order->delivery_location_id !=0)
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order->delivery_location_id ==0 )
                                        <td class="text">{{$order['other_location']}}</td>
                                        @endif
                                        <td>{{ round($order->total_quantity, 2) }}</td>
                                        <td>{{ round($order->pending_quantity, 2) }}</td>                                        
                                        @if( Auth::user()->role_id <> 5)
                                        <td class="text-center">
                                            <a href="{{url('create_delivery_order/'.$order->id)}}" class="table-link" title="Create Delivery order">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        @endif
                                        <td class="text-center">

                                            <a href="{{url('orders/'.$order->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id == 0 ||Auth::user()->role_id == 1  || Auth::user()->role_id == 5 )

                                            @if($order->order_status == 'pending')
                                            <a href="{{url('orders/'.$order->id.'/edit')}}" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if($order->order_status == 'cancelled')
                                            <a href="javascript:void(0)" class="table-link" title="Non Editible">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                    <i class="fa fa-ban fa-stack-2x fa-rotate-90 text-danger"></i>
                                                </span>
                                            </a>
                                            @endif

                                            @if($order->order_status == 'completed')
                                            <a href="javascript:void(0)" class="table-link" title="Non Editible">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                    <i class="fa fa-ban fa-stack-2x fa-rotate-90 text-danger"></i>
                                                </span>
                                            </a>
                                            @endif


                                            @if(Auth::user()->role_id <> 5)

                                            <a href="#" class="table-link" title="manual complete" data-toggle="modal" data-target="#cancel_order_modal" onclick="cancel_order_row({{$order->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_orders_modal" onclick="delete_order_row({{$order->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            <a href="{{url('order/'.$order->id.'-track')}}" class="table-link" title="Track Order">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    @if($order->is_approved=='no')
                                                    <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
                                                    @else
                                                    <i class="fa fa-truck fa-stack-1x fa-inverse"></i>
                                                    @endif
                                                </span>
                                            </a>

                                        </td>
                                    </tr>
                                    @endif

                                    @endforeach
                                </tbody>
                            </table>

                            <span class="pull-right">
                                <?php
//                               
                                echo $allorders->appends(Input::except('page'))->render();
//                                echo $allorders->render();
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop