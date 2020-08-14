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
        @if (Session::has('flash_success_message'))
            <div id="flash_success_message" class="alert alert-success no_data_msg_container">{{ Session::get('flash_success_message') }}</div>
        @endif
        @if(Session::has('error'))
            <div class="clearfix"> &nbsp;</div>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <strong> {{ Session::get('error') }} </strong>
            </div>
        @endif
        <?php
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if (getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if (getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if (getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if (getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if (getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';

            $ip = App\Security::all();
            if (isset($ip) && !$ip->isEmpty()) {
                foreach ($ip as $key => $value) {
                    $ip_array[$key] = $value->ip_address;
                }
            } else {
                $ip_array = array($ipaddress);
            }
            // print_r($ip_array);
            // exit;
            ?>
            @if(in_array($ipaddress, $ip_array) || Auth::user()->role_id == 0)
        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 2)
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
                            <span class="" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30"> 
                                {{round($inquiry_pending_sum,2)}}Ton
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
        @endif
        <br/>
        <br/>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
<?php 
$login_count = Session::has('login_count')?Session::get('login_count'):false;
if($login_count == 1){
    Session::forget('login_count');
    Session::put('login_count',2);?>
    history.pushState(null, null, location.href); 
    history.back(); 
    history.forward(); 
    window.onpopstate = function () { history.go(1); }; 
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };  
<?php } ?>
</script>
@endsection
