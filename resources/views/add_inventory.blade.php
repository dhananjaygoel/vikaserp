@extends('layouts.master')
@section('title','Inventory')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/dashboard">Home</a></li>
                    <li class="active"><span>Product Inventory</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Inventory</h1>
                    <div class="row col-md-8 pull-right top-page-ui">
                        <div class="filter-block productsub_filter">
                            <form method="GET" action="{{url('inventory')}}" id="filter_search">
                                <div class="form-group  col-md-5 pull-right">
                                    <input class="form-control" placeholder="Enter Product Size" autocomplete="off" type="text" name="search_inventory" id="search_inventory" type="text" value="{{(Input::get('search_inventory') != '' )? Input::get('search_inventory'): ''}}" onblur="this.form.submit();">
                                    <a onclick="this.form.submit();" style="cursor: pointer;">
                                        <i class="fa fa-search search-icon"></i>
                                    </a>
                                </div>
                            </form>
                            <a href="{{url('export_inventory')}}" class="btn btn-primary form_button_footer">Export Inventory List</a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body clearfix">
                        <div class="alert alert-success inventory_update">
                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                            <strong>Well done!</strong> Inventory details successfully updated.
                        </div>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="opening"><span>Size</span></th>
                                        <th class="inventory-size"><span>Opening</span></th>
                                        <th><span>Sales<br/>Challan</span></th>
                                        <th><span>Purchase<br/>Challan</span></th>
                                        <th><span>Physical<br/>Closing</span></th>
                                        <th><span>P SO</span></th>
                                        <th><span>P DO</span></th>
                                        <th><span>P PO</span></th>
                                        <th><span>P PA</span></th>
                                        <th><span>Virtual<br />Stock</span></th>
                                        <th><span>Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
//                                    echo "<pre>";
//                                    print_r($inventory_list->toArray());
//                                    echo "<pre>";
//                                    exit();
                                    ?>
                                    @foreach($inventory_list as $inventory)
                                    <tr class="smallinput">
                                        <td>{{$inventory->product_sub_category->alias_name}}</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" placeholder="Stock in(kg)" value="{{$inventory->opening_qty}}" class="form-control" />
                                                <input type="hidden" value="{{$inventory->id}}"/>
                                            </div>
                                        </td>
                                        <td>{{$inventory->sales_challan_qty}}</td>
                                        <td>{{$inventory->purchase_challan_qty}}</td>
                                        <td>{{$inventory->physical_closing_qty}}</td>
                                        <td>{{$inventory->pending_sales_order_qty}}</td>
                                        <td>{{$inventory->pending_delivery_order_qty}}</td>
                                        <td>{{$inventory->pending_purchase_order_qty}}</td>
                                        <td>{{$inventory->pending_purchase_advise_qty}}</td>
                                        <td>{{$inventory->virtual_qty}}</td>
                                        <td>
                                            <div class="row product-price">                                                
                                                <div class="form-group col-md-2 difference_form">
                                                    <input class="btn btn-primary" type="button" value="save" onclick="update_inventory(this);">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <ul class="pagination pull-right">
                            <?php
                            echo $inventory_list->render();
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection