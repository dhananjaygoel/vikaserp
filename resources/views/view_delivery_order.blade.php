@extends('layouts.master')
@section('title','View Delivery Order')
@section('content')
<div class="row">						
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Delivery Order</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Delivery Order </h1>                                 
                    <div class="pull-right top-page-ui">
                        <a href="edit_deliveryorder.php" class="btn btn-primary pull-right">
                            Edit Delivery Order
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
                                        <td><span>Warehouse :</span> Lorem Ipsum</td>
                                    </tr>
                                    <tr>
                                        <td><span>Supplier Name:</span> Supplier1</td>
                                    </tr>
                                    <tr>
                                        <td><span>Customer Name:</span> Customer1</td>
                                    </tr>
                                    <tr><td><span>Contact Person: </span>Lorem Ipsum</td></tr>
                                    <tr>
                                        <td><span>Date:</span> 30 April 2015</td>
                                    </tr>
                                    <tr><td><span>Serial Number: </span>Apr30/04/01/01</td></tr>
                                    <tr>
                                        <td><span>Mobile Number: </span>9166778822</td>
                                    </tr>
                                    <tr>
                                        <td><span class="underline"> Product Details </span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>   
                                    <tr class="headingunderline">
                                        <td>
                                            <span>Product</span>
                                        </td>
                                        <td>
                                            <span>Quantity</span>
                                        </td>
                                        <td>
                                            <span>Unit</span>
                                        </td>
                                        <td>
                                            <span>Price</span>
                                        </td>
                                        <td>
                                            <span>Pending Order</span>
                                        </td>

                                        <td><span>Remark</span></td>
                                    </tr>
                                    <tr>
                                        <td> Product1</td>
                                        <td>25</td>
                                        <td>Unit</td>
                                        <td>100</td>
                                        <td>15</td>
                                        <td>Ipsum</td>
                                    </tr>
                                    <tr>
                                        <td> Product2</td>
                                        <td>25</td>
                                        <td>Unit</td>
                                        <td>150</td>
                                        <td>15</td>
                                        <td>Ipsum</td>
                                    </tr>
                                    <tr>
                                        <td> Product3</td>
                                        <td>25</td>
                                        <td>Unit</td>
                                        <td>150</td>
                                        <td>25</td>
                                        <td>Lorem</td>
                                    </tr>
                                    <tr>
                                        <td> Product4</td>
                                        <td>25</td>
                                        <td>Unit</td>
                                        <td>150</td>
                                        <td>15</td>
                                        <td>Ipsum</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="table-example" class="table table-hover customerview_table  ">
                                <tbody>
                                <td><span>Plus VAT: </span>Yes</td>
                                </tr>
                                <tr>
                                    <td><span>VAT Percentage: </span>5%</td>
                                </tr>
                                <tr>
                                    <td><span>VAT: </span>Lorem</td>
                                </tr>
                                <tr>
                                    <td><span>Grand Total: </span> $25000</td>
                                </tr>
                                <tr><td><b>Vehicle Name:</b> Lorem Ipsum </td> </tr>
                                <tr><td><b>Driver Name:</b> Lorem Ipsum</td> </tr>
                                <tr><td><b>Driver Contact:</b> Lorem Ipsum</td> </tr>
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
                </div>
            </div>
        </div>
    </div>
</div>
@stop