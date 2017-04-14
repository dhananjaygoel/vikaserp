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
                    <div class="search_form_wrapper receipt_master_search_wrapper pull-right">
                        <form class="search_form" method="GET" action="javascript:void(0);">
                            <input type="text" placeholder="From" name="export_from_date" class="form-control export_from_date" id="export_from_date">
                            <input type="text" placeholder="To" name="export_to_date" class="form-control export_to_date" id="export_to_date">
                            <input type="submit" name="search_data" value="Search" class="search_button btn btn-primary">
                        </form>                    
                        <button class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
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
                        <div class="alert alert-success alert-success1">{{Session::get('success')}}</div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
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
                                    <tr>
                                        <td>1</td>
                                        <td>2017-04-14</td>
                                        <td>01:01:01</td>
                                        <td><a href="#"><i class="fa fa-pencil"></i></a></td>
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