@extends('layouts.master')
@section('title','Labour chart')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <h1 class="pull-left">Labours by chart</h1>
                <div class="form-group pull-right">

                    <div class="col-md-12">
                        <form method="GET" action="javascript:;">
                            <select class="form-control" id="labour_chart_filter" name="labour_chart_filter">
                                <option value="Day">Day wise</option>
                                <option value="Month">Month wise</option>
                            </select>
                        </form>
                    </div>

                </div>
                <div class="col-lg-6"></div>
                <div class="col-lg-6">

                    <div class="form-group ">
                       

                        <div class="col-md-12">
                            <div class="search_form_wrapper delivery_challan_search_form_wrapper">
                                <form class="search_form" method="GET" action="{{url('performance/labours/labour-performance')}}">
                                   <input name="performance" id="performance-days" class="form-control performance-days" value="{{date('F-Y', mktime(0, 0, 0))}}"/>                                   
                                   
                                    <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                                </form>
                                
                            </div>
                        </div>


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

                            <div class="table-responsive">
                                <table id="day-wise" class="table table-bordered complex-data-table">
                                    <tbody>
                                        <?php
                                        $today = date('d');
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
                                        <?php foreach ($labours as $labour) { ?>
                                            <tr>
                                                <td rowspan="2"><b>{{$labour->first_name}} {{$labour->last_name}}</B></td>
                                                <td><b>Tonnage</b></td>
                                                @for($i = 1; $i<= $today ; $i++ )
                                                <?php
                                                $k = 0;
                                                $tangage = 0;
                                                foreach ($data as $key => $value) {
                                                    if ($value['date'] == '2017-04-' . $i) {
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
                                                $k = 0;
                                                $tangage = 0;
                                                foreach ($data as $key => $value) {
                                                    if ($value['date'] == '2017-04-' . $i) {
                                                        if ($value['labour_id'] == $labour->id) {
                                                            $k++;
                                                            $tangage +=$value['tonnage'];
                                                        }
                                                    }
//                                                       
                                                }
                                                ?>
                                                <td>{{$k}}</td>
                                                @endfor
                                            </tr>
<?php } ?>

                                    </tbody>
                                </table>

                            </div>
                            <div class="table-responsive" >
                                <table id="month-wise" class="table table-bordered complex-data-table" style="display: none">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" rowspan="1"></td>
                                            <td colspan="31"><b>Month</b></td>
                                        </tr>
                                        <tr class="text-bold">
                                            <td colspan="2"></td>
                                            <td>Jan</td>
                                            <td>Feb</td>
                                            <td>March</td>
                                            <td>April</td>
                                            <td>May</td>
                                            <td>Jun</td>
                                            <td>Jully</td>
                                            <td>Aug</td>
                                            <td>Sep</td>
                                            <td>Oct</td>
                                            <td>Nov</td>
                                            <td>Dec</td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2"><b>Rakshit</B></td>
                                            <td><b>Tonnage</b></td>
                                            <td>18.25</td>
                                            <td>24.12</td>
                                            <td>08.34</td>
                                            <td>40.34</td>
                                            <td>68.14</td>
                                            <td>43.45</td>
                                            <td>41.44</td>
                                            <td>28.12</td>
                                            <td>26.75</td>
                                            <td>19.95</td>
                                            <td>24.55</td>
                                            <td>11.75</td>
                                        </tr>
                                        <tr>
                                            <td><b>Delivery</b></td>
                                            <td>12.45</td>
                                            <td>16.65</td>
                                            <td>24.75</td>
                                            <td>20.12</td>
                                            <td>34.24</td>
                                            <td>16.75</td>
                                            <td>32.35</td>
                                            <td>17.14</td>
                                            <td>23.04</td>
                                            <td>17.12</td>
                                            <td>16.33</td>
                                            <td>26.42</td>
                                        </tr>

                                        <tr>
                                            <td rowspan="2"><b>Nikhil</b></td>
                                            <td><b>Tonnage</b></td>
                                            <td>18.45</td>
                                            <td>24.65</td>
                                            <td>08.41</td>
                                            <td>40.23</td>
                                            <td>68.41</td>
                                            <td>43.23</td>
                                            <td>41.12</td>
                                            <td>28.78</td>
                                            <td>26.98</td>
                                            <td>19.75</td>
                                            <td>24.25</td>
                                            <td>11.96</td>
                                        </tr>

                                        <tr>
                                            <td><b>Delivery</b></td>
                                            <td>12.46</td>
                                            <td>16.34</td>
                                            <td>24.42</td>
                                            <td>20.34</td>
                                            <td>34.02</td>
                                            <td>16.36</td>
                                            <td>32.15</td>
                                            <td>17.11</td>
                                            <td>23.21</td>
                                            <td>17.32</td>
                                            <td>16.42</td>
                                            <td>26.15</td>
                                        </tr>
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