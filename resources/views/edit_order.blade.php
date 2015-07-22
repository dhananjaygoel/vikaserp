@extends('layouts.master')
@section('title','Edit Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('orders')}}">Orders</a></li>
                    <li class="active"><span>Edit Order</span></li>
                </ol>
            </div>
        </div>
        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        {!! Form::open(array('method'=>'PUT','url'=>url('orders',$order->id), 'id'=>'edit_order_form'))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="order_id" value="{{$order->id}}">
                        <input type="hidden" name="customer_id" value="{{$order['customer']->id}}" id="hidden_cutomer_id">
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
                            @if($order->order_source == 'warehouse')
                            <div class="radio">
                                <input checked="" value="warehouse" id="warehouse_radio" name="status" type="radio" onchange="show_hide_supplier($order - > order_source)">
                                <label for="warehouse_radio">Warehouse</label>
                                <input  value="supplier" id="supplier_radio" name="status" type="radio">
                                <label for="supplier_radio">Supplier</label>
                            </div>
                            <div class="supplier_order" style="display:none">
                                <select class="form-control" name="supplier_id" id="add_status_type">
                                    <option value="" selected="">Select supplier</option>
                                    @if(count($customers)>0)
                                    @foreach($customers as $customer)  
                                    <option value="{{$customer->id}}" >{{$customer->owner_name}}</option>                                       
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @elseif($order->order_source == 'supplier')
                            <div class="radio">
                                <input value="warehouse" id="warehouse_radio" name="status" type="radio">
                                <label for="warehouse_radio">Warehouse</label>
                                <input  checked="" value="supplier" id="supplier_radio" name="status" type="radio" onchange="show_hide_supplier($order - > order_source)">
                                <label for="supplier_radio">Supplier</label>
                            </div>
                            <div class="supplier_order">
                                <select class="form-control" name="supplier_id" id="add_status_type">
                                    <option value="" >Select supplier</option>
                                    @if(count($customers)>0)
                                    @foreach($customers as $customer)
                                    @if($customer->customer_status == 'permanent')
                                    @if($order['customer']->owner_name == $customer->owner_name)
                                    <option value="{{$customer->id}}" selected="selected">{{$customer->owner_name}}</option>
                                    @elseif($order['customer']->owner_name != $customer->owner_name)
                                    <option value="{{$customer->id}}" >{{$customer->owner_name}}</option>
                                    @endif
                                    @endif
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            @endif
                            <br/>                               
                            <div class="clearfix"></div>
                        </div>
                        @if($order['customer']->customer_status =="pending")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input value="existing_customer" id="existing_customer" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                <label for ="existing_customer">Existing</label>
                                <input checked="" value="new_customer" id="new_customer" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                <label for="new_customer">New</label>
                            </div>
                            <div class="customer_select" style="display: none">
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        <input class="form-control" placeholder="Enter Customer Name " type="text" name="existing_customer_name" id="existing_customer_name">
                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="" type="hidden">
                                        <i class="fa fa-search search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field">
                            <input type="hidden" id='pending_user_id' name="pending_user_id" value='{{$order['customer']->id}}'/>
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{$order['customer']->owner_name}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person</label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{$order['customer']->contact_person}}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Phone Number </label>
                                <input id="mobile_number" class="form-control" placeholder="Phone Number " name="mobile_number" value="{{$order['customer']->phone_number1}}" type="text">
                            </div>

                            <div class="form-group">
                                <label for="period">Credit Period(Days)</label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{$order['customer']->credit_period}}" type="text">
                            </div>
                        </div>
                        @elseif($order['customer']->customer_status == "permanent")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input checked="" value="existing_customer" id="optionsRadios1" name="customer_status" type="radio" onchange="show_hide_customer('Permanent');">
                                <label for="optionsRadios1">Existing</label>
                                <input  value="new_customer" id="optionsRadios2" name="customer_status" type="radio" onchange="show_hide_customer('Pending');">
                                <label for="optionsRadios2">New</label>
                            </div>
                            <div class="customer_select" >
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        <input class="form-control" placeholder="Enter Customer Name " type="text" value="{{$order['customer']->owner_name}}" id="existing_customer_name">
                                        <input id="existing_customer_id" class="form-control" name="existing_customer_name" value="{{$order['customer']->id}}" type="hidden">
                                        <i class="fa fa-search search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>

                        </div>
                        <div class="exist_field " style="display: none">
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="" type="text">
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
                                <label for="period">Credit Period(Days)</label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="" type="text">
                            </div>
                        </div>
                        @endif
                        <div class="row col-md-4">
                            <div class="form-group">
                                <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                <select class="form-control" name="add_inquiry_location" id="add_order_location">
                                    <option value="">Delivery Location</option>
                                    @foreach($delivery_location as $location)
                                    @if($order->delivery_location_id == $location->id)
                                    <option value="{{$location->id}}" selected="">{{$location->area_name}}</option>
                                    @else
                                    <option value="{{$location->id}}">{{$location->area_name}}</option>
                                    @endif
                                    @endforeach
                                    @if($order->delivery_location_id == 0)
                                    <option id="other_location" value="other" selected="">Other</option>
                                    @else
                                    <option id="other_location" value="other">Other</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        @if($order->delivery_location_id == 0)
                        <div class="locationtext" id="other_location_input_wrapper" style="display: block;">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="{{$order->other_location}}" type="text">
                                </div>
                                <div class="col-md-4">
                                    <label for="location">Other Location Difference</label>
                                    <input id="location_difference" class="form-control" placeholder="Location " name="other_location_difference" value="{{$order->other_location_difference}}" type="text">
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="locationtext" id="other_location_input_wrapper" style="display: none;">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="location">Location </label>
                                    <input id="location" class="form-control" placeholder="Location " name="other_location_name" value="" type="text">
                                </div>
                                <div class="col-md-4">
                                    <label for="location">Other Location Difference</label>
                                    <input id="location_difference" class="form-control" placeholder="Location " name="other_location_difference" value="" type="text">
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table" class="table table-hover  ">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Quantity</span></td>
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Price</span><span class="mandatory">*</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>
                                        @foreach($order['all_order_products'] as $key=>$product)
                                        @if($product->order_type =='order')
                                        <tr id="add_row_{{$key}}" class="add_product_row" data-row-id="{{$key}}">

                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" value="{{$product['order_product_details']->alias_name}}" onfocus="product_autocomplete({{$key}});">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}"  value="{{$product->product_category_id}}">
                                                    <input type="hidden" name="product[{{$key}}][order]" value="{{$product->id}}">
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
                                        @endif
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

                        <div class="clearfix"></div>
                        @if($order->vat_percentage == 0)
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
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$order->other_location}}" type="text"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @elseif($order->vat_percentage != 0)
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
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="{{$order->vat_percentage}}" type="text"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif 


                        <div class="clearfix"></div>
                        <div class="form-group col-md-4 targetdate">
                            <label for="date">Expected Delivery Date: </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="expected_date" class="form-control" id="expected_delivery_date_order" value="{{date('Y-m-d', strtotime($order->expected_delivery_date))}}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="order_remark">Remark:</label>
                            <textarea class="form-control" id="order_remark" name="order_remark"  rows="3">{{$order->remarks}}</textarea>
                        </div>

                        <div class="checkbox">
                            <label class="marginsms"><input type="checkbox" name="send_email" value=""><span class="checksms">Send Email</span></label>

                        </div>
                        <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" id="sendSMS" >Save and Send SMS</button> 
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