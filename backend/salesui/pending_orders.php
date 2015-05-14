<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Pending Orders - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Pending Order Report</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Pending Order Report</h1>
										
										<div class="filter-block pull-right">
										
                                                                                    <div class="form-group pull-left">
                                                                                        <div class="col-md-12">
                                                                                        <select class="form-control" id="user_filter" name="user_filter">
                                                                                    <option value="" selected="">Select Party</option>
                                                                                    <option value="2">Party Name 1</option>
                                                                                     <option value="2">Party Name 2</option>
                                                                                    
                                                                                                                
                                                                                </select>
                                                                                        </div>
                                                                                        </div>
                                                                                                          <div class="form-group pull-left">
                                                                                    <select class="form-control" id="user_filter1" name="user_filter">
                                                                                                    <option value="" selected="">Fullfilled</option>
                                                                                                    <option id="warehouse" value="1">Warehouse</option>
                                                                                                    <option id="direct" value="2">Direct</option>

                                                                                                    </select> 
												</div>
                                                                                    <div class="form-group pull-left">
                                                                                        <div class="col-md-12">
                                                                                        <select class="form-control" id="user_filter" name="user_filter">
                                                                                    <option value="" selected="">Select Location</option>
                                                                                    <option value="2">Delhi</option>
                                                                                     <option value="2">Mumbai</option>
                                                                                     
                                                                                    
                                                                                                                
                                                                                </select>
                                                                                        </div>
                                                                                        </div>
                                                                                    <div class="form-group pull-left">
                                                                                        <div class="col-md-12">
                                                                                        <select class="form-control" id="user_filter" name="user_filter">
                                                                                    <option value="" selected="">Select Size</option>
                                                                                    <option value="2">30 kg</option>
                                                                                     <option value="2">20 kg</option>
                                                                                     
                                                                                    
                                                                                                                
                                                                                </select>
                                                                                        </div>
                                                                                        </div>
                                                                                    
                                                                                   
												
												
											</div>
									</div>
								</div>
							</div>
							
							<div class="row" id="table_warehouse">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        
                        <div class="table-responsive">
                                                        <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Party</th>
                                       
                                        <th>Quantity</th>
                                       
                                        <th>Remarks</th>
                                        <th>Delivery Location </th> 
                                        <th>Order By</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td>1</td>
                                        <td>pun01 </td>
                                        <td>09 Apr 2015</td>
                                        
                                        <td>Party Name 1</td>
                                        
                                        <td>5</td>                                        
                                        
                                         <td>Lorem Ipsum</td>
                                         <td>Pune</td>  
                                         <td>Name 1</td>
                                            
                                                                                
                                    </tr>
                                                                        <tr>
                                        <td>2</td>
                                        <td>mum02 </td>
                                        <td>19 Apr 2015</td>
                                        <td>Party Name 2</td>
                                        
                                        <td>9</td>                                        
                                       
                                        <td>Lorem Ipsum</td>
                                         <td>Mumbai</td>  
                                        <td>Name 2</td>
                                      
                                                                                
                                    </tr>
                                    
                                                                        
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
           <div class="row" id="table_direct">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        
                        <div class="table-responsive">
                                                        <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Order</th>
                                        <th>Date</th>
                                        <th>Party</th>
                                       
                                        <th>Quantity</th>
                                        
                                        <th>Remarks</th>
                                        <th>Delivery Loc </th>
                                        <th>Supplier</th>
                                        <th>Order By </th> 
                                  
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td>1</td>
                                        <td>pun01 </td>
                                        <td>09 Apr 2015</td>
                                        
                                        <td>Party Name 1</td>
                                        
                                        <td>5</td>                                        
                                       
                                         <td>Lorem Ipsum..</td>
                                         <td>Pune</td>  
                                         <td>Supplier1</td>
                                        <td>Name 1</td>
                                       
                                                                                
                                    </tr>
                                                                        <tr>
                                        <td>2</td>
                                        <td>mum02 </td>
                                        <td>19 Apr 2015</td>
                                        <td>Party Name 2</td>
                                        
                                        <td>9</td>                                        
                                       
                                         <td>Lorem Ipsum..</td>
                                         <td>Mumbai</td>  
                                         <td>Supplier2</td> 
                                        <td>Name 2</td>
                                          
                                                                                
                                    </tr>
                                    
                                                                        
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
	
	<!-- this page specific inline scripts -->
         	 <script>
            $(document).ready(function(){     
   $('#user_filter1').change(function(){
         if($('#user_filter1').val()=='1'){
           $('#table_warehouse').show();
           $('#table_direct').hide();           
        }
    });
});
          $(document).ready(function(){     
   $('#user_filter1').change(function(){
         if($('#user_filter1').val()=='2'){
            $('#table_direct').show(); 
            $('#table_warehouse').hide();
                      
        }
    });
});



        </script>
	
</body>
</html>