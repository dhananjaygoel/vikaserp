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
                                        <td><span>Bill Date:</span> {{isset($purchase_challan['purchase_advice']->purchase_advice_date)?date('F jS, Y',strtotime($purchase_challan['purchase_advice']->purchase_advice_date)):''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Bill Number:</span> {{ $purchase_challan->bill_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Serial Number: </span> {{isset($purchase_challan->serial_number)?$purchase_challan->serial_number:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Order From: </span>
                                            @if($purchase_challan['supplier']->owner_name != "" && $purchase_challan['supplier']->tally_name != "")
                                            {{$purchase_challan['supplier']->owner_name.'-'.$purchase_challan['supplier']->tally_name}}
                                            @else
                                            {{$purchase_challan['supplier']->owner_name}}
                                            @endif

                                        </td>
                                    </tr>
                                    <?php // dd($purchase_challan['purchase_order']); ?>
                                    @if(isset($purchase_challan['purchase_order']) && count($purchase_challan['purchase_order'])>0)
                                        @if($purchase_challan['purchase_order']->order_for == 0)
                                            <tr><td><span><b>Order For: </b></span> Warehouse</td></tr>
                                        @elseif($purchase_challan['purchase_order']->order_for != 0)
                                            @foreach($customers as $customer)
                                            @if($customer->id == $purchase_challan['purchase_order']->order_for)
                                            <tr>
                                                <td>
                                                    <span><b>Order For:</b></span>
                                                    {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endif
                                    @else    
                                        <tr><td><span><b>Order For: </b></span> </td></tr>    
                                    @endif    
                                    <?php // dd($purchase_challan['purchase_order']); ?>
                                    @if(isset($purchase_challan['purchase_order']) && count($purchase_challan['purchase_order'])>0 && $purchase_challan['purchase_order']->discount > 0)
                                        <tr>
                                            <td>
                                                <span><b>Discount/Premium :</b> </span>
                                                {{$purchase_challan['purchase_order']->discount_type}}                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Fixed/Percentage :</b> </span>
                                                {{$purchase_challan['purchase_order']->discount_unit}}                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Amount :</b> </span>
                                                {{$purchase_challan['purchase_order']->discount}}                                            
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td>
                                                <span><b>Discount/Premium :</b> </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Fixed/Percentage :</b> </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Amount :</b> </span>
                                            </td>
                                        </tr>                                    
                                    @endif                                    
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
                                        <td  class="widthtable">
                                            <span>Length</span>
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
                                        <td> {{isset($product_data['purchase_product_details']->alias_name)?$product_data['purchase_product_details']->alias_name:''}} </td>
                                        <td> {{isset($product_data->quantity)?$product_data->quantity:'0'}}</td>
                                        <td> {{$product_data['unit']->unit_name}} </td>
                                        <td> {{isset($product_data->length)?$product_data->length:'0'}} </td>
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
                                        <td>
                                            <span>Total Actual Quantity: </span>{{$total_quantity}}
                                            &nbsp;
                                            &nbsp;
                                            <span>Total Amount: </span>{{$total_amount}}
                                        </td>
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
                                    @if(isset($purchase_challan['purchase_order']) && $purchase_challan['purchase_order']->order_for == 0)
                                        <tr>
                                            <td><span>Unloaded By: </span>
                                                <?php
                                                if (isset($purchase_challan['challan_loaded_by'])) {
                                                    foreach ($purchase_challan['challan_loaded_by'] as $challan_loaded_by) {
                                                        foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                                                            echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>Labour: </span>
                                                <?php
                                                if (isset($purchase_challan['challan_labours']) && !empty($purchase_challan['challan_labours'])) {
                                                    foreach ($purchase_challan['challan_labours'] as $challan_labour) {
                                                        foreach ($challan_labour['dc_labour'] as $labour) {
                                                            echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                                                        }
                                                    }
                                                } else {
                                                    echo "N/A";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><span>Unloaded By: </span>
                                        </tr>
                                        <tr>
                                            <td><span>Labour: </span>
                                        </tr>    
                                    @endif
                                    @if($purchase_challan->vat_percentage>0)
                                    <tr>
                                        <td><span>GST Percentage: </span>{{ $purchase_challan->vat_percentage }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td><span>Plus GST: </span>No</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><span>Round Off: </span>{{ $purchase_challan->round_off }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Grand Total: </span>{{$purchase_challan->grand_total}}</td>
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
                            <a href="{{URL::previous()}}" class="btn btn-default">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


