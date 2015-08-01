@extends('layouts.master')
@section('title','View Delivery Order')
@section('content')
<div class="row">						
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                    <li class="active"><span>View Delivery Order</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Delivery Order</h1>                                 
                    <div class="pull-right top-page-ui">
                        @if(($delivery_data->serial_no == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1))
                        <a href="{{URL::action('DeliveryOrderController@edit',['id'=>$delivery_data->id])}}" class="btn btn-primary pull-right">
                            Edit Delivery Order
                        </a>
                        @endif
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
                                        <td><span>Tally Name:</span> 
                                            @if($delivery_data['customer']->owner_name != "" && $delivery_data['customer']->tally_name != "")
                                            {{ $delivery_data['customer']->owner_name }}-{{$delivery_data['customer']->tally_name}}
                                            @else
                                            {{ $delivery_data['customer']->owner_name }}
                                            @endif

                                        </td>
                                    </tr>
                                    <tr><td><span>Contact Person: </span>{{ $delivery_data['customer']->contact_person }}</td></tr>
                                    <tr>
                                        <td><span>Date:</span> {{ date('jS F, Y', strtotime ($delivery_data['created_at'])) }}</td>
                                    </tr>
                                    <tr><td><span>Serial Number: </span> 
                                            @if($delivery_data->serial_no != "")
                                            {{$delivery_data->serial_no}} 
                                            @else 
                                            {{'--'}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Mobile Number: </span>{{$delivery_data['customer']->phone_number1}}</td>
                                    </tr>

                                    <tr>
                                        <td><span>Delivery Location: </span>

                                            @if($delivery_data->delivery_location_id == 0)
                                            {{$delivery_data->other_location}}
                                            @else
                                            @foreach($delivery_locations as $location)
                                            @if($location->id == $delivery_data->delivery_location_id)
                                            {{$location->area_name}}
                                            @endif
                                            @endforeach
                                            @endif                                            

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Delivery Location Difference: </span>
                                            {{$delivery_data->location_difference}}
                                        </td>
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
                                            <span>Present shipping</span>
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
                                    @foreach($delivery_data['delivery_product'] as $product)
                                    @if($product->order_type =='delivery_order')
                                    <tr>
                                        <td> {{ $product['order_product_details']->alias_name}}</td>
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
                                    $grand = $grand - $grand * $delivery_data->vat_percentage / 100;
                                    ?>
                                    @endif
                                    @endforeach

                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                    @if($delivery_data->vat_percentage != "" || $delivery_data->vat_percentage > 0)  
                                    <tr>
                                        <td><span>Plus VAT: </span>    
                                            Yes                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>VAT Percentage: </span>{{ $delivery_data->vat_percentage }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td><span>Plus VAT: </span>    
                                            No                                            
                                        </td>
                                    </tr>
                                    @endif                                    


                                    <tr><td><b>Vehicle Name:</b> {{ $delivery_data->vehicle_number }} </td> </tr>

                                    <tr><td><b>Driver Contact:</b> {{ $delivery_data->driver_contact_no }} </td> </tr>

                                    <tr>
                                        <td><span>Remark: </span>{{ $delivery_data->remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="{{url('delivery_order')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop