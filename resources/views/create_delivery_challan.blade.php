@extends('layouts.master')
@section('title','Delivery Challan')
@section('content')
<style>
    .multiselect-container.dropdown-menu {
        max-height: 350px;
        overflow-y: scroll;    
    }
    .multiselect.dropdown-toggle.btn.btn-default{
        background: white none repeat scroll 0 0;
        border: 1px solid gray;
        color: #344644;
    }
    .caret{
        border-top-color: #344644 !important;
    }
</style>
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
        <div class="row">
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
                        <div class="form-group">Date : {{date('d F, Y')}}</div>
                        <hr>
                        {!!Form::open(array('data-button'=>'btn_delorderto_delchallan','method'=>'POST','url'=>url('create_delivery_challan/'.$delivery_data['id']),'id'=>'onenter_prevent'))!!}
                        <input type="hidden" name="order_id" value="{{$delivery_data->order_id}}">
                        <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                        <input type="hidden" id="customer_id" name="customer_id" value="{{isset($delivery_data['customer']->id)?$delivery_data['customer']->id:''}}">
                        <div class="form-group">
                            <span>Serial Number: </span>{{($delivery_data->serial_no != "") ? $delivery_data->serial_no : '--'}}
                        </div>
                        <hr>
                        <input type="hidden" name="supplier_id" value="{{ $delivery_data->supplier_id }}"/>
                        <input type="hidden" id="order_source"  value="{{ $delivery_data->order_source }}"/>
                        <div class="form-group">
                            <td><span>Party:</span>
                                @if(isset($delivery_data['customer']->owner_name) && $delivery_data['customer']->owner_name != "" && $delivery_data['customer']->tally_name != "")
                                {{ $delivery_data['customer']->owner_name}}-{{$delivery_data['customer']->tally_name}}
                                @else
                                {{ isset($delivery_data['customer']->owner_name)?$delivery_data['customer']->owner_name:''}}
                                @endif
                                <input type="hidden" name="existing_customer_id" value="{{isset($delivery_data['customer']->id)?$delivery_data['customer']->id:''}}" id="existing_customer_id">
                            </td>
                            <input type="hidden" name="location_difference" value="{{$delivery_data->location_difference}}" id="location_difference">
                        </div>

                        @if($delivery_data->discount > 0)
                            <div class="form-group">
                                <span>Discount/Premium : </span>
                                {{$delivery_data->discount_type}}                                 
                            </div>
                            <div class="form-group">                                    
                                <span>Fixed/Percentage : </span>
                                {{$delivery_data->discount_unit}}                                
                            </div>
                            <div class="form-group">                                    
                                <span>Amount : </span>
                                {{$delivery_data->discount}}                                
                            </div>
                        @else
                            <div class="form-group">                                
                                <span>Discount/Premium : </span>                                    
                            </div>
                            <div class="form-group">                                     
                                <span>Fixed/Percentage :</span>                                    
                            </div>
                            <div class="form-group">                                    
                                 <span>Amount : </span>                                    
                            </div>
                        @endif
                        <hr>
                        <div class="form-group row">
                            <span class="col-md-2">Empty Truck Weight(Kg):</span> 
                            @if(isset($delivery_data->empty_truck_weight))
                            @if($delivery_data->empty_truck_weight > 0)
                            {{ $delivery_data->empty_truck_weight}}
                            <input type="hidden" name="empty_truck_weight" value="{{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control" name="empty_truck_weight">
                            @else
                            <input type="text" name="empty_truck_weight" value="{{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                            @endif
                            @else
                            <input type="text" name="empty_truck_weight" value="0" id="empty_truck_weight" class="form-control" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                            @endif  
                        </div>
                        <hr>
                        <div class="form-group row">
                            <span class="col-md-2">Final Truck Weight(Kg):</span>
                            @if(isset($delivery_data->final_truck_weight))
                            @if($delivery_data->final_truck_weight > 0)                            
                            <input type="text" name="final_truck_weight" value="{{ $delivery_data->final_truck_weight}}" id="final_truck_weight" class="form-control" name="final_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" onblur="truck_weight(this)">
                            <input type="hidden" name="empty_truck_weight" value="{{isset($delivery_data->final_truck_weight)?$delivery_data->final_truck_weight:'0'}}" id="final_truck_weight" class="form-control" name="final_truck_weight">
                            @else
                            <input type="text" name="final_truck_weight" value="{{isset($delivery_data->final_truck_weight)?$delivery_data->final_truck_weight:'0'}}" id="final_truck_weight" class="form-control" name="final_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                            @endif
                            @else
                            <input type="text" name="final_truck_weight" value="" id="final_truck_weight" class="form-control" name="final_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" onblur="truck_weight(this)">
                            @endif 
                        </div>
                        <hr>
                        <div class="form-group underline">Product Details</div>
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_challan" class="table table-hover">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Actual Pieces</span></td>
                                            <td><span>Average Weight</span></td>
                                            <td><span>Average Quantity</span></td>
                                            <td><span>Actual Quantity</span></td>    
                                            <td><span>Present Shipping</span></td>
                                            <td><span>Rate</span></td>
                                            <td><span>GST</span></td>
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Amount</span></td>
                                        </tr>
                                        <?php $key = 1; $actualsum =0; ?>
                                        @foreach($delivery_data['delivery_product'] as $product)
                                        @if($product->order_type =='delivery_order')
                                        <?php
                                         $actual_quantity = $product->actual_pieces * $product->actual_quantity;              
                                         $actualsum =  $actualsum + $actual_quantity;
                                        ?>
                                        <tr id="add_row_{{$key}}" class="add_product_row" {{($product->present_shipping==0)?'style = display:none':''}}>
                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    {{ $product['order_product_details']->alias_name}}
                                                    <input type="hidden" value="{{$product['order_product_details']->weight}}" id="product_weight_{{$key}}">
                                                    <input type="hidden" name="product[{{$key}}][name]" id="name_{{$key}}" value="{{$product['order_product_details']->alias_name}}">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}" value="{{$product['order_product_details']->id}}">
                                                    <input type="hidden" name="product[{{$key}}][order]" value="{{$product->id}}">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="actual_pieces_{{$key}}" class="form-control " placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="{{ $product->actual_pieces}}" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" maxlength="10" onblur="fetch_average_quantity();" onclick="clear_actual_qty();" >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="average_weight_{{$key}}" class="form-control" placeholder="Average Weight" name="product[{{$key}}][average_weight]" value="{{$product->actual_quantity}}" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" onblur="fetch_average_quantity();" maxlength="10" onclick="clear_actual_qty();">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <!--                                                <div class="form-group">
                                                                                                    <input type="text" class="form-control" name="average_quantity" id="average_quantity" readonly="readonly">-->
                                                <div class="form-group"><div id="average_quantity_{{$key}}">{{$actual_quantity}}</div></div>
                                                </div>
                                            </td>
                                            <td class="col-md-1 sfdsf">
                                                <div class="form-group"><div id="actual_quantity_readonly_{{$key}}" name="product[{{$key}}][actual_quantity]"></div></div>
                                                <input id="actual_quantity_{{$key}}"  name="product[{{$key}}][actual_quantity]" value="" type="hidden" >
                                                <!--                                                <div class="form-group">
                                                                                                    <input id="quantity_{{$key}}" type="hidden" value="{{ $product->present_shipping}}" name="product[{{$key}}][quantity]">
                                                                                                    @if($product->present_shipping >=0)
                                                                                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][actual_quantity]" value="" type="text" onkeypress=" return numbersOnly(this,event,true,true);" onblur="fetch_price();">
                                                                                                    @elseif($product->present_shipping <0)
                                                                                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][actual_quantity]" value="" type="text" onkeypress=" return numbersOnly(this,event,true,true);" onblur="fetch_price();">
                                                                                                    @endif
                                                                                                </div>-->
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    {{ $product->present_shipping}}
                                                    <input id="present_shipping_{{$key}}" class="form-control text-center" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="{{ $product->present_shipping}}" type="hidden" >
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">{{$product->price}}<input type="hidden" class="form-control" id="product_price_{{$key}}" value="{{$product->price}}" name="product[{{$key}}][price]" placeholder="Price" onblur="fetch_price();"></div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input class="vat_chkbox" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes">
                                                    <!--<input type="hidden" class="form-control" id="product_vatpercentage_{{$key}}" value="{{$product->vat_percentage}}" name="product[{{$key}}][vat_percentage]" placeholder="Vat Percentage" onblur="fetch_price();">-->
                                                </div>
                                            </td>
                                            <td class="col-md-2">
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
                                            <td class="col-md-2">
                                                <div class="form-group"><div id="amount_{{$key}}"></div></div>
                                            </td>
                                        </tr>
                                        <?php $key++; ?>
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

                            <!--                            <label for="total_actual_qty">
                                                            <b class="challan">Actual Quantity*</b> 
                                                            <input type="text" class="form-control error_check" id="total_actual_qty" name="total_actual_qty" placeholder="Enter Actual Quantity" onblur="fetch_actual_quantity();" onfocus="error_check(this)" onkeypress=" return numbersOnly(this, event, false, false);"> 
                                                        </label>-->
                            <label for="total_actual_qty_truck">
                                <b class="challan">Actual Quantity*</b> 
                                <input type="text" class="form-control" id="total_actual_qty_truck" name="total_actual_qty_truck" readonly="" >  
                            </label>
                            &nbsp;&nbsp;
                            <label for="total_avg_qty">
                                <b class="challan">Total Avg Quantity*</b>
                                <input type="text" value ="{{$actualsum}}"class="form-control" id="total_avg_qty" name="total_avg_qty" placeholder="" readonly="readonly">
                                <!--                                <div class="form-group"><div id="total_avg_qty"></div></div>-->
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
                                            <input type="text" class="form-control" id="total_actual_quantity_calc" name="total_actual_quantity_calc" placeholder="" readonly="readonly">
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 no_left_margin">
                                        <label for="Loading"><b class="challan">Loading</b></label>
                                        <input id="loading_charge" class="form-control" placeholder="Loading Charges" name="loading" value ="" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" onblur="grand_total_challan();">
                                    </div>
                                    <!--                            <div class="col-md-4">
                                                                    <label for="Loading Vat Percentage"><b class="challan">Loading Vat Percentage</b></label>
                                                                    <input id="loading_vat_percentage" class="form-control" placeholder="Loading Vat Percentage" name="loading_vat_percentage" value="" type="tel" onblur="grand_total_challan();">
                                                                </div>
                                                                <div class="col-md-4 no_right_margin">
                                                                    <label for="Total Loading Vat Charges"><b class="challan">Total Loading Charges</b></label>
                                                                    <input id="loading_total_charge" readonly="" class="form-control" value="">
                                                                </div>-->
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 no_left_margin">
                                        <label for="Discount"><b class="challan">Discount</b></label>
                                        <input id="discount_value" class="form-control" placeholder="Discount " name="discount" value ="" type="tel" onblur="grand_total_challan();" onkeypress=" return numbersOnly(this, event, true, true);">
                                    </div>
                                    <!--                            <div class="col-md-4">
                                                                    <label for="Loading_discount_percentage"><b class="challan">Discount Vat Percentage</b></label>
                                                                    <input id="discount_vat_percentage" class="form-control" placeholder="Discount Vat Percentage" name="discount_vat_percentage" value="" type="tel" onblur="grand_total_challan();">
                                                                </div>
                                                                <div class="col-md-4 no_right_margin">
                                                                    <label for="Total_frieght_charges"><b class="challan">Total Discount Charges</b></label>
                                                                    <input id="discount_total_charge" readonly="" class="form-control" value="">
                                                                </div>-->
                                </div>
                                <!--
                                <div class="form-group">
                                    <label for="Discount"><b class="challan">Discount</b></label>
                                    <input id="discount_value" class="form-control" placeholder="Discount " name="discount" value="" type="tel" onblur="grand_total_challan();">
                                </div>
                                -->
                                <div class="form-group">
                                    <div class="col-md-12 no_left_margin">
                                        <label for="Freight"><b class="challan">Freight</b></label>
                                        <input id="freight_value" class="form-control" placeholder="Freight " name="freight" value="" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" onblur="grand_total_challan();">
                                    </div>
                                    <!--                            <div class="col-md-4">
                                                                    <label for="Loading_frieght_percentage"><b class="challan">Freight Vat Percentage</b></label>
                                                                    <input id="freight_vat_percentage" class="form-control" placeholder="Freight Vat Percentage" name="freight_vat_percentage" value="" type="tel" onblur="grand_total_challan();">
                                                                </div>
                                                                <div class="col-md-4 no_right_margin">
                                                                    <label for="Total_frieght_charges"><b class="challan">Total Freight Charges</b></label>
                                                                    <input id="freight_total_charge" readonly="" class="form-control" value="">
                                                                </div>-->
                                </div>
                                <div class="form-group" style="display: none">
                                    <label for="Total"><b class="challan">Total</b></label>
                                    <div id="total_l_d_f"></div>
                                </div>
                                <div class="form-group">
                                    
                                    @if($produc_type['profile'] == 1)
                                    <label for="loadedby_profile"><b class="challan">Loaded By (Profile)</b></label>
                                    <div class="form-group clearfix">
                                        <select id="loaded_by_select_profile" name='loaded_by_profile[]' class="form-control" multiple="multiple">
                                            @if(isset($loaders))
                                            @foreach ($loaders as $loader)
                                            <option value="{{$loader->id}}">{{$loader->first_name}} {{$loader->last_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @endif
<!--<input id="loadedby" class="form-control" placeholder="Loaded By" name="loadedby" value="" type="text">-->
                                </div>
                                <div class="form-group">
                                     @if($produc_type['pipe'] == 1)
                                    <label for="labour_pipe"><b class="challan">Labour (Pipe)</b></label>
                                    <div class="form-group clearfix">
                                        <select id="labour_select_pipe" name="labour_pipe[]" class="form-control" multiple="multiple">
                                            @if(isset($labours))
                                            @foreach ($labours as $labour)
                                            <option value="{{$labour->id}}">{{$labour->first_name}} {{$labour->last_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @endif
                                    @if($produc_type['structure'] == 1)
                                    <label for="labour_structure"><b class="challan">Labour (Structure)</b></label>
                                    <div class="form-group clearfix">
                                        <select id="labour_select_structure" name="labour_structure[]" class="form-control" multiple="multiple">
                                            @if(isset($labours))
                                            @foreach ($labours as $labour)
                                            <option value="{{$labour->id}}">{{$labour->first_name}} {{$labour->last_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @endif
                                    @if($produc_type['profile'] == 1)
                                    <label for="labour_profile"><b class="challan">Labour (Profile)</b></label>
                                    <div class="form-group clearfix">
                                        <select id="labour_select_profile" name="labour_profile[]" class="form-control" multiple="multiple">
                                            @if(isset($labours))
                                            @foreach ($labours as $labour)
                                            <option value="{{$labour->id}}">{{$labour->first_name}} {{$labour->last_name}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @endif
<!--<input id="labour" class="form-control" placeholder="Labour" name="labour" value="" type="tel">-->
                                </div>
                                
                                <!--                                                <div class="form-group">
                                                                                    <label for="vatp"><b class="challan">VAT Value : </b>
                                                                                        <span id="vat_val"></span>
                                                                                    </label>
                                                                                </div>-->
                                @if($delivery_data->vat_percentage==0)
                                <!--                        <div class="form-group">
                                                            <label for="Plusvat"><b class="challan">Plus VAT : </b> No</label>
                                                        </div>-->
                                @else
                                <!--                        <div class="form-group">
                                                            <label for="vatp"><b class="challan">VAT Percentage : </b>
                                                                {{$delivery_data->vat_percentage}}
                                                                <input type="hidden" name="vat_percentage" id="vat_percentage" value="{{$delivery_data->vat_percentage}}" readonly="readonly">
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="vatp"><b class="challan">VAT Value : </b>
                                                                <span id="vat_val"></span>
                                                            </label>
                                                        </div>-->
                                @endif
                                <div class="form-group" style="display: none">
                                    <label for="vatp"><b class="challan">Total : </b>
                                        <span class="gtotal">
                                            <input type="text" class="form-control" name="vat_total" id="vat_tot_val" readonly="readonly">
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="RoundOff"><b class="challan">Round Off</b></label>
                                    <input id="round_off" class="form-control" placeholder="Round Off" name="round_off" value="" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" onblur="grand_total_challan();">
                                </div>
                                <div class="form-group" style="display: none">
                                    <label for="Grand_total"><b class="challan">Grand Total : </b>
                                        <span class="gtotal">
                                            <input type="text" class="form-control" name="grand_total" id="grand_total" readonly="readonly">
                                        </span>
                                    </label>
                                </div>
                                @if($delivery_data->vat_percentage > 0)
                                <div class="form-group" style="display: none">
                                    <label for="billno"><b class="challan">Bill Number</b></label>
                                    <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="" type="hidden">
                                </div>
                                @endif
                                <div class="form-group">
                                    <label for="challan_vehicle_number"><b class="challan">Vehicle Number</b></label>
                                    <input id="challan_vehicle_number" class="form-control" name="challan_vehicle_number" value="{{isset($delivery_data->vehicle_number)?$delivery_data->vehicle_number:''}}" type="text">
                                </div>
                                
                                <div class="form-group">
                                    <label for="challan_remark"><b class="challan">Remark</b></label>
                                    <textarea class="form-control" id="challan_remark" name="challan_remark" rows="3">{{isset($delivery_data->remarks)?$delivery_data->remarks:''}}</textarea>
                                </div>
                                <hr>
                                <div>
                                    <button type="submit" class="btn btn-primary form_button_footer btn_delorderto_delchallan">Submit</button>
                                    <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                                </div>
                                <div class="clearfix"></div>
                                {!! Form::close() !!}
                                <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop
