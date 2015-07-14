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
                                        <td class="text-center">{{$challan['purchase_advice']->purchase_advice_date}}</td>
                                        <td class="text-center">250</td>
                                        <td class="text-center">
                                            <a href="{{URL::action('PurchaseChallanController@show',['id'=> $challan->id]) }}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <span class="table-link" title="edit">
                                                <!--<a href="{{URL::action('PurchaseChallanController@edit',['id'=> $challan->id])}}" class="table-link" title="edit">-->
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </span>
                                            <!--</a>-->

                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#print_model_{{$challan->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
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
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <b class="clearfix">
                                    Showing  {{($purchase_challan->currentPage() - 1 ) * $purchase_challan->perPage() + 1 }} to 
                                    {{ ($purchase_challan->currentPage() - 1 ) * $purchase_challan->perPage() + $purchase_challan->count()}} of
                                    {{ $purchase_challan->total()}}
                                </b>      
                            </span> 
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