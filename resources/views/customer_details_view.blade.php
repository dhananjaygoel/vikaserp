@extends('layouts.master')
@section('title','Customer Details')
@section('content')
<style>
    .customer-detail-table b{
        margin-right: 10px;
    }
    #settle_due_modal .modal-content{
        margin: 210px auto;
        width: 400px;
    }
    .modal-settle-div{
         width: 40%;
         margin-left: 30%
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Customer Details</span></li>
                </ol>
                <div class="filter-block">
                   <form action="{{url('customer_list')}}" method="GET" id="orderForm">
                        <h1 class="pull-left">Customer Details</h1>
                        <div class="col-md-2 pull-right">                              
                            <select class="form-control" id="user_filter3" name="fulfilled_filter" onchange="this.form.submit();">
                                <option value="As of Today" >As of Today</option>
                                <option value="3 days" >3 days</option>
                                <option value="A Week" >A Week</option>
                            </select>                            
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
                        @if(count($customers) > 0)
                        <div class="table-responsive customer-detail-table">
                            <table id="table-example" class="table table-hover">                                
                                <tbody>                                    
                                    @foreach($customers as $c)
                                    <?php
                                        $total_due_amount=0;
                                        $credit_period = $c->credit_period;
                                        foreach($c['delivery_challan'] as $challan){
                                            $challan_date = $challan->created_at;
                                            $due_date = date('Y-m-d', strtotime($challan_date. " + ".$credit_period." days"));
                                        }
                                        $current_date = date('Y-m-d');
                                    ?>
                                    @if($due_date>=$current_date)
                                    <tr>
                                        <td><b>Customer Name:</b>
                                            @if(isset($c->tally_name) && !empty($c->tally_name)){{$c->tally_name}}@else Test User @endif
                                        </td>
                                    </tr>
                                    <?php
                                        $total_due_amount=0;
                                        foreach($c['delivery_challan'] as $challan){
                                            $total_due_amount=$total_due_amount+$challan->grand_price;
                                            $settled_amount=0;
                                            foreach($challan['challan_receipt'] as $receipt){
                                                $settled_amount=$settled_amount+$receipt->settled_amount;
                                            }
                                        }
                                    ?>
                                    <tr><td><b>Due Amount: </b>{{$total_due_amount}}</td></tr>
                                    <tr>
                                        <td><b>Unsettled Amount:</b> {{$total_due_amount-$settled_amount}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Location: </b>
                                            @foreach($city as $town)
                                            @if($town->id == $c->city)
                                            {{ $town->city_name }}
                                            @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Credit Period:</b> {{$credit_period}} days</td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>                            
                            <span class="clearfix"></span><br>
                            <span class="clearfix"></span>                            
                            
                            <table id="table-example2" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>Delivery Challan</th>
                                        <th>Due date</th>
                                        <th>Total Amount</th>
                                        <th>Settled Amount</th>
                                        <th>Due Payment</th>
                                        @if(Auth::user()->role_id ==0 )
                                        <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $c)                                        
                                        @foreach($c['delivery_challan'] as $challan)
                                            <?php
                                                $i=1;$total_due_amount=0;                                             
                                            ?>
                                            <?php    
                                                $total_due_amount=$total_due_amount+$challan->grand_price;
                                                $settled_amount=0;
                                                foreach($challan['challan_receipt'] as $receipt){
                                                    $settled_amount=$settled_amount+$receipt->settled_amount;
                                                }                                                 
                                            ?>
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$challan->serial_number}}</td>
                                            <td><?php
                                                $challan_date = $challan->created_at;
                                                $due_date = date('Y-m-d', strtotime($challan_date. " + ".$credit_period." days"));                                            
                                                ?>
                                                {{$due_date}}
                                            </td>                                            
                                            <td>
                                                {{$total_due_amount}}
                                            </td>
                                            <td>
                                                {{$settled_amount}}
                                            </td>
                                            <td>
                                                {{$total_due_amount-$settled_amount}}
                                            </td>
                                            @if(Auth::user()->role_id ==6 )
                                            <td>
                                                <button class="btn btn-primary settle-payment"  data-serial_no="{{$challan->serial_number}}" data-challan_id="{{$challan->id}}" data-due_amount="{{$total_due_amount-$settled_amount}}" >
                                                    Settle
                                                </button>
                                            </td>
                                            @endif
                                        @endforeach                                    
                                    @endforeach
                                </tbody>
                            </table>
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
<div class="modal fade" id="settle_due_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
            <form id="settle_price_form" method="post" action="{{URL::action('ReceiptMasterController@settle_amount')}}" accept-charset="UTF-8" >    
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                        <h4 id="amount_label">Enter amount to settle For <span id="serial-no"></span></h4>
                        <div class=" modal-settle-div text-center">
                            <input class="form-control" id="modal_price" name="model_price"  onkeypress=" return numbersOnly(this,event,true,true);">                            
                            <input type="hidden" id="modal-challan" name="challan_id">
                            <input type="hidden" value="{{$customers[0]->id}}" name="customer_id">
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary modal-price-save" >Settle</button>
                    <button class="btn btn-primary modal-price-save" data-dismiss="modal">Back</button>
                </div>
            </form>    
        </div>
    </div>
</div>
@stop