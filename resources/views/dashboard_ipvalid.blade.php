@extends('layouts.master')
@section('title','Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
            
                <div class="clearfix">
                    <h1 class="pull-left">Dashboard</h1>
                    </div>
                    @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-danger no_data_msg_container">{{ Session::get('flash_message') }}</div>
                    @endif
                    <div class="pull-right top-page-ui">   
                    
                                     
                        @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 2)
                        <div class="row text-center ">
                            <div class="col-md-12">
                                <a href="{{url('orders/create')}}" class="btn btn-primary btn-lg text-center button_indexright">
                                    <i class="fa fa-plus-circle fa-lg"></i> Place Order
                                </a>
                                <a href="{{url('inquiry/create')}}" class="btn btn-primary btn-lg text-center ">
                                    <i class="fa fa-plus-circle fa-lg"></i> Add Inquiry
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
               
            </div>
        </div>
    </div>
</div>

@endsection
