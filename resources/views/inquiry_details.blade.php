
@extends('layouts.master')
@section('title','Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Inquiry</span></li>
                </ol>

                <div class="filter-block">
                    <h1 class="pull-left">View Inquiry</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{ url('inquiry/'.$inquiry->id.'/edit') }}" class="btn btn-primary pull-right">
                            Edit Inquiry
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <form>
                            <div class="table-responsive">
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        <tr>
                                            <td><span>Customer Name:</span> {{$inquiry['customer']->owner_name}}</td>
                                        </tr>
                                        <tr><td><span>Contact Person: </span>{{$inquiry['customer']->contact_person}}</td></tr>
                                        <tr>
                                            <td><span>Phone Number: </span>{{$inquiry['customer']->phone_number1}}</td>
                                        </tr>
                                        @if($inquiry['customer']->credit_period !='' || $inquiry['customer']->credit_period> 0)
                                        <tr>
                                            <td><span>Credit Period: </span>{{$inquiry['customer']->credit_period}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><span class="underline">Product Details </span></td>

                                        </tr>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span> Product(Alias)</span></td>
                                            <td><span> Quantity</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Price</span></td>
                                            <td class="widthtable"><span>Update Price</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        @foreach($inquiry['inquiry_products'] as $product_data)
                                        <tr>
                                            <td>{{$product_data['product_category']['product_sub_category']->alias_name}}</td>
                                            <td>{{$product_data->quantity}}</td>
                                            <td>{{$product_data['unit']->unit_name}}</td>
                                            <td>{{$product_data->price}}</td>
                                            <td>
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" id="difference_{{$product_data->id}}" placeholder="Price" value='{{$product_data->price}}' required="">
                                                        <input type="hidden"name="product_id" value='{{$product_data->id}}' id='hidden_inquiry_product_id_{{$product_data->id}}'>
                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">
                                                        <input type="button" name="save_price" value="Save" class="btn btn-primary" id="save_price_inquiry_view_{{$product_data->id}}" onclick="save_price_inquiry_view({{$product_data->id}});">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$product_data->remarks}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        @if($inquiry->vat_percentage == 0)
                                        <tr><td><span>Plus VAT: </span>No</td></tr>
                                        @elseif($inquiry->vat_percentage != 0)
                                        <tr><td><span>Plus VAT: </span>Yes</td></tr>
                                        <tr><td><span>VAT Percentage: </span>{{$inquiry->vat_percentage."%"}}</td></tr>
                                        @endif

                                        <tr>
                                            <td><span>Total: </span> <?php $total = $inquiry['inquiry_products']->sum('price') * $product_data->quantity; echo $total; ?></td>

                                        </tr>

                                        <tr>
                                            <td><span>Expected Delivery Date: </span>{{date('d F,Y',strtotime($inquiry->expected_delivery_date))}}</td>

                                        </tr>
                                        <tr>
                                            <td><span>Remark: </span>{{$inquiry->remarks}}</td>

                                        </tr>

                                    </tbody>
                                </table>



                            </div>
                            
                            <hr>
                            <div>
                                <button title="SMS would be sent to Party and Relationship Manager" type="button" class="btn btn-primary smstooltip" >Send SMS</button><span title="SMS has been sent 5 times" class="badge enquirybadge smstooltip">0</span>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop