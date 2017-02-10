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
                    
                    <div class="search_form_wrapper delivery_challan_search_form_wrapper">
                        <form class="search_form" method="GET" action="{{URL::action('DeliveryChallanController@index')}}">
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
                            @if(sizeof($allorders)!=0 && ($qstring_sort_type_order == 'pending' ||$qstring_sort_type_order==''))
                            <input type="hidden" name="delivery_order_status" value="pending">
                            @elseif(sizeof($allorders)!=0 && $qstring_sort_type_order == 'completed')
                            <input type="hidden" name="delivery_order_status" value="completed">
                            @else
                            <input type="hidden" name="delivery_order_status" value="pending">
                            @endif
                            <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                        </form>
                        <form class="pull-left" method="POST" action="{{URL::action('DeliveryChallanController@exportDeliveryChallanBasedOnStatus')}}">
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
                            @if($qstring_sort_type_order == 'pending' || $qstring_sort_type_order == '')
                            <input type="hidden" name="delivery_order_status" value="pending">
                            @elseif($qstring_sort_type_order == 'completed')
                            <input type="hidden" name="delivery_order_status" value="completed">
                            @else
                            <input type="hidden" name="delivery_order_status" value="pending">
                            @endif
                            <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right" style=" float: left !important; margin-left: 2% !important;">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div id="flash_message" class="alert no_data_msg_container"></div>
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
                                        <th class="text-center">Pending Order</th>
                                        <th class="text-center">VAT</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1; ?>
                                    @foreach($allorders as $challan)
                                    <?php
//                            echo "<pre>";
//print_r($challan['customer']);
//echo "</pre>";
//exit;
                                    ?>
                                    
                                    @if($challan->challan_status == 'pending')
                                    <tr id="challan_order_row_{{$challan->id}}">
                                        <td class="text-center">{{$k++}}</td>
                                        <td class="text-center">
                                            {{ ($challan['customer']->tally_name != "") ? $challan['customer']->tally_name : $challan['customer']->owner_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ ($challan->serial_number != '') ? $challan->serial_number : '' }}
                                        </td>
                                        <td class="text-center">{{ round($challan->total_quantity, 2) }}</td>
                                        <td class="text-center">{{ (round($challan->total_quantity_pending, 2)>0)? round($challan->total_quantity_pending, 2)  :0 }}</td>
                                        <td class="text-center">{{ !empty(round($challan->vat_percentage, 2))? round($challan->vat_percentage, 2):0}}</td>
                                        <td class="text-center">
                                            <a href="{{url('delivery_challan/'.$challan->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if(Auth::user()->role_id == 0)
                                            <a href="{{URL::action('DeliveryChallanController@edit', ['id'=> $challan->id])}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id != 4)
                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_challan" onclick="print_delivery_challan({{$challan->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan" title="delete" onclick="delete_challan({{$challan->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>


                                    @elseif($challan->challan_status == 'completed')
                                    <tr id="challan_order_row_{{$challan->id}}">
                                        <td class="text-center">{{$k++}}</td>
                                        <td class="text-center">{{ ($challan['customer']->tally_name != "") ? $challan['customer']->tally_name : $challan['customer']->owner_name }}</td>
                                        <td class="text-center">
                                            @if($challan->serial_number == '')
                                            @elseif(isset($challan->serial_number) && $challan->serial_number != '')
                                            {{$challan->serial_number}}
                                            @endif
                                        </td>
                                        <td class="text-center">{{round($challan->total_quantity, 2)}}</td>
                                         <td class="text-center">{{ (round($challan->total_quantity_pending, 2)>0)? round($challan->total_quantity_pending, 2)  :0 }}</td>
                                         <td class="text-center">{{ !empty(round($challan->vat_percentage, 2))? round($challan->vat_percentage, 2):0}}</td>
                                         
                                         <td class="text-center">
                                            <a href="{{url('delivery_challan/'.$challan->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if(Auth::user()->role_id == 0)
                                            <a href="{{URL::action('DeliveryChallanController@edit', ['id'=> $challan->id])}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <!--                                                                                    <a href="{{url('delivery_challan/'.$challan->id.'/edit')}}" class="table-link" title="edit">
                                                                                                                                    <span class="fa-stack">
                                                                                                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                                                                                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                                                                                                    </span>
                                                                                                                                </a>-->

                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_challan" onclick="print_delivery_challan({{$challan->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan" title="delete" onclick="delete_challan({{$challan->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                <div class="modal fade" id="delete_challan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>

                                            <form method="POST" id="delete_delivery_challan">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" id="challan_id">
                                                <div class="modal-body">
                                                    <div class="delete">
                                                        <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" placeholder="" name="password" id="pwdr" type="password"></div>

                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="order_sort_type" value="{{ ($qstring_sort_type_order!="") ? $qstring_sort_type_order : "" }}"/>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                    <button type="button" class="btn btn-default delete_challan_submit" id="delete_challan_submit" data-dismiss="modal">Yes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="print_challan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token"value="{{csrf_token()}}">
                                                <input type="hidden" name="serial_number" value="{{$challan['delivery_order']->serial_no}}">
                                                <input type="hidden" name="delivery_order_id" value="{{isset($challan['delivery_order']->id) ? $challan['delivery_order']->id:''}}">
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
                                                    <button type="button" class="btn btn-primary form_button_footer print_delivery_challan" id="print_delivery_challan">Generate Challan</button>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php //echo $allorders->render();  ?>
                                <?php
//                                if (!isset($_GET)) {
//                                    echo $allorders->render();
//                                } else {
//                                    echo $allorders->appends($_GET)->render();
//                                }
                                echo $allorders->appends(Input::except('page'))->render();
                                ?>
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