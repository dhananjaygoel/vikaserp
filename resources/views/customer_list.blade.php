@extends('layouts.master')
@section('title','Customer List')
@section('content')
<style>
    .save-unsettled-amount{
        font-size: 17px;
        cursor: pointer;
        margin-left: 5px;
    }
    .input-unsettled{
        display: inline; 
        width: 50%;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Customers</span></li>
                </ol>

                <div class="filter-block">
                    <form action="{{url('due-payment')}}" method="GET" id="due-payment-form">
                        <h1 class="pull-left">Customers</h1> 
                        <a href="" id="print-account-customers" data-toggle="modal" data-target="#print_acount_customers" class="btn btn-primary pull-right" style=" margin-right: 8px !important;">
                            Print
                        </a>
                        <div class="col-md-2 pull-right">
                            @if(Auth::user()->role_id ==6 ||Auth::user()->role_id ==0)
                            <select class="form-control" id="location_filter" name="location_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Select Location--</option>
                                @foreach($delivery_location as $location)
                                @if($location->id!=0)
                                <option <?php if (Input::get('location_filter') == $location->id) echo 'selected=""'; ?> value="{{$location->id}}">{{$location->area_name}}</option>
                                @endif
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-md-2 pull-right" style="padding: 0; margin-right: 15px">
                            @if(Auth::user()->role_id ==6 ||Auth::user()->role_id ==0)                            
                            <select class="form-control" id="territory_filter" name="territory_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Select Territory--</option>
                                @foreach($territories as $territory)
                                @if($territory->id!=0)
                                <option <?php if (Input::get('territory_filter') == $territory->id) echo 'selected=""'; ?> value="{{$territory->id}}">{{$territory->teritory_name}}</option>
                                @endif
                                @endforeach
                            </select>
                            @endif                            
                        </div> 
                        <div class="col-md-2 pull-right">  
                            @if(Auth::user()->role_id ==6 ||Auth::user()->role_id ==0)
                            <select class="form-control" id="date_filter" name="date_filter" onchange="this.form.submit();">
                                <option value="1" <?php if (Input::get('date_filter') == 1) echo "selected=''"; ?>>As of Today</option>
                                <option value="3" <?php if (Input::get('date_filter') == 3) echo "selected=''"; ?>>3 days</option>
                                <option value="7" <?php if (Input::get('date_filter') == 7) echo "selected=''"; ?>>A Week</option>
                            </select>
                            @endif
                        </div>
                        <div class="input-group col-md-2 pull-right" style="margin-right: 10px">
                            @if(Auth::user()->role_id ==6 ||Auth::user()->role_id ==0)
                            <input class="form-control " id="search_filter" placeholder="Customer Name" name="search" value="{{Input::get('search')}}"  type="text">
                            <input type='hidden' placeholder="Customer Name" value="{{Input::get('size_filter')}}" id="order_size_temp" autocomplete="off" name="size_filter" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" onclick="javascript:document.getElementById('orderForm').submit();">
                                    <i class="fa fa-search" id="search_icon"></i>
                                </button>
                            </span>
                            @endif
                        </div>
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
                        @if(count((array)$customers) > 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>Customer Name</th>
                                        <th>Due Amount</th>
                                        <th>Unsettled Amount</th>
                                        <th>Location</th>
                                        @if(Auth::user()->role_id ==0)
                                        <th>Collection User</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = ($customers->currentPage() - 1) * $customers->perPage() + 1;
                                    ?>
                                    @foreach($customers as $c)
                                    <?php
                                    $total_due_amount = 0;
                                    $credit_period = $c->credit_period;
                                    foreach ($c['delivery_challan'] as $challan) {
                                        $challan_date = $challan->created_at;
                                        $due_date = date('Y-m-d', strtotime($challan_date . " + " . $credit_period . " days"));
                                    }
                                    $current_date = date('Y-m-d');
                                    $limit_date = date('Y-m-d');
//                                        if(Input::get('date_filter')!=""){
//                                            $days = Input::get('date_filter');
//                                            $limit_date = date('Y-m-d', strtotime($current_date. " + ".$days." days"));
//                                        }                                        
                                    ?>                                    
                                    <tr>
                                        <td class="col-md-1">{{$i++}}</td>
                                        <td><a href="{{url('customer_details/'.$c->id)}}">@if(isset($c->tally_name) && !empty($c->tally_name)){{$c->tally_name}}@else Test User @endif</a></td>
                                        <?php
                                        $total_due_amount = 0;
                                        $unsettled_amount = 0;
                                        $settled_challan_amount = 0;
                                        foreach ($c['delivery_challan'] as $challan) {
                                            if ($challan->challan_status == "completed") {
                                                $total_due_amount = $total_due_amount + $challan->grand_price;
                                                $settled_challan_amount = $settled_challan_amount + $challan->settle_amount;
                                            }
                                        }
                                        foreach ($c['customer_receipt'] as $receipt) {
                                            $unsettled_amount = $unsettled_amount + $receipt->settled_amount;
                                        }
                                        foreach ($c['customer_receipt_debit'] as $receipt) {
                                            $unsettled_amount = $unsettled_amount - $receipt->settled_amount;
                                        }
                                        $total_due_amount = $total_due_amount - $settled_challan_amount;
                                        $unsettled_amount = $unsettled_amount - $settled_challan_amount;
                                        ?>
                                        <td>{{$total_due_amount}}</td>
                                        <td>
                                            @if(Auth::user()->role_id ==0)
                                            <input type="text" class="form-control input-unsettled" value="{{$unsettled_amount}}" data-price="{{$unsettled_amount}}"> <i class="fa fa-save save-unsettled-amount" data-id="{{$c->id}}" data-amount="{{$c->id}}"></i>
                                            @endif
                                            @if(Auth::user()->role_id ==6)
                                            {{$unsettled_amount}}
                                            @endif
                                        </td>
                                        <td>                                            
                                            @foreach($delivery_location as $location)
                                            @if($c->delivery_location_id==$location->id)
                                            {{$location->area_name}}                                                         
                                            @endif
                                            @endforeach
                                        </td>
                                        @if(Auth::user()->role_id ==0)
                                        <td>
                                            <?php
                                            if (isset($c['collection_user_location'])) {
                                                $del_locations = $c['collection_user_location'];
                                                foreach ($del_locations as $del_loc) {
                                                    if (isset($del_loc['collection_user'][0])) {
                                                        echo $del_loc['collection_user'][0]->first_name;
                                                        echo " ";
                                                        echo $del_loc['collection_user'][0]->last_name;
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php
                                if (isset($_GET['search']) && Request::get('search') != '') {
                                    echo $customers->appends(array('search' => Request::get('search')))->render();
                                } else {
                                    echo $customers->render();
                                }
                                ?>
                            </span>
                            <span class="clearfix"></span>

                            @if($customers->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('due-payment')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $customers->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                        @else
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <strong> Currently No customers found </strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="print_acount_customers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <button type="button"  class="btn btn-primary form_button_footer print_account_customers" >Print</button>
                    <button type="button" class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@stop