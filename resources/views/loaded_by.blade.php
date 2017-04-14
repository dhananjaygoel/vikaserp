@extends('layouts.master')
@section('title','Loaded By')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Loaded By</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Loaded By</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('LoadByController@create')}}"  title="Add Loaded By" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add Loaded By
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="main-box clearfix">
                <div class="main-box-body main_contents clearfix">
                    @if(Session::has('success'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">&times;</span></button>
                        {{Session::get('success')}}
                    </div>
                    @endif
                    @if(Session::has('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">&times;</span></button>
                        {{Session::get('error')}}
                    </div>
                    @endif
                    @if(count($loaders)>0)
                    <div class="table-responsive">
                        <table id="table-example" class="table customerview_table">
                            <thead>
                                <tr>
                                    <th class="col-md-1">#</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Mobile Number</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                ?>
                                @foreach ($loaders as $loader)
                                <tr>
                                    <td class="col-md-1">{{ $i }}</td>
                                    <td>{{$loader->first_name}}</td>
                                    <td>{{$loader->last_name}}</td>
                                    <td>{{$loader->phone_number}} </td>
                                    <td class="text-center">
                                        <a href="{{URL::action('LoadByController@show',['id'=> $loader->id])}}" class="table-link" title="View">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        <a href="{{URL::action('LoadByController@edit',['id'=> $loader->id])}}" class="table-link" title="Edit">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>                                        
                                        <a href="#" class="table-link danger delete-loader" data-id="{{$loader->id}}">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no user available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_loaded_by_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        {!! Form::open(array('method' => 'delete', 'id'=>'delete_loaded_by_form'))!!}
        <input type="hidden" name="loader_hidden" id="loader_hidden" value="" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div class="delete">
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
@stop