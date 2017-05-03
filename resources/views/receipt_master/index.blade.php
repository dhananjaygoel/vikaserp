@extends('layouts.master')
@section('title','Receipt Master')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb pull-left">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Receipt Master</span></li>
                </ol>                
            </div> 
        </div>
        <div class="row" style="margin-bottom:10px">
            <div class="col-md-12">
                <div class="col-lg-4">
                    <h4><strong>RECEIPT DATES</strong></h4>
                </div>
                <div class="col-lg-8">
                    <form class="search_form" method="GET" id="searchCustomerForm">
                        <input type="text" placeholder="From" name="search_from_date" class="form-control export_from_date" id="export_from_date" value="{{ isset($from_date) ? $from_date : '' }}">
                        <input type="text" placeholder="To" name="search_to_date" class="form-control export_to_date" id="export_to_date" value="{{ isset($to_date) ? $to_date : ''}}">
                        <input type="submit" name="search_data" value="Search" class="search_button btn btn-primary">
                    </form>                    
                    <button class="btn btn-primary" data-toggle="modal" data-target="#add-receipt"><i class="fa fa-plus"></i> Add</button>
                </div>                    
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="main-box-body main_contents clearfix">
                    <div id="flash_message" class="alert no_data_msg_container"></div>
                    @if(Session::has('error'))
                    <div class="clearfix"> &nbsp;</div>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong> {{ Session::get('error') }} </strong>
                    </div>
                    @endif
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-success1">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">&times;</span></button>
                        {{Session::get('success')}}
                    </div>
                    {{--*/ Session::forget('success') /*--}}
                    @endif
                    @if (Session::has('flash_message'))
                    <div id="flash_error" class="alert alert-info no_data_msg_container">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('flash_message') }}
                    </div>
                    {{--*/ Session::forget('flash_message') /*--}}
                    @endif
                    @if(count($receipts)>0)
                    <div class="table-responsive tablepending">
                        <table id="table-example" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th class="text-center" style="width: 15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = ($receipts->currentPage() - 1 ) * $receipts->perPage() + 1; ?>
                                @foreach($receipts as $receipt)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{date("Y-m-d", strtotime($receipt['created_at'])) }}</td>
                                    <td>{{date("H:i:s", strtotime($receipt['created_at'])) }}</td>
                                    <td class="text-center">
                                        <a href="{{URL::action('ReceiptMasterController@edit',['id'=> $receipt->id])}}" class="table-link" title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        @if(Auth::user()->role_id == 0) 
                                        <a href="#" class="table-link danger delete-receipt" data-id="{{$receipt->id}}"  class="table-link" title="Delete">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                        <span class="pull-right">
                            <?php echo $receipts->render(); ?>
                        </span>
                    </div>
                    @else
                    <div class="alert alert-info no_data_msg_container">
                        Currently no user available.
                    </div>
                    @endif
                </div>    
            </div>
        </div>
    </div>
</div>
<div class="modal" id="add-receipt">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Add Receipt</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4 text-center">
                            <a href="{{url()}}/receipt-master/journal" class="btn btn-sm btn-primary">Journal</a>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="{{url()}}/receipt-master/bank" class="btn btn-sm btn-primary">Bank</a>
                        </div>
                        <div class="col-md-4 text-center">
                            <a href="{{url()}}/receipt-master/cash" class="btn btn-sm btn-primary">Cash</a>
                        </div>
                    </div>
                </div>    
            </div>            
        </div>
    </div>
</div>
<div class="modal fade" id="delete_receipt_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                {!! Form::open(array('method'=>'DELETE', 'id'=>'delete_receipt_form'))!!}
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="delete">
                    <?php
                    $us = Auth::user();
                    $us['mobile_number']
                    ?>
                    <div><b>Mobile:</b>
                        {{$us['mobile_number']}}
                        <input type="hidden" name="mobile" value="{{$us['mobile_number']}}"/>
                        <input type="hidden" name="receipt_id" value=""/>
                    </div>
                    <div class="pwd">
                        <div class="pwdl"><b>Password:</b></div>
                        <div class="pwdr"><input class="form-control" id="model_pass" name="model_pass" placeholder="" required="required" type="password"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@stop