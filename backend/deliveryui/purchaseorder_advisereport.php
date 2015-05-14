<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Pending Purchase Advice Report - Vikas Associate Order Automation System</title>
	
	<!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap.min.css" />
	<link rel="stylesheet" href="css/libs/daterangepicker.css" type="text/css" />
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
										<li class="active"><span>Pending Purchase Advice Report</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Pending Purchase Advice Report</h1>
										
										  
                                                                                    <div class="form-group pull-right">
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
                                        <th><a href="#" class="desc"><span>Total Quantity</span></a></th>
                                        <th><a href="#" class="desc"><span>Truck Number</span></a></th>
                                
                                        <th><a href="#" class="desc"><span>Order By</span></a> </th> 
                                        
                                        <th class="col-md-2">Remarks </th> 
                                     
                                        
                                    </tr>
                                </thead>
                                <tbody>                    


                                    
                                        <tr>
                                        <td>1</td>
                                        <td>30 April 2015</td>
                                        <td>PO/Apr15/04/01</td>
                                        <td>Party Name 1</td>
                                        <td>500</td>
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
	<script src="js/moment.min.js"></script>
        <script src="js/select2.min.js"></script>
        <script src="js/bootstrap-datepicker.js"></script>
	<!-- theme scripts -->
	<script src="js/scripts.js"></script>
	<script src="js/pace.min.js"></script>
	<script src="js/bootstrap-editable.min.js"></script>
	<!-- this page specific inline scripts -->
	<script type="text/javascript">

            $(function($) {
		$('#datepickerDate').datepicker({
		  format: 'dd-mm-yyyy'
		});

		$('#datepickerDateComponent').datepicker();
                });

        
	</script>
        <script>
            $(document).ready(function(){
		//toggle `popup` / `inline` mode
		$.fn.editable.defaults.mode = 'popup';     
		
		//make username editable
		$('#username').editable();
		
		$('#remarks').editable();
                $('#labours').editable();
                $('#bill').editable();
		
                });
              
        </script> 
     
        </body>
</html>