@extends('layouts.master')
@section('title','Create Purchase Orders')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('purchase_orders')}}">Purchase Order</a></li>
                    <li class="active"><span>Add Purchase Order</span></li>
                </ol>
            </div>
        </div>
        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form id="onenter_prevent" method="POST" action="{{URL::action('PurchaseOrderController@store')}}">
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
                            <div class="form-group ">
                                <div class="radio superadmin">
                                    <input checked="" value="0" id="admin_and_superadmin" name="viewable_by" type="radio"
                                    <?php
                                    if (Input::old('viewable_by') == "0") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="admin_and_superadmin">Viewable to Admin and Superadmin</label>
                                    <input  value="1" id="viewable_by_all" name="viewable_by" type="radio"
                                    <?php
                                    if (Input::old('viewable_by') == "1") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="viewable_by_all">Viewable by all</label>
                                </div>
                                <div class="radio">
                                    <input checked="" value="existing_supplier" id="existing_supplier" name="supplier_status" type="radio"
                                    <?php
                                    if (Input::old('supplier_status') == "existing_supplier") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="existing_supplier">Existing Supplier</label>
                                    <input value="new_supplier" id="new_supplier" name="supplier_status" type="radio"
                                    <?php
                                    if (Input::old('supplier_status') == "new_supplier") {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="new_supplier">New Supplier</label>
                                </div>

                                <?php
                                if (Input::old('supplier_status') == "new_supplier") {
                                    $style = 'style="display: none"';
                                } else {
                                    $style = 'style="display: block"';
                                }
                                ?>

                                <div class="supplier customer_select" <?= $style ?>>
                                    <div class="col-md-12">
                                        <div class="form-group searchproduct">
                                            <input class="form-control" placeholder="Enter Supplier Name " type="text" name="existing_supplier_name" id="existing_supplier_name">
                                            <input type="hidden" id="existing_supplier_id" name="autocomplete_supplier_id">
                                            <input type="hidden" id="customer_default_location">
                                            <i class="fa fa-search search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (Input::old('supplier_status') == "new_supplier") {
                                $style = 'style="display: block"';
                            } else {
                                $style = 'style="display: none"';
                            }
                            ?>
                            <div class="exist_field" <?= $style ?>>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label for="name"> Supplier Name<span class="mandatory">*</span></label>
                                    <input id="name" class="form-control" placeholder="Supplier Name" name="supplier_name" value="{{Input::old('supplier_name')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{Input::old('mobile_number')}}" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                    <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{Input::old('credit_period')}}" type="text">
                                </div>
                            </div>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="add_product_table_purchase" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                                <td><span>Quantity</span></td>
                                                <td><span>Unit</span><span class="mandatory">*</span></td>
                                                <td><span>Price</span></td>
                                                <td><span>Remark</span></td>
                                            </tr>
                                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                                                <tr id="add_row_{{$i}}" class="add_product_row">
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                            <input class="form-control" placeholder="Enter Product name " type="text" name="product[{{$i}}][name]" id="add_purchase_product_name_{{$i}}" onfocus="product_autocomplete_purchase({{$i}});">
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
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                    <select class="form-control" name="purchase_order_location" id="add_inquiry_location">
                                        <option value="" selected="">Delivery Location</option>
                                        @foreach($delivery_locations as $delivery_location)
                                        @if($delivery_location->status == 'permanent')
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
                                        <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{Input::old('other_location_name')}}" type="text">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="location">Other Location Difference</label>
                                        <input id="location_difference" class="form-control" placeholder="Location " name="other_location_difference" value="{{Input::old('other_location_difference')}}" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row col-md-4">
                                <div class="form-group">
                                    <label for="orderfor">Order For:</label>
                                    <select class="form-control" id="orderfor" name="order_for">
                                        <option value="0">Warehouse</option>
                                        @foreach($customers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->owner_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="radio">
                                    <input checked="" value="include_vat" id="inclusive_of_vat" name="vat_status" type="radio">
                                    <label for="inclusive_of_vat">All Inclusive</label>
                                    <input value="exclude_vat" id="exclusive_of_vat" name="vat_status" type="radio">
                                    <label for="exclusive_of_vat">Plus VAT</label>
                                </div>
                            </div>
                            <div class="plusvat " style="display: none">
                                <div class="form-group">
                                    <table id="table-example" class="table ">
                                        <tbody>
                                            <tr class="cdtable">
                                                <td class="cdfirst">VAT Percentage:</td>
                                                <td><input id="price" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{Input::old('vat_percentage')}}" type="text"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-md-4 targetdate">
                                <label for="time">Expected Delivery Date:<span class="mandatory">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_delivery_date" class="form-control" id="datepickerDate" value="{{Input::old('expected_delivery_date')!=''?Input::old('expected_delivery_date'):date('m-d-Y')}}" >
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark:</label>
                                <textarea class="form-control" id="inquiry_remark" name="purchase_order_remark" rows="3"></textarea>
                            </div>
                            <div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="" name="send_email"><span class="checksms">Send Email to Party</span></label>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" id="sendSMS" >Save and Send SMS</button>
                                <a href="{{URL::to('purchase_orders')}}" class="btn btn-default form_button_footer">Back</a>
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