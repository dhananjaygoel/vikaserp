@extends('layouts.master')
@section('title','Edit Purchase Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchase_challan')}}">Purchase Challan</a></li>
                    <li class="active"><span>Purchase Challan</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Edit Purchase Challan </h1>                                 
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

                        {!!Form::open(array('method'=>'PUT','url'=>url('purchase_challan/'.$purchase_challan['id']),'id'=>'updateUserForm'))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="form-group">
                            <label><b>Bill Date:</b> {{$purchase_challan['purchase_advice']->purchase_advice_date}} </label>
                        </div>
                        @if($purchase_challan->bill_number!='')
                        <div class="form-group">
                            <label><b>Bill Number:</b> {{ $purchase_challan->bill_number }} </label>
                        </div>
                        @endif
                        <div class="form-group">
                            <label><b>Party Name:</b> {{  $purchase_challan['supplier']->owner_name }} </label>
                        </div>
                        <div class="form-group">
                            <label><b>Serial Number:</b> {{ $purchase_challan->serial_number }} </label>
                        </div>
                        <div class="table-responsive">
                            <table id="table-example" class="table table_deliverchallan serial purchaseorder_advide_table">
                                <tbody>
                                    <tr>
                                        <td class="col-md-2"><span>Product Name (Alias)</span></td>
                                        <td class="col-md-2"><span>Actual Quantity</span></td>
                                        <td class="col-md-1"><span>Unit</span></td>
                                        <td class="col-md-2 text-center"><span>Present Shipping</span></td>
                                        <td class="col-md-2"><span>Rate</span></td>
                                        <td class="col-md-2"><span>Amount</span></td>
                                    </tr>

                                    <?php $total_price = 0; ?>
                                    @foreach($purchase_challan['purchase_product'] as $key=>$products)
                                    <tr id="add_row_{{$key}}" class="add_product_row">
                                        <td>
                                            <div class="form-group">
                                                {{$products['product_category']['product_sub_category']->alias_name}}
                                                <input type="hidden" name="product[{{$key}}][product_category_id]" id="add_product_id_{{$key}}" value="{{$products['product_category']['product_sub_category']->id}}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input id="quantity_{{$key}}" class="form-control" placeholder="Actual Quantity" name="product[{{$key}}][quantity]" value="{{$products->quantity}}" type="text">
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
                                                    <input type="text" class="form-control" id="product_price_{{$key}}" value="{{$products->price}}" name="product[{{$key}}][price]" placeholder="Rate">
                                                </div>                                         
                                            </div>
                                        </td>
                                        <td>   
                                            <div class="form-group">
                                                <?php $total_price += $products->present_shipping * $products->price; ?>
                                                {{ $products->present_shipping * $products->price }}
                                            </div>
                                        </td>
                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                            <table>
                                <tr class="row5">
                                    <td>
                                        <div class="add_button1">
                                            <div class="form-group pull-left">
                                                <label for="addmore"></label>
                                                <a href="#" class="table-link" title="add more" id="add_more_product">
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
                            <label><b>Total Actual Quantity:</b> 500</label>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_name"><b class="challan">Vehicle Name</b></label>
                            <input id="vehicle_name" class="form-control" placeholder="Vehicle Name" name="vehicle_number" value="{{ $purchase_challan->vehicle_number }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="vehicle_name"><b class="challan">Discount</b></label>
                            <input id="vehicle_name" class="form-control" placeholder="Discount" name="discount" value="{{$purchase_challan->discount}}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="driver_name"><b class="challan">Freight</b></label>
                            <input id="driver_name" class="form-control" placeholder="Freight " name="Freight" value="{{ $purchase_challan->freight }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="total"><b class="challan">Total</b> </label>
                        </div>
                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Loaded By</b></label>
                            <input id="loadedby" class="form-control" placeholder="Loaded By" name="loadedby" value="{{ $purchase_challan->loaded_by }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour </b></label>
                            <input id="labour" class="form-control" placeholder="Labour" name="labour" value="{{ $purchase_challan->labours }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="Plusvat"><b class="challan">Plus VAT</b> Yes/No</label>
                        </div>
                        <div class="form-group">
                            <label for="driver_contact"><b class="challan">VAT Percentage</b> {{ $purchase_challan->vat_percentage }}</label>
                        </div>                     
                        <div class="form-group">
                            <label for="total"><b class="challan">Grand Total</b> $25000</label>
                        </div>
                        @if($purchase_challan->bill_number!='')
                        <div class="form-group">
                            <label for="billno"><b class="challan">Bill Number</b></label>
                            <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="{{ $purchase_challan->bill_number }}" type="text">
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="inquiry_remark"><b class="challan">Remark</b></label>
                            <textarea class="form-control" id="inquiry_remark" name="remarks"  rows="3">
                                    {{ $purchase_challan->remarks }}
                            </textarea>
                        </div>
                        <hr>
                        <div>
                            <button type="submit" class="btn btn-primary" >Submit</button>
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

@endsection


