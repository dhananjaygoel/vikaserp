@extends('layouts.master')
@section('title','Users')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Users</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Users</h1>

                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('UsersController@create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add user
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">

                    @if (Session::has('flash_message'))
                    <div class="alert alert-success alert-success1">
                        <i class="fa fa-check-circle fa-fw fa-lg"></i>
                        <strong>Well done!</strong> User details successfully added.
                    </div> <br/>
                    @endif
                    <div class="main-box-body main_contents clearfix">

                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Mobile</th>                                                            
                                        <th>Type Of User</th>                                                            
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                 
                                    <?php
                                    $i = ($users_data->currentPage() - 1 ) * $users_data->perPage() + 1;
                                    ?>
                                    @foreach($users_data as $user)                                    
                                    <tr>
                                        <td class="col-md-1">{{ $i }}</td>
                                        <td>{{$user->first_name}}</td>
                                        <td>{{$user->last_name}}</td>
                                        <td><a href="mailto:">{{$user->email}}</a></td>                                        
                                        <td>{{$user->phone_number}}</td>
                                        <td>{{$user->mobile_number}} </td>
                                        <td>
                                            @if($user->role_id == 1)
                                            {{'Admin'}}
                                            @elseif($user->role_id == 2)
                                            {{'Sales Staff'}}
                                            @elseif($user->role_id == 3)
                                            {{'Delivery Staff'}}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="edit_user.php" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal<?php echo $user->id; ?>">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>                                    
                                <div class="modal fade" id="myModal<?php echo $user->id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('route' => array('users.destroy', $user->id), 'method' => 'delete')) !!}
                                          <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <?php
//                                                    $us = Auth::user();
//                                                   $us['mobile_number']
                                                    ?>
                                                    <div><b>Mobile:</b>
                                                        9898989890
                                                        <input type="hidden" name="mobile" value="9898989890"/>
                                                        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>"/>
                                                    </div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" name="model_pass" placeholder="" type="text"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                </div>
                                            </div>           
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default">Yes</button>
                                            </div>
                                             {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>

                                <?php $i++; ?>
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php echo $users_data->render(); ?>
                                </ul>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>       
@endsection