@extends('layouts.master')
@section('title','Pending Delivery Order Report')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Pending Delivery Order Report</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Pending Delivery Order Report</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <?php $i = ($delivery_data->currentPage() - 1 ) * $delivery_data->perPage() + 1; ?>
                        @if(sizeof($delivery_data) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><a href="#" class="desc"><span>Date</span></a></th>
                                        <th><a href="#" class="asc"><span>Serial</span></a></th>
                                        <th><a href="#" class="desc"><span>Party</span></a></th>
                                        <th><a href="#" class="desc"><span>Truck Number</span></a></th>
                                        <th><a href="#" class="desc"><span>Order By</span></a> </th>                                       
                                        <th class="col-md-2">Remarks </th> 
                                    </tr>
                                </thead>
                                <tbody> 
                                    @foreach($delivery_data as $delivery)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ date("jS F, Y", strtotime($delivery->created_at)) }}</td>
                                        <td>
                                            @if($delivery->serial_no != "")
                                            {{ $delivery->serial_no }}
                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td>{{$delivery['customer']->owner_name}}</td>
                                        <td>{{ $delivery->vehicle_number }}</td>                                    
                                        <td>{{ $delivery['user']->first_name}} </td>
                                        <td>{{$delivery->remarks}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php echo $delivery_data->render(); ?>
                                </ul>
                            </span>
                            <div class="clearfix"></div>
                            @if($delivery_data->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('pending_delivery_order')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $delivery_data->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span> 
                            @endif 
                        </div>                       
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no pending delivery order data available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop