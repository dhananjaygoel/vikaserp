@extends('layouts.master')
@section('title','Product Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}">Home</a></li>
                    <li class="active"><span>Add Product Category</span></li>
                </ol>
                <div class="clearfix">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    @if (count($errors) > 0)
                    <div class="alert alert-warning">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif   
                    <div class="main-box-body clearfix">
                        <form method="POST" action="{{URL::action('ProductController@store')}}"accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label>Product Category Type</label>                                
                                <div class="radio">
                                    
                                    @foreach($product_type as $prod_type)
                                    <input value="{{$prod_type->id}}" id="optionsRadios{{$prod_type->id}}" name="product_type" type="radio">
                                    <label for="optionsRadios{{$prod_type->id}}">{{$prod_type->name}}</label>
                                    @endforeach                      

                                </div>                               
                            </div>
                            <div class="form-group">
                                <label for="cat_name">Sub Product Category Name</label>
                                <input id="cat_name" class="form-control" placeholder="Sub Product Category Name" name="product_category_name" value="{{ old('product_category_name') }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input id="price" class="form-control" placeholder="Price" name="price" value="{{ old('price') }}" type="text">
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary " >Send SMS</button>
                            </div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url()}}/product_category" class="btn btn-default form_button_footer">Back</a>
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