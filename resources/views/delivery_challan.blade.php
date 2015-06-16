
<?php
//echo'<pre>';
//print_r($allorders[0]['order_cancelled']['cancelled_by']);
//echo '</pre>';
//exit;
?>
@extends('layouts.master')
@section('title','Delivery Challan')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Delivery Challan</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Delivery Challan</h1>                                 

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">

                    @if (Session::has('flash_message'))
                    <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                    @endif
                    <div class="main-box-body main_contents clearfix">

                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Party Name</th>
                                        <th class="text-center">Serial Number</th>
                                        <th class="text-center">Present Shipping</th>



<!--     <th class="col-md-2">Amount</th> -->

                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-center">Party1</td>
                                        <td class="text-center">Apr15/04/01/01</td>                                        
                                        <td class="text-center">600</td>
                                    <!--      <td>
                                            <div class="row product-price">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" id="Amount" placeholder="Amount">
                                               
                                            </div>
                                            <div class="form-group col-md-2 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                        </td>-->


                                        <td class="text-center">
                                            <a href="{{url('delivery_challan/1')}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="{{url('delivery_challan/1/edit')}}" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#myModal1">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>

                                        </td>

                                    </tr>
                                    


                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>

                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="text"></div>

                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>


                                                </div>

                                            </div>           
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>    

                                <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>



                                            <div class="modal-body">
                                                <form method="POST" action="" accept-charset="UTF-8" >


                                                    <div class="row print_time"> 
                                                        <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                    </div>
                                                    <div class="checkbox">
                                                        <label><input type="checkbox" value="" checked=""><span title="SMS would be sent to Party" class="checksms smstooltip">Send SMS</span></label>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <hr>
                                                    <div >
                                                        <button type="button" class="btn btn-primary form_button_footer" >Generate Challan</button>
                                                        <!--<button type="button" class="btn btn-primary form_button_footer" >Send Message</button>-->
                                                        <a href="delivery_orders_challan.php" class="btn btn-default form_button_footer">Cancel</a>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                </form>


                                            </div>           
                                            <!--    <div class="modal-footer">
                                                
                                                <button type="button" class="btn btn-primary">No</button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                                </div>-->
                                        </div>
                                    </div>
                                </div> 

                                </tbody>
                            </table>

                            <span class="pull-right">
                                <ul class="pagination pull-right">
                                    <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                                </ul>

                            </span>

                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop