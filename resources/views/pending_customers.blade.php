@extends('layouts.master')
@section('title','Pending Customers')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Pending Customers</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Pending Customers</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(Session::has('success'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('success') }} </strong>
                        </div>
                        @endif
                        @if(Session::has('error'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('error') }} </strong>
                        </div>
                        @endif
                        @if(count($customers) > 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>Customer Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Delivery Location </th>                                                         
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = ($customers->currentPage() - 1) * $customers->perPage() + 1;
                                    ?>
                                    @foreach($customers as $key=>$c)
                                    <tr>
                                        <td class="col-md-1">{{$i}}</td>
                                        <td>{{$c->owner_name}}</td>
                                        <td>{{$c->contact_person}}</td>
                                        <td>{{$c->phone_number1}} </td>
                                        <?php $temp = 'N/A' ?>
                                        @foreach($locations as $l)
                                        @if($l->id == $c->delivery_location_id)
                                        <?php $temp = $l->area_name ?>
                                        @endif
                                        @endforeach
                                        <td>{{$temp}}</td>
                                        <td class="text-center">
                                            <a href="{{url('pending_customers/'.$c->id)}}" class="table-link" title="Add to customer">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="{{url('pending_customers/'.$c->id.'/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal{{$c->id}}" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                <div class="modal fade" id="myModal{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{url('pending_customers/'.$c->id)}}" id="deleteCustomerForm{{$c->id}}">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input name="_method" type="hidden" value="DELETE">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="delete"> 
                                                        <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password" type="text"></div>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>delete</b> this customer?</div>
                                                    </div>
                                                </div>            
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                    <button type="submit" class="btn btn-default deleteCustomer" data-dismiss="modal">Yes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $customers->render(); ?>
                            </span>
                            <div class="clearfix"></div>
                            @if($customers->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('pending_customers')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $customers->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span> 
                            @endif 
                        </div>
                        @else
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <strong> No pending customers found </strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop