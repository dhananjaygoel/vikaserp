@extends('layouts.master')
@section('title','Add Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('inquiry')}}">Inquiry</a></li>
                    <li class="active"><span>Add Inquiry</span></li>
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
                        <form method="POST" action="{{URL::action('InquiryController@store')}}">
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
                                <label>Customer</label>
                                <div class="radio">
                                    <input checked="" value="existing_customer" id="existing_customer" name="customer_status" type="radio">
                                    <label for="existing_customer">Existing</label>
                                    <input  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                    <label for="new_customer">New</label>
                                </div>
                                <div class="customer_select" >
                                    <div class="col-md-4">
                                        <div class="form-group searchproduct">
                                            <input class="form-control" placeholder="Enter Customer Name" type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name">
                                            <input type="hidden" id="existing_customer_id" name="existing_customer_name">
                                            <input type="hidden" id="customer_default_location">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>

<!--<p style>Please select the Customer name</p>-->
                                    </div>

                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="exist_field " style="display: none">
                                <div class="form-group">
                                    <label for="name">Customer Name</label>
                                    <input id="customer_name" class="form-control" placeholder="Name" name="customer_name" value="{{ old('customer_name') }}" type="text">
<!--                                    <input id="customer_id" class="form-control" name="existing_customer_id" value="" type="hidden">-->
                                </div>
                                <div class="form-group">
                                    <label for="name">Contact Person</label>
                                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ old('contact_person') }}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number </label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number" name="mobile_number" value="{{ old('mobile_number') }}" type="text">
                                </div>

                                <div class="form-group">
                                    <label for="period">Credit Period(Days)</label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{ old('credit_period') }}" type="text">
                                </div>
                            </div>
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label for="location">Delivery Location:</label>
                                    <select class="form-control" name="add_inquiry_location" id="add_order_location">
                                        <option value="" selected="" disabled="">Delivery Location</option>
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
                            <div class="locationtext" id="other_location_input_wrapper">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="" type="text">

                                    </div>
                                    <div class="col-md-4">
                                        <label for="location">Other Location Difference</label>
                                        <input id="location_difference" class="form-control" placeholder="Other Location Difference " name="other_location_difference" value="" type="text">
                                    </div>
                                </div>
                            </div>


                            <div class="inquiry_table col-md-12">
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
                                            <?php for ($i = 1; $i <= 3; $i++) { ?>
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
                                                            <select class="form-control" name="product[{{$i}}][units]" id="units_{{$i}}" onchange="calulate_price(<?php echo $i; ?>);">
                                                                <!--                                                                <option value="" selected="">Unit</option>-->
                                                                @foreach($units as $unit)
                                                                <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="product_price_{{$i}}" name="product[{{$i}}][price]" placeholder="Price">
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
                                    <input checked="" value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                                    <label for="optionsRadios3">All Inclusive</label>
                                    <input value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                                    <label for="optionsRadios4">Plus VAT</label>
                                </div>
                            </div>

                            <div class="plusvat " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="" type="text"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-md-4 targetdate">
                                <label for="date">Expected Delivery Date: </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_date" class="form-control" id="expected_delivery_date">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                            </div>

                            <button type="button" class="btn btn-primary" id="sendSMS" >Save and Send SMS</button>


                            <hr>
                            <div>
                                <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                                <a href="{{URL::to('inquiry')}}" class="btn btn-default form_button_footer">Back</a>
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