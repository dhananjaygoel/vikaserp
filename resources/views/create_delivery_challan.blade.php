<?php
//echo'<pre>';
//print_r($delivery_data['delivery_product']->toArray());
//echo '</pre>';
//exit;
?>
@extends('layouts.master')
@section('title','Delivery Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                    <li class="active"><span>Delivery Challan</span></li>
                </ol>

            </div>
        </div>

        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">


                    <div class="main-box-body clearfix">



                        @if (Session::has('validation_message'))
                        <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('validation_message') }}</div>
                        @endif
                        <div id="flash_error_present_shipping"></div>
                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-warning">                         
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach                            
                        </div>
                        @endif

                        <div class="form-group">
                            Date : {{date('d F, Y')}}
                        </div> 
                        <hr>
                        {!!Form::open(array('method'=>'POST','url'=>url('create_delivery_challan/'.$delivery_data['id']),'id'=>'create_delivery_challan_form'))!!}
                        <input type="hidden" name="order_id" value="{{$delivery_data->order_id}}">
                        <input type="hidden" name="customer_id" value="{{$delivery_data['customer']->id}}">


                        <div class="form-group">

                            <span>Serial Number: </span> 
                            @if($delivery_data->serial_no != "")
                            {{$delivery_data->serial_no}} 
                            @else 
                            {{'--'}}
                            @endif
                        </div>
                        <hr>
                        <div class="form-group">
                            <td><span>Party:</span> {{ $delivery_data['customer']->owner_name }}</td>
                        </div>
                        <hr>


                        <div class="form-group underline">Product Details</div>

                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_challan" class="table table-hover">
                                    <tbody> 
                                        <tr class="headingunderline">
                                            <td><span>Select Product</span></td>
                                            <td><span>Actual Quantity</span></td>
                                            <td><span>Actual Pieces</span></td>                                            
                                            <td><span>Presenting Shipping</span></td>
                                            <td><span>Rate</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Amount</span></td>
                                        </tr>

                                        @foreach($delivery_data['delivery_product'] as $key=>$product)
                                        @if($product->order_type =='delivery_order')
                                        <tr id="add_row_{{$key}}" class="add_product_row">
                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    <input value="{{ $product['product_category']->product_category_name}}" class="form-control" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" onfocus="product_autocomplete({{$key}});">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}" value="{{$product['product_category']->id}}">
                                                    <i class="fa fa-search search-icon"></i>
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="quantity_{{$key}}" type="hidden" value="{{ $product->quantity}}" name="product[{{$key}}][quantity]">
                                                    @if($product->present_shipping >=0)
                                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][actual_quantity]" value="{{$product->present_shipping}}" type="text">
                                                    @elseif($product->quantity <0)
                                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][actual_quantity]" value="" type="text">
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="actual_pieces_{{$key}}" class="form-control" placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="{{$product->actual_pieces}}" type="text">
                                                </div>
                                            </td>

                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    {{ $product->present_shipping}}
                                                    <input id="present_shipping_{{$key}}" class="form-control text-center" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="{{ $product->present_shipping}}" type="hidden" >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">     
                                                    @foreach($price_delivery_order as $rate)
                                                    @if($rate['product_id'] == $product['product_category']->id)
                                                    <input type="text" class="form-control" id="product_price_{{$key}}" value="{{$rate['total_rate']}}" name="product[{{$key}}][price]" placeholder="Price" onblur="change_amount({{$key}})">
                                                    @endif
                                                    @endforeach

                                                </div>

                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group ">


                                                    @foreach($units as $unit)
                                                    @if($unit->id == $product->unit_id)
                                                    <input class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}" value="{{$unit->id}}" type="hidden">
                                                    {{$unit->unit_name}}
                                                    @endif
                                                    @endforeach

                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <div id="amount_{{$key}}"><span class="text-center">{{$product->price* $product->present_shipping}}</span></div>
                                                </div>
                                            </td>

                                        </tr>
                                        @endif
                                        @endforeach
                                        <?php //} ?>
                                    </tbody>
                                </table>
                                <table>
                                    <tbody>
                                        <tr class="row5">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                        <label for="addmore"></label>
                                                        <a class="table-link" title="add more" id="add_product_row_delivery_challan">
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
                        <div class="form-group">
                            <label for="vehicle_name"><b class="challan">Discount</b></label>
                            <input id="discount_value" class="form-control" placeholder="Discount" name="discount" value="" type="text" onblur="grand_total_delivery_order();">
                        </div>
                        <div class="form-group">
                            <label for="driver_name"><b class="challan">Freight</b></label>
                            <input id="freight_value" class="form-control" placeholder="Freight " name="freight" value="" type="text" onblur="grand_total_delivery_order();">
                        </div>
                        <div class="form-group">
                            <label for="total"><b class="challan">Total</b><span class="gtotal"><input type="text" id="total_price" name="total_price" placeholder="" readonly="readonly"></span></label>
                            

                        </div>
                        <div class="form-group">
                            <label for="driver_contact"><b class="challan">Loading</b></label>
                            <input id="loading_charge" class="form-control" placeholder="loading" name="loading" value="" type="text" onblur="grand_total_delivery_order();">
                        </div>

                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Loaded By</b></label>
                            <input id="loadedby" class="form-control" placeholder="Loaded By" name="loadedby" value="" type="text">
                        </div>
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour </b></label>
                            <input id="labour" class="form-control" placeholder="Labour" name="labour" value="" type="text">
                        </div>



                        <div class="form-group">

                            <label for="Plusvat"><b class="challan">Plus VAT : </b> @if($delivery_data->vat_percentage==0)NO 
                                @else
                                Yes
                                @endif
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="vatp"><b class="challan">VAT Percentage : </b>
                                {{$delivery_data->vat_percentage}}
                                <input type="hidden" name="vat_percentage" id="vat_percentage" value="{{$delivery_data->vat_percentage}}" readonly="readonly"></label>
                        </div>    
                        <div class="form-group">
                            <label for="total"><b class="challan">Grand Total : </b><span class="gtotal">
                                    <input type="text" class="form-group" name="grand_total" id="grand_total" readonly="readonly"></span></label>

                        </div>
                        <div class="form-group">
                            <label for="billno"><b class="challan">Bill Number</b></label>
                            <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="" type="text">
                        </div>
                        <div class="form-group">
                            <label for="challan_remark"><b class="challan">Remark</b></label>
                            <textarea class="form-control" id="challan_remark" name="challan_remark"  rows="3"></textarea>
                        </div>

                        <!-- <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->

                        <hr>  
                        <div >
                            <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                            <a href="{{url('delivery_order')}}" class="btn btn-default form_button_footer">Back</a>
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
