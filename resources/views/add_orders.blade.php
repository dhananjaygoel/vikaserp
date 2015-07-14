@extends('layouts.master')
@section('title','Add Orders')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Place Order</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{URL::action('OrderController@store')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (Session::has('flash_message'))
                            <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('flash_message') }}</div>
                            @endif
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="warehouse" id="warehouse_radio" name="status" type="radio">
                                    <label for="warehouse_radio">Warehouse</label>
                                    <input  value="supplier" id="supplier_radio" name="status" type="radio">
                                    <label for="supplier_radio">Supplier</label>
                                </div>
                                <div class="supplier_order" style="display:none">
                                    <select class="form-control" name="supplier_id" id="add_status_type">
                                        <option value="" selected="">Select supplier</option>
                                        @if(count($customers)>0)
                                        @foreach($customers as $customer)
                                        @if($customer->customer_status == 'permanent')
                                        <option value="{{$customer->id}}" >{{$customer->owner_name}}</option>
                                        @endif
                                        @endforeach
                                        @endif


                                    </select>
                                </div>
                                <br/>
                                <label>Customer</label>
                                <div class="radio">
                                    <input checked="" value="existing_customer" id="existing_customer" name="customer_status" type="radio">
                                    <label for="existing_customer">Existing</label>
                                    <input  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                    <label for="new_customer">New</label>
                                </div>
                                <div class="customer_select_order" >
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                            <input class="form-control" placeholder="Enter Customer Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name">
                                            <input type="hidden" id="existing_customer_id" name="existing_customer_name">
                                            <input type="hidden" id="customer_default_location">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="new_customer_details" style="display: none">
                                <div class="form-group">
                                    <label for="name">Customer Name</label>
                                    <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{old('customer_name')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="name">Contact Person</label>
                                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{old('contact_person')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number </label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{old('mobile_number')}}" type="text">
                                </div>

                                <div class="form-group">
                                    <label for="period">Credit Period(Days)</label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{old('credit_period')}}" type="text">
                                </div>
                            </div>

                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label for="location">Delivery Location:</label>
                                    <select class="form-control" name="add_order_location" id="add_order_location">
                                        <option value="" selected="">Delivery Location</option>
                                        @foreach($delivery_locations as $delivery_location)
                                        @if($delivery_location->status=='permanent' && $delivery_location->id!=0)
                                        <option value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
                                        @endif
                                        @endforeach
                                        <option id="other_location" value="other">Other</option>                                        
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
                                    <div class="col-md-4">
                                        <label for="location">Other Location Difference</label>
                                        <input id="location_difference" class="form-control" placeholder="Other Location Difference " name="other_location_difference" value="" type="text">
                                    </div>
                                    <!--                                    <div class="col-md-8 addlocation">
                                                                            <button class="btn btn-primary btn-xs">ADD</button>
                                                                        </div>-->
                                </div>
                            </div>

                            <div class="order_table col-md-12">

                                <div class="table-responsive">
                                    <table id="add_product_table" class="table table-hover  ">
                                        <tbody> 
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span></td>
                                                <td><span>Price</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>

                                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                                                <tr id="add_row_{{$i}}" class="add_product_row">
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                            <input class="form-control" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="product_autocomplete({{$i}});">
                                                            <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="">
                                                            <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group">
                                                            <input id="quantity_{{$i}}" class="form-control" placeholder="Qnty" name="product[{{$i}}][quantity]" value="" type="text">
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group ">
                                                            <select class="form-control" name="product[{{$i}}][units]" id="units_{{$i}}">
                                                                @foreach($units as $unit)
                                                                <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group col-md-6">
                                                            <!--                                                            form for save product value-->
                                                            <input type="text" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price">

                                                        </div>
                                                        <div class="form-group col-md-6 difference_form">
                                                            <!--<input class="btn btn-primary" type="button" class="form-control" value="save" >-->     
                                                        </div>

                                                    </td>
                                                    <td class="col-md-4">
                                                        <div class="form-group">
                                                            <input id="remark" class="form-control" placeholder="Remark" name="product[{{$i}}][remark]" value="" type="text">
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>



                                        </tbody>
                                    </table>
                                    <table>
                                        <tbody>
                                            <tr class="row5">
                                                <td>
                                                    <div class="add_button1">
                                                        <div class="form-group pull-left">

                                                            <label for="addmore"></label>
                                                            <a class="table-link" title="add more" id="add_product_row">
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
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="include_vat" id="all_inclusive" name="status1" type="radio">
                                    <label for="all_inclusive">All Inclusive</label>
                                    <input value="exclude_vat" id="vat_inclusive" name="status1" type="radio">
                                    <label for="vat_inclusive">Plus VAT</label>
                                </div>
                            </div>
                            <div class="vat_field " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="price" class="form-control" placeholder="VAT Percentage" name="vat_price" value="" type="text"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 

                            <div class="clearfix"></div>
                            <div class="form-group col-md-4 targetdate">
                                <label for="time">Expected Delivery Date: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_date" value="{{Input::old('expected_date')!=''?Input::old('expected_date'):date('m-d-Y')}}" class="form-control" id="expected_delivery_date_order">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="order_remark">Remark</label>
                                <textarea class="form-control" id="order_remark" name="order_remark"  rows="3"></textarea>
                            </div>
                            <div class="checkbox">
                                <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email</span></label>

                            </div>
                            <div >
                                <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" id="sendSMS" >Save and Send SMS</button>
                            </div>
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>

                                <a href="{{url('orders')}}" class="btn btn-default form_button_footer">Back</a>
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