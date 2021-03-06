@extends('layouts.master')
@section('title','Purchase Advice')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchase_orders')}}">Purchase Order</a></li>
                    <li class="active"><span>Create Purchase Advice </span></li>
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
                        <!-- {!! Form::open(array('method'=>'post','url'=>url('create_purchase_advice',$purchase_orders->id)))!!} -->
                        <form data-button="" id="onenter_prevent" method="POST" action="{{URL::action('PurchaseAdviseController@store_advise')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">                                                        
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            <input type="hidden" name="id" value="{{$purchase_orders->id}}">
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
                            @if (Session::has('flash_message_error'))
                            <div class="alert alert-danger">{{ Session::get('flash_message_error') }}</div>
                            @endif
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">Bill Date: <span class="mandatory">*</span></td>
                                                <td>
                                                    <div class="targetdate">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                            <input type="text" name="bill_date" class="form-control" id="datepickerDate" value="{{Input::old('bill_date')!=''?Input::old('bill_date'):date('d/m/Y')}}">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="cdtable">
                                                <td><b>Supplier Name:</b></td>
                                                <td>
                                                    @if($purchase_orders['customer']->owner_name != "" && $purchase_orders['customer']->tally_name != "")
                                                    {{$purchase_orders['customer']->owner_name}}{{'-'.$purchase_orders['customer']->tally_name}}
                                                    @else
                                                    {{$purchase_orders['customer']->owner_name}}
                                                    @endif

                                                    <input type="hidden" name="supplier_id" value="{{$purchase_orders['customer']->id}}">
                                                </td>
                                            </tr>
                                            @if($purchase_orders->discount > 0)
                                                <tr>
                                                    <td>
                                                        <span><b>Discount/Premium :</b> </span>                                                                                                
                                                    </td>
                                                    <td>{{$purchase_orders->discount_type}}</td>
                                                    <input type="hidden" id="discount_type" name="discount_type" value="{{$purchase_orders->discount_type}}" >
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span><b>Fixed/Percentage :</b> </span>                                                    
                                                    </td>
                                                    <td>{{$purchase_orders->discount_unit}}</td>
                                                    <input type="hidden" id="discount_unit" name="discount_unit" value="{{$purchase_orders->discount_unit}}" >
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span><b>Amount :</b> </span>                                                    
                                                    </td>
                                                    <td>{{$purchase_orders->discount}}</td>
                                                    <input type="hidden" id="discount_amount" name="discount" value="{{$purchase_orders->discount}}" >
                                                </tr>
                                            @else
                                                <tr>
                                                    <td>
                                                        <span><b>Discount/Premium :</b> </span>
                                                        <input type="hidden" id="discount_type" name="discount_type" value="{{$purchase_orders->discount_type}}" >
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span><b>Fixed/Percentage :</b> </span>
                                                        <input type="hidden" id="discount_unit" name="discount_unit" value="{{$purchase_orders->discount_unit}}" >
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span><b>Amount :</b> </span>
                                                        <input type="hidden" id="discount_amount" name="discount" value="{{$purchase_orders->discount}}" >
                                                    </td>
                                                    <td></td>
                                                </tr>                                    
                                            @endif
                                        </tbody>
                                    </table>
                                    <table id="create_purchase_advise_table" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td class="col-md-3"><span> Product Name(Alias)</span></td>
                                                <td class="col-md-1"><span>Unit</span></td>
                                                <td class="col-md-1"><span>Length</span></td>
                                                <td class="col-md-1"><span>Quantity</span></td>
                                                <td class="col-md-1"><span>Actual Pieces</span></td>
                                                <td class="col-md-1"><span>Pending Order</span</td>
                                                <td class="col-md-2"><span>Present Shipping</span></td>
                                                <td class="col-md-1">Price</td>
                                                <td class="col-md-3"><span>Remark</span></td>
                                            </tr>
                                            <?php $key = 0;?>
                                            @foreach($purchase_orders['purchase_products'] as $product_data)
                                            @if($product_data->order_type == 'purchase_order' && $product_data->pending_quantity > 0)
                                            <tr id="add_row_{{++$key}}" class="add_product_row">
                                                <td class="test">
                                                    {{$product_data['purchase_product_details']->alias_name}}
                                                    <input type="hidden" name="product[{{$key}}][id]" value="{{$product_data['purchase_product_details']->id}}">
                                                    <input type="hidden" name="product[{{$key}}][key]" value="{{$product_data->id}}">
                                                </td>

                                                <td class="col-md-1">
                                                    {{isset($product_data['unit']->unit_name)?@$product_data['unit']->unit_name:''}}
                                                    <input type="hidden" name="product[{{$key}}][units]" value="{{@$product_data['unit']->id}}">
                                                </td>
                                                <td class="col-md-1">
                                                <div class="form-group">
                                                    {{$product_data->length?$product_data->length:0}}
                                                    <input id="length_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][length]" value="{{$product_data->length?$product_data->length:'0'}}" type="hidden" >
                                                </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group ">
                                                        {{$product_data->quantity}}
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <input type="tel" class="form-control" name="product[{{$key}}][actual_pieces]" value="" placeholder="actual pieces" onkeypress=" return numbersOnly(this,event,true,false);" id="actual_pieces{{$key}}">

                                                </td>

                                                <td>
                                                    <input class="form-control" type="tel" name="pending_order" id="pending_order_{{$key}}" readonly="" value="{{$product_data->pending_quantity}}"/>
                                                    <input class="form-control" type="hidden" name="pending_order_org" id="pending_order_org{{$key}}" value="{{$product_data->pending_quantity}}"/>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        @if($product_data->present_shipping != 0)
                                                        <input id="{{"present_shipping".$key}}" class="form-control" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" onblur="calutate_pending_order(<?php echo $product_data->pending_quantity . ',' . $key; ?>)" value="{{$product_data->present_shipping}}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                                                        @else
                                                        <input id="{{"present_shipping".$key}}" class="form-control" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" onblur="calutate_pending_order(<?php echo $product_data->pending_quantity . ',' . $key; ?>);" value="{{$product_data->present_shipping}}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="col-md-1">₹ {{$product_data->price}}</td>
                                                <td class="col-md-2">
                                                    {{$product_data->remarks}}

                                                    <input type="hidden" name="product[{{$key}}][remark]" value="{{$product_data->remarks}}">
                                                    <input type="hidden" name="product[{{$key}}][price]" value="{{$product_data->price}}">
                                                    <input type="hidden" name="product[{{$key}}][quantity]" value="{{$product_data->quantity}}">
                                                    <input type="hidden" class="form-control" name="product[{{$key}}][purchase]" value="purchase_order">
                                                </td>
                                            </tr>
                                            @endif
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
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table id="table-example" class="table table-hover customerview_table  ">
                                        <tbody>
                                            @if($purchase_orders['vat_percentage'] == 0)
                                            <tr class="cdtable"><td class="cdfirst"><span>Plus GST: </span>No</td><td></td></tr>
                                        <input type="hidden" name="vat_percentage" value="">
                                        @elseif($purchase_orders['vat_percentage'] != 0)
                                        <tr class="cdtable"><td class="cdfirst"><span>Plus GST: </span>Yes</td><td></td></tr>
                                        <tr class="cdtable"><td class="cdfirst" colspan="5"><span>GST Percentage: </span>{{$purchase_orders['vat_percentage']."%"}}</td></tr>
                                        <input type="hidden" name="vat_percentage" value="{{$purchase_orders['vat_percentage']}}">
                                        @if ($purchase_orders['tcs_applicable'] == 1)
                                        <tr class="cdtable"><td class="cdfirst"><span>TCS Applicable: </span>Yes</td><td></td></tr>
                                        <input type="hidden" name="tcs_applicable" value="yes">
                                        <tr class="cdtable">
                                            <td class="cdfirst"><span>TCS Percentage: </span></td>
                                            <td><input type="text" name="tcs_percentage" value="{{$purchase_orders->tcs_percentage}}" class="form-control" id="tcs_percentage"></td>
                                        </tr>
                                        @endif
                                        @endif
                                        
                                        <tr class="cdtable">
                                            <td class="cdfirst">Vehicle Number <span class="mandatory">*</span></td>
                                            <td><input id="vehicle_number" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="" type="text"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table id="table-example" class="table table-hover  ">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span><b>Delivery Location: </b></span>

                                                    @if($purchase_orders->delivery_location_id !=0)
                                                    {{$purchase_orders['delivery_location']->area_name}}
                                                    <input type="hidden" name="delivery_location_id" value="{{$purchase_orders['delivery_location_id']}}">
                                                    @else
                                                    {{$purchase_orders->other_location}}
                                                    @endif
                                                    <input type="hidden" name="delivery_location_id" value="{{$purchase_orders['delivery_location_id']}}">
                                                    <input type="hidden" name="other_location" value="{{$purchase_orders['other_location']}}">
                                                    <input type="hidden" name="other_location_difference" value="{{$purchase_orders['other_location_difference']}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span><b>Expected Delivery Date:</b> </span>
                                                    {{date("j F, Y", strtotime($purchase_orders['expected_delivery_date']))}}
                                                    <input type="hidden" name="expected_delivery_date" value="{{$purchase_orders['expected_delivery_date']}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span><b>Remark:</b></span>
                                                    {{$purchase_orders['remarks']}}
                                                    <input type="hidden" name="grand_remark" value="{{$purchase_orders['remarks']}}">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                </div>
                            </div>
                            <!--<button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button>-->
                            <hr>
                            <div>
                                <button  type="button" class="btn btn-primary btn_purorder_to_puradvice" id="" >Submit</button>
                                <!--<input type="submit" class="btn btn-primary btn_purorder_to_puradvice" value="Submit">-->
                                <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                            </div>
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