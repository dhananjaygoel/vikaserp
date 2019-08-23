@extends('layouts.master')
@section('title','Delivery Order')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Delivery Orders</span></li>
                </ol>
                <input type="hidden" id="module" value="deliveryorder">
                <!--<h1 class="pull-left">Delivery Orders</h1>-->
                <div class="filter-block">
                    <!--                    <a href="{{URL::action('DeliveryOrderController@create')}}" class="btn btn-primary pull-right">
                                            <i class="fa fa-plus-circle fa-lg"></i> Create Delivery order Independently
                                        </a>-->
                    <!--<div class="form-group pull-right">-->

                    <form method="GET" action="{{URL::action('DeliveryOrderController@index')}}" id="filter_form">
                        <div class=" pull-right col-md-3">
                            <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                            <?php
                            $session_sort_type_order = Session::get('order-sort-type');
                            if ((Input::get('order_status') != "") || (Input::get('delivery_order_status') != "")) {
                                if (Input::get('order_status') != "") {
                                    $qstring_sort_type_order = Input::get('order_status');
                                } elseif (Input::get('delivery_order_status') != "") {
                                    $qstring_sort_type_order = Input::get('delivery_order_status');
                                }
                            }
                            if (!empty($qstring_sort_type_order) && trim($qstring_sort_type_order) != "") {
                                $qstring_sort_type_order = $qstring_sort_type_order;
                            } else {
                                $qstring_sort_type_order = $session_sort_type_order;
                            }
                            ?>
                            <select class="form-control" id="order_status" name="order_status" onchange="this.form.submit()">
                                <option <?php if ($qstring_sort_type_order == 'Inprocess') echo 'selected=""'; ?> value="Inprocess">Inprocess</option>
                                <option <?php if ($qstring_sort_type_order == 'Delivered') echo 'selected=""'; ?> value="Delivered">Delivered</option>

                            </select>
                            <?php
                            if (isset($session_sort_type_order)) {
                                Session::put('order-sort-type', "");
                            }
                            ?>
                        </div>
                    </form>

                    <!--</div>-->
                    <div class="search_form_wrapper delivery_challan_search_form_wrapper">
                        <form class="search_form" method="GET" action="{{URL::action('DeliveryOrderController@index')}}">
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
                            @if(isset($qstring_sort_type_order) && $qstring_sort_type_order =='Delivered' )
                            <input type="hidden" name="delivery_order_status" value="Delivered">
                            @elseif(($qstring_sort_type_order =='') || isset($qstring_sort_type_order) && $qstring_sort_type_order =='Inprocess')
                            <input type="hidden" name="delivery_order_status" value="Inprocess">
                            @else
                            <input type="hidden" name="delivery_order_status" value="Inprocess">
                            @endif
                            <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                        </form>
                        <form class="pull-left" method="POST" action="{{URL::action('DeliveryOrderController@exportDeliveryOrderBasedOnStatus')}}">
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
                            <input type="hidden" name="delivery_order_status" value="Delivered">
                            @elseif(($qstring_sort_type_order =='') || isset($qstring_sort_type_order) && $qstring_sort_type_order =='Inprocess')
                            <input type="hidden" name="delivery_order_status" value="Inprocess">
                            @else
                            <input type="hidden" name="delivery_order_status" value="Inprocess">
                            @endif
                            <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right " style=" float: left !important; margin-left: 2% !important;">
                        </form>
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
                        @if(Session::has('error'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('error') }} </strong>
                        </div>
                        @endif

                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">{{Session::get('success')}}</div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div class="alert alert-success alert-success1">
                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                            <strong>Well done!</strong> User details successfully added.
                        </div><br/>
                        @endif
                        @if (Session::has('wrong'))
                        <div class="alert alert-danger alert-success1">{{Session::get('wrong')}}</div>
                        @endif
                        @if(sizeof($delivery_data) != 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        @if(Input::has('flag') && Input::get('flag') == 'true')
                                        <th><a href="{{url('delivery_order?flag=false')}}">Flag</a></th>
                                        @else
                                        <th><a href="{{url('delivery_order?flag=true')}}">Flag</a></th>
                                        @endif
                                        <th class="">#</th>
                                        <th>Date</th>
                                        <th>Tally Name</th>
                                        <th>Delivery Location</th>
<!--                                        <th>Quantity</th>-->
                                        <th>Present Shipping</th>
                                        <th>Pending Order</th>
                                        <th>Vehicle Number</th>
                                      
                                        @if(Input::get('order_status') == 'Inprocess' || Input::get('order_status') == '')
                                        @if( Auth::user()->role_id != 8 && Auth::user()->role_id != 9 )
                                        <th class="text-center">Create Delivery Challan</th>
                                        @endif
                                        @endif
                                        <th class="text-center col-md-2">Actions</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($delivery_data->currentPage() - 1 ) * $delivery_data->perPage() + 1; ?>
                                    @foreach($delivery_data as $delivery)
                                    <tr id="delivery_order_row_{{$delivery->id}}" {{($delivery->present_shipping==0)?'style = display:none':''}} >
                                        <td>
                                            <span class="{{($delivery->flaged==true)?'filled_star flags':'empty_star flags'}}" data-orderid="{{$delivery->id}}"></span>
                                        </td>
                                        <td>{{ $i++ }}</td>
                                        <td>{{date("F jS, Y", strtotime($delivery->created_at)) }}</td>
                                        <td>
                                            @if(isset($delivery['customer']->tally_name) && $delivery['customer']->tally_name != "")
                                            {{$delivery['customer']->tally_name}}
                                            @else
                                            {{isset($delivery['customer']->owner_name)?$delivery['customer']->owner_name:'N/A'}}

                                            @endif
                                        </td>
                                        <td>
                                            @if(count($delivery['location']))
                                            {{$delivery['location']['area_name']}}
                                            @else
                                            {{$delivery->other_location}}
                                            @endif
                                        </td>
<!--                                        <td>
                                            {{ round($delivery->total_quantity, 2) }}
                                        </td>-->
                                        <td>
                                            {{ round($delivery->present_shipping, 2) }}
                                        </td>
                                        <td>
                                            {{ round($delivery->pending_order, 2) }}
                                        </td>
                                        <td>
                                            {{$delivery->vehicle_number}}
                                        </td>
                                     
                                        @if(Input::get('order_status') == 'Inprocess' || Input::get('order_status') == '')
                                        @if( Auth::user()->role_id != 8 && Auth::user()->role_id != 9 )
                                        <td class="text-center">
                                            <!-- $delivery->serial_no != "" -->
                                            @if($delivery->final_truck_weight != null && $delivery->final_truck_weight != 0)
                                            <a href="{{url('create_delivery_challan/'.$delivery->id)}}" class="table-link" title="Delivery challan">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @else
                                            <span class="table-link normal_cursor" title="Delivery challan">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif
                                        </td>
                                        @endif
                                        @endif
                                        <td class="text-center actionicons">
                                            <?php
                                                  // $disable = "disabled";
                                                  // if($delivery->order_details['del_boy'] OR $delivery->order_details['del_supervisor'])
                                                  // {
                                                  //    $disable = "";
                                                  // }
                                            ?>    
                                             @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 8   )
                                              <button class="btn btn-primary assign_load" id="assign_load" data-order_id="{{$delivery->order_id}}" 
                                            data-role_id ="{{Auth::user()->role_id}}"
                                           data-delivery_id="{{$delivery->id}}" 
                                           data-supervisor_id="{{$delivery->del_supervisor}}" 
                                           data-delivery_boy="{{$delivery->del_boy}}" 
                                       data-final_truck_weight="{{$delivery->final_truck_weight}}" 
                                       data-product_detail_table="{{$delivery->product_detail_table}}" 
                                       data-labour_pipe="{{$delivery->labour_pipe}}" 
                                       data-labour_structure="{{$delivery->labour_structure}}" 
                                       data-toggle="modal" data-target="#myModalassign" 
                                       title="assign" type="button"  style="padding-right: 6px;padding-left: 6px;padding-top: 0px;padding-bottom: 0px;"><i class="fa fa-user fa-stack-3x fa-inverse"></i></button>
                                      
                                           @endif   
                                           
                                           
                                                                       
                                                <?php

                                                  // $disable = "disabled";
                                                  // if($delivery->order_details['del_boy'] OR $delivery->order_details['del_supervisor'])
                                                  // {
                                                  //    $disable = "";
                                                  // }
                                            if(Auth::user()->role_id == 0 && $delivery->final_truck_weight >0){
                                                $tclass ="trucksuccess";
                                            }
                                            else{
                                                if(Auth::user()->role_id == 9){
                                                    $tclass ="trucksuccess";
                                                }
                                                else{
                                                     $tclass ="disabled";
                                                }
                                               
                                            }
                                            ?>  
                                             @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 9   )                                         
                                             <a style="padding-right: 6px;padding-left: 6px;padding-top: 0px;padding-bottom: 0px;" href="{{url('create_load_truck/'.$delivery->id)}}" class="btn btn-primary truck_load <?php echo $tclass; ?>" id="truck_load" title="Load truck"><i class="fa fa-truck fa-stack-3x fa-inverse"></i></a>

                                            <!-- <a class="table-link truck_load" title="truck_load" data-order_id="{{$delivery->order_id}}" id="truck_load" data-toggle="modal" href="#myModal" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-truck fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a> -->
                                              @endif
                                            
                                            <a href="{{URL::action('DeliveryOrderController@show',['id'=> $delivery->id])}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                          
                                            <!-- <a class="table-link truck_load" title="truck_load" data-order_id="{{$delivery->order_id}}" id="truck_load" data-toggle="modal" href="#myModal" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-truck fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a> -->
                                               @if($delivery->order_status == 'pending')
                                                @if(($delivery->serial_no == "" ||  Auth::user()->role_id == 8  || Auth::user()->role_id == 0  || Auth::user()->role_id == 1 || Auth::user()->role_id == 4 || Auth::user()->role_id == 2))

                                                    @if(Auth::user()->role_id == 3  || Auth::user()->role_id == 0  || Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                                        <a href="{{URL::action('DeliveryOrderController@edit', ['id'=> $delivery->id])}}" class="table-link" title="edit">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>

                                                    </span>
                                                        </a>
                                                    @endif

                                                @elseif($delivery->serial_no != "" && Auth::user()->role_id == 0 || Auth::user()->role_id == 3 || Auth::user()->role_id == 8  || Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                                
												   <a href="{{URL::action('DeliveryOrderController@edit', ['id'=> $delivery->id])}}" class="table-link" title="edit">

                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                    </span>

                                                </a>
                                                @endif
                                            @endif


                                            @if($delivery->serial_no == "" || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                                @if(Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                                    <a href="#" class="table-link" title="print" data-toggle="modal" data-target="#print_challan" id="{{$delivery->id}}" data-bind="{{$delivery->empty_truck_weight}}" data-customer_type="{{$delivery->order_source}}" data-vehicle_number="{{$delivery->vehicle_number}}"  onclick="print_challan_do(this)">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                                    </a>
                                                @endif

                                            @elseif($delivery->serial_no != "" && Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <span class="table-link normal_cursor" title="print">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            @endif

                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1   )

                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModalDeleteDeliveryOrder" title="delete" onclick='delete_delivery_order({{$delivery->id}})'>
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($delivery->final_truck_weight != null && $delivery->final_truck_weight != 0 && $delivery->location_difference !=0)
                                                    🔵 Loaded    
                                            @else
                                                    🔴 Loading
                                            @endif
                                          
                                        </td>
                                    </tr>
                                    @endforeach
                                <div class="modal fade" id="myModalDeleteDeliveryOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <form method="POST" accept-charset="UTF-8" action="#" id="delete_delivery_order">
                                                <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                                <div class="modal-body">
                                                    <div class="delete">
                                                        <div><b>Mobile:</b> {{auth()->user()->mobile_number}}
                                                            <input type="hidden" name="mobile" value="{{auth()->user()->mobile_number}}"/>
                                                            <input type="hidden" name="user_id" id="user_id"/>
                                                        </div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" name="password" placeholder="" id="pwdr delivery_order_password" required="required" type="password"></div>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <input type="hidden" name="order_sort_type" value="{{($qstring_sort_type_order!="")?$qstring_sort_type_order:""}}"/>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                    <button type="submit" class="btn btn-default delete_delivery_order_submit">Yes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="print_challan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                     <?php
//                                                                                echo "<pre>";
//                                                                                print_r($delivery_data->toArray());
//                                                                                echo "</pre>";
                                                                                
                                                    ?>
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
                                                <hr>
                                                <div class="col-md-3">
                                                    <label><span title="Empty Truck Weight" class="smstooltip empty_truck_weight_title">Empty Truck Weight (Kg)</span></label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" id="empty_truck_weight" value="" class="form-control empty_truck_weight" name="empty_truck_weight" maxlength="10" onkeypress=" return numbersOnly(this,event,false,false);" >
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="col-md-3">
                                                    <label><span title="Vehicle Number" class="smstooltip ">Vehicle Number</span></label>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" id="vehicle_no" value="" class="form-control vehicle_number" name="vehicle_number" >
                                                </div>
                                                <div class="checkbox col-md-12">
                                                    <label><input type="checkbox" id="checksms" value=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div>
                                                    <button type="button" class="btn btn-primary form_button_footer print_delivery_order" id="print_delivery_order" >Print</button>
                                                    <button type="button" class="btn btn-default form_button_footer" data-dismiss="modal">Cancel</button>

                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <?php
//                                    if (!isset($_GET)) {
//                                        echo $delivery_data->render();
//                                    } else {
//                                        echo $delivery_data->appends($_GET)->render();
//                                    }
                                    echo $delivery_data->appends(Input::except('page'))->render();
                                    ?>
                                </ul>
                            </span>
                            <div class="clearfix"></div>
                            @if($delivery_data->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('delivery_order')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $delivery_data->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                        @else
                        <div class="alert alert-info no_data_msg_container">
                            Currently no delivery order data available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Final Truck Weight </h4>
            </div>
            <div class="modal-body">
                <p class="err-p text-center" style="font-weight: bold"></p>
                <input type="hidden"  name="order_id" id="order_id" class="form-control">
                <div class="form-group">
                    <input type="number" name="final_truck_weight" id="final_truck_weight" class="form-control" placeholder="Final Truck Weight" value="">
                </div>
                <div class="form-group">
                    <input type="button" value="Save" id="submit_2" onclick="loaded_truck_delivery()" class="btn btn-sm btn-primary">

                </div>


            </div>

        </div>

    </div>
</div>



<div class="modal fade" id="myModalsupassign" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign  </h4>
            </div>
            <div class="modal-body">
                <p class="err-p text-center" style="font-weight: bold"></p>
                <input type="hidden"  name="order_id" id="order_id" class="form-control">
                <?php $dduser = auth()->user();
                       $roleid = $dduser->role_id;
                
                      if($roleid ==2 || $roleid ==0) {
                          $type = "del_supervisor";
                           $options =array(''=>'Select Supervisor');
                           $array = \App\User::where('role_id',8)
                                       ->orderBy('id', 'DESC')
                                       ->get();
                           ?>
                            @foreach($array as $user)<?php 
                               $options[$user->id] = $user->first_name.' '.$user->last_name;
                              ?>
                            @endforeach
                        <?php
                      }
                      
                    ?>
                
                <div class="form-group">
                <?php if(!empty($delivery)){ 
                     
        ?>
                <select  class="form-control del_supervisor" name="del_supervisor"  data-order_id="{{$delivery->order_id}}"  data-role_id="{{$roleid}}"
                data-delivery_id="{{$delivery->id}}" id="del_supervisor"> 
                                                        @if($roleid ==2 || $roleid ==0) {
                                                        @foreach($options as $optkey =>$user)

                                                              <option  value = {{$optkey }}>{{$user}}</option>  
                                                        @endforeach
                                                        @endif
                   </select>
                
                 <input type ="hidden" name ="delivery_id" id="delivery_id" value ="{{$delivery->id}}">
                 <input type ="hidden" name ="_token" id = "token" value="{{csrf_token()}}"/>
                                                
                </div>
                <?php }?>
                <div class="form-group">
                    <input type="button" value="Save" id="submit_2" onclick="order_assign()" class="btn btn-sm btn-primary">

                </div>


            </div>

        </div>

    </div>
</div>

<div class="modal fade" id="myModalassign" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign  </h4>
            </div>
            <div class="modal-body">
                <p class="err-p text-center" style="font-weight: bold"></p>
                <input type="hidden"  name="order_id" id="order_id" class="form-control">
                <?php $dduser = auth()->user();
                       $roleid = $dduser->role_id;
                       
                
                if($roleid ==0 || $roleid ==8 || $roleid ==2){
                    if($roleid ==0) {
                          $type = "del_supervisor";
                           $options =array(''=>'Select Supervisor');
                           $array = \App\User::where('role_id',8)
                                       ->orderBy('id', 'DESC')
                                       ->get();
                           ?>
                            @foreach($array as $user)<?php 
                               $options[$user->id] = $user->first_name.' '.$user->last_name;
                              ?>
                            @endforeach
                        <?php
                      }
                      if($roleid ==2) {
                          $type = "del_supervisor";
                           $options =array(''=>'Select Supervisor');
                           $array = \App\User::where('role_id',8)
                                       ->orderBy('id', 'DESC')
                                       ->get();
                           ?>
                            @foreach($array as $user)<?php 
                               $options[$user->id] = $user->first_name.' '.$user->last_name;
                              ?>
                            @endforeach
                        <?php
                      }
                      if($roleid ==8){
                           $type = "del_boy";
                            $options =array(''=>'Select Delivery boy');
                            $array = \App\User::where('role_id',9)
                                       ->orderBy('id', 'DESC')
                                       ->get();
                         
                           ?>

                            @foreach($array as $user)<?php 
                               $options[$user->id] = $user->first_name.' '.$user->last_name;
                              ?>
                            @endforeach
                        <?php
                      }
                    ?>
                
                <div class="form-group">
                <?php if(!empty($delivery)){ 
                    
        
        ?>
                <select  class="form-control del_supervisor" name="del_supervisor"  data-order_id="{{$delivery->order_id}}"  data-role_id="{{$roleid}}" data-supervisor_id="{{$delivery->del_supervisor}}"
                 data-delivery_boy="{{$delivery->del_boy}}" data-delivery_id="{{$delivery->id}}" id="del_supervisor"> 
                                                        @foreach($options as $optkey =>$user)
                                                              <option value = {{$optkey }}>{{$user}}</option>  
                                                        @endforeach
                   </select>
                <input type ="hidden" name ="assign_type" id="assign_type" value = "{{$type}}">
                 <input type ="hidden" name ="delivery_id" id="delivery_id" value ="{{$delivery->id}}">
                 <input type ="hidden" name ="_token" id = "token" value="{{csrf_token()}}"/>
                                                
                </div>
                <?php }}?>
                <div class="form-group">
                    <input type="button" value="Save" id="submit_2" onclick="loaded_assign()" class="btn btn-sm btn-primary">

                </div>


            </div>

        </div>

    </div>
</div>
@stop