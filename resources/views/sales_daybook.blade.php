
<?php
//echo'<pre>';
//print_r($allorders);
//echo '</pre>';
//exit;
?>
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

                                    <form action="{{url('sales_daybook/date')}}" method="POST">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="col-sm-6 ">
                                            <input type="text" class="form-control delivery_challan_date" name="challan_date" id="sales_daybook_date" value="{{$challan_date}}">
                                        </div>
                                        <div class="col-sm-6 ">
                                            <input type="submit" class="btn btn-primary form_button_footer" value="Search">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="pull-right col-md-4">

                            <button type="button" class="btn btn-primary form_button_footer" onClick="location.href = 'print_salesdaybook.php'" >Print</button>
                            <button type="button" class="btn btn-primary form_button_footer" >Export</button>
                        </div>
                    </div>

                </div>
                <div class="clearfix"></div>
                <div class="col-md-3">
                    <div class="selectall">
                        <button type="button" class="btn btn-primary form_button_footer" id="select_all_button" onclick="select_all_checkbox();" all_checked="allunchecked">Select All</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
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
                    <div class="main-box-body main_contents clearfix">

                        <div class="table-responsive">
                            <form action="{{url('delete_multiple_challan')}}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <table id="add_product_table_delivery_challan" class="table table-hover">
                                    <thead>
                                        <tr>

                                            <th class="text-center">#</th>
                                            <th>Date</th>
                                            <th>Serial</th>
                                            <th>Party</th>
                                            <th>Truck Number</th>
                                            <th>Fullfilled By</th>
                                            <th>Order By </th> 
                                            <th>Loaded By </th>
                                            <th>Labors </th>
                                            <th>Actual Quantity</th>
                                            <th>Amount </th>
                                            <th>Bill Number</th> 
                                            <th>Remarks </th> 
                                            <th>Action </th>

                                        </tr>
                                    </thead>
                                    <tbody id="challan_data" >    

                                        <?php $k = 0; ?>
                                        @foreach($allorders as $challan)
                                        

                                        <tr class="add_product_row">
                                            <td ><input type="checkbox" id ="checkbox_{{$k}}" name="challan_id[{{$k}}][checkbox]" value="{{$challan->id}}" ></td>
                                            <td >{{$challan["customer"]->owner_name}}</td>
                                            <td >
                                                @if($challan->serial_number == '')
                                                --
                                                @elseif($challan->serial_number != '')
                                                {{$challan->serial_number}}
                                                @endif
                                            </td> 
                                            <td >{{$challan["customer"]->owner_name}}</td>
                                            <td >{{$challan["delivery_order"]->vehicle_number}}</td>
                                            <td >{{$challan["delivery_order"]->order_source}}</td>
                                            <td >{{$challan['user'][0]->first_name}}</td>
                                            <td>{{$challan->loaded_by}}</td>
                                            <td >{{$challan->labours}}</td>
                                            <td><?php
                                                $total_qunatity = 0;
                                                foreach ($challan["all_order_products"] as $products) {
                                                    $total_qunatity = $total_qunatity + $products["actual_pieces"];
                                                }
                                                echo $total_qunatity;
                                                ?>
                                            </td>
                                            <td >{{$challan->grand_total}}</td>
                                            <td >{{$challan->bill_number}}</td>
                                            <td>{{$challan->remarks}}</td>




                                            <td>

                                                <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_{{$challan->id}}" title="delete">
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>

                                            </td>
                                            <?php $k++; ?>
                                        </tr>
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


                                    
                                    @endforeach

                                    </tbody>


                                </table>
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
                                    <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_selected" ><button type="button" class="btn btn-primary form_button_footer" >Delete</button></a>
                                </div>
                            </form>
                            <div class="clearfix"></div>
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
