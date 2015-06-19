@extends('layouts.master')
@section('title','Purchase Order Daybook')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active"><span>Purchase Daybook</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left col-md-7">Purchase Daybook</h1>
                    <div class="pull-right top-page-ui col-md-5">
                        <div class="col-md-6 ">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="datepickerDate">
                                </div>
                            </div>
                        </div>
                        <div class="pull-right col-md-5">
                            <button type="button" class="btn btn-primary form_button_footer" onClick="location.href = 'print_purchasedaybook.php'" >Print</button>
                            <button type="button" class="btn btn-primary form_button_footer" >Export</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="selectall">
                        <button type="button" class="btn btn-primary form_button_footer" >Select All</button>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div id="table1" class="row">				
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="cb">#</th>
                                        <th>Date</th>
                                        <th>Serial Number</th>
                                        <th>Party</th>
                                        <th>Truck Number</th>
                                        <th>Deliverd To</th>
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
                                <tbody>                    
                                    <tr>
                                        <td><input type="radio" name="radio-1" id="radio-1" /><span class="cbt">1</span></td>
                                        <td>16 April,2015</td>
                                        <td>PO/Apr15/04/01/01</td>
                                        <td>Party1</td>
                                        <td>MH 14 BS 3022</td>                                        
                                        <td>                                    
                                            Warehouse
                                        </td>
                                        <td>Name1 </td>
                                        <td>Lorem</td>
                                        <td>56</td>
                                        <td>50 </td>
                                        <td>500 </td>
                                        <td></td>
                                        <td>Lorem ipsum</td>
                                        <td>  <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a></td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="radio-1" id="radio-1" /><span class="cbt">2</span></td>
                                        <td>19 April,2015</td>
                                        <td>PO/Apr15/04/01/01</td>
                                        <td>Party2</td>
                                        <td>MH 14 BS 3022</td>                                        
                                        <td>                                    
                                            Customer
                                        </td>
                                        <td>Name1 </td>
                                        <td>ipsum</td>
                                        <td>56</td>
                                        <td>50 </td>
                                        <td>500 </td>
                                        <td>Pune 01</td>
                                        <td>Lorem ipsum</td>
                                        <td>  <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="radio-1" id="radio-1" /><span class="cbt">3</span></td>
                                        <td>21 April,2015</td>
                                        <td>PO/Apr15/04/01/01</td>
                                        <td>Party3</td>
                                        <td>MH 14 BS 3022</td>                                        
                                        <td>                                    
                                            Warehouse
                                        </td>

                                        <td>Name1 </td>

                                        <td>65</td>
                                        <td>lorem</td>
                                        <td>50 </td>
                                        <td>500 </td>
                                        <td></td>
                                        <td>lorem ipsum</td>
                                        <td>  <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" >
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="radio" name="radio-1" id="radio-1" /><span class="cbt">4</span></td>
                                        <td>25 April,2015</td>
                                        <td>PO/Apr15/04/01/01</td>
                                        <td>Party4</td>
                                        <td>MH 14 BS 3022</td>                                        
                                        <td>                                    
                                            Customer
                                        </td>
                                        <td>Name1 </td>

                                        <td>35</td>
                                        <td>Ipsum</td>
                                        <td>50 </td>
                                        <td>500 </td>
                                        <td>Mum 01</td>
                                        <td>lorem ipsum</td>
                                        <td>  <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" >
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
                                                    <div><b>UserID:</b> 9988776655</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" type="text"></div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>delete </b> ?</div>
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
                            <div class="pull-right deletebutton">
                                <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" ><button type="button" class="btn btn-primary form_button_footer" >Delete</button></a>
                            </div>
                            <div class="clearfix"></div>
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