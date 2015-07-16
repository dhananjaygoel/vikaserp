@extends('layouts.master')
@section('title','Product Size')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/dashboard">Home</a></li>
                    <li class="active"><span>Product Size</span></li>
                </ol>
                <div class="clearfix">                    
                    <div class=" row col-md-12 pull-right top-page-ui">
                        <div class="filter-block col-md-12 productsub_filter pull-right">  
                            @if( Auth::user()->role_id == 0 )
                            <div class="col-md-2 form-group pull-right">
                                <a href="{{URL::action('ProductsubController@create')}}" class="btn btn-primary pull-right">
                                    <i class="fa fa-plus-circle fa-lg" style="cursor: pointer;"></i> Add Product Size
                                </a>
                            </div>
                            @endif
                            <div class="form-group col-md-3  pull-right">
                                <form method="GET" action="{{URL::action('ProductsubController@index')}}" id="filter_search">
                                    <input class="form-control ui-autocomplete-input" placeholder="Product Size" value="{{Input::get('product_size')}}" id="product_size" autocomplete="off" name="product_size" type="text">
                                    <a  onclick="this.form.submit()">
                                        <i class="fa fa-search search-icon" id="search_icon"></i>
                                    </a>
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                </form>
                            </div>
                            <div class="form-group  col-md-2  pull-right">
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
                            <div class="form-group col-md-2 pull-right">
                                <form method="GET" action="{{URL::action('ProductsubController@index')}}" id="filter_search">
                                    <input class="form-control ui-autocomplete-input" placeholder="Product Name" autocomplete="off" value="{{Input::get('search_text')}}" name="search_text" id="search_text" type="text">
                                    <a onclick="this.form.submit()">
                                        <i class="fa fa-search search-icon" id="search_icon"></i>
                                    </a>
                                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                </form>
                            </div>
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
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            {{Session::get('success')}}                            
                        </div>
                        @endif
                        @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
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
                                        <th>Alias Name</th>
                                        <th>Size(Meter)</th>
                                        @if(Input::get('product_filter') != 2)
                                        <th>Thickness</th>
                                        @endif
                                        <th>Weight(KG)</th>                                        
                                        <th>Standard Length</th>                                        
                                        <th>Todays Price</th>                                        
                                        <th class="col-md-2">Difference</th>  
                                        @if( Auth::user()->role_id == 0 )
                                        <th >Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody> 
                                    <?php
                                    $i = ($product_sub_cat->currentPage() - 1 ) * $product_sub_cat->perPage() + 1;
                                    ?>
                                    @foreach($product_sub_cat as $produ_sub) 
                                    @if(sizeof($produ_sub['product_category']) != 0)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $produ_sub['product_category']->product_category_name }} </td>
                                        <td>{{ $produ_sub->alias_name }}</td>
                                        <td>{{ $produ_sub->size }}</td>
                                        @if(Input::get('product_filter') != 2)
                                        <td>
                                            @if($produ_sub['product_category']->product_type_id == 1)
                                            {{ $produ_sub->thickness}}
                                            @else
                                            {{'--'}}
                                            @endif
                                        </td>
                                        @endif
                                        <td>{{ $produ_sub->weight }} KG</td>
                                        <td>{{ $produ_sub->standard_length }}</td>

                                        <td>
                                            <?php
                                            $sign = substr($produ_sub->difference, 0, 1);
                                            ?>
                                            @if($sign == '-')
                                            {{ $produ_sub['product_category']->price - substr($produ_sub->difference,1) }}
                                            @else
                                            {{ $produ_sub['product_category']->price + $produ_sub->difference }}
                                            @endif

                                        </td>
<!--                                        <td>
                                            @foreach($units as $unit)
                                            @if($unit->id ==  $produ_sub->unit_id)
                                            {{ $unit->unit_name}}
                                            @endif
                                            @endforeach
                                        </td>-->

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
                                        @if( Auth::user()->role_id == 0 )
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
                                        @endif
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
                                    @if(sizeof($_GET) < 2)
                                    <?php echo $product_sub_cat->render(); ?>
                                    @else
                                    <?php echo $product_sub_cat->appends(array('product_size' => $filter[0], 'search_text' => $filter[1], 'product_filter' => $filter[2]))->render(); ?>
                                    @endif                                    
                                </ul>
                            </span>
                            <div class="clearfix"></div>  

                            @if($product_sub_cat->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('product_sub_category')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $product_sub_cat->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif

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
    </div>
</div>
@endsection