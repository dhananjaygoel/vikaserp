@extends('layouts.master')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Dashboard</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Dashboard</h1>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('orders')}}"><div class="main-box infographic-box">
                        <i class="fa fa-user red-bg"></i>
                        <span class="headline">Total Order </span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                2562
                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('pending_orders')}}"><div class="main-box infographic-box">
                        <i class="fa fa-shopping-cart emerald-bg"></i>
                        <span class="headline">Pending Order</span>
                        <span class="value">
                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                                658
                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('pending_inquiry')}}"><div class="main-box infographic-box">
                        <i class="fa fa-money green-bg"></i>
                        <span class="headline">Pending Inquiries</span>
                        <span class="value">
                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                                123
                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('inquiry')}}"><div class="main-box infographic-box">
                        <i class="fa fa-eye yellow-bg"></i>
                        <span class="headline">Total Inquiries </span>
                        <span class="value">
                            <span class="timer" data-from="539" data-to="12526" data-speed="1100">
                                12526
                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_orders')}}"><div class="main-box infographic-box">
                        <i class="fa  fa-tasks red-bg"></i>
                        <span class="headline">Total Delivery Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                2Ton
                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('pending_delivery_orders')}}"><div class="main-box infographic-box">
                        <i class="fa fa-archive emerald-bg"></i>
                        <span class="headline">Pending Delivery Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                1Ton


                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_order_challan')}}"><div class="main-box infographic-box">
                        <i class="fa fa-desktop green-bg"></i>
                        <span class="headline">Total Challan</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                3Ton

                            </span>
                        </span>
                    </div></a>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('purchase_orders')}}"><div class="main-box infographic-box">
                        <i class="fa fa-file-text-o yellow-bg"></i>
                        <span class="headline">Total Purchase Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                4Ton


                            </span>
                        </span>
                    </div></a>
            </div>
        </div>

    </div>
</div>
@endsection
