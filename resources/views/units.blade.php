@extends('layouts.master')
@section('title','Units')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Unit</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Unit</h1>

                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('UnitController@create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New Unit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($units) ==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no units available.
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

                                        <th>Unit</th>

                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($units->currentPage() - 1) * $units->perPage() + 1; ?>
                                    @foreach($units as $units_data)
                                    <tr>
                                        <td class="col-md-1">{{$i++}}</td>
                                        <td>{{$units_data->unit_name}}</td>
                                        <td class="text-center">
                                            <a href="{{ Url::action('UnitController@edit', ['id' => $units_data->id]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_unit_modal_{{$units_data->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                        </td>
                                    </tr>

                                <div class="modal fade" id="delete_unit_modal_{{$units_data->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(array('method'=>'DELETE','url'=>url('unit',$units_data->id), 'id'=>'delete_units_form'))!!}
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
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
                                <?php echo $units->render(); ?>
                            </span>

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop