@extends('layouts.master')
@section('title','Edit Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('inquiry')}}">Inquiry</a></li>
                    <li class="active"><span>Edit Inquiry</span></li>
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
                        {!! Form::open(array('method'=>'PUT','url'=>url('inquiry',$inquiry->id), 'id'=>'onenter_prevent','data-button'=>'btn_edit_inquiry'))!!}
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
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if (Session::has('flash_message_error'))
                        <div class="alert alert-danger">{{ Session::get('flash_message_error') }}</div>
                        @endif
                        <input type="hidden" name="inquiry_status" value="{{$inquiry->inquiry_status}}">
                        <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="inquiry_id" value="{{$inquiry->id}}">
                        <input type="hidden" name="customer_id" value="{{$inquiry['customer']->id}}" id="hidden_cutomer_id">
                        @if($inquiry['customer']->customer_status =="pending")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input value="existing_customer" id="optionsRadios1" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                <label for ="optionsRadios1">Existing</label>
                                <input checked="" value="new_customer" id="optionsRadios2" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                <label for="optionsRadios2">New</label>
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
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{$inquiry['customer']->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person<span class="mandatory">*</span></label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{$inquiry['customer']->contact_person}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Phone Number <span class="mandatory">*</span></label>
                                <input id="mobile_number" class="form-control" placeholder="Phone Number " name="mobile_number" value="{{$inquiry['customer']->phone_number1}}" type="text">
                            </div>

                            <div class="form-group">
                                <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" onkeypress=" return validation_only_digit();" value="{{$inquiry['customer']->credit_period}}" type="tel">
                            </div>
                        </div>
                        @elseif($inquiry['customer']->customer_status == "permanent")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input checked="" value="existing_customer" id="optionsRadios1" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                <label for="optionsRadios1">Existing</label>
                                <input  value="new_customer" id="optionsRadios2" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                <label for="optionsRadios2">New</label>
                            </div>
                            <div class="customer_select" >
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$inquiry['customer']->tally_name}}" id="existing_customer_name" tabindex="1" >
                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="{{$inquiry['customer']->id}}" type="hidden">
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
                                <label for="name">Contact Person<span class="mandatory">*</span></label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="tel"  maxlength="10" onkeypress=" return numbersOnly(this,event,false,false);">
                            </div>

                            <div class="form-group">
                                <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                <input id="period" class="form-control" placeholder="Credit Period" onkeypress=" return numbersOnly(this,event,false,false);" name="credit_period" value="" type="text">
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
                                        @if($location->status=='permanent' && $location->id!=0)
                                        @if($inquiry->delivery_location_id == $location->id)
                                        <option value="{{$location->id}}" selected="" data-location-difference="{{$location->difference}}">{{$location->area_name}}</option>
                                        @else
                                        <option value="{{$location->id}}" data-location-difference="{{$location->difference}}">{{$location->area_name}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                        @if($inquiry->delivery_location_id == 0)
                                        <option id="other_location" value="other" selected="">Other</option>
                                        @else
                                        <option id="other_location" value="other">Other</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="location">Freight</label>
                                    <input id="location_difference" class="form-control focus_on_enter" placeholder="Freight " name="location_difference" value="{{$inquiry->location_difference}}" type="tel" onkeypress=" return numbersOnly(this,event,true,true);" tabindex="3" >
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        @if($inquiry->delivery_location_id==0)
                        <div class="locationtext" id="other_location_input_wrapper" style="display: block;">
                            <div class="row col-md-12">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{$inquiry->other_location}}" type="text">
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="locationtext" id="other_location_input_wrapper" style="display: none;">
                            <div class="row">
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
                                            <td><span>Price</span></td>
                                            <td class="inquiry_vat_chkbox"><span>Vat</span></td>
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
                                                                <input class="form-control each_product_detail each_product_detail_edit" data-productid="{{$i}} focus_on_enter" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="product_autocomplete({{$i}});" value="<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" tabindex="4" >
                                                                <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="<?php if (isset($session_data['product'][$i]['id'])) { ?>{{$session_data['product'][$i]['id']}}<?php } ?>">
                                                                <i class="fa fa-search search-icon"></i>
                                                            </div>
                                                        </td>
                                                        <td class="col-md-1">
                                                            <div class="form-group">
                                                                <input id="quantity_{{$i}}" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[{{$i}}][quantity]" type="tel" value="<?php if (isset($session_data['product'][$i]['quantity'])) { ?>{{$session_data['product'][$i]['quantity']}}<?php } ?>">
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
                                                                <input type="text" class="form-control" id="product_price_{{$i}}" onkeypress=" return numbersOnly(this,event,true,true);" name="product[{{$i}}][price]" placeholder="Price" value="<?php if (isset($session_data['product'][$i]['price'])) { ?>{{$session_data['product'][$i]['price']}}<?php } ?>">
                                                            </div>
                                                        </td>
                                                        <td class="col-md-1">
                                                            <div class="form-group inquiry_vat_chkbox">
                                                                <input type="text" class="form-control" id="vat_percentage_{{$i}}" name="product[{{$i}}][vat_percentage]" placeholder="Vat percentage" value="yes">
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
                                            <?php $counter = 0; ?>
                                            @foreach($inquiry['inquiry_products'] as $key=>$product)
                                            <tr id="add_row_{{$key}}" class="add_product_row" data-row-id="{{$key}}">
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control each_product_detail each_product_detail_edit" data-productid="{{$key}}" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" onfocus="product_autocomplete({{$key}});" value="{{isset($product['inquiry_product_details'])?$product['inquiry_product_details']->alias_name: ''}}" @if($counter==0)tabindex="4" class="ui-dform-text" @endif >
                                                               <input type="hidden" name="product[{{$key}}][id]" value="{{$product->product_category_id}}" id="add_product_id_{{$key}}">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                    <?php $counter++; ?>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity_{{$key}}" class="form-control each_product_qty" placeholder="Qnty" onkeypress=" return numbersOnly(this,event,true,true);" name="product[{{$key}}][quantity]" value="{{$product->quantity}}" type="tel">
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
                                                        <input type="text" class="form-control" value="{{$product->price}}" onkeypress=" return numbersOnly(this,event,true,true);" id="product_price_{{$key}}" name="product[{{$key}}][price]">
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group inquiry_vat_chkbox">
                                                        <!--<input type="text" class="form-control" id="vat_percentage_{{$key}}" name="product[{{$key}}][vat_percentage]" placeholder="Vat percentage" value="{{$product->vat_percentage}}">-->
                                                        <input class="vat_chkbox" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes">
                                                    </div>
                                                </td>
                                                <td class="col-md-4">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="product[{{$key}}][remark]" value="{{$product->remarks}}" type="text">
                                                    </div>
                                                </td>
                                            </tr>
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
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" onkeypress=" return onlyPercentage(event);" value="{{$inquiry->vat_percentage}}" type="text" ></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        @if($inquiry->vat_percentage == 0)
                        <!--
                        <div class="form-group">
                            <div class="radio">
                                <input checked="" value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                <label for="optionsRadios3">All Inclusive</label>
                                <input value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                <label for="optionsRadios4">Plus VAT</label>
                            </div>
                        </div>
                        <div class="plusvat " style="display: none">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">VAT Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="" type="tel"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        -->
                        @elseif($inquiry->vat_percentage != 0)
                        <!--
                        <div class="form-group">
                            <div class="radio">
                                <input value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                <label for="optionsRadios3">All Inclusive</label>
                                <input checked="" value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                <label for="optionsRadios4">Plus VAT</label>
                            </div>
                        </div>
                        <div class="plusvat">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">VAT Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$inquiry->vat_percentage}}" type="text"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        -->
                        @endif
                        <div class="form-group col-md-4 targetdate">
                            <label for="date">Expected Delivery Date:</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="expected_date" class="form-control" id="expected_delivery_date" value="{{date('m-d-Y', strtotime($inquiry->expected_delivery_date))}}" >
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="inquiry_remark">Remark</label>
                            <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3">{{$inquiry->remarks}}</textarea>
                        </div>
                        <button type="button" class="btn btn-primary btn_edit_inquiry_sms" id="edit_inquiry_sendSMS" >Save and Send SMS</button>
                        <hr>
                        <div>
                            <button type="submit" class="btn btn-primary form_button_footer btn_edit_inquiry">Submit</button>
                            <!--<input type="submit" class="btn btn-primary form_button_footer btn_edit_inquiry" value="Submit">-->
                            <a href="{{URL::to('inquiry')}}" class="btn btn-default form_button_footer">Back</a>
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