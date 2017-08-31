@extends('layouts.master')
@section('title','Labour chart')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">                
                <div class="form-group col-md-12 col-lg-12 pull-right">
                    <h1 class="pull-left">Labours performance</h1>                    
                    <div class="col-md-6 pull-right" id="month_div">
                        <div class="form-group">
                            <div class="col-md-6 pull-right">
                                <form class="search_form labours_performance_search_form" method="GET" action="javascript:;">
                                    <div class="col-md-8 day-wise" id="day-wise">
                                        <input name="performance" id="performance-days" class="form-control performance-days" value="{{date('F-Y', mktime(0, 0, 0))}}"/>
                                    </div>
                                    <div class="col-md-8 month-wise" id="month-wise" style="display: none">
                                        <input name="performance" id="performance-months" class="form-control performance-month" value="{{date('Y', mktime(0, 0, 0))}}"/>
                                    </div>
                                    <div class="col-md-4  pull-right">
                                        <input type="submit" disabled="" name="search_data" id="search_month" value="Search" class="search_button btn btn-primary pull-right export_btn ">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3 pull-right">
                                <form method="GET" action="javascript:;">
                                    <select class="form-control" id="labour_chart_filter" name="labour_chart_filter">
                                        <option value="Day">Day wise</option>
                                        <option value="Month">Month wise</option>
                                    </select>
                                </form>
                        </div>
                        </div>
                    </div>
<!--                    <div class="form-group pull-right">
                        
                    </div>-->
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
                            <?php
//                                  
                            $today = date("d", strtotime($enddate));
                            $today_year = date("Y", strtotime($enddate));
                            $today_month = date("m", strtotime($enddate));
                            ?>

                            <div class="table-responsive day-wise report_table"  id="day-wise">
                                <table class="table table-bordered complex-data-table ">
                                    <tbody>

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
                                        <?php foreach ($labours as $labour) { ?>
                                            <tr>
                                                <td rowspan="2"><b>{{$labour->first_name}} {{$labour->last_name}}</B></td>
                                                <td><b>Tonnage</b></td>
                                                @for($i = 1; $i<= $today ; $i++ )
                                                <?php
                                                if ($i < 10) {
                                                    $temp_date = '0' . $i;
                                                } else {
                                                    $temp_date = $i;
                                                }
                                                $k = 0;
                                                $tangage = 0;
                                                foreach ($data as $key => $value) {
                                                    if ($value['date'] == $today_year . '-' . $today_month . '-' . $temp_date) {
                                                        if ($value['labour_id'] == $labour->id) {
                                                            $k++;
                                                            $tangage +=$value['tonnage'];
                                                        }
                                                    }
//                                                       
                                                }
                                                ?>
                                                <td>{{$tangage}}</td>

                                                @endfor

                                            </tr>
                                            <tr>
                                                <td><b>Delivery</b></td>
                                                @for($i = 1; $i<= $today ; $i++ )
                                                <?php
                                                $dc_id_list = array();
//                                                $k = 0;
                                                $tangage = 0;
                                                if ($i < 10) {
                                                    $temp_date = '0' . $i;
                                                } else {
                                                    $temp_date = $i;
                                                }
                                                foreach ($data as $key => $value) {
                                                    if ($value['date'] == $today_year . '-' . $today_month . '-' . $temp_date) {
                                                        if ($value['labour_id'] == $labour->id) {
                                                            array_push($dc_id_list, $value['delivery_id']);
//                                                            $k++;
                                                            $tangage +=$value['tonnage'];
                                                        }
                                                    }
//                                                       
                                                }
                                                ?>
                                                <td><?php
                                                $dc_id_list = array_unique($dc_id_list);
                                                ?>{{count($dc_id_list)}}</td>
                                                @endfor
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>

                            </div>
                            <div class="table-responsive month-wise report_table" style="display: none" id="month-wise">
                                <table  class="table table-bordered complex-data-table" >
                                    <tbody>
                                        <?php
                                        $month = date('m');
                                        $year = date('Y');
                                        ?>
                                        <tr>
                                            <td colspan="2" rowspan="1"></td>
                                            <td colspan="{{$month}}"><b>Month</b></td>
                                        </tr>
                                        <tr class="text-bold">
                                            <td colspan="2"></td>
                                            @for($i = 1; $i<= $month ; $i++ )
                                            <td>{{ date('F', mktime(0, 0, 0, $i)) }}</td>
                                            @endfor
                                        </tr>
                                        <?php foreach ($labours as $labour) { ?>
                                            <tr>
                                                <td rowspan="2"><b>{{$labour->first_name}} {{$labour->last_name}}</B></td>
                                                <td><b>Tonnage</b></td>
                                                @for($i = 1; $i<= $month ; $i++ )
                                                <?php
                                                $k = 0;
                                                $tangage = 0;
                                                if ($i < 10) {
                                                    $temp_month = '0' . $i;
                                                }else{
                                                   $temp_month = $i; 
                                                }
                                                $start_limit = $year . '-' . $temp_month . '-01';
                                                $end_limit = $year . '-' . $temp_month . '-31';
                                               
                                                foreach ($data as $key => $value) {
                                                    if ($value['date'] >= $start_limit && $value['date'] <= $end_limit) {
                                                        if ($value['labour_id'] == $labour->id) {

                                                            $k++;
                                                            $tangage +=$value['tonnage'];
                                                        }
                                                    }
//                                                       
                                                }
                                                ?>
                                                <td>{{$tangage}}</td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                <td><b>Delivery</b></td>
                                                @for($i = 1; $i<= $month ; $i++ )
                                                <?php
                                                $dc_id_list = array();
//                                                $k = 0;
                                                $tangage = 0;
                                                if ($i < 10) {
                                                    $temp_month = '0' . $i;
                                                }else{
                                                    $temp_month = $i;
                                                }
                                                $start_limit = $year . '-' . $temp_month . '-01';
                                                $end_limit = $year . '-' . $temp_month . '-31';

                                                foreach ($data as $key => $value) {
                                                    if ($value['date'] >= $start_limit && $value['date'] <= $end_limit) {
                                                        if ($value['labour_id'] == $labour->id) {
                                                            array_push($dc_id_list, $value['delivery_id']);
//                                                            $k++;
                                                            $tangage +=$value['tonnage'];
                                                        }
                                                    }
                                                }
                                                ?>
                                                <td><?php
                                                $dc_id_list = array_unique($dc_id_list);
                                                ?>{{count($dc_id_list)}}</td>
                                                @endfor
                                            </tr>
                                        <?php } ?>
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