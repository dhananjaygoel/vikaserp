@extends('layouts.master')
@section('title','Edit Delivery Order')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                        <li class="active"><span>Edit Delivery Order</span></li>
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
                            <div class="form-group">Date : {{date('d F, Y')}}</div>
                            {!!Form::open(array('data-button'=>'btn_edit_delivery_order','method'=>'PUT','url'=>url('delivery_order/'.$delivery_data['id']),'id'=>'onenter_prevent'))!!}
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            @if($delivery_data->order_source == 'supplier')
                                <div class="form-group">
                                    <label for="cn"><b>Supplier Name: </b>
                                    @foreach($customers as $customer)
                                        @if($customer->id == $delivery_data->supplier_id)
                                            {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @if($delivery_data['customer']->customer_status =="pending")
                                <div class="form-group">
                                    <label>Customer<span class="mandatory">*</span></label>
                                    <div class="radio">
                                        <input <?php if ($delivery_data['customer']->customer_status == 'permanent') echo 'checked=""'; ?> value="existing_customer" id="exist_customer" name="customer_status"  type="radio">
                                        <label for="exist_customer">Existing</label>
                                        <input <?php if ($delivery_data['customer']->customer_status == 'pending') echo 'checked=""'; ?>  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                        <label for="new_customer">New</label>
                                    </div>
                                    <div class="customer_select" style="display: none">
                                        <div class="col-md-4">
                                            <div class="form-group searchproduct">
                                                <input class="form-control" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name">
                                                <input type="hidden" id="existing_customer_id" name="autocomplete_customer_id">
                                                <!--<i class="fa fa-search search-icon"></i>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="exist_field">
                                    <input type="hidden" id='pending_user_id' name="pending_user_id" value='{{$delivery_data['customer']->id}}'/>
                                    <div class="form-group">
                                        <label for="name">Customer Name<span class="mandatory">*</span></label>
                                        <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{ $delivery_data['customer']->owner_name }}" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Contact Person<span class="mandatory">*</span></label>
                                        <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ $delivery_data['customer']->contact_person }}" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                        <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{ $delivery_data['customer']->phone_number1 }}" type="tel">
                                    </div>
                                    <div class="form-group">
                                        <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                        <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" onkeypress=" return numbersOnly(this,event,false,false);" value="{{ $delivery_data['customer']->credit_period }}" type="tel">
                                    </div>
                                </div>
                            @elseif($delivery_data['customer']->customer_status == "permanent")
                                <div class="form-group">
                                    <label>Customer<span class="mandatory">*</span></label>
                                    <div class="radio">
                                        <input <?php if ($delivery_data['customer']->customer_status == 'permanent') echo 'checked=""'; ?> value="existing_customer" id="exist_customer" name="customer_status"  type="radio">
                                        <label for="exist_customer">Existing</label>
                                        <input <?php if ($delivery_data['customer']->customer_status == 'pending') echo 'checked=""'; ?>  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                        <label for="new_customer">New</label>
                                    </div>
                                    <div class="customer_select">
                                        <div class="col-md-4">
                                            <div class="form-group searchproduct">
                                                <input value="{{ $delivery_data['customer']->tally_name }}" class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name" tabindex="1">
                                                <input type="hidden" value="{{ $delivery_data['customer']->id }}" id="existing_customer_id" name="autocomplete_customer_id">
                                                <!--<i class="fa fa-search search-icon"></i>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="exist_field" style="display: none">
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
                                        <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="tel">
                                    </div>
                                    <div class="form-group">
                                        <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                        <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="" type="tel">
                                    </div>
                                </div>
                            @endif
                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                        <select class="form-control focus_on_enter" name="add_order_location" id="add_order_location" tabindex="2" class="ui-dform-select">
                                            <option value="0" selected="">Delivery Location</option>
                                            @foreach($delivery_locations as $delivery_location)
                                                @if($delivery_location->status=='permanent' && $delivery_location->id!= 0)
                                                    <option  data-location-difference="{{$delivery_location->difference}}" <?php if ($delivery_location->id == $delivery_data->delivery_location_id) echo 'selected=""'; ?>  value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
                                                @endif
                                            @endforeach
                                            <option {{($delivery_data->delivery_location_id == 0)?'selected':''}} id="other_location" value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="location">Freight</label>
                                        <input id="location_difference" class="form-control focus_on_enter" placeholder="Freight " onkeypress=" return numbersOnly(this,event,true,true);" name="location_difference" value="{{ $delivery_data->location_difference}}" type="tel" tabindex="3" >
                                    </div>

                                </div>
                            </div>
                            @if($delivery_data->discount > 0)
                                <div class="form-group">
                                    <label><b>Discount/Premium :</b> </label>
                                    {{$delivery_data->discount_type}}
                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$delivery_data->discount_type}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Fixed/Percentage :</b> </label>
                                    {{$delivery_data->discount_unit}}
                                    <input type="hidden" id="discount_unit" name="discount_unit" value="{{$delivery_data->discount_unit}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Amount :</b> </label>
                                    {{$delivery_data->discount}}
                                    <input type="hidden" id="discount_amount" name="discount" value="{{$delivery_data->discount}}" >
                                </div>
                            @else
                                <div class="form-group">
                                    <label><b>Discount/Premium :</b> </label>
                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$delivery_data->discount_type}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Fixed/Percentage :</b> </label>
                                    <input type="hidden" id="discount_unit" name="discount_unit" value="{{$delivery_data->discount_unit}}" >
                                </div>
                                <div class="form-group">
                                    <label><b>Amount :</b> </label>
                                    <input type="hidden" id="discount_amount" name="discount" value="{{$delivery_data->discount}}" >
                                </div>
                            @endif
                            <div class="clearfix"></div>
                            <div class="locationtext"<?php if ($delivery_data->delivery_location_id == 0) echo 'style="display:block;"'; ?>>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location Name" name="location" value="{{ $delivery_data->other_location}}" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                <table id="add_product_table_delivery_order" class="table table-hover">
                                        <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Length</span></td>
                                            <td><span>Quantity</span></td>
                                            <td><span>Present Shipping</span></td>
                                            <td><span>Price</span><span class="mandatory">*</span></td>
                                            <td class="inquiry_vat_chkbox"><span>GST</span></td>
                                            <td><span>Pending Quantity</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        <?php
                                        $session_data = Session::get('input_data');
                                        if (isset($session_data['product'])) {
                                        $total_products_added = sizeof($session_data['product']);
                                        $a = sizeof($delivery_data['delivery_product']);
                                        for ($i = 0; $i <= $total_products_added + 1; $i++) {
                                        if (isset($session_data['product'][$i]['name'])) {
                                        if ($i <= $a) {
                                        ?>
                                        <tr id="add_row_{{$i}}" class="add_product_row" data-row-id="{{$i}}" {{($session_data['product'][$i]['present_shipping']==0)?'style = display:none':''}}>
                                        <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    <input value="{{$session_data['product'][$i]['name']}}" class="form-control" placeholder="Enter Product name " type="hidden" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="product_autocomplete({{$i}});">
                                                    <input type="hidden" name="product[{{$i}}][product_category_id]" id="add_product_id_{{$i}}" value="{{$session_data['product'][$i]['product_category_id']}}">
                                                    <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="{{$session_data['product'][$i]['id']}}">
                                                    <input type="hidden" name="product[{{$i}}][order]" value="{{ $session_data['product'][$i]['order']}}">
                                                    <!--                                                    <i class="fa fa-search search-icon"></i>-->
                                                    {{$session_data['product'][$i]['name']}}
                                                </div>
                                                <input type="hidden" name="prod_id" value="{{$i}}">
                                            </td>
                                           <td class="col-md-1">
                                                <div class="form-group ">
                                                    <select class="form-control" name="product[{{$i}}][units]" id="units_{{$i}}">
                                                        @foreach($units as $unit)
                                                            @if($session_data['product'][$i]['units'] == $unit->id)
                                                                <option value="{{$unit->id}}" selected="">{{$unit->unit_name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                          </tr>
                                        <?php } else {
                                        ?>
                                        <tr id="add_row_{{$i}}" class="add_product_row" data-row-id="{{$i}}" {{($session_data['product'][$i]['present_shipping']==0)?'style = display:none':''}}>
                                         <td>xxxx</td><td>xxxx</td>
                                         </tr>
                                          <?php }}}}?>
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
                                                            <span class="fa-stack more_button" ><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-plus fa-stack-1x fa-inverse"></i></span>
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
                                <label for="vehicle_name">Vehicle Number</label>
                                <input id="vehicle_number" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{ $delivery_data->vehicle_number }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="driver_contact">Driver Contact</label>
                                <input id="driver_contact" class="form-control" placeholder="Driver Contact" name="driver_contact" value="{{ $delivery_data->driver_contact_no }}" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" type="tel">
                            </div>
                            <div class="clearfix"></div>


                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="order_remark" name="order_remark"  rows="3">{{ $delivery_data->remarks }}</textarea>
                            </div>
                            <div>
                                <!--<button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button>-->
                            </div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer btn_edit_delivery_order">Submit</button>
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
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

    {{-- @include('autocomplete_tally_product_name') --}}

@stop