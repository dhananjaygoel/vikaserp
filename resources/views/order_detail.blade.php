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
                    @if($order->order_status!='completed' && Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <div class="pull-right top-page-ui">
                        <a href="{{url('orders/'.$order->id.'/edit')}}" class="btn btn-primary pull-right">Edit Order</a>
                    </div>
                    @endif
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
                                        @if($order->order_source == 'warehouse')
                                        <tr><td><span><b>Order From: </b></span> Warehouse</td></tr>
                                        @elseif($order->order_source == 'supplier')                                        
                                        @foreach($customers as $customer)
                                        @if($customer->id == $order->supplier_id)
                                        <tr>
                                            <td>
                                                <span><b>Order From:</b></span>
                                                {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                        @endif
                                        @foreach($customers as $customer)
                                        @if($customer->id == $order->customer_id)
                                        <tr>
                                            <td colspan="2">
                                                <span><b>Order For:</b></span>
                                                {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                            </td>
                                        </tr>
                                        <tr><td colspan="2"><span><b>Contact Person: </b></span> {{$customer->contact_person}}</td></tr>
                                        <tr><td colspan="2"><span><b>Mobile Number: </b></span>{{$customer->phone_number1}}</td></tr>
                                        @if($customer->credit_period != "" || $customer->credit_period>0)
                                        <tr>
                                            <td><span><b>Credit Period(Days): </b></span>{{$customer->credit_period}}</td>
                                        </tr>                                        
                                        @endif
                                        @endif                                        
                                        @endforeach                                        
                                        <tr>
                                            @if($order->delivery_location_id !=0)
                                            @foreach($delivery_location as $location)
                                            @if($order->delivery_location_id == $location->id)
                                            <td><span>Delivery Location: </span>{{$location->area_name}}</td>
                                            <td><span>Delivery Freight: </span>{{$order->location_difference}}</td>
                                            @endif
                                            @endforeach
                                            @else
                                            <td><span>Delivery Location: </span>{{$order->other_location}}</td>
                                            <td><span>Delivery Freight: </span>{{$order->location_difference}}</td>
                                            @endif
                                        </tr>
                                        @if($order->discount > 0)
                                            <tr>
                                                <td colspan="2">
                                                    <span><b>Discount/Premium :</b> </span>
                                                    {{$order->discount_type}}                                            
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span><b>Fixed/Percentage :</b> </span>
                                                    {{$order->discount_unit}}                                            
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span><b>Amount :</b> </span>
                                                    {{$order->discount}}                                            
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="2">
                                                    <span><b>Discount/Premium :</b> </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span><b>Fixed/Percentage :</b> </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <span><b>Amount :</b> </span>
                                                </td>
                                            </tr>                                    
                                        @endif
                                        <tr><td colspan="2"><span class="underline">Ordered Product Details </span></td></tr>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Product(Alias)</span></td>
                                            <td><span>Qty</span></td>
                                            <td><span>Length</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Price</span></td>
                                            <td><span>GST</span></td>
                                            <td class="widthtable"><span>Remark</span></td>
                                        </tr>
                                        <?php $total = 0; ?>
                                        @foreach($order['all_order_products'] as $key=>$product)
                                        @if($product->order_type =='order')
                                        <tr id="add_row_{{$key}}" class="add_product_row">
                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    {{isset($product['order_product_details']->alias_name)?$product['order_product_details']->alias_name:' '}}
                                                </div>
                                            </td>
                                            <td class="col-md-1"><div class="form-group">{{$product->quantity}}</div></td>
                                            <td class="col-md-1"><div class="form-group ">{{$product->length}}</div></td>
                                            <td class="col-md-2"><div class="form-group ">{{$product['unit']->unit_name}}</div></td>
                                            <td class="col-md-2">
                                                <div class="form-group">{{$product->price}}
                                                    <?php $total = $total + $product->price * $product->quantity; ?>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input type="checkbox" disabled="" {{($product->vat_percentage>0)?'checked':''}} >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">{{$product->remarks}}</div>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table ">
                                    <tbody>

<!--                                        <tr>
                                            <td><span>Total: </span></td>
                                        </tr>-->
                                        <tr><td><span>Expected Delivery Date: </span>{{date("F jS, Y", strtotime($order->expected_delivery_date)) }}</td></tr>
                                        <tr><td><span>Remark: </span>{{$order->remarks}}</td></tr>
                                        <tr><td><span>Order By : </span>{{$order->createdby->first_name." ".$order->createdby->last_name}}</td></tr>
                                        <tr><td><span>Order Time/Date : </span>{{$order->updated_at}}</td></tr>
                                    </tbody>
                                </table>
                                <!--                                <a href="{{url('orders')}}" class="btn btn-default form_button_footer">Back</a>-->

                                @if( Auth::user()->role_id  <> 5)
                                
                                <?php 
                                        if(isset($is_approval['way']) && $is_approval['way'] == 'approval'){ ?>
                               
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>      
                                 <?php }else{  ?> 
                               
                                    <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>                           
                                        <?php } ?>  
                                @endif
                                @if( Auth::user()->role_id  == 5)
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                                @endif
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