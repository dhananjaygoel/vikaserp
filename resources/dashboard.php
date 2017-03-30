@extends('layouts.master')
@section('title','Dashboard')
@section('content')
<script src="http://code.highcharts.com/highcharts.js"></script>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Dashboard</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Dashboard</h1>
                    <div class="pull-right top-page-ui">                        
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 2)
                        <div class="row text-center ">
                            <div class="col-md-12">
                                <a href="{{url('orders/create')}}" class="btn btn-primary btn-lg text-center button_indexright">
                                    <i class="fa fa-plus-circle fa-lg"></i> Place Order
                                </a>
                                <a href="{{url('inquiry/create')}}" class="btn btn-primary btn-lg text-center ">
                                    <i class="fa fa-plus-circle fa-lg"></i> Add Inquiry
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->role_id == 0)
        <div class="row">
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('orders')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-user red-bg"></i>
                                    <span class="headline">Total Order </span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                            {{$order}}
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('inquiry?inquiry_filter=Pending')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-money green-bg"></i>
                        <span class="headline">Pending Inquiries</span>
                        <span class="value">
                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                                <!--{{$pending_inquiry}}-->
                                {{round($inquiry_pending_sum,2)}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('orders?order_filter=pending&party_filter=&fulfilled_filter=&location_filter=&size_filter=')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-shopping-cart emerald-bg"></i>
                        <span class="headline">Pending Order</span>
                        <span class="value">
                            <span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
                                <!--{{$pending_order}}-->
                                {{round($order_pending_sum,2)}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('inquiry')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-eye yellow-bg"></i>
                                    <span class="headline">Total Inquiries </span>
                                    <span class="value">
                                        <span class="timer" data-from="539" data-to="12526" data-speed="1100">
                                            {{$inquiry}}
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('delivery_order')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa  fa-tasks red-bg"></i>
                                    <span class="headline">Total Delivery Order</span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                            {{round($deliver_sum, 2)}}Ton
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <a class="indexlink" href="{{url('delivery_order?_token=yx1phrqi9pseT2vXrbHWBfhcyyN7YPol1EMJdj6k&order_status=Inprocess')}}">
                    <div class="main-box infographic-box">
                        <i class="fa fa-archive emerald-bg"></i>
                        <span class="headline">Pending Delivery Order</span>
                        <span class="value">
                            <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                {{round($deliver_pending_sum,2)}}Ton
                            </span>
                        </span>
                    </div>
                </a>
            </div>
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('delivery_challan?status_filter=completed')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-desktop green-bg"></i>
                                    <span class="headline">Total Challan</span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                            {{round($delivery_challan_sum,2)}}Ton
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
            <!--            <div class="col-lg-3 col-sm-6 col-xs-12">
                            <a class="indexlink" href="{{url('purchase_orders')}}">
                                <div class="main-box infographic-box">
                                    <i class="fa fa-file-text-o yellow-bg"></i>
                                    <span class="headline">Total Purchase Order</span>
                                    <span class="value">
                                        <span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
                                            {{round($purc_order_sum,2)}}Ton
                                        </span>
                                    </span>
                                </div>
                            </a>
                        </div>-->
        </div>
        @endif
        <br/>
        <hr>  
        <!--graph-->
        <div class="row text-center ">
            <div class="col-md-12">
                <div class="col-lg-4 col-sm-6 col-xs-12">
                    <h4>Inquiry</h4>
                    <div id="inquiry" style="height: 250px;"></div>
                </div>
                <div class="col-lg-4 col-sm-6 col-xs-12">
                    <h4>Order</h4>
                    <div id="order" style="height: 250px;"></div>
                </div>
                <div class="col-lg-4 col-sm-6 col-xs-12">
                    <h4>Delivery Challan</h4>
                    <div id="deliverychallan" style="height: 250px;"></div>
                </div>
            </div>
        </div>
        <br/>
        <br/>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <h1 class="pull-left">Labour and loaded by chart</h1>
        <div class="form-group pull-right">
            <div class="col-md-12">
                <form method="GET" action="javascript:;">
                    <select class="form-control" id="labour_loaded_by_chart_filter" name="labour-loaded-by-chart-filter">
                        <option value="Day wise">Day wise</option>
                        <option value="Month wise">Month wise</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <div class="main-box-body main_contents clearfix">
                <div class="table-responsive">
                    <table id="table-example" class="table table-bordered complex-data-table">
                        <tbody>
                            <tr>
                                <td colspan="2" rowspan="1"></td>
                                <td colspan="31"><b>Date</b></td>
                            </tr>
                            <tr class="text-bold">
                                <td colspan="2"></td>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>10</td>
                                <td>11</td>
                                <td>12</td>
                                <td>13</td>
                                <td>14</td>
                                <td>15</td>
                                <td>16</td>
                                <td>17</td>
                                <td>18</td>
                                <td>19</td>
                                <td>20</td>
                                <td>21</td>
                                <td>22</td>
                                <td>23</td>
                                <td>24</td>
                                <td>25</td>
                                <td>26</td>
                                <td>27</td>
                                <td>28</td>
                                <td>29</td>
                                <td>30</td>
                                <td>31</td>
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
                                <td>16.56</td>
                                <td>74.33</td>
                                <td>15.42</td>
                                <td>20.44</td>
                                <td>42.25</td>
                                <td>66.24</td>
                                <td>24.36</td>
                                <td>30.42</td>
                                <td>47.58</td>
                                <td>32.25</td>
                                <td>38.35</td>
                                <td>46.06</td>
                                <td>61.47</td>
                                <td>63.09</td>
                                <td>06.45</td>
                                <td>22.75</td>
                                <td>34.65</td>
                                <td>55.44</td>
                                <td>68.65</td>
                            </tr>
                            <tr>
                                <td><b>Delivery<b></td>
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
                                <td>38.33</td>
                                <td>34.75</td>
                                <td>36.96</td>
                                <td>24.42</td>
                                <td>18.63</td>
                                <td>16.35</td>
                                <td>23.64</td>
                                <td>11.78</td>
                                <td>25.96</td>
                                <td>23.65</td>
                                <td>12.42</td>
                                <td>18.85</td>
                                <td>19.34</td>
                                <td>33.46</td>
                                <td>19.36</td>
                                <td>33.45</td>
                                <td>39.65</td>
                                <td>12.79</td>
                                <td>13.45</td>
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
                                <td>16.36</td>
                                <td>74.52</td>
                                <td>15.36</td>
                                <td>20.36</td>
                                <td>42.04</td>
                                <td>66.36</td>
                                <td>24.12</td>
                                <td>30.42</td>
                                <td>47.85</td>
                                <td>32.96</td>
                                <td>38.75</td>
                                <td>46.75</td>
                                <td>61.35</td>
                                <td>63.21</td>
                                <td>06.46</td>
                                <td>22.47</td>
                                <td>34.68</td>
                                <td>55.42</td>
                                <td>68.36</td>
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
                                <td>38.42</td>
                                <td>34.63</td>
                                <td>36.42</td>
                                <td>24.15</td>
                                <td>18.69</td>
                                <td>16.75</td>
                                <td>23.95</td>
                                <td>11.45</td>
                                <td>25.69</td>
                                <td>23.75</td>
                                <td>12.91</td>
                                <td>18.15</td>
                                <td>19.65</td>
                                <td>33.45</td>
                                <td>19.85</td>
                                <td>33.16</td>
                                <td>39.14</td>
                                <td>12.25</td>
                                <td>13.15</td>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <div class="table-responsive" style="display: none;">
                    <table id="table-example" class="table table-bordered complex-data-table">
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
                                <td><b>Delivery<b></td>
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



<script type="text/javascript">
    var inquiry_stats = <?php echo json_encode(isset($inquiries_stats_all) ? $inquiries_stats_all : ''); ?>;
    var order_stats = <?php echo json_encode(isset($orders_stats_all) ? $orders_stats_all : ''); ?>;
    var delivery_challan_stats = <?php echo json_encode(isset($delivery_challan_stats_all) ? $delivery_challan_stats_all : ''); ?>;
                            
</script>
@endsection
