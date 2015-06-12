@extends('layouts.master')
@section('title','Product Sub Category')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/dashboard">Home</a></li>
                    <li class="active"><span>Product Sub Category</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Product Sub Category</h1>
                    <div class=" row col-md-8 pull-right top-page-ui">
                        <div class="filter-block col-md-8 productsub_filter">       
                            <div class="form-group  col-md-5">
                                <form method="GET" action="{{URL::action('ProductsubController@index')}}" id="filter_form">
                                    <select class="form-control" name="product_filter" onchange="this.form.submit()">
                                        <option value="" selected="">--Product category--</option>
                                        @foreach($product_type as $prod_type)
                                        <option <?php if (Input::get('product_filter') == $prod_type->id) echo 'selected="selected"'; ?> value="{{$prod_type->id}}"> {{$prod_type->name}}</option>
                                        @endforeach
                                    </select> 
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                </form>
                            </div> 
                            <div class="form-group  col-md-6">
                                <form method="GET" action="{{URL::action('ProductsubController@index')}}" id="filter_search">
                                    <input class="form-control" placeholder="Enter Product Name" value="{{Input::get('search_text')}}" name="search_text" type="text">
                                    <a  onclick="this.form.submit()">
                                        <i class="fa fa-search search-icon" id="search_icon"></i>
                                    </a>
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                </form>
                            </div>	
                        </div>
                        <div class="col-md-4">
                            <a href="{{URL::action('ProductsubController@create')}}" class="btn btn-primary pull-right">
                                <i class="fa fa-plus-circle fa-lg" style="cursor: pointer;"></i> Add Product Category
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="table1">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">

                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">
                            {{Session::get('success')}}                            
                        </div>
                        @endif
                        @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">
                            {{Session::get('wrong')}}                            
                        </div>
                        @endif
                        
                        @if(sizeof($product_sub_cat) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Size</th>
                                        <th >Thickness</th>
                                        <th>Weight</th>
                                        <th class="col-md-2">Difference</th>                                                         
                                        <th >Actions</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php $i = ($product_sub_cat->currentPage() - 1 ) * $product_sub_cat->perPage() + 1; ?>

                                    @foreach($product_sub_cat as $produ_sub) 
                                    @if(sizeof($produ_sub['product_category']) != 0)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $produ_sub['product_category']->product_category_name }} </td>
                                        <td>{{ $produ_sub->size }}</td>
                                        <td>{{ $produ_sub->thickness}}</td>
                                        <td>{{ $produ_sub->weight}}</td>
                                        <td>
                                            <form method="post" action="{{URL::action('ProductsubController@update_difference')}}">
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="text" class="form-control" required="" name="difference" value="{{ $produ_sub->difference}}">
                                                        <input type="hidden" class="form-control" name="id" value="{{ $produ_sub->id}}">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">
                                                        <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                                    </div>
                                                </div>
                                            </form>
                                        </td>                                        
                                        <td>
                                            <a href="{{URL::action('ProductsubController@edit',['id'=>$produ_sub->id])}}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal{{$produ_sub->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>                           
                                    <?php $i++; ?>
                                    @endif
                                <div class="modal fade" id="myModal{{$produ_sub->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('route' => array('product_sub_category.destroy', $produ_sub->id), 'method' => 'delete')) !!}
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <?php
                                                    $us = Auth::user();
                                                    $us['mobile_number']
                                                    ?>
                                                    <div><b>Mobile:</b>
                                                        {{$us['mobile_number']}}
                                                        <input type="hidden" name="mobile" value="{{$us['mobile_number']}}"/>
                                                        <input type="hidden" name="user_id" value="<?php echo $produ_sub->id; ?>"/>
                                                    </div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" id="model_pass<?php echo $produ_sub->id; ?>" name="model_pass" placeholder="" required="required" type="password"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>                                
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php echo $product_sub_cat->render(); ?>
                                </ul>
                            </span>
                        </div>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no product sub category available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
<!--        <div class="row" id="table2">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Size</th>
                                        <th>Weight</th>
                                        <th class="col-md-2">Difference</th>                                                         
                                        <th >Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    
                                    <tr>
                                        <td >1</td>
                                        <td>CRP Pipe </td>
                                        <td>60 mm</td>
                                        <td>10 kg</td>
                                        <td>
                                            <div class="row product-price">
                                                <div class="form-group col-md-6">
                                                    <input type="text" class="form-control" id="difference">
                                                </div>
                                                <div class="form-group col-md-2 difference_form">
                                                    <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                                </div>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <a href="edit_prod_sub_cat.php" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>CRP Structure </td>
                                        <td>20 mm</td>
                                        <td>5 kg</td>
                                        <td>
                                            <div class="row product-price">
                                                <div class="form-group col-md-6">
                                                    <input type="text" class="form-control" id="difference">
                                                </div>
                                                <div class="form-group col-md-2 difference_form">
                                                    <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                                </div>
                                            </div>
                                        </td>                  
                                        <td>
                                            <a href="edit_prod_sub_cat.php" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal">
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
                                                <p>Are you sure you want to delete</p>
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
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</div>
@endsection