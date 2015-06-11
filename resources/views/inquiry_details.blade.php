@extends('layouts.master')
@section('title','Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Inquiry</span></li>
                </ol>

                <div class="filter-block">
                    <h1 class="pull-left">View Inquiry</h1>
                    <div class="pull-right top-page-ui">
                        <a href="edit_enquiry.php" class="btn btn-primary pull-right">
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
                                        <tr>
                                            <td><span>Credit Period: </span>{{$inquiry['customer']->credit_period}}</td>
                                        </tr>

                                        <tr>
                                            <td><span class="underline">Product Details </span></td>

                                        </tr>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table customerview_table">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span> Product</span></td>
                                            <td><span> Quantity</span></td>
                                            <td><span>Unit</span></td>
                                            <td><span>Price</span></td>
                                            <td class="widthtable"><span>Update Price</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        @foreach($inquiry['inquiry_products'] as $product_data)
                                        <tr>
                                            <td>Product1</td>
                                            <td>{{$product_data->quantity}}</td>
                                            <td>{{$product_data['unit']->unit_name}}</td>
                                            <td>{{$product_data->price}}</td>
                                            <td>
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Price">

                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">

                                                        <input class="btn btn-primary" type="submit" class="form-control" value="save" >
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$product_data->remark}}</td>
                                        </tr>
                                        @endforeach
                                        <tr>

                                            <td>Product2</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>69</td>
                                            <td>
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Price">

                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">

                                                        <input class="btn btn-primary" type="submit" class="form-control" value="save" >
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>
                                        <tr>

                                            <td>Product3</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>69</td>
                                            <td>
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Price">

                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">

                                                        <input class="btn btn-primary" type="submit" class="form-control" value="save" >
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>
                                        <tr>

                                            <td>Product4</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>55</td>
                                            <td>
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Price">

                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">

                                                        <input class="btn btn-primary" type="submit" class="form-control" value="save" >
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <table id="table-example" class="table customerview_table">

                                    <tbody>
                                        <tr>
                                            <td><span>Plus VAT: </span>Yes</td>

                                        </tr>
                                        <tr>
                                            <td><span>VAT Percentage: </span>5%</td>

                                        </tr>
                                        <tr>
                                            <td><span>VAT: </span>Lorem</td>

                                        </tr>
                                        <tr>
                                            <td><span>Grand Total: </span> 5000</td>

                                        </tr>

                                        <tr>
                                            <td><span>Expected Delivery Date: </span>25 April,2015</td>

                                        </tr>
                                        <tr>
                                            <td><span>Remark: </span>Lorem Ipsum Dollar</td>

                                        </tr>

                                    </tbody>
                                </table>



                            </div>
                            <button type="button" class="btn btn-primary " >Save</button>
                            <hr>
                            <div>
                                <button title="SMS would be sent to Party and Relationship Manager" type="button" class="btn btn-primary smstooltip" >Send SMS</button><span title="SMS has been sent 5 times" class="badge enquirybadge smstooltip">5</span>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop