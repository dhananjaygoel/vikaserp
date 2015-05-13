<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Customers - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Customers</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Pending Customers</h1>
										
										
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
                                        <th class="col-md-1">#</th>
                                        <th>Customer Name</th>
                                        <th>Contact Person</th>
                                        <th>Phone</th>
                                        <th>Delivery Location </th>                                                            
                                                                                       
                                       <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td class="col-md-1">1</td>
                                        <td>Customer1</td>
                                        <td>Person1</td>
                                                                           
                                        <td>123456789 </td>
                                        <td>Pune</td>
                                        <td class="text-center">
                                            <a href="add_pendingcustomer.php" class="table-link" title="Add to customer">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>    
                                            <a href="edit_pendingcustomer.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                        </td>
                                        
                                    </tr>
                                                                             <tr>
                                        <td class="col-md-1">2</td>
                                        <td>Customer2</td>
                                        <td>Person2</td>
                                                                           
                                        <td>123456789 </td>
                                        <td>Noida</td>
                                     <td class="text-center">
                                            <a href="add_pendingcustomer.php" class="table-link" title="Add to customer">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>    
                                            <a href="edit_pendingcustomer.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal">
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
                                         <p>Are you sure you want to delete this record?</p>
                                        
                                         
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
	
	<!-- this page specific inline scripts -->
	
</body>
</html>