@extends('layouts.master')
@section('title','Edit Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Edit Order</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        {!! Form::open(array('method'=>'PUT','url'=>url('orders',$order->id), 'id'=>'onenter_prevent','data-button'=>'btn_edit_order'))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
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
                        <div class="form-group">
                            @if($order->order_source == 'warehouse')
                            <div class="radio">
                                <input checked="" value="warehouse" id="warehouse_radio" name="status" type="radio" onchange="show_hide_supplier($order - > order_source)">
                                @if(Auth::user()->role_id <> 5 & $order['createdby']->role_id <> 5)
                                <label for="warehouse_radio">Warehouse</label>
                                @endif
                                <input  value="supplier" id="supplier_radio" name="status" type="radio">
                                @if(Auth::user()->role_id <> 5 & $order['createdby']->role_id <> 5)
                                <label for="supplier_radio">Supplier</label>
                                @endif
                            </div>
                            <div class="supplier_order" style="display:none">
                                <select class="form-control" name="supplier_id" id="add_status_type">
                                    <option value="" selected="">Select supplier</option>
                                    @if(count($customers)>0)
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" >{{$customer->tally_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @elseif($order->order_source == 'supplier')
                            <div class="radio">
                                <input value="warehouse" id="warehouse_radio" name="status" type="radio">
                                <label for="warehouse_radio">Warehouse</label>
                                <input  checked="" value="supplier" id="supplier_radio" name="status" type="radio" onchange="show_hide_supplier($order - > order_source)">
                                <label for="supplier_radio">Supplier</label>
                            </div>
                            <div class="supplier_order">
                                <select class="form-control" name="supplier_id" id="add_status_type">
                                    <option value="" disabled="">Select supplier</option>
                                    @if(count($customers)>0)
                                    @foreach($customers as $customer)
                                    <option
                                    <?php
                                    if ($customer->id == $order->supplier_id) {
                                        echo 'selected="selected"';
                                    }
                                    ?> value="{{$customer->id}}">
                                        {{($customer->tally_name != "")?$customer->tally_name:$customer->owner_name}}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            <br/>
                            <div class="clearfix"></div>
                        </div>
                        @if($order['customer']->customer_status =="pending")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input value="existing_customer" id="existing_customer" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                <label for ="existing_customer">Existing</label>
                                <input checked="" value="new_customer" id="new_customer" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                <label for="new_customer">New</label>
                            </div>
                            <div class="customer_select" style="display: none">
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" name="existing_customer_name" id="existing_customer_name" tabindex="1" >
                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="" type="hidden">
                                        <!--<i class="fa fa-search search-icon"></i>-->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field">
                            <input type="hidden" id='pending_user_id' name="pending_user_id" value='{{$order['customer']->id}}'/>
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{$order['customer']->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{$order['customer']->contact_person}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Phone Number </label>
                                <input id="mobile_number" class="form-control" placeholder="Phone Number " onkeypress=" return numbersOnly(this,event,false,false);" maxlength="10" name="mobile_number" value="{{$order['customer']->phone_number1}}" type="tel">
                            </div>

                            <div class="form-group">
                                <label for="period">Credit Period(Days)</label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" onkeypress=" return numbersOnly(this,event,false,false);" value="{{$order['customer']->credit_period}}" type="tel">
                            </div>
                        </div>
                        @elseif($order['customer']->customer_status == "permanent")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input checked="" value="existing_customer" id="optionsRadios1" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                @if(Auth::user()->role_id <> 5 & $order['createdby']->role_id <> 5)
                                <label for="optionsRadios1">Existing</label>
                                @endif
                                <input  value="new_customer" id="optionsRadios2" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                @if(Auth::user()->role_id <> 5 & $order['createdby']->role_id <> 5)
                                <label for="optionsRadios2">New</label>
                                 @endif
                            </div>
                            <div class="customer_select" >
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        @if(Auth::user()->role_id <> 5 & $order['createdby']->role_id <> 5 )
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$order['customer']->tally_name}}" id="existing_customer_name" tabindex="1" >
                                        @endif
                                        @if(Auth::user()->role_id == 5 & $order['createdby']->role_id == 5)
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$order['customer']->tally_name}}" id="existing_customer_name1" disabled="" tabindex="1" >
                                        
                                        @endif
                                        @if(Auth::user()->role_id <> 5 & $order['createdby']->role_id == 5 )
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$order['customer']->tally_name}}" id="existing_customer_name1" disabled="" tabindex="1" >
                                        
                                        @endif
                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="{{$order['customer']->id}}" type="hidden">
                                        <!--<i class="fa fa-search search-icon"></i>-->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field " style="display: none">
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number </label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number " onkeypress=" return numbersOnly(this,event,false,false);" maxlength="10" name="mobile_number" value="" type="tel">
                            </div>

                            <div class="form-group">
                                <label for="period">Credit Period(Days)</label>
                                <input id="period" onkeypress=" return numbersOnly(this,event,false,false);"  class="form-control" placeholder="Credit Period" name="credit_period" value="" type="tel">
                            </div>
                        </div>
                        @endif
                        <div class="row col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                    <select class="form-control focus_on_enter" name="add_inquiry_location" id="add_order_location" tabindex="2" >
                                        <option value="0">Delivery Location</option>
                                        @foreach($delivery_location as $location)
                                        <option value="{{$location->id}}" {{($order->delivery_location_id == $location->id)?'selected':''}} data-location-difference="{{$location->difference}}">{{$location->area_name}}</option>
                                        @endforeach
                                        <option id="other_location" value="other" {{($order->delivery_location_id == 0)?'selected':''}} >Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="location">Freight</label>
                                    <input id="location_difference" class="form-control focus_on_enter" placeholder="Freight" onkeypress=" return numbersOnly(this,event,true,true);" name="location_difference" value="{{$order->location_difference}}" type="tel" tabindex="3">
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        @if($order->delivery_location_id == 0)
                        <div class="locationtext" id="other_location_input_wrapper" style="display: block;">
                            <div class="row col-md-12">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{$order->other_location}}" type="text">
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="locationtext" id="other_location_input_wrapper" style="display: none;">
                            <div class="row col-md-12">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="" type="text">
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table" class="table table-hover  ">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Quantity</span></td>
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Price</span><span class="mandatory">*</span></td>
                                            <td class="inquiry_vat_chkbox"><span>Vat Percentage</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        <?php
                                        $session_data = Session::get('input_data');
                                        if (isset($session_data['product'])) {
                                            $total_products_added = sizeof($session_data['product']);
                                            for ($i = 0; $i <= $total_products_added; $i++) {
                                                if (isset($session_data['product'][$i]['name'])) {
                                                    ?>
                                                    <tr id="add_row_{{$i}}" class="add_product_row" data-row-id="{{$i}}">
                                                        <td class="col-md-3">
                                                            <div class="form-group searchproduct">
                                                                <input class="form-control focus_on_enter each_product_detail" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="product_autocomplete({{$i}});" value="<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?> " tabindex="4" >
                                                                <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="<?php if (isset($session_data['product'][$i]['id'])) { ?>{{$session_data['product'][$i]['id']}}<?php } ?>">
                                                                <input type="hidden" name="product[{{$i}}][order]" value="<?php if (isset($session_data['product'][$i]['order'])) { ?>{{$session_data['product'][$i]['order']}}<?php } ?>">
                                                                <i class="fa fa-search search-icon"></i>
                                                            </div>
                                                        </td>
                                                        <td class="col-md-1">
                                                            <div class="form-group">
                                                                <input id="quantity_{{$i}}" class="form-control" placeholder="Qnty" name="product[{{$i}}][quantity]" onkeypress=" return numbersOnly(this,event,true,true);" type="tel" value="<?php if (isset($session_data['product'][$i]['quantity'])) { ?>{{$session_data['product'][$i]['quantity']}}<?php } ?>">
                                                            </div>
                                                        </td>
                                                        <td class="col-md-2">
                                                            <div class="form-group ">
                                                                <select class="form-control" name="product[{{$i}}][units]" id="units_{{$i}}">
                                                                    @foreach($units as $unit)
                                                                    <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td class="col-md-2">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price" onkeypress=" return numbersOnly(this,event,true,true);" value="<?php if (isset($session_data['product'][$i]['price'])) { ?>{{$session_data['product'][$i]['price']}}<?php } ?>">
                                                            </div>
                                                        </td>
                                                        <td class="col-md-1">
                                                            <div class="form-group inquiry_vat_chkbox">
                                                                <input type="text" class="form-control" id="vat_percentage_{{$i}}" name="product[{{$i}}][vat_percentage]" placeholder="Vat percentage" value="<?php if (isset($session_data['product'][$i]['vat_percentage'])) { ?>{{$session_data['product'][$i]['vat_percentage']}}<?php } ?>">
                                                            </div>
                                                        </td>
                                                        <td class="col-md-3">
                                                            <div class="form-group">
                                                                <input id="remark" class="form-control" placeholder="Remark" name="product[{{$i}}][remark]" type="text" value="<?php if (isset($session_data['product'][$i]['remark'])) { ?>{{$session_data['product'][$i]['remark']}}<?php } ?>">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            Session::put('input_data', '');
                                        } else {
                                            ?>
                                            @foreach($order['all_order_products'] as $key=>$product)
                                            @if($product->order_type =='order')
                                            <tr id="add_row_{{$key}}" class="add_product_row" data-row-id="{{$key}}">
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control each_product_detail" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" value="{{$product['order_product_details']->alias_name}}" onfocus="product_autocomplete({{$key}});" tabindex="4" class="ui-dform-text">
                                                        <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}"  value="{{$product->product_category_id}}">
                                                        <input type="hidden" name="product[{{$key}}][order]" value="{{$product->id}}">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity_{{$key}}" class="form-control" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[{{$key}}][quantity]" value="{{$product->quantity}}" type="tel">
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group ">
                                                        <select class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}">
                                                            @foreach($units as $unit)
                                                            @if($product->unit_id == $unit->id)
                                                            <option value="{{$unit->id}}" selected="">{{$unit->unit_name}}</option>
                                                            @else
                                                            <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="tel" class="form-control" value="{{$product->price}}" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_{{$key}}" name="product[{{$key}}][price]">
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group inquiry_vat_chkbox">
                                                        <!--<input type="text" class="form-control" id="vat_percentage_{{$key}}" name="product[{{$key}}][vat_percentage]" placeholder="Vat percentage" value="{{$product->vat_percentage}}">-->
                                                        <input class="vat_chkbox" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes">
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="product[{{$key}}][remark]" value="{{$product->remarks}}" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <table>
                                    <tbody>
                                        <tr class="row5">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">
                                                        <label for="addmore"></label>
                                                        <a class="table-link" title="add more" id="add_product_row">
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
                        <div class="plusvat">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">VAT Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" onkeypress=" return onlyPercentage(event);" name="vat_percentage" value="{{$order->vat_percentage}}" type="tel"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($order->vat_percentage == 0)
                        
<!--                        <div class="form-group">
                            <div class="radio">
                                <input checked="" value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                <label for="optionsRadios3">All Inclusive</label>
                                <input value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                <label for="optionsRadios4">Plus VAT</label>
                            </div>
                        </div>-->
<!--                        <div class="plusvat " style="display: none">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">VAT Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$order->other_location}}" type="text"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
                        
                        @elseif($order->vat_percentage != 0)
                        
<!--                        <div class="form-group">
                            <div class="radio">
                                <input value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                <label for="optionsRadios3">All Inclusive</label>
                                <input checked="" value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                <label for="optionsRadios4">Plus VAT</label>
                            </div>
                        </div>-->
<!--                        <div class="plusvat">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">VAT Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$order->vat_percentage}}" type="tel"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>-->
                        
                        @endif
                        <div class="clearfix"></div>
                        <div class="form-group col-md-4 targetdate">
                            <label for="date">Expected Delivery Date: </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="expected_date" class="form-control" id="expected_delivery_date_order" value="{{date('m-d-Y', strtotime($order->expected_delivery_date))}}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="order_remark">Remark:</label>
                            <textarea class="form-control" id="order_remark" name="order_remark"  rows="3">{{$order->remarks}}</textarea>
                        </div>
                        <div class="checkbox">
                            <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email</span></label>
                        </div>
                        <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip btn_edit_order_sms" id="edit_order_sendSMS" >Save and Send SMS</button>
                        <hr>
                        <div>
                            <button type="submit" class="btn btn-primary form_button_footer btn_edit_order">Submit</button>
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
@include('autocomplete_tally_product_name')
@stop
