@extends('layouts.master')
@section('title','Purchase Advise')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Purchase Advice</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Purchase Advice</h1>

                    <div class="pull-right top-page-ui">
                        <a href="{{url('purchaseorder_advise/create')}}" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Create Purchase Advice Independently
                        </a>
                        <div class="form-group pull-right">
                            <div class="col-md-12">
                                <select class="form-control" id="user_filter" name="user_filter">
                                    <option value="" selected="">Status</option>
                                    <option value="2">Delivered</option>
                                    <option value="2">Inprocess</option>




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
                                        <th>Date</th>

                                        <th>Serial Number</th>

                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                    <tr>
                                        <td>1</td>
                                        <td>09 Apr 2015</td>

                                        <td>PO/Apr15/04/01</td>                                        

                                        <td class="text-center">

                                            <a href="view_purchaseadvice.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="edit_purchaseadvice.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>


                                            <a href="purchaseorder_challanbutton.php" class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
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
                                    <tr>
                                        <td>2</td>
                                        <td>08 Apr 2015</td>

                                        <td>PO/Apr15/04/01</td>                                        

                                        <td class="text-center">

                                            <a href="view_purchaseadvice.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="edit_purchaseadvice.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>


                                            <a href="purchaseorder_challanbutton.php" class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
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
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>



                                            <div class="modal-body">
                                                <form method="POST" action="" accept-charset="UTF-8" >


                                                    <div class="row print_time "> 
                                                        <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                                                    </div>


                                                    <div class="clearfix"></div>

                                                    <hr>
                                                    <div >
                                                        <button type="button" class="btn btn-primary form_button_footer" >Print</button>

                                                        <a href="purchaseorder_advise.php" class="btn btn-default form_button_footer">Cancel</a>
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