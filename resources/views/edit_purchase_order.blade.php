@extends('layouts.master')
@section('title','Edit Purchase Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Edit Purchase Order</span></li>
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
                        {!! Form::open(array('method'=>'PUT','url'=>url('purchase_orders',$purchase_order->id), 'id'=>'edit_purchase_order_form'))!!}
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
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="purchase_order_id" value="{{$purchase_order->id}}">
                        <input type="hidden" name="supplier_id" value="{{$purchase_order['customer']->id}}" id="hidden_supplier_id">
                        <div class="form-group ">
                            @if($purchase_order->is_view_all =="0")
                            <div class="radio superadmin">
                                <input checked="" value="0" id="admin_and_superadmin" name="viewable_by" type="radio">
                                <label for="admin_and_superadmin">Viewable to Admin and Superadmin</label>
                                <input  value="1" id="viewable_by_all" name="viewable_by" type="radio">
                                <label for="viewable_by_all">Viewable by all</label>
                            </div>
                            @elseif($purchase_order->is_view_all =="1")
                            <div class="radio superadmin">
                                <input value="0" id="admin_and_superadmin" name="viewable_by" type="radio">
                                <label for="admin_and_superadmin">Viewable to Admin and Superadmin</label>
                                <input checked="" value="1" id="viewable_by_all" name="viewable_by" type="radio">
                                <label for="viewable_by_all">Viewable by all</label>
                            </div>
                            @endif
                            @if($purchase_order['customer']->customer_status =="pending")
                            <div class="radio">
                                <input value="existing_supplier" id="existing_supplier" name="supplier_status" type="radio">
                                <label for="existing_supplier">Existing Supplier</label>
                                <input  checked="" value="new_supplier" id="new_supplier" name="supplier_status" type="radio">
                                <label for="new_supplier">New Supplier</label>
                            </div>
                            <div class="supplier customer_select" style="display:none">
                                <div class="col-md-12">
                                    <div class="form-group searchproduct">
                                        <input class="form-control" placeholder="Enter Supplier Name " type="text" name="existing_supplier_name" id="existing_supplier_name">
                                        <input type="hidden" id="existing_supplier_id" name="autocomplete_supplier_id" value="{{$purchase_order['customer']->id}}">
                                        <i class="fa fa-search search-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="exist_field">
                            <div class="form-group">
                                <label for="name"> Supplier Name</label>
                                <input id="name" class="form-control" placeholder="Supplier Name" name="supplier_name" value="{{$purchase_order['customer']->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number </label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{$purchase_order['customer']->mobile_number}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="period">Credit Period</label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{$purchase_order['customer']->credit_period}}" type="text">
                            </div>
                        </div>
                        @elseif($purchase_order['customer']->customer_status =="permanent")
                        <div class="radio">
                            <input checked="" value="existing_supplier" id="existing_supplier" name="supplier_status" type="radio">
                            <label for="existing_supplier">Existing Supplier</label>
                            <input   value="new_supplier" id="new_supplier" name="supplier_status" type="radio">
                            <label for="new_supplier">New Supplier</label>
                        </div>
                        <div class="supplier customer_select">
                            <div class="col-md-12">
                                <div class="form-group searchproduct">
                                    <input class="form-control" placeholder="Enter Supplier Name " type="text" name="existing_supplier_name" id="existing_supplier_name" value="{{$purchase_order['customer']->owner_name}}">
                                    <input type="hidden" id="existing_supplier_id" name="autocomplete_supplier_id" value="{{$purchase_order['customer']->id}}">
                                    <i class="fa fa-search search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exist_field"  style="display:none">
                        <div class="form-group">
                            <label for="name"> Supplier Name</label>
                            <input id="name" class="form-control" placeholder="Supplier Name" name="supplier_name" value="{{$purchase_order['customer']->owner_name}}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="mobile_number">Mobile Number </label>
                            <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{$purchase_order['customer']->mobile_number}}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="period">Credit Period</label>
                            <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{$purchase_order['customer']->credit_period}}" type="text">
                        </div>
                    </div>
                    @endif
                    <div class="inquiry_table col-md-12" >
                        <div class="table-responsive">
                            <table id="add_product_table" class="table table-hover">
                                <tbody>
                                    <tr class="headingunderline">
                                        <td><span>Select Product</span></td>
                                        <td><span>Quantity</span></td>
                                        <td><span>Unit</span></td>
                                        <td><span>Price</span></td>
                                        <td><span>Remark</span></td>
                                    </tr>
                                    @foreach($purchase_order['purchase_products'] as $key=>$product)
                                    <tr id="add_row_{{$key}}" class="add_product_row">
                                        <td class="col-md-3">
                                            <div class="form-group searchproduct">
                                                <input class="form-control" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" value="{{$product['product_category']->product_category_name}}">
                                                <input type="hidden" name="product[{{$key}}][id]" value="{{$product->product_category_id}}">
                                                <i class="fa fa-search search-icon"></i>
                                            </div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group">
                                                <input id="quantity_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][quantity]" value="{{$product->quantity}}" type="text">
                                            </div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group ">
                                                <select class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}">
                                                    <option value="">Unit</option>
                                                    @foreach($units as $unit)
                                                    @if($product->unit_id == $unit->id)
                                                    <option value="{{$unit->id}}" selected="">{{$unit->unit_name}}</option>
                                                    @else
                                                    <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group">
                                                <input type="text" class="form-control" value="{{$product->price}}" id="product_price_{{$key}}" name="product[{{$key}}][price]">
                                            </div>
                                        </td>
                                        <td class="col-md-4">
                                            <div class="form-group">
                                                <input id="remark" class="form-control" placeholder="Remark" name="product[{{$key}}][remark]" value="{{$product->remarks}}" type="text">
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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
                            <label for="location">Delivery Location:</label>
                            <select class="form-control" name="purchase_order_location" id="add_inquiry_location">
                                <option value="" selected="">Delivery Location</option>
                                @foreach($delivery_locations as $delivery_location)
                                @if($purchase_order->delivery_location_id == $delivery_location->id)
                                <option value="{{$delivery_location->id}}" selected="">{{$delivery_location->area_name}}</option>
                                @else
                                <option value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
                                @endif
                                @endforeach
                                @if($purchase_order->delivery_location_id == 0)
                                <option id="other_location" value="other" selected="">Other</option>
                                @else
                                <option id="other_location" value="other">Other</option>
                                @endif
                                <option id="other_location" value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    @if($purchase_order->delivery_location_id == 0)
                    <div class="locationtext" id="other_location_input_wrapper" style="display: block;">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="location">Location </label>
                                <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{$purchase_order->other_location}}" type="text">
                            </div>
                        </div>
                    </div>
                    @endif
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
                    @if($purchase_order->vat_percentage == 0)
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
                    @elseif($purchase_order->vat_percentage != 0)
                    <div class="form-group">
                        <div class="radio">
                            <input value="include_vat" id="optionsRadios3" name="vat_status" type="radio">
                            <label for="optionsRadios3">All Inclusive</label>
                            <input checked="" value="exclude_vat" id="optionsRadios4" name="vat_status" type="radio">
                            <label for="optionsRadios4">Plus VAT</label>
                        </div>
                    </div>

                    <div class="plusvat">
                        <div class="form-group">
                            <table id="table-example" class="table ">
                                <tbody>
                                    <tr class="cdtable">
                                        <td class="cdfirst">VAT Percentage:</td>
                                        <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$purchase_order->vat_percentage}}" type="text"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <div class="form-group col-md-4 targetdate">
                        <label for="date">Expected Delivery Date: </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" name="expected_delivery_date" class="form-control" id="expected_delivery_date" value="{{date('m-d-Y', strtotime($purchase_order->expected_delivery_date))}}" >
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="form-group">
                        <label for="inquiry_remark">Remark</label>
                        <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3">{{$purchase_order->remarks}}</textarea>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" value=""><span class="checksms">Send Email to Party</span></label>
                    </div>
                    <hr>
                    <div>
                        <input type="submit" title="SMS would be sent to Party" class="btn btn-primary smstooltip" value="Save and Send SMS">
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