@extends('layouts.master')
@section('title','Edit Purchase Advise')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchaseorder_advise')}}">Purchase Advice</a></li>
                    <li class="active"><span>Edit Purchase Advice</span></li>
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
                        <form data-button="btn_edit_purchase_advice" id="onenter_prevent" method="POST" action="{{url('purchaseorder_advise/'.$purchase_advise->id)}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            <input name="_method" type="hidden" value="PUT">
                            @if (count($errors->all()) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif
                            @if (Session::has('flash_message_error'))
                            <div class="alert alert-danger">{{ Session::get('flash_message_error') }}</div>
                            @endif
                            <div class="form-group">
                                <label for="billdate"><b>Bill Date:</b> {{date("jS F, Y", strtotime($purchase_advise->purchase_advice_date))}}</label>
                            </div>
                            <div class="form-group">
                                <label for="cn"><b>Serial Number:</b> {{$purchase_advise->serial_number}}</label>
                            </div>
                            <div class="form-group">
                                <label for="cn"><b>Supplier Name:</b> {{$purchase_advise['supplier']->owner_name.'-'.$purchase_advise['supplier']->tally_name}}</label>
                            </div>                            
                            @if($purchase_advise['purchase_order'][0]->discount > 0)
                                <div class="form-group">
                                    <label><b>Discount/Premium :</b> </label>
                                    {{$purchase_advise['purchase_order'][0]->discount_type}}
                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$purchase_advise['purchase_order'][0]->discount_type}}" >
                                </div>
                                <div class="form-group">                                    
                                    <label><b>Fixed/Percentage :</b> </label>
                                    {{$purchase_advise['purchase_order'][0]->discount_unit}}
                                    <input type="hidden" id="discount_unit" name="discount_unit" value="{{$purchase_advise['purchase_order'][0]->discount_unit}}" >
                                </div>
                                <div class="form-group">                                    
                                    <label><b>Amount :</b> </label>
                                    {{$purchase_advise['purchase_order'][0]->discount}}
                                    <input type="hidden" id="discount_amount" name="discount" value="{{$purchase_advise['purchase_order'][0]->discount}}" >
                                </div>
                            @else
                                <div class="form-group">                                
                                    <label><b>Discount/Premium :</b> </label> 
                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$purchase_advise['purchase_order'][0]->discount_type}}" >
                                </div>
                                <div class="form-group">                                     
                                         <label><b>Fixed/Percentage :</b> </label>
                                         <input type="hidden" id="discount_unit" name="discount_unit" value="{{$purchase_advise['purchase_order'][0]->discount_unit}}" >
                                </div>
                                <div class="form-group">                                    
                                        <label><b>Amount :</b> </label>
                                        <input type="hidden" id="discount_amount" name="discount" value="{{$purchase_advise['purchase_order'][0]->discount}}" >
                                </div>
                            @endif
                            <div class="inquiry_table col-md-12" >
                                <div class="table-responsive">
                                    <table id="create_purchase_advise_table" class="table table-hover">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Length</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Actual Pieces</span></td>
                                                <td><span>Pending Order</span></td>
                                                <td><span>Present Shipping</span></td>
                                                <td><span>Price</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                            @foreach($purchase_advise['purchase_products'] as $key=>$product)
                                        <input type="hidden" value="{{$product->from}}" name="product[{{$key}}][purchase]">
                                        <tr id="add_row_{{$key}}" class="add_product_row">
                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    {{$product['purchase_product_details']->alias_name}}
                                                    <input class="form-control" type="hidden" name="product[{{$key}}][name]" value="{{$product['purchase_product_details']->alias_name}}">
                                                    <input type="hidden" name="product[{{$key}}][id]" value="{{$product->product_category_id}}">
                                                    <input type="hidden" name="product[{{$key}}][purchase_product_id]" value="{{$product->id}}">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group ">
                                                    {{$product['unit']->unit_name}}
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group ">
                                                    {{$product->length}}
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group ">
                                                    {{$product->quantity}}
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" value="{{$product->actual_pieces}}" name="product[{{$key}}][actual_pieces]" onkeypress=" return numbersOnly(this,event,true,false);" id="actual_pieces{{$key}}">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <?php $pending_quantity = $product->quantity - $product->present_shipping; ?>
                                                    <input type="tel" class="form-control" readonly="" value="{{ $pending_quantity}}" id="pending_order_{{$key}}" name="pending_order_{{$key}}"/>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" value="{{$product->present_shipping}}" id='present_shipping{{$key}}' onblur="calutate_pending_order(<?php echo $product->quantity . ',' . $key; ?>)"  name="product[{{$key}}][present_shipping]" onkeypress=" return numbersOnly(this,event,true,false);">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input type="tel" class="form-control" value="{{$product->price}}" id="product_price_{{$key}}" name="product[{{$key}}][price]" onkeypress=" return numbersOnly(this,event,true,false);">
                                                </div>
                                            </td>
                                            <td class="col-md-3">
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
                                                            <a class="table-link" title="add more" id="add_purchase_advice_product_row">
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
                            <table id="table-example" class="table table-hover  ">
                                <tbody>
                                    <tr class="cdtable">
                                        <td class="cdfirst">Delivery Location:</td>
                                        <td>
                                            @if($purchase_advise->delivery_location_id > 0)
                                            {{$purchase_advise['location']->area_name}}
                                            @else
                                            {{$purchase_advise->other_location}}
                                            @endif
                                        </td>
                                    </tr>
                                    <?php
                                    if ($purchase_advise->vat_percentage != '') {
                                        ?>
                                        <tr class="cdtable">
                                            <td class="cdfirst">GST Percentage:</td>
                                            <td>{{$purchase_advise->vat_percentage}}%</td>
                                        </tr>
                                    <?php } ?>
                                    <tr class="cdtable">
                                        <td class="cdfirst">Vehicle Number:<span class="mandatory">*</span></td>
                                        <td><input id="price" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{$purchase_advise->vehicle_number}}" type="text"></td>
                                    </tr>
                                    <tr class="">
                                        <td class="cdfirst">Expected Delivery Date:</td>
                                        <td> {{ date("jS F, Y", strtotime($purchase_advise->expected_delivery_date)) }} </td>
                                    </tr>
                                    <tr class="cdtable">
                                        <td class="cdfirst">Remark:</td>
                                        <td><input id="price" class="form-control cdbox" placeholder="Remark" name="remarks" value="{{$purchase_advise->remarks}}" type="text"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="checkbox">
                                <label style="margin-right:10px;"><input type="checkbox" name="send_whatsapp" value="yes"><span title="Whatsapp message would be sent to Party" class="checksms smstooltip">Send Whatsapp</span></label>
                                <label><input type="checkbox" name="send_msg" value="yes"><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                            </div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer btn_edit_purchase_advice">Submit</button>
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- {{-- @include('autocomplete_tally_product_name') --}} -->
@endsection