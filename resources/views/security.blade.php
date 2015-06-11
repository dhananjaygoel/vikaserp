@extends('layouts.master')
@section('title','Security')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('security')}}">Home</a></li>
                    <li class="active"><span>Security</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Security</h1>

                    <div class="pull-right top-page-ui">
                        <a href="{{url('security/create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add IP Address
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $user = Auth::user();
        ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">

                        <div class="table-responsive">
                            @if(Session::has('error'))
                            <div class="alert alert-danger">                                        
                                                      
                                    {{Session::get('error')}}
                            </div>
                            @endif
                            
                            @if(Session::has('message'))
                            <div class="alert alert-success">                                        
                                {{Session::get('message')}}
                            </div>
                            @endif
                            @if(count($sec)>0)
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>

                                        <th>IP Address</th>

                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                    
                                    <?php $k = 1; ?>
                                    @foreach($sec as $security)
                                    <tr>

                                        <td class="col-md-1">{{$k++}}</td>

                                        <td>{{$security->ip_address}}</td>


                                        <td class="text-center">

                                            <a href="{{url('security/'.$security->id.'/edit')}}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal_{{$security->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                        </td>
                                    </tr>
                                <div class="modal fade" id="myModal_{{$security->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">security
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>

                                            <div class="modal-body">
                                                <div class="delete">
                                                    <form action="{{url('security', $security->id)}}"method='POST'>
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                        <input type="hidden" name="id" value="{{$security->id}}">
                                                        <div><b>UserID:</b> {{$user['mobile_number']}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" placeholder="" id='password_{{$security->id}}' name='password' type="password" required="required"></div>


                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>delete </b> ?</div>


                                                </div>

                                            </div>        
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit"  class="btn btn-default" >Yes</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                @endforeach
                                
                                </tbody>
                            </table>
                            @else
                                <div class="alert alert-info no_data_msg_container">
                                    Currently no security IP Address available.
                                </div>
                            @endif
                            <span class="pull-right">
                                <?php echo $sec->render(); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
