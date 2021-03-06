@extends('layouts.master')
@section('title','Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="{{url('dashboard')}}">Home</a></li>
            <li class="active"><span>Inquiry</span></li>
        </ol>
        <div class="clearfix">

            <div class="pull-right top-page-ui">
                <a href="{{URL::action('InquiryController@create')}}" class="btn btn-primary pull-right">
                    <i class="fa fa-plus-circle fa-lg"></i> Add New Inquiry
                </a>
                 @if( Auth::user()->role_id <> 5)
                 
                <div class="form-group pull-right">
                    
                    <div class="col-md-12">
                       
                        <form method="GET" action="{{url('inquiry')}}">
                            <select class="form-control" id="inquiry_filter" name="inquiry_filter" onchange="this.form.submit();">
                                <option <?php if (Input::get('inquiry_filter') == 'Pending') echo 'selected=""'; ?> value="Pending">Pending</option>
                                <!--<option <?php if (Input::get('inquiry_filter') == 'Approval') echo 'selected=""'; ?> value="Approval">Pending Approval</option>-->
                                <option <?php if (Input::get('inquiry_filter') == 'Completed') echo 'selected=""'; ?> value="Completed">Completed</option>
                            </select>
                        </form>
                    </div>
                </div>
                <form action="{{URL::action('InquiryController@exportinquiryBasedOnStatus')}}" class="pull-right">
                            <input type="hidden" name="inquiry_status" id="inquiry_status" <?php
                            if (sizeof($inquiries)!=0 && (Input::get('inquiry_filter') == 'Pending' ||Input::get('inquiry_filter')=='')) {
                                echo "value='Pending'";
                            }elseif(sizeof($inquiries)!=0 && Input::get('inquiry_filter') == 'Completed'){
                                echo "value='Completed'";
                            }elseif(sizeof($inquiries)!=0 && Input::get('inquiry_filter') == 'Approval'){
                                echo "value='Pending_Approval'";
                            }
                            ?>>
                           <input type="submit" value="Export" class="btn btn-primary pull-right">
                </form>
                <!-- @if(sizeof($inquiries)!=0 && (Input::get('inquiry_filter') == 'Pending' ||Input::get('inquiry_filter')==''))
                <a href="{{URL::action('InquiryController@exportinquiryBasedOnStatus',['inquiry_status'=>'Pending'])}}" class="btn btn-primary pull-right">
                    Export
                </a>
                @endif
                @if(sizeof($inquiries)!=0 && Input::get('inquiry_filter') == 'Completed')
                <a href="{{URL::action('InquiryController@exportinquiryBasedOnStatus',['inquiry_status'=>'Completed'])}}" class="btn btn-primary pull-right">
                    Export
                </a>
                @endif
                @if(sizeof($inquiries)!=0 && Input::get('inquiry_filter') == 'Approval')
                <a href="{{URL::action('InquiryController@exportinquiryBasedOnStatus',['inquiry_status'=>'Pending_Approval'])}}" class="btn btn-primary pull-right">
                    Export
                </a>
                @endif -->
                
                @endif
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-lg-12">
        <h1 class="pull-left">Inquiry</h1>
        <!-- <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Inquiry</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Inquiry</h1>
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('InquiryController@create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New Inquiry
                        </a>
                        <div class="form-group pull-right">
                            <div class="col-md-12">
                                <form method="GET" action="{{url('inquiry')}}">
                                    <select class="form-control" id="inquiry_filter" name="inquiry_filter" onchange="this.form.submit();">
                                        <option <?php if (Input::get('inquiry_filter') == 'Pending') echo 'selected=""'; ?> value="Pending">Pending</option>
                                        <option <?php if (Input::get('inquiry_filter') == 'Completed') echo 'selected=""'; ?> value="Completed">Completed</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        @if(sizeof($inquiries)!=0 && (Input::get('inquiry_filter') == 'Pending' ||Input::get('inquiry_filter')==''))
                    <a href="{{URL::action('InquiryController@exportinquiryBasedOnStatus',['inquiry_status'=>'Pending'])}}" class="btn btn-primary pull-right">
                        Export
                    </a>
                    @endif
                    @if(sizeof($inquiries)!=0 && Input::get('inquiry_filter') == 'Completed')
                    <a href="{{URL::action('InquiryController@exportinquiryBasedOnStatus',['inquiry_status'=>'Completed'])}}" class="btn btn-primary pull-right">
                        Export
                    </a>
                    @endif
                    </div>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div id="flash_message" class="alert no_data_msg_container"></div>
                        @if(sizeof($inquiries) ==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no inquiries have been added.
                        </div>
                        @else
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if (Session::has('flash_success_message'))
                        <div id="flash_error" class="alert alert-success no_data_msg_container">{{ Session::get('flash_success_message') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Tally Name</th>

                                        <th class="text-center">Total Quantity</th>
                                        <th class="text-center">Phone Number</th>
                                        <th class="text-center">Delivery Location</th>

                                        @if((Input::get('inquiry_filter') == 'Pending' || Input::get('inquiry_filter') == ''))
                                        <th class="text-center">Place Order</th>

                                        @endif
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($inquiries->currentPage() - 1) * $inquiries->perPage() + 1;

                                     ?>


                                    @foreach($inquiries as $inquiry)

                                    <tr id="inquiry_row_{{$inquiry['id']}}">
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-center">
                                            {{(isset($inquiry["customer"]->tally_name) && $inquiry["customer"]->tally_name != "")? $inquiry["customer"]->tally_name :(isset($inquiry["customer"]->owner_name) ? $inquiry["customer"]->owner_name:'')}}
                                        </td>

                                        <?php $qty = 0; $alias = 0; ?>
                                        @foreach($inquiry['inquiry_products'] as $prod)
                                        <?php  $alias = $prod['inquiry_product_details']->alias_name;?>
                                        @if(isset($prod['unit']->unit_name))
                                        @if($prod['unit']->unit_name == 'KG')
                                        <?php
                                        $qty += $prod->quantity;
                                        $alias = $prod['inquiry_product_details']->alias_name;
                                        ?>
                                        @endif

                                        @if($prod['unit']->unit_name == 'Pieces')
                                        <?php
                                        $qty += $prod->quantity * $prod['inquiry_product_details']->weight;
                                         $alias = $prod['inquiry_product_details']->alias_name;
                                        ?>
                                        @endif

                                        @if($prod['unit']->unit_name == 'Meter')
                                        <?php
                                        $qty += ($prod->quantity / isset($prod['inquiry_product_details']->standard_length)?$prod['inquiry_product_details']->standard_length:1) * $prod['inquiry_product_details']->weight;
                                         $alias = $prod['inquiry_product_details']->alias_name;
                                        ?>
                                        @endif

                                            @if($prod['unit']->unit_name == 'ft')
                                                <?php
                                                $qty += $prod->quantity * $prod['inquiry_product_details']->weight * $prod->length;
                                                 $alias = $prod['inquiry_product_details']->alias_name;
                                                ?>
                                            @endif

                                            @if($prod['unit']->unit_name == 'mm')
                                                <?php
                                                $qty += $prod->quantity * $prod['inquiry_product_details']->weight * ($prod->length / 305);
                                                 $alias = $prod['inquiry_product_details']->alias_name;
                                                ?>
                                            @endif
                                        @endif
                                        @endforeach

                                        <td class="text-center">{{ round($qty, 2) }}</td>
                                        <td class="text-center">{{$inquiry['customer']['phone_number1']}} </td>
                                        @if($inquiry['delivery_location']['area_name'] !="")
                                        <td class="text-center">{{$inquiry['delivery_location']['area_name']}}</td>
                                        @elseif($inquiry['delivery_location']['area_name'] =="")
                                        <td class="text-center">{{$inquiry['other_location']}}</td>
                                        @endif
                                        @if($inquiry->inquiry_status != 'completed')
                                         @if(Input::get('inquiry_filter') == 'Pending' || Input::get('inquiry_filter') == '')
                                        <td class="text-center">

                                            @if($inquiry->is_approved=='no')
                                            <a href="javascript:void(0)" class="table-link" title="Need Admin Approval">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @else
                                            <a title="Place Order" href="{{ url('place_order/'. $inquiry['id']) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif

                                        </td>
                                         @endif
                                        @endif
                                        <td class="text-center">                                                                    @if($inquiry->is_approved=='no' &&   Auth::user()->role_id == 0 )
                                            <a title="View" href="{{ Url::action('InquiryController@show', ['inquiry' => $inquiry['id'],'way' => 'approval']) }}" class="btn btn-primary btn-sm /*table-link*/">View
<!--                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>-->
                                            </a>
                                            <a title="Approve" class="btn btn-primary btn-sm"  href="{{ Url::action('InquiryController@edit', ['inquiry' => $inquiry['id'],'way' => 'approval']) }}">Approve</a>                                   
                                            <a class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" title="Reject" data-target="#delete_inquiry" onclick="reject_inquiry_row({{$inquiry['id']}})">Reject</a>

                                            @else
                                            <a title="View" href="{{ Url::action('InquiryController@show', ['inquiry' => $inquiry['id']]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if($inquiry->inquiry_status != 'completed')
                                             @if( Auth::user()->role_id <> 2 && Auth::user()->role_id <> 3)
                                            <a title="Edit" href="{{ Url::action('InquiryController@edit', ['inquiry' => $inquiry['id']]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                            @endif


                                            @if( Auth::user()->role_id == 0 )
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_inquiry" onclick="delete_inquiry_row({{$inquiry['id']}})">
                                                <input type="hidden" id="is_gst{{$inquiry->id}}" value="{{$inquiry->is_gst}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif                                                                                   @endif
                                        </td>
                                    </tr>

                                    @endforeach
                                <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Press <b>confirm</b> to send SMS to Customer.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Confirm</button>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="delete_inquiry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <form method="post" action="{{url('inquiry/delete')}}">
                                                <div class="modal-body">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                                    <input class="form-control" name="inquiry_id" id="inquiry_id" type="hidden"/>
                                                    <input class="form-control" name="way" id="way" type="hidden"/>
                                                    <input type="hidden" name="inquiry_sort_type" value="{{(Input::get('inquiry_filter')!="")?Input::get('inquiry_filter'):""}}"/>

                                                    <div class="delete">
                                                        <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" placeholder="" type="password" name="password" id="pwdr" required=""></div>
                                                        </div>
                                                        <div class="checkbox col-md-12">
                                                            <!-- <label style="margin-right:10px;"><input type="checkbox" id="checkwhatsapp" name="send_whatsapp" value="yes" checked><span title="Whatsapp message would be sent to Party" class="checksms smstooltip">Send Whatsapp</span></label> -->
                                                            <label><input type="checkbox" name="send_sms" id="checksms" value="yes" checked><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                        </div>
                                                        <div class="clearfix"></div>
                                                        <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <!--<a class="btn btn-default delete_inquiry_form_submit">Confirm</a>-->
                                                    <input type="submit" class="btn btn-default submit_inquiry_delete" value="Confirm"/>
                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php
                                echo $inquiries->appends(Input::except('page'))->render();
                                ?>
                            </span>
                            <div class="clearfix"></div>
                            @if($inquiries->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('inquiry')}}" id="filter_search">
                                    <input type="hidden" name="inquiry_filter" value="{{(Input::get('inquiry_filter')!="")?Input::get('inquiry_filter'):""}}"/>

                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $inquiries->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
<?php 
$login_count = Session::has('login_count')?Session::get('login_count'):false;
if($login_count == 1){
    Session::forget('login_count');
    Session::put('login_count',2);?>
    history.pushState(null, null, location.href); 
    history.back(); 
    history.forward(); 
    window.onpopstate = function () { history.go(1); }; 
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function () {
        window.history.pushState(null, "", window.location.href);
    };  
<?php } ?>
</script>
@stop
