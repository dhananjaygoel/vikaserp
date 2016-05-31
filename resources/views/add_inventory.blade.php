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
                            <a class="btn btn-primary save_all_inventory">Save all</a>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="opening"><span>Alias Name</span></th>
                                        <th class="opening"><span>Minimal</span></th>
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
                                <form method="POST" action="{{url('inventory')}}" id="frm_inventory_save_all">
                                    <input type="hidden" name="pagenumber" value="{{(Input::get('page')!= '')?Input::get('page') : 1 }}"  />
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        ?>
                                        @foreach($inventory_list as $inventory)

                                        <tr class="smallinput datadisplay_{{$inventory->id}}">
                                            <td>{{$inventory->product_sub_category->alias_name}}</td>
                                            <?php
                                            $total = ($inventory->physical_closing_qty - $inventory->pending_delivery_order_qty - $inventory->pending_sales_order_qty + $inventory->pending_purchase_advise_qty);
                                            ?>
                                            <td class="{{ ($inventory->minimal < $total) ?'minimum_reach': '' }}">
                                                <div class="form-group">                                                    
                                                    <input type="text" name="minimal_{{$inventory->id}}" id="minimal_{{$inventory->id}}" value="{{$inventory->minimal}}" maxlength="9" class="form-control no_alphabets" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">                                                    
                                                    <input type="text" name="{{$inventory->id}}" placeholder="Stock in(kg)" value="{{$inventory->opening_qty}}" maxlength="9" class="form-control no_alphabets" />
                                                </div>
                                            </td>
                                            <td id="sales_challan_{{$inventory->id}}">{{($inventory->sales_challan_qty <= 0 )? 0: $inventory->sales_challan_qty}}</td>
                                            <td id="purchase_challan_{{$inventory->id}}">{{($inventory->purchase_challan_qty <= 0) ? 0 : $inventory->purchase_challan_qty}}</td>
                                            <td id="physical_closing_{{$inventory->id}}">{{$inventory->physical_closing_qty}}</td>
                                            <td id="pending_order_{{$inventory->id}}">{{($inventory->pending_sales_order_qty <= 0) ? 0 : $inventory->pending_sales_order_qty}}</td>
                                            <td id="pending_deliver_order_{{$inventory->id}}">{{($inventory->pending_delivery_order_qty <= 0) ? 0 : $inventory->pending_delivery_order_qty}}</td>
                                            <td id="pending_purchase_order_{{$inventory->id}}">{{($inventory->pending_purchase_order_qty <= 0) ? 0 : $inventory->pending_purchase_order_qty }}</td>
                                            <td id="pending_purchase_advise_{{$inventory->id}}">{{($inventory->pending_purchase_advise_qty <= 0) ? 0 : $inventory->pending_purchase_advise_qty}}</td>
                                            <td id="virtual_qty_{{$inventory->id}}">{{$inventory->virtual_qty}}</td>
                                            <td>
                                                <div class="row product-price">                                                
                                                    <div class="form-group col-md-2 difference_form">
                                                        <input class="btn btn-primary" type="button" value="save" data-id="{{$inventory->id}}" onclick="update_inventory(this,{{$inventory->id}});">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                        $i++;
                                        ?>
                                        @endforeach

                                    </tbody>
                                </form>
                            </table>
                        </div>
                        <ul class="pagination pull-right">
                            {!! $inventory_list->render() !!}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection