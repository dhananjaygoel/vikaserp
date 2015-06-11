@extends('layouts.master')
@section('title','Edit Product Sub Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Edit Product Sub Category</span></li>
                </ol>
                <div class="clearfix"></div>
                <div class="clearfix">
                    <h1 class="pull-left"> Edit Product Sub Category</h1>                   
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
                            {{ $error }}
                            @endforeach
                        </div>
                        @endif 

                        {!!Form::open(array('method'=>'PUT','url'=>url('product_sub_category/'.$prod_sub_cat[0]->id),'id'=>'updateUserForm'))!!}

                        <div class="form-group productcategory col-md-3">
                            <input type="hidden" name="baseurl" id="baseurl2" value="{{url()}}" />
                            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                            <label for="status">Select Product Category</label>
                            <select class="form-control" name="product_type" id="product_type_select">
                                <option disabled="" selected="" value="">--Select Product Category--</option>
                                @foreach($product_type as $prod_type)
                                <option <?php if ($prod_sub_cat[0]['product_category']->product_type_id == $prod_type->id) echo 'selected="selected"'; ?>  value="{{$prod_type->id}}" id="product_type{{$prod_type->id}}"> {{$prod_type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group productcategory col-md-3">
                            <label for="status">Sub Product Name</label>
                            <select class="form-control" name="select_product_categroy" id="select_product_categroy">
                                <option disabled="" value="">--Sub Product Name--</option>
                                <option selected="selected" value="{{ $prod_sub_cat[0]['product_category']->id }}">{{ $prod_sub_cat[0]['product_category']->product_category_name }}</option>

                                <!--                                    @foreach($prod_category as $prod_cat)
                                                                    <option <?php if ($prod_cat->id == $prod_sub_cat[0]['product_category']->id) echo 'selected="selected"'; ?> value="{{ $prod_cat->id }}">{{ $prod_cat->product_category_name }}</option>
                                                                    @endforeach-->
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="size">Product Size</label>
                            <input id="size" class="form-control" placeholder="Product Size" name="size" value="{{$prod_sub_cat[0]->size}}" type="text">
                        </div>
                        <div class="thick">   
                            <div class="form-group ">
                                <label for="thickness">Product Thickness</label>
                                <input id="thickness" class="form-control" placeholder="Product Thickness" name="thickness" value="{{ $prod_sub_cat[0]->thickness }}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="weight">Product Weight</label>
                            <input id="weight" class="form-control" placeholder="Product Weight" name="weight" value="{{ $prod_sub_cat[0]->weight }}" type="text">
                        </div>
                        <div class="form-group">
                            <label for="difference">Difference</label>
                            <input id="difference" class="form-control" placeholder=" Difference" name="difference" value="{{ $prod_sub_cat[0]->difference }}" type="text">
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
                        {!!Form::close()!!}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection