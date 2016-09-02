@extends('layouts.master')
@section('title','Purchase Order Daybook')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Purchase Daybook</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left col-md-6">Purchase Daybook</h1>
                    <div class="pull-right top-page-ui col-md-6">
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <form action="{{URL::action('PurchaseDaybookController@index')}}" method="GET" >
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control delivery_challan_date" name="date" placeholder="Search by date" id="sales_daybook_date" <?php
                                            if (Input::get('date') != "") {
                                                echo "value='" . Input::get('date') . "'";
                                            }
                                            ?>>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-primary form_button_footer"><i class="fa fa-search"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a class="btn btn-primary form_button_footer print_purchase_daybook" > Print </a>
                            <a href="{{url('export_purchasedaybook')}}" class="btn btn-primary form_button_footer" > Export </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <br/>
            <div id="table1" class="row">
                <div class="col-lg-12">
                    <div class="main-box clearfix">
                        <div class="main-box-body main_contents clearfix">

                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif

                            @if(sizeof($purchase_daybook) != 0)
                            <div class="table-responsive">
                                <form action="{{url('delete_all_daybook')}}" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <table id="table-example" class="table table-hover">
                                        <thead>
                                            <?php $i = 1; ?>
                                            <tr>
                                                <th class="cb">
                                                    @if(Auth::user()->role_id == 0)
                                        <div class="checkbox">
                                            <label style="font-weight: bold;">
                                                <input onclick="select_all_checkbox();" all_checked="allunchecked" type="checkbox" id="select_all_button" value="" >
                                                Select All to Delete
                                            </label>
                                        </div>
                                        @else
                                        #
                                        @endif
                                        </th>

                                        <th> Date </th>
                                        <th>Serial Number</th>
                                        <th>Tally Name</th>
                                        <th>Truck Number</th>
                                        <th>Deliverd To</th>
                                        <th>Order By </th>
                                        <th>Loaded By </th>
                                        <th>Labors </th>
                                        <th>Actual Quantity</th>
                                        <th>Amount </th>
                                        <th>Bill Number</th>
                                        <th>Remarks </th>
                                        @if(Auth::user()->role_id == 0)
                                        <th>Action </th>
                                        @endif
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($purchase_daybook as $daybook)
                                            <?php
                                            $total_qty = 0;
                                            $total_amount = 0;
                                            $total_qunatity = 0;
                                            ?>
                                            @foreach($daybook['all_purchase_products'] as $total)
                                            <?php
                                            if ($total->unit_id == 1) {
                                                $total_qunatity += $total->present_shipping;
                                            }
                                            if ($total->unit_id == 2) {
                                                $total_qunatity += ($total->present_shipping * $total['purchase_product_details']->weight);
                                            }
                                            if ($total->unit_id == 3) {
                                                $total_qunatity += ($total->present_shipping / $total['purchase_product_details']->standard_length ) * $total['purchase_product_details']->weight;
                                            }
                                            ?>
                                            @endforeach
                                            <tr>
                                                <td>
                                                    @if(Auth::user()->role_id == 0)
                                                    <input type="checkbox" name="daybook[]" id="daybook[]" value="{{ $daybook->id }}" />
                                                    @endif
                                                    <span class="cbt">{{ $i++ }}</span>
                                                </td>

                                                <td>{{ date("m-d-Y", strtotime($daybook->updated_at)) }}</td>
                                                <td>{{ $daybook->serial_number }}</td>
                                                <td>
                                                    @if($daybook['supplier']->tally_name != "")
                                                    {{ $daybook['supplier']->tally_name }}
                                                    @else
                                                    {{"Advance Sales"}}
                                                    @endif
                                                </td>
                                                <td>{{ $daybook->vehicle_number }}</td>
                                                <td>{{ $daybook['supplier']->owner_name }}</td>
                                                <td>{{ $daybook['orderedby']->first_name }} </td>
                                                <td>{{ $daybook->unloaded_by }} </td>
                                                <td>{{ $daybook->labours }}</td>
                                                <td>{{ round($daybook['all_purchase_products']->sum('quantity'), 2) }}</td>
                                                <td>{{ $daybook->grand_total}}</td>
                                                <td>{{ $daybook->bill_number }}</td>
                                                <td>
                                                    @if((strlen(trim($daybook->remarks))) > 50)
                                                    {{ substr(trim($daybook->remarks),0,50)}} ..
                                                    @else
                                                    {{trim($daybook->remarks)}}
                                                    @endif
                                                </td>
                                                @if(Auth::user()->role_id == 0)
                                                <td>  
                                                    <a href="#" class="table-link danger delete-purchase-order-daybook" data-toggle="modal" data-target="#delete-purchase-order-daybook" data-url='{{url('purchase_order_daybook',$daybook->id)}}'>
                                                        <span class="fa-stack">
                                                            <i class="fa fa-square fa-stack-2x"></i>
                                                            <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                        </span>
                                                    </a>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @if(Auth::user()->role_id == 0)
                                    <div class="pull-right deletebutton">
                                        <a href="#" class="table-link danger" data-toggle="modal" data-target="#del_all_model" >
                                            <button type="button" class="btn btn-primary form_button_footer" >Delete All</button>
                                        </a>
                                    </div>
                                    @endif
                                    <div class="modal fade" id="del_all_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="modal-body">
                                                        <div class="delete">
                                                            <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                            <div class="pwd">
                                                                <div class="pwdl"><b>Password:</b></div>
                                                                <div class="pwdr"><input class="form-control" type="password" name="delete_all_password" id="delete_all_password"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-default" id="btnmodel">Yes</button>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="clearfix"></div>
                                <span class="pull-right">
                                    <ul class="pagination pull-right">
                                        <?php echo $purchase_daybook->render(); ?>
                                    </ul>
                                </span>
                                <div class="clearfix"></div>
                                @if($purchase_daybook->lastPage() > 1)
                                <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                    <form class="form-inline" method="GET" action="{{url('purchase_order_daybook')}}" id="filter_search">
                                        <div class="form-group">
                                            <label for="exampleInputName2"><b>Go To</b></label>
                                            &nbsp;
                                            <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                            &nbsp;
                                            <label for="exampleInputName2"><b>of {{ $purchase_daybook->lastPage()}} </b></label>
                                            <a onclick="this.form.submit()"></a>
                                        </div>
                                    </form>
                                </span>
                                @endif
                            </div>
                            @else
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <strong> No purchase day book records found</strong>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
                                          <div class="modal fade" id="delete-purchase-order-daybook" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                        <h4 class="modal-title" id="myModalLabel"></h4>
                                                    </div>
                                                    {!! Form::open(array('method'=>'POST', 'id'=>'delete_purchase_daybook_form'))!!}
                                                    <div class="modal-body">
                                                        <div class="delete">
                                                            <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                            <div class="pwd">
                                                                <div class="pwdl"><b>Password:</b></div>
                                                                <div class="pwdr"><input class="form-control" placeholder="" type="password" name="password"></div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-default" >Yes</button>
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
    </div>
    @stop