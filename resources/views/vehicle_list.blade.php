@extends('layouts.master')
@section('title','Vehicle List')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/dashboard">Home</a></li>
                    <li class="active"><span>Vehicle List</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Vehicle List</h1>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <h4 class="pull-left">Delivery Order Vehicle List</h4>                    
                </div>
                <div class="main-box clearfix">            
                    <div class="main-box-body main_contents clearfix">                        

                        @if(sizeof($do_vehicle_list) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>Vehicle Number</th>                                        
                                    </tr>
                                </thead>
                                <tbody>                 
                                    <?php
                                    $i = ($do_vehicle_list->currentPage() - 1 ) * $do_vehicle_list->perPage() + 1;
                                    ?>
                                    @foreach($do_vehicle_list as $vehicle)                                    
                                    <tr>
                                        <td class="col-md-1">{{ $i }}</td>
                                        <td>{{$vehicle->vehicle_number}}</td>                                        
                                    </tr>
                                <?php $i++; ?>
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php echo $do_vehicle_list->render(); ?>
                                </ul>
                            </span>
                            <div class="clearfix"></div>  
                            @if($do_vehicle_list->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('vehicle-list')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $do_vehicle_list->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span> 
                            @endif
                        </div>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no Vehicle no available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <h4 class="pull-left">Purchase Advise Vehicle List</h4>                    
                </div>
                <div class="main-box clearfix">            
                    <div class="main-box-body main_contents clearfix">                        

                        @if(sizeof($pa_vehicle_list) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>Vehicle Number</th>                                        
                                    </tr>
                                </thead>
                                <tbody>                 
                                    <?php
                                    $i = ($pa_vehicle_list->currentPage() - 1 ) * $pa_vehicle_list->perPage() + 1;
                                    ?>
                                    @foreach($pa_vehicle_list as $vehicle)                                    
                                    <tr>
                                        <td class="col-md-1">{{ $i }}</td>
                                        <td>{{$vehicle->vehicle_number}}</td>                                        
                                    </tr>
                                <?php $i++; ?>
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php echo $pa_vehicle_list->render(); ?>
                                </ul>
                            </span>
                            <div class="clearfix"></div>  
                            @if($pa_vehicle_list->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('vehicle-list')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $pa_vehicle_list->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span> 
                            @endif
                        </div>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no Vehicle no available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>     
@endsection
