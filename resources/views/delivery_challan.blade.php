@extends('layouts.master')
@section('title','Delivery Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Delivery Challan</span></li>
                </ol>
                <div class="filter-block">
                    <form action="{{url('delivery_challan')}}" method="GET">
                        <div class=" pull-right col-md-3">
                            <?php
                            $session_sort_type_order = Session::get('order-sort-type');
                            $qstring_sort_type_order = Input::get('status_filter');

                            if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                                $qstring_sort_type_order = $qstring_sort_type_order;
                            } else {
                                $qstring_sort_type_order = $session_sort_type_order;
                            }
                            ?>
                            <select class="form-control" id="user_filter3" name="status_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Status--</option>
                                <option <?php if ($qstring_sort_type_order == 'pending') echo 'selected=""'; ?> value="pending">Inprogress</option>
                                <option <?php if ($qstring_sort_type_order == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                            </select>
                            <?php
                            if (isset($session_sort_type_order)) {
                                Session::put('order-sort-type', "");
                            }
                            ?>
                            <br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($allorders)==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no orders have been added to Delivery Challan.
                        </div>
                        @else
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Tally Name</th>
                                        <th class="text-center">Serial Number</th>
                                        <th class="text-center">Present Shipping</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1; ?>
                                    @foreach($allorders as $challan)
                                    @if($challan->challan_status == 'pending')
                                    <tr>
                                        <td class="text-center">{{$k++}}</td>
                                        <td class="text-left">

                                            @if($challan['customer']->tally_name != "")
                                            {{$challan['customer']->tally_name}}
                                            @else
                                            {{$challan['customer']->owner_name}}
                                            @endif

                                        </td>
                                        <td class="text-center">
                                            @if($challan->serial_number == '')
                                            @elseif($challan->serial_number != '')
                                            {{$challan->serial_number}}
                                            @endif
                                        </td>
                                        <td class="text-center">{{ round($challan->total_quantity, 2) }}</td>
                                        <td class="text-center">
                                            <a href="{{url('delivery_challan/'.$challan->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id != 4)
                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_challan_{{$challan->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_{{$challan->id}}" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                <div class="modal fade" id="delete_challan_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('method'=>'POST','url'=>url('delivery_challan',$challan->id), 'id'=>'delete_delivery_challan_form'))!!}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>

                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="print_challan_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token"value="{{csrf_token()}}">
                                                <input type="hidden" name="serial_number" value="{{$challan['delivery_order']->serial_no}}">
                                                <input type="hidden" name="delivery_order_id" value="{{$challan['delivery_order']->id}}">
                                                <div class="row print_time">
                                                    <div class="col-md-12"> Print By <br>
                                                        <span class="current_time"></span>
                                                    </div>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" value="" id="checksms"><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div >
                                                    <button type="submit" class="btn btn-primary form_button_footer print_delivery_challan" id="{{$challan->id}}">Generate Challan</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif($challan->challan_status == 'completed')
                                <tr>
                                    <td class="text-center">{{$k++}}</td>
                                    <td class="text-center">{{$challan['customer']->tally_name}}</td>
                                    <td class="text-center">
                                        @if($challan->serial_number == '')
                                        @elseif($challan->serial_number != '')
                                        {{$challan->serial_number}}
                                        @endif
                                    </td>
                                    <td class="text-center">{{round($challan->total_quantity, 2)}}</td>
                                    <td class="text-center">
                                        <a href="{{url('delivery_challan/'.$challan->id)}}" class="table-link" title="view">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                        <!--                                        <a href="{{url('delivery_challan/'.$challan->id.'/edit')}}" class="table-link" title="edit">
                                                                                    <span class="fa-stack">
                                                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                                                    </span>
                                                                                </a>
                                        -->
                                        <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_challan_{{$challan->id}}">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_{{$challan->id}}" title="delete">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                <div class="modal fade" id="delete_challan_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            {!! Form::open(array('method'=>'POST','url'=>url('delivery_challan',$challan->id), 'id'=>'delete_delivery_challan_form'))!!}
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>

                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="print_challan_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token"value="{{csrf_token()}}">

                                                <input type="hidden" name="serial_number" value="{{$challan['delivery_order']->serial_no}}">
                                                <input type="hidden" name="delivery_order_id" value="{{$challan['delivery_order']->id}}">
                                                <div class="row print_time">
                                                    <div class="col-md-12"> Print By <br>
                                                        <span class="current_time"></span>
                                                    </div>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" value="" id="checksms"><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div >
                                                    <button type="submit" class="btn btn-primary form_button_footer print_delivery_challan" id="{{$challan->id}}">Generate Challan</button>
                                                    <!--<button type="button" class="btn btn-primary form_button_footer" >Send Message</button>-->
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach

                                </tbody>
                            </table>

                            <span class="pull-right">
                                <?php echo $allorders->render(); ?>
                            </span>
                            <span class="clearfix"></span>

                            @if($allorders->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('delivery_challan')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $allorders->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif

                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop