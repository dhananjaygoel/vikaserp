@extends('layouts.master')
@section('title','Product Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('/')}}/product_category">Product Category</a></li>
                    <li class="active"><span>Add Product Category</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"> Add Product Category</h1>                   
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-lg-12">
                 
                <div class="main-box">
                   
                    <div class="main-box-body clearfix">
                    @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            {{Session::get('wrong')}}
                        </div>
                    @endif
                         @if (Session::has('flash_message'))
        <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('flash_message') }}</div>
        @endif
                        @if (count($errors->all()) > 0)
                        <div class="alert alert-warning">                        
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach                       
                        </div>
                        @endif 
                        <form  method="POST" action="{{URL::action('ProductController@store')}}"accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                            <div class="form-group">
                                <label>Product Category Type<span class="mandatory">*</span></label>                                
                                <div class="radio">

                                    @foreach($product_type as $prod_type)
                                    <input value="{{$prod_type->id}}" id="optionsRadios{{$prod_type->id}}" name="product_type" type="radio" {{(Input::old('product_type') == $prod_type->id)?'checked':''}}>
                                    <label for="optionsRadios{{$prod_type->id}}">{{$prod_type->name}}</label>
                                    @endforeach
                                </div>                               
                            </div>
                            <div class="form-group">
                                <label for="cat_name">Product Category Name<span class="mandatory">*</span></label>
                                <input id="cat_name" class="form-control" placeholder="Product Category Name" name="product_category_name" value="{{ old('product_category_name') }}" type="text">
                            </div>

                            <div class="form-group">
                                <label for="price">Price<span class="mandatory">*</span></label>
                                <input id="price" class="form-control" placeholder="Price" name="price" value="{{ old('price') }}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                            </div>

                            <div class="form-group">
                                <label for="thickness">Thickness Difference</label>
                                <input id="thickness" class="form-control" placeholder="Thickness" name="thickness" value="{{ old('thickness') }}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                            </div>

                            <div class="form-group" >
                                <label for="">HSN Code<span class="mandatory">*</span></label>
                                <select name="hsn_code" class="form-control" id="hsn_desc_get_desc" required>
                                    <option value="">Hsn Code</option>
                                    @foreach(\App\Hsn::orderBy('id','DESC')->get() as $hsn)
                                        <option value="{{$hsn->hsn_code.':'.$hsn->hsn_desc}}">{{$hsn->hsn_code}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="hsn_desc">HSN Description<span class="mandatory">*</span></label>
                                <textarea name="hsn_desc" id="hsn_desc" class="form-control">{{old('hsn_desc')}}</textarea>
                            </div>


                            <div>
                                <button type="button" class="btn btn-primary" id="sendSMS" data-id='product-id-submit-btn'>Send SMS</button>
                            </div>
                            <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" id='product-id-submit-btn' data-id='sendSMS'>Submit</button>
                                <a href="{{url('/')}}/product_category" class="btn btn-default form_button_footer">Back</a>
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