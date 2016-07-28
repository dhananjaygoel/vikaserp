@extends('layouts.master')
@section('title','Delete Bulk')
@section('content')
<?php

use Illuminate\Support\Facades\Session;
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('bulk-delete')}}">Bulk Delete</a></li>
                    <li class="active"><span>Bulk Delete</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left"></h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        @if(isset($msg)&&(!empty($msg)))
                        <div id="flash_error" class="alert alert-success no_data_msg_container">{{ucfirst(str_replace('_',' ',$msg))}}</div>
                        @endif
                        <form id="" name="" method="GET" action="{{URL::action('BulkDeleteController@show_result')}}">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if (Session::has('flash_message'))
                            <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('flash_message') }}</div>
                            @endif
                            <div class="row col-md-12">
                                <div class="form-group">
                                    <div class="form-group col-md-4">
                                        <label for="location">Select Module:<span class="mandatory">*</span></label>
                                        <select class="form-control" name="select_module" id="select_module">
                                            <option value="0" selected="">Select Module</option>
                                            <option id="other_location" {{(isset($module) && $module == "inquiry")?'selected':''}} value="inquiry">Inquiry</option>
                                            <option id="other_location" {{(isset($module) && $module == "order")?'selected':''}} value="order">Orders</option>
                                            <option id="other_location" {{(isset($module) && $module == "delivery_order")?'selected':''}} value="delivery_order">Delivery Orders</option>
                                            <option id="other_location" {{(isset($module) && $module == "delivery_challan")?'selected':''}} value="delivery_challan">Delivery Challan</option>
                                            <option id="other_location" {{(isset($module) && $module == "purchase_order")?'selected':''}} value="purchase_order">Purchase Orders</option>
                                            <option id="other_location" {{(isset($module) && $module == "purchase_advice")?'selected':''}} value="purchase_advice">Purchase Advise</option>
                                            <option id="other_location" {{(isset($module) && $module == "purchase_challan")?'selected':''}} value="purchase_challan">Purchase Challan</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4 targetdate">
                                        <label for="date">Select Date: </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" name="expected_date" class="form-control" id="expected_date" value="{{isset($expected_date)?$expected_date:''}}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-2 targetdate">
                                        <label for="date"></label>
                                        <div class="input-group">
                                            <input type="submit" class="btn btn-primary" value="Search">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(isset($bulk_searched_result))
<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <div class="main-box-body main_contents clearfix">
                <div id="empty_select_completed" class="alert alert-danger">
                </div>
                <form id="frmdeleterecords" name="" method="GET" action="{{URL::action('BulkDeleteController@show_result')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @if (Session::has('flash_message'))
                    <div class="alert alert-success alert-success1">
                        <i class="fa fa-check-circle fa-fw fa-lg"></i>
                        <strong>Well done!</strong> User details successfully added.
                    </div> <br/>
                    @endif
                    @if (Session::has('flash_message_error'))
                    <div id="flash_error" class="alert alert-danger no_data_msg_container">{{ Session::get('flash_message_error') }}</div>
                    @endif
                    @if (Session::has('success'))
                    <div class="alert alert-success alert-success1">
                        {{Session::get('success')}}                            
                    </div>
                    @endif

                    @if (Session::has('wrong'))
                    <div class="alert alert-danger alert-success1">
                        {{Session::get('wrong')}}                            
                    </div>
                    @endif

                    @if(isset($result_temp) && !$result_temp->isEmpty() && $result_temp->count())                        
                    <div class="table-responsive">
                        <table id="table-example" class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-1">#</th>
                                    @foreach($head as $header)
                                    <th class="{{($header=='ACTION')?'text-center':''}}">{{$header}}</th>
                                    @endforeach
                                    <th class="delete_completed_heading">
                                        <input type="checkbox" class="delete_completed"> Select All
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = ($result_temp->currentPage() - 1) * $result_temp->perPage() + 1; ?>
                                @foreach($result_data as $trkey=>$tr)
                                <tr id="inquiry_row_{{$tr_id[$trkey]}}">
                                    <td class="">{{$i++}}</td>
                                    @foreach($tr as $tdkey=>$td)
                                    <td class="">{{$td}}</td>
                                    @endforeach
                                    <td><input type="checkbox" class="checkBoxClass" id="{{$tr_id[$trkey]}}" value="{{$tr_id[$trkey]}}" name="delete_seletected_module[{{$tr_id[$trkey]}}]"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" name="select_module" value="{{isset($module)?$module:''}}">
                        <input type="hidden" name="expected_date" value="{{isset($expected_date)?$expected_date:''}}">
                        <input type="hidden" name="page" value="{{(Input::get('page'))?Input::get('page'):''}}">
                        <span class="pull-right">
                            <ul class="pagination pull-right">
                                <?php echo $result_temp->appends(Input::except('page'))->render(); ?>
                            </ul>
                        </span>
                        <div class="clearfix"></div>  
                    </div>
                    <div class="pull-right targetdate">
                        <label for="date"></label>
                        <!--                        <div class="col-md-12">
                                                    Password <input type="password" class="form-control" placeholder="Enter your password here" name="password_delete_completetd" id="password_delete_completetd">
                                                </div>-->
                        <div class="col-md-12">
                            <input type="submit" class="btn btn-primary submit_delete_all" value="Delete Records">
                        </div>
                        <input type="hidden" name="password_delete" id="password_delete">
                    </div>
                    @else
                    <div class="alert alert-info no_data_msg_container">
                        Currently no records available.
                    </div>
                    @endif
                </form>


                <div class="modal fade" id="delete_records_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="myModalLabel">Bulk Delete</h4>
                            </div>                            
                            <div class="modal-body">
                                <div class="alert alert-danger alert-dismissable delete_records_empty">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    Please Enter Password Here
                                </div>
                                <div class="delete">
                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                    <div class="pwd">
                                        <div class="pwdl"><b>Password:</b></div>
                                        <div class="pwdr" id="pwdr">
                                            <input class="form-control" id="password_delete_completetd" placeholder="" name="password_delete_completetd" type="password">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="delp">Are you sure you want to <b>delete this </b> records?</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-default delete_records_modal">Yes</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif
    @stop