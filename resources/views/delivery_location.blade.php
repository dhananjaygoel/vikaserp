@extends('layouts.master')
@section('title','Location')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Location</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Location</h1>
                    @if( Auth::user()->role_id == 0  )
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('DeliveryLocationController@create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New Location
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($delivery_location) ==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no locations have been added.
                        </div>
                        @else
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if (Session::has('flash_success_message'))
                        <div id="flash_error" class="alert alert-success no_data_msg_container">{{ Session::get('flash_success_message') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>State Name</th>
                                        <th>City Name</th>
                                        <th>Delivery Location</th>
                                        <th>Difference</th>
                                        @if( Auth::user()->role_id == 0 )
                                        <th class="text-center">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($delivery_location->currentPage() - 1) * $delivery_location->perPage() + 1; ?>
                                    @foreach($delivery_location as $location_data)
                                    <tr>
                                        <td class="col-md-1">{{$i++}}</td>
                                        <td>{{$location_data['city']['states']->state_name}}</td>
                                        <td>{{$location_data['city']->city_name}}</td>
                                        <td>{{$location_data->area_name}}</td>
                                        <td>
                                            @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)

                                            <form method="post" action="{{URL::action('DeliveryLocationController@delivery_difference')}}">
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="tel" disabled="" class="form-control" required="" name="difference" value="{{ $location_data->difference }}">
                                                        <input type="hidden" class="form-control" name="id" value="{{ $location_data->id}}">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">
                                                        <input class="btn btn-primary" disabled="" type="submit" class="form-control" value="save" >     
                                                    </div>
                                                </div>
                                            </form>

                                            @else

                                            <form method="post" action="{{URL::action('DeliveryLocationController@delivery_difference')}}">
                                                <div class="row product-price">
                                                    <div class="form-group col-md-6">
                                                        <input type="tel" class="form-control" required="" name="difference" value="{{ $location_data->difference }}">
                                                        <input type="hidden" class="form-control" name="id" value="{{ $location_data->id}}">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    </div>
                                                    <div class="form-group col-md-2 difference_form">
                                                        <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                                    </div>
                                                </div>
                                            </form>

                                            @endif

                                        </td>
                                        @if( Auth::user()->role_id == 0 )
                                        <td class="text-center">
                                            <a href="{{ Url::action('DeliveryLocationController@edit', ['id' => $location_data->id]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_location_modal_{{$location_data->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                        </td>
                                        @endif
                                    </tr>

                                <div class="modal fade" id="delete_location_modal_{{$location_data->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(array('method'=>'DELETE','url'=>url('location',$location_data->id), 'id'=>'delete_location_form'))!!}
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="password" name="password" required=""></div>

                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b> ?</div>


                                                </div>

                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach


                                </tbody>
                            </table>

                            <span class="pull-right">
                                <?php echo $delivery_location->render(); ?>
                            </span>
                            <div class="clearfix"></div>
                            @if($delivery_location->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('location')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $delivery_location->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span> 
                            @endif 

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop