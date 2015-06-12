@extends('layouts.master')
@section('title','Order Details')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Order Details </span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{url('orders/1/edit')}}" class="btn btn-primary pull-right">
                            Edit Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-dashboard"></i> &nbsp; View Order </h2>
                    </header>            
                    <div class="main-box-body clearfix">

                        <div class="inquiry_table col-md-12">

                            <div class="table-responsive">
                                <table id="table-example" class="table table-hover customerview_table  ">


                                    <tbody> 
                                        <tr><td><span>Warehouse: </span>Lorem Ipsum</td></tr>
                                        <tr><td><span>Supplier Name:</span> Supplier1</td></tr>
                                        <tr><td><span>Customer Name:</span> Customer1</td></tr>
                                        <tr><td><span>Contact Person: </span>Lorem Ipsum</td></tr>
                                        <tr>
                                            <td><span>Mobile Number: </span>9166778822</td>

                                        </tr>
                                        <tr> <td><span>Credit Period: </span>Lorem Ipsum</td></tr>   
                                        <tr>
                                            <td><span class="underline">Ordered Product Details </span></td>

                                        </tr>
                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">


                                    <tbody>   
                                        <tr class="headingunderline">


                                            <td>
                                                <span> Product</span>
                                            </td>
                                            <td>
                                                <span> Qty</span>
                                            </td>
                                            <td>
                                                <span>Unit</span>
                                            </td>

                                            <td>
                                                <span>Price</span>
                                            </td>
                                            <td class="widthtable">
                                                <span>Remark</span>
                                            </td>

                                        </tr>

                                        <tr>

                                            <td>
                                                Product1
                                            </td>
                                            <td>
                                                55
                                            </td>
                                            <td>
                                                350
                                            </td>


                                            <td>
                                                650
                                            </td>
                                            <td>
                                                Lorem
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>
                                                Product1
                                            </td>
                                            <td>
                                                55
                                            </td>
                                            <td>
                                                350
                                            </td>


                                            <td>
                                                650
                                            </td>
                                            <td>
                                                Lorem
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>
                                                Product1
                                            </td>
                                            <td>
                                                55
                                            </td>
                                            <td>
                                                350
                                            </td>


                                            <td>
                                                650
                                            </td>
                                            <td>
                                                Lorem
                                            </td>

                                        </tr>
                                        <tr>

                                            <td>
                                                Product1
                                            </td>
                                            <td>
                                                55
                                            </td>
                                            <td>
                                                350
                                            </td>


                                            <td>
                                                650
                                            </td>
                                            <td>
                                                Lorem
                                            </td>

                                        </tr>


                                    </tbody>
                                </table>
                                <table id="table-example" class="table table-hover customerview_table  ">


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
                                            <td><span>Estimated Delivery Date: </span>20 April,2015</td>

                                        </tr>   

                                        <tr>
                                            <td><span>Expected Delivery Date: </span>25 April,2015</td>

                                        </tr>      
                                        <tr>
                                            <td><span>Delivery Location: </span>Lorem Ipsum Dollar</td>

                                        </tr>
                                        <tr>
                                            <td><span>Remark: </span>Lorem Ipsum Dollar</td>

                                        </tr>


                                    </tbody>
                                </table>
                            </div>

                        </div>


                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop