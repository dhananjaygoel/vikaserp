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
                    <h1 class="pull-left">Product Category</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('ProductController@create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add Product Category
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-success1">
                        {{Session::get('success')}}                            
                    </div>
                    @endif
                    <div class="main-box-body main_contents clearfix">   
                        @if(sizeof($product_cat) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class=" table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th class="col-md-3">Name</th>
                                        <th class="col-md-2">Type</th>
                                        <th class="col-md-3">Price</th>

                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                    <tr>
                                        <td>1</td>
                                        <td>Category Name 1</td>
                                        <td>Pipe</td>
                                        <td>
                                            <div class="row product-price">
                                                <div class="form-group col-md-4">
                                                    <input type="text" class="form-control" id="difference">

                                                </div>
                                                <div class="form-group col-md-2 difference_form">

                                                    <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                                </div>
                                            </div>
                                        </td>                                        

                                        <td>
                                            <a href="view_prod_cat.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="edit_prod_cat.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Category Name 2</td>
                                        <td>Structure</td>
                                        <td>
                                            <div class="row product-price">
                                                <div class="form-group col-md-4">
                                                    <input type="text" class="form-control" id="difference">

                                                </div>
                                                <div class="form-group col-md-2 difference_form">

                                                    <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                                </div>
                                            </div>
                                        </td>                                        

                                        <td>
                                            <a href="view_prod_cat.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="edit_prod_cat.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                        </td>
                                    </tr>

                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>

                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> 9988776655</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="text"></div>

                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b> ?</div>


                                                </div>

                                            </div>          
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                                </ul>
                            </span>
                        </div>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no product category available here.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection