@extends('layouts.master')
@section('title','Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Dashboard</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Dashboard</h1>
                    <div class="pull-right top-page-ui">                        
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 2)
                        <div class="row text-center ">
                            <div class="col-md-12">
                                <a href="{{url('orders/create')}}" class="btn btn-primary btn-lg text-center button_indexright">
                                    <i class="fa fa-plus-circle fa-lg"></i> Place Order
                                </a>
                                <a href="{{url('inquiry/create')}}" class="btn btn-primary btn-lg text-center ">
                                    <i class="fa fa-plus-circle fa-lg"></i> Add Inquiry
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role_id == 0)
        <div class="row">
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('orders')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-user red-bg"></i>
                                    <span class="headline">Total Order </span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                            
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('inquiry?inquiry_filter=Pending')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-money green-bg"></i>
                        <span class="headline">Pending Inquiries</span>
                        <span class="value">
                            <span class="" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">                  {{round($inquiry_pending_sum,2)}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('orders?order_filter=pending&party_filter=&fulfilled_filter=&location_filter=&size_filter=')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-shopping-cart emerald-bg"></i>
                        <span class="headline">Pending Order</span>
                        <span class="value">
                            <span class="" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                              
                                {{round($order_pending_sum,2)}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('inquiry')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-eye yellow-bg"></i>
                                    <span class="headline">Total Inquiries </span>
                                    <span class="value">
                                        <span class="timer" data-from="539" data-to="12526" data-speed="1100">
                                          
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('delivery_order')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa  fa-tasks red-bg"></i>
                                    <span class="headline">Total Delivery Order</span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                           
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_order?_token=yx1phrqi9pseT2vXrbHWBfhcyyN7YPol1EMJdj6k&order_status=Inprocess')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-archive emerald-bg"></i>
                        <span class="headline">Pending Delivery Order</span>
                        <span class="value">
                            <span class="" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{round($deliver_pending_sum,2)}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('delivery_challan?status_filter=completed')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-desktop green-bg"></i>
                                    <span class="headline">Total Challan</span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                           
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('purchase_orders')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-file-text-o yellow-bg"></i>
                                    <span class="headline">Total Purchase Order</span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                           
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
        </div>
        @endif
        <br/>
        <hr>  
        <!--graph-->
        <div class="row text-center ">
            <div class="col-md-12">
                <div class="col-lg-4 col-sm-6 col-xs-12">
                    <h4>Inquiry</h4>
                    <div id="inquiry" style="height: 250px;"></div>
                </div>
                <div class="col-lg-4 col-sm-6 col-xs-12">
                    <h4>Order</h4>
                    <div id="order" style="height: 250px;"></div>
                </div>
                <div class="col-lg-4 col-sm-6 col-xs-12">
                    <h4>Delivery Challan</h4>
                    <div id="deliverychallan" style="height: 250px;"></div>
                </div>
            </div>
        </div>
        <br/>
        <br/>
    </div>
</div>


<script type="text/javascript">
    var inquiry_stats = <?php echo json_encode(isset($inquiries_stats_all) ? $inquiries_stats_all : ''); ?>;
    var order_stats = <?php echo json_encode(isset($orders_stats_all) ? $orders_stats_all : ''); ?>;
    var delivery_challan_stats = <?php echo json_encode(isset($delivery_challan_stats_all) ? $delivery_challan_stats_all : ''); ?>;
                            
</script>
@endsection
