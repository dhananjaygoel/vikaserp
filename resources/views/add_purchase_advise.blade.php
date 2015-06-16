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
        <div  class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{url('purchaseorder_advise')}}" accept-charset="UTF-8" >
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
                                        <td class="cdfirst">Bill Date:</td>
                                        <td>
                                            <div class="targetdate">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input type="text" name="bill_date" class="form-control" id="datepickerDate" value="{{Input::old('bill_date')}}">
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group ">
                                <div class="radio">
                                    <input checked="" value="existing" id="optionsRadios1" name="supplier_status" type="radio">
                                    <label for="optionsRadios1">Existing Supplier</label>
                                    <input  value="new" id="optionsRadios3" name="supplier_status" type="radio">
                                    <label for="optionsRadios3">New Supplier</label>

                                </div>
                                <div class="supplier">
                                    <select class="form-control" name="supplier_id" id="add_status_type">
                                        <option value="" selected="">Select supplier</option>
                                        @if(count($customers))
                                        @foreach($customers as $c)
                                        <option value="{{$c->id}}">{{$c->owner_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="exist_field"  style="display:none">
                                <div class="form-group">
                                    <label for="supplier_name"> Supplier Name</label>
                                    <input id="name" class="form-control" placeholder="Supplier Name" name="supplier_name" value="" type="text">
                                </div>
                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number </label>
                                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="text">
                                </div>

                                <div class="form-group">
                                    <label for="credit_period">Credit Period</label>
                                    <input id="credit_period" class="form-control" placeholder="Credit Period" name="credit_period" value="" type="text">
                                </div>
                            </div>
                            <div class="inquiry_table col-md-12">
                                <div class="table-responsive">
                                    <table id="add_product_table" class="table table-hover  ">
                                        <tbody>
                                            <tr class="headingunderline">
                                                <td><span>Select Product</span></td>
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
                                                                <option value="" selected="">Unit</option>
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
                                    <label for="loc1">Delivery Location:</label>
                                    <select class="form-control" name="delivery_location_id" id="loc1">
                                        <option value="">Select delivery location</option>
                                        @if(count($locations))
                                        @foreach($locations as $l)
                                        <option value="{{$l->id}}">{{$l->area_name}}</option>
                                        @endforeach
                                        @endif
                                        <option id="other" value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="locationtext">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="location">Location </label>
                                        <input id="location" class="form-control" placeholder="Location " name="new_location" value="" type="text">
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
                                                <td><input id="price" class="form-control" placeholder="VAT Percentage" name="vat_percentage" value="" type="text"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div> 
                            <div class="form-group">

                                <label for="price">Total Price</label>
                                <input id="price" class="form-control" placeholder="Total Price" name="total_price" value="" type="text">

                            </div>
                            <div class="form-group">
                                <label for="cp">Vehicle Number </label>
                                <input id="cp" class="form-control" placeholder="Vehicle Name" name="vehicle_number" value="" type="text">

                            </div>
                            <div class="form-group col-md-4 targetdate">

                                <label for="date">Expected Delivery Date</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="expected_delivery_date" class="form-control" id="datepickerDate1">
                                </div>

                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="inquiry_remark">Remark</label>
                                <textarea class="form-control" id="inquiry_remark" name="remarks"  rows="3"></textarea>
                            </div>
                            <div >
                                <button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> 

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