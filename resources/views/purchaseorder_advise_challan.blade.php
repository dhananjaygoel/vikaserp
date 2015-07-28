@extends('layouts.master')
@section('title','Purchase Order Advise Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="{{url('purchase_orders')}}">Purchase Order</a></li>
            <li class="active"><span>Create Purchase Challan</span></li>
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
                @if (count($errors) > 0)
                <div class="alert alert-warning">                        
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach                       
                </div>
                @endif 
                <form method="POST" action="{{URL::action('PurchaseChallanController@store')}}" accept-charset="UTF-8" id="onenter_prevent">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="purchase_advice_id" value="{{$purchase_advise->id}}"/>
                    <input type="hidden" name="purchase_order_id" value="{{$purchase_advise->purchase_order_id}}"/>
                    <input type="hidden" name="delivery_location_id" value="{{$purchase_advise->delivery_location_id}}"/>

                    <div class="form-group">
                        <label><b>Bill Date:</b> {{ date("jS F, Y", strtotime($purchase_advise->purchase_advice_date)) }}
                            <input type='hidden' class="form-control" name="bill_date" value="{{$purchase_advise->purchase_advice_date}}"/> 
                        </label>
                    </div>
                    <div class="form-group">
                        <label><b>Serial Number:</b> {{$purchase_advise->serial_number}}
                            <input type="hidden" class="form-control" name="serial_no" value="{{$purchase_advise->serial_number}}"/> 
                        </label>
                    </div>
                    <div class="form-group">
                        <label><b>Created By:</b> {{$purchase_advise['supplier']->owner_name }}
                            <input type="hidden" name="supplier_id" value="{{$purchase_advise['supplier']->id }}"/> 
                            <input type="hidden" name="created_by" value="{{$purchase_advise->created_by }}"/> 
                        </label>
                    </div>
                    <div class="table-responsive">
                        <table id="table-example" class="table table_deliverchallan serial purchaseorder_advide_table ">
                            <tbody>
                                <tr>
                                    <td class="col-md-2"><span>Product Name(Alias)<span class="mandatory">*</span> </span></td>
                                    <td class="col-md-2"><span>Actual Quantity</span></td>
                                    <td class="col-md-1"><span>Unit</span><span class="mandatory">*</span></td>
                                    <td class="col-md-2 text-center"><span>Present Shipping</span></td>
                                    <td class="col-md-2"><span>Rate</span></td>
                                    <td class="col-md-2"><span>Amount</span></td>
                                </tr>
                                <?php $total_price = 0; ?>
                                @foreach($purchase_advise['purchase_products'] as $key=>$products)
                                <tr id="add_row_{{$key}}" class="add_product_row">
                            <input type="hidden" name="product[{{$key}}][purchase_advice_id]" value="{{$purchase_advise->id}}"/>
                            <input type="hidden" name="product[{{$key}}][id]" value="{{$products->id}}"/>
                            <input type="hidden" name="product[{{$key}}][order_type]" value="purchase_advice"/>
                            <td>
                                <div class="form-group">
                                    {{$products['purchase_product_details']->alias_name}}
                                    <input type="hidden" name="product[{{$key}}][product_category_id]" id="add_product_id_{{$key}}" value="{{$products['purchase_product_details']->id}}">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <!--<input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="{{$products->present_shipping}}" type="text">-->
                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][quantity]" value="{{$products->present_shipping}}" type="text" onblur="purchase_challan_calculation();">
                                </div>
                            </td>
                            <td> 
                                <div class="form-group">
                                    {{$products['unit']->unit_name}}
                                    <input id="unit_id{{$key}}" name="product[{{$key}}][unit_id]" value="{{$products['unit']->id}}" type="hidden">
                                </div>
                            </td>
                            <td>  
                                <div class="form-group text-center">
                                    {{$products->present_shipping}}
                                    <input id="present_shipping_{{$key}}" name="product[{{$key}}][present_shipping]" value="{{$products->present_shipping}}" type="hidden">
                                </div>
                            </td>
                            <td class="shippingcolumn">
                                <div class="row ">
                                    <div class="form-group col-md-12">
                                        <!--<input type="text" class="form-control" id="difference" value="{{$products->price}}" placeholder="Rate">-->
                                        <input type="text" class="form-control" id="product_price_{{$key}}" value="{{$products->price}}" name="product[{{$key}}][price]" placeholder="Rate">
                                    </div>                                         
                                </div>
                            </td>
                            <td>   
                                <div class="form-group">                                    
                                    <div id="amount_{{$key}}">{{ $products->present_shipping * $products->price }}</div>
                                </div>
                            </td>
                            </tr>
                            @endforeach                         
                            </tbody>
                        </table>
                        <table >
                            <tr class="row5">
                                <td>
                                    <div class="add_button">
                                        <div class="form-group pull-left">
                                            <label for="addmore"></label>
                                            <a href="#" class="table-link" title="add more" id="add_more_product1" onclick="create_purchase_challan_function();">
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
                        </table>
                    </div>
                    <div class="form-group">
                        <label><b>Total Actual Quantity :</b> <div id="total_actual_quantity"></div></label>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_name"><b class="challan">Vehicle Number</b></label>
                        <input id="vehicle_number" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{$purchase_advise->vehicle_number}}" type="text">
                    </div>
                    <div class="form-group">
                        <label for="vehicle_name"><b class="challan">Discount</b></label>
                        <input id="discount" class="form-control" placeholder="Discount" name="discount" value="" type="text" onblur="purchase_challan_calculation();">
                    </div>
                    <div class="form-group">
                        <label for="driver_name"><b class="challan">Freight</b><span class="mandatory">*</span></label>
                        <input id="freight" class="form-control" placeholder="Freight " name="Freight" value="" type="text" onblur="purchase_challan_calculation();">
                    </div>
                    <div class="form-group">
                        <label for="total"><b class="challan">Total :</b> <div id="total_price"></div></label>
                    </div>

                    <div class="form-group">
                        <label for="loadedby"><b class="challan">Unloaded By</b><span class="mandatory">*</span></label>
                        <input id="loadedby" class="form-control" placeholder="Unloaded By" name="unloaded_by" value="" type="text">
                    </div>
                    <div class="form-group">
                        <label for="labour"><b class="challan">Labour </b><span class="mandatory">*</span></label>
                        <input id="labour" class="form-control" placeholder="Labour" name="labour" value="" type="text">
                    </div>
                    @if($purchase_advise->vat_percentage==0 || $purchase_advise->vat_percentage== '')
                    <div class="form-group">
                        <label for="Plusvat"><b class="challan">Plus VAT</b> : No </label>
                    </div>
                    @else
                    <div class="form-group">
                        <label for="driver_contact"><b class="challan">VAT Percentage</b> {{$purchase_advise->vat_percentage}}</label>
                        <input id="vat_percentage" type="hidden" value="{{$purchase_advise->vat_percentage}}" name="vat_percentage"/>

                    </div>
                    <div class="form-group">
                        <label for="driver_contact"><b class="challan">VAT Value :</b> <div id="vat_value"></div></label>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="vatp"><b class="challan">Total : </b>
                            <span class="gtotal">
                                <input type="text" class="form-control" name="vat_total" id="vat_tot_val" readonly="readonly">
                            </span>

                        </label>
                    </div>
                    <div class="form-group">
                        <label for="labour"><b class="challan">Round Off</b></label>
                        <input id="round_off" class="form-control" placeholder="Round Off" name="round_off" value="" type="text" onblur="purchase_challan_calculation();">
                    </div>

                    <div class="form-group">
                        <label for="total"><b class="challan">Grand Total :</b> <div id="grand_total"></div>
                        </label>
                        <input type="hidden" id="grand_total_val" name="grand_total">
                    </div>
                    @if($purchase_advise->vat_percentage>0)
                    <div class="form-group">
                        <label for="billno"><b class="challan">Bill Number</b></label>
                        <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="" type="text">
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="inquiry_remark"><span class="checksms">Remark</span></label>
                        <textarea class="form-control" id="inquiry_remark" name="remark"  rows="3">{{$purchase_advise->remarks}}</textarea>
                    </div>
                    <!--   <button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->
                    <hr>
                    <div>
                        <button type="submit" class="btn btn-primary" >Submit</button>
                        <a href="{{url('purchase_challan')}}" class="btn btn-default">Back</a>
                    </div>
                    <div class="clearfix"></div>                   
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@endsection


