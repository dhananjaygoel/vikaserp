@extends('layouts.master')
@section('title','Create Purchase Orders')
@section('content')
<?php

use Illuminate\Support\Facades\Session;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchase_orders')}}">Purchase Order</a></li>
                    <li class="active"><span>Add Purchase Order</span></li>
                </ol>
            </div>
        </div>
        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form data-button="sendSMSPurchaseOrder" id="onenter_prevent" method="POST" action="{{URL::action('PurchaseOrderController@store')}}">
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
                            <div class="form-group ">
                                <div class="radio superadmin">
                                    <input checked="" value="0" id="admin_and_superadmin" name="viewable_by" type="radio"
                                    <?php
                                    if (Input::old('viewable_by') == "0") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="admin_and_superadmin">Viewable to Admin and Superadmin</label>
                                    <input  value="1" id="viewable_by_all" name="viewable_by" type="radio"
                                    <?php
                                    if (Input::old('viewable_by') == "1") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="viewable_by_all">Viewable by all</label>
                                </div>
                                <div class="radio">
                                    <input checked="" value="existing_supplier" id="existing_supplier" name="supplier_status" type="radio"
                                    <?php
                                    if (Input::old('supplier_status') == "existing_supplier") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="existing_supplier">Existing Supplier</label>
                                    <input value="new_supplier" id="new_supplier" name="supplier_status" type="radio"
                                    <?php
                                    if (Input::old('supplier_status') == "new_supplier") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="new_supplier">New Supplier</label>
                                </div>                                   
                                <?php
                                if (Input::old('supplier_status') == "new_supplier") {
                                    $style = 'style="display: none"';
                                } else {
                                    $style = 'style="display: block"';
                                }
                                ?>
                                <div class="customer_select_order" style="{{(Input::old('supplier_status') == "new_supplier")?'display:none':'display:block'}}">
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                            <input class="form-control focus_on_enter tabindex1" placeholder="Enter Tally Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name" tabindex="1" >
                                            <input type="hidden" id="existing_customer_id" name="autocomplete_supplier_id">
                                            <input type="hidden" id="customer_default_location">
                                                <!--<i class="fa fa-sort-desc " id='existing_customer_id_focus'></i>-->
                                        </div>
                                    </div>
                                </div>
<!--                                <div class="supplier customer_select" <?= $style ?>>
                                    <div class="col-md-12">
                                        <div class="form-group searchproduct">
                                            <input class="form-control" placeholder="Enter Supplier Name " type="text" name="existing_supplier_name" id="existing_supplier_name">
                                            <input type="hidden" id="existing_supplier_id" name="autocomplete_supplier_id">
                                            <input type="hidden" id="customer_default_location">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                            <?php
                            if (Input::old('supplier_status') == "new_supplier") {
                                $style = 'style="display: block"';
                            } else {
                                $style = 'style="display: none"';
                            }
                            ?>
                            <div class="exist_field" <?= $style ?>>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label for="name"> Supplier Name<span class="mandatory">*</span></label>
                                    <input id="name" class="form-control" placeholder="Supplier Name" name="supplier_name" value="{{Input::old('supplier_name')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{Input::old('mobile_number')}}" type="tel" autocomplete="off" onkeypress=" return numbersOnly(this, event, false, false);" maxlength="10" pattern="[0-9]{10}">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{Input::old('credit_period')}}" type="tel" onkeypress=" return numbersOnly(this, event, false, false);">
                                </div>
                            </div>                             
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
<!--                                    <table id="add_product_table_purchase" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Price</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                    <?php
                                    $session_data = Session::get('input_data');
                                    if (isset($session_data['product'])) {
                                        $total_products_added = sizeof($session_data['product']);
                                    }
                                    if (isset($total_products_added) && ($total_products_added > 10)) {
                                        $j = $total_products_added;
                                    } else {
                                        $j = 10;
                                    }
                                    for ($i = 1; $i <= $j; $i++) {
                                        if ($i == 1)
                                            $z = $i + 1;
                                        else {
                                            $z = 0;
                                        }
                                        ?>
                                                    <tr id="add_row_{{$i}}" class="add_product_row">
                                                        <td class="col-md-3">
                                                            <div class="form-group searchproduct">
                                                                <input class="form-control each_product_detail focus_on_enter tabindex{{$i}}" data-productid="{{$i}}" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_purchase_product_name_{{$i}}" onfocus="product_autocomplete_purchase({{$i}});" value="<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" tabindex="{{$z}}">
                                                                <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="">
                                                                <i class="fa fa-search search-icon"></i>
                                                            </div>
                                                        </td>
                                                        <td class="col-md-1">
                                                            <div class="form-group">
                                                                <input id="quantity_{{$i}}"  class="form-control each_product_qty" placeholder="Qnty" name="product[{{$i}}][quantity]" type="tel" value="<?php if (isset($session_data['product'][$i]['quantity'])) { ?>{{$session_data['product'][$i]['quantity']}}<?php } ?>" onkeypress=" return numbersOnly(this,event,true,false);">
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
                                                                <input type="tel" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price" value="<?php if (isset($session_data['product'][$i]['price'])) { ?>{{$session_data['product'][$i]['price']}}<?php } ?>" onkeypress=" return numbersOnly(this,event,true,false);">
                                                            </div>
                                                        </td>
                                                        <td class="col-md-4">
                                                            <div class="form-group">
                                                                <input id="remark" class="form-control" placeholder="Remark" name="product[{{$i}}][remark]" type="text" value="<?php if (isset($session_data['product'][$i]['remark'])) { ?>{{$session_data['product'][$i]['remark']}}<?php } ?>">
                                                            </div>
                                                        </td>
                                                    </tr>
                                    <?php } ?>

                                        </tbody>
                                    </table>-->

                                    <div class="form-group ">
                                        <div class="col-md-4">
                                            <label for="location">Discount/Premium:</label>
                                            @if(Auth::user()->role_id <> 5)
                                            <select class="form-control focus_on_enter tabindex2" name="discount_type" id="discount_type" tabindex="2" >
                                                <option value="Discount" selected="">Discount</option>
                                                <option value="Premium">Premium</option>
                                            </select>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label for="location">Fixed/Percentage:</label>
                                            @if(Auth::user()->role_id <> 5)
                                            <select class="form-control focus_on_enter tabindex2" name="discount_unit" id="discount_unit" tabindex="2" >
                                                <option value="Fixed" selected="">Fixed</option>
                                                <option value="Percent">Percent</option>
                                            </select>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label for="discount">Amount</label>
                                              @if(Auth::user()->role_id <> 5)
                                                <input id="discount_amount" class="form-control focus_on_enter tabindex3" placeholder="Amount " name="discount" value="" type="tel" onkeypress=" return numbersOnly(this, event, true, true);" tabindex="3" >
                                              @endif
                                        </div>
                                    </div>
                                    <table id="add_product_table" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Length</span></td>
                                                <td><span>Quantity</span></td>

                                                <td><span>Price</span></td>
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
                                                             <input class="form-control each_product_detail focus_on_enter tabindex{{$i}}" data-productid="{{$i}}" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_purchase_product_name_{{$i}}" onfocus="product_autocomplete_purchase({{$i}});" value="<?php if (isset($session_data['product'][$i]['name'])) { ?>{{$session_data['product'][$i]['name']}}<?php } ?>" tabindex="{{$z}}">
                                                             <input type = "hidden" name = "product[{{$i}}][id]" id = "add_product_id_{{$i}}" value = "<?php if (isset($session_data['product'][$i]['id'])) { ?>{{$session_data['product'][$i]['id']}}<?php } ?>">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group ">
                                                            <select class="form-control unit" name="product[{{$i}}][units]" id="units_{{$i}}" onchange="unitType(this);">
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
                                                        <div class="form-group meter_list_{{$i}}" style="display:none">
                                                            <input id="quantity_{{$i}}"  class="form-control each_product_qty" placeholder="Qnty" name="product[{{$i}}][quantity]" type="tel" value="<?php if (isset($session_data['product'][$i]['quantity'])) { ?>{{$session_data['product'][$i]['quantity']}}<?php } ?>" onkeypress=" return numbersOnly(this, event, true, false);">
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
        <?php // ($z == 1) ? $z = $z + 3 : $z = $z + 4; 
        } ?>                                                 
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
                                                         <?php } ?>
                                                    </td>

                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="tel" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price" value="<?php if (isset($session_data['product'][$i]['price'])) { ?>{{$session_data['product'][$i]['price']}}<?php } ?>" onkeypress=" return numbersOnly(this, event, true, false);">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-4">
                                                        <div class="form-group">
                                                            <input id="remark" class="form-control" placeholder="Remark" name="product[{{$i}}][remark]" type="text" value="<?php if (isset($session_data['product'][$i]['remark'])) { ?>{{$session_data['product'][$i]['remark']}}<?php } ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
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
                                                            <a class="table-link" title="add more" id="add_purchase_product_row">
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
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                    <select class="form-control" name="purchase_order_location" id="purchase_other_location">
                                        <option value="0" selected="" >--Delivery Location--</option>
                                        @foreach($delivery_locations as $delivery_location)
                                        @if($delivery_location->status == 'permanent')
                                        <option value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
                                        @endif
                                        @endforeach
                                        <option value="-1">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="locationtext" id="other_location_input_wrapper">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{Input::old('other_location_name')}}" type="text">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="location">Other Location Difference</label>
                                        <input id="location_difference" class="form-control" placeholder="Location " name="other_location_difference" value="{{Input::old('other_location_difference')}}" type="tel">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label for="orderfor">Order For:</label>
                                    <select class="form-control" id="orderfor" name="order_for">
                                        <option value="0">Warehouse</option>
                                        @foreach($customers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->tally_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="include_vat" id="inclusive_of_vat" name="vat_status" type="radio">
                                    <label for="inclusive_of_vat">All Inclusive</label>
                                    <input value="exclude_vat" id="exclusive_of_vat" name="vat_status" type="radio">
                                    <label for="exclusive_of_vat">Plus GST</label>
                                </div>
                            </div>
                            <div class="plusvat " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">GST Percentage:</td>
                                                <td><input id="price" class="form-control" placeholder="GST Percentage" name="vat_percentage" value="{{Input::old('vat_percentage')}}" type="text" onkeypress=" return onlyPercentage(event);"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row col-md-12 tcs-chkbox" style="display: none">
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label class="marginsms"><input type="checkbox" id="tcs_applicable" name="tcs_applicable" value="yes"><span class="checksms tcs-class">TCS Applicable</span></label>
                                    </div>
                                    <div id="tcs_percentage" style="display:none;">
                                        <table id="table-example" class="table ">
                                            <tbody>
                                                <tr class="cdtable">
                                                    <td class="cdfirst">TCS Percentage:</td>
                                                    <td><input id="tcs_percentage" class="form-control" placeholder="GST Percentage" name="tcs_percentage" value="0.1" type="text" onkeypress=" return numbersOnly(this, event, true, false);"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php Session::put('input_data', ''); ?>
                            <div class="form-group col-md-4 targetdate">
                                <label for="time">Expected Delivery Date:<span class="mandatory">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_delivery_date" class="form-control" id="datepickerDate" value="{{Input::old('expected_delivery_date')!=''?Input::old('expected_delivery_date'):date('d/m/Y')}}" >
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark:</label>
                                <textarea class="form-control" id="inquiry_remark" name="purchase_order_remark" rows="3"></textarea>
                            </div>
                            <!-- <div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="" name="send_email"><span class="checksms">Send Email to Party</span></label>
                                </div>
                            </div> -->
                            <div class="checkbox">
                                <!-- <label style="margin-right:10px;"><input type="checkbox" name="send_whatsapp" value="yes"><span title="Whatsapp message would be sent to Party" class="checksms smstooltip">Send Whatsapp</span></label> -->
                                <label><input type="checkbox" name="send_msg" value="yes"><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                            </div>
                            <hr>
                            <!--<div>-->
                                <!--<button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" id="sendSMSPurchaseOrder" >Save and Send SMS</button>-->
                                
                            <!--</div>-->
                            <!--<hr>-->
                            <div>
                                <input type="hidden" name="total_products" id="total_products" value="{{isset($existig_product)?$existig_product:10}}">
                                <button type="submit" class="btn btn-primary form_button_footer btn_add_purchase_order">Submit</button>
                                <a href="{{URL::to('purchase_orders')}}" class="btn btn-default form_button_footer">Back</a>
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
<!-- {{--  @include('autocomplete_tally_product_name')  --}} -->
@stop
