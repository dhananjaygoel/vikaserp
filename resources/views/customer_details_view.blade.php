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
    .save-unsettled-amount{
        font-size: 17px;
        cursor: pointer;
        margin-left: 5px;
    }
    .input-unsettled{
        display: inline; 
        width: 10%;
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
                    <form action="{{URL::action('CustomerController@get_customer_details', ['id' => $customer->id])}}" method="GET" id="customer-details-form">
                        <h1 class="pull-left">Customer Details</h1>
                        @if(Auth::user()->role_id ==0 )
                        <a href="" id="print-customer-details" data-toggle="modal" data-target="#print_customers_details" class="btn btn-primary pull-right" style=" margin-right: 8px !important;">
                            Print
                        </a>
                        <div class="col-md-2 pull-right">                              
                            <select class="form-control" id="settle_filter" name="settle_filter" onchange="this.form.submit();">
                                <option value="Unsettled" <?php if (Input::get('settle_filter') == 'Unsettled') echo "selected=''"; ?> >Unsettled</option>
                                <option value="Settled" <?php if (Input::get('settle_filter') == 'Settled') echo "selected=''"; ?>>Settled</option>                                
                            </select>                            
                        </div>
                        @endif
                        <div class="col-md-2 pull-right">                              
                            <select class="form-control" id="date_filter" name="date_filter" onchange="this.form.submit();">
                                <option value="1" <?php if (Input::get('date_filter') == 1) echo "selected=''"; ?>>As of Today</option>
                                <option value="3" <?php if (Input::get('date_filter') == 3) echo "selected=''"; ?>>3 days</option>
                                <option value="7" <?php if (Input::get('date_filter') == 7) echo "selected=''"; ?>>A Week</option>
                            </select>                            
                        </div>                        
                    </form>                    
                </div>
                <a href="" id="print-customer-details" data-toggle="modal" data-target="#show_all_logs" class="btn btn-primary pull-right" style=" margin-right: 8px !important;">
                    View Log(s)
                </a>
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
                        @if(isset($customer) > 0)
                        <div class="table-responsive customer-detail-table">
                            <table id="table-example" class="table table-hover">                                
                                <tbody>
                                    <?php
                                    $total_due_amount = 0;
                                    $unsettled_amount = 0;
                                    $credit_period = $customer->credit_period;
                                    ?>                                                                        
                                    <tr>
                                        <td><b>Customer Name:</b>
                                            @if(isset($customer->tally_name) && !empty($customer->tally_name)){{$customer->tally_name}} @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Location: </b>
                                            @foreach($delivery_location as $location)
                                            @if($customer->delivery_location_id==$location->id)
                                            {{$location->area_name}}                                                         
                                            @endif
                                            @endforeach
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <?php
                                        foreach ($customer['customer_receipt'] as $receipt) {
                                            $unsettled_amount = $unsettled_amount + $receipt->settled_amount;
                                        }
                                        foreach ($customer['customer_receipt_debit'] as $receipt) {
                                            $unsettled_amount = $unsettled_amount - $receipt->settled_amount;
                                        }

                                        $total_due_amount = 0;
                                        $settled_challan_amount = 0;
                                        foreach ($customer['delivery_challan'] as $challan) {
                                            if ($challan->challan_status == "completed") {
                                                $total_due_amount = $total_due_amount + $challan->grand_price;
                                                $settled_challan_amount = $settled_challan_amount + $challan->settle_amount;
                                            }
                                        }
                                        $total_due_amount = $total_due_amount - $settled_challan_amount;
                                        $unsettled_amount = $unsettled_amount - $settled_challan_amount;
                                        ?>
                                        <td><b>Unsettled Amount:</b> 

                                            @if(isset($unsettled_amount))
                                            @if(Auth::user()->role_id ==0)
                                            <input type="text" class="form-control input-unsettled" data-price="{{$unsettled_amount}}" data-due="{{$total_due_amount}}" value="{{$unsettled_amount}}"> <i class="fa fa-save save-unsettled-amount" data-id="{{$customer->id}}"></i>
                                            @endif
                                            @if(Auth::user()->role_id ==6)
                                            {{$unsettled_amount}}
                                            @endif                                                
                                            @endif

                                            <a href="javascript:void(0)" class="btn btn-primary pull-right pass-journal-entry {{($is_discount_user=='true' | $total_due_amount <> 0 | $unsettled_amount ==0)?'disabled':''}}" data-price="{{$unsettled_amount}}" style=" margin-right: 8px !important;" data-id="{{$customer->id}}"  title="Pass Journal Entry">
                                                Pass Journal Entry
                                            </a> 
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <td><b>@if(Auth::user()->role_id ==6 )
                                                Total Due Amount: 
                                                @endif
                                                @if(Auth::user()->role_id ==0 )
                                                Total Due Payment: 
                                                @endif
                                            </b>
                                            {{$total_due_amount}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Credit Period:</b> {{$credit_period}} days</td>
                                    </tr>
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
                                        @if(Input::get('settle_filter')!='Settled')
                                        <th>Due Payment</th>
                                        @endif
                                        <th>Action</th>                                        
                                    </tr>
                                </thead>
                                <?php //dd($delivery_challans);  ?>
                                <tbody>                                        
                                    @if(isset($delivery_challans) && count((array)$delivery_challans)>0 && $delivery_challans!="")
                                    <?php $i = 1; ?>
                                    @foreach($delivery_challans as $challan)
                                    <?php
                                    $total_due_amount = 0;
                                    $settled_amount = 0;
                                    ?>
                                    <?php
                                    $total_due_amount = $total_due_amount + $challan->grand_price;
                                    if (isset($challan->settle_amount) && $challan->settle_amount != "") {
                                        $settled_amount = $challan->settle_amount;
                                    }
                                    ?>                                        
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$challan->serial_number}}</td>
                                        <td><?php
                                    $challan_date = $challan->created_at;
                                    $due_date = date('Y-m-d', strtotime($challan_date . " + " . $credit_period . " days"));
                                    ?>
                                            {{$due_date}}
                                        </td>                                            
                                        <td>
                                            {{$total_due_amount}}
                                        </td>
                                        <td>
                                            {{$settled_amount}}
                                        </td>
                                        @if(Input::get('settle_filter')!='Settled')
                                        <td>
                                            {{$total_due_amount-$settled_amount}}
                                        </td>
                                        @endif
                                        @if(Auth::user()->role_id ==6 )
                                        <td>
                                            <button class="btn btn-primary settle-payment"  data-serial_no="{{$challan->serial_number}}" data-challan_id="{{$challan->id}}" data-due_amount="{{$total_due_amount-$settled_amount}}" >
                                                Settle
                                            </button>
                                        </td>
                                        @endif
                                        @if(Auth::user()->role_id ==0 )
                                        <td>                                                
                                            @if(Input::get('settle_filter')=='Settled')
                                            <button class="btn btn-primary update-payment"  data-serial_no="{{$challan->serial_number}}" data-challan_id="{{$challan->id}}" data-settle_amount="{{$settled_amount}}" >Update</button>
                                            @else
                                            <button class="btn btn-primary settle-payment"  data-serial_no="{{$challan->serial_number}}" data-challan_id="{{$challan->id}}" data-due_amount="{{$total_due_amount-$settled_amount}}" >Settle</button>
                                            @endif                                                
                                        </td>
                                        @endif
                                    </tr>                                        
                                    @endforeach
                                    @else
                                    <tr><td></td><td>No records Available</td></tr>
                                    @endif
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
            <form id="settle_price_form" method="post" action="@if(Input::get('settle_filter')=='Settled'){{URL::action('ReceiptMasterController@update_settle_amount')}}@else {{URL::action('ReceiptMasterController@settle_amount')}} @endif" accept-charset="UTF-8" >    
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-body text-center">
                    <h4 id="amount_label">Enter amount to settle For <span id="serial-no"></span></h4>
                    <div class=" modal-settle-div text-center">
                        @if(Input::get('settle_filter')=='Settled')
                        <input class="form-control" id="modal_update_price" name="model_price" data-price="" onkeypress=" return numbersOnly(this, event, true, true);">
                        @else
                        <input class="form-control" id="modal_price" name="model_price" data-price="" onkeypress=" return numbersOnly(this, event, true, true);">
                        @endif    
                        <input type="hidden" id="modal-challan" name="challan_id">
                        <input type="hidden" value="{{$customer->id}}" name="customer_id">
                    </div>
                    <span id="amount-error" style="display:none; color:red"></span>
                </div>
                <div class="modal-footer">
                    @if(Auth::user()->role_id ==6)
                    <button class="btn btn-primary modal-settle-price" >Settle</button>
                    @endif
                    @if(Auth::user()->role_id ==0)
                    @if(Input::get('settle_filter')=='Settled')
                    <button class="btn btn-primary modal_update_settle_price" >Update</button>
                    @else
                    <button class="btn btn-primary modal-settle-price" >Settle</button>
                    @endif
                    @endif
                    <button class="btn btn-primary" data-dismiss="modal">Back</button>
                </div>
            </form>    
        </div>
    </div>
</div>
<div class="modal fade" id="print_customers_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <button type="button"  data-id="{{$customer->id}}" class="btn btn-primary form_button_footer print_customers_details" >Print</button>
                    <button type="button" class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<?php
$data = array();
$final = array();
$data_temp = array();
$i = 0;
foreach ($customer['customer_receipt'] as $key => $value) {
    $data[$i++] = [
        'id' => $value['id'],
        'customer_id' => $value['customer_id'],
        'settled_amount_cr' => $value['settled_amount'],
        'settled_amount_dr' => 0,
        'debited_by_type' => $value['debited_by_type'],
        'receipt_id' => $value['receipt_id'],
        'narration' => $value['narration'],
        'created_at' => $value['created_at'],
        'updated_at' => $value['updated_at'],
        'deleted_at' => $value['deleted_at'],
    ];
}

foreach ($customer['customer_receipt_debit'] as $key => $value) {
    $data[$i++] = [
        'id' => $value['id'],
        'customer_id' => $value['customer_id'],
        'settled_amount_cr' => 0,
        'settled_amount_dr' => $value['settled_amount'],
        'debited_by_type' => $value['debited_by_type'],
        'receipt_id' => $value['receipt_id'],
        'narration' => $value['narration'],
        'created_at' => $value['created_at'],
        'updated_at' => $value['updated_at'],
        'deleted_at' => $value['deleted_at'],
    ];
}



$data_temp = $data;

foreach ($data as $key => $value) {
    foreach ($data_temp as $key_temp => $value_temp) {
        if (($value['receipt_id'] == $value_temp['receipt_id']) && ($key <> $key_temp)) {
            if ($value_temp['settled_amount_cr'] > 0) {
                $data[$key] = $value_temp;
                $data[$key]['settled_amount_dr'] = $value['settled_amount_dr'];
            }
            unset($data[$key_temp]);
        }
    }
}
$data = array_values($data);
?>

<div class="modal fade" id="show_all_logs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="row print_time "> 
                    @if(count((array)$customer['customer_receipt']))
                    <table id="table-example2" class="table table-hover">
                        <thead>
                            <tr>
                                <th class="col-md-1">#</th>
                                <th>Date</th>
                                <th>Receipt Type</th>
                                <th>Credit</th>
                                <th>Debit</th>
                            </tr>
                        </thead>

                        <tbody> 
                            <?php
                            $k = 1;
                            ?>

                            @foreach($data as $key => $customer_receipt)                           
                            <tr>

                                <td>{{$k++}}</td>
                                <td>{{$customer_receipt['created_at']}}</td>
                                <td>
                                    <?php
                                    if ($customer_receipt['debited_by_type'] == 2)
                                        $receiptType = "Bank";
                                    else if ($customer_receipt['debited_by_type'] == 3)
                                        $receiptType = "Cash";
                                    else
                                        $receiptType = "Journal"
                                        ?>  
                                    {{$receiptType}}
                                </td>
                                <td>{{$customer_receipt['settled_amount_cr']}}</td>
                                <td>{{$customer_receipt['settled_amount_dr']}}</td>

                            </tr>
                            @endforeach                            
                        </tbody>                           
                    </table>
                    @else
                    <p> No logs available </p>

                    @endif
                </div>                
                <hr>
                <div>

                    <button type="button" class="btn btn-default form_button_footer" data-dismiss="modal">Ok</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@stop