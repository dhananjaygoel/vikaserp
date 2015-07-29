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
                                        <th>Party Name</th>
                                        <th>Vechile Number</th>
                                        <th>Quantity</th>
                                        <th>Serial Number</th>
                                        @if(Input::get('purchaseaAdviseFilter') == 'in_process' || Input::get('purchaseaAdviseFilter') == '')
                                        <th class="text-center">Create Purchase Challan</th>
                                        @endif
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = ($purchase_advise->currentPage() - 1) * $purchase_advise->perPage() + 1;
                                    ?>
                                    @foreach($purchase_advise as $key=>$pa)

                                    <?php $qty_sum = 0; ?>
                                    @foreach($pa['purchase_products'] as $prod)
                                    <?php $qty_sum += $prod->quantity; ?>
                                    @endforeach
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ date("jS F, Y", strtotime($pa->purchase_advice_date)) }}</td>
                                        <td>{{ $pa['supplier']->owner_name }}</td>
                                        <td>{{ $pa->vehicle_number}}</td>
                                        <td>{{ round($pa->total_quantity, 2) }}</td>
                                        <td>{{ $pa->serial_number }}</td>
                                        @if(Input::get('purchaseaAdviseFilter') == 'in_process' || Input::get('purchaseaAdviseFilter') == '')

                                        <td class="text-center">
                                            @if($pa->serial_number != "")
                                            <a href="{{ url('purchaseorder_advise_challan/'.$pa->id)}}" class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($pa->serial_number == "")
                                            <span class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                        </td>

                                        @endif    
                                        <td class="text-center">
                                            <a href="{{url('purchaseorder_advise/'.$pa->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                            @if($pa->serial_number == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            @if($pa->advice_status == 'in_process')
                                            <a href="{{url('purchaseorder_advise/'.$pa->id.'/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @elseif($pa->serial_number != "" && Auth::user()->role_id == 0  || Auth::user()->role_id == 1 )
                                            <span class="table-link normal_cursor" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                            
                                            

                                            @if($pa->serial_number == ""  || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link" title="print" data-toggle="modal" data-target="#printModal{{$pa->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @elseif($pa->serial_number != "" && Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <span class="table-link normal_cursor" title="print">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif                                           

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
                                <div class="modal fade" id="printModal{{$pa->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row print_time "> 
                                                    <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" value="" id="checksms"><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="pa_id" value="{{$pa->id}}"/>
                                                <div >
                                                    <button type="button" class="btn btn-primary form_button_footer print_purchase_advise" id="{{$pa->id}}" >Print</button>
                                                    <button class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                                                </div>
                                                <div class="clearfix"></div>
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
                            @if($purchase_advise->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('purchaseorder_advise')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $purchase_advise->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span> 
                            @endif 
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