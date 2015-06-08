<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Orders - Vikas Associate Order Automation System</title>
	
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
	
	<!-- RTL support - for demo only -->
	<script src="js/demo-rtl.js"></script>
	<!-- 
	If you need RTL support just include here RTL CSS file <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
	And add "rtl" class to <body> element - e.g. <body class="rtl"> 
	-->
	
	<!-- libraries -->
	<link rel="stylesheet" type="text/css" href="css/libs/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="css/libs/nanoscroller.css" />

	<!-- global styles -->
	<link rel="stylesheet" type="text/css" href="css/compiled/theme_styles.css" />

	<!-- this page specific styles -->
	
	<!-- Favicon -->
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon"/>

	<!-- google font libraries -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="theme-wrapper">
	<?php include("include/header.php"); ?>
		<div id="page-wrapper" class="container">
			<div class="row">
				<?php include("include/sidebarleft.php"); ?>
				<div id="content-wrapper"><div class="row">
						<div class="col-lg-12">
							
							<div class="row">
								<div class="col-lg-12">
									<ol class="breadcrumb">
										<li><a href="#">Home</a></li>
										<li class="active"><span>Orders</span></li>
									</ol>
									
									<div class="filter-block">
                                                                            <h1 class="pull-left">Orders</h1>                                 
                                                                                  
                                                                                  <div class="pull-right top-page-ui">
											<a href="add_order.php" class="btn btn-primary pull-right">
												<i class="fa fa-plus-circle fa-lg"></i> Place Order
											</a>
                                                                                       <div class="form-group pull-right">
                                                                                        <div class="col-md-12">
                                                                                        <select class="form-control" id="user_filter3" name="user_filter">
                                                                                    <option value="" selected="">Status</option>
                                                                                    <option value="1">Pending</option>
                                                                                    <option value="2">Completed</option>
                                                                                    <option value="3">Canceled</option>
                                                                                    
                                                                                     
                                                                                    
                                                                                                                
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
                        
                        <div class="table-responsive tablepending">
                                                        <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Mobile </th> 
                                        <th>Delivery Location</th>                                                            
                                        <th>Order By</th>
                                        <th>Total Quantity</th>
                                        <th>Pending Quantity</th>                                                          
                                        
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td>1</td>
                                        <td>Name 1</td>
                                        <td>9999999999 </td>
                                        <td>Pune</td>
                                        <td>Lorem Ipsum</td>
                                        <td>100</td>                                        
                                        <td>50</td>
                                        
                                        <td class="text-center">
                                           
                                            <a href="order_view.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="edit_orders.php" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                           
                                            <a href="#" class="table-link" title="manual complete" data-toggle="modal" data-target="#myModal1">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                             <a href="createdelivery_order.php" class="table-link" title="Create Delivery order">
                                                <span class="fa-stack">
                                                          <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                             <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                        </td>
                                        
                                    </tr>
                                                                        <tr>
                                        <td>2</td>
                                        <td>Name 2</td>
                                        <td>9988776655 </td>
                                        <td>Pune</td>
                                        <td>Lorem Ipsum</td>
                                        <td>100</td>                                        
                                        <td>50</td>
                                        <td class="text-center">
                                              <a href="order_view.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="edit_orders.php" class="table-link" title="Edit">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                          
                                            <a href="#" class="table-link" title="manual complete" data-toggle="modal" data-target="#myModal1">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                                <a href="createdelivery_order.php" class="table-link" title="Create Delivery order">
                                                <span class="fa-stack">
                                                          <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                              <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal">
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
                                         <p>
                                             
                          
                                          Are you sure to complete the Order?
                                         <div class="radio">
                                               <input  value="" id="overprice" name="overprice" type="radio">
                                               <label for="overprice">Over Pricing</label>
                                           
                                           </div>
                                           <div class="radio">
                                               <input  value="" id="delivery" name="delivery" type="radio">
                                               <label for="delivery">Late Delivery</label>
                                           
                                           </div>
                                          <div class="radio">
                                               <input  value="" id="quality" name="quality" type="radio">
                                               <label for="quality">Undesired Quality</label>
                                           
                                           </div>
                                          <div class="form-group">
                                              <label for="reason"><b>Reason</b></label>
                                              <textarea class="form-control" id="inquiry_remark" name="reason"  rows="2" placeholder="Reason"></textarea>
                                        </div>
                                         </p>
                                        
                                         
                                    </div>           
                                    <div class="modal-footer">
                                    
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
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
                        <div class="table-responsive tablecompleted">
                                                        <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                     
                                        <th>Total Quantity</th>
                                        <th>Mobile </th>                                                            
                                        <th>Delivery Location</th>                                                            
                                        <th>Order By</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td>1</td>
                                        <td>Name 1</td>
                                      
                                        <td>100</td>                                        
                                        <td>9999999999 </td>
                                        <td>Pune</td>
                                        <td>Lorem Ipsum</td>
                                        <td class="text-center">
                                           
                                            <a href="order_view.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                          
                                           
                                            
                                             
                                             <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                        </td>
                                        
                                    </tr>
                                                                        <tr>
                                        <td>2</td>
                                        <td>Name 2</td>
                                   
                                        <td>500</td>                                        
                                        <td>9999999999 </td>
                                        <td>Mumbai</td>
                                        <td>Lorem </td>
                                        <td class="text-center">
                                              <a href="order_view.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                           
                                          
                                              <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal">
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
                                         <p>
                                             
                          
                                          Are you sure to complete the Order?
                                         <div class="radio">
                                               <input  value="" id="overprice" name="overprice" type="radio">
                                               <label for="overprice">Over Pricing</label>
                                           
                                           </div>
                                           <div class="radio">
                                               <input  value="" id="delivery" name="delivery" type="radio">
                                               <label for="delivery">Late Delivery</label>
                                           
                                           </div>
                                          <div class="radio">
                                               <input  value="" id="quality" name="quality" type="radio">
                                               <label for="quality">Undesired Quality</label>
                                           
                                           </div>
                                          <div class="form-group">
                                              <label for="reason"><b>Reason</b></label>
                                              <textarea class="form-control" id="inquiry_remark" name="reason"  rows="2" placeholder="Reason"></textarea>
                                        </div>
                                         </p>
                                        
                                         
                                    </div>           
                                    <div class="modal-footer">
                                    
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
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
                        <div class="table-responsive tablecancel">
                                                        <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                     
                                        <th>Total Quantity</th>
                                        <th>Mobile </th>    
                                        
                                        <th>Delivery Location</th>                                                            
                                        <th>Order By</th>
                                        <th>Cancel By</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td>1</td>
                                        <td>Name 1</td>
                                      
                                        <td>100</td>                                        
                                        <td>9999999999 </td>
                                        <td>Pune</td>
                                        <td>Lorem </td>
                                        <td>Admin </td>
                                        <td class="text-center">
                                           
                                            <a href="order_view.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                           
                                           
                                             <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                        </td>
                                        
                                    </tr>
                                                                        <tr>
                                        <td>2</td>
                                        <td>Name 2</td>
                                   
                                        <td>500</td>                                        
                                        <td>9999999999 </td>
                                        <td>Mumbai</td>
                                        <td>Ipsum </td>
                                         <td>Admin </td>
                                        <td class="text-center">
                                              <a href="order_view.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                          
                                          
                                              <a href="#" class="table-link danger" title="delete" data-toggle="modal" data-target="#myModal">
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
                                         <p>
                                             
                          
                                          Are you sure to complete the Order?
                                         <div class="radio">
                                               <input  value="" id="overprice" name="overprice" type="radio">
                                               <label for="overprice">Over Pricing</label>
                                           
                                           </div>
                                           <div class="radio">
                                               <input  value="" id="delivery" name="delivery" type="radio">
                                               <label for="delivery">Late Delivery</label>
                                           
                                           </div>
                                          <div class="radio">
                                               <input  value="" id="quality" name="quality" type="radio">
                                               <label for="quality">Undesired Quality</label>
                                           
                                           </div>
                                          <div class="form-group">
                                              <label for="reason"><b>Reason</b></label>
                                              <textarea class="form-control" id="inquiry_remark" name="reason"  rows="2" placeholder="Reason"></textarea>
                                        </div>
                                         </p>
                                        
                                         
                                    </div>           
                                    <div class="modal-footer">
                                    
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
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
					
					<?php include("include/footer.php"); ?>
				</div>
			</div>
		</div>
	</div>
	
	

	<!-- global scripts -->
	<script src="js/demo-skin-changer.js"></script> <!-- only for demo -->
	
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.nanoscroller.min.js"></script>
	
	<script src="js/demo.js"></script> <!-- only for demo -->
	
	<!-- this page specific scripts -->
	
	<!-- theme scripts -->
	<script src="js/scripts.js"></script>
	<script src="js/pace.min.js"></script>
        <script>
                    $('input[type=radio]').click(function(){
            if (this.previous) {
                this.checked = false;
            }
            this.previous = this.checked;
        });
        </script>
	<script>
            $(document).ready(function(){     
   $('#user_filter3').change(function(){
         if($('#user_filter3').val()=='1'){
           $('.tablepending').show();
           $('.tablecompleted').hide();      
           $('.tablecancel').hide();  
        }
    });
});
          $(document).ready(function(){     
   $('#user_filter3').change(function(){
         if($('#user_filter3').val()=='2'){
          
           $('.tablecompleted').show();      
           $('.tablecancel').hide(); 
           $('.tablepending').hide();           
        }
    });
});

        $(document).ready(function(){     
   $('#user_filter3').change(function(){
         if($('#user_filter3').val()=='3'){
          $('.tablecancel').show(); 
          $('.tablecompleted').hide();      
         $('.tablepending').hide();  
                      
        }
    });
});

        </script>
	
	<!-- this page specific inline scripts -->
	
</body>
</html>