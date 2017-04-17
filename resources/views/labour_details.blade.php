@extends('layouts.master')
@section('title','Labour Detail')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('performance/labours')}}">Labours</a></li>
                    <li class="active"><span>View Labour</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Labour</h1>                                 
                    <div class="pull-right top-page-ui">
                        @if(isset($labour->id))
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                        <a href="{{url('performance/labours/'.$labour->id.'/edit')}}" class="btn btn-primary pull-right">
                            Edit Labour
                        </a>
                        @endif
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
                                        <td><span>Labour Name:</span> {{(isset($labour->labour_name))?$labour->labour_name:''}}</td>
                                    </tr>
                                    
                                   

                                    <tr>
                                        <td><span>Phone Number:</span> {{(isset($labour->phone_number))?$labour->phone_number:''}}</td>
                                    </tr>
                                    
                                  
                                </tbody>
                            </table>
                            <a href="{{url('performance/labours')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop