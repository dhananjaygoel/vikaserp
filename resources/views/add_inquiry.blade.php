@extends('layouts.master')
@section('title','Add Inquiry')
@section('content')
<?php 
    // dd($ip);
    $ip_array = [];
    $ipaddress = '';
    if (isset($ip) && !$ip->isEmpty()) {
        foreach ($ip as $key => $value) {
            $ip_array[$key] = $value->ip_address;
        }
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
    }   
     
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li style="<?php if(!in_array($ipaddress, $ip_array) && Auth::user()->role_id == 2){ echo 'display:none;'; }else {echo ""; }?>"><a href="{{url('inquiry')}}">Inquiry</a></li>
                    <li class="active"><span>Add Inquiry</span></li>
                </ol>
                <div class="clearfix"><h1 class="pull-left"></h1></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form id="onenter_prevent" data-button='btn_add_inquiry' name="add_inquiry_form" method="POST" action="{{URL::action('InquiryController@store')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
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
                            <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('flash_message') }}</div>
                            @endif
                            <div class="form-group">
                                <label>Customer<span class="mandatory">*</span></label>

                                <div class="radio">
                                    <input checked="" value="existing_customer" id="existing_customer" name="customer_status" type="radio" {{(Input::old('customer_status') == "existing_customer")? 'checked' : ''}}>
                                    @if(Auth::user()->role_id <> 5)
                                    <label for="existing_customer">Existing</label>
                                    @endif
                                    <input value="new_customer" id="new_customer" name="customer_status" type="radio" {{(Input::old('customer_status') == "new_customer")?'checked':''}}>
                                    @if(Auth::user()->role_id <> 5)
                                    <label for="new_customer">New</label>
                                    @endif
                                </div>

                                <div class="customer_select" style="{{(Input::old('customer_status') == "new_customer")?'display:none':'display:block'}}" >
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                            @if(Auth::user()->role_id <> 5)
                                            <input class="form-control focus_on_enter tabindex1" placeholder="Enter Tally Name" type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name" tabindex="1" />
                                            <input type="hidden" id="existing_customer_id" name="existing_customer_name">
                                            <input type="hidden" id="customer_default_location">    
                                            @endif

                                            @if(Auth::user()->role_id == 5)
                                            <input class="form-control " type="text" value="{{$inquiry->tally_name}}" id="existing_customer_name1" tabindex="1" autocomplete="off" name="existing_customer_name" disabled="yes">
                                            <input type="hidden" id="existing_customer_id" name="existing_customer_name" value="{{$inquiry->id}}">
                                            <input type="hidden" id="customer_default_location" value="{{$inquiry->delivery_location_id}}">
                                            @endif

<!--<i class="fa fa-search search-icon"></i>-->
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="exist_field " style="{{(Input::old('customer_status') == "new_customer")?'display:block':'display:none'}}">
                                <div class="form-group">
                                    <label for="name">Customer Name<span class="mandatory">*</span></label>
                                    <input id="customer_name" class="form-control" placeholder="Name" name="customer_name" value="{{ old('customer_name') }}" type="text">
<!--                                    <input id="customer_id" class="form-control" name="existing_customer_id" value="" type="hidden">-->
                                </div>
                                <div class="form-group">
                                    <label for="name">Contact Person<span class="mandatory">*</span></label>
                                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ old('contact_person') }}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number') }}" type="tel" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{ old('credit_period') }}" type="tel" onkeypress=" return numbersOnly(this, event, false, false);">
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="form-group col-md-4">
                                        <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                        @if(Auth::user()->role_id <> 5)
                                        <select class="form-control focus_on_enter tabindex2" name="add_inquiry_location" id="add_order_location" tabindex="2" >
                                            <option value="0" selected="">Delivery Location</option>
                                            @foreach($delivery_locations as $delivery_location)
                                            @if($delivery_location->status=='permanent' && $delivery_location->id!=0)
                                            <option value="{{$delivery_location->id}}" data-location-difference="{{$delivery_location->difference}}">{{$delivery_location->area_name}}</option>
                                            @endif
                                            @endforeach
                                            <option id="other_location" value="other">Other</option>
                                        </select>
                                        @endif  

                                        @if(Auth::user()->role_id == 5)

                                        <select class="form-control focus_on_enter" name="add_inquiry_location" id="add_order_location" tabindex="2" >
                                            <option value="0">Delivery Location</option>
                                            @foreach($delivery_locations as $location)
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
                                        @endif


                                    </div>
                                    <div class="col-md-4">
                                        <label for="location">Freight: </label>
                                        <!--<input id="location_difference" class="form-control" placeholder="Freight " name="location_difference" value="" type="tel">-->
                                        @if(Auth::user()->role_id <> 5)
                                        <input id="location_difference" class="form-control focus_on_enter tabindex3" placeholder="Freight " name="location_difference" value="" type="tel" tabindex="3" onkeypress=" return numbersOnly(this, event, true, true);">
                                        @endif

                                        @if(Auth::user()->role_id == 5)
                                        <input id="location_difference" class="form-control focus_on_enter tabindex3" placeholder="Freight " name="location_difference" value="{{$inquiry->delivery_location['difference']}}" type="tel" tabindex="3" onkeypress=" return numbersOnly(this, event, true, true);" >
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="locationtext" id="other_location_input_wrapper">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control focus_on_enter tabindex5" placeholder="Location " name="other_location_name" value="" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="add_product_table" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>length</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Price</span></td>
                                                <td class="inquiry_vat_chkbox"><span>GST</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                            <?php
                                            $session_data = Session::get('input_data');
                                            if (isset($session_data['product'])) {
                                                $total_products_added = sizeof($session_data['product']);
                                            }
                                            $j = (isset($total_products_added) && ($total_products_added > 1)) ? $total_products_added : 1;
                                            for ($i = 1; $i <= $j; $i++) {
                                                ?>
                                                <tr id="add_row_{{$i}}" class="add_product_row" data-row-id="{{$i}}">
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                            <input class="form-control each_product_detail " data-productid="{{$i}}" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="product_autocomplete({{$i}});" value="<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" />                                                           
                                                            <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="">
                                                            
                                                           
<!--                                                            <i class="fa fa-search search-icon"onclick="showProductCategory(this);" style="cursor: pointer" data-productid="{{$i}}"> Type</i>-->
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group ">
                                                            <select class="form-control unit" name="product[{{$i}}][units]" id="units_{{$i}}" onchange="unitType(this);">
                                                                    <option value='' id = 'unit_{{$i}}_0' selected="selected">--Select--</option>
                                                                    <option value=1 id = 'unit_{{$i}}_1'>KG</option>
                                                                    <option value=2 id = 'unit_{{$i}}_2'>Pieces</option>
                                                                    <option value=3 id = 'unit_{{$i}}_3'>Meter</option>
                                                                    <option value=4 id = 'unit_{{$i}}_4'>ft</option>
                                                                    <option value=5 id = 'unit_{{$i}}_5'>mm</option>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="col-md-1">
                                                        <div class = "form-group">
                                                            <div class = "form-group length_list_{{$i}}">
                                                            <input id = "length_{{$i}}" class = "form-control each_length_qnty" data-productid="{{$i}}"  name = "product[{{$i}}][length]" type = "text" onkeypress=" return numbersOnly(this, event, true, true);" value = "{{ (isset($session_data['product'][$i]['length'])) ? $session_data['product'][$i]['length']:''}}" disabled>
                                                        </div>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group meter_list_{{$i}}" style="display:none">
                                                            <input id="quantity_{{$i}}" class="form-control each_product_qty" placeholder="Qnty" name="product[{{$i}}][quantity]" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" value="{{ (isset($session_data['product'][$i]['quantity'])) ? $session_data['product'][$i]['quantity']:'50'}}">
                                                        </div>
                                                        <div class = "form-group kg_list_{{$i}}" >
                                                            <select class = "form-control kg_list" name = "kg_list" id = "kg_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($n = 50; $n <= 15000; $n++) { ?>
                                                                    <option value = "{{$n}}">{{$n}}</option>
                                                                    <?php
                                                                    $n = $n + 49;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class = "form-group pieces_list_{{$i}}" style="display:none">
                                                            <select class = "form-control pieces_list " name = "pieces_list" id = "pieces_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                    <option value = "{{$z}}">{{$z}}</option>
                                                                    <?php // ($z == 1) ? $z = $z + 3 : $z = $z + 4; 
                                                                }
                                                                ?>                                                 
                                                            </select>
                                                        </div>

                                                        <div class = "form-group ff_list_{{$i}}" style="display:none">
                                                            <select class = "form-control ff_list " name = "ff_list" id = "ff_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                <option value = "{{$z}}">{{$z}}</option>
                                                                <?php // ($z == 1) ? $z = $z + 3 : $z = $z + 4;
                                                                } ?>
                                                            </select>
                                                        </div>

                                                        <div class = "form-group mm_list_{{$i}}" style="display:none">
                                                            <select class = "form-control mm_list " name = "mm_list" id = "mm_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                <option value = "{{$z}}">{{$z}}</option>
                                                                <?php // ($z == 1) ? $z = $z + 3 : $z = $z + 4;
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="tel" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price" onkeypress=" return numbersOnly(this, event, true, true);"  value="<?php if (isset($session_data['product'][$i]['price'])) { ?>{{$session_data['product'][$i]['price']}}<?php } ?>">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group inquiry_vat_chkbox">
                                                            <!--<input type="text" class="form-control" id="vat_percentage_{{$i}}" name="product[{{$i}}][vat_percentage]" placeholder="Vat percentage">-->
                                                            <input class="vat_chkbox" type="checkbox" name="product[{{$i}}][vat_percentage]" value="yes" onchange="check_vat();">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <div class="form-group">
                                                            <input id="remark" class="form-control" placeholder="Remark" name="product[{{$i}}][remark]" type="text" value="{{(isset($session_data['product'][$i]['remark']))?$session_data['product'][$i]['remark']:''}}">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            Session::forget('input_data');
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
                                    <input checked="" value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                    <label for="optionsRadios3">All Inclusive</label>
                                    <input value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                    <label for="optionsRadios4">Plus VAT</label>
                                </div>
                            </div>
                            -->

                            <div class="form-group col-md-4 targetdate">
                                <label for="date">Expected Delivery Date: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_date" class="form-control" id="expected_delivery_date" value="{{Input::old('expected_date')!=''?Input::old('expected_date'):date('d/m/Y')}}">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                            </div>
                            
                            <div class="checkbox customer_select_order" style="{{(Input::old('customer_status') == "new_customer")?'display:none':'display:block'}}">
                                <!-- <label class="marginsms" style="margin-right:10px;"><input type="checkbox" id="send_whatsapp" name="send_whatsapp" value="yes"><span class="checksms">Send Whatsapp</span></label> -->
                                <label class="marginsms"><input type="checkbox" id="send_msg" name="send_msg" value="yes"><span class="checksms">Send SMS</span></label>
                            </div>
<!--                            <button type="button" class="btn btn-primary btn_add_inquiry_sms" id="add_inquiry_sendSMS">Save and Send SMS</button>
                            <hr>-->
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer btn_add_inquiry">Submit</button>
                                <!--<input type="submit" class="btn btn-primary form_button_footer btn_add_inquiry" value="Submit">-->
                                <!-- <a href="{{url('/')}}/inquiry" class="btn btn-default form_button_footer">Back</a> -->
                                <a href="<?php if(!in_array($ipaddress, $ip_array) && Auth::user()->role_id == 2){ echo "/dashboard";}else{ echo "/inquiry";}?>" class="btn btn-default form_button_footer">Back</a>
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
<style>
    .gr_ver_2{
        top: 20px !important;
    }
</style>
@stop