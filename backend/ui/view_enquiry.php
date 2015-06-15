<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Inquiry - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Inquiry</span></li>
									</ol>
									
										<div class="filter-block">
                                                                                    <h1 class="pull-left">View Inquiry</h1>                                 
                                                                                  <div class="pull-right top-page-ui">
											<a href="edit_enquiry.php" class="btn btn-primary pull-right">
												Edit Inquiry
											</a>
										</div>
                                                                                 
											
										</div>
                                                                                
								</div>
							</div>
							
							<div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <form>
                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">
                              
                                <tbody>                    


                                      
                                        <tr>
                                            <td><span>Customer Name:</span> Customer1</td>
                                            
                                    
                                        </tr>
                                        <tr><td><span>Contact Person: </span>Lorem Ipsum </td></tr>
                                        <tr>
                                        <td><span>Mobile Number: </span>9166778822</td>
                                    
                                        </tr>
                                        <tr>
                                        <td><span>Credit Period: </span>Lorem Ipsum Dollar</td>
                                    
                                        </tr>
                                         
                                        <tr>
                                            <td><span class="underline">Product Details </span></td>
                                 
                                        </tr>
                                </tbody>
                            </table>
                             <table id="table-example" class="table customerview_table">
                              
                                <tbody>                    
                                         <tr class="headingunderline">
                                                 
                                                    
                                                    <td>
                                                    <span> Product</span>
                                                    </td>
                                                    <td>
                                                    <span> Quantity</span>
                                                    </td>
                                                    <td>
                                                    <span>Unit</span>
                                                    </td>
                                                    <td>
                                                    <span>Price</span>
                                                    </td>
                                                    <td class="widthtable"><span>Update Price</span></td>
                                                    <td>
                                                    <span>Remark</span>
                                                    </td>
                                                     
                                                  
                                                  
                                                </tr>
                                        <tr>
                                            
                                            <td>Product1</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>62</td>
                                            <td>   
                                                <div class="row product-price">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" id="difference" value="" placeholder="Price">
                                               
                                            </div>
                                            <div class="form-group col-md-2 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>
                                         <tr>
                                          
                                            <td>Product2</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>69</td>
                                               <td>   
                                                <div class="row product-price">
                                            <div class="form-group col-md-6">
                                              <input type="text" class="form-control" id="difference" value="" placeholder="Price">
                                               
                                            </div>
                                            <div class="form-group col-md-2 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>
                                         <tr>
                                            
                                            <td>Product3</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>69</td>
                                               <td>   
                                                <div class="row product-price">
                                            <div class="form-group col-md-6">
                                            <input type="text" class="form-control" id="difference" value="" placeholder="Price">
                                               
                                            </div>
                                            <div class="form-group col-md-2 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>
                                         <tr>
                                            
                                            <td>Product4</td>
                                            <td>55</td>
                                            <td>6</td>
                                            <td>55</td>
                                               <td>   
                                                <div class="row product-price">
                                            <div class="form-group col-md-6">
                                             <input type="text" class="form-control" id="difference" value="" placeholder="Price">
                                               
                                            </div>
                                            <div class="form-group col-md-2 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                            </td>
                                            <td>Lorem </td>
                                        </tr>
                                       
                                </tbody>
                             </table>
                             <table id="table-example" class="table customerview_table">
                              
                                <tbody>                    
                                     <tr>
                                        <td><span>Plus VAT: </span>Yes</td>
                                    
                                        </tr>
                                         <tr>
                                        <td><span>VAT Percentage: </span>5%</td>
                                    
                                        </tr>
                                         <tr>
                                        <td><span>VAT: </span>Lorem</td>
                                    
                                        </tr>
                                         <tr>
                                        <td><span>Grand Total: </span> 5000</td>
                                    
                                        </tr>
                                        
                                     <tr>
                                        <td><span>Expected Delivery Date: </span>25 April,2015</td>
                                    
                                        </tr>
                                         <tr>
                                        <td><span>Remark: </span>Lorem Ipsum Dollar</td>
                                    
                                        </tr>

                                        </tbody>
                                        </table>



                          </div>
                           <!-- <button type="button" class="btn btn-primary " >Save</button>-->
                            <hr>
                            <div>
                                <button title="SMS would be sent to Party and Relationship Manager" type="button" class="btn btn-primary smstooltip" >Send SMS</button><span title="SMS has been sent 5 times" class="badge enquirybadge smstooltip">5</span>
                                 
                            </div>
                        </form>
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
<script>

$(function() {
    $('.smstooltip').tooltip();
});
</script>
</body>
</html>
