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
										<li class="active"><span>View Customer</span></li>
									</ol>
									
										<div class="filter-block">
                                                                                    <h1 class="pull-left">View Customer</h1>                                 
                                                                                  <div class="pull-right top-page-ui">
											<a href="edit_customer.php" class="btn btn-primary pull-right">
												Edit Customer
											</a>
										</div>
                                                                                 
											
										</div>
                                                                                
								</div>
							</div>
							
							<div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        
                        <div class="table-responsive">
                                                        <table id="table-example" class="table customerview_table">
                              
                                <tbody>                    


                                        <tr>
                                            <td><span>Owner Name:</span> Owner1</td>
                                       
                                        </tr>
                                        <tr>
                                            <td><span>Company Name:</span> Company1</td>
                                    
                                        </tr>
                                        <tr>
                                            <td><span>Contact Person:</span> Lorem ipsum</td>
                                    
                                        </tr>
                                        <tr>
                                        <td><span>Address1: </span>Lorem Ipsum Dollar</td>
                                    
                                        </tr>
                                        <tr>
                                        <td><span>Address2: </span>Lorem Ipsum Dollar</td>
                                    
                                        </tr>
                                        <tr>
                                            <td class="col-md-4"><span>City:</span> Ipsum</td>
                                           
                                            
                                        </tr>
                                        <tr> <td><span>State:</span> Lorem </td></tr>
                                        <tr><td><span>Zip:</span> 302021</td></tr>
                                        <tr>
                                            <td><span>Email:</span> <a href="mailto:"/>Info@company.com</a></td>
                                    
                                        </tr>
                                        <tr>
                                            <td><span>Tally Name:</span> Tally1</td>
                                    
                                        </tr>
                                        <tr>
                                            <td><span>Tally Category:</span> Lorem</td>
                                    
                                        </tr>
                                        <tr>
                                            <td><span>Tally Subcategory:</span> Ipsum</td>
                                    
                                        </tr>
                                        <tr>
                                            <td><span>Phone Number1:</span> 123456789</td>
                                            
                                            
                                        </tr>
                                        <tr><td><span>Phone Number2:</span> 123456789</td></tr>
                                        <tr> <td><span>VAT-TIN Number:</span> 654321</td></tr>
                                        <tr><td><span>Excise Number:</span> 2345678</td></tr>
                                        <tr>
                                            <td><span>Delivery Location:</span> Ex-warehouse</td>
                                    
                                        </tr>
                                         <tr>
                                             <td><span>Username:</span> User1</td>
                                           
                                        </tr>
                                        <tr><td><span>Password:</span> password</td></tr>
                                          <tr>
                                              <td><span>Credit Period:</span> </td>
                                       
                                        </tr>
                                        <tr>
                                            <td><span>Relationship Manager:</span> Admin1</td>
                                       
                                        </tr>

                                        </tbody>
                                        </table>



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