<?php
//echo'<pre>';
//print_r($order->toArray());
//echo '</pre>';
//exit;
?>
@extends('layouts.master')
@section('title','Order Details')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Order Details </span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{url('orders/'.$order->id.'/edit')}}" class="btn btn-primary pull-right">
                            Edit Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-dashboard"></i> &nbsp; View Order </h2>
                    </header>            
                    <div class="main-box-body clearfix">

                        <div class="inquiry_table col-md-12">

                            <div class="table-responsive">
                                <table id="table-example" class="table table-hover customerview_table  ">


                                    <tbody> 
                                        @if($order->order_source == 'warehouse')
                                        <tr><td><span><b>Warehouse: </b></span> yes</td></tr>
                                        <tr><td><span><b>Supplier Name:</b></span> Warehouse</td></tr>
                                        @elseif($order->order_source == 'supplier')
                                        @foreach($customers as $customer)
                                        @if($customer->id == $order->supplier_id)
                                        <tr><td><span><b>Supplier Name:</b></span>  {{$customer->owner_name}} </td></tr>
                                        @endif
                                        @endforeach
                                        @endif
                                        @foreach($customers as $customer)
                                        @if($customer->id == $order->customer_id)
                                        <tr><td><span><b>Customer Name:</b></span> {{$customer->owner_name}} </td></tr>
                                        <tr><td><span><b>Contact Person: </b></span> {{$customer->contact_person}}</td></tr>
                                        <tr><td><span><b>Mobile Number: </b></span>{{$customer->phone_number1}}</td></tr>
                                        <tr> <td><span><b>Credit Period: </b></span>{{$customer->credit_period}}</td></tr>   
                                        @endif
                                        @endforeach                                        



                                        <tr>
                                            <td><span class="underline">Ordered Product Details </span></td>

                                        </tr>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">


                                    <tbody>   
                                        <tr class="headingunderline">


                                            <td>
                                                <span> Product</span>
                                            </td>
                                            <td>
                                                <span> Qty</span>
                                            </td>
                                            <td>
                                                <span>Unit</span>
                                            </td>

                                            <td>
                                                <span>Price</span>
                                            </td>
                                            <td class="widthtable">
                                                <span>Remark</span>
                                            </td>

                                        </tr>
                                        <?php $total=0;?>
                                        @foreach($order['all_order_products'] as $key=>$product)
                                        <tr id="add_row_{{$key}}" class="add_product_row">

                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    {{$product['product_category']->product_category_name}}

                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    {{$product->quantity}}
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group ">
                                                    {{$product['unit']->unit_name}} 
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    {{$product->price}}
                                                    <?php $total= $total+$product->price;?>
                                                </div>
                                            </td>
                                            <td class="col-md-4">
                                                <div class="form-group">
                                                    {{$product->remarks}}
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">


                                    <tbody> 
                                        @if($order->vat_percentage !=0)
                                        <tr>

                                            <td><span>Plus VAT: </span>Yes</td>

                                        </tr>
                                        <tr>
                                            
                                            <td><span>VAT Percentage: </span>{{$order->vat_percentage}}</td>

                                        </tr>
                                        @elseif($order->vat_percentage ==0)
                                        <tr>

                                            <td><span>Plus VAT: </span>NO</td>

                                        </tr>
                                        <tr>
                                          
                                            <td><span>VAT: </span>0</td>

                                        </tr>
                                        @endif
                                        <tr>
                                            
                                            <td><span>Grand Total: </span>{{$total}} </td>

                                        </tr>

                                        <tr>
                                            <td><span>Estimated Delivery Date: </span>{{$order->estimated_delivery_date}}</td>

                                        </tr>   

                                        <tr>
                                            <td><span>Expected Delivery Date: </span>{{$order->expected_delivery_date}}</td>

                                        </tr>      
                                        <tr>
                                            @foreach($delivery_location as $location)
                                            @if($order->delivery_location_id == $location->id)
                                            <td><span>Delivery Location: </span>{{$location->area_name}}</td>

                                            @endif
                                            @endforeach                                          

                                        </tr>
                                        <tr>
                                            <td><span>Remark: </span>{{$order->remarks}}</td>

                                        </tr>


                                    </tbody>
                                </table>
                            </div>

                        </div>


                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop