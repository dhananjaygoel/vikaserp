<?php
//echo'<pre>';
//print_r($delivery_location->toArray());
//echo '</pre>';
//exit;
?>
@extends('layouts.master')
@section('title','Pending Order Report')
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
                            <div class="col-md-4">
                                <form action="{{url('pending_order_report')}}" method="GET">
                                    <select class="form-control" id="user_filter3" name="party_filter" onchange="this.form.submit();">
                                         <option value="" selected="">Select Party</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->owner_name}}</option>
                                        @endforeach                                        
                                    </select>                                    
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="{{url('pending_order_report')}}" method="GET">
                                    <select class="form-control" id="user_filter3" name="fulfilled_filter" onchange="this.form.submit();">
                                        <option value="" selected="">Fulfilled</option>
                                        <option value="0" >Warehouse</option>
                                        <option value="all" >Direct</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->owner_name}}</option>
                                        @endforeach
                                    </select>                                    
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form action="{{url('pending_order_report')}}" method="GET">
                                    <select class="form-control" id="user_filter3" name="location_filter" onchange="this.form.submit();">
                                         <option value="" selected="">Select Location</option>
                                        @foreach($delivery_location as $location)                                        
                                            <option value="{{$location->id}}">{{$location->area_name}}</option>
                                        @endforeach 
                                                                                
                                    </select>                                    
                                </form>
                            </div>
<!--                            <div class="col-md-3">
                                <form action="{{url('pending_order_report')}}" method="GET">
                                    <select class="form-control" id="user_filter3" name="size_filter" onchange="this.form.submit();">
                                         <option value="" selected="">Select Size</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->owner_name}}</option>
                                        @endforeach                                        
                                    </select>                                    
                                </form>
                            </div>-->
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    @if(sizeof($allorders)==0)
                    <div class="alert alert-info no_data_msg_container">
                        Currently no orders to show.
                    </div>
                    @else
                    @if (Session::has('flash_message'))
                    <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                    @endif
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive tablepending">
                            <table id="table-example" class="table table-hover">
                                <?php $k = 1; ?>
                                @foreach($allorders as $order)
                                
                                @if($k==1)
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Party</th>
                                        <th>Quantity</th>
                                        <th>Remarks</th>
                                        <th>Delivery Location</th>
                                        <th>Order By</th>
                                        
                                        
                                        
                                    </tr>
                                </thead><tbody>
                                    @endif


                                    <tr>
                                        
                                        <td>{{$k++}}</td>
                                        
                                        <th><?php $order_date = strtotime($order['created_at']);
                                            echo date('d F Y',$order_date);
                                        ?></th>
                                        <td>{{$order['customer']->owner_name}}</td>
                                        <td><?php
                                        $total_quantity = 0;
                                        foreach ($order['all_order_products'] as $key => $product) {
                                            $total_quantity = $total_quantity + $product['quantity'];
                                        }
                                        echo $total_quantity;
                                        ?></td>
                                        <td>{{$order['remarks']}}</td>
                                        @if($order['delivery_location']['area_name'] !="")
                                        
                                        <td class="text">{{$order['delivery_location']['area_name']}}</td>
                                        @elseif($order['delivery_location']['area_name'] =="")
                                        <td class="text">{{$order['other_location']}}</td>
                                        @endif
                                        <td class="text"><?php 
                                            foreach($users as $u)
                                            {
                                                if($u['id'] == $order['created_by']){
                                                    echo $u['first_name'];
                                                }
                                            }
                                        ?></td>
                                        
                                        
                                    </tr>
                                     
                                



                                
                                
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
