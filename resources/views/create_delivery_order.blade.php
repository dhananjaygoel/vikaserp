<?php
//echo'<pre>';
//print_r($pending_orders);
//echo '</pre>';
//exit;
?>
@extends('layouts.master')
@section('title','Create Delivery Order')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Create Delivery Order</span></li>
                </ol>

            </div>
        </div>

        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">


                    <div class="main-box-body clearfix">

                        {!! Form::open(array('method'=>'post','url'=>url('create_delivery_order',$order->id), 'id'=>'create_delivery_order_form'))!!}

                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <input type="hidden" name="customer_id" value="{{$order['customer']->id}}" id="hidden_cutomer_id">

                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-warning">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif

                        <table id="table-example" class="table table-hover  ">
                            <tbody>
                                <tr><td><span><b>Date: </b></span> <?php echo date('d F, Y'); ?></td></tr>
                                @if($order->order_source == 'warehouse')
                                <tr><td><span><b>Warehouse: </b></span> yes</td></tr>
                                <!--<tr><td><span><b>Supplier Name:</b></span> Warehouse</td></tr>-->
                                @elseif($order->order_source == 'supplier')
                                <!--<tr><td><span><b>Warehouse: </b></span> no</td></tr>-->
                                @foreach($customers as $customer)
                                @if($customer->id == $order->supplier_id)
                                <tr><td><span><b>Supplier Name:</b></span>  {{$customer->owner_name}} </td></tr>
                                @endif
                                @endforeach
                                @endif
                                @foreach($customers as $customer)
                                @if($customer->id == $order->customer_id)
                                <tr><td><span><b>Customer Name :</b></span> {{$customer->owner_name}} </td></tr>
                                <tr><td><span><b>Contact Person : </b></span> {{$customer->contact_person}}</td></tr>
                                <tr><td><span><b>Mobile Number : </b></span>{{$customer->phone_number1}}</td></tr>

                                @if($customer->credit_period > 0 || $customer->credit_period != 0)
                                <tr> <td><span><b>Credit Period : </b></span>{{$customer->credit_period}}</td></tr>   
                                @endif

                                @endif
                                @endforeach 
                                <tr><td><span><b>Delivery Location : </b></span>
                                        @foreach($delivery_location as $location)
                                        @if($order->delivery_location_id == $location->id)
                                        {{$location->area_name}}

                                        @endif
                                        @endforeach

                                    </td></tr>
                            </tbody>
                        </table>
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <div id="flash_error_present_shipping"></div>
                                <table id="add_product_table_delivery_order" class="table table-hover  ">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span></td>
                                            <td><span>Quantity</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Present Shipping</span></td>

                                            <td><span>Price</span></td>
                                            <td><span>Pending Order</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        <?php $total = 0; ?>
                                        @foreach($order['all_order_products'] as $key=>$product)
                                        @if($product->order_type =='order')
                                        <tr id="add_row_{{$key}}" class="add_product_row">

                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    {{$product['product_category']['product_sub_category']->alias_name }}
                                                    <input class="form-control" placeholder="Enter Product name " type="hidden" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" value="{{$product['product_category']->product_category_name}}" readonly="readonly" >
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}"  value="{{$product->product_category_id}}" readonly="readonly">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    {{$product->quantity}}
                                                    <input id="quantity_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][quantity]" value="{{$product->quantity}}" type="hidden" >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group "> 
                                                    @foreach($units as $unit)
                                                    @if($product->unit_id == $unit->id)
                                                    {{$unit->unit_name}}
                                                    <input type="hidden" value="{{$unit->id}}" name="product[{{$key}}][units]">
                                                    @endif
                                                    @endforeach

                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="present_shipping_{{$key}}" class="form-control" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="" type="text" onblur="change_quantity({{$key}});">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    {{$product->price}}
                                                    <input type="hidden" class="form-control" value="{{$product->price}}" id="product_price_{{$key}}" name="product[{{$key}}][price]" readonly="readonly">
                                                    <?php $total = $total + $product->price; ?>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    @if(count($pending_orders)>0)
                                                    @foreach($pending_orders as $porder)
                                                    @if($porder['product_id'] == $product->product_category_id && $porder['id']== $product->id)
                                                    <input type="hidden" value="{{$porder['total_pending_quantity']}}" id="pending_qunatity_value_{{$key}}">
                                                    <div id="pending_qunatity_{{$key}}"><span class="text-center">{{$porder['total_pending_quantity']}}</span>
                                                    </div>
                                                    @endif
                                                    @endforeach
                                                    @else
                                                    <input type="hidden" value="{{$product->quantity}}" id="pending_qunatity_value_{{$key}}">
                                                    <div id="pending_qunatity_{{$key}}"><span class="text-center">{{$product->quantity}}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="col-md-4">
                                                <div class="form-group">
                                                    {{$product->remarks}}
                                                    <input id="remark"  class="form-control" placeholder="Remark" name="product[{{$key}}][remark]" value="{{$product->remarks}}" type="hidden" readonly="readonly">
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach

                                    </tbody>
                                </table>
                                <table>
                                    <tbody>
                                        <tr class="row5">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                        <label for="addmore"></label>
                                                        <a class="table-link" title="add more" id="add_product_row_delivery_order">
                                                            <span class="fa-stack more_button" >
                                                                <i class="fa fa-square fa-stack-2x"></i>
                                                                <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                            </span>
                                                        </a>

                                                    </div>
                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <table id="table-example" class="table table-hover  ">
                            <tbody>
                                @if($order->vat_percentage == 0)
                                <tr class="cdtable">
                                    <td class="cdfirst">Plus VAT:</td>
                                    <td>No</td>
                                </tr>
                                @elseif($order->vat_percentage != 0)
                                <tr class="cdtable">
                                    <td class="cdfirst">Plus VAT:</td>
                                    <td>Yes</td>
                                </tr>
                                <tr class="cdtable">

                                    <td class="cdfirst">VAT Percentage:</td>

                                    <td>
                                        {{$order->vat_percentage}}
                                        <input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$order->vat_percentage}}" type="hidden" readonly="readonly" onblur="grand_total_delivery_order();">
                                    </td>
                                </tr>
                                @endif
                                <tr class="cdtable">
                                    <td class="cdfirst">Grand Total:</td>

                                    <td>
                                        <input type="text" name="grand_total" id ="grand_total" class="form-control" value="" readonly="readonly">
                                    </td>
                                </tr>
                                <tr class="cdtable">
                                    <td class="cdfirst">Vehicle Number:</td>
                                    <td><input  class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{old('vehicle_number')}}" type="text" onblur="grand_total_delivery_order();"></td>
                                </tr>
                                <tr class="cdtable">
                                    <td class="cdfirst">Driver Name:</td>
                                    <td><input  class="form-control" placeholder="Driver Name" name="driver_name" value="{{old('driver_name')}}" type="text"></td>
                                </tr>
                                <tr class="cdtable">
                                    <td class="cdfirst">Driver Contact:</td>
                                    <td><input  class="form-control" placeholder="Driver Contact" name="driver_contact" value="{{old('driver_contact')}}" type="text"></td>
                                </tr>
                                <tr class="cdtable">
                                    <td class="cdfirst">Remark:</td>
                                    <td><input class="form-control cdbox" placeholder="Remark" name="remarks" value="{{$order->remarks}}" type="text"></td>
                                </tr>


                            </tbody>
                        </table>

                        <div class="clearfix"></div>

                        <div class="form-group col-md-4 " style="display: none">

                            <label for="time">Estimated Delivery Date:</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="estimated_date" class="form-control" value="{{date('Y-m-d', strtotime($order->estimated_delivery_date))}}" readonly="readonly">
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group col-md-4 targetdate" style="display: none">
                            <label for="date">Expected Delivery Date: </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="expected_date" class="form-control" value="{{date('Y-m-d', strtotime($order->expected_delivery_date))}}" readonly="readonly">
                            </div>
                        </div>



                        <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> 
                        <hr>
                        <div >
                            <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>

                            <a href="{{url('orders')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>

                        <div class="clearfix"></div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
