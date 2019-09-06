@extends('layouts.master')
@section('title','Purchase Advice details')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchaseorder_advise')}}">Purchase Advice</a></li>
                    <li class="active"><span>Purchase Advice details</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Purchase Advice</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{url('purchaseorder_advise/'.$purchase_advise->id.'/edit')}}" class="btn btn-primary pull-right">
                            Edit Purchase Advice
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
                                        <td><span>Bill Date:</span> {{date('F jS, Y', strtotime($purchase_advise->purchase_advice_date))}}</td>
                                    </tr>                                    
                                    <tr>
                                        <td><span>Order From:</span>
                                            @if($purchase_advise['supplier']->owner_name != "" && $purchase_advise['supplier']->tally_name != "")
                                            {{$purchase_advise['supplier']->owner_name}}-{{$purchase_advise['supplier']->tally_name}}
                                            @else
                                            {{$purchase_advise['supplier']->owner_name}}
                                            @endif
                                        </td>
                                    </tr>
                                    @if($purchase_advise['purchase_order'][0]->order_for == 0)
                                        <tr><td><span><b>Order For: </b></span> Warehouse</td></tr>
                                    @elseif($purchase_advise['purchase_order'][0]->order_for != 0)                                        
                                        @foreach($customers as $customer)
                                        @if($customer->id == $purchase_advise['purchase_order'][0]->order_for)
                                        <tr>
                                            <td>
                                                <span><b>Order For:</b></span>
                                                {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                            </td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @endif
                                    <tr><td><span>Contact Person: </span> {{$purchase_advise['supplier']->contact_person}}</td></tr>

                                    <tr><td><span>Serial Number: </span>{{$purchase_advise->serial_number}}</td></tr>
                                    <tr>
                                        <td><span>Mobile Number: </span>{{$purchase_advise['supplier']->phone_number1}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Credit Period(Days): </span> {{$purchase_advise['supplier']->credit_period}}</td>
                                    </tr>
                                    <?php // dd($purchase_advise['purchase_order'][0]->discount); ?>
                                    @if($purchase_advise['purchase_order'][0]->discount > 0)
                                        <tr>
                                            <td>
                                                <span><b>Discount/Premium :</b> </span>
                                                {{$purchase_advise['purchase_order'][0]->discount_type}}
                                            </td>                                            
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Fixed/Percentage :</b> </span>
                                                {{$purchase_advise['purchase_order'][0]->discount_unit}}
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td>
                                                <span><b>Amount :</b> </span> 
                                                {{$purchase_advise['purchase_order'][0]->discount}}
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
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                    <tr class="headingunderline">
                                        <td><span>Product</span></td>
                                        <td><span>Unit</span></td>
                                        <td><span>Length</span></td>
                                        <td><span>Actual Pieces</span></td>
                                        <td class="col-md-2" ><span>Present Shipping</span></td>
                                        <td><span>Price</span></td>
                                        <td><span>Remark</span></td>
                                    </tr>
                                    @foreach($purchase_advise['purchase_products'] as $product_data)
                                    @if($product_data->order_type == 'purchase_advice' && $product_data->actual_pieces > '0')
                                    <tr>
                                        <td>{{$product_data['purchase_product_details']->alias_name}}</td>
                                        <td>{{isset($product_data['unit']->unit_name)?$product_data['unit']->unit_name:''}}</td>
                                        <td>{{$product_data->length}}</td>
                                        <td>{{$product_data->actual_pieces}}</td>
                                        <td>{{$product_data->present_shipping}}</td>
                                        <td>{{$product_data->price}}</td>
                                        <td>{{$product_data->remarks}}</td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                    <tr>
                                        <td><span>Plus GST: </span>
                                            <?php
                                            if ($purchase_advise->vat_percentage != '') {
                                                echo 'Yes';
                                            } else {
                                                echo 'no';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    if ($purchase_advise->vat_percentage != '') {
                                        ?>
                                        <tr>
                                            <td><span>GST Percentage: </span> {{$purchase_advise->vat_percentage.'%'}}</td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td><span>Expected Delivery Date: </span>{{date("F jS, Y", strtotime($purchase_advise->expected_delivery_date))}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Vehicle Number: </span> {{$purchase_advise->vehicle_number}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Delivery Location: </span>
                                            @if($purchase_advise->delivery_location_id > 0)
                                            {{$purchase_advise['location']->area_name}}
                                            @else
                                            {{$purchase_advise->other_location}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Remark: </span> {{$purchase_advise->remarks}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop