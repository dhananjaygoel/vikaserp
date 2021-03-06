@extends('layouts.master')
@section('title','Place Order')
@section('content')
<?php
// echo '<pre>';
// print_r($inquiry['inquiry_products']);
// exit;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('inquiry')}}">Inquiry</a></li>
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
                        {!! Form::open(array('method'=>'POST','url'=>url('store_order/'.$inquiry->id), 'id'=>'place_order_form'))!!}
                        @if (count($errors->all()) > 0)
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
                        <?php $role_id = (isset($inquiry['createdby']->role_id)?$inquiry['createdby']->role_id:0); ?>
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="inquiry_id" value="{{$inquiry->id}}">                                                        
                        <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                        <input type="hidden" name="customer_id" value="{{$inquiry['customer']->id}}" id="hidden_cutomer_id">
                        <div class="radio">
                            <input checked="" value="warehouse" id="warehouse_radio" name="status" type="radio">
                            @if(Auth::user()->role_id <> 5 & $role_id <> 5)
                            <label for="warehouse_radio">Warehouse</label>
                            @endif
                            <input  value="supplier" id="supplier_radio" name="status" type="radio">
                            @if(Auth::user()->role_id <> 5 & $role_id <> 5)
                            <label for="supplier_radio">Supplier</label>
                            @endif
                        </div>
                        <div class="supplier_order" style="display:none">
                            <select class="form-control" name="supplier_id" id="add_status_type">
                                <option value="" selected="">Select supplier</option>
                                @if(count((array)$customers)>0)
                                @foreach($customers as $customer)
                                @if($customer->customer_status == 'permanent')
                                <option value="{{$customer->id}}" >{{$inquiry['customer']->tally_name}}</option>
                                @endif
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <br/>
                        <?php
                        ?>

                        @if($inquiry['customer']->customer_status =="pending")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input value="existing_customer" id="optionsRadios1" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                @if(Auth::user()->role_id <> 5 & $role_id <> 5)
                                <label for ="optionsRadios1">Existing</label>
                                @endif
                                <input checked="" value="new_customer" id="optionsRadios2" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                @if(Auth::user()->role_id <> 5 & $role_id <> 5)
                                <label for="optionsRadios2">New</label>
                                @endif
                            </div>
                            <div class="customer_select" style="display: none">
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        @if(Auth::user()->role_id <> 5)
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" name="existing_customer_name" id="existing_customer_name" tabindex="1" >
                                        @endif

                                        @if(Auth::user()->role_id == 5 )
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" name="existing_customer_name" id="existing_customer_name1" disabled="" tabindex="1" >
                                        @endif



                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="" type="hidden">
                                        <!--<i class="fa fa-search search-icon"></i>-->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field">
                            <input type="hidden" id='pending_user_id' name="pending_user_id" value='{{$inquiry['customer']->id}}'/>
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{$inquiry['customer']->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{$inquiry['customer']->contact_person}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Phone Number </label>
                                <input id="mobile_number" class="form-control" placeholder="Phone Number " onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" name="mobile_number" value="{{$inquiry['customer']->phone_number1}}" type="tel">
                            </div>
                            <div class="form-group">
                                <label for="period">Credit Period(Days)</label>
                                <input id="period" class="form-control" placeholder="Credit Period" onkeypress=" return numbersOnly(this, event, false, false);" name="credit_period" value="{{$inquiry['customer']->credit_period}}" type="tel">
                            </div>
                        </div>
                        @elseif($inquiry['customer']->customer_status == "permanent")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input checked="" value="existing_customer" id="optionsRadios1" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                @if(Auth::user()->role_id <> 5  & $role_id <> 5)
                                <label for="optionsRadios1">Existing</label>
                                @endif
                                <input  value="new_customer" id="optionsRadios2" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                @if(Auth::user()->role_id <> 5 & $role_id <> 5)
                                <label for="optionsRadios2">New</label>
                                @endif
                            </div>
                            <div class="customer_select" >
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        @if(Auth::user()->role_id <> 5 & $role_id <> 5)
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$inquiry['customer']->tally_name}}" id="existing_customer_name" tabindex="1" >
                                        @endif

                                        @if(Auth::user()->role_id == 5 & $role_id == 5)
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$inquiry['customer']->tally_name}}" id="existing_customer_name1"  disabled="" tabindex="1" >
                                        @endif

                                        @if(Auth::user()->role_id <> 5 & $role_id == 5)
                                        <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$inquiry['customer']->tally_name}}" id="existing_customer_name1"  disabled="" tabindex="1" >
                                        @endif

                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="{{$inquiry['customer']->id}}" type="hidden">
                                        <!--<i class="fa fa-search search-icon"></i>-->
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field " style="display: none">
                            <div class="form-group">
                                <input type="hidden" id='pending_user_id' name="pending_user_id" value=''/>
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number </label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" type="tel">
                            </div>
                            <div class="form-group">
                                <label for="period">Credit Period(Days)</label>
                                <input id="period" class="form-control" placeholder="Credit Period" onkeypress=" return numbersOnly(this, event, false, false);" name="credit_period" value="" type="tel">
                            </div>
                        </div>
                        @endif
                        <div class="row col-md-12">
                            <div class="form-group">
                                <div class="col-md-4">
                                    <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                    <select class="form-control" name="add_inquiry_location" id="add_order_location">
                                        <option value="0">Delivery Location</option>
                                        @foreach($delivery_location as $location)
                                        <option value="{{$location->id}}" <?php
                                                        if ($inquiry->delivery_location_id == $location->id) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?> data-location-difference="{{$location->difference}}">{{$location->area_name}}</option>
                                        @endforeach
                                        <option id="other_location" value="other" <?php
                                        if ($inquiry->delivery_location_id == 0) {
                                            echo 'selected="selected"';
                                        }
                                                        ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="location">Freight</label>
                                    <input id="location_difference" class="form-control" placeholder="Freight " onkeypress=" return numbersOnly(this, event, true, true);" name="location_difference" value="{{$inquiry->location_difference}}" type="tel">
                                </div>
                                @if(Auth::user()->role_id <> 5)
                                    <div class="col-md-2">
                                        <label for="location">Discount/Premium:</label>                                        
                                        <select class="form-control focus_on_enter tabindex2" name="discount_type" id="discount_type" tabindex="2" >
                                            <option value="Discount" selected="">Discount</option>
                                            <option value="Premium">Premium</option>
                                        </select>                                        
                                    </div>
                                    <div class="col-md-2">
                                        <label for="location">Fixed/Percentage:</label>                                        
                                        <select class="form-control focus_on_enter tabindex2" name="discount_unit" id="discount_unit" tabindex="2" >
                                            <option value="Fixed" selected="">Fixed</option>
                                            <option value="Percent">Percent</option>
                                        </select>                                        
                                    </div>
                                    <div class="col-md-2">
                                        <label for="discount">Amount</label>                                         
                                            <input id="discount_amount" class="form-control focus_on_enter tabindex3" placeholder="Amount " name="discount" value="" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" tabindex="3" >                                          
                                    </div>
                                    @endif
                                    @if(Auth::user()->role_id == 5) 
                                        <input type = "hidden" name ="discount_type"  value = "">
                                        <input type = "hidden" name ="discount_unit"  value = "">
                                        <input type = "hidden" name ="discount"  value = "0">
                                    @endif
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <br>
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table" class="table table-hover  ">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>length</span></td>
                                            <td><span>Quantity</span></td>
                                            <td><span>Price</span></td>
                                            <td class="inquiry_vat_chkbox"><span>GST </span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        @foreach($inquiry['inquiry_products'] as $key=>$product)
                                        <tr id="add_row_{{$key}}" class="add_product_row" data-row-id="{{$key}}">
                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    <input class="form-control focus_on_enter each_product_detail" data-productid="{{$key}}" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" value="{{isset($product['inquiry_product_details'])?$product['inquiry_product_details']->alias_name: ''}}" tabindex="2" >
                                                    <input type="hidden" name="product[{{$key}}][id]"  id="add_product_id_{{$key}}" value="{{$product->product_category_id}}">
                                                    <!--<i class="fa fa-search search-icon"></i>-->
                                                </div>
                                            </td>

                                            <td class="col-md-2">
                                                <div class="form-group ">
                                                    <select class="form-control unit" name="product[{{$key}}][units]" id="units_{{$key}}" onchange="unitType(this);">
                                                        <?php if($product->unit_id == 1 || $product->unit_id == 2 || $product->unit_id == 3) { ?>
                                                            <option value=1 id = 'unit_{{$key}}_1' {{($product->unit_id == 1)?'selected':''}}>KG</option>
                                                            <option value=2 id = 'unit_{{$key}}_2' {{($product->unit_id == 2)?'selected':''}}>Pieces</option>
                                                            <option value=3 id = 'unit_{{$key}}_3' {{($product->unit_id == 3)?'selected':''}}>Meter</option>
                                                        <?php } elseif($product->unit_id == 4 || $product->unit_id == 5) { ?>
                                                            <option value=4 id = 'unit_{{$key}}_4' {{($product->unit_id == 4)?'selected':''}}>ft</option>
                                                            <option value=5 id = 'unit_{{$key}}_5' {{($product->unit_id == 5)?'selected':''}}>mm</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>

                                            <td class="col-md-1">
                                                <div class = "form-group">
                                                    <div class = "form-group length_list_{{$key}}">
                                                    <input id = "length_{{$key}}" class = "form-control each_length_qnty" data-productid="{{$key}}"  name = "product[{{$key}}][length]" type = "tel" onkeypress=" return numbersOnly(this, event, true, true);" {{$product['length'] == '' ? 'disabled': ''}} value = "{{ (isset($product['length'])) ? $product['length']:''}}">
                                                </div>
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group meter_list_{{$key}}" {{($product->unit_id==3)?'':'style=display:none'}} >
                                                    <input id="quantity_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][quantity]" onkeypress=" return numbersOnly(this, event, true, true);" value="{{$product->quantity}}" type="tel">
                                                </div>
                                                <div class = "form-group kg_list_{{$key}}" {{($product->unit_id==1)?'':'style=display:none'}}>
                                                    <select class = "form-control kg_list" name = "kg_list" id = "kg_list_{{$key}}" onchange="setQty(this);">
                                                        <?php for ($n = 50; $n <= 15000; $n++) { ?>
                                                            <option {{($product->quantity == $n)?'selected':''}} value = "{{$n}}">{{$n}}</option>
                                                            <?php
                                                            $n = $n + 49;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class = "form-group pieces_list_{{$key}}" {{($product->unit_id=='2')?'':'style=display:none'}}>
                                                    <select class = "form-control pieces_list " name = "pieces_list" id = "pieces_list_{{$key}}" onchange="setQty(this);">
                                                        <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                            <option {{($product->quantity == $z)?'selected':''}} value = "{{$z}}">{{$z}}</option>
                                                            <?php
                                                          //  ($z == 1) ? $z = $z + 3 : $z = $z + 4;
                                                        }
                                                        ?>                                                 
                                                    </select>
                                                </div>

                                                <div class = "form-group ff_list_{{$key}}" {{($product->unit_id==4)?'':'style=display:none'}}>
                                                    <select class = "form-control ff_list " name = "ff_list" id = "ff_list_{{$key}}" onchange="setQty(this);">
                                                    <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                        <option {{($product->quantity == $z)?'selected':''}} value = "{{$z}}">{{$z}}</option>
                                                        <?php
                                                        // ($z == 1) ? $z = $z + 3 : $z = $z + 4;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class = "form-group mm_list_{{$key}}" {{($product->unit_id==5)?'':'style=display:none'}}>
                                                    <select class = "form-control mm_list " name = "mm_list" id = "mm_list_{{$key}}" onchange="setQty(this);">
                                                    <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                        <option {{($product->quantity == $z)?'selected':''}} value = "{{$z}}">{{$z}}</option>
                                                        <?php
                                                        // ($z == 1) ? $z = $z + 3 : $z = $z + 4;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="{{$product->price}}" id="product_price_{{$key}}" onkeypress=" return numbersOnly(this, event, true, true);" name="product[{{$key}}][price]">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group inquiry_vat_chkbox">
                                                    <!--<input type="text" class="form-control" id="vat_percentage_{{$key}}" name="product[{{$key}}][vat_percentage]" placeholder="GST percentage" value="{{$product->vat_percentage}}">-->
                                                    <input class="vat_chkbox" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes" onchange="check_vat();">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="remark" class="form-control" placeholder="Remark" name="product[{{$key}}][remark]" value="{{$product->remarks}}" type="text">
                                                </div>
                                            </td>
                                        </tr>
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
                        <br>
                        @if($inquiry->delivery_location_id == 0)
                        <div class="locationtext" id="other_location_input_wrapper" style="display: block;">
                            <div class="row col-md-12">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{$inquiry->other_location}}" type="text">
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="locationtext" id="other_location_input_wrapper">
                            <div class="row col-md-12">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="" type="text">
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="clearfix"></div>

                        @if($inquiry->vat_percentage == 0)
                        <!--
                        <div class="form-group">
                            <div class="radio">
                                <input checked="" value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                <label for="optionsRadios3">All Inclusive</label>
                                <input value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                <label for="optionsRadios4">Plus GST</label>
                            </div>
                        </div>
                        <div class="plusvat " style="display: none">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">GST Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="GST Percentage" name="vat_percentage" value="" type="text"></td>
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
                                <label for="optionsRadios4">Plus GST</label>
                            </div>
                        </div>
                        <div class="plusvat">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">GST Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="GST Percentage" name="vat_percentage" value="{{$inquiry->vat_percentage}}" type="text"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        -->
                        @endif
                        <div class="form-group col-md-4 targetdate">
                            <label for="date">Expected Delivery Date: </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="expected_date" class="form-control" id="expected_delivery_date" value="{{date('d/m/Y', strtotime($inquiry->expected_delivery_date))}}" >
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="inquiry_remark">Remark</label>
                            <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3">{{$inquiry->remarks}}</textarea>
                        </div>
                        <!--<button type="button" class="btn btn-primary btn_inquiry_to_order_sms" id="add_inquiry_to_order_sendSMS" >Save and Send SMS</button>-->
                        <!-- <div class="checkbox">
                            <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email</span></label>
                        </div> -->
                        <div class="checkbox customer_select_order">
                                <!-- <label class="marginsms" style="margin-right:10px;"><input type="checkbox" id="send_whatsapp" name="send_whatsapp" value="yes"><span class="checksms">Send Whatsapp</span></label> -->
                                <label class="marginsms"><input type="checkbox" id="send_msg" name="send_msg" value="yes"><span class="checksms">Send SMS</span></label>
                        </div>
                        <hr>
                        <div>
                            <input type="submit" class="btn btn-primary form_button_footer btn_inquiry_to_order" value="Submit">
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
<!-- {{-- @include('autocomplete_tally_product_name') --}} -->
@stop