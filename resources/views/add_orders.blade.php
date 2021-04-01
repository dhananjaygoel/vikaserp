@extends('layouts.master')
@section('title','Add Orders')
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
                    <li style="<?php if(!in_array($ipaddress, $ip_array) && Auth::user()->role_id == 2){ echo 'display:none;'; }else {echo ""; }?>"><a href="{{url('orders')}}"  >Orders</a></li>
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

                                <div class="radio">
                                    <input checked="" value="warehouse" id="warehouse_radio" name="status" type="radio">
                                    @if(Auth::user()->role_id <> 5)
                                    <label for="warehouse_radio">Warehouse</label>
                                    @endif
                                    <input  value="supplier" id="supplier_radio" name="status" type="radio">
                                    @if(Auth::user()->role_id <> 5)
                                    <label for="supplier_radio">Supplier</label>
                                    @endif
                                </div>

                                <div class="supplier_order" style="display:none">
                                    <select class="form-control" name="supplier_id" id="add_status_type">
                                        <option value="" selected="">Select supplier</option>
                                        @if(count((array)$customers)>0)
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

                                     @if(Auth::user()->role_id <> 5)
                                    <label for="new_customer">New</label>
                                    @endif
                                </div>

                                <style>
.searchproduct .fa-sort-desc{position: absolute;right:6px;top:7px;cursor:pointer;}
                                </style>
                                <div class="customer_select_order" style="{{(Input::old('customer_status') == "new_customer")?'display:none':'display:block'}}">
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                         @if(Auth::user()->role_id <> 5)
                                            <input class="form-control focus_on_enter tabindex1" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name" tabindex="1" >
                                            <input type="hidden" id="existing_customer_id" name="existing_customer_name">
                                            <input type="hidden" id="customer_default_location">
                                                <!--<i class="fa fa-sort-desc " id='existing_customer_id_focus'></i>-->
                                         @endif


                                         @if(Auth::user()->role_id == 5)
                                            <input class="form-control focus_on_enter" placeholder="Enter Tally Name " type="text" value="{{$order->tally_name}}" id="existing_customer_name1" tabindex="1" disabled="yes">
                                              <input type="hidden" id="existing_customer_id" name="existing_customer_name" value="{{$order->id}}">
                                            <input type="hidden" id="customer_default_location" value="{{$order->delivery_location_id}}">
                                         @endif

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
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" name="mobile_number" value="{{old('mobile_number')}}" type="tel" pattern="[0-9]{10}">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" onkeypress=" return numbersOnly(this, event, false, false);" value="{{old('credit_period')}}" type="tel">
                                </div>
                            </div>

                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                         @if(Auth::user()->role_id <> 5)
                                        <select class="form-control focus_on_enter tabindex2" name="add_order_location" id="add_order_location" tabindex="2" >
                                            <option value="" selected="">Delivery Location</option>
                                            @foreach($delivery_locations as $delivery_location)
                                            @if($delivery_location->status=='permanent' && $delivery_location->id!=0)
                                            <option value="{{$delivery_location->id}}" data-location-difference="{{$delivery_location->difference}}">{{$delivery_location->area_name}}</option>
                                            @endif
                                            @endforeach
                                            <option id="other_location" value="other">Other</option>
                                        </select>
                                         @endif

                                        @if(Auth::user()->role_id == 5)
                                           <select class="form-control focus_on_enter" name="add_order_location" id="add_order_location" tabindex="2" >
                                        <option value="0">Delivery Location</option>
                                        @foreach($delivery_locations as $location)
                                        @if($location->status=='permanent' && $location->id!=0)
                                        @if($order->delivery_location_id == $location->id)
                                        <option value="{{$location->id}}" selected="" data-location-difference="{{$location->difference}}">{{$location->area_name}}</option>
                                        @else
                                        <option value="{{$location->id}}" data-location-difference="{{$location->difference}}">{{$location->area_name}}</option>
                                        @endif
                                        @endif
                                        @endforeach
                                        @if($order->delivery_location_id == 0)
                                        <option id="other_location" value="other" selected="">Other</option>
                                        @else
                                        <option id="other_location" value="other">Other</option>
                                        @endif
                                    </select>
                                        @endif

                                    </div>
                                    <div class="col-md-2">
                                        <label for="location">Freight</label>
                                          @if(Auth::user()->role_id <> 5)
                                        <input id="location_difference" class="form-control focus_on_enter tabindex3" placeholder="Freight " name="location_difference" value="" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" tabindex="3" >

                                        @endif

                                        @if(Auth::user()->role_id == 5)
                                        <input id="location_difference" class="form-control focus_on_enter tabindex3" placeholder="Freight " name="location_difference" value="{{$order->delivery_location['difference']}}" type="tel" tabindex="3" onkeypress=" return numbersOnly(this, event, true, true);" >
                                        @endif

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
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Length</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Price</span><span class="mandatory">*</span></td>
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
                                                        <div class = "form-group searchproduct">
                                                            <input class = "form-control focus_on_enter each_product_detail tabindex4" placeholder = "Enter Product name" data-productid="{{$i}}" type = "text" name = "product[{{$i}}][name]" id = "add_product_name_{{$i}}" onfocus = "product_autocomplete({{$i}});" value = "<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" tabindex="4" >
                                                            <input type = "hidden" name = "product[{{$i}}][id]" id = "add_product_id_{{$i}}" value = "<?php if (isset($session_data['product'][$i]['id'])) { ?>{{$session_data['product'][$i]['id']}}<?php } ?>">
                                                            <!--<i class = "fa fa-search search-icon"></i>-->
                                                        </div>
                                                    </td>

                                                    <td class="col-md-2" id='test1'>
                                                        <div class = "form-group ">
                                                            <select class = "form-control unit" onchange="unitType(this);" name = "product[{{$i}}][units]" id = "units_{{$i}}">
                                                                    <option value='' id = 'unit_{{$i}}_0' <?php if (!isset($session_data['product'][$i]['units'])) { ?>selected="selected"<?php } ?>>--Select--</option>
                                                                     <?php if (isset($session_data['product'][$i]['units']) and (($session_data['product'][$i]['units']==1) or ($session_data['product'][$i]['units']==2) or ($session_data['product'][$i]['units']==3))) { ?>
                                                                    <option value=1 id = 'unit_{{$i}}_1' <?php if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==1)) { ?> selected="selected"<?php } ?> >KG</option>
                                                                    <option value=2 id = 'unit_{{$i}}_2' <?php if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==2)) { ?> selected="selected"<?php } ?> >Pieces</option>
                                                                    <option value=3 id = 'unit_{{$i}}_3' <?php if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==3)) { ?> selected="selected"<?php } ?> >Meter</option>
                                                                     <?php } elseif (isset($session_data['product'][$i]['units']) and (($session_data['product'][$i]['units']==4) or ($session_data['product'][$i]['units']==5))) { ?>
                                                                    <option value=4 id = 'unit_{{$i}}_4' <?php if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==4)) { ?> selected="selected"<?php } ?>>ft</option>
                                                                    <option value=5 id = 'unit_{{$i}}_5' <?php if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==5)) { ?> selected="selected"<?php } ?>>mm</option>
                                                                     <?php }else{ ?>
                                                                    <option value=1 id = 'unit_{{$i}}_1'>KG</option>
                                                                    <option value=2 id = 'unit_{{$i}}_2'>Pieces</option>
                                                                    <option value=3 id = 'unit_{{$i}}_3'>Meter</option>
                                                                    <option value=4 id = 'unit_{{$i}}_4'>ft</option>
                                                                    <option value=5 id = 'unit_{{$i}}_5'>mm</option>
                                                                     <?php } ?>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="col-md-1">
                                                        <div class = "form-group">
                                                            <div class = "form-group length_list_{{$i}}">
                                                            <input id = "length_{{$i}}" class = "form-control each_length_qnty" data-productid="{{$i}}"  name = "product[{{$i}}][length]" type = "tel" onkeypress=" return numbersOnly(this, event, true, true);" value = "{{ (isset($session_data['product'][$i]['length'])) ? $session_data['product'][$i]['length']:''}}" <?php if (isset($session_data['product'][$i]['length']) and ($session_data['product'][$i]['length'] > 0)) { ?> enable <?php }else{ ?>disabled <?php } ?>>
                                                        </div>
                                                        </div>
                                                    </td>

                                                    <td class="col-md-1">
                                                         <?php if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']!=3)) { ?>
                                                         <div class = "form-group meter_list_{{$i}}" style="display:none">
                                                            <input id = "quantity_{{$i}}" class = "form-control each_product_qty" data-productid="{{$i}}" placeholder = "Qnty" name = "product[{{$i}}][quantity]" type = "tel" onkeypress=" return numbersOnly(this, event, true, true);" value = "{{ (isset($session_data['product'][$i]['quantity'])) ? $session_data['product'][$i]['quantity']:'50'}}">
                                                        </div>
                                                         <?php } if (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==1)) { ?>
                                                        <div class = "form-group kg_list_{{$i}}" >
                                                            <select class = "form-control kg_list" name = "kg_list" id = "kg_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($n = 50; $n <= 15000; $n++) { ?>
                                                                    <option value = "{{$n}}" <?php if($session_data['product'][$i]['quantity']== $n)  echo "selected='selected'"; ?>>{{$n}}</option>
                                                                    <?php $n = $n + 49;
                                                                 } ?>                                                                ?>
                                                            </select>
                                                        </div>
                                                        <?php }elseif (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==2)) { ?>
                                                                <div class = "form-group pieces_list_{{$i}}" >
                                                            <select class = "form-control pieces_list " name = "pieces_list" id = "pieces_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                    <option value = "{{$z}}" <?php if($session_data['product'][$i]['quantity']== $z)  echo "selected='selected'"; ?>>{{$z}}</option>
                                                                    <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php }elseif (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==3)) { ?>
                                                                 <div class = "form-group meter_list_{{$i}}" >
                                                            <input id = "quantity_{{$i}}" class = "form-control each_product_qty" data-productid="{{$i}}" placeholder = "Qnty" name = "product[{{$i}}][quantity]" type = "tel" onkeypress=" return numbersOnly(this, event, true, true);" value = "{{ (isset($session_data['product'][$i]['quantity'])) ? $session_data['product'][$i]['quantity']:'50'}}">
                                                        </div>
                                                        <?php }elseif (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==4)) { ?>
                                                                 <div class = "form-group ff_list_{{$i}}">
                                                            <select class = "form-control ff_list " name = "ff_list" id = "ff_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                <option value = "{{$z}}" <?php if($session_data['product'][$i]['quantity']== $z)  echo "selected='selected'"; ?> >{{$z}}</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php }elseif (isset($session_data['product'][$i]['units']) and ($session_data['product'][$i]['units']==5)) { ?>
                                                                 <div class = "form-group mm_list_{{$i}}">
                                                            <select class = "form-control mm_list " name = "mm_list" id = "mm_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                <option value = "{{$z}}" <?php if($session_data['product'][$i]['quantity']== $z) echo "selected='selected'"; ?>>{{$z}}</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                         <?php   }else{ ?>
                                                        <div class = "form-group meter_list_{{$i}}" style="display:none">
                                                            <input id = "quantity_{{$i}}" class = "form-control each_product_qty" data-productid="{{$i}}" placeholder = "Qnty" name = "product[{{$i}}][quantity]" type = "tel" onkeypress=" return numbersOnly(this, event, true, true);" value = "{{ (isset($session_data['product'][$i]['quantity'])) ? $session_data['product'][$i]['quantity']:'50'}}">
                                                        </div>
                                                         <div class = "form-group kg_list_{{$i}}" >
                                                            <select class = "form-control kg_list" name = "kg_list" id = "kg_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($n = 50; $n <= 15000; $n++) { ?>
                                                                    <option value = "{{$n}}">{{$n}}</option>
                                                                    <?php $n = $n + 49;
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class = "form-group pieces_list_{{$i}}" style="display:none">
                                                            <select class = "form-control pieces_list " name = "pieces_list" id = "pieces_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                    <option value = "{{$z}}">{{$z}}</option>
                                                                    <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class = "form-group ff_list_{{$i}}" style="display:none">
                                                            <select class = "form-control ff_list " name = "ff_list" id = "ff_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                <option value = "{{$z}}">{{$z}}</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class = "form-group mm_list_{{$i}}" style="display:none">
                                                            <select class = "form-control mm_list " name = "mm_list" id = "mm_list_{{$i}}" onchange="setQty(this);">
                                                                <?php for ($z = 1; $z <= 1000; $z++) { ?>
                                                                <option value = "{{$z}}">{{$z}}</option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                         <?php } ?>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class = "form-group">
                                                            <input type = "tel" class = "form-control" id = "product_price_{{$i}}" name = "product[{{$i}}][price]" placeholder = "Price" onkeypress=" return numbersOnly(this,event,true,true);" value = "{{(isset($session_data['product'][$i]['price'])) ?$session_data['product'][$i]['price'] : ''}}">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group inquiry_vat_chkbox">
                                                            <!--<input type="text" class="form-control" id="vat_percentage_{{$i}}" name="product[{{$i}}][vat_percentage]" placeholder="Vat percentage" value = "{{(isset($session_data['product'][$i]['vat_percentage'])) ?$session_data['product'][$i]['vat_percentage'] : ''}}">-->
                                                            <input class="vat_chkbox" type="checkbox" name="product[{{$i}}][vat_percentage]" value="yes" onchange="check_vat();">
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
                            <div class="checkbox">
                                <label class="marginsms"><input type="checkbox" id="tcs_applicable" name="tcs_applicable" value="yes"><span class="checksms tcs-class">TCS Applicable</span></label>
                            </div>
                            <div class="tcs-applicable" id="tcs_percentage" style="display:none;">
                                <label for="tcs_percentage">TCS Percentage:</label>
                                <input type="text" name="tcs_percentage" value="0.1" class="form-control" id="tcs_percentage">
                            </div>
                            <div class="form-group col-md-4 targetdate">
                                <label for="time">Expected Delivery Date:<span class="mandatory">*</span> </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_date" value="{{Input::old('expected_date')!=''?Input::old('expected_date'):date('d/m/Y')}}" class="form-control" id="expected_delivery_date_order">
                                </div>
                            </div>

                            <div class="clearfix"></div>

                            <div class="form-group">
                                <label for="order_remark">Remark</label>
                                <textarea class="form-control" id="order_remark" name="order_remark" rows="3">{{ (isset($session_data['order_remark'])) ? $session_data['order_remark']:''}}</textarea>
                            </div>
                            <!-- <div class="checkbox customer_select_order">
                                <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email</span></label>
                            </div> -->
                            <div class="checkbox customer_select_order">
                                <!-- <label class="marginsms" style="margin-right:10px;"><input type="checkbox" id="send_whatsapp" name="send_whatsapp" value="yes"><span class="checksms">Send Whatsapp</span></label> -->
                                <label class="marginsms"><input type="checkbox" id="send_msg" name="send_msg" value="yes"><span class="checksms">Send SMS</span></label>
                            </div>
                            <div>
                                <!-- <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip btn_add_order_sms" id="add_order_sendSMS" >Save and Send SMS</button>-->
                            </div>
                            <hr>
                            <div>
                                <input type="hidden" name="total_products" id="total_products" value="{{isset($existig_product)?$existig_product:10}}">
                                <button type="submit" class="btn btn-primary form_button_footer btn_add_order">Submit</button>
                                <!-- <a href="{{url('orders')}}" class="btn btn-default form_button_footer">Back</a> -->
                                <a href="<?php if(!in_array($ipaddress, $ip_array) && Auth::user()->role_id == 2){ echo "/dashboard";}else{ echo "/orders";}?>" class="btn btn-default form_button_footer">Back</a>
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

@stop
