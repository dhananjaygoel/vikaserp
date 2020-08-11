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
                     
                        @if($delivery_data->order_status == 'pending')
                        @if($delivery_data->serial_no == "")
                        @if(Auth::user()->role_id == 0)
                        <a href="{{URL::action('DeliveryOrderController@edit',['delivery_order'=>$delivery_data->id])}}" class="btn btn-primary pull-right">
                            Edit Delivery Order
                        </a>
                        @endif
                        @endif
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
                                    @if(isset($print_user ) && $print_user != '')
                                    <?php 
                                        $time = date('h:i A', strtotime(isset($delivery_data->print_time)?$delivery_data->print_time:'00:00:00'));
                                        $date = date('j F, Y', strtotime(isset($delivery_data->print_time)?$delivery_data->print_time:'01/01/0000'));
                                    ?>
                                    <tr>
                                        <td><span><b>Printed By: </b></span> 
                                        {{ucwords($print_user->first_name).' '.ucwords($print_user->last_name).' on '.$date.' '.$time}}
                                        </td>
                                    </tr>
                                    @endif
                                    @if($delivery_data->order_source == 'warehouse')
                                        <tr><td><span><b>Order From: </b></span> Warehouse</td></tr>
                                    @elseif($delivery_data->order_source == 'supplier')
                                    <tr>
                                        <td><span>Order From:</span>                                                                                
                                            @foreach($customers as $customer)
                                            @if($customer->id == $delivery_data->supplier_id)                                                                                                    
                                                {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                            @endif
                                            @endforeach
                                        
                                        </td>
                                    </tr>
                                    @endif
                                    @if(Auth::user()->role_id == 0)
                                        <tr>
                                            <td><span>Order For:</span>
                                                @if($delivery_data['customer']->owner_name != "" && $delivery_data['customer']->tally_name != "")
                                                {{ $delivery_data['customer']->owner_name }}-{{$delivery_data['customer']->tally_name}}
                                                @else
                                                {{ $delivery_data['customer']->owner_name }}
                                                @endif
                                            </td>
                                        </tr>                                    
                                        <tr>
                                            <td><span>Contact Person: </span>{{ $delivery_data['customer']->contact_person }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><span>Date:</span> {{ date('j F, Y', strtotime ($delivery_data['created_at'])) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Serial Number: </span>
                                            {{($delivery_data->serial_no != "") ? $delivery_data->serial_no : '--'}}
                                        </td>
                                    </tr>
                                    @if(Auth::user()->role_id == 0)
                                        <tr>
                                            <td><span>Mobile Number: </span>{{$delivery_data['customer']->phone_number1}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <span>Delivery Location: </span>
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
                                        <td><span>Delivery Freight: </span>
                                            {{$delivery_data->location_difference}}
                                        </td>
                                    </tr>
                                    @if($delivery_data->discount > 0)
                                        <tr>
                                            <td>
                                                <span><b>Discount/Premium :</b> </span>
                                                {{$delivery_data->discount_type}}                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Fixed/Percentage :</b> </span>
                                                {{$delivery_data->discount_unit}}                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Amount :</b> </span>
                                                {{$delivery_data->discount}}                                            
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                <span><b>Discount/Premium :</b> </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Fixed/Percentage :</b> </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Amount :</b> </span>
                                            </td>
                                        </tr>                                    
                                    @endif
                                        <tr>
                                            <td>
                                                <span><b>Empty Truck Weight(KG):</b> </span>
                                                {{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:0}} KG
                                            </td>
                                        </tr> 
                                        <tr>
                                            <td>
                                                <span><b>Final Truck Weight(KG):</b> </span>
                                                {{isset($delivery_data->final_truck_weight)?$delivery_data->final_truck_weight:0}} KG
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
                                        <td><span>Product(Alias)</span></td>
                                        <td><span>Present shipping</span></td>
                                        <td><span>Actual Pieces</span></td>
                                        <td><span>Average Weight</span></td>
                                        @if(Auth::user()->role_id == 5)
                                        <td><span>Total Order</span></td>
                                        @endif
                                        <td><span>Length</span></td>
                                        <td><span>Unit</span></td>
                                        <td><span>Price</span></td>
                                        <td><span>GST</span></td>
                                        <td><span>Remark</span></td>                                    
                                    </tr>
                                    <?php $grand = 0; ?>
                                    @foreach($delivery_data['delivery_product'] as $product)
                                    @if($product->order_type =='delivery_order' )
                                    <tr>
                                        <td> {{ $product['order_product_details']->alias_name}}</td>
                                        <td>{{$product->present_shipping}}</td>
                                        <!-- @if(Auth::user()->role_id == 5)
                                        <td>
                                            @foreach($order_data['all_order_products'] as $all_order_products)

                                            @if($all_order_products->product_category_id == $product->product_category_id)
                                            {{$all_order_products->quantity}}                                            
                                            @endif
                                            @endforeach


                                        </td>
                                        @endif -->
                                        <td>{{isset($product->actual_pieces)?$product->actual_pieces:'0'}}</td>
                                        <td>{{isset($product->actual_quantity)?$product->actual_quantity:'0'}}</td>
                                        @if(Auth::user()->role_id == 5)
                                        <td>
                                            @foreach($order_data['all_order_products'] as $all_order_products)

                                            @if($all_order_products->product_category_id == $product->product_category_id)
                                            {{$all_order_products->quantity}} KG                                          
                                            @endif
                                            @endforeach


                                        </td>
                                        @endif
                                        <td>{{$product->length}}</td>
                                        <td>
                                        {{isset($product['unit']->unit_name)?$product['unit']->unit_name:''}}
                                        </td>
                                        <td>₹ {{$product->price}}</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="checkbox" disabled="" {{($product->vat_percentage>0)?'checked':''}} >
                                            </div>
                                        </td>
                                        <td>{{$product->remarks}}</td>
                                    </tr>
                                    <?php
                                    $grand = $grand + (float)$product->present_shipping * (float)$product->price;
                                    $grand = $grand - $grand * (float)$delivery_data->vat_percentage / 100;
                                    ?>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                    @if($delivery_data->vat_percentage != "" || $delivery_data->vat_percentage > 0)
                                    <tr><td><span>GST Percentage: </span>{{ $delivery_data->vat_percentage }}</td></tr>
                                    @endif
                                    <tr><td><b>Vehicle Number:</b> {{ $delivery_data->vehicle_number }} </td> </tr>
                                    <tr><td><b>Driver Contact:</b> {{ $delivery_data->driver_contact_no }} </td> </tr>
                                    <tr><td><span>Remark: </span>{{ $delivery_data->remarks }}</td></tr>
                                    @if($delivery_data->order_id > 0 && Auth::user()->role_id <> 5	)
                                    <tr>
                                        <td><span>Order By : </span>{{(isset($delivery_data->order_details->createdby->first_name)?$delivery_data->order_details->createdby->first_name:'')." ".(isset($delivery_data->order_details->createdby->last_name)?$delivery_data->order_details->createdby->last_name:'')}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Order Time/Date : </span>{{(isset($delivery_data->order_details->updated_at) ? date('j F, Y h:i A', strtotime($delivery_data->order_details->updated_at)):"") }}</td>
                                    </tr>
                                    @endif                                    
                                    <tr>
                                        <td><span>Delivery Order By : </span>{{(isset($delivery_data->user->first_name)?$delivery_data->user->first_name:'')." ".(isset($delivery_data->user->last_name)?$delivery_data->user->last_name:'')}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Delivery Order Time/Date : </span>{{isset($delivery_data->updated_at)? date('j F, Y h:i A', strtotime($delivery_data->updated_at)):''}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @if( Auth::user()->role_id  <> 5)
                            <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                            @endif
                             @if( Auth::user()->role_id  == 5)
                            <a href="{{url('order/'.$delivery_data->order_id.'-track')}}" class="btn btn-default form_button_footer">Back</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop