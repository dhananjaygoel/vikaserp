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
                                        <td><span>Bill Date:</span> {{ date('jS F, Y',strtotime($purchase_challan['purchase_advice']->purchase_advice_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Bill Number:</span> {{ $purchase_challan->bill_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Serial Number: </span> {{ $purchase_challan->serial_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Tally Name: </span>
                                            @if($purchase_challan['supplier']->owner_name != "" && $purchase_challan['supplier']->tally_name != "")
                                            {{$purchase_challan['supplier']->owner_name.'-'.$purchase_challan['supplier']->tally_name}}
                                            @else
                                            {{$purchase_challan['supplier']->owner_name}}
                                            @endif

                                        </td>
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
                                    <?php
                                    $total_quantity = 0;
                                    $total_amount = 0;
                                    ?>
                                    @foreach($purchase_challan['purchase_product'] as $product_data)
                                    @if($product_data->order_type == 'purchase_challan')
                                    <tr>
                                        <td> {{$product_data['purchase_product_details']->alias_name}} </td>
                                        <td> {{$product_data->quantity}}</td>
                                        <td> {{$product_data['unit']->unit_name}} </td>
                                        <td> {{$product_data->present_shipping}}</td> 
                                        <td> {{$product_data->price}}</td>
                                        <td> 
                                            <?php
                                            $total_quantity += $product_data->quantity;
                                            $amount = $product_data->quantity * $product_data->price;
                                            echo $amount;
                                            $total_amount = $total_amount + $amount;
                                            ?>

                                        </td>
                                        <td> {{$product_data->remarks}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>   
                                    <tr>
                                        <td><span>Total Actual Quantity: </span>{{$total_quantity}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Discount: </span>{{$purchase_challan->discount}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Freight: </span>{{ $purchase_challan->freight }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Total: </span>
                                            <?php
                                            $total = $total_amount + $purchase_challan->discount + $purchase_challan->freight;
                                            echo $total;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Unloaded By: </span>{{ $purchase_challan->unloaded_by }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Labour: </span>{{ $purchase_challan->labours }}</td>
                                    </tr>
                                    @if($purchase_challan->vat_percentage>0)
                                    <tr>
                                        <td><span>VAT Percentage: </span>{{ $purchase_challan->vat_percentage }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td><span>Plus VAT: </span>No</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><span>Grand Total:</span>{{$purchase_challan->grand_total}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Vehicle Name: </span>{{ $purchase_challan->vehicle_number }}</td>
                                    </tr> 
                                    <tr>
                                        <td>
                                            <span>Delivery Location: </span>
                                            @if($purchase_challan->delivery_location_id != 0 )
                                            {{$purchase_challan['delivery_location']->area_name}}
                                            @else
                                            {{$purchase_challan->other_location}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Remark: </span>{{ $purchase_challan->remarks }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="{{url('purchase_challan')}}" class="btn btn-default">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


