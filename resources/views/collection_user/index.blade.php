@extends('layouts.master')
@section('title','Collection Users')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/dashboard">Home</a></li>
                    <li class="active"><span>Collection Users</span></li>
                </ol>
            </div>
        </div>
        <div class="row" style="margin-bottom:10px">
            <div class="col-md-12">
                <div class="col-lg-3">
                    <h1 class="pull-left">Collection Users</h1>
                </div>
                <div class="col-lg-9">
                        <form method="GET" action=" {{url()}}/account" id="st_collection_user_form">
                            <div class="col-lg-3">
                                <div class="input-group pull-right">
                                    <input type="text" class="form-control" name="search" id="search" placeholder="Search Collection User" value="{{ Request::get('search') }}">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-2">                                                          
                                <select class="form-control" id="user_filter3" name="territory_filter" onchange="this.form.submit();">
                                    <option value="" selected="">Select Territory</option>
                                    @foreach($territories as $territory)
                                    @if($territory->id!=0)
                                    <option <?php if (Input::get('territory_filter') == $territory->id) echo 'selected=""'; ?> value="{{$territory->id}}">{{$territory->teritory_name}}</option>
                                    @endif
                                    @endforeach
                                </select>                                
                            </div> 
                            <div class="col-lg-2">
                            <div class="form-group">
                                <select class="form-control" name="location" id="cuser_location">
                                    <option value="">Select Location</option>
                                    @if(isset($locations))
                                    @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" <?php echo(Request::get('location')? Request::get('location')==$loc->id ? 'selected' : '': ''); ?> >{{ $loc->area_name }}</option>
                                    @endforeach
                                    @endif                                
                                </select>
                            </div>               
                            </div> 
                        </form>  
                        <div class="col-lg-5 pull-right"> 
                        <a class="btn btn-primary" href="{{url()}}/account/create"><i class="fa fa-plus"></i> Add Collection User</a>
                        <button class="btn btn-primary st_download_collection_u_list" data-token="{{csrf_token()}}"><i class="fa fa-plus"></i> Download List</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">            
                    <div class="main-box-body main_contents clearfix">
                        @if (Session::has('flash_message'))
                        <div class="alert alert-success alert-success1">
                            <button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">
                                  &times;
                                </button>
                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                            <strong>Well done!</strong> {{Session::get('flash_message')}}
                        </div> <br/>
                        @endif
                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">
                            <button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">
                                  &times;
                                </button>
                            {{Session::get('success')}}
                        </div>
                        @endif

                        @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">
                            <button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">
                                  &times;
                                </button>
                            {{Session::get('wrong')}}
                        </div>
                        @endif

                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Territory</th>
                                        <th>Location</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>    
                                    @if(isset($users) && !empty($users) && count($users)>0) 
                                    @foreach($users as $key => $user)
                                    <tr>
                                        <td class="col-md-1">{{ $key+1 }}</td>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>                
                                        <td>{{ $user->mobile_number }}</td>
                                        <td>
                                            <?php
                                            if(isset($user->locations[0])){
                                                $territory_id=$user->locations[0]->teritory_id;
                                            }                                            
                                            ?>
                                            @foreach($territories as $territory)
                                                @if($territory_id == $territory->id)
                                                    {{ $territory->teritory_name}}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            {{count($user->locations)}}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{URL::route('account.show',$user->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="{{ URL::route('account.edit',$user->id) }}" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <!-- delete script is in custom_js/laravel.js -->
                                            <a class="table-link danger" href="account/{{ $user->id }}" data-method="delete" 
  data-token="{{csrf_token()}}" data-confirm="Are you sure?" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr> 
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center"><h4>No Records Found</h4></td>
                                    </tr>
                                    @endif
                                    
                                </tbody>
                            </table>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<div class="modal" id="confirm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <div class="delete">
                    <?php
                    $us = Auth::user();
                    $us['mobile_number']
                    ?>
                    <div><b>Mobile:</b>
                        {{$us['mobile_number']}}
                    </div>
                    <div class="pwd">
                        <div class="pwdl"><b>Password:</b></div>
                        <div class="pwdr"><input class="form-control" id="cpassword" name="password" required="required" type="password"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary" id="delete-btn">Delete</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
