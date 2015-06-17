@extends('layouts.master')
@section('title','Pending Delivery Order Report')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Pending Delivery Order Report</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Pending Delivery Order Report</h1>
                    <div class="pull-right top-page-ui">     

                        <div class="col-md-12">
                            <div class="form-group  pull-right">
                                <div class="col-md-12">
                                    <select class="form-control" id="user_filter" name="user_filter">
                                        <option value="" selected="">Status</option>
                                        <option value="2">Pending</option>
                                        <option value="2">Canceled</option>



                                    </select>
                                </div>
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
                                        <th><a href="#" class="desc"><span>Date</span></a></th>
                                        <th><a href="#" class="asc"><span>Serial</span></a></th>
                                        <th><a href="#" class="desc"><span>Party</span></a></th>
                                        <th><a href="#" class="desc"><span>Truck Number</span></a></th>

                                        <th><a href="#" class="desc"><span>Order By</span></a> </th> 

                                        <th class="col-md-2">Remarks </th> 


                                    </tr>
                                </thead>
                                <tbody>                    



                                    <tr>
                                        <td>1</td>
                                        <td>30 April 2015</td>
                                        <td>Apr15/04/01</td>
                                        <td>Party Name 1</td>
                                        <td>MH 14 BS 3022</td>                                        

                                        <td>Name 1 </td>

                                        <td></td>


                                    </tr>



                                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>



                                            <div class="modal-body">
                                                <p>Are you sure you want to cancel order</p>


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

                                                    <div class="form-group">
                                                        <label for="vehicle_name">Vehicle Name</label>
                                                        <input id="vehicle_name" class="form-control" placeholder="Vehicle Name" name="vehicle_name" value="" type="text">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="driver_name">Driver Name</label>
                                                        <input id="driver_name" class="form-control" placeholder="Driver Name " name="driver_name" value="" type="text">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="driver_contact">Driver Contact</label>
                                                        <input id="driver_contact" class="form-control" placeholder="Driver Contact" name="driver_contact" value="" type="text">
                                                    </div>


                                                    <hr>
                                                    <div >
                                                        <button type="button" class="btn btn-primary form_button_footer" >Print</button>

                                                        <a href="orders.php" class="btn btn-default form_button_footer">Cancel</a>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                </form>


                                            </div>           

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