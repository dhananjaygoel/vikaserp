@extends('layouts.master')
@section('title','Purchase Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Purchase Challan</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Purchase Challan</h1>
                    <?php
                    $session_sort_type_order = Session::get('order-sort-type');
                    $qstring_sort_type_order = Input::get('order_filter');
                    if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                        $qstring_sort_type_order = $qstring_sort_type_order;
                    } else {
                        $qstring_sort_type_order = $session_sort_type_order;
                    }
                    ?>
                    <div class="pull-right top-page-ui">
                        <div class="form-group pull-right">
                            <form action="{{url('purchase_challan')}}" method="GET">
                                <div class="col-md-12">
                                    <select class="form-control" id="user_filter3" name="order_filter" onchange="this.form.submit();">
                                        <!--<option value="" selected="">--Status-- </option>-->
                                        <option <?php if (Input::get('order_filter') == 'pending') echo 'selected=""'; ?> value="pending">Pending</option>
                                        <option <?php if (Input::get('order_filter') == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                                    </select>
                                </div>
                            </form>
                        </div>


                        <div class="search_form_wrapper pull-right" style="width:100% ">

                            <form class="search_form" method="GET" action="{{URL::action('PurchaseChallanController@index')}}">
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
                                @if(Input::get('order_filter') == 'completed')
                                <input type="hidden" name="order_filter" value="completed">
                               
                                @elseif(Input::get('order_filter') == 'pending')
                                <input type="hidden" name="order_filter" value="pending">
                                @else
                                <input type="hidden" name="order_filter" value="pending">
                                @endif
                                <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                            </form>
                            <form class="pull-left" method="POST" action="{{URL::action('PurchaseChallanController@exportPurchaseChallanBasedOnStatus')}}">
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
                                @if(Input::get('order_filter') == 'completed' )
                                <input type="hidden" name="order_filter" value="completed">
                                @elseif(Input::get('order_filter') == 'delivered' )
                                <input type="hidden" name="order_filter" value="completed">
                                @elseif(Input::get('order_filter') == 'pending')
                                <input type="hidden" name="order_filter" value="pending">
                                @else
                                <input type="hidden" name="order_filter" value="pending">
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
                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">
                            {{Session::get('success')}}
                        </div>
                        @endif
                        @if(count($purchase_challan) > 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center ">Tally Name</th>
                                        <th class="text-center ">Serial Number</th>
                                        <th class="text-center">Bill Number</th>
                                        <th class="text-center">Bill date</th>
                                        <th class="text-center col-md-2">Total Quantity</th>
                                        <th class="text-center ">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($purchase_challan->currentPage() - 1) * $purchase_challan->perPage() + 1; ?>
                                    @foreach($purchase_challan as $challan)
                                    <tr id="purchase_challan_row_{{$challan->id}}">
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-center">
                                            @if($challan['supplier']->tally_name != "")
                                            {{$challan['supplier']->tally_name}}
                                            @else
                                            {{$challan['supplier']->owner_name}}
                                            @endif
                                        </td>
                                        <td class="text-center">{{$challan->serial_number}}</td>
                                        <td class="text-center">{{$challan->bill_number}}</td>
                                        <td class="text-center">{{date('F jS, Y',strtotime($challan['purchase_advice']->purchase_advice_date))}}</td>
                                        <td class="text-center">
                                            <?php
                                            $total_qty = 0;
                                            foreach ($challan['all_purchase_products'] as $pc) {
                                                if ($pc->unit_id == 1) {
                                                    $total_qty += $pc->quantity;
                                                }
                                                if ($pc->unit_id == 2) {
                                                    $total_qty += ($pc->quantity * $pc['purchase_product_details']->weight);
                                                }
                                                if ($pc->unit_id == 3) {
                                                    $total_qty += (($pc->quantity / $pc['purchase_product_details']->standard_length ) * $pc['purchase_product_details']->weight);
                                                }
                                            }
                                            echo round($challan['all_purchase_products']->sum('quantity'), 2);
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{URL::action('PurchaseChallanController@show',['id'=> $challan->id]) }}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            @if(($challan->order_status != 'completed' || Auth::user()->role_id == 0  || Auth::user()->role_id == 1) )
                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_model" onclick="print_purchase_challan({{$challan->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_purchase_challan" title="delete" onclick="delete_purchase_challan({{$challan->id}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>

                                    </tr>


                                    @endforeach
                                <div class="modal fade" id="delete_purchase_challan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" id="delete_purchase_challan_form">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <div class="delete">
                                                        <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" required="" placeholder="" type="password" name="password"></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="button" class="btn btn-default delete_purchase_challan_submit" id="delete_purchase_challan_submit">Yes</button>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="print_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="purchase_challan_id" id="purchase_challan_id"/>
                                                <div class="row print_time">
                                                    <div class="col-md-12"> Print By <br>
                                                        <span class="current_time"></span>
                                                    </div>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" value=""  id="checksms" ><span title="SMS would be sent to Relationship Manager" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div >
                                                    <button type="button" class="btn btn-primary form_button_footer print_purchase_challan" data-dismiss="modal" >Generate Challan</button>
                                                    <a class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</a>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php 
                                 echo $purchase_challan->appends(Input::except('page'))->render();?>
                            </span>
                            <div class="clearfix"></div>
                            @if($purchase_challan->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('purchase_challan')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $purchase_challan->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                        @else
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <strong> No purchase challan found</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop