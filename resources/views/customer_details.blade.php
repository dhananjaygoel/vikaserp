@extends('layouts.master')
@section('title','Customer Detail')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('customers')}}">Customers</a></li>
                    <li class="active"><span>View Customer</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Customer</h1>                                 
                    <div class="pull-right top-page-ui">
                        <a href="{{url('customers/'.$customer->id.'/edit')}}" class="btn btn-primary pull-right">
                            Edit Customer
                        </a>
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
                                        <td><span>Owner Name:</span> {{$customer->owner_name}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Company Name:</span> {{$customer->company_name}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Contact Person:</span> {{$customer->contact_person}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Address1: </span> {{$customer->address1}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Address2: </span> {{$customer->address2}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>State:</span>
                                            @foreach($states as $state)
                                            @if($state->id == $customer->state)
                                            {{$state->state_name}}
                                            @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="col-md-4"><span>City:</span> 
                                            @foreach($cities as $city)
                                            @if($customer->city == $city->id)
                                            {{$city->city_name}}
                                            @endif
                                            @endforeach
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><span>Zip:</span> {{$customer->zip}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Email:</span> <a href="mailto:"/> {{$customer->email}}</a></td>
                                    </tr>
                                    <tr>
                                        <td><span>Tally Name:</span> {{$customer->tally_name}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><span>Phone Number1:</span> {{$customer->phone_number1}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Phone Number2:</span> {{$customer->phone_number2}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><span>Delivery Location:</span> {{$customer['deliverylocation']->area_name}} <em>({{$customer['deliverylocation']['city']->city_name}}, {{$customer['deliverylocation']['state']->state_name}})</em></td>
                                    </tr>
                                    <tr>
                                        <td><span>Username:</span> {{$customer->username}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Password:</span> </td>
                                    </tr>
                                    <tr>
                                        <td><span>Credit Period(Days):</span> {{$customer->credit_period}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Relationship Manager:</span> {{$customer['manager']->first_name}}&nbsp;{{$customer['manager']->last_name}}</td>
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