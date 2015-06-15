<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Add Delivery Order - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Add Delivery Order</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left"></h1>
										
										
									</div>
								</div>
							</div>
											<div  class="row">
    <div class="col-lg-12">
        <div class="main-box">
                     

              <div class="main-box-body clearfix">
                
                <div class="form-group">
                    Date: 29 April, 2015
                </div>   
                
                <form method="POST" action="" accept-charset="UTF-8" >
                    <div class="form-group">
                
                    <label>Customer</label>
                    <div class="radio">
                        <input  value="exist" id="optionsRadios1" name="status" type="radio">
                        <label for="optionsRadios1">Existing</label>
                        <input  value="new" id="optionsRadios2" name="status" type="radio">
                        <label for="optionsRadios2">New</label>
                    
                        
                    </div>
                       <div class="customer_select" >
                  
                    <div class="col-md-4">
                    <div class="form-group searchproduct">
                    <input class="form-control" placeholder="Enter Customer Name " type="text">
                    <i class="fa fa-search search-icon"></i>
                    </div>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    
                </div>
                    <div class="exist_field" style="display: none">
                    <div class="form-group">
                    <label for="name">Customer Name</label>
                    <input id="name" class="form-control" placeholder="Name" name="name" value="" type="text">
                </div>
                   <div class="form-group">
                    <label for="name">Contact Person</label>
                    <input id="contact_person" class="form-control" placeholder="Contact Person" name="contact_person" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="mobile_number">Mobile Number </label>
                    <input id="mobile_number" class="form-control" placeholder="Mobile Number " name="mobile_number" value="" type="text">
                </div>
                    
                    <div class="form-group">
                    <label for="period">Credit Period</label>
                    <input id="period" class="form-control" placeholder="Credit Period" name="period" value="" type="text">
                </div>
            </div>
                   
                    
                 <div class="inquiry_table col-md-12">
                          
                                            <div class="table-responsive">
                                            <table id="table-example" class="table table-hover  ">
                                
                                        
                                            <tbody> 
                                                  <tr class="headingunderline">
                                                   
                                                      <td><span>Select Product(Alias)</span></td>
                                                       <td><span>Quantity</span></td>
                                                       <td><span>Unit</span></td>
                                                       <td><span>Price</span></td>
                                                     
                                                       <td><span>Remark</span></td>
                                                        
                                                </tr>
                                                <tr>
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                     <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                               <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                    
                                                      <td class="col-md-4">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                  <tr>
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                            <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                   
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                  <tr>
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                              <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                   
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                  <tr>
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                                <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                    
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                  <tr>
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                          <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                   
                                                      <td class="col-md-2">
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
                                                    </tr>
                                                    <tr class="row6">
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                               <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                    
                                                      <td class="col-md-2">
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
                                                    </tr>
                                                    <tr class="row8">
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                               <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                   
                                                      <td class="col-md-2">
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
                                                    </tr>
                                                    <tr class="row10">
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                                <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                      
                                                      <td class="col-md-2">
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
                                                    </tr>
                                                    <tr class="row12">
                                                   
                                                 
                                                    <td class="col-md-3">
                                                        <div class="form-group searchproduct">
                                                    <input class="form-control" placeholder="Enter Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                      
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                     <td class="col-md-2">
                                                          <div class="row">
                                            <div class="form-group ">
                                             <input type="text" class="form-control" value="" id="price" placeholder="price">
                                               
                                            </div>
                                          
                                            </div>
                                                    </td>
                                                    
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                             
                                                
                                                             </tbody>
                                        </table>
                        </div>
                    
                                              
                                        
                                               
					</div>
                    
             
        
                <div class="form-group">
                    <label for="vehicle_name">Vehicle Number</label>
                    <input id="vehicle_name" class="form-control" placeholder="Vehicle Number" name="vehicle_name" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="driver_name">Driver Name</label>
                    <input id="driver_name" class="form-control" placeholder="Driver Name " name="driver_name" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="driver_contact">Driver Contact</label>
                    <input id="driver_contact" class="form-control" placeholder="Driver Contact" name="driver_contact" value="" type="text">
                </div>
                  
                 
                              
                    <div class="row col-md-4">  
                 <div class="form-group">
  <label for="location">Delivery Location:</label>
  <select class="form-control" id="loc1">
    <option>Location1</option>
    <option>Location2</option>
    <option id="other" value="3">Other</option>

  </select>
</div>
                        </div>
                      <div class="clearfix"></div>
                    <div class="locationtext">
                        <div class="row">
                    <div class="form-group col-md-4">
                    <label for="location">Location </label>
                    <input id="location" class="form-control" placeholder="Location " name="location" value="" type="text">
                </div>
                        <div class="col-md-8 addlocation">
                           
                            <button class="btn btn-primary btn-xs">ADD</button>
                        </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    
                    <div class="form-group">
                    
                    <div class="radio">
                        <input checked="" value="include_vat" id="optionsRadios5" name="status1" type="radio">
                        <label for="optionsRadios5">All Inclusive</label>
                        <input value="exclude_vat" id="optionsRadios6" name="status1" type="radio">
                        <label for="optionsRadios6">Plus VAT</label>
                    </div>
                </div>
                        <div class="plusvat " style="display: none">
                    <div class="form-group">
                        <table id="table-example" class="table ">
                                
                                        
                                                <tbody>
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">VAT Percentage:</td>
                                                        <td><input id="price" class="form-control" placeholder="VAT Percentage" name="price" value="" type="text"></td>
                                                    </tr>
                                                   <!-- <tr class="cdtable">
                                                        <td class="cdfirst">VAT:</td>
                                                        <td>Lorem</td>
                                                    </tr>
                                                  
                                                    <tr class="cdtable">
                                                        <td class="cdfirst">Grand Total:</td>
                                                        <td>650</td>
                                                    </tr>-->
                                                    
                                                   
                                                </tbody>
                                            </table>
                    
                </div>
                         
                       
                
            </div> 
                     
                
                <!--
                     <div class="form-group col-md-4 targetdate">
                          <label for="date">Expected Delivery Date </label>
                    <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="date" class="form-control" id="datepickerDate1">
                    </div>
                        </div>-->
                       <div class="clearfix"></div>
                    <div class="form-group">
                        <label for="grandtotal">Grand Total:<span class="gtotal"> $25000</span></label>
                        
                    </div>
                    <div class="form-group">
			<label for="inquiry_remark">Remark</label>
                        <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                    </div>
                    
                     <div >
                   <!--  <button title="SMS would be sent to Party" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button>--> 
                    

                    </div>
                         
                                    
              
                
                <hr>
                <div >
                    <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                    
                    <a href="delivery_orders.php" class="btn btn-default form_button_footer">Back</a>
                </div>
                
                <div class="clearfix"></div>
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
       $("#optionsRadios6").click(function(){
        $(".plusvat").show();
     
    });
      $("#optionsRadios5").click(function(){
        $(".plusvat").hide();
     
    });
});
$('#datepickerDate').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDateComponent').datepicker();
 $('#datepickerDate1').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDateComponent1').datepicker();
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
   
      $('#loc1').change(function(){
         if($('#loc1').val()=='3'){
           $('.locationtext').toggle();
          
        }
       

});

});
</script>
<script>
$(function() {
    $('.smstooltip').tooltip();
});
</script>
</body>
</html>