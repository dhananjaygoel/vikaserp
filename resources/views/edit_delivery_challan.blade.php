<?php
//echo '<pre>';
//print_r($allorder->toArray());
//echo '</pre>';
//exit;
?>
@extends('layouts.master')
@section('title','Edit Delivery Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_challan')}}">Delivery Challan</a></li>
                    <li class="active"><span>Edit Delivery Challan</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Edit Delivery Challan</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box"> 
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{url('delivery_challan/'.$allorder->id)}}" accept-charset="UTF-8" >


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
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input name="_method" type="hidden" value="PUT">


                            <div class="form-group">
                                <label><b>Party Name:</b> {{$allorder['customer']->owner_name}}</label>

                            </div>
                            <hr>
                            <div class="form-group">
                                <label><b>Serial Number:</b> {{$allorder['delivery_order']->serial_no}}</label>

                            </div>
                            <hr>
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_challan" class="table table-hover table_deliverchallan">
                                    <tbody>
                                        <tr>
                                            <td class="col-md-2"><span>Product Name(Alias)</span></td>
                                            <td class="col-md-1"><span>Actual Quantity</span></td>

                                            <td class="col-md-1"><span>Actual Pieces</span></td>

                                            <td class="col-md-1"><span>Unit</span></td>
                                            <td class="col-md-2"><span>Present Shipping</span></td>
                                            <td class="col-md-2"><span>Rate</span></td>
                                            <td class="col-md-3"><span>Amount</span></td>

                                        </tr>
                                        @foreach($allorder['all_order_products'] as $key=>$product)
                                        <tr id="add_row_{{$key}}" class="add_product_row">

                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" value="{{$product['product_category']->product_category_name}}" onfocus="product_autocomplete({{$key}});">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}"  value="{{$product->product_category_id}}">
                                                    <i class="fa fa-search search-icon"></i>
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="quantity_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][quantity]" value="{{$product->quantity}}" type="text">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="actual_quantity_{{$key}}" class="form-control" placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="{{$product->actual_pieces}}" type="text">
                                                </div>
                                            </td>

                                            <td class="col-md-1">
                                                <div class="form-group ">
                                                    <select class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}">

                                                        <option value="{{$product['unit']['id']}}" selected="">{{$product['unit']['unit_name']}}</option>

                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="present_shipping_{{$key}}" class="form-control" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="{{$product->present_shipping}}" type="text" readonly="readonly">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input type="text" class="form-control" value="{{$product->price}}" id="product_price_{{$key}}" name="product[{{$key}}][price]">
                                                </div>
                                            </td>
                                            <td class="col-md-3">
                                                <div class="form-group">
                                                    Amount {{$key}}
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
                                                        <a class="table-link" title="add more" id="add_product_row_delivery_challan">
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





                            <div class="form-group">
                                <label for="vehicle_name"><b class="challan">Discount</b></label>
                                <input id="vehicle_name" class="form-control" placeholder="Discount" name="discount"  value="{{$allorder->discount}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="driver_name"><b class="challan">Freight</b></label>
                                <input id="driver_name" class="form-control" placeholder="Freight " name="freight"  value="{{$allorder->freight}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="total"><b class="challan">Total</b><span class="gtotal">$150000</span></label>

                            </div>
                            <div class="form-group">
                                <label for="driver_contact"><b class="challan">Loading</b></label>
                                <input id="driver_contact" class="form-control" placeholder="loading" name="loading"  value="{{$allorder->loading_charge}}" type="text">
                            </div>

                            <div class="form-group">
                                <label for="loadedby"><b class="challan">Loaded By</b></label>
                                <input id="loadedby" class="form-control" placeholder="Loaded By" name="loadedby"  value="{{$allorder->loaded_by}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="labour"><b class="challan">Labour </b></label>
                                <input id="labour" class="form-control" placeholder="Labour" name="labour" v value="{{$allorder->labours}}" type="text">
                            </div>



                            <div class="form-group">

                                <label for="Plusvat"><b class="challan">Plus VAT</b> Yes/No</label>
                            </div>

                            <div class="form-group">
                                <label for="driver_contact"><b class="challan">VAT Percentage</b> <input type="text" name="vat_percentage" id="vat_percentage" value="{{$allorder->vat_percentage}}" readonly="readonly"></label>

                            </div>


                            <div class="form-group">
                                <label for="total"><b class="challan">Grand Total</b><span class="gtotal"><input type="text" class="form-group" name="grand_total" id="grand_total" readonly="readonly"  value="{{$allorder->grand_total}}"></span></label>

                            </div>
                            <div class="form-group">
                                <label for="billno"><b class="challan">Bill Number</b></label>
                                <input id="billno" class="form-control" placeholder="Bill Number" name="billno"  value="{{$allorder->bill_number}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="challan_remark"><b class="challan">Remark</b></label>
                                <textarea class="form-control" id="challan_remark" name="challan_remark"  rows="3"> {{$allorder->remarks}}</textarea>
                            </div>

                            <!--  <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->

                            <hr>                  

                            <div>
                                <button type="submit" class="btn btn-primary" >Submit</button>

                            </div>

                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
