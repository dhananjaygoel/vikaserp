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
										<li class="active"><span>Create Purchase Advice</span></li>
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
                <h2><i class="fa fa-user"></i> &nbsp; Add Purchase Advice </h2>
            </header>            

          <div class="main-box-body clearfix">
          
                                                
                <form method="POST" action="" accept-charset="UTF-8" >
                  
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
                                                        <td><b>Serial Number:</b></td>
                                                        <td>PO/Apr15/02/01/01</td>
                                                    </tr>
                                       
                                                </tbody>
                                            </table>
                    <!--
                    <div class="form-group">
                        <label for="person"><b>Contact Person:</b> Lorem Ipsum</label>
       
                </div>
                    
                     <div class="form-group">
                        <label for="mobile"><b>Mobile:</b> 9988776655</label>
       
                </div>
                     <div class="form-group">
                        <label for="cp"><b>Credit Period: </b> Lorem </label>
       
                </div>
                    -->
                    
                    <div class="form-group ">
                            
                        
                        <div class="radio">
                                 <input checked="" value="exist" id="optionsRadios1" name="status" type="radio">
                        <label for="optionsRadios1">Existing Supplier</label>
                               <input  value="exist" id="optionsRadios3" name="status" type="radio">
                        <label for="optionsRadios3">New Supplier</label>
                       
                           </div>
                 <div class="supplier">
                        <select class="form-control" name="type" id="add_status_type">
                         <option value="" selected="">Select supplier</option>
                         <option value="2">Supplier1</option>
                         <option value="2">Supplier2</option>
                              
                         </select>
                 </div>
                
               
                    
                </div>
                    <div class="exist_field"  style="display:none">
                    <div class="form-group">
                    <label for="name"> Supplier Name</label>
                    <input id="name" class="form-control" placeholder="Supplier Name" name="name" value="" type="text">
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
                                                   
                                                      <td><span>Select Product</span></td>
                                                       <td><span>Actual Quantity</span></td>
                                                       <td><span>Actual Pieces</span></td>
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
                                            </div>
                                                    </td>
                                                      <td class="col-md-4">
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
                                            </div>
                                                    </td>
                                                      <td class="col-md-4">
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
                                            </div>
                                                    </td>
                                                      <td class="col-md-4">
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
                                            </div>
                                                    </td>
                                                      <td class="col-md-4">
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
                                                        <div class="form-group">
                       
                    <input id="pieces" class="form-control" placeholder="Pieces" name="pieces" value="" type="text">
              
                    </div>
                                                    </td>
                                                      <td class="col-md-1">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                    <td class="col-md-1">
                                                          <div class="row">
                                            <div class="form-group col-md-12">
                                                <input type="text" class="form-control" value="price" id="price">
                                               
                                            </div>
                                            <!--                  
                                            <div class="form-group col-md-6 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div> -->
                                            </div>
                                                    </td>
                                                      <td class="col-md-4">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                             
                                                
                                                             </tbody>
                                        </table>
                        </div>
                    
                                              
                                        
                                               
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
                        <div class="row col-md-4">  
                 <div class="form-group">
  <label for="orderfor">Order For:</label>
  <select class="form-control" id="orderfor">
    <option>Warehouse</option>
    <option>Customer</option>
   

  </select>
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
                    <div class="form-group">
                        
                    <label for="price">Total Price</label>
                    <input id="price" class="form-control" placeholder="Total Price" name="price" value="" type="text">
               
            </div>
                      <div class="form-group">
                        <label for="cp">Vehicle Name </label>
                        <input id="cp" class="form-control" placeholder="Vehicle Name" name="cp" value="" type="text">
       
                </div>
               <div class="form-group col-md-4 targetdate">
                        
                    <label for="date">Estimated Delivery Date</label>
                   <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="date" class="form-control" id="datepickerDate1">
                    </div>
               
            </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
			<label for="inquiry_remark">Remark</label>
                        <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                    </div>
                 <div >
                  <button type="button" class="btn btn-primary form_button_footer" >Save and Send SMS</button>

                    
                   
                </div>
                                    
              
                
                <hr>
                <div >
                    <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                    
                    <a href="purchaseorder_advise.php" class="btn btn-default form_button_footer">Back</a>
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
    $("#optionsRadios1").click(function(){
        $(".exist_field").hide();
        $(".customer_select").show();
    });
    $("#optionsRadios3").click(function(){
        $(".exist_field").show();
        $(".customer_select").hide();
    });
    $("#optionsRadios1").click(function(){
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
</script>
<script>

$('#datepickerDate').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDateComponent').datepicker();
 
$('#datepickerDate1').datepicker({
		  format: 'mm-dd-yyyy'
		});
 $('#datepickerDate1Component').datepicker();
</script>
</body>
</html>