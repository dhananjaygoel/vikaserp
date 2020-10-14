@extends('layouts.master')
@section('title','Inventory')
@section('content')
<style>
    @media only screen and (max-width : 1024px)  {
        .inventoryExport { display: none !important;}
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('/')}}/dashboard">Home</a></li>
                    <li class="active"><span>Product Inventory</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Inventory</h1>
                    <div class="row col-md-10 pull-right top-page-ui">
                        <div class="filter-block productsub_filter">
                            <div class="col-md-12">
                                <form method="GET" action="{{url('inventory')}}" id="filter_search">
                                    <div class="form-group col-md-2">
                                        <input class="form-control" placeholder="Product Alias Name" autocomplete="off" type="text" name="search_inventory" id="search_inventory" type="text" value="{{(Input::get('search_inventory') != '' )? Input::get('search_inventory'): ''}}" onblur="this.form.submit();">
                                        <!--                                        <a onclick="this.form.submit();" style="cursor: pointer;">
                                                                                    <i class="fa fa-search search-icon"></i>
                                                                                </a>-->
                                    </div>
                                    <div class="form-group col-md-2">
                                        <select class="form-control" name="inventory_filter" onchange="this.form.submit();">
                                            <option value="all">All</option>
                                            <option value="minimal" {{((Input::has('inventory_filter')) && (Input::get('inventory_filter')=='minimal'))? 'selected' : ''}}>Minimal Only</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <select class="form-control" name="product_category_filter" onchange="this.form.submit();">
                                            <option value="">-- Select Product Category --</option>
                                            @foreach($product_category as $category)
                                            @if((Input::has('product_category_filter')) && (Input::get('product_category_filter')!=''))
                                            <option value="{{$category->id}}" {{($category->id==Input::get('product_category_filter'))?'selected':''}} >{{$category->product_category_name}}</option>
                                            @else
                                            <option value="{{$category->id}}">{{$category->product_category_name}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                                <form action="{{URL::action('InventoryController@export_inventory')}}">
                                    <div class="form-group col-md-4">
                                        <input type="hidden" id="search_inventory" name="search_inventory"  value="{{(Input::get('search_inventory') != '' )? Input::get('search_inventory'): ''}}">
                                        <input type="hidden" name="inventory_filter" value="{{((Input::has('inventory_filter')) && (Input::get('inventory_filter')=='minimal'))? 'minimal' : 'all'}}">
                                        <input type="hidden" name="product_category_filter" value="{{Input::get('product_category_filter')}}">
                                        <input type="submit" class="btn btn-primary form_button_footer " value="Export Inventory List">
                                        <!-- <a href="{{url('export_inventory')}}" class="btn btn-primary form_button_footer ">Export Inventory List</a> -->
                                        <!-- <input type="hidden" id="export-data-field" name="export_data" value="" class="btn btn-primary form_button_footer"> -->
                                        <!-- <a class="btn btn-primary" id="export-inventory-list">Export Inventory List</a>                                         -->
                                        @if(auth()->user()->role_id == 0)
                                        <a class="btn btn-primary save_all_inventory">Save all</a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        <form method="POST" action="{{url('inventory')}}" id="frm_inventory_update">
                    <input type="hidden" name="input_product_stock" id="input_product_stock">
                    <input type="hidden" name="input_product" id="input_product">
                </form>-->
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body clearfix">
                        @if (Session::has('success'))
                        <div class="alert alert-success alert-autohide">
                            {{Session::get('success')}}
                        </div>
                        @endif
                        @if (Session::has('error'))
                        <div class="alert alert-danger alert-autohide">
                            {{Session::get('error')}}
                        </div>
                        @endif
                        <div class="alert alert-success inventory_update">
                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                            <strong>Great!</strong> Inventory details successfully updated.
                        </div>
                        <div class="alert alert-danger inventory_update_min">
                            <strong>Error!</strong> Negatives values are not allowed in opening stock.
                        </div>
                        <div class="alert alert-danger inventory_update_max">
                            <strong>Error!</strong> Maximum 6 digits allowed.
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table" id="inventory_html">
                            @include('inventory_table')
                            </table>
                        </div>
                        <span class="pagination pull-right">
                            <?php
                            echo $inventory_list->appends(Input::except('page'))->render();
                            ?>
                        </span>
                        <div class="clearfix"></div>
                        @if($inventory_list->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('inventory')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $inventory_list->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var page_number = $("input[name='pagenumber']").val();
    $.ajax({
        preventDefault(); //Prevent default behavior
        data: {page:page_number},
        url: 'inventory_table',
        success:function(data)
        {
        $('#inventory_html').html(data);
        }
    });
});
</script>
@endsection
