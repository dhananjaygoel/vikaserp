@extends('layouts.master')
@section('title','Customers')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Customers</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Customers</h1>
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <a href="{{url('excel_export_customer')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> Download List
                    </a>
                    <a href="{{url('excel_import_customer')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> Import Customer
                    </a>

                    <a href="{{url('customers/create')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> Add Customer
                    </a>
                    <a href="{{url('bulk_set_price')}}" class="btn btn-primary pull-right">
                        <i class="fa fa-plus-circle fa-lg"></i> Bulk Set Price
                    </a>
                    @endif
                    <form method="GET" id="searchCustomerForm">



                        <div class="input-group col-md-3 pull-right">
                            <input type="text" class="form-control" name="search" id="search" placeholder="Tally Name, City, Delivery Location" value="{{Request::get('search')}}">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                        <!--                        <div class="form-group pull-right col-md-3">
                                                    <input class="form-control" name="search" id="search" placeholder="Tally Name, City, Delivery Location" value="{{Request::get('search')}}" type="text">
                                                    <i onclick="submit_filter_form();" class="fa fa-search search-icon"></i>
                                                </div>-->
                    </form>
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
                                        <th>Tally Name</th>
                                        <th>Email</th>
                                        <th>Mobile </th>
                                        <th>City</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = ($customers->currentPage() - 1) * $customers->perPage() + 1;
                                    ?>
                                    @foreach($customers as $c)
                                    <tr>
                                        <td class="col-md-1">{{$i++}}</td>
                                        <td>{{$c->tally_name}}</td>
                                        <td>{{$c->email}}</td>
                                        <td>{{$c->phone_number1}}</td>
                                        <td>
                                            @foreach($city as $town)
                                            @if($town->id == $c->city)
                                            {{ $town->city_name }}
                                            @endif
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            <a href="{{url('customers/'.$c->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                                            <a href="{{url('customers/'.$c->id.'/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if(Auth::user()->role_id == 0)
                                            <a href="{{URL::to('set_price/'.$c->id)}}" class="table-link" title="Set Price">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-money fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if(Auth::user()->role_id == 0)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal{{$c->id}}" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                <div class="modal fade" id="myModal{{$c->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{url('customers/'.$c->id)}}" id="deleteCustomerForm{{$c->id}}">
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
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php
                                if (isset($_GET['search']) && Request::get('search') != '') {
                                    echo $customers->appends(array('search' => Request::get('search')))->render();
                                } else {
                                    echo $customers->render();
                                }
                                ?>
                            </span>
                            <span class="clearfix"></span>

                            @if($customers->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('customers')}}" id="filter_search">
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
                            <strong> Currently No customers found </strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop