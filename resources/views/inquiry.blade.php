@extends('layouts.master')
@section('title','Inquiry')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
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
                                        <option value="" selected="">Status</option>
                                        <option <?php if (Input::get('inquiry_filter') == 'Pending') echo 'selected=""'; ?> value="Pending">Pending</option>
                                        <option <?php if (Input::get('inquiry_filter') == 'Completed') echo 'selected=""'; ?> value="Completed">Completed</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">

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
                                        <th class="text-center">Name</th>
                                        <th class="text-center"> Total Quantity</th>
                                        <th class="text-center">Phone Number</th>
                                        <th class="text-center">Delivery Location</th>
                                        <th class="text-center">Place Order</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = ($inquiries->currentPage() - 1) * $inquiries->perPage() + 1; ?>
                                    @foreach($inquiries as $inquiry)
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td class="text-center">{{$inquiry['customer']['owner_name']}}</td>

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
                                        <td class="text-center">
                                            <a title="Place Order" href="{{ url('place_order/'. $inquiry['id']) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a title="View" href="{{ Url::action('InquiryController@show', ['id' => $inquiry['id']]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a title="Edit" href="{{ Url::action('InquiryController@edit', ['id' => $inquiry['id']]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @if( Auth::user()->role_id == 0 )
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_inquiry_{{$inquiry['id']}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
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
                                <div class="modal fade" id="delete_inquiry_{{$inquiry['id']}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                {!! Form::open(array('method'=>'DELETE','url'=>url('inquiry',$inquiry['id']), 'id'=>'delete_inquiry_form'))!!}
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="password" name="password" required=""></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-default" id="yes">Confirm</button>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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