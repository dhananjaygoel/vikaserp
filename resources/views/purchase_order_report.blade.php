@extends('layouts.master')
@section('title','Purchase Order Report')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Purchase Orders Report</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Purchase Orders Report</h1>
                    <div class="filter-block pull-right">
                        <div class="form-group pull-left">
                            <div class="col-md-12">
                                <form method="GET" action="{{url('purchase_order_report')}}">
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
                                <form method="GET" action="{{url('purchase_order_report')}}">
                                    <select class="form-control" id="order_for_filter" name="order_for_filter" onchange="this.form.submit();">
                                        <option value="" selected="">Order For</option>
                                        <option value="warehouse">Warehouse</option>
                                        <option value="direct">Direct</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="table1">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($purchase_orders) ==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no pending purchase orders exists.
                        </div>
                        @else
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Party Name</th>
                                        <th>Quantity</th>
                                        <th>Remarks</th>
                                        <th>Delivery Location </th>
                                        <th>Order By </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($purchase_orders->currentPage() - 1) * $purchase_orders->perPage() + 1; ?>
                                    @foreach($purchase_orders as $order)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        @if(isset($order->serial_number))
                                        <td>{{$order->serial_number}}</td>
                                        @else
                                        <td>{{"--"}}</td>
                                        @endif
                                        <td>{{date("jS F, Y H:i a", strtotime($order->created_at))}}</td>
                                        <td>{{$order['customer']->owner_name}}</td>
                                        <td>{{$order['purchase_products']->sum('quantity')}}</td>
                                        <td>{{$order->remarks}}</td>
                                        <td>{{$order['delivery_location']->area_name}}</td>
                                        <td>{{$order['user']->first_name}}</td>
                                    </tr>
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
@stop