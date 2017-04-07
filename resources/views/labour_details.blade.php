@extends('layouts.master')
@section('title','Labour Detail')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('labours')}}">Labours</a></li>
                    <li class="active"><span>View Labour</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Labour</h1>                                 
                    <div class="pull-right top-page-ui">
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                        <a href="{{url('labours/'.$labour->id.'/edit')}}" class="btn btn-primary pull-right">
                            Edit Labour
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">
                                <tbody>
                                    <tr>
                                        <td><span>Owner Name:</span> {{($labour->labour_name)?$labour->labour_name:''}}</td>
                                    </tr>
                                    
                                   
<!--                                    <tr>
                                        <td><span>Location: </span> {{($labour->location)?$labour->location:''}}</td>
                                    </tr>-->
                                    
                                    
                                    
                                    
                                   

                                    <tr>
                                        <td><span>Phone Number:</span> {{($labour->phone_number)?$labour->phone_number:''}}</td>
                                    </tr>
                                    
                                  
                                </tbody>
                            </table>
                            <a href="{{url('labours')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop