@extends('layouts.master')
@section('title','Edit Product Category')
@section('content')
<div class="row">
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb">
                <li><a href="{{url()}}/product_category">Product Category</a></li>
                <li class="active"><span>Edit Product Category</span></li>
            </ol>
            <div class="clearfix">
                <h1 class="pull-left"> Edit Product Category</h1>
            </div>
        </div>
    </div>
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

                {!!Form::open(array('method'=>'PUT','url'=>url('product_category/'.$product_cat[0]['id']),'id'=>''))!!}
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="form-group">
                    <label>Product Category Type<span class="mandatory">*</span></label>
                    <div class="radio">
                        @foreach($product_type as $prod_type)
                        <input <?php if ($product_cat[0]['product_type_id'] == $prod_type->id) echo 'checked="checked"'; ?> value="{{$prod_type->id}}" id="optionsRadios{{$prod_type->id}}" name="product_type" type="radio">
                        <label for="optionsRadios{{$prod_type->id}}">{{$prod_type->name}}</label>
                        @endforeach     
                    </div>
                </div>
                <div class="form-group">
                    <label for="cat_name">Product Category Name<span class="mandatory">*</span></label>
                    <input id="cat_name" class="form-control" placeholder="Product Category Name" name="product_category_name" value="{{ $product_cat[0]['product_category_name'] }}" type="text">
                </div>
<!--                <div class="form-group">
                    <label for="alias_name">Product Name</label>
                    <input id="alias_name" class="form-control" placeholder="Product Name" name="alias_name" value="{{ $product_cat[0]['alias_name'] }}" type="text">
                </div>-->




                <div class="form-group">
                    <label for="price">Price<span class="mandatory">*</span></label>
                    <input id="price" class="form-control" placeholder="Price" name="price" value="{{ $product_cat[0]['price'] }}" type="tel" onkeypress=" return numbersOnly(this,event,true,false);">
                </div>



                    <div class="form-group" >
                        <label for="">HSN Code<span class="mandatory">*</span></label>
                        <select name="hsn_code" class="form-control" id="hsn_desc_get_desc" required>
                            <option value="">Hsn Code</option>
                            @foreach(\App\Hsn::orderBy('id','DESC')->get() as $hsn)
                                <option value="{{$hsn->hsn_code.':'.$hsn->hsn_desc}}" {{($hsn->hsn_code==$product_cat[0]['hsn_code'])?'selected':''}}>{{$hsn->hsn_code}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="hsn_desc">HSN Description<span class="mandatory">*</span></label>
                        <textarea name="hsn_desc" id="hsn_desc" class="form-control">{{ $product_cat[0]['hsn_desc'] }}</textarea>
                    </div>


                <hr>
                <div >
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
@endsection