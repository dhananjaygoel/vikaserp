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
                                <select class="form-control" id="user_filter" name="user_filter">
                                    <option value="" selected="">Status</option>
                                    <option value="2">Pending</option>
                                    <option value="2">Completed</option>
                                    <option value="2">Canceled</option>
                                </select>
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
                                        <td class="text-center">{{$i}}</td>
                                        <td class="text-center">{{$inquiry['customer']->owner_name}}</td>
                                        <td class="text-center">100</td>
                                        <td class="text-center">{{$inquiry['customer']->phone_number}} </td>
                                        <td class="text-center">{{$inquiry['delivery_location']['area_name']}}</td>
                                        <td class="text-center">
                                            <a title="Place Order" href="add_order.php" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a title="View" href="{{ Url::action('InquiryController@show', ['id' => $inquiry->id]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a title="Edit" href="{{ Url::action('InquiryController@edit', ['id' => $inquiry->id]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#delete_inquiry_{{$inquiry->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
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
                                <div class="modal fade" id="delete_inquiry_{{$inquiry->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> 9988776655</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="text"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Confirm</button>
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php $i++; ?>
                                @endforeach
                                </tbody>
                            </table>

                            <span class="pull-right">
                                <?php echo $inquiries->render(); ?>
                            </span>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop