<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Edit Delivery Order - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Edit Order</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left"></h1>
										
										
									</div>
								</div>
							</div>
											<div  class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2><i class="fa fa-user"></i> &nbsp; Edit Delivery Order</h2>
            </header>            
            <form method="POST" action="" accept-charset="UTF-8" >
                <div class="main-box-body clearfix"> 
                     <div class="form-group">
                         <label><b>Date:</b> 25 April,2015</label>
                   
                </div>
                     <div class="form-group">
                       <label><b>Serial Number:</b> Apr30/02/02/01</label>
                </div>
                    <div class="form-group">
                         <label><b>Customer Name:</b> lorem Ipsum</label>
                   
                </div>
                <div class="inquiry_table col-md-12">
                          
                                            <div class="table-responsive">
                                            <table id="table-example" class="table table-hover  ">
                                
                                        
                                            <tbody> 
                                                  <tr class="headingunderline">
                                                   
                                                       <td><span>Select Product</span></td>
                                                       <td><span>Quantity</span></td>
                                                       <td><span>Unit</span></td>
                                                       <td><span>Price</span></td>
                                                       <td><span>Pending Order</span></td>
                                                       <td><span>Present Shipping</span></td>
                                                       <td><span>Remark</span></td>
                                                        
                                                </tr>
                                                <tr>
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group ">
                                                   Product
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                                                    Quantity                  </div>
                                                    </td>
                                                      <td >
                                                          <div class="form-group">   Unit</div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">15</div></td>
                                                     <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                          <tr>
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group ">
                                                   Product
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                                                    Quantity                  </div>
                                                    </td>
                                                      <td >
                                                          <div class="form-group">   Unit</div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">15</div></td>
                                                     <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                   <tr>
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group ">
                                                   Product
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                                                    Quantity                  </div>
                                                    </td>
                                                      <td >
                                                          <div class="form-group">   Unit</div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">15</div></td>
                                                     <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                   <tr>
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group ">
                                                   Product
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                                                    Quantity                  </div>
                                                    </td>
                                                      <td >
                                                          <div class="form-group">   Unit</div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">15</div></td>
                                                     <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
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
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">64</div></td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
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
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">64</div></td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
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
                                                          <tr class="row10">
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">64</div></td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                <tr class="row11">
                                                        <td>
                                                             <div class="add_button1">
                                                    <div class="form-group pull-left">

                                                    <label for="addmore"></label>
                                                    <a href="#" class="table-link" title="add more" id="addmore4">
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
                                                  <tr class="row12">
                                                   
                                                 
                                                    <td>
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td>
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group col-md-6">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>
                                            </div>
                                                    </td>
                                                    <td class="text-center"><div class="form-group">64</div></td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="shipping" class="form-control" placeholder="Present shipping" name="shipping" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td>
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                             
                                                
                                                             </tbody>
                                        </table>
                        </div>
                    
                                              
                                        
                                               
					</div>
                    <!--  <div class="form-group">
                       <label><b>All Inclusive:</b> Yes</label>
                </div>
                    <div class="form-group">
                       <label><b>VAT Percentage:</b> 5%</label>
                </div>
                  <div class="form-group">
                       <label><b>VAT:</b> Lorem </label>
                </div><div class="form-group">
                       <label><b>Grand Total:</b> 5000</label>
                </div>-->
                    <div class="form-group">
                       <label><b>Estimated Price:</b> Lorem Ipsum</label>
                </div>
                    <div class="form-group">
                       <label><b>Estimated Delivery Date:</b> 10 April,2015</label>
                </div>
                    
                     <div class="form-group">
                       <label><b>Target Delivery Date:</b> 15 April,2015</label>
                </div>
                       <table id="table-example" class="table table-hover  ">
                                
                                        
                                                <tbody>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">VAT Percentage:</td>
                                                        <td><input id="price" class="form-control" placeholder="VAT Percentage" name="price" value="" type="text"></td>
                                                    </tr>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">Vehicle Name:</td>
                                                        <td><input id="price" class="form-control" placeholder="Vehicle Name" name="price" value="" type="text"></td>
                                                    </tr>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">Driver Name:</td>
                                                        <td><input id="price" class="form-control" placeholder="Driver Name" name="price" value="" type="text"></td>
                                                    </tr>
                                                   <tr class="cdtable">
                                                        <td class="cdfirst">Driver Contact:</td>
                                                        <td><input id="price" class="form-control" placeholder="Driver Contact" name="price" value="" type="text"></td>
                                                    </tr>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">Remark:</td>
                                                        <td><input id="price" class="form-control cdbox" placeholder="Remark" name="price" value="" type="text"></td>
                                                    </tr>
                                                    
                                                   
                                                </tbody>
                                            </table>
                    
                    
                      <button type="button" class="btn btn-primary form_button_footer" >Save and Send SMS</button>
                      <hr>
                      <div>
                  <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                  </div>
              </div>
                
                
            </form>
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
</body>
</html>