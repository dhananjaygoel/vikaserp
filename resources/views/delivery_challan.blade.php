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
                            <select class="form-control" id="user_filter3" name="status_filter" onchange="this.form.submit();">
                                <option disabled="" value="" selected="">Status</option>
                                <option <?php if (Input::get('status_filter') == 'pending') echo 'selected=""'; ?> value="pending">Inprogress</option>
                                <option <?php if (Input::get('status_filter') == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                            </select>
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
                                        <th class="text-center">Party Name</th>
                                        <th class="text-center">Serial Number</th>
                                        <th class="text-center">Present Shipping</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    
                                    <?php $k = 1; ?>
                                    @foreach($allorders as $challan)
                                    @if($challan->challan_status == 'pending')

                                    <tr>
                                        <td class="text-center">{{$k++}}</td>
                                        <td class="text-center">{{$challan['customer']->owner_name}}</td>
                                        <td class="text-center">
                                            @if($challan->serial_number == '')

                                            @elseif($challan->serial_number != '')
                                            {{$challan->serial_number}}
                                            @endif
                                        </td>                                        
                                        <td class="text-center"><?php
                                            $total_shipping = 0;
                                            foreach ($challan['all_order_products'] as $products) {
                                                $total_shipping = $total_shipping + $products['present_shipping'];
//                                            echo ' '.$products['present_shipping'];
                                            }
                                            echo $total_shipping;
                                            ?></td>



                                        <td class="text-center">
                                            <a href="{{url('delivery_challan/'.$challan->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="{{url('delivery_challan/'.$challan->id.'/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_challan_{{$challan->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
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
                                                <form method="POST" action="print_delivery_challan/{{$challan->id}}" accept-charset="UTF-8" >
                                                    <input type="hidden" name="_token"value="{{csrf_token()}}">
                                                    <input type="hidden" name="serial_number" value="{{$challan['delivery_order']->serial_no}}">
                                                    <input type="hidden" name="delivery_order_id" value="{{$challan['delivery_order']->id}}">
                                                    <div class="row print_time"> 
                                                        <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" value="" checked=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    <div >
                                                        <button type="submit" class="btn btn-primary form_button_footer" >Generate Challan</button>
                                                        <!--<button type="button" class="btn btn-primary form_button_footer" >Send Message</button>-->
                                                        <a href="{{url('delivery_challa')}}" class="btn btn-default form_button_footer">Cancel</a>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    {!! Form::close() !!}
                                            </div>           
                                            <!--    <div class="modal-footer">
                                                
                                                <button type="button" class="btn btn-primary">No</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                                </div>-->
                                        </div>
                                    </div>
                                </div> 
                                @elseif($challan->challan_status == 'completed')
                                <tr>
                                    <td class="text-center">{{$k++}}</td>
                                    <td class="text-center">{{$challan['customer']->owner_name}}</td>
                                    <td class="text-center">
                                        @if($challan->serial_number == '')
                                        @elseif($challan->serial_number != '')
                                        {{$challan->serial_number}}
                                        @endif
                                    </td>                                        
                                    <td class="text-center"><?php
                                        $total_shipping = 0;
                                        foreach ($challan['all_order_products'] as $products) {
                                            $total_shipping = $total_shipping + $products['present_shipping'];
//                                            echo ' '.$products['present_shipping'];
                                        }
                                        echo $total_shipping;
                                        ?></td>
                                    <td class="text-center">
                                        <a href="{{url('delivery_challan/'.$challan->id)}}" class="table-link" title="view">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>

                                        <a href="{{url('delivery_challan/'.$challan->id.'/edit')}}" class="table-link" title="edit">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>

                                        <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_challan_{{$challan->id}}">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
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
                                                <form method="POST" action="print_delivery_challan/{{$challan->id}}" accept-charset="UTF-8" >
                                                    <input type="hidden" name="_token"value="{{csrf_token()}}">

                                                    <input type="hidden" name="serial_number" value="{{$challan['delivery_order']->serial_no}}">
                                                    <input type="hidden" name="delivery_order_id" value="{{$challan['delivery_order']->id}}">
                                                    <div class="row print_time"> 
                                                        <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" value="" checked=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    <div >
                                                        <button type="submit" class="btn btn-primary form_button_footer" >Generate Challan</button>
                                                        <!--<button type="button" class="btn btn-primary form_button_footer" >Send Message</button>-->
                                                        <a href="{{url('delivery_challa')}}" class="btn btn-default form_button_footer">Cancel</a>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                    {!! Form::close() !!}


                                            </div>           
                                            <!--    <div class="modal-footer">
                                                
                                                <button type="button" class="btn btn-primary">No</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                                </div>-->
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

                        </div>    
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop