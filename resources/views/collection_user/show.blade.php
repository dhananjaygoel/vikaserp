@extends('layouts.master')
@section('title','Collection User Details')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('collectionusers')}}">Collection Users</a></li>
                    <li class="active"><span>View Collection User</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Collection User</h1>                                 
                    <div class="pull-right top-page-ui">
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                        <a href="{{ URL::route('collectionusers.edit',$user->id) }}" class="btn btn-primary pull-right">
                            Edit Collection User
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
                                        <td><span>First Name:</span> {{($user->first_name)?$user->first_name:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Last Name:</span> {{($user->last_name)?$user->last_name:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Mobile Number:</span> {{($user->mobile_number)?$user->mobile_number:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Email: </span> {{($user->email)?$user->email:''}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>Location :</span>
                                            <ul>
                                            @foreach($user->locations as $loc)
                                                <li>{{ $loc->location_data->area_name }}</li>
                                            @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <a href="{{url('collectionusers')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop