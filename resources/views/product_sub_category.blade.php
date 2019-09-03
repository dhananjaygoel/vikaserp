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
                <h1 class="pull-left">Product Size</h1>
                    <div class=" row col-md-12 pull-right top-page-ui">
                        <div class="filter-block col-md-12 productsub_filter pull-right">
                            <form method="GET" action="{{URL::action('ProductsubController@index')}}" id="filter_search" >
                                <div class="col-md-12 pull-right">
                                    @if( Auth::user()->role_id == 0 )
                                    <div class="col-md-3 form-group pull-right">
                                        <a href="{{URL::action('ProductsubController@create')}}" class="btn btn-primary pull-right">
                                            <i class="fa fa-plus-circle fa-lg" style="cursor: pointer;"></i> Add Product Size
                                        </a>
                                        <!--<a href="{{url('export_product_size')}}" class="btn btn-primary form_button_footer">Export</a>-->
                                         <!--<input type="submit"  name="export_data" value="Export" class="btn btn-primary form_button_footer">-->
                                    </div>
                                    @endif
                                    <div class="form-group col-md-3  pull-right">
                                        <input class="form-control ui-autocomplete-input" placeholder="Product Size" value="{{Input::get('product_size')}}" id="product_size" autocomplete="off" name="product_size" type="text" onblur="this.form.submit();">
                                        <a  onclick="this.form.submit()">
                                            <i class="fa fa-search search-icon" id="search_icon"></i>
                                        </a>
                                    </div>
                                    <div class="form-group  col-md-2  pull-right">
                                        <select class="form-control" name="product_filter" onchange="this.form.submit()">
                                            <option value="" selected="">--Product category--</option>
                                            @foreach($product_type as $prod_type)
                                            <option <?php if (Input::get('product_filter') == $prod_type->id) echo 'selected="selected"'; ?> value="{{$prod_type->id}}"> {{$prod_type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2 pull-right">
                                        <input class="form-control ui-autocomplete-input" placeholder="Product Name" autocomplete="off" value="{{Input::get('search_text')}}" name="search_text" id="search_text" type="text" onblur="this.form.submit();">
                                        <a onclick="this.form.submit()" style="cursor: pointer;">
                                            <i class="fa fa-search search-icon" id="search_icon"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <form method="GET" action="{{URL::action('ProductsubController@index')}}" id="filter_search" >
                                <div class="col-md-12 pull-right">
                                    @if( Auth::user()->role_id == 0 )
                                    <div class="col-md-3 form-group pull-right">
                                        <input type="submit"  name="export_data" value="Export" class="btn btn-primary form_button_footer" style="margin-top: -28%;">
                                    </div>
                                    @endif
                                    <div class="form-group col-md-3  pull-right">
                                        <input value="{{Input::get('product_size')}}" id="product_size"  name="product_size" type="hidden">

                                    </div>
                                    <div class="form-group  col-md-2  pull-right">
                                        <input value="{{Input::get('product_filter')}}" name="product_filter" type="hidden" >
                                    </div>
                                    <div class="form-group col-md-2 pull-right">

                                        <input value="{{Input::get('search_text')}}" name="search_text" type="hidden" >

                                    </div>
                                </div>
                            </form>
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
                        <div class="alert alert-success alert-success1 custom_alert_success">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            Product differences have been successfully updated.
                        </div>
                        @if(sizeof($product_sub_cat) != 0)
                        <div class="table-responsive">
                            <form method="POST" id="save_all_product_sizes">
                                <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">
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
                                            <th>Today's Price</th>
                                            <th class="col-md-2">Difference</th>
                                            <th >Total Price </th>
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
                                                @if($produ_sub['product_category']->product_type_id == 1 || $produ_sub['product_category']->product_type_id == 3 ||$produ_sub['product_category']->product_type_id == 2)
                                                @if(is_numeric($produ_sub->thickness))
                                                {{ round($produ_sub->thickness, 2) }}
                                                @else
                                                {{$produ_sub->thickness}}
                                                @endif
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
                                                <?php $prod_diff = $produ_sub['product_category']->price - substr($produ_sub->difference, 1); ?>
                                                @else
                                                {{ $produ_sub['product_category']->price + $produ_sub->difference }}
                                                <?php $prod_diff = $produ_sub['product_category']->price + $produ_sub->difference; ?>
                                                @endif
                                            </td>
                                            <td>
                                                @if(Auth::user()->role_id == 0)
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="tel" class="form-control" required="" name="difference_{{$i}}" value="{{ $produ_sub->difference}}" onkeypress=" return numbersOnly(this, event, true, true);">
                                                        <input type="hidden" class="form-control" name="id_{{$i}}" value="{{ $produ_sub->id}}">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}" id="_token">

                                                        <?php $price_diff = $produ_sub->difference; ?>
                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">
                                                        <input class="btn btn-primary" type="button" class="form-control" value="save" onclick="update_difference(this);" >
                                                    </div>
                                                </div>
                                                @else
                                                <div class="form-group col-md-6">{{ $produ_sub->difference }} </div>
                                                <?php $price_diff = $produ_sub->difference; ?>
                                                @endif
                                            </td>
                                            <td>
                                                <label>{{ $produ_sub->difference + $prod_diff}}</label>
                                            </td>
                                            @if( Auth::user()->role_id == 0 )
                                            <td>
                                                <a href="{{URL::action('ProductsubController@edit',['id'=>$produ_sub->id])}}" class="table-link">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <!--                                                <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal{{$produ_sub->id}}">-->
                                                <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal" onclick="delete_sub_product_row({{$produ_sub->id}})">
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

                                        @endforeach
                                    </tbody>
                                </table>
                                <button name="submit" class="btn btn-primary" data-pageid="{{(Input::get('page')!= '')?Input::get('page') : 1 }}" id="save_all_size_btn" type="button">Save All Sizes</button>
                            </form>

                            <!--                                    <div class="modal fade" id="myModal{{$produ_sub->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                        </div>
                                        <!--                                                {!! Form::open(array('route' => array('product_sub_category.destroy', $produ_sub->id), 'method' => 'delete')) !!}-->

                                        <form method="post"  id="delete_sub_row">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <input class="form-control" name="product_sub_id" id="product_sub_id" type="hidden"/>
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