@extends('layouts.master')
@section('title','Sales Daybook')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Sales Daybook</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Sales Daybook</h1>
                    <div class="pull-right top-page-ui col-md-7">
                        <div class="col-md-8 ">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <form action="" method="GET">
                                        <div class="col-sm-6 ">
                                            <input type="text" class="form-control delivery_challan_date" name="challan_date" id="sales_daybook_date" placeholder="Search by date" value="{{Request::get('challan_date')}}">
                                        </div>
                                        <div class="col-sm-6 ">
                                            <input type="submit" class="btn btn-primary form_button_footer" value="Search">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if(sizeof($allorders) > 0)
                        <div class="pull-right col-md-4">
                            <a class="btn btn-primary form_button_footer print_sales_order_daybook" >Print</a>
                            <a href="{{url('export_sales_daybook')}}" class="btn btn-primary form_button_footer" >Export</a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
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
                        @if (Session::has('error'))
                        <div id="flash_error" class="alert alert-danger no_data_msg_container">{{ Session::get('error') }}</div>
                        @endif

                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                        <div class="table-responsive">
                            <form action="{{url('delete_multiple_challan')}}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <table id="add_product_table_delivery_challan" class="table table-hover">
                                    <thead>
                                        <tr>
                                            @if( Auth::user()->role_id == 0 )
                                            <th><input type="checkbox" class="table-link" id ="select_all_button" onclick="select_all_checkbox();" all_checked="allunchecked" ></th>
                                            <th>#</th>
                                            @endif
                                            @if( Auth::user()->role_id == 1 )
                                            <th>#</th>
                                            @endif
                                            <th>Date</th>
                                            <th>Serial</th>
                                            <th>Tally Name</th>
                                            <th>Truck Number</th>
                                            <th>Fullfilled By</th>
                                            <th>Order By </th>
                                            <th>Loaded By </th>
                                            <th>Labors </th>
                                            <th>Actual Quantity</th>
                                            <th>Amount</th>
                                            <th>Bill Number</th>
                                            <th>Remarks </th>
                                            @if( Auth::user()->role_id == 0)
                                            <th>Action </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="challan_data" >
                                        <?php
                                        $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1;
                                        ?>
                                        @foreach($allorders as $challan)
                                        <tr class="add_product_row">
                                            @if( Auth::user()->role_id == 0 )
                                            <td><input type="checkbox" id ="checkbox_{{$k}}" name="challan_id[{{$k}}][checkbox]" value="{{$challan->id}}" > </td>
                                            <td>{{$k++}}</td>
                                            @endif
                                            @if( Auth::user()->role_id == 1)
                                            <th>{{$k}}</th>
                                            @endif
                                            <td >{{ date('m-d-Y',strtotime($challan['updated_at']))}}</td>
                                            <td >
                                                @if($challan->serial_number == '')
                                                --
                                                @else
                                                {{$challan->serial_number}}
                                                @endif
                                            </td>
                                            <td >
                                                @if($challan["customer"]->tally_name != "")
                                                {{$challan["customer"]->tally_name}}
                                                @else
                                                @if(isset($challan["customer"]->owner_name))
                                                {{"Advance Sales"}}
                                                @endif
                                                @endif
                                            </td>
                                            <td>{{isset($challan["delivery_order"])?$challan["delivery_order"]->vehicle_number: ''}}</td>
                                            <td>
                                                @if(isset($challan["delivery_order"]->supplier_id) && $challan["delivery_order"]->supplier_id != 0)
                                                @foreach($supplier as $sup)
                                                @if($sup->id == $challan["delivery_order"]->supplier_id)
                                                {{$sup->tally_name}}
                                                @endif
                                                @endforeach
                                                @else
                                                {{$challan["delivery_order"]->order_source}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($challan['user'][0]->first_name))
                                                {{$challan['user'][0]->first_name}}
                                                @endif
                                            </td>
                                            <td>{{$challan->loaded_by}}</td>
                                            <td>{{$challan->labours}}</td>
                                            <td>
                                                <?php
                                                $total_qunatity = 0;
                                                foreach ($challan["delivery_challan_products"] as $products) {
                                                    if ($products['unit']->id == 1) {
                                                        $total_qunatity += $products->quantity;
                                                    }
                                                    if ($products['unit']->id == 2) {
                                                        $total_qunatity +=($products->quantity * $products['order_product_details']->weight);
                                                    }
                                                    if ($products['unit']->id == 3) {
                                                        $total_qunatity +=(($products->quantity / $products['order_product_details']->standard_length ) * $products['order_product_details']->weight);
                                                    }
                                                }
                                                echo round($challan['delivery_challan_products']->sum('actual_quantity'), 2);
                                                ?>
                                            </td>
                                            <td >{{round($challan->grand_price, 2)}}</td>
                                            <td >{{$challan->bill_number}}</td>
                                            <td>
                                                @if((strlen(trim($challan->remarks))) > 50)
                                                {{ substr(trim($challan->remarks),0,50)}} ..
                                                @else
                                                {{trim($challan->remarks)}}
                                                @endif
                                            </td>
                                            @if( Auth::user()->role_id == 0)
                                            <td>
                                                <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_{{$challan->id}}" title="delete">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @if( Auth::user()->role_id == 0  )
                                    <div class="modal fade" id="delete_challan_{{$challan->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                                </div>
                                                {!! Form::open(array("method"=>"POST","url"=>url("delete_sales_daybook",$challan->id), "id"=>"delete_sales_daybook_form"))!!}
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
                                                    <button type="button" class="btn btn-default" id="yes" onclick="this.form.submit();">Yes</button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                @if( Auth::user()->role_id == 0  )
                                <div class="modal fade" id="delete_challan_selected" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
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
                                                <button type="submit" class="btn btn-default" id="delete_selected_chllan_button">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right deletebutton">
                                    <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_selected" ><button type="button" class="btn btn-primary form_button_footer" >Delete All</button></a>
                                </div>
                                @endif
                            </form>
                            <div class="clearfix"></div>
                            <span class="pull-right">
                                @if(isset($_GET['challan_date']) && Request::get('challan_date') != '')
                                <?php echo $allorders->appends(array('challan_date' => Request::get('challan_date')))->render(); ?>
                                @else
                                <?php echo $allorders->render(); ?>
                                @endif
                            </span>
                            <div class="clearfix"></div>
                            @if($allorders->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('sales_daybook')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $allorders->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop