<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Delivery Challan - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Delivery Challan</span></li>
									</ol>
									
										<div class="filter-block">
                                                                                    <h1 class="pull-left">View Delivery Challan </h1>                                 
                                                                                  <div class="pull-right top-page-ui">
											<a href="edit_deliverychallan.php" class="btn btn-primary pull-right">
												Edit Delivery Challan
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
                                            <td><span>Date:</span> 30 April 2015</td>
                                            
                                    
                                        </tr>
                                       
                                        <tr>
                                        <td><span>Serial Number: </span>Apr16/08/02/01</td>
                                    
                                        </tr>
                                        
                                        <tr>
                                        <td><span>Party Name: </span>Party1</td>
                                     
                                        </tr>
                                         
                                        <tr>
                                            <td><span class="underline"> Product Details </span></td>
                                 
                                        </tr>
                                </tbody>
                                                        </table>
                                                <table id="table-example" class="table customerview_table">
                              
                                <tbody>  
                                         <tr class="headingunderline">
                                                   <td>
                                                    <span>Product Name</span>
                                                    </td>
                                             <td>
                                                    <span>Actual Quantity</span>
                                                    </td>
                                                     <td>
                                                    <span>Actual Pieces</span>
                                                    </td>
                                                    <td>
                                                    <span>Unit</span>
                                                    </td>
                                                    <td>
                                                    <span>Rate</span>
                                                    </td>
                                                    <td>
                                                    <span>Present Shipping</span>
                                                    </td>
                                                     <td>
                                                    <span>Amount</span>
                                                    </td>
                                                     <td>
                                                    <span>Remark</span>
                                                    </td>
                                                   
                                                     
                                                  
                                                  
                                                </tr>
                                      
                                           <tr>
                                                <td>Product1</td>
                                            <td> 60</td>
                                            <td> 60</td>
                                            <td> 60</td> 
                                            <td> 60</td>
                                            <td> 35</td>
                                            <td> 350</td>
                                            <td>Lorem</td>
                                            
                                            
                                        </tr>
                                        
                                           <tr>
                                                 <td>Product2</td>
                                            <td> 60</td>
                                            <td> 60</td>
                                            <td> 60</td> 
                                            <td> 60</td>
                                            <td> 35</td>
                                            <td> 350</td>
                                            <td>Lorem</td>
                                            
                                            
                                        </tr>
                                        
                                           <tr>
                                                 <td>Product3</td>
                                            <td> 60</td>
                                            <td> 60</td>
                                            <td> 60</td> 
                                            <td> 60</td>
                                            <td> 35</td>
                                            <td> 350</td>
                                            <td>Lorem</td>
                                            
                                            
                                        </tr>
                                        
                                           <tr>
                                              <td>Product4</td>   
                                            <td> 60</td>
                                            <td> 60</td>
                                            <td> 60</td> 
                                            <td> 60</td>
                                            <td> 35</td>
                                            <td> 350</td>
                                            <td>Lorem</td>
                                            
                                            
                                        </tr>
                                          
                                     
                                     
                                       
                                     
                                      

                                        </tbody>
                                        </table>
                               <table id="table-example" class="table table-hover customerview_table  ">
                                
                                        
                                            <tbody>   
                                                <tr>
                                        <td><span>Discount: </span>5%</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Freight: </span>Lorem ipsum</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Loading: </span>Lorem ipsum</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Loaded By: </span>Lorem ipsum</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Labours: </span>Lorem ipsum</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Total: </span>500</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Plus VAT: </span>Yes</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>VAT Percentage: </span>5%</td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>VAT: </span></td>
                                     
                                        </tr>
                                        <tr>
                                        <td><span>Grand Total: 6000</span></td>
                                     
                                        </tr>
                                         <tr>
                                        <td><span>Delivery Location: </span>Lorem Ipsum Dollar</td>
                                    
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

