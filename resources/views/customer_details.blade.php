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
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                        <a href="{{url('customers/'.$customer->id.'/edit')}}" class="btn btn-primary pull-right">
                            Edit Customer
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
                                        <td><span>Customer Type:</span> @if($customer->is_supplier =='yes')Supplier @else Customer @endif</td>
                                    </tr>
                                    <tr>
                                        <td><span>Owner Name:</span> {{($customer->owner_name)?$customer->owner_name:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Company Name:</span> {{($customer->company_name)?$customer->company_name:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>GST Number:</span> {{($customer->gstin_number)?$customer->gstin_number:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Contact Person:</span> {{($customer->contact_person)?$customer->contact_person:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Address1: </span> {{($customer->address1)?$customer->address1:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Address2: </span> {{($customer->address2)?$customer->address2:''}}</td>
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
                                        <td><span>Phone Number1:</span> {{($customer->phone_number1)?$customer->phone_number1:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Phone Number2:</span> {{($customer->phone_number2)?$customer->phone_number2:''}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Delivery Location:</span> {{$customer->deliverylocation->area_name}} <em>({{$customer->deliverylocation->city->city_name}}, {{$customer->deliverylocation->states->state_name}})</em></td>
                                    </tr>
                                    <tr>
                                        <td><span>Username:</span> {{($customer->username != '' )?$customer->username: 'N/A'}}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Password:</span> ********** </td>
                                    </tr>
                                    <tr>
                                        <td><span>Credit Period(Days):</span> {{($customer->credit_period != '')?$customer->credit_period:'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><span>Relationship Manager:</span> {{isset($customer['manager']->first_name)?$customer['manager']->first_name: 'N'}}{{isset($customer['manager']->last_name)?' '.$customer['manager']->last_name: '/A'}}</td>
                                    </tr>

                                    @if(sizeof($customer['customerproduct']) > 0)
                                    <tr>
                                        <td><span>Set price(Category & difference)</span></td>
                                    </tr>                                                                        @foreach($product_category as $pc)
                                    <?php
                                    $price = '';
                                    foreach ($customer['customerproduct'] as $key => $value) {
                                        if ($pc->id == $value->product_category_id) {
                                            ?>
                                            <tr>
                                                <td><span>{{$pc->product_category_name}}:</span> {{$price = $value->difference_amount}}</td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <a href="{{url('customers')}}" class="btn btn-default form_button_footer">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
@stop