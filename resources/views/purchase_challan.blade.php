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
                    <div class="pull-right top-page-ui">
                        <form action="{{url('purchase_challan')}}" method="GET">
                            <select class="form-control" id="user_filter3" name="order_filter" onchange="this.form.submit();">
                                <option value="" selected="">--Status-- </option>
                                <option <?php if (Input::get('order_filter') == 'pending') echo 'selected=""'; ?> value="pending">Pending</option>
                                <option <?php if (Input::get('order_filter') == 'completed') echo 'selected=""'; ?> value="completed">Completed</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">

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
                                        <th class="text-center ">Party Name</th>
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
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-center">{{$challan['supplier']->owner_name}}</td>
                                        <td class="text-center">{{$challan->serial_number}}</td>
                                        <td class="text-center">{{$challan->bill_number}}</td>
                                        <td class="text-center">{{date('jS F, Y',strtotime($challan['purchase_advice']->purchase_advice_date))}}</td>
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
                                            echo round($total_qty, 2);
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{URL::action('PurchaseChallanController@show',['id'=> $challan->id]) }}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            @if($challan->order_status != 'completed' || Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_model_{{$challan->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @if( Auth::user()->role_id == 0  || Auth::user()->role_id == 1)
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_purchase_challan_{{$challan->id}}" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>

                                    </tr>

                                <div class="modal fade" id="delete_purchase_challan_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(array('method'=>'DELETE','url'=>url('purchase_challan',$challan->id), 'id'=>'delete_purchase_challan_form'))!!}
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
                                                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="print_model_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <input type="hidden" name="challan_id" value="{{$challan->id}}"/>
                                                <div class="row print_time">
                                                    <div class="col-md-12"> Print By <br> 05:00 PM</div>
                                                </div>
                                                <div class="checkbox">
                                                    <label><input type="checkbox" value=""  id="checksms" ><span title="SMS would be sent to Relationship Manager" class="checksms smstooltip">Send SMS</span></label>
                                                </div>
                                                <div class="clearfix"></div>
                                                <hr>
                                                <div >
                                                    <button type="button" class="btn btn-primary form_button_footer print_purchase_challan" id="{{$challan->id}}" >Generate Challan</button>
                                                    <a href="#" class="btn btn-default form_button_footer">Cancel</a>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $purchase_challan->render(); ?>
                            </span>
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