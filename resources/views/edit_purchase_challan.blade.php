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
<!--                    <div class="pull-right top-page-ui">
                        <a href="abc.php" class="btn btn-primary pull-right">
                            Edit Purchase Challan
                        </a>
                    </div>                -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="" accept-charset="UTF-8" >
                            <div class="form-group">
                                <label><b>Bill Date:</b> Edited Date </label>
                            </div>
                            <div class="form-group">
                                <label><b>Bill Number:</b> Mum01 </label>
                            </div>
                            <div class="form-group">
                                <label><b>Party Name:</b> Party1 </label>
                            </div>
                            <div class="form-group">
                                <label><b>Serial Number:</b>PO/ May05/02/01/01</label>
                            </div>
                            <div class="table-responsive">
                                <table id="table-example" class="table table_deliverchallan serial">
                                    <tbody>
                                        <tr>
                                            <td class="col-md-2"><span>Product Name (Alias)</span></td>
                                            <td class="col-md-2"><span>Actual Quantity</span></td>
                                            <td class="col-md-1"><span>Unit</span></td>
                                            <td class="col-md-2 text-center"><span>Present Shipping</span></td>
                                            <td class="col-md-2"><span>Rate</span></td>
                                            <td class="col-md-2"><span>Amount</span></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    Product1
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>
                                            <td> 
                                                <div class="form-group">
                                                    Unit
                                                </div>
                                            </td>
                                            <td>  
                                                <div class="form-group text-center">
                                                    Shipping1
                                                </div>
                                            </td>
                                            <td class="shippingcolumn">
                                                <div class="row ">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>   
                                                <div class="form-group">
                                                    Amount1
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    Product1
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>
                                            <td> 
                                                <div class="form-group">
                                                    Unit
                                                </div>
                                            </td>
                                            <td>  
                                                <div class="form-group text-center">
                                                    Shipping2
                                                </div></td>
                                            <td class="shippingcolumn">
                                                <div class="row ">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>   
                                                <div class="form-group">
                                                    Amount2
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    Product1
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>
                                            <td> 
                                                <div class="form-group">
                                                    Unit
                                                </div>
                                            </td>
                                            <td>  <div class="form-group text-center">
                                                    Shipping3
                                                </div></td>
                                            <td class="shippingcolumn">
                                                <div class="row ">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">

                                                    </div>

                                                </div>
                                            </td>
                                            <td>   <div class="form-group">

                                                    Amount3
                                                </div></td>

                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group">

                                                    Product1
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">

                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>

                                            <td> <div class="form-group">

                                                    Unit
                                                </div></td>
                                            <td>  <div class="form-group text-center">

                                                    Shipping4
                                                </div></td>
                                            <td class="shippingcolumn">
                                                <div class="row ">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">

                                                    </div>

                                                </div>
                                            </td>
                                            <td>   <div class="form-group">

                                                    Amount4
                                                </div></td>

                                        </tr>
                                        <tr class="row5">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                        <label for="addmore"></label>
                                                        <a href="#" class="table-link" title="add more" id="addmore1">
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
                                        <tr class="row6">
                                            <td>
                                                <div class=" form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                    <i class="fa fa-search search-icon"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">

                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>


                                            <td>
                                                <div class="form-group ">
                                                    <select class="form-control" name="type" id="add_status_type">

                                                        <option value="2">Kg</option>
                                                        <option value="3">mm</option>
                                                        <option value="3">cm</option>
                                                    </select>
                                                </div>
                                            </td>     


                                            <td>  <div class="form-group">

                                                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                                                </div></td>
                                            <td class="shippingcolumn">
                                                <div class="row ">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">

                                                    </div>

                                                </div>
                                            </td>
                                            <td>   <div class="form-group">

                                                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                                                </div></td>

                                        </tr>
                                        <tr class="row7">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                        <label for="addmore"></label>
                                                        <a href="#" class="table-link" title="add more" id="addmore2">
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
                                        <tr class="row8">
                                            <td>
                                                <div class=" form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                    <i class="fa fa-search search-icon"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">

                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>


                                            <td class="col-md-2">
                                                <div class="form-group ">
                                                    <select class="form-control" name="type" id="add_status_type">

                                                        <option value="2">Kg</option>
                                                        <option value="3">mm</option>
                                                        <option value="3">cm</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>  <div class="form-group">

                                                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                                                </div></td>
                                            <td class="shippingcolumn">
                                                <div class="row ">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">

                                                    </div>

                                                </div>
                                            </td>
                                            <td>   <div class="form-group">

                                                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                                                </div></td>

                                        </tr>
                                        <tr class="row9">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                        <label for="addmore"></label>
                                                        <a href="#" class="table-link" title="add more" id="addmore3">
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
                                        <tr class="row10">
                                            <td>
                                                <div class=" form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                    <i class="fa fa-search search-icon"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">

                                                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                                                </div>
                                            </td>


                                            <td class="col-md-2">
                                                <div class="form-group ">
                                                    <select class="form-control" name="type" id="add_status_type">

                                                        <option value="2">Kg</option>
                                                        <option value="3">mm</option>
                                                        <option value="3">cm</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>  <div class="form-group">

                                                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                                                </div></td>
                                            <td class="shippingcolumn">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">

                                                    </div>

                                                </div>
                                            </td>
                                            <td>   <div class="form-group">

                                                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                                                </div></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label><b>Total Actual Quantity:</b> 500</label>
                            </div>
                            <div class="form-group">
                                <label for="vehicle_name"><b class="challan">Vehicle Name</b></label>
                                <input id="vehicle_name" class="form-control" placeholder="Vehicle Name" name="Discount" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="vehicle_name"><b class="challan">Discount</b></label>
                                <input id="vehicle_name" class="form-control" placeholder="Discount" name="Discount" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="driver_name"><b class="challan">Freight</b></label>
                                <input id="driver_name" class="form-control" placeholder="Freight " name="Freight" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="total"><b class="challan">Total</b> $15000</label>

                            </div>
                            <div class="form-group">
                                <label for="driver_contact"><b class="challan">Unloading</b></label>
                                <input id="driver_contact" class="form-control" placeholder="unloading" name="loading" value="" type="text">
                            </div>

                            <div class="form-group">
                                <label for="loadedby"><b class="challan">Unloaded By</b></label>
                                <input id="loadedby" class="form-control" placeholder="unloaded By" name="loadedby" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="labour"><b class="challan">Labour </b></label>
                                <input id="labour" class="form-control" placeholder="Labour" name="labour" value="" type="text">
                            </div>



                            <div class="form-group">

                                <label for="Plusvat"><b class="challan">Plus VAT</b> Yes/No</label>
                            </div>

                            <div class="form-group">
                                <label for="driver_contact"><b class="challan">VAT Percentage</b> 5%</label>


                                <!--
                            <div class="form-group">
                                <label for="driver_contact"><b class="challan">VAT</b></label>
   <input id="driver_contact" class="form-control" placeholder="VAT" name="VAT" value="" type="text">
</div>
                                <div class="form-group">
   <label for="grandtotal"><b class="challan">Grand Total</b></label>
   <input id="grandtotal" class="form-control" placeholder="Grand Total" name="grandtotal" value="" type="text">
</div>-->

                            </div>                     
                            <div class="form-group">
                                <label for="total"><b class="challan">Grand Total</b> $25000</label>

                            </div>
                            <div class="form-group">
                                <label for="billno"><b class="challan">Bill Number</b></label>
                                <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="inquiry_remark"><b class="challan">Remark</b></label>
                                <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                            </div>

                            <!--<button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->


                            <hr>

                            <div>
                                <button type="button" class="btn btn-primary" >Submit</button>

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


