@extends('layouts.master')
@section('title','Add Product Sub Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('product_sub_category')}}">Product Sizes</a></li>
                    <li class="active"><span>Add Product Size</span></li>
                </ol>
                <div class="clearfix">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">                    
                    <div class="main-box-body clearfix">
                        @if (count($errors) > 0)
                        <div class="alert alert-warning"> 
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach                        
                        </div>
                        @endif 
                        <form  method="POST" action="{{URL::action('ProductsubController@store')}}" accept-charset="UTF-8" >
                            <input type="hidden" name="units" id="units" value="{{$units->id}}">
                            <div class="form-group productcategory col-md-3">
                                <input type="hidden" name="baseurl" id="baseurl2" value="{{url()}}" />
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <label for="status">Select Product Category<span class="mandatory">*</span></label>
                                <select class="form-control" name="product_type" id="product_sub_category_select">
                                    <option disabled="" selected="" value="">--Select Product Category--</option>
                                    @foreach($product_type as $prod_type)
                                    <option value="{{$prod_type->id}}" id="product_type{{$prod_type->id}}" {{(Input::old('product_type') == $prod_type->id)?'selected':''}}> {{$prod_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group productcategory col-md-3">
                                <label for="status">Sub Product Name<span class="mandatory">*</span></label>
                                <select class="form-control" name="select_product_categroy" id="select_product_categroy">
                                    <option disabled="" selected="" value="">--Sub Product Name--</option>
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="alias_name">Alias Name<span class="mandatory">*</span></label>
                                <input id="alias_name" class="form-control" placeholder="Alias Name" name="alias_name" value="{{ old('alias_name') }}" type="text">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="hsn_code">HSN Code<span class="mandatory">*</span></label>
                                <input id="hsn_code" class="form-control" placeholder="HSN Code" name="hsn_code" value="{{ old('hsn_code') }}" onkeypress=" return numbersOnly(this, event, false, false);" type="text">
                            </div>
                            <div class="form-group">
                                <label for="size">Product Size<span class="mandatory">*</span></label>
                                <input id="size" class="form-control" placeholder="Product Size" name="size" value="{{ old('size') }}" type="text">
                            </div>
                            <div class="thick12" style="display: none;">   
                                <div class="form-group ">
                                    <label for="thickness">Product Thickness</label>
                                    <input id="thickness" class="form-control" placeholder="Product Thickness" name="thickness" value="{{ old('thickness') }}" type="text">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="weight">Product Weight<span class="mandatory">*</span></label>
                                <input id="weight" class="form-control" placeholder="Product Weight" name="weight" value="{{ old('weight') }}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="difference">Standard Length<span class="mandatory">*</span></label>
                                <input id="standard_length" class="form-control" placeholder=" Standard Length" name="standard_length" value="{{ old('standard_length') }}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                            </div>
                            <div class="form-group">
                                <label for="difference">Difference<span class="mandatory">*</span></label>
                                <input id="difference" class="form-control" placeholder=" Difference" name="difference" value="{{ old('difference') }}" type="tel" onkeypress=" return numbersOnly(this,event,true,true);">
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary " id="sendSMS" >Send SMS</button>
                            </div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url()}}/product_sub_category" class="btn btn-default form_button_footer">Back</a>
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