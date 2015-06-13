@extends('layouts.master')
@section('title','Add Delivery Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Add Delivery Order</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                </div>
            </div>
        </div>
        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <div class="form-group">
                            Date: 29 April, 2015
                        </div>   
                        <form method="POST" action="" accept-charset="UTF-8" >
                            <div class="form-group">
                                <label>Customer</label>
                                <div class="radio">
                                    <input  value="exist_customer" id="exist_customer" name="status" checked="" type="radio">
                                    <label for="exist_customer">Existing</label>
                                    <input  value="new_customer" id="new_customer" name="status" type="radio">
                                    <label for="new_customer">New</label>
                                </div>
                                <div class="customer_select" >
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                            <input class="form-control" placeholder="Enter Customer Name " type="text">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="exist_field" style="display: none">
                                <div class="form-group">
                                    <label for="name">Customer Name</label>
                                    <input id="name" class="form-control" placeholder="Name" name="name" value="" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">Contact Person</label>
                                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number </label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period</label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="period" value="" type="text">
                                </div>
                            </div>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="table-example" class="table table-hover  ">
                                        <tbody> 
                                            <tr class="headingunderline">
                                                <td><span>Select Product</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span></td>
                                                <td><span>Price</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-4">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
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
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
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
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
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
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="row11">
                                                <td>
                                                    <div class="add_button1">
                                                        <div class="form-group pull-left">
                                                            <label for="addmore"></label>
                                                            <a href="#" class="table-link" title="add more" id="addmore4">
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
                                            <tr class="row12">
                                                <td class="col-md-3">
                                                    <div class="form-group searchproduct">
                                                        <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                    </div>
                                                </td>
                                                <td class="col-md-1">
                                                    <div class="form-group">
                                                        <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
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
                                                <td class="col-md-2">
                                                    <div class="row">
                                                        <div class="form-group ">
                                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="col-md-2">
                                                    <div class="form-group">
                                                        <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="vehicle_name">Vehicle Name</label>
                                <input id="vehicle_name" class="form-control" placeholder="Vehicle Name" name="vehicle_name" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="driver_name">Driver Name</label>
                                <input id="driver_name" class="form-control" placeholder="Driver Name " name="driver_name" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="driver_contact">Driver Contact</label>
                                <input id="driver_contact" class="form-control" placeholder="Driver Contact" name="driver_contact" value="" type="text">
                            </div>
                            <div class="row col-md-4">  
                                <div class="form-group">
                                    <label for="location">Delivery Location:</label>
                                    <select class="form-control" id="loc1">
                                        <option>Location1</option>
                                        <option>Location2</option>
                                        <option id="other" value="3">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="locationtext">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location " name="location" value="" type="text">
                                    </div>
                                    <div class="col-md-8 addlocation">
                                        <button class="btn btn-primary btn-xs">ADD</button>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="include_vat" id="optionsRadios5" name="status1" type="radio">
                                    <label for="optionsRadios5">All Inclusive</label>
                                    <input value="exclude_vat" id="optionsRadios6" name="status1" type="radio">
                                    <label for="optionsRadios6">Plus VAT</label>
                                </div>
                            </div>
                            <div class="plusvat " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="price" class="form-control" placeholder="VAT Percentage" name="price" value="" type="text"></td>
                                            </tr>
                                           <!-- <tr class="cdtable">
                                                <td class="cdfirst">VAT:</td>
                                                <td>Lorem</td>
                                            </tr>
                                          
                                            <tr class="cdtable">
                                                <td class="cdfirst">Grand Total:</td>
                                                <td>650</td>
                                            </tr>-->
                                        </tbody>
                                    </table>
                                </div>
                            </div> 
                            <!--
                                  <div class="form-group col-md-4 targetdate">
                                       <label for="date">Expected Delivery Date </label>
                                 <div class="input-group">
                                 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                 <input type="text" name="date" class="form-control" id="datepickerDate1">
                                 </div>
                                     </div>-->
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="grandtotal">Grand Total:<span class="gtotal"> $25000</span></label>
                            </div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                            </div>
                            <div >
                                <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> 
                            </div>
                            <hr>
                            <div >
                                <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="delivery_orders.php" class="btn btn-default form_button_footer">Back</a>
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
@stop