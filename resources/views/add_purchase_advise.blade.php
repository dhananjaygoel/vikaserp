@extends('layouts.master')
@section('title','Add Purchase Advise Independently')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchaseorder_advise')}}">Purchase Advise</a></li>
                    <li class="active"><span>Create Purchase Advice</span></li>
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
                        <form id="onenter_prevent" method="POST" action="{{url('purchaseorder_advise')}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif
                            <table id="table-example" class="table ">
                                <tbody>
                                    <tr class="cdtable">
                                        <td class="cdfirst">Bill Date:<span class="mandatory">*</span></td>
                                        <td>
                                            <div class="targetdate">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="bill_date" class="form-control" id="bill_date" value="{{Input::old('bill_date')!=''?Input::old('bill_date'):date('m-d-Y')}}">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group ">
                                <div class="radio">
                                    <input checked="" value="existing" id="optionsRadios1" name="supplier_status" type="radio"
                                    <?php
                                    if (Input::old('supplier_status') == "existing") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="optionsRadios1">Existing Supplier</label>
                                    <input  value="new" id="optionsRadios3" name="supplier_status" type="radio"
                                    <?php
                                    if (Input::old('supplier_status') == "new") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="optionsRadios3">New Supplier</label>
                                </div>
                                <?php
                                if (Input::old('supplier_status') == "new") {
                                    $style = 'style="display: none"';
                                } else {
                                    $style = 'style="display: block"';
                                }
                                ?>
                                <div class="supplier" <?= $style ?>>
                                    <select class="form-control" name="supplier_id" id="supplier_select" onchange="get_default_location();">
                                        <option value="" selected="">Select supplier</option>
                                        @if(count($customers))
                                        @foreach($customers as $c)
                                        <option value="{{$c->id}}" default_location="{{$c->delivery_location_id}}">{{$c->owner_name.'-'.$c->tally_name}}</option>
                                        @endforeach
                                        @endif
                                        <input type="hidden" id="customer_default_location">
                                    </select>
                                </div>
                            </div>
                            <?php
                            if (Input::old('supplier_status') == "new") {
                                $style = 'style="display: block"';
                            } else {
                                $style = 'style="display: none"';
                            }
                            ?>
                            <div class="exist_field" <?= $style ?>>
                                <div class="form-group">
                                    <label for="supplier_name"> Supplier Name<span class="mandatory">*</span></label>
                                    <input id="name" class="form-control" placeholder="Supplier Name" name="supplier_name" value="{{ Input::old('supplier_name') }}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{ Input::old('mobile_number') }}" type="tel">
                                </div>

                                <div class="form-group">
                                    <label for="credit_period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="credit_period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{ Input::old('credit_period') }}" type="number">
                                </div>
                            </div>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="add_product_table" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)<span class="mandatory">*</span></span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Price</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                                                <tr id="add_row_{{$i}}" class="add_product_row">
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                            <input class="form-control" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_product_name_{{$i}}" onfocus="purchase_order_advise_product_autocomplete({{$i}});">
                                                            <input type="hidden" name="product[{{$i}}][id]" id="add_product_id_{{$i}}" value="">
                                                            <input type="hidden" name="product[{{$i}}][purchase]" value="">
                                                            <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <div class="form-group">
                                                            <input id="quantity_{{$i}}" class="form-control" placeholder="Qnty" name="product[{{$i}}][quantity]" value="" type="number">
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
                                                            <a class="table-link" title="add more" id="add_purchase_advise_product_row">
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


                            <div class="row col-md-4">  
                                <div class="form-group">
                                    <label for="loc1">Delivery Location:</label>
                                    <select class="form-control" name="delivery_location_id" id="purchase_other_location">
                                        <option value="" selected="" disabled="">--Delivery Location--</option>
                                        @foreach($delivery_locations as $delivery_location)
                                        @if($delivery_location->status == 'permanent')
                                        <option value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
                                        @endif
                                        @endforeach
                                        <option value="-1">Other</option>
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
                                        <input id="location_difference" class="form-control" placeholder="Location " name="other_location_difference" value="" type="number">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row col-md-4">  
                                <div class="form-group">
                                    <label for="orderfor">Order For:</label>
                                    <select class="form-control" id="orderfor" name="order_for">
                                        <option value="0">Warehouse</option>
                                        @if(count($customers))
                                        @foreach($customers as $c)
                                        <option value="{{$c->id}}">{{$c->owner_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">

                                <div class="radio">
                                    <input checked="" value="include_vat" id="optionsRadios5" name="is_vat" type="radio">
                                    <label for="optionsRadios5">All Inclusive</label>
                                    <input value="exclude_vat" id="optionsRadios6" name="is_vat" type="radio">
                                    <label for="optionsRadios6">Plus VAT</label>
                                </div>
                            </div>
                            <div class="plusvat " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">


                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="price" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="" type="number"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div> 
                            <!--<div class="form-group">-->

                            <!--<label for="price">Total Price</label>-->
                            <input id="price" class="form-control" placeholder="Total Price" name="total_price" value="" type="hidden">

                            <!--</div>-->
                            <div class="form-group">
                                <label for="cp">Vehicle Number <span class="mandatory">*</span></label>
                                <input id="cp" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{ Input::old('vehicle_number') }}" type="text">

                            </div>
                            <div class="form-group col-md-4 targetdate">

                                <label for="date">Expected Delivery Date<span class="mandatory">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_delivery_date" class="form-control" id="datepickerDate1" value="{{Input::old('expected_delivery_date')!=''?Input::old('expected_delivery_date'):date('m-d-Y')}}">
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="inquiry_remark" name="remarks"  rows="3"></textarea>
                            </div>
                            <div >
                            </div>
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('purchaseorder_advise')}}" class="btn btn-default form_button_footer">Back</a>
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