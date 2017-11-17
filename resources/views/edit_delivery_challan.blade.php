@extends('layouts.master')
@section('title','Edit Delivery Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_challan')}}">Delivery Challan</a></li>
                    <li class="active"><span>Edit Delivery Challan</span></li>
                </ol>
                <div class="clearfix"><h1 class="pull-left">Edit Delivery Challan</h1></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{url('delivery_challan/'.$allorder->id)}}" accept-charset="UTF-8" >
                            @if (Session::has('validation_message'))
                            <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('validation_message') }}</div>
                            @endif
                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            <input name="_method" type="hidden" value="PUT">
                            <input type="hidden" name="order" value="{{$allorder->order_id}}">
                            <input type="hidden" name="order_id" value="{{$allorder->delivery_order_id}}">
                            <input type="hidden" id="customer_id" name="customer_id" value="{{$allorder->customer_id}}">
                            <input type="hidden" name="existing_customer_id" value="{{$allorder->customer_id}}" id="existing_customer_id">
                            <input type="hidden" id="order_source" value="{{$allorder['delivery_order']->order_source}}">                            
                            <div class="form-group">
                                <label><b>Party Name:</b> {{$allorder->customer->owner_name}}</label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label><b>Serial Number:</b> {{$allorder->delivery_order->serial_no}}</label>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-md-2"><b>Empty Truck Weight(Kg):</b></label> 

                                <input type="text" name="empty_truck_weight" value="{{isset($allorder->delivery_order->empty_truck_weight)?$allorder->delivery_order->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);">
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-md-2"><b>Final Truck Weight(Kg):</b></label>                                 
                                <input type="text" name="final_truck_weight" value="{{isset($allorder->delivery_order->final_truck_weight)?$allorder->delivery_order->final_truck_weight:'0'}}" id="final_truck_weight" class="form-control" name="final_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" onblur="truck_weight(this)">
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-md-2"><b>Total Actual Quantity:</b></label>                                 
                                <input type="text" class="form-control" id="total_actual_qty_truck" name="total_actual_qty_truck" readonly="" style="width: 10.33%;" value="{{(isset($allorder->delivery_order->final_truck_weight)&&isset($allorder->delivery_order->empty_truck_weight))?($allorder->delivery_order->final_truck_weight - $allorder->delivery_order->empty_truck_weight):'0'}}"> 
                            </div>
                            <?php // dd($allorder);?>
                            @if($allorder['delivery_order']->discount > 0)
                                <div class="form-group">
                                    <label><b>Discount/Premium :</b> </label>
                                    {{$allorder['delivery_order']->discount_type}}
                                </div>
                                <div class="form-group">                                    
                                        <label><b>Fixed/Percentage :</b> </label>
                                        {{$allorder['delivery_order']->discount_unit}}
                                </div>
                                <div class="form-group">                                    
                                        <label><b>Amount :</b> </label>
                                        {{$allorder['delivery_order']->discount}}
                                </div>
                            @else
                                <div class="form-group">                                
                                    <label><b>Discount/Premium :</b> </label>                                
                                </div>
                                <div class="form-group">                                     
                                         <label><b>Fixed/Percentage :</b> </label>                             
                                </div>
                                <div class="form-group">                                    
                                        <label><b>Amount :</b> </label>                                
                                </div>
                            @endif
                            <hr>
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_challan" class="table table-hover">
                                    <tbody>
                                        <tr class="headingunderline">

                                            <td><span>Select Product</span></td>
                                            <td><span>Actual Pieces</span></td>
                                            <td><span>Actual Quantity</span></td>

                                            <td><span>Present Shipping</span></td>
                                            <td><span>Rate</span></td>
                                            <td class="inquiry_vat_chkbox"><span>GST</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Amount</span></td>
                                            <td><span>Remove Product</span></td>
                                        </tr>
                                        <?php $key = 1; ?>
                                        @foreach($allorder['all_order_products'] as $product)
                                        @if($product->order_type == 'delivery_challan')
                                        <tr id="add_row_{{$key}}" class="add_product_row">
                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    <input type="text" class="form-control each_product_detail ui-autocomplete-input" data-productid="{{$key}}" placeholder="Enter Product name" autocomplete="off" name="product[{{$key}}][name]" id="delivery_challan_product_name_{{$key}}" value="{{ $product['order_product_details']->alias_name}}" onfocus="delivery_challan_product_autocomplete({{$key}});">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}" value="{{$product['order_product_details']->id}}" data-curname="{{ $product['order_product_details']->alias_name}}">
                                                    <!--<i class="fa fa-search search-icon"></i>-->
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="actual_pieces_{{$key}}" class="form-control" placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="{{$product->actual_pieces}}" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" onblur="fetch_price();">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="quantity_{{$key}}" type="hidden" value="{{ $product->quantity}}" name="product[{{$key}}][quantity]">
                                                    @if($product->present_shipping >=0)
                                                    <input id="actual_quantity_{{$key}}" class="form-control delivery_challan_qty" placeholder="Actual Quantity" name="product[{{$key}}][actual_quantity]" value="{{ $product->quantity}}" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" onblur="fetch_price();">
                                                    @elseif($product->present_shipping <0)
                                                    <input id="actual_quantity_{{$key}}" class="form-control delivery_challan_qty" placeholder="Actual Quantity" name="product[{{$key}}][actual_quantity]" value="{{ $product->quantity}}" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" onblur="fetch_price();">
                                                    @endif
                                                </div>
                                            </td>

                                            <td class="col-md-2">
                                                <div class="form-group">{{ $product->present_shipping}}
                                                    <input id="present_shipping_{{$key}}" class="form-control text-center" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="{{ $product->present_shipping}}" type="hidden" >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" id="product_price_{{$key}}" value="{{$product->price}}" name="product[{{$key}}][price]" placeholder="Price" onkeypress=" return numbersOnly(this, event, true, true);" onblur="fetch_price({{$key}})">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group inquiry_vat_chkbox">
                                                    <!--<input type="text" class="form-control" id="product_vatpercentage_{{$key}}" value="{{$product->vat_percentage}}" name="product[{{$key}}][vat_percentage]" placeholder="Vat Percenatge" onblur="fetch_price({{$key}})">-->
                                                    <input class="vat_chkbox" disabled="" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes">

                                                    <input class="vat_chkbox" type="hidden" value="{{($product->vat_percentage>0)?'1':'0'}}" name="product[{{$key}}][vat_percentage_value]" value="yes" id = "product_vat_percentage_value_{{$key}}">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group ">
                                                    @foreach($units as $unit)
                                                    @if($unit->id == $product->unit_id)
                                                    <input class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}" value="{{$unit->id}}" type="hidden">
                                                    {{$unit->unit_name}}
                                                    <input type="hidden" id="unit_name_{{$key}}" value="{{$unit->unit_name}}">
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group"><div id="amount_{{$key}}"></div></div>
                                            </td>
                                            <td class="col-md-1">
                                                <!--                                                <button id="delete_{{$key}}" type="button" onclick="clear_data(this); fetch_price();" style="background-color: transparent; border: 1px" >  </button>-->
                                                <a href="javascript:void(0)" class="table-link danger" id="delete_{{$key}}"  title="delete" onclick="clear_data(this); fetch_price();">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>

                                            </td>
                                        </tr>
                                        <?php $key++ ?>
                                        @endif
                                        @endforeach
<!--                                        <tr id="add_row_{{$key}}" class="add_product_row">
                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    <input type="text" class="form-control each_product_detail ui-autocomplete-input" placeholder="Enter Product name" autocomplete="off" name="product[{{$key}}][name]" id="delivery_challan_product_name_{{$key}}" data-productid="{{$key}}" onfocus="delivery_challan_product_autocomplete({{$key}});">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}">
                                                    <i class="fa fa-search search-icon"></i>
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="quantity_{{$key}}" type="hidden" value="" name="product[{{$key}}][quantity]">
                                                    <input id="actual_quantity_{{$key}}" class="form-control delivery_challan_qty" placeholder="Qnty" name="product[{{$key}}][actual_quantity]" value="" type="tel" onkeypress=" return numbersOnly(this,event,true,true);" onblur="fetch_price(); set_value(this); " data-bind="{{$key}}">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="actual_pieces_{{$key}}" class="form-control" placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="" type="tel" onkeypress=" return numbersOnly(this,event,true,true);" onblur="fetch_price();">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="present_shipping_{{$key}}" class="form-control text-center" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="" type="input" onkeypress=" return numbersOnly(this,event,true,true);">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" id="product_price_{{$key}}" value="" name="product[{{$key}}][price]" placeholder="Price" onkeypress=" return numbersOnly(this,event,true,true);" onblur="fetch_price({{$key}})">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group inquiry_vat_chkbox">
                                                    <input type="tel" class="form-control" id="product_vatpercentage_{{$key}}" value="" name="product[{{$key}}][vat_percentage]" placeholder="Vat Percentage" onblur="fetch_price({{$key}})">
                                                    <input class="vat_chkbox" type="checkbox" id = "product[{{$key}}][vat_percentage]" name="product[{{$key}}][vat_percentage]" {{($product->vat_percentage>0)?'checked':''}} disabled="" value="yes" >
                                                    
                                                    <input class="vat_chkbox" type="hidden" value="{{($product->vat_percentage>0)?'1':'0'}}" id ="product_vat_percentage_value_{{$key}}" name="product[{{$key}}][vat_percentage_value]" value="yes">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group ">
                                                    @foreach($units as $unit)
                                                    @if($unit->id == $product->unit_id)
                                                    <input class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}" value="{{$unit->id}}" type="hidden">
                                                    {{$unit->unit_name}}
                                                    <input type="hidden" id="unit_name_{{$key}}" value="{{$unit->unit_name}}">
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <div id="amount_{{$key}}"></div>
                                                </div>
                                            </td>
                                            
                                            
                                        </tr>-->
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
                            <div class="form-group">
                                <label for="total">
                                    <b class="challan">Total</b>
                                    <span class="gtotal">
                                        <input type="text" class="form-control" id="total_price" name="total_price" placeholder="" readonly="readonly">
                                    </span>
                                </label>
                                &nbsp;&nbsp;
                                <label for="total">
                                    <b class="challan">Total Actual Quantity</b>
                                    <span class="gtotal">
                                        <input type="text" class="form-control" id="total_actual_quantity" name="total_actual_quantity" placeholder="" readonly="readonly">
                                    </span>
                                </label>
                            </div>
                            <div class="form-group" style="display: none">
                                <label for="billno"><b class="challan">Bill Number</b></label>
                                <input id="billno" class="form-control" placeholder="Bill Number" name="billno"  value="{{$allorder->bill_number}}" type="text">
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 no_left_margin">
                                    <label for="loading"><b class="challan">Loading</b></label>
                                    <input id="loading_charge" class="form-control" placeholder="Loading Charges" name="loading" onkeypress=" return numbersOnly(this, event, true, true);" value="{{$allorder->loading_charge}}" type="tel" onblur="grand_total_challan();" >
                                </div>

                                <!--                                <div class="col-md-4">
                                                                    <label for="driver_contact"><b class="challan">Loading Vat Percentage</b></label>
                                                                    <input id="loading_vat_percentage" class="form-control" placeholder="Loading Vat Percentage" name="loading_vat_percentage" value="{{$allorder->loading_vat_percentage}}" type="tel" onblur="grand_total_challan();">
                                                                </div>
                                                                <div class="col-md-4 no_right_margin">
                                                                    <label for="driver_contact"><b class="challan">Total Loading Vat Charges</b></label>
                                                                    <input id="loading_total_charge" readonly="" class="form-control" value="">
                                                                </div>-->
                            </div>
                            <!--
                            <div class="form-group">
                                <label for="Discount"><b class="challan">Discount</b></label>
                                <input id="discount_value" class="form-control" placeholder="Discount" name="discount"  value="{{$allorder->discount}}" type="text" onblur="grand_total_challan();">
                            </div>
                            -->


                            <div class="form-group">
                                <div class="col-md-12 no_left_margin">
                                    <label for="Discount"><b class="challan">Discount</b></label>
                                    <input id="discount_value" class="form-control" placeholder="Discount " name="discount" value="{{$allorder->discount}}" type="tel" onblur="grand_total_challan(); " onkeypress=" return numbersOnly(this, event, true, true);" onkeypress=" return numbersOnly(this, event, true, true);" >
                                </div>
                                <!--                                <div class="col-md-4">
                                                                    <label for="Loading_discount_percentage"><b class="challan">Discount Vat Percentage</b></label>
                                                                    <input id="discount_vat_percentage" class="form-control" placeholder="Discount Vat Percentage" name="discount_vat_percentage" value="{{$allorder->discount_vat_percentage}}" type="tel" onblur="grand_total_challan();">
                                                                </div>
                                                                <div class="col-md-4 no_right_margin">
                                                                    <label for="Total_frieght_charges"><b class="challan">Total Discount Charges</b></label>
                                                                    <input id="discount_total_charge" readonly="" class="form-control" value="">
                                                                </div>-->
                            </div>





                            <div class="form-group">
                                <div class="col-md-12 no_left_margin">
                                    <label for="Freight"><b class="challan">Freight</b></label>
                                    <input id="freight_value" class="form-control" placeholder="Freight " name="freight" value="{{$allorder->freight}}" type="text" onkeypress=" return numbersOnly(this, event, true, true);" onblur="grand_total_challan();" readonly="readonly">
                                </div>
                                <!--                                <div class="col-md-4">
                                                                    <label for="Loading_frieght_percentage"><b class="challan">Freight Vat Percentage</b></label>
                                                                    <input id="freight_vat_percentage" class="form-control" placeholder="Freight Vat Percentage" name="freight_vat_percentage" value="{{($allorder->freight_vat_percentage != '')?$allorder->freight_vat_percentage:0}}" type="text" onblur="grand_total_challan();">
                                                                </div>
                                                                <div class="col-md-4 no_right_margin">
                                                                    <label for="Total_frieght_charges"><b class="challan">Total Freight Charges</b></label>
                                                                    <input id="freight_total_charge" readonly="" class="form-control" value="">
                                                                </div>-->
                            </div>
                            <div class="form-group" >
                                <label for="Total"><b class="challan">Total </b></label>
                                <div id="total_l_d_f"></div>
                            </div>
                            @if($product_type['pipe'] == 1)
                            <div class="form-group">
                                <label for="loadedby"><b class="challan">Loaded By (Pipe):</b></label>                                
                                <?php
                                if (isset($allorder['challan_loaded_by'])) {
                                    foreach ($allorder['challan_loaded_by'] as $challan_loaded_by) {
                                        foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                                            if (isset($challan_loaded_by->product_type_id) && $challan_loaded_by->product_type_id == 1) {
                                                echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                                            }
                                        }
                                    }
                                }
                                ?>

                                <div class="form-group clearfix" style="display: none">
                                    <select id="loaded_by_select_pipe" name='loaded_by_pipe[]' class="form-control" multiple="multiple">
                                        @if(isset($allorder['challan_loaded_by']))
                                        @foreach ($allorder['challan_loaded_by'] as $challan_loaded_by)
                                        @foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby)
                                        @if(isset($challan_loaded_by->product_type_id) && $challan_loaded_by->product_type_id == 1)

                                        <option value="{{$loadedby->id}}" selected="">{{$loadedby->first_name}} {{$loadedby->last_name}}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            <!--<input id="loadedby" class="form-control" placeholder="Loaded By" name="loadedby"  value="{{($allorder->loaded_by != '')?$allorder->loaded_by:''}}" type="text" readonly="">-->
                            </div>
                            @endif
                            @if($product_type['structure'] == 1)
                            <div class="form-group">
                                <label for="loadedby"><b class="challan">Loaded By (Structure):</b></label>

                                <?php
                                if (isset($allorder['challan_loaded_by'])) {
                                    foreach ($allorder['challan_loaded_by'] as $challan_loaded_by) {
                                        foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                                            if (isset($challan_loaded_by->product_type_id) && $challan_loaded_by->product_type_id == 2) {
                                                echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                                            }
                                        }
                                    }
                                }
                                ?>  

                                <div class="form-group clearfix" style="display: none">
                                    <select id="loaded_by_select_structure" name='loaded_by_structure[]' class="form-control" multiple="multiple">
                                        @if(isset($allorder['challan_loaded_by']))
                                        @foreach ($allorder['challan_loaded_by'] as $challan_loaded_by)
                                        @foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby)
                                        @if(isset($challan_loaded_by->product_type_id) && $challan_loaded_by->product_type_id == 2)
                                        <option value="{{$loadedby->id}}" selected="">{{$loadedby->first_name}} {{$loadedby->last_name}}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if($product_type['profile'] == 1)
                            <div class="form-group">
                                <label for="loadedby"><b class="challan">Loaded By (Profile):</b></label>

                                <?php
                                if (isset($allorder['challan_loaded_by'])) {
                                    foreach ($allorder['challan_loaded_by'] as $challan_loaded_by) {
                                        foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                                            if (isset($challan_loaded_by->product_type_id) && $challan_loaded_by->product_type_id == 3) {
                                                echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                                            }
                                        }
                                    }
                                }
                                ?>  

                                <div class="form-group clearfix" style="display: none">
                                    <select id="loaded_by_select_profile" name='loaded_by_profile[]' class="form-control" multiple="multiple">
                                        @if(isset($allorder['challan_loaded_by']))
                                        @foreach ($allorder['challan_loaded_by'] as $challan_loaded_by)
                                        @foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby)
                                        @if(isset($challan_loaded_by->product_type_id) && $challan_loaded_by->product_type_id == 3)
                                        <option value="{{$loadedby->id}}" selected="">{{$loadedby->first_name}} {{$loadedby->last_name}}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @endif
                            @if($product_type['pipe'] == 1)
                            <div class="form-group">
                                <label for="labours"><b class="challan">Labours (Pipe):</b></label>
                                <?php
                                if (isset($allorder['challan_labours']) && !empty($allorder['challan_labours'])) {
                                    foreach ($allorder['challan_labours'] as $challan_labour) {
                                        foreach ($challan_labour['dc_labour'] as $labour) {
                                            if (isset($challan_labour->product_type_id) && $challan_labour->product_type_id == 1) {
                                                echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                                            }
                                        }
                                    }
                                } else {
                                    echo "N/A";
                                }
                                ?>

                                <div class="form-group clearfix" style="display: none">
                                    <select id="labour_select_pipe" name="labour_pipe[]" class="form-control" multiple="multiple">
                                        @if(isset($allorder['challan_labours']) && !empty($allorder['challan_labours']))
                                        @foreach ($allorder['challan_labours'] as $challan_labour)
                                        @foreach ($challan_labour['dc_labour'] as $labour)
                                        @if(isset($challan_labour->product_type_id) && $challan_labour->product_type_id == 1)
                                        <option value="{{$labour->id}}" selected="">{{$labour->first_name}} {{$labour->last_name}}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>
                            @endif
                            @if($product_type['structure'] == 1)
                            <div class="form-group">
                                <label for="labours"><b class="challan">Labours (Structure):</b></label>                               
                                <?php
                                if (isset($allorder['challan_labours']) && !empty($allorder['challan_labours'])) {
                                    foreach ($allorder['challan_labours'] as $challan_labour) {
                                        foreach ($challan_labour['dc_labour'] as $labour) {
                                            if (isset($challan_labour->product_type_id) && $challan_labour->product_type_id == 2) {
                                                echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                                            }
                                        }
                                    }
                                } else {
                                    echo "N/A";
                                }
                                ?>

                                <div class="form-group clearfix" style="display: none">
                                    <select id="labour_select_structure" name="labour_structure[]" class="form-control" multiple="multiple">
                                        @if(isset($allorder['challan_labours']) && !empty($allorder['challan_labours']))
                                        @foreach ($allorder['challan_labours'] as $challan_labour)
                                        @foreach ($challan_labour['dc_labour'] as $labour)
                                        @if(isset($challan_labour->product_type_id) && $challan_labour->product_type_id == 2)
                                        <option value="{{$labour->id}}" selected="">{{$labour->first_name}} {{$labour->last_name}}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>
                            @endif
                            @if($product_type['profile'] == 1)
                            <div class="form-group">
                                <label for="labours"><b class="challan">Labours (Profile):</b></label>                               
                                <?php
                                if (isset($allorder['challan_labours']) && !empty($allorder['challan_labours'])) {
                                    foreach ($allorder['challan_labours'] as $challan_labour) {
                                        foreach ($challan_labour['dc_labour'] as $labour) {
                                            if (isset($challan_labour->product_type_id) && $challan_labour->product_type_id == 3) {
                                                echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                                            }
                                        }
                                    }
                                } else {
                                    echo "N/A";
                                }
                                ?>

                                <div class="form-group clearfix" style="display: none">
                                    <select id="labour_select_profile" name="labour_profile[]" class="form-control" multiple="multiple">
                                        @if(isset($allorder['challan_labours']) && !empty($allorder['challan_labours']))
                                        @foreach ($allorder['challan_labours'] as $challan_labour)
                                        @foreach ($challan_labour['dc_labour'] as $labour)
                                        @if(isset($challan_labour->product_type_id) && $challan_labour->product_type_id == 3)
                                        <option value="{{$labour->id}}" selected="">{{$labour->first_name}} {{$labour->last_name}}</option>
                                        @endif
                                        @endforeach
                                        @endforeach
                                        @endif
                                    </select>
                                </div>

                            </div>
                            @endif
                            <div class="form-group">
                                <label for="roundoff"><b class="challan">Round Off</b></label>
                                <input id="round_off" class="form-control" placeholder="Round Off" name="round_off" onkeypress=" return numbersOnly(this, event, true, true);" value="{{($allorder->round_off != '')?$allorder->round_off:''}}" type="tel" onblur="grand_total_challan();" readonly="readonly">
                            </div>
                            @if($allorder->vat_percentage==0 || $allorder->vat_percentage=='')
                            <!--                            <div class="form-group">
                                                            <label for="Plusvat"><b class="challan">Plus VAT : </b> No
                                                            </label>
                                                        </div>-->
                            @else
                            <!--                            <div class="form-group">                                
                                                            <label for="vatp"><b class="challan">VAT Percentage : </b>
                                                                {{($allorder->vat_percentage != '') ? $allorder->vat_percentage : ''}}
                                                                <input type="hidden" name="vat_percentage" id="vat_percentage" value="{{($allorder->vat_percentage>0) ? $allorder->vat_percentage :''}}" readonly="readonly">
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="vatp"><b class="challan">VAT Value : </b>
                                                                <span id="vat_val"></span>
                                                            </label>
                                                        </div>-->
                            @endif
                            <div class="form-group" style="display: none">
                                <label for="total"><b class="challan">Grand Total </b>
                                    <span class="gtotal">
                                        <input type="text" class="form-control" name="grand_total" id="grand_total" readonly="readonly">
                                    </span>
                                </label>
                            </div>
                            <div class="form-group" >
                                <label for="challan_vehicle_number"><b class="challan">Vehicle Number :</b></label>
                                <input id="challan_vehicle_number" class="form-control" placeholder="Vehicle Number" name="challan_vehicle_number"  value="{{$allorder->delivery_order->vehicle_number}}" type="text">
                            </div>

                            <div class="form-group" >
                                <label for="challan_driver_contact"><b class="challan">Driver Contact :</b></label>
                                <input id="challan_driver_contact" class="form-control" placeholder="Driver Contact" name="challan_driver_contact"  value="{{$allorder->delivery_order->driver_contact_no}}" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" type="text">
                            </div>
                            <div class="form-group">
                                <label for="challan_remark"><b class="challan">Remark</b></label>
                                <textarea class="form-control" id="challan_remark" name="challan_remark" rows="3">{{trim($allorder->remarks)}}</textarea>
                            </div>
                            <!--  <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary btn_edit_delivery_challan" >Submit</button>

                            </div>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('autocomplete_tally_product_name') --}}
@endsection
