@extends('layouts.master')
@section('title','Product Category')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Product Category</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left"> View Product Category</h1>
                    <div class="pull-right top-page-ui">
                        <a href="edit_prod_cat.php" class="btn btn-primary pull-right">
                            Edit Product Category
                        </a>
                    </div>
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
                                            @if($product_cat[0]['product_type_id'] == 1)
                                            {{'Pipe'}}
                                            @else
                                            {{'structure'}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Sub Product Category Name:</span> {{ $product_cat[0]['product_category_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Price: </span>{{ $product_cat[0]['price'] }}
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