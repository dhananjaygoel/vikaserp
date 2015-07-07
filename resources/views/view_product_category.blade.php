@extends('layouts.master')
@section('title','Product Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/product_category">Product Category</a></li>
                    <li class="active"><span>View Product Category</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left"> View Product Category</h1>
                    @if ( Auth::user()->role_id == 0 )         
                    <div class="pull-right top-page-ui">
                        <a href="{{ URL::action('ProductController@edit',['id'=>$product_cat->id]) }}" class="btn btn-primary pull-right">
                            Edit Product Category
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">                              
                                <tbody>                  
                                    <tr>
                                        <td>
                                            <span>Product Category Type:</span> 
                                            @if($product_cat->product_type_id == 1)
                                            {{$product_cat['product_type']->name}}
                                            @else
                                            {{$product_cat['product_type']->name}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Sub Product Category Name:</span> {{ $product_cat->product_category_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Sub Product Category Alias Name:</span> {{ isset($product_cat['product_sub_category'])?$product_cat['product_sub_category']->alias_name: 'No Alias' }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Price: </span>{{ $product_cat->price }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection