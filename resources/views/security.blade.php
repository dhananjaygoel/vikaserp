@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                    <div class="col-lg-12">

                        <div class="row">
                            <div class="col-lg-12">
                                <ol class="breadcrumb">
                                    <li><a href="#">Home</a></li>
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
                        
                        <?php $user = Auth::user();
//                                                                    echo '<pre>';
//                                                                    print_r($user);
//                                                                    echo '</pre>';
//                                                                    exit
//                        echo $user['mobile_number'];
//                        exit;
                                                                    ?>
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="main-box clearfix">
                                    <div class="main-box-body main_contents clearfix">

                                        <div class="table-responsive">
                                            <table id="table-example" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="col-md-1">#</th>

                                                        <th>IP Address</th>

                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>                    
                                                   
                                                    
                                                    @if(count($sec)>0)
                                                    <?php $k=1;?>
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
                                                                        <div><b>UserID:</b> <input type='text' name="mobile_number" value="{{$user['mobile_number']}}"></div>
                                                                    <div class="pwd">
                                                                        <div class="pwdl"><b>Password:</b></div>
                                                                        <div class="pwdr"><input class="form-control" placeholder="" name='password' type="password"></div>
                                                                        

                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <div class="delp">Are you sure you want to <b>delete </b> ?</div>


                                                                </div>

                                                            </div>        
                                                            <div class="modal-footer">
                                                                
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                                    <button type="submit" class="btn btn-default" >Yes</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  
                                                    @endforeach
                                                    @endif

                                                




                                                </tbody>
                                            </table>

<!--                                            <span class="pull-right">
                                                <ul class="pagination pull-right">
                                                    <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#">5</a></li>
                                                    <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                                                </ul>

                                            </span>-->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
