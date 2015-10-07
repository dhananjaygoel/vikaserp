@extends('layouts.master')
@section('title','Pending Purchase Advice Report')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Pending Purchase Advice Report</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Pending Purchase Advice Report</h1>
                    <!--                    <div class="form-group pull-right">
                                            <form method="GET" id="purchaseaAdviseFilterForm">
                                                <div class="col-md-12">
                                                    <select class="form-control" id="purchaseaAdviseFilter" name="purchaseaAdviseFilter">
                                                        <option value="" selected="">Status</option>
                                                        <option value="delivered" <?php
                    if (Request::get('purchaseaAdviseFilter') == "delivered") {
                        echo "selected=selected";
                    }
                    ?>>Delivered</option>
                                                        <option value="in_process" <?php
                    if (Request::get('purchaseaAdviseFilter') == "in_process") {
                        echo "selected=selected";
                    }
                    ?>>Inprocess</option>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>-->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(count($pending_advise) > 0)
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><a href="javascript:void(0);" class="desc pendingpadvice" data-column="purchase_advice_date"><span>Date</span></a></th>
                                        <th><a href="javascript:void(0);" class="desc  pendingpadvice" data-column="serial_number"><span>Serial</span></a></th>
                                        <th><a href="javascript:void(0);" class="hidesorticon" data-column="party"><span>Party</span></a></th>
                                        <th><a href="javascript:void(0);" class="hidesorticon" data-column="tot_qty"><span>Total Quantity</span></a></th>
                                        <th><a href="javascript:void(0);" class="hidesorticon" data-column="vehicle_number"><span>Truck Number</span></a></th>
                                        <th><a href="javascript:void(0);" class="hidesorticon" data-column="owner_name"><span>Order By</span></a> </th>
                                        <th class="col-md-2">Remarks </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = ($pending_advise->currentPage() - 1) * $pending_advise->perPage() + 1;
                                    ?>
                                    @foreach($pending_advise as $key=>$pa)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{date("m-d-Y", strtotime($pa->purchase_advice_date))}}</td>
                                        <td>{{$pa->serial_number}}</td>
                                        <td>{{isset($pa['party']->tally_name)?$pa['party']->tally_name:''}}</td>
                                        <td>{{$pa['purchase_products']->sum('quantity')}}</td>
                                        <td>{{$pa->vehicle_number}}</td>
                                        <td>{{$pa['supplier']->owner_name}}</td>
                                        <td>{{$pa->remarks}}</td>
                                    </tr>
                                    <?php $i++; ?>
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $pending_advise->render(); ?>
                            </span>
                            @if($pending_advise->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('pending_purchase_advice')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $pending_advise->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                        <?php
                        $sort_column = "";
                        $sort_column_by = "";
                        $sort_column = Input::get('filteron');
                        $sort_column_by = Input::get('filterby');
                        ?>
                        <input type="hidden" name="base_url" id="base_url" value="{{URL::to('/')}}">
                        <input type="hidden" name="pending_advice_sortfield" id="pending_advice_sortfield" value="{{($sort_column!="")?$sort_column:''}}">
                        <input type="hidden" name="pending_advice_sortfieldby" id="pending_advice_sortfieldby" value="{{($sort_column_by!="")?$sort_column_by:''}}">
                        <a id="redirect_url_for_sorting" href=''></a>
                        @else
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <strong> No pending purchase advice found </strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop