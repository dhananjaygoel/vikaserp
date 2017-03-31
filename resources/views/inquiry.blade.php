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
            <h1 class="pull-left">Inquiry Pending Approval</h1>
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
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="main-box clearfix">
            <div class="main-box-body main_contents clearfix">
                <div class="table-responsive">
                    <table id="table-example" class="table table-hover data-table-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tally Name</th>
                                <th>Total Quantity</th>
                                <th>Phone Number</th>
                                <th>Delivery Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rakshit</td>
                                <td>15</td>
                                <td>8866130903</td>
                                <td>32 Shirala</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="javascript:;">Approve</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" data-target="#reject-inquiry-popup">Reject</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Bhushan</td>
                                <td>8</td>
                                <td>8985745658</td>
                                <td>Mumbai</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="javascript:;">Approve</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" data-target="#reject-inquiry-popup">Reject</a>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Nikhil</td>
                                <td>18</td>
                                <td>9235854578</td>
                                <td>Nasik</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="javascript:;">Approve</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" data-target="#reject-inquiry-popup">Reject</a>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Abhinav</td>
                                <td>12</td>
                                <td>7236985415</td>
                                <td>Thane</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="javascript:;">Approve</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" data-target="#reject-inquiry-popup">Reject</a>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Akhilesh</td>
                                <td>76</td>
                                <td>9595756545</td>
                                <td>Pune</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="javascript:;">Approve</a>
                                    <a class="btn btn-danger btn-sm" href="javascript:;" data-toggle="modal" data-target="#reject-inquiry-popup">Reject</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Inquiry Modal Start -->
<div class="modal fade" id="reject-inquiry-popup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Reject Inquiry</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb0">
                <p>Are you sure you want to reject the Inquiry?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>
<!-- Approve Inquiry Modal End -->

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
                                        @if(Input::get('inquiry_filter') == 'Pending' || Input::get('inquiry_filter') == '')
                                        <th class="text-center">Place Order</th>
                                        @endif
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($inquiries->currentPage() - 1) * $inquiries->perPage() + 1; ?>
                                    @foreach($inquiries as $inquiry)
                                    <tr id="inquiry_row_{{$inquiry['id']}}">
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-center">
                                            {{(isset($inquiry["customer"]->tally_name) && $inquiry["customer"]->tally_name != "")? $inquiry["customer"]->tally_name :(isset($inquiry["customer"]->owner_name) ? $inquiry["customer"]->owner_name:'')}}
                                        </td>

                                        <?php $qty = 0; ?>
                                        @foreach($inquiry['inquiry_products'] as $prod)
                                        @if($prod['unit']->unit_name == 'KG')
                                        <?php
                                        $qty += $prod->quantity;
                                        ?>
                                        @endif

                                        @if($prod['unit']->unit_name == 'Pieces')
                                        <?php
                                        $qty += $prod->quantity * $prod['inquiry_product_details']->weight;
                                        ?>
                                        @endif

                                        @if($prod['unit']->unit_name == 'Meter')
                                        <?php
                                        $qty += ($prod->quantity / $prod['inquiry_product_details']->standard_length) * $prod['inquiry_product_details']->weight;
                                        ?>
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
                                        <td class="text-center">

                                            <a title="Place Order" href="{{ url('place_order/'. $inquiry['id']) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>                                          
                                        </td>
                                        @endif
                                        <td class="text-center">
                                            <a title="View" href="{{ Url::action('InquiryController@show', ['id' => $inquiry['id']]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if($inquiry->inquiry_status != 'completed')
                                            <a title="Edit" href="{{ Url::action('InquiryController@edit', ['id' => $inquiry['id']]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>                                            
                                            @endif


                                            @if( Auth::user()->role_id == 0 )
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_inquiry" onclick="delete_inquiry_row({{$inquiry['id']}})">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
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
                                                    <div class="delete">
                                                        <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                        <div class="pwd">
                                                            <div class="pwdl"><b>Password:</b></div>
                                                            <div class="pwdr"><input class="form-control" placeholder="" type="password" name="password" id="pwdr" required=""></div>
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
                                <?php echo $inquiries->render(); ?>
                            </span>
                            <div class="clearfix"></div>
                            @if($inquiries->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('inquiry')}}" id="filter_search">
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
@stop