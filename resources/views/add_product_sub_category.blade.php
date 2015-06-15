@extends('layouts.master')
@section('title','Add Product Sub Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/product_sub_category">Home</a></li>
                    <li class="active"><span>Add Product Sub Category</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"> Add Product Sub Category</h1>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">                    
                    <div class="main-box-body clearfix">

                        @if (count($errors) > 0)
                        <div class="alert alert-warning">                           
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach                        
                        </div>
                        @endif  

                        <form method="POST" action="{{URL::action('ProductsubController@store')}}" accept-charset="UTF-8" >
                            <div class="form-group productcategory col-md-3">
                                <input type="hidden" name="baseurl" id="baseurl2" value="{{url()}}" />
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <label for="status">Select Product Category</label>
                                <select class="form-control" name="product_type" id="product_type_select">
                                    <option disabled="" selected="" value="">--Select Product Category--</option>
                                    @foreach($product_type as $prod_type)
                                    <option value="{{$prod_type->id}}" id="product_type{{$prod_type->id}}"> {{$prod_type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group productcategory col-md-3">
                                <label for="status">Sub Product Name</label>
                                <select class="form-control" name="select_product_categroy" id="select_product_categroy">
                                    <option disabled="" selected="" value="">--Sub Product Name--</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="alias_name">Alias Name</label>
                                <input id="alias_name" class="form-control" placeholder="Sub Product Category Alias Name" name="alias_name" value="{{ old('alias_name') }}" type="text">
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label for="size">Product Size</label>
                                <input id="size" class="form-control" placeholder="Product Size" name="size" value="{{ old('size') }}" type="text">
                            </div>
                            <div class="thick">   
                                <div class="form-group ">
                                    <label for="thickness">Product Thickness</label>
                                    <input id="thickness" class="form-control" placeholder="Product Thickness" name="thickness" value="{{ old('thickness') }}" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="weight">Product Weight</label>
                                <input id="weight" class="form-control" placeholder="Product Weight" name="weight" value="{{ old('weight') }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="difference">Difference</label>
                                <input id="difference" class="form-control" placeholder=" Difference" name="difference" value="{{ old('difference') }}" type="text">
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary " >Send SMS</button>
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