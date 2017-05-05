@extends('layouts.master')
@section('title','Loaded By chart')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">                
                <div class="form-group col-md-12 col-lg-12 pull-right">
                    <h1 class="pull-left">Loaded by performance</h1>
                    
                    <div class="col-md-4 pull-right" id="month_div">
                        <div class="form-group">
                            <div class="col-md-10 pull-right">
                                <form class="search_form loaded_by_performance_search_form" method="GET" action="javascript:;">
                                    <div class="col-md-8">
                                        <input name="performance" id="performance-days" class="form-control performance-days" value="{{date('F-Y', mktime(0, 0, 0))}}"/>
                                    </div>
                                    <div class="col-md-4  pull-right">
                                        <input type="submit" disabled="" name="search_data" id="search_month" value="Search" class="search_button btn btn-primary pull-right export_btn">
                                    </div>
                                 </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 pull-right" id="year_div" style="display: none;">
                        <div class="form-group">
                            <div class="col-md-10 pull-right">
                                <form class="search_form loaded_by_performance_search_form" id="loaded_by_performance_months_from" method="GET" action="javascript:;">
                                    <div class="col-md-8">
                                        <input name="performance" id="performance-months" class="form-control performance-month" value="{{date('Y', mktime(0, 0, 0))}}"/>
                                    </div>
                                    <div class="col-md-4  pull-right">
                                        <input type="submit" disabled="" name="search_data"  id="search_year" value="Search" class="search_button btn btn-primary pull-right export_btn">
                                    </div>
                                 </form>
                            </div>
                        </div>
                    </div>
                    <div class="form-group pull-right">
                        <form method="GET" action="javascript:;">
                            <select class="form-control" id="loaded_by_chart_filter" name="labour_chart_filter">
                                <option value="Day" selected="selected">Day wise</option>
                                <option value="Month">Month wise</option>
                            </select>
                        </form>
                    </div>
                </div>                
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
                            <div class="table-responsive report_table">
                                <table id="day-wise" class="table table-bordered complex-data-table">
                                    <tbody>
                                        
                                        <?php  $today = date('d');
//                                            $today = date("d", strtotime($date));
                                        ?>
                                        <tr>
                                            <td colspan="2" rowspan="1"></td>
                                            <td colspan="{{$today}}"><b>Date</b></td>
                                        </tr>
                                        <tr class="text-bold">
                                            <td colspan="2"></td>
                                            @for($i = 1; $i<= $today ; $i++ )
                                                <td>{{ $i }}</td>
                                            @endfor
                                        </tr>
                                        @if(isset($loaded_by))
                                            <?php $date_val = substr($date, 0, 8); ?>
                                            @foreach($loaded_by as $loader_val)
                                            <tr>
                                                <td rowspan="2"><b>{{$loader_val->first_name}} {{$loader_val->last_name}}</B></td>                                                
                                                <td><b>Tonnage</b></td>    
                                                @for($i = 1; $i<= $today ; $i++ )
                                                <?php 
                                                $k = 0;
                                                $tangage = 0;
                                                foreach ($final_array as $key => $value) {
                                                    if($i<=9){ $i = sprintf("%02d", $i); }
                                                    if ($value['date'] == "$date_val" . $i) {
                                                        if ($value['loader_id'] == $loader_val->id) {
                                                            $k++;
                                                            $tangage +=$value['tonnage'];
                                                        }
                                                    }
                                                }
                                                ?>
                                                 <td>{{ $tangage }}</td>
                                                @endfor
                                                <tr>
                                                    <td><b>Delivery</b></td>
                                                    @for($i = 1; $i<= $today ; $i++ )
                                                    <?php 
                                                   $k=0;
                                                   $tangage=0;
                                                       foreach ($final_array as $key => $value) {
                                                           if($i<=9){ $i = sprintf("%02d", $i); }
                                                           if($value['date']=="$date_val".$i){
                                                               if($value['loader_id'] == $loader_val->id){
                                                                   $k++;
                                                                   $tangage +=$value['tonnage'];
                                                               }
                                                           }
                                                       }
                                                   ?>
                                                   <td>{{$k}}</td>
                                                   @endfor
                                                </tr>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop