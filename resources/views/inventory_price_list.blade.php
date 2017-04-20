@extends('layouts.master')
@section('title','Inventory Price List')
@section('content')
<style>
.crossout{
    /*width: 120px;*/
   min-width: 150px;
   min-width: 150px;
   width: 150px;
   background-image: linear-gradient(to bottom left,  transparent calc(50% - 1px), #DDDDDD, transparent calc(50% + 1px));
}
.thickness-head{
   float: right;
   margin-top: -10px;
}
.size-head{
    float: left;
    margin-top: 20px;
}
.inventory-price-value{
    width: 70px;
    margin: auto;
}
#price_list_modal .modal-content{
    margin: 210px auto;
    width: 200px;
}
#price_list_modal .modal-price-save,#price_list_modal .pwdr {
    margin-left: 10px;
}
#price_list_modal .modal-header .close{
    margin-top: -10px;
}
.pwd .pwdr{
    width: 41%;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">   
            <div class="col-lg-12">
                <h1 class="pull-left">Inventory Price List</h1>
                <div class="form-group pull-right">
                    <div class="col-md-12">
                        <form method="GET" action="javascript:;">
                            <select class="form-control" id="inventory_price_list_filter" name="labour_chart_filter">
                                <!--<option value="">Product Name</option>-->
                                @if(isset($product_cat))
                                @foreach($product_cat as $product)
                                    <option value="{{$product->id}}">{{$product->product_category_name}}</option> 
                                @endforeach
                                @endif
                            </select>
                        </form>                        
                    </div>
                </div>
                <form class="pull-right" method="POST" action="{{URL::action('InventoryController@exportinventoryPriceList')}}">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="export_product_id" name="product_id" value="<?php echo $product_id;?>">
                    <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right " style=" float: left !important; margin-left: 2% !important;">
                </form>
                <a href="" id="print-inventory-price-list" data-toggle="modal" data-target="#print_inventory_price_list" class="btn btn-primary pull-right" data-id="<?php echo $product_id;?>" style=" margin-right: 8px !important;">
                    Print
                </a>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box clearfix">
                        <div class="main-box-body main_contents clearfix">
                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif

                            <div class="table-responsive report-table-content">
                                <table id="day-wise" class="table table-bordered text-center complex-data-table">
                                    <tbody>
                                        <tr style="width:50px; height:50px;">
                                            <td class="crossout" colspan="1" rowspan="1"><span class="size-head">{{$product_column}}</span><span class="thickness-head">Thickness</span></td> 
                                            @if(isset($thickness_array))
                                                @foreach($thickness_array as $thickness)
                                                   <td>{{$thickness}}</td>
                                                @endforeach
                                            @endif                                            
                                        </tr>                                        
                                        @foreach($report_arr as $key=>$record)
                                        <tr class="text-center">                                            
                                            <td>{{$key}}</td>                                                                                        
                                            @if(isset($record))
                                                @foreach($record as $key1=>$value)
                                                <td class="text-center">
                                                   @if(isset($value) && $value!="-")
                                                   <input class="form-control inventory-price-value" type="text" value="{{$value}}" data-size="{{$key}}"  data-thickness="{{$key1}}" data-product="@if(isset($product_id)){{$product_id}}@endif">
                                                   @else
                                                        {{$value}}     
                                                   @endif
                                                </td>
                                                @endforeach                                         
                                            @endif                                            
                                        </tr>
                                        @endforeach                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

<div class="modal fade" id="print_inventory_price_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="row print_time ">
                    <div class="col-md-12"> Print By <br>
                        <span class="current_time"></span>
                    </div>
                </div>                
                <hr>
                <div>
                    <button type="button" class="btn btn-primary form_button_footer print_inventory_price_list" >Print</button>
                    <button type="button" class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="price_list_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body text-center">
                <form id="inventory_price_form">
                    <div class="pwd">             
                        <div class="pwdr"><input class="form-control" id="modal_price" name="model_price" data-id="" onkeypress=" return numbersOnly(this,event,true,true);"></div>
                        <input type="hidden" id="prod-thickness" name="thickness">
                        <input type="hidden" id="prod-size" name="size">
                    </div>
                    <span class="modal-price-save">
                        <button class="btn btn-primary modal-price-save" data-dismiss="modal">Save</button>
                    </span>
                </form>    
            </div>
        </div>
    </div>
</div>
    @stop