@extends('layouts.master')
@section('title','Purchase Advise')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Purchase Advice</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Purchase Advice</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{url('purchaseorder_advise/create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Create Purchase Advice Independently
                        </a>
                        <div class="form-group pull-right">
                            <form method="GET" id="purchaseaAdviseFilterForm">
                                <div class="col-md-12">
                                    <select class="form-control" id="purchaseaAdviseFilter" name="purchaseaAdviseFilter">
                                        <option value="" selected="">Status</option>
                                        <option value="delivered" <?php
                                        if (Request::get('purchaseaAdviseFilter') == "delivered") {
                                            echo "selected=selected";
                                        }
                                        ?>>Delivered</option>
                                        <option value="in_process" <?php
                                        if (Request::get('purchaseaAdviseFilter') == "in_process") {
                                            echo "selected=selected";
                                        }
                                        ?>>Inprocess</option>
                                    </select>
                                </div>
                            </form>
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
                        @if(count($purchase_advise) > 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Serial Number</th>
                                        <th class="text-center">Create Purchase Challan</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = ($purchase_advise->currentPage() - 1) * $purchase_advise->perPage() + 1;
                                    ?>
                                    @foreach($purchase_advise as $key=>$pa)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$pa->purchase_advice_date}}</td>
                                        <td>{{$pa->serial_number}}</td>
                                        <td class="text-center"><a href="{{ url('purchaseorder_advise_challan/'.$pa->id)}}" class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a></td>
                                        <td class="text-center">
                                            <a href="{{url('purchaseorder_advise/'.$pa->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="{{url('purchaseorder_advise/'.$pa->id.'/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#printModal">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal{{$pa->id}}" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                <div class="modal fade" id="myModal{{$pa->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{url('purchaseorder_advise/'.$pa->id)}}" id="deleteCustomerForm{{$pa->id}}">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input name="_method" type="hidden" value="DELETE">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="delete">
                                                        <div><b>UserID:</b> {{Auth::user()->phone_number}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password" type="text"></div>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>cancel</b> this advise</div>
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
                                <div class="modal fade" id="printModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="" accept-charset="UTF-8" >
                                                    <div class="row print_time "> 
                                                        <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    <div >
                                                        <a href="purchaseorder_advise.php" type="button" class="btn btn-primary form_button_footer" >Print</a>
                                                        <button class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $purchase_advise->render() ?>
                            </span>
                        </div>
                        @else
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <strong> No purchase advise found</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop