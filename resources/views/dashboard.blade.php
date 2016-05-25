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
                </div>
            </div>
        </div>
        @if(Auth::user()->role_id == 0)
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('orders')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-user red-bg"></i>
                        <span class="headline">Total Order </span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{$order}}
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('orders?order_filter=pending&party_filter=&fulfilled_filter=&location_filter=&size_filter=')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-shopping-cart emerald-bg"></i>
                        <span class="headline">Pending Order</span>
                        <span class="value">
                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                                {{$pending_order}}
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('inquiry?inquiry_filter=Pending')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-money green-bg"></i>
                        <span class="headline">Pending Inquiries</span>
                        <span class="value">
                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                                {{$pending_inquiry}}
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('inquiry')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-eye yellow-bg"></i>
                        <span class="headline">Total Inquiries </span>
                        <span class="value">
                            <span class="timer" data-from="539" data-to="12526" data-speed="1100">
                                {{$inquiry}}
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_order')}}">
                    <div class="main-box infographic-box">
                        <i class="fa  fa-tasks red-bg"></i>
                        <span class="headline">Total Delivery Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{$deliver_sum}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_order?_token=yx1phrqi9pseT2vXrbHWBfhcyyN7YPol1EMJdj6k&order_status=Inprocess')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-archive emerald-bg"></i>
                        <span class="headline">Pending Delivery Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{ $deliver_pending_sum }}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_challan')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-desktop green-bg"></i>
                        <span class="headline">Total Challan</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{$challan_sum}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('purchase_orders')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-file-text-o yellow-bg"></i>
                        <span class="headline">Total Purchase Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{$purc_order_sum}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
        </div>
        @endif
        <br/>
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
        <br/>
        <br/>
    </div>
</div>
@endsection
