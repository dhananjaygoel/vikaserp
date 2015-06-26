@extends('layouts.master')
@section('title','View Purchase Challan')
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
                    <h1 class="pull-left">View Purchase Challan </h1>                                 
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('PurchaseChallanController@edit',['id'=> $purchase_challan->id])}}" class="btn btn-primary pull-right">
                            Edit Purchase Challan
                        </a>
                    </div>                
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">
                                <tbody>               
                                    <tr>
                                        <td><span>Bill Date:</span> {{$purchase_challan['purchase_advice']->purchase_advice_date}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Bill Number:</span> {{ $purchase_challan->bill_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Serial Number: </span> {{ $purchase_challan->serial_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Party Name: </span>{{  $purchase_challan['supplier']->owner_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><span class="underline"> Product Details </span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="table-example" class="table customerview_table">
                                <tbody>  
                                    <tr class="headingunderline">
                                        <td class="widthtable">
                                            <span>Product Name(Alias)</span>
                                        </td>       
                                        <td class="widthtable">
                                            <span>Actual Quantity</span>
                                        </td>
                                        <td  class="widthtable">
                                            <span>Unit</span>
                                        </td>
                                        <td class="widthtable">
                                            <span>Present Shipping</span>
                                        </td>
                                        <td class="widthtable">
                                            <span>Rate</span>
                                        </td>
                                        <td  class="widthtable">
                                            <span>Amount</span>
                                        </td>
                                        <td class="widthtable">
                                            <span>Remark</span>
                                        </td>
                                    </tr>
                                    @foreach($purchase_challan['purchase_product'] as $product_data)
                                    <tr>
                                        <td> {{$product_data['product_sub_category']->alias_name}} </td>
                                        <td> {{$product_data->quantity}}</td>
                                        <td> {{$product_data['unit']->unit_name}} </td>
                                        <td> {{$product_data->present_shipping}}</td> 
                                        <td> {{$product_data->price}}</td>
                                        <td> 35</td>
                                        <td> {{$product_data->remarks}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>   
                                    <tr>
                                        <td><span>Total Actual Quantity: </span>500</td>
                                    </tr>
                                    <tr>
                                        <td><span>Discount: </span>{{$purchase_challan->discount}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Freight: </span>{{ $purchase_challan->discount }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Total: </span>$15000</td>
                                    </tr>
                                    <tr>
                                        <td><span>Unloading: </span>{{ $purchase_challan->unloading }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Unloaded By: </span>{{ $purchase_challan->unloaded_by }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Labour: </span>{{ $purchase_challan->labours }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Plus VAT: </span>Yes</td>
                                    </tr>
                                    <tr>
                                        <td><span>VAT Percentage: </span>{{ $purchase_challan->vat_percentage }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Grand Total:</span>$25000</td>
                                    </tr>
                                    <tr>
                                        <td><span>Vehicle Name: </span>{{ $purchase_challan->vehicle_number }}</td>
                                    </tr> 
                                    <tr>
                                        <td><span>Delivery Location: </span>{{ $purchase_challan->vat_percentage }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Remark: </span>{{ $purchase_challan->remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection


