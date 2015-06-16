<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Create Purchase Advice - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Create Purchase Advice </span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left"></h1>
										
										
									</div>
								</div>
							</div>
							
							<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
                     

            <div class="main-box-body clearfix">
                    <form method="POST" action="" accept-charset="UTF-8" >
                     
                        <div class="inquiry_table col-md-12">
                          
                                            <div class="table-responsive">
                                          <!--  <table id="table-example" class="table table-hover  ">
                                
                                           <tbody>
                                                    <tr><td><b>Bill Date:</b> 25 April,2015</td></tr>
                                                  
                                                    <tr><td><b>Customer Name:</b> Customer1</td></tr>
                                                    <tr><td><b>Contact person:</b> lorem Ipsum</td> </tr>
                                                    
                                                    <tr><td><b>Mobile:</b> 9188556655</td></tr>
                                                    <tr><td><b>Credit Period:</b> </td> </tr>
                                                   
                                                    
                                                    
                                                </tbody>
                                            </table>-->
                                                   <table id="table-example" class="table ">
                                
                                                <tbody>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">Bill Date:</td>
                                                        <td>
                                                             <div class="targetdate">
                        
               
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="date" class="form-control" id="datepickerDate">
                    </div>
               
            </div>
                                                        </td>
                                                    </tr>
                                                   
                                                     <tr class="cdtable">
                                                        <td><b>Supplier Name:</b></td>
                                                        <td>Lorem Ipsum</td>
                                                    </tr>
                                       
                                                </tbody>
                                            </table>
                                                <table id="table-example" class="table table-hover  ">
                                
                                        
                                            <tbody>   
                                                 <tr class="headingunderline">
                                            
                                                  
                                                     <td class="col-md-2">
                                                    <span> Product Name(Alias)</span>
                                                    </td>
                                                   
                                                    <td class="col-md-1">
                                                    <span>Unit</span>
                                                    </td>
                                                   
                                                   
                                                    <td class="col-md-2">
                                                    <span>Pending Order</span>
                                                    </td>
                                                    
                                                    <td class="col-md-2">
                                                    <span>Present Shipping</span>
                                                    </td>
                                                    <td class="col-md-2">
                                                    <span>Price</span>
                                                    </td>
                                                    <td class="col-md-3">
                                                    <span>Remark</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                   Product Name
                                                    </td>
                                                   
                                                    <td class="col-md-1">
                                                    Unit
                                                    </td>
                                                       <td>
                                                 Pending Order
                                                       </td>
                                                      <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                      <td>
                                                 Remark
                                                       </td>
                                                    
                                                </tr>
                                             <tr>
                                                    <td>
                                                   Product Name
                                                    </td>
                                                  
                                                    <td class="col-md-1">
                                                    Unit
                                                    </td>
                                                       <td>
                                                 Pending Order
                                                       </td>
                                                        <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text" >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                     <td>
                                                 Remark
                                                       </td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td>
                                                   Product Name
                                                    </td>
                                                  
                                                    <td class="col-md-1">
                                                    Unit
                                                    </td>
                                                       <td>
                                                 Pending Order
                                                       </td>
                                                     <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text" >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                    <td>
                                                 Remark
                                                       </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                   Product Name
                                                    </td>
                                                  
                                                    <td class="col-md-1">
                                                    Unit
                                                    </td>
                                                       <td>
                                                 Pending Order
                                                       </td>
                                                      <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text" >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                    <td>
                                                 Remark
                                                       </td> 
                                                    
                                                </tr>
                                                 <tr>
                                                    <td>
                                                   Product Name
                                                    </td>
                                                   
                                                    <td class="col-md-1">
                                                    Unit
                                                    </td>
                                                       <td>
                                                 Pending Order
                                                       </td>
                                                     <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text" >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                    <td>
                                                 Remark
                                                       </td>
                                                </tr>
                                                  <tr class="row5">
                                                        <td>
                                                             <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                    <label for="addmore"></label>
                                                    <a href="#" class="table-link" title="add more" id="addmore1">
                                                    <span class="fa-stack more_button" >
                                                            <i class="fa fa-square fa-stack-2x"></i>
                                                            <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                    </a>

                                                    </div>
                                                    </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    
                                                             <tr class="row6">
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                      
                                                       <td class="col-md-1">
                                                          <div class="form-group">         <select class="form-control" name="type" id="add_status_type">
                                           <option value="" selected=""></option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select></div>
                                                    </td>
                                                
                                                    <td><div class="form-group">Pending Order</div></td>
                                                     <td>
                                                        <div class="form-group pcshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text" >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                 <tr class="row7">
                                                        <td>
                                                             <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                    <label for="addmore"></label>
                                                    <a href="#" class="table-link" title="add more" id="addmore2">
                                                    <span class="fa-stack more_button" >
                                                            <i class="fa fa-square fa-stack-2x"></i>
                                                            <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                    </a>

                                                    </div>
                                                    </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                   <tr class="row8">
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       
                                                       <td class="col-md-1">
                                                          <div class="form-group">         <select class="form-control" name="type" id="add_status_type">
                                           <option value="" selected=""></option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select></div>
                                                    </td>
                                                
                                                    <td><div class="form-group">Pending Order</div></td>
                                                     <td>
                                                        <div class="form-group pcshipping">
                                                        <input id="" class="form-control" placeholder="Present Shipping" name="" value="" type="text" >
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group pshipping">
                                                        <input id="" class="form-control" placeholder="Price" name="" value="" type="text">
                                                        </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                  <tr class="row9">
                                                        <td>
                                                             <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                    <label for="addmore"></label>
                                                    <a href="#" class="table-link" title="add more" id="addmore3">
                                                    <span class="fa-stack more_button" >
                                                            <i class="fa fa-square fa-stack-2x"></i>
                                                            <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                    </a>

                                                    </div>
                                                    </div>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                                  <table id="table-example" class="table table-hover  ">
                                
                                        
                                                <tbody>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">VAT:</td>
                                                        <td></td>
                                                    </tr>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">VAT Percentage:</td>
                                                        <td>5%</td>
                                                    </tr>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">Vehicle Number:</td>
                                                        <td><input id="price" class="form-control" placeholder="Vehicle Number" name="price" value="" type="text"></td>
                                                    </tr>
                                                    
                                                    
                                                   
                                                </tbody>
                                            </table>  
                                                <table id="table-example" class="table table-hover  ">
                                
                                        
                                                <tbody>
                                                  
                                                  
                                                    <tr><td><b>Delivery Location:</b> Location1</td> </tr>
                                                    <tr><td><b>Expected Delivery Date:</b> 25 May,2015</td></tr>
                                                    <tr><td><b>Remark:</b> Lorem Ipsum Dollar</td></tr>
                                                   
                                                </tbody>
                                            </table>
                                                <br>
                                                </div>

                                                </div>
                   
                         <!--    <button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> -->

                        <hr>
                        <div>
                            <button type="button" class="btn btn-primary " >Submit</button>
                        </div>
                        
                    </form>    
          
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
	<script src="js/bootstrap-datepicker.js"></script>
       <script src="js/select2.min.js"></script>
        <script src="js/moment.min.js"></script>
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
$('#datepickerDate').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDateComponent').datepicker();
</script>
<script>
            $(document).ready(function(){
		//toggle `popup` / `inline` mode
		$.fn.editable.defaults.mode = 'popup';     
		
		//make username editable
		
		
		
                $('#labours').editable();
               
		
                });
              
        </script> 
<script>
$(function() {
    $('.smstooltip').tooltip();
});
</script>
<script src="js/bootstrap-editable.min.js"></script>
</body>
</html>