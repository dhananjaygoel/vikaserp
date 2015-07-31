@extends('layouts.master')
@section('title','Delivery Challan Details')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_challan')}}">Delivery Challan</a></li>
                    <li class="active"><span>View Delivery Challan</span></li>
                </ol>
                <div class="clearfix">

                    <!--                    <div class="pull-right top-page-ui">
                                            <a href="{{url('delivery_challan/'.$allorder->id.'/edit')}}" class="btn btn-primary pull-right">
                                                Edit Delivery Challan
                                            </a>
                                        </div>-->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box"> 
                    <div class="main-box-body clearfix">
                        <div class="form-group">
                            <label><b>Tally Name:</b>
                                @if($allorder['customer']->owner_name != "" && $allorder['customer']->tally_name != "")
                                {{$allorder['customer']->owner_name}}{{'-'.$allorder['customer']->tally_name}}
                                @else
                                {{$allorder['customer']->owner_name}}
                                @endif

                            </label>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label><b>Serial Number:</b> {{$allorder['delivery_order']->serial_no}}</label>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label ><b> <span class="underline">Product Details</span></b></label>
                        </div>
                        <div class="table-responsive">
                            <table id="add_product_table" class="table customerview_table">
                                <tbody>
                                    <tr class="headingunderline">
                                        <td class="col-md-2"><span>Product Name(Alias)</span></td>
                                        <td class="col-md-1"><span>Actual Quantity</span></td>
                                        <td class="col-md-1"><span>Actual Pieces</span></td>
                                        <td class="col-md-1"><span>Unit</span></td>
                                        <td class="col-md-2"><span>Present Shipping</span></td>
                                        <td class="col-md-2"><span>Rate</span></td>
                                        <td class="col-md-3"><span>Amount</span></td>

                                    </tr>
                                    <?php $total_amount = 0; ?>
                                    @foreach($allorder['all_order_products'] as $key=>$product)
                                    @if($product->order_type =='delivery_challan')
                                    <tr id="add_row_{{$key}}" class="add_product_row">

                                        <td class="col-md-2">
                                            <div class="form-group searchproduct">

                                                {{$product['order_product_details']->alias_name}}

                                            </div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group">
                                                {{$product->quantity}}
                                            </div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group">
                                                {{$product->actual_pieces}}
                                            </div>
                                        </td>

                                        <td class="col-md-1">
                                            <div class="form-group ">
                                                {{$product['unit']['unit_name']}}
                                            </div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group">
                                                {{$product->present_shipping}}
                                            </div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group">
                                                {{$product->price}}
                                            </div>
                                        </td>
                                        <td class="col-md-3">
                                            <div class="form-group">
                                                <?php
                                                $amount = $product->actual_quantity * $product->price;
                                                $total_amount = $amount + $total_amount;
                                                echo $amount;
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach

                                </tbody>
                            </table>

                        </div>

                        <div class="form-group">
                            <label for="vehicle_name"><b class="challan">Total Actual Quantity: </b></label>
                            {{$allorder['all_order_products']->sum('actual_quantity')}}
                            <?php  ?>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="vehicle_name"><b class="challan">Discount: </b></label>
                            {{$allorder->discount}}
                            <?php $total_amount = $total_amount + $allorder->discount; ?>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="driver_name"><b class="challan">Freight: </b></label>
                            {{$allorder->freight}}
                            <?php $total_amount = $total_amount + $allorder->freight; ?>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="driver_contact"><b class="challan">Loading: </b></label>
                            {{$allorder->loading_charge}}
                            <?php $total_amount = $total_amount + $allorder->loading_charge; ?>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="total"><b class="challan">Total</b><span class="gtotal">{{$total_amount}}</span></label>

                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Loaded By: </b></label>
                            {{$allorder->loaded_by}}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour: </b></label>
                            {{$allorder->labours}}
                        </div>
                        <hr>


                        @if($allorder->vat_percentage != "" || $allorder->vat_percentage != 0)
                        <div class="form-group">
                            <label for="Plusvat"><b class="challan">VAT: </b> Yes</label>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="driver_contact"><b class="challan">VAT Percentage: </b> {{$allorder->vat_percentage}} %</label>

                        </div>

                        <hr>
                        @else
                        <div class="form-group">
                            <label for="Plusvat"><b class="challan">VAT: </b> No</label>
                        </div>
                        <hr>
                        @endif

                        <div class="form-group">
                            <label for="total"><b class="challan">Round Off: </b><span class="gtotal">{{$allorder->round_off}}</span></label>
                        </div>

                        <div class="form-group">
                            <label for="total"><b class="challan">Grand Total: </b><span class="gtotal">{{$allorder->grand_price}}</span></label>

                        </div>
                        <hr>
                        @if($allorder->bill_number != "")
                        <div class="form-group">
                            <label for="billno"><b class="challan">Bill Number: </b></label>
                            {{$allorder->bill_number}}
                        </div>
                        <hr>
                        @endif
                        <div class="form-group">
                            <label for="challan_remark"><b class="challan">Remark: </b></label>
                            <textarea class="form-control" id="challan_remark" name="challan_remark"  rows="3" readonly="readonly"> {{$allorder->remarks}}</textarea>
                        </div>
                        <a href="{{url('delivery_challan')}}" class="btn btn-default form_button_footer">Back</a>
                    </div>                  
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
