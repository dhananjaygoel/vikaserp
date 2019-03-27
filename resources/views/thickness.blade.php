@extends('layouts.master')
@section('title','Thickness')
@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('dashboard')}}">Home</a></li>
                        <li class="active"><span>Thickness</span></li>
                    </ol>

                    <div class="clearfix">
                        <h1 class="pull-left">Thickness</h1>
                        @if( Auth::user()->role_id == 0  )
                            <div class="pull-right top-page-ui">
                                <a href="{{URL::action('ThicknessController@create')}}" class="btn btn-primary pull-right">
                                    <i class="fa fa-plus-circle fa-lg"></i> Add More Thickness
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
                            @if(sizeof($thickness) ==0)
                                <div class="alert alert-info no_data_msg_container">
                                    Currently no states available.
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
                                            <th>Thickness</th>
                                            <th>Difference</th>
                                            @if( Auth::user()->role_id == 0 )
                                                <th class="text-center">Actions</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = ($thickness->currentPage() - 1) * $thickness->perPage() + 1; ?>
                                        @foreach($thickness as $thicknesses)
                                            <tr>
                                                <td class="col-md-1">{{$i++}}</td>
                                                <td>{{$thicknesses->thickness}}</td>
                                                <td>{{$thicknesses->diffrence}}</td>
                                                @if( Auth::user()->role_id == 0 )
                                                    <td class="text-center">
                                                        <a href="{{ Url::action('ThicknessController@edit', ['id' => $thicknesses->id]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                                        </a>
                                                        <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_states_modal_{{$thicknesses->id}}">
                                                        <span class="fa-stack">
                                                            <i class="fa fa-square fa-stack-2x"></i>
                                                            <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                        </span>
                                                        </a>
                                                    </td>
                                                @endif
                                            </tr>

                                            <div class="modal fade" id="delete_states_modal_{{$thicknesses->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            {!! Form::open(array('method'=>'DELETE','url'=>url('thickness',$thicknesses->id), 'id'=>'delete_states_form'))!!}
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
                                <?php echo $thickness->render(); ?>
                            </span>
                                    <div class="clearfix"></div>
                                    @if($thickness->lastPage() > 1)
                                        <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('states')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $thickness->lastPage()}} </b></label>
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