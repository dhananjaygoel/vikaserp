@extends('layouts.master')
@section('title','View Delivery Order')
@section('content')
<div class="row">						
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{'delivery_order'}}">Delivery Order</a></li>
                    <li class="active"><span>View Delivery Order</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Delivery Order</h1>                                 
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('DeliveryOrderController@edit',['id'=>$delivery_data[0]->id])}}" class="btn btn-primary pull-right">
                            Edit Delivery Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">
                                <tbody>                    
                                    <tr>
                                        <td><span>Customer Name:</span> {{ $delivery_data[0]['customer']->owner_name }}</td>
                                    </tr>
                                    <tr><td><span>Contact Person: </span>{{ $delivery_data[0]['customer']->contact_person }}</td></tr>
                                    <tr>
                                        <td><span>Date:</span> 30 April 2015</td>
                                    </tr>
                                    <tr><td><span>Serial Number: </span> 
                                            @if($delivery_data[0]->serial_no != "")
                                            {{$delivery_data[0]->serial_no}} 
                                            @else 
                                            {{'--'}}
                                            @endif
                                        </td></tr>
                                    <tr>
                                        <td><span>Mobile Number: </span>{{$delivery_data[0]['customer']->phone_number1}}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="underline"> Product Details </span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>   
                                    <tr class="headingunderline">
                                        <td>
                                            <span>Product</span>
                                        </td>
                                        <td>
                                            <span>Quantity</span>
                                        </td>
                                        <td>
                                            <span>Unit</span>
                                        </td>
                                        <td>
                                            <span>Price</span>
                                        </td>

                                        <td><span>Remark</span></td>
                                    </tr>
                                    @foreach($delivery_data[0]['delivery_product'] as $product)
                                    <tr>
                                        <td> {{ $product['product_category']->product_category_name}}</td>
                                        <td>{{$product->quantity}}</td>
                                        <td>
                                            @foreach($units as $unit)
                                            @if($unit->id == $product->unit_id) {{ $unit->unit_name }} @endif
                                            @endforeach
                                        </td>
                                        <td>{{$product->price}}</td>
                                        <td>{{$product->remarks}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                <td><span>Plus VAT: </span>Yes **</td>
                                </tr>
                                <tr>
                                    <td><span>VAT Percentage: </span>{{ $delivery_data[0]->vat_percentage }}</td>
                                </tr>
                                <tr>
                                    <td><span>Grand Total: </span> $25000</td>
                                </tr>
                                <tr><td><b>Vehicle Name:</b> {{ $delivery_data[0]->vehicle_number }} </td> </tr>
                                <tr><td><b>Driver Name:</b> {{ $delivery_data[0]->driver_name }}  </td> </tr>
                                <tr><td><b>Driver Contact:</b> {{ $delivery_data[0]->driver_contact_no }} </td> </tr>
                                <tr>
                                    <td><span>Delivery Location: </span>

                                        @foreach($delivery_locations as $location)
                                        @if($location->id == $delivery_data[0]->delivery_location_id)

                                        {{$location->area_name}}
                                        @endif
                                        @endforeach

                                    </td>
                                </tr>
                                <tr>
                                    <td><span>Remark: </span>{{ $delivery_data[0]->remarks }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop