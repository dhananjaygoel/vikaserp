@extends('layouts.master')
@section('title','Add Orders')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Place Order</span></li>
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
                        <form id="onenter_prevent" data-button='btn_add_order' method="POST" action="{{URL::action('OrderController@store')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
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
                                <div class="radio">
                                    <input checked="" value="warehouse" id="warehouse_radio" name="status" type="radio">
                                    <label for="warehouse_radio">Warehouse</label>
                                    <input  value="supplier" id="supplier_radio" name="status" type="radio">
                                    <label for="supplier_radio">Supplier</label>
                                </div>
                                <div class="supplier_order" style="display:none">
                                    <select class="form-control" name="supplier_id" id="add_status_type">
                                        <option value="" selected="">Select supplier</option>
                                        @if(count($customers)>0)
                                        @foreach($customers as $customer)
                                        @if($customer->customer_status == 'permanent')
                                        <option value="{{$customer->id}}" >{{$customer->tally_name}}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <br/>
                                <label>Customer<span class="mandatory">*</span></label>
                                <div class="radio">
                                    <input checked value="existing_customer" id="existing_customer" name="customer_status" type="radio" class="existing_customer_order" {{(Input::old('customer_status') == "existing_customer")? 'checked' : ''}}>
                                    <label for="existing_customer" >Existing</label>
                                    <input value="new_customer" id="new_customer" class="new_customer_order" name="customer_status" type="radio" {{(Input::old('customer_status') == "new_customer")?'checked':''}}> 
                                    <label for="new_customer">New</label>
                                </div>                                
                                <style>
.searchproduct .fa-sort-desc{position: absolute;right:6px;top:7px;cursor:pointer;}
                                </style>
                                <div class="customer_select_order" style="{{(Input::old('customer_status') == "new_customer")?'display:none':'display:block'}}">
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                            <input class="form-control focus_on_enter tabindex1" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name" tabindex="1" >
                                            <input type="hidden" id="existing_customer_id" name="existing_customer_name">
                                            <input type="hidden" id="customer_default_location">
                                                <!--<i class="fa fa-sort-desc " id='existing_customer_id_focus'></i>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="new_customer_details" style="{{(Input::old('customer_status') == "new_customer")?'display:block':'display:none'}}">
                                <div class="form-group">
                                    <label for="name">Customer Name<span class="mandatory">*</span></label>
                                    <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{old('customer_name')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">Contact Person<span class="mandatory">*</span></label>
                                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{old('contact_person')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number" onkeypress="return validation_only_digit();" maxlength="10" name="mobile_number" value="{{old('mobile_number')}}" type="tel">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" onkeypress="return validation_only_digit();" value="{{old('credit_period')}}" type="tel">
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                        <select class="form-control focus_on_enter tabindex2" name="add_order_location" id="add_order_location" tabindex="2" >
                                            <option value="0" selected="">Delivery Location</option>
                                            @foreach($delivery_locations as $delivery_location)
                                            @if($delivery_location->status=='permanent' && $delivery_location->id!=0)
                                            <option value="{{$delivery_location->id}}" data-location-difference="{{$delivery_location->difference}}">{{$delivery_location->area_name}}</option>
                                            @endif
                                            @endforeach
                                            <option id="other_location" value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="location">Location Difference</label>
                                        <input id="location_difference" class="form-control focus_on_enter tabindex3" placeholder="Location Difference " name="location_difference" value="" type="tel" onkeypress=" return validation_digit();" tabindex="3" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="locationtext">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location</label>
                                        <input id="location" class="form-control" placeholder="Location " name="location" value="" type="text">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="order_table col-md-12">
                                <div class="table-responsive">
                                    <table id="add_product_table" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Price</span><span class="mandatory">*</span></td>
                                                <td class="inquiry_vat_chkbox"><span>Vat</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                            <?php
                                            $session_data = Session::get('input_data');
                                            if (isset($session_data['product'])) {
                                                $total_products_added = sizeof($session_data['product']);
                                            }
                                            $j = (isset($total_products_added) && ($total_products_added > 10)) ? $total_products_added : 1;
                                            for ($i = 1; $i <= $j; $i++) {
                                                ?>
                                                <tr id="add_row_{{$i}}" class="add_product_row" data-row-id="{{$i}}">
                                                    <td class="col-md-3">
                                                        <div class = "form-group searchproduct">
                                                            <input class = "form-control focus_on_enter each_product_detail tabindex4" placeholder = "Enter Product name" data-productid="{{$i}}" type = "text" name = "product[{{$i}}][name]" id = "add_product_name_{{$i}}" onfocus = "product_autocomplete({{$i}});" value = "<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" tabindex="4" >
                                                            <input type = "hidden" name = "product[{{$i}}][id]" id = "add_product_id_{{$i}}" value = "">
                                                            <i class = "fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class = "form-group">
                                                            <input id = "quantity_{{$i}}" class = "form-control each_product_qty" data-productid="{{$i}}" placeholder = "Qnty" name = "product[{{$i}}][quantity]" type = "tel" onkeypress=" return validation_digit();" value = "<?php if (isset($session_data['product'][$i]['quantity'])) { ?>{{$session_data['product'][$i]['quantity']}}<?php } ?>">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class = "form-group ">
                                                            <select class = "form-control" name = "product[{{$i}}][units]" id = "units_{{$i}}">
                                                                @foreach($units as $unit)
                                                                <option value = "{{$unit->id}}">{{$unit->unit_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class = "form-group">
                                                            <input type = "tel" class = "form-control" id = "product_price_{{$i}}" name = "product[{{$i}}][price]" placeholder = "Price" onkeypress=" return validation_digit();" value = "{{(isset($session_data['product'][$i]['price'])) ?$session_data['product'][$i]['price'] : ''}}">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group inquiry_vat_chkbox">
                                                            <!--<input type="text" class="form-control" id="vat_percentage_{{$i}}" name="product[{{$i}}][vat_percentage]" placeholder="Vat percentage" value = "{{(isset($session_data['product'][$i]['vat_percentage'])) ?$session_data['product'][$i]['vat_percentage'] : ''}}">-->
                                                            <input class="vat_chkbox" type="checkbox" name="product[{{$i}}][vat_percentage]" value="yes">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <div class = "form-group">
                                                            <input id = "remark" class = "form-control" placeholder = "Remark" name = "product[{{$i}}][remark]" type = "text" value = "{{(isset($session_data['product'][$i]['remark'])) ?$session_data['product'][$i]['remark'] : ''}}">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            Session::put('input_data', '');
                                            ?>
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
                            <!--
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="include_vat" id="all_inclusive" name="status1" type="radio">
                                    <label for="all_inclusive">All Inclusive</label>
                                    <input value="exclude_vat" id="vat_inclusive" name="status1" type="radio">
                                    <label for="vat_inclusive">Plus VAT</label>
                                </div>
                            </div>
                            -->
                            <div class="vat_field ">
                                <div class="form-group">
                                    <table id="table-example" class="table">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_price" onkeypress=" return validation_digit();" value="" type="text"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-4 targetdate">
                                <label for="time">Expected Delivery Date:<span class="mandatory">*</span> </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_date" value="{{Input::old('expected_date')!=''?Input::old('expected_date'):date('m-d-Y')}}" class="form-control" id="expected_delivery_date_order">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="order_remark">Remark</label>
                                <textarea class="form-control" id="order_remark" name="order_remark" rows="3"></textarea>
                            </div>
                            <div class="checkbox">
                                <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email</span></label>
                            </div>
                            <div>
                                <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip btn_add_order_sms" id="add_order_sendSMS" >Save and Send SMS</button>
                            </div>
                            <hr>
                            <div>
                                <input type="hidden" name="total_products" id="total_products" value="{{isset($existig_product)?$existig_product:10}}">
                                <button type="submit" class="btn btn-primary form_button_footer btn_add_order">Submit</button>
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