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
                                        <td><span>Date:</span> {{ substr($delivery_data[0]->created_at, 0,10) }}</td>
                                    </tr>
                                    <tr><td><span>Serial Number: </span> 
                                            @if($delivery_data[0]->serial_no != "")
                                            {{$delivery_data[0]->serial_no}} 
                                            @else 
                                            {{'--'}}
                                            @endif
                                        </td>
                                    </tr>
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
                                            <span>Product(Alias)</span>
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
                                    <?php $grand = 0; ?>
                                    @foreach($delivery_data[0]['delivery_product'] as $product)
                                    @if($product->order_type =='delivery_order')
                                    <tr>
                                        <td> {{ $product['product_category']['product_sub_category']->alias_name}}</td>
                                        <td>{{$product->present_shipping}}</td>
                                        <td>
                                            @foreach($units as $unit)
                                            @if($unit->id == $product->unit_id) {{ $unit->unit_name }} @endif
                                            @endforeach
                                        </td>
                                        <td>{{$product->price}}</td>
                                        <td>{{$product->remarks}}</td>
                                    </tr>

                                    <?php
                                    $grand = $grand + $product->present_shipping * $product->price;
                                    $grand = $grand - $grand * $delivery_data[0]->vat_percentage / 100;
                                    ?>
                                    @endif
                                    @endforeach

                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                    @if($delivery_data[0]->vat_percentage != "" || $delivery_data[0]->vat_percentage > 0)  
                                    <tr>
                                        <td><span>Plus VAT: </span>    
                                        Yes                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>VAT Percentage: </span>{{ $delivery_data[0]->vat_percentage }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td><span>Plus VAT: </span>    
                                        No                                            
                                        </td>
                                    </tr>
                                    @endif                                    
                                    
                                    <tr>
                                        <td><span>Grand Total: </span> {{ $grand }}</td>
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