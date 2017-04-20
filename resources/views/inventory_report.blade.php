@extends('layouts.master')
@section('title','Inventory Report')
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
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">   
            <div class="col-lg-12">
                <h1 class="pull-left">Inventory Report</h1>
                <div class="form-group pull-right">
                    <div class="col-md-12">
                        <form method="GET" action="javascript:;">
                            <select class="form-control" id="inventory_report_filter" name="labour_chart_filter">
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
                <form class="pull-right" method="POST" action="{{URL::action('InventoryController@exportinventoryReport')}}">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <input type="hidden" id="export_product_id" name="product_id" value="<?php echo $product_id;?>">
                    <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right " style=" float: left !important; margin-left: 2% !important;">
                </form>
<!--                <form class="pull-right" method="POST" action="{{URL::action('InventoryController@exportinventoryReport')}}">
                    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="product_id" value="<?php echo $product_id;?>">
                    <input type="submit"  name="export_data" value="Print" class="btn btn-primary " style=" float: left !important; margin-right: 10% !important;">
                </form>-->
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
                                            <td class="crossout" colspan="1" rowspan="1"><span class="size-head">Size</span><span class="thickness-head">Thickness</span></td> 
                                            @if(isset($thickness_array))
                                                @foreach($thickness_array as $thickness)
                                                   <td>{{$thickness}}</td>
                                                @endforeach
                                            @endif                                            
                                        </tr>                                        
                                        @foreach($report_arr as $key=>$record)
                                        <tr>                                            
                                            <td>{{$key}}</td>                                                                                        
                                            @if(isset($record))
                                                @foreach($record as $value)
                                                <td>
                                                   @if(isset($value))
                                                        {{$value}}
                                                   @else
                                                        {{"-"}}     
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
    @stop