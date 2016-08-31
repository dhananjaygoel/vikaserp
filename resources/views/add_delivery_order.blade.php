@extends('layouts.master')
@section('title','Add Delivery Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                    <li class="active"><span>Add Delivery Order</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                </div>
            </div>
        </div>
        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
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
                        <div class="form-group">Date: {{date('d F, Y')}}</div>
                        <form id="onenter_prevent" method="POST" action="{{URL::action('DeliveryOrderController@store')}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">                                                        
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            <div class="form-group">
                                <label>Customer<span class="mandatory">*</span></label>
                                <div class="radio">
                                    <input value="exist_customer" id="exist_customer" name="customer_status" checked="" type="radio"
                                    <?php
                                    if (Input::old('customer_status') == "exist_customer") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="exist_customer">Existing</label>
                                    <input value="new_customer" id="new_customer" name="customer_status" type="radio"
                                    <?php
                                    if (Input::old('customer_status') == "new_customer") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="new_customer">New</label>
                                </div>
                                <div class="" style="{{(Input::old('customer_status') == "new_customer")?'display: none':'display: block'}}">
                                    <div class="col-md-4">
                                        <div class="form-group ">
                                            <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" id="existing_customer_name" name="existing_customer_name" tabindex="1"/>
                                            
                                            <input type="hidden" id="existing_customer_id" name="autocomplete_customer_id">
                                            <input type="hidden" id="customer_default_location">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="exist_field" style="{{(Input::old('customer_status') == "new_customer")?'display: block':'display: none'}}">
                                <div class="form-group">
                                    <label for="name">Customer Name<span class="mandatory">*</span></label>
                                    <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{ old('customer_name') }}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">Contact Person<span class="mandatory">*</span></label>
                                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ old('contact_person') }}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number<span class="mandatory">*</span> </label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{ old('mobile_number') }}" type="tel">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{ old('mobile_number') }}" type="tel">
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                        <select class="form-control focus_on_enter" name="add_order_location" id="add_order_location" tabindex="2">
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
                                        <input id="location_difference" class="form-control focus_on_enter" placeholder="Location Difference " name="location_difference" value="" type="tel" tabindex="3"  />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="clearfix"></div>
                            <div class="locationtext">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location Name" name="other_location_name" value="" type="text">
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="add_product_table" class="table table-hover">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Price</span><span class="mandatory">*</span></td>
                                                <td><span>Vat Percentage</span></td>
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
                                                        <div class="form-group searchproduct">
                                                            <input class="form-control each_product_detail focus_on_enter" data-productid="{{$i}}" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="product_autocomplete({{$i}});" value="<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" tabindex="4" >
                                                            <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="">
                                                            <i class="fa fa-search search-icon"></i>
                                                            <input type="hidden" name="product[{{$i}}][order]" value="">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group">
                                                            <input id="quantity_{{$i}}" class="form-control each_product_qty" placeholder="Qnty" name="product[{{$i}}][quantity]" type="tel" onblur="calculate_grand_total();" value="<?php if (isset($session_data['product'][$i]['quantity'])) { ?>{{$session_data['product'][$i]['quantity']}}<?php } ?>">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                            <select class="form-control" name="product[{{$i}}][units]" id="units_{{$i}}">
                                                                @foreach($units as $unit)
                                                                <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group ">
                                                            <!-- form for save product value-->
                                                            <input type="tel" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price" value = "{{(isset($session_data['product'][$i]['price'])) ?$session_data['product'][$i]['price'] : ''}}">
                                                            <!--
                                                        <div class="form-group col-md-6 difference_form">
                                                            <input class="btn btn-primary" type="button" class="form-control" value="save" >
                                                        </div>
                                                            -->
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="vat_percentage_{{$i}}" name="product[{{$i}}][vat_percentage]" placeholder="Vat Percentage" value = "{{(isset($session_data['product'][$i]['vat_percentage'])) ?$session_data['product'][$i]['vat_percentage'] : ''}}">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                            <input id="remark" class="form-control" placeholder="Remark" name="product[{{$i}}][remark]" type="text" value = "{{(isset($session_data['product'][$i]['remark'])) ?$session_data['product'][$i]['remark'] : ''}}">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <?php Session::put('input_data', ''); ?>
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
                            <div class="form-group">
                                <label for="vehicle_name">Vehicle Number</label>
                                <input id="vehicle_number" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="" type="text">
                            </div>
                            <!--                            <div class="form-group">
                                                            <label for="driver_name">Driver Name</label>
                                                            <input id="driver_name" class="form-control" placeholder="Driver Name " name="driver_name" value="" type="text">
                                                        </div>-->
                            <div class="form-group">
                                <label for="driver_contact">Driver Contact</label>
                                <input id="driver_contact" class="form-control" placeholder="Driver Contact" name="driver_contact" value="" type="tel">
                            </div>

                            <div class="clearfix"></div>
                            <!--
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="include_vat" id="optionsRadios5" name="status1" type="radio" onclick="calculate_grand_total();">
                                    <label for="optionsRadios5">All Inclusive</label>
                                    <input value="exclude_vat" id="optionsRadios6" name="status1" type="radio" onclick="calculate_grand_total();">
                                    <label for="optionsRadios6">Plus VAT</label>
                                </div>
                            </div>
                            -->
                            <div class="plusvat " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_price" value="" type="tel" onblur="calculate_grand_total();"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="order_remark" name="order_remark"  rows="3"></textarea>
                            </div>
                            <div >
                                <!--<button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button>-->
                            </div>
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer btn_add_delivery_order" >Submit</button>
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
@include('autocomplete_tally_product_name')
@stop