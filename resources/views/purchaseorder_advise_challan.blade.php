@extends('layouts.master')
@section('title','Purchase Order Advise Challan')
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
        <ol class="breadcrumb">
            <li><a href="{{url('purchase_orders')}}">Purchase Advice</a></li>
            <li class="active"><span>Create Purchase Challan</span></li>
        </ol>
        <div class="clearfix">
            <h1 class="pull-left"></h1>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <div class="main-box-body clearfix">                
                @if (count($errors->all()) > 0)
                <div class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif
                @if (Session::has('validation_message'))
                <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('validation_message') }}</div>
                @endif                
                <form data-button="btn_puradvice_to_purchallan" method="POST" action="{{URL::action('PurchaseChallanController@store')}}" accept-charset="UTF-8" id="onenter_prevent">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                    <input type="hidden" name="purchase_advice_id" value="{{$purchase_advise->id}}"/>
                   
                    <input type="hidden" name="purchase_order_id" value="{{$purchase_advise->purchase_order_id}}"/>
                    <input type="hidden" name="delivery_location_id" value="{{$purchase_advise->delivery_location_id}}"/>

                    <div class="form-group">
                        <label><b>Bill Date:</b> {{ date("j F, Y", strtotime($purchase_advise->purchase_advice_date)) }}
                            <input type='hidden' class="form-control" name="bill_date" value="{{$purchase_advise->purchase_advice_date}}"/>
                        </label>
                    </div>
                    <div class="form-group">
                        <label><b>Serial Number:</b> {{$purchase_advise->serial_number}}
                            <input type="hidden" class="form-control" name="serial_no" value="{{$purchase_advise->serial_number}}"/>
                        </label>
                    </div>
                    <div class="form-group">
                        <label><b>Created By:</b> {{$purchase_advise['supplier']->owner_name }}
                            <input type="hidden" name="supplier_id" id="supplier_id" value="{{$purchase_advise['supplier']->id }}"/>
                            <input type="hidden" name="created_by" value="{{$purchase_advise->created_by }}"/>
                        </label>
                    </div>                    
                    @if((isset($purchase_advise['purchase_order'][0]->discount)) && $purchase_advise['purchase_order'][0]->discount > 0)
                        <div class="form-group">
                            <label><b>Discount/Premium :</b> </label>
                            {{isset($purchase_advise['purchase_order'][0]->discount_type)?$purchase_advise['purchase_order'][0]->discount_type:''}}
                        </div>
                        <div class="form-group">                                    
                                <label><b>Fixed/Percentage :</b> </label>
                                {{isset($purchase_advise['purchase_order'][0]->discount_unit)?$purchase_advise['purchase_order'][0]->discount_unit:''}}
                        </div>
                        <div class="form-group">                                    
                                <label><b>Amount :</b> </label>
                                ₹ {{isset($purchase_advise['purchase_order'][0]->discount)?$purchase_advise['purchase_order'][0]->discount:''}}
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
                    <div class="table-responsive">
                        <table id="table-example" class="table table_deliverchallan serial purchaseorder_advide_table ">
                            <tbody>
                                <tr>
                                    <td class="col-md-2"><span>Product Name(Alias)<span class="mandatory">*</span> </span></td>
                                    <td class="col-md-1"><span>Unit</span><span class="mandatory">*</span></td>
                                    <td class="col-md-1"><span>Length</span></td>
                                    <td class="col-md-2"><span>Actual Quantity</span></td>

                                    <td class="col-md-2 text-center"><span>Present Shipping</span></td>
                                    <td class="col-md-2"><span>Rate</span></td>
                                    <td class="col-md-2"><span>Amount</span></td>
                                </tr>
                                <?php $total_price = 0; ?>
                                @foreach($purchase_advise['purchase_products'] as $key=>$products)
                                <tr id="add_row_{{$key}}" class="add_product_row">
                            <input type="hidden" name="product[{{$key}}][purchase_advice_id]" value="{{$purchase_advise->id}}"/>
                            <input type="hidden" name="product[{{$key}}][id]" value="{{$products->id}}"/>
                             <input type="hidden" name="product[{{$key}}][actual_pieces]" value="{{$products->actual_pieces}}"/>   
                            <input type="hidden" name="product[{{$key}}][order_type]" value="purchase_advice"/>
                            <td>
                                <div class="form-group">
                                    {{$products['purchase_product_details']->alias_name}}
                                    <input type="hidden" name="product[{{$key}}][product_category_id]" id="add_product_id_{{$key}}" value="{{$products['purchase_product_details']->id}}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    {{$products['unit']->unit_name}}
                                    <input id="unit_id{{$key}}" name="product[{{$key}}][unit_id]" value="{{$products['unit']->id}}" type="hidden">
                                </div>
                            </td>
                            <td class="col-md-1">
                                <div class="form-group">
                                    {{$products->length}}
                                    <input id="length_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][length]" value="{{$products->length}}" type="hidden" >
                                </div>
                            </td>
                            <td>
                                <div class="form-group {{$key}}">
                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][quantity]" value="{{$products->present_shipping}}" type="text" onblur="purchase_challan_calculation();" onkeypress=" return numbersOnly(this, event, true, false);">
                                </div>
                                <!--                                <div class="form-group meter_list_{{$key}}" {{($products->unit_id==3)?'':'style=display:none'}}>
                                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="{{$products->present_shipping}}" type="text">
                                                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][quantity]" value="{{$products->present_shipping}}" type="text" onblur="purchase_challan_calculation();" onkeypress=" return numbersOnly(this, event, true, false);">
                                                                </div>-->
                                <!--                                <div class = "form-group kg_list_{{$key}}" {{($products->unit_id==1)?'':'style=display:none'}}>
                                                                    <select class = "form-control kg_list" name = "kg_list" id = "kg_list_{{$key}}" onchange="setQty(this);">
                                <?php for ($n = 50; $n <= 15000; $n++) { ?>
                                                                                    <option {{($products->quantity == $n)?'selected':''}} value = "{{$n}}">{{$n}}</option>
                                    <?php
                                    $n = $n + 49;
                                }
                                ?>
                                                                    </select>
                                                                </div>
                                                                <div class = "form-group pieces_list_{{$key}}" {{($products->unit_id=='2')?'':'style=display:none'}}>
                                                                    <select class = "form-control pieces_list " name = "pieces_list" id = "pieces_list_{{$key}}" onchange="setQty(this);">
                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                                    <option {{($products->quantity == $z)?'selected':''}} value = "{{$z}}">{{$z}}</option>
                                    <?php
//                                            ($z == 1) ? $z = $z + 3 : $z = $z + 4;
                                }
                                ?>                                                 
                                                                    </select>
                                                                </div>-->
                            </td>

                            <td>
                                <div class="form-group text-center">
                                    {{$products->present_shipping}}
                                    <input id="present_shipping_{{$key}}" name="product[{{$key}}][present_shipping]" value="{{$products->present_shipping}}" type="hidden">
                                </div>
                            </td>
                            <td class="shippingcolumn">
                                <div class="row ">
                                    <div class="form-group col-md-12">
                                        <!--<input type="text" class="form-control" id="difference" value="{{$products->price}}" placeholder="Rate">-->
                                        <input type="text" class="form-control" id="product_price_{{$key}}" value="{{$products->price}}" name="product[{{$key}}][price]" placeholder="Rate" onkeypress=" return numbersOnly(this, event, true, false);">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <?php $total_price += $products->present_shipping * $products->price; ?>
                                    <div id="amount_{{$key}}">₹ {{ $products->present_shipping * $products->price }}</div>
                                </div>
                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table >
                            <tr class="row5">
                                <td>
                                    <div class="add_button">
                                        <div class="form-group pull-left">
                                            <label for="addmore"></label>
                                            <!--                                            <a href="#" class="table-link" title="add more" id="add_more_product1" >
                                                                                            <span class="fa-stack more_button" >
                                                                                                <i class="fa fa-square fa-stack-2x"></i>
                                                                                                <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                                                            </span>
                                                                                        </a>-->
                                        </div>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">

                        <label><b>Total Actual Quantity : </b> <span id="total_actual_quantity">{{$purchase_advise['purchase_products']->sum('present_shipping')}} KG</span></label>
                        &nbsp;
                        &nbsp;
                        <label for="total"><b class="challan">Total Amount : </b> <span id="total_price2">₹ {{ $total_price }}</span></label>
                    </div>
                    @if ( ($purchase_advise->vat_percentage !=0 || $purchase_advise->vat_percentage != ''))
                    @if($purchase_advise['tcs_applicable'] == 1)
                        <div class="form-group">
                            <label for="tcs_applicable"><b class="challan">TCS Applicable:</b> Yes</label>
                            <input type="hidden" name="tcs_applicable" value="yes">
                        </div>
                        <div class="form-group">
                            <label for="tcs_percentage"><b class="challan">TCS Percentage:</b></label>
                            <input type="text" name="tcs_percentage" value="{{$purchase_advise->tcs_percentage}}" class="form-control" id="tcs_percentage" onblur="purchase_challan_calculation();">
                        </div>
                    @else
                        <div class="form-group">
                            <div class="checkbox">
                                    <label class="marginsms"><input type="checkbox" id="tcs_applicable" name="tcs_applicable" value="yes"><span class="checksms tcs-class">TCS Applicable</span></label>
                            </div>
                            <div class="tcs-applicable" id="tcs_percentage" style="display:none;">
                                <label for="tcs_percentage">TCS Percentage:</label>
                                <input type="text" name="tcs_percentage" value="0.1" class="form-control" id="tcs_percentage">
                            </div>
                        </div>
                    @endif
                    @endif
                    <div class="form-group">
                        <label for="vehicle_name"><b class="challan">Vehicle Number</b></label>
                        <input id="vehicle_number" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{$purchase_advise->vehicle_number}}" type="text">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_name"><b class="challan">Discount : ₹</b></label>
                        <input id="discount" class="form-control" placeholder="Discount" name="discount" value="" type="text" onblur="purchase_challan_calculation();" onkeypress=" return numbersOnly(this, event, true, true);">
                    </div>
                    <div class="form-group">
                        <label for="driver_name"><b class="challan">Freight<span class="mandatory">* : ₹</span></b></label>
                        <input id="freight" class="form-control" placeholder="Freight " name="Freight" value="" type="text" onblur="purchase_challan_calculation();" onkeypress=" return numbersOnly(this, event, true, true);">
                    </div>
                    <div class="form-group">
                        <label for="total"><b class="challan">Total : </b>₹ <span id="total_price">{{ $total_price }}</span></label>
                    </div>
                    @if(isset($purchase_advise['purchase_order'][0]->order_for) && $purchase_advise['purchase_order'][0]->order_for == 0)
                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Unloaded By</b><span class="mandatory">*</span></label>
    <!--                        <input id="loadedby" class="form-control" placeholder="Unloaded By" name="unloaded_by" value="1" type="hidden">-->
                            <div class="form-group clearfix loaded_by_select_pipe">
                                <select id="loaded_by_select_pipe" name='unloaded_by[]' class="form-control" multiple="multiple">
                                    @if(isset($loaders))
                                    @foreach ($loaders as $loader)
                                    <option value="{{$loader->id}}">{{$loader->first_name}} {{$loader->last_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour </b><span class="mandatory">*</span></label>
    <!--                        <input id="labour" class="form-control" placeholder="Labour" name="labour" value="11" type="hidden">-->
                            <div class="form-group clearfix labour_select_pipe">
                                <select id="labour_select_pipe" name="labour[]" class="form-control" multiple="multiple">
                                    @if(isset($labours))
                                    @foreach ($labours as $labour)
                                    <option value="{{$labour->id}}">{{$labour->first_name}} {{$labour->last_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    @else
                        <input type="hidden" class="form-control"  name="unloaded_by[]" value="0" >
                        <input type="hidden" class="form-control"  name="labour[]" value="0" >
                    @endif
                    @if($purchase_advise->vat_percentage==0 || $purchase_advise->vat_percentage== '')
                    <div class="form-group">
                        <label for="Plusvat"><b class="challan">Plus GST</b> : No </label>
                    </div>
                    @else
                    <div class="form-group">
                        <label for="driver_contact"><b class="challan">GST Percentage</b> {{$purchase_advise->vat_percentage}}</label>
                        <input id="vat_percentage" type="hidden" value="{{$purchase_advise->vat_percentage}}" name="vat_percentage"/>

                    </div>
                    <div class="form-group">
                        <label for="driver_contact"><b class="challan">GST Value : ₹</b> <div id="vat_value"></div></label>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="vatp"><b class="challan">Total : ₹</b>
                            <span class="gtotal">
                                <input type="text" class="form-control" name="vat_total" id="vat_tot_val" readonly="readonly">
                            </span>

                        </label>
                    </div>
                    <!-- <div class="form-group">
                        <label for="labour"><b class="challan">Round Off : ₹</b></label> -->
                        <input type="hidden" id="round_off" class="form-control" placeholder="Round Off" name="round_off" value="" onblur="purchase_challan_calculation();" onkeypress=" return numbersOnly(this, event, true, true);">
                    <!-- </div> -->

                    <div class="form-group">
                        <label for="total"><b class="challan">Grand Total : ₹</b> <div id="grand_total"></div>
                        </label>
                        <input type="hidden" id="grand_total_val" name="grand_total">
                    </div>
                    @if($purchase_advise->vat_percentage>0)
                    <div class="form-group">
                        <label for="billno"><b class="challan">Bill Number</b></label>
                        <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="" type="text">
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="inquiry_remark"><span class="checksms">Remark</span></label>
                        <textarea class="form-control" id="inquiry_remark" name="remark"  rows="3">{{$purchase_advise->remarks}}</textarea>
                    </div>
                    <!--   <button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->
                    <hr>
                    <div>
                        <button type="submit" class="btn btn-primary btn_puradvice_to_purchallan" >Submit</button>
                        <a href="{{URL::previous()}}" class="btn btn-default">Back</a>
                    </div>
                    <div class="clearfix"></div>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@endsection


