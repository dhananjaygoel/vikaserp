@extends('layouts.master')
@section('title','Edit Delivery Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                    <li class="active"><span>Edit Delivery Order</span></li>
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
                        @if (Session::has('validation_message'))
                        <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('validation_message') }}</div>
                        @endif

                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-warning">                         
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach                            
                        </div>
                        @endif

                        <div class="form-group">
                            Date : {{date('d F, Y')}}
                        </div> 
                        {!!Form::open(array('method'=>'PUT','url'=>url('delivery_order/'.$delivery_data['id']),'id'=>'edit_delivery_order'))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        @if($delivery_data['customer']->customer_status =="pending")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input <?php if ($delivery_data['customer']->customer_status == 'permanent') echo 'checked=""'; ?> value="existing_customer" id="exist_customer" name="customer_status"  type="radio">
                                <label for="exist_customer">Existing</label>
                                <input <?php if ($delivery_data['customer']->customer_status == 'pending') echo 'checked=""'; ?>  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                <label for="new_customer">New</label>
                            </div>
                            <div class="customer_select" style="display: none">
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        <input class="form-control" placeholder="Enter Customer Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name">
                                        <input type="hidden" id="existing_customer_id" name="autocomplete_customer_id">
                                        <i class="fa fa-search search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field">
                            <input type="hidden" id='pending_user_id' name="pending_user_id" value='{{$delivery_data['customer']->id}}'/>
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="{{ $delivery_data['customer']->owner_name }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person<span class="mandatory">*</span></label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="{{ $delivery_data['customer']->contact_person }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="{{ $delivery_data['customer']->phone_number1 }}" type="tel">
                            </div>

                            <div class="form-group">
                                <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="{{ $delivery_data['customer']->credit_period }}" type="number">
                            </div>
                        </div>
                        @elseif($delivery_data['customer']->customer_status == "permanent")
                        <div class="form-group">
                            <label>Customer<span class="mandatory">*</span></label>
                            <div class="radio">
                                <input <?php if ($delivery_data['customer']->customer_status == 'permanent') echo 'checked=""'; ?> value="existing_customer" id="exist_customer" name="customer_status"  type="radio">
                                <label for="exist_customer">Existing</label>
                                <input <?php if ($delivery_data['customer']->customer_status == 'pending') echo 'checked=""'; ?>  value="new_customer" id="new_customer" name="customer_status" type="radio">
                                <label for="new_customer">New</label>
                            </div>
                            <div class="customer_select">
                                <div class="col-md-4">
                                    <div class="form-group searchproduct">
                                        <input value="{{ $delivery_data['customer']->owner_name }}-{{ $delivery_data['customer']->tally_name }}" class="form-control" placeholder="Enter Customer Name " type="text" id="existing_customer_name" autocomplete="off" name="existing_customer_name">
                                        <input type="hidden" value="{{ $delivery_data['customer']->id }}" id="existing_customer_id" name="autocomplete_customer_id">
                                        <i class="fa fa-search search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="exist_field" style="display: none">
                            <div class="form-group">
                                <label for="name">Customer Name<span class="mandatory">*</span></label>
                                <input id="name" class="form-control" placeholder="Name" name="customer_name" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Contact Person<span class="mandatory">*</span></label>
                                <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number <span class="mandatory">*</span></label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="tel">
                            </div>
                            <div class="form-group">
                                <label for="period">Credit Period(Days)<span class="mandatory">*</span></label>
                                <input id="period" class="form-control" placeholder="Credit Period" name="credit_period" value="" type="number">
                            </div>
                        </div>
                        @endif

                        <div class="row col-md-4">
                            <div class="form-group">
                                <label for="location">Delivery Location:<span class="mandatory">*</span></label>
                                <select class="form-control" name="add_order_location" id="add_order_location">
                                    <option value="" selected="">Delivery Location</option>
                                    @foreach($delivery_locations as $delivery_location)
                                    @if($delivery_location->status=='permanent' && $delivery_location->id!=0)
                                    <option <?php if ($delivery_location->id == $delivery_data->delivery_location_id) echo 'selected=""'; ?>  value="{{$delivery_location->id}}">{{$delivery_location->area_name}}</option>
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
                                <div class="form-group col-md-4">
                                    <label for="location">Other Location Difference</label>
                                    <input id="location_difference" class="form-control" placeholder="Other Location Difference " name="other_location_difference" value="" type="number">
                                </div>
                            </div>
                        </div>

                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_order" class="table table-hover">
                                    <tbody> 
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Quantity</span></td>
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Present Shipping</span></td>
                                            <td><span>Price</span><span class="mandatory">*</span></td>
                                            <td><span>Pending Quantity</span></td>
                                            <td><span>Remark</span></td>
                                        </tr>

                                        @foreach($delivery_data['delivery_product'] as $key=>$product)

                                        @if($product->order_type =='delivery_order')
                                        <tr id="add_row_{{$key}}" class="add_product_row" data-row-id="{{$key}}">
                                            <td class="col-md-3">
                                                <div class="form-group searchproduct">
                                                    <input value="{{ $product['order_product_details']->alias_name}}" class="form-control" placeholder="Enter Product name " type="hidden" name="product[{{$key}}][name]" id="add_product_name_{{$key}}" onfocus="product_autocomplete({{$key}});">
                                                    <input type="hidden" name="product[{{$key}}][product_category_id]" id="add_product_id_{{$key}}" value="{{$product['order_product_details']->id}}">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}" value="{{$product->id}}">
                                                    <input type="hidden" name="product[{{$key}}][order]" value="{{ $product->from}}">
<!--                                                    <i class="fa fa-search search-icon"></i>-->
                                                </div>{{ $product['order_product_details']->alias_name}}
                                                <input type="hidden" name="prod_id" value="{{$key}}">
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input id="quantity_{{$key}}" class="form-control" placeholder="Qnty" name="product[{{$key}}][quantity]" value="{{ $product->quantity}}" type="hidden">
                                                    {{ $product->quantity}}
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group ">
                                                    <select class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}">
                                                        @foreach($units as $unit)
                                                        @if($product->unit_id == $unit->id)
                                                        <option value="{{$unit->id}}" selected="">{{$unit->unit_name}}</option>                                                        
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group col-md-6">
                                                    <!--                                                            form for save product value-->
                                                    <input type="number" class="form-control" id="present_shipping_{{$key}}" value="{{$product->present_shipping}}" name="product[{{$key}}][present_shipping]" placeholder="Present Shipping" onblur="change_quantity2({{$key}});">
                                                </div>
                                                <div class="form-group col-md-6 difference_form">
                                                    <!--<input class="btn btn-primary" type="button" class="form-control" value="save" >-->     
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group col-md-6">
                                                    <!--                                                            form for save product value-->
                                                    <input type="text" class="form-control" id="product_price_{{$key}}" value="{{$product->price}}" name="product[{{$key}}][price]" placeholder="Price">

                                                </div>
                                                <div class="form-group col-md-6 difference_form">
                                                    <!--<input class="btn btn-primary" type="button" class="form-control" value="save" >-->     
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">

                                                    @foreach($pending_orders as $porder)
                                                    @if($porder['product_id'] == $product->product_category_id && $porder['id']== $product->id)
                                                    <input type="hidden" value="{{$porder['total_pending_quantity']}}" id="pending_qunatity_value_{{$key}}">
                                                    <div id="pending_qunatity_{{$key}}"><span class="text-center">{{$porder['total_pending_quantity']}}</span>
                                                    </div>
                                                    @endif
                                                    @endforeach
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
                                        <?php //}  ?>
                                    </tbody>
                                </table>
                                <table>
                                    <tbody>
                                        <tr class="row5">
                                            <td>
                                                <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                        <label for="addmore"></label>
                                                        <a class="table-link" title="add more" id="add_product_row_delivery_order">
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
                            <label for="vehicle_name">Vehicle Number</label>
                            <input id="vehicle_number" class="form-control" placeholder="Vehicle Number" name="vehicle_number" value="{{ $delivery_data->vehicle_number }}" type="text">
                        </div>

                        <div class="form-group">
                            <label for="driver_contact">Driver Contact</label>
                            <input id="driver_contact" class="form-control" placeholder="Driver Contact" name="driver_contact" value="{{ $delivery_data->driver_contact_no }}" type="tel">
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group">
                            <div class="radio">
                                <input checked="" value="include_vat" id="optionsRadios5" name="status1" type="radio" onclick="grand_total_delivery_order();">
                                <label for="optionsRadios5">All Inclusive</label>
                                <input value="exclude_vat" id="optionsRadios6" name="status1" type="radio" onclick="grand_total_delivery_order();" <?php
                                if ($delivery_data->vat_percentage != '') {
                                    echo 'checked=""';
                                }
                                ?>>
                                <label for="optionsRadios6">Plus VAT</label>
                            </div>
                        </div>
                        <?php
                        if ($delivery_data->vat_percentage != '') {
                            $style = "display:block";
                        } else {
                            $style = '';
                        }
                        ?>
                        <div class="plusvat " style="<?= $style ?>">
                            <div class="form-group">
                                <table id="table-example" class="table ">
                                    <tbody>
                                        <tr class="cdtable">
                                            <td class="cdfirst">VAT Percentage:</td>
                                            <td><input id="vat_percentage" class="form-control" placeholder="VAT Percentage" name="vat_price" value="{{ $delivery_data->vat_percentage }}" type="number" onblur="grand_total_delivery_order({{$key}});"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                        <div class="clearfix"></div>

                        <div class="form-group">
                            <label for="inquiry_remark">Remark</label>
                            <textarea class="form-control" id="order_remark" name="order_remark"  rows="3">{{ $delivery_data->remarks }}</textarea>
                        </div>
                        <div >
                            <!--<button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button>--> 
                        </div>
                        <hr>
                        <div >
                            <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                            <a href="{{url('delivery_order')}}" class="btn btn-default form_button_footer">Back</a>
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