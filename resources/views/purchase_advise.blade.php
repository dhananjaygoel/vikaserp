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
<!--                        <a href="{{url('purchaseorder_advise/create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Create Purchase Advice Independently
                        </a>-->
                        <div class="form-group pull-right">
                            <form method="GET" id="purchaseaAdviseFilterForm">
                                <div class="col-md-12">
                                    <?php
                                    $session_sort_type_order = Session::get('order-sort-type');
                                    $qstring_sort_type_order = Input::get('purchaseaAdviseFilter');
                                    if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                                        $qstring_sort_type_order = $qstring_sort_type_order;
                                    } else {
                                        $qstring_sort_type_order = $session_sort_type_order;
                                    }
                                    ?>
                                    <select class="form-control" id="purchaseaAdviseFilter" name="purchaseaAdviseFilter">
                                        <!--<option value="" selected="">Status</option>-->
                                        <option value="in_process" <?php
                                        if ($qstring_sort_type_order == "in_process" | $qstring_sort_type_order == "Inprocess") {
                                            echo "selected=selected";
                                        }
                                        ?>>Inprocess</option>
                                        <option value="delivered" <?php
                                        if ($qstring_sort_type_order == "delivered" | $qstring_sort_type_order == "Delivered") {
                                            echo "selected=selected";
                                        }
                                        ?>>Delivered</option>

                                    </select>
                                    <?php
                                    if (isset($session_sort_type_order)) {
                                        Session::put('order-sort-type', "");
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                        <br>
                        <div class="search_form_wrapper pull-right" style="width:121% ">

                            <form class="search_form" method="GET" action="{{URL::action('PurchaseAdviseController@index')}}">
                                <input type="text" placeholder="From" name="export_from_date" class="form-control export_from_date" id="export_from_date" <?php
                                if (Input::get('export_from_date') != "") {
                                    echo "value='" . Input::get('export_from_date') . "'";
                                }
                                ?>>
                                <input type="text" placeholder="To" name="export_to_date" class="form-control export_to_date" id="export_to_date" <?php
                                if (Input::get('export_to_date') != "") {
                                    echo "value='" . Input::get('export_to_date') . "'";
                                }
                                ?>>
                                @if(isset($qstring_sort_type_order) && $qstring_sort_type_order =='delivered' )
                                <input type="hidden" name="purchaseaAdviseFilter" value="Delivered">
                                 @elseif(isset($qstring_sort_type_order) && $qstring_sort_type_order =='Delivered' )
                                <input type="hidden" name="purchaseaAdviseFilter" value="Delivered">
                                @elseif(($qstring_sort_type_order =='') || isset($qstring_sort_type_order) && $qstring_sort_type_order =='in_process')
                                <input type="hidden" name="purchaseaAdviseFilter" value="Inprocess">
                                @else
                                <input type="hidden" name="purchaseaAdviseFilter" value="Inprocess">
                                @endif
                                <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                            </form>
                            <form class="pull-left" method="POST" action="{{URL::action('PurchaseAdviseController@exportPurchaseAdviseBasedOnStatus')}}">
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="export_from_date" id="export_from_date" <?php
                                if (Input::get('export_to_date') != "") {
                                    echo "value='" . Input::get('export_from_date') . "'";
                                }
                                ?>>
                                <input type="hidden" name="export_to_date" id="export_to_date" <?php
                                if (Input::get('export_to_date') != "") {
                                    echo "value='" . Input::get('export_to_date') . "'";
                                }
                                ?>>
                                @if(isset($qstring_sort_type_order) && $qstring_sort_type_order =='Delivered' )
                                <input type="hidden" name="purchaseaAdviseFilter" value="Delivered">
                                @elseif(isset($qstring_sort_type_order) && $qstring_sort_type_order =='delivered' )
                                <input type="hidden" name="purchaseaAdviseFilter" value="Delivered">
                                @elseif(($qstring_sort_type_order =='') || isset($qstring_sort_type_order) && $qstring_sort_type_order =='in_process')
                                <input type="hidden" name="purchaseaAdviseFilter" value="Inprocess">
                                @else
                                <input type="hidden" name="purchaseaAdviseFilter" value="Inprocess">
                                @endif
                                <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right " style=" float: left !important; margin-left: 2% !important;">
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div id="flash_message" class="alert no_data_msg_container"></div>
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
                                        <th>Tally Name</th>
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
                                    <tr id="purchase_advice_row_{{$pa->id}}">
                                        <td>{{ $i }}</td>
                                        <td>{{ date("F jS, Y", strtotime($pa->updated_at)) }}</td>
                                        <td>{{($pa['supplier']->tally_name != "" ) ? $pa['supplier']->tally_name:$pa['supplier']->owner_name}}</td>
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

                                            @if($pa->serial_number == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                            @if($pa->advice_status == 'in_process')
                                            <a href="{{url('purchaseorder_advise/'.$pa->id.'/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @elseif($pa->serial_number != "" && Auth::user()->role_id == 0  || Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                                            <span class="table-link normal_cursor" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                            @if($pa->serial_number == ""  || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link" title="print" data-toggle="modal" data-target="#printModal" data-vehicle_number="{{$pa->vehicle_number}}" onclick="print_purchase_advice({{$pa->id}},'{{$pa->vehicle_number}}')">
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
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#deletePurchaseAdvice" title="delete" onclick="delete_purchase_advice({{$pa->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>


                                    <?php $i++; ?>
                                    @endforeach
                                <div class="modal fade" id="deletePurchaseAdvice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" id="delete_purchase_advice">
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
                                                            <div class="pwdr"><input class="form-control" id="pwdr" placeholder="" name="password" type="password" type="text"></div>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>cancel</b> this advise</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                    <button type="button" class="btn btn-default delete_purchase_advice_submit" id="delete_purchase_advice_submit" data-dismiss="modal">Yes</button>
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
                                                <div class="row print_time ">
                                                    <div class="col-md-12"> Print By <br>
                                                        <span class="current_time"></span>
                                                    </div>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" value="" id="checksms"><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-3">
                                                    <label><span title="Vehicle Number" class="smstooltip empty_truck_weight_title">Vehicle Number</span></label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" id="vehicle_no" value="" class="form-control vehicle_number" name="vehicle_number" >
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="pa_id" id="pa_id"/>                                                
                                                <div >
                                                    <button type="button" class="btn btn-primary form_button_footer print_purchase_advise" data-dismiss="modal">Print</button>
                                                    <button class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $purchase_advise->appends(Input::except('page'))->render(); ?>
                            </span>
                             <div class="clearfix"></div>
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