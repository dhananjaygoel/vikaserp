<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title> Orders - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Order </span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left"></h1>
										<div class="pull-right top-page-ui">
											<a href="edit_orders.php" class="btn btn-primary pull-right">
												Edit Order
											</a>
										</div>
										
									</div>
								</div>
							</div>
                                                    <br>
							<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2><i class="fa fa-dashboard"></i> &nbsp; View Order </h2>
            </header>            

            <div class="main-box-body clearfix">
                 
                     <div class="inquiry_table col-md-12">
                          
                                            <div class="table-responsive">
                                            <table id="table-example" class="table table-hover customerview_table  ">
                                
                                        
                                            <tbody>   
                                                  <tr>
                                            <td><span>Customer Name:</span> Customer1</td>
                                            
                                    
                                        </tr>
                                        <tr><td><span>Contact Person: </span>Lorem Ipsum</td></tr>
                                         <tr>
                                        <td><span>Mobile Number: </span>9166778822</td>
                                     
                                        </tr>
                                        <tr> <td><span>Credit Period: </span>Lorem Ipsum</td></tr>   
                                        <tr>
                                            <td><span class="underline">Ordered Product Details </span></td>
                                 
                                        </tr>
                                            </tbody>
                                            </table>
                                         <table id="table-example" class="table table-hover customerview_table  ">
                                
                                        
                                            <tbody>   
                                         <tr class="headingunderline">
                                            
                                                   
                                                    <td>
                                                    <span> Product</span>
                                                    </td>
                                                    <td>
                                                    <span> Qty</span>
                                                    </td>
                                                    <td>
                                                    <span>Unit</span>
                                                    </td>
                                                   
                                                    <td>
                                                    <span>Price</span>
                                                    </td>
                                                   <td class="widthtable">
                                                    <span>Remark</span>
                                                    </td>
                                                     
                                                </tr>
                                        
                                                <tr>
                                                   
                                                    <td>
                                                    Product1
                                                    </td>
                                                    <td>
                                                   55
                                                    </td>
                                                    <td>
                                                    350
                                                    </td>
                                                  
                                                     
                                                    <td>
                                                    650
                                                    </td>
                                                      <td>
                                                    Lorem
                                                    </td>
                                                  
                                                </tr>
                                                   <tr>
                                                  
                                                    <td>
                                                    Product1
                                                    </td>
                                                    <td>
                                                   55
                                                    </td>
                                                    <td>
                                                    350
                                                    </td>
                                                   
                                                     
                                                    <td>
                                                    650
                                                    </td>
                                                     <td>
                                                    Lorem
                                                    </td>
                                                  
                                                </tr>
                                                   <tr>
                                                    
                                                    <td>
                                                    Product1
                                                    </td>
                                                    <td>
                                                   55
                                                    </td>
                                                    <td>
                                                    350
                                                    </td>
                                                 
                                                     
                                                    <td>
                                                    650
                                                    </td>
                                                       <td>
                                                    Lorem
                                                    </td>
                                                  
                                                </tr>
                                                   <tr>
                                                
                                                    <td>
                                                    Product1
                                                    </td>
                                                    <td>
                                                   55
                                                    </td>
                                                    <td>
                                                    350
                                                    </td>
                                                  
                                                     
                                                    <td>
                                                    650
                                                    </td>
                                                      <td>
                                                    Lorem
                                                    </td>
                                                  
                                                </tr>
                                             
                                              
                                            </tbody>
                                         </table>
                                                 <table id="table-example" class="table table-hover customerview_table  ">
                                
                                        
                                            <tbody>   
                                          <tr>
                                        <td><span>All Inclusive: </span>Yes</td>
                                    
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
                                        <td><span>Estimated Price: </span>Lorem</td>
                                    
                                        </tr>   
                                        <tr>
                                        <td><span>Estimated Delivery Date: </span>20 April,2015</td>
                                    
                                        </tr>   
                                        
                                     <tr>
                                        <td><span>Target Delivery Date: </span>25 April,2015</td>
                                    
                                        </tr>      
                                         <tr>
                                        <td><span>Delivery Location: </span>Lorem Ipsum Dollar</td>
                                    
                                        </tr>
                                                <tr>
                                        <td><span>Remark: </span>Lorem Ipsum Dollar</td>
                                    
                                        </tr>
                                                
                                        
                                                </tbody>
                                                </table>
                                                </div>

                                                </div>
                                                
          
                <div class="clearfix"></div>
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
$(document).ready(function(){
    $("#optionsRadios1").click(function(){
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#optionsRadios2").click(function(){
        $(".exist_field").show();
        $(".customer_select").hide();
    });
     $("#optionsRadios4").click(function(){
        $(".supplier").show();
       
    });
      $("#optionsRadios3").click(function(){
        $(".supplier").hide();
       
    });
});
</script>

<script>
$(document).ready(function(){
    $("#addmore1").click(function(){
        $(".row5").hide();
        $(".row6").show();
        $(".row7").show();
    });
    $("#addmore2").click(function(){
        $(".row7").hide();
        $(".row8").show();
        $(".row9").show();
    });
    $("#addmore3").click(function(){
        $(".row9").hide();
        $(".row10").show();
        $(".row11").show();
    });
      $("#addmore4").click(function(){
        $(".row11").hide();
        $(".row12").show();
        
    });
   
 

});
</script>
<script>
            $(document).ready(function(){
		//toggle `popup` / `inline` mode
		$.fn.editable.defaults.mode = 'popup';     
		
		//make username editable
		
		
		
                $('#labours').editable();
               
		
                });
              
        </script> 
        <script src="js/bootstrap-editable.min.js"></script>
</body>
</html>