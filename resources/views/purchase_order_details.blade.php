@extends('layouts.master')
@section('title','Purchase Order Details')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchase_orders')}}">Purchase Order</a></li>
                    <li class="active"><span>Purchase Order Details</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{ url('purchase_orders/'.$purchase_orders->id.'/edit') }}" class="btn btn-primary pull-right">
                            Edit Purchase Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="table-example" class="table table-hover customerview_table  ">
                                    <tbody>
                                        <tr><td><span>Supplier Name:</span> {{$purchase_orders['customer']->owner_name}}{{'-'.$purchase_orders['customer']->tally_name}}</td></tr>
                                        <tr><td><span>Contact Person:</span>{{$purchase_orders['customer']->contact_person}}</td></tr>
                                        <tr><td><span>Mobile Number: </span>{{$purchase_orders['customer']->phone_number1}}</td></tr>

                                        @if($purchase_orders['customer']->credit_period > 0 || $purchase_orders['customer']->credit_period != "")
                                        <tr> <td><span>Credit Period(Days): </span>{{$purchase_orders['customer']->credit_period}}</td></tr>
                                        @endif

                                        <tr><td><span class="underline">Ordered Product Details </span></td>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span> Product(Alias) </span></td>
                                            <td><span> Qty</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Price</span></td>
                                            <td class="widthtable"><span>Remark</span></td>
                                        </tr>
                                        <?php $total = 0; ?>

                                        @foreach($purchase_orders['purchase_products'] as $product_data)
                                        @if($product_data->order_type == 'purchase_order')

                                        <tr>
                                            <td>{{$product_data['purchase_product_details']->alias_name}}</td>
                                            <td>{{$product_data->quantity}}</td>
                                            <td>{{$product_data['unit']->unit_name}}</td>
                                            <td>{{$product_data->price}}</td>
                                            <td>{{$product_data->remarks}}</td>
                                        </tr>
                                        <?php
                                        $total += $product_data->quantity * $product_data->price;
                                        ?>

                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">
                                    <tbody>
                                        @if($purchase_orders['vat_percentage'] == 0)
                                        <tr><td><span>Plus VAT: </span>No</td></tr>
                                        @elseif($purchase_orders['vat_percentage'] != 0)
                                        <tr><td><span>Plus VAT: </span>Yes</td></tr>
                                        <tr><td><span>VAT Percentage: </span>{{$purchase_orders['vat_percentage']."%"}}</td></tr>
                                        @endif

                                        <!--<tr><td><span>Grand Total: </span> {{$total}}</td></tr>-->
                                        <tr><td><span>Expected Delivery Date: </span>{{date("jS F, Y", strtotime($purchase_orders['expected_delivery_date']))}}</td></tr>
                                        <tr><td><span>Delivery Location: </span>{{$purchase_orders['delivery_location']->area_name}}</td></tr>
                                        <tr><td><span>Remark: </span>{{$purchase_orders['remarks']}}</td></tr>
                                    </tbody>
                                </table>
                                <a href="{{url('purchase_orders')}}" class="btn btn-default form_button_footer">Back</a>
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