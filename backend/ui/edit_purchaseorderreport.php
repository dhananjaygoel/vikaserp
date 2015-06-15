<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Edit Purchase Orders - Vikas Associate Order Automation System</title>
	
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
		<header class="navbar" id="header-navbar">
			<div class="container">
				<a href="index.php" id="logo" class="navbar-brand">
					<img src="img/logo1.png" alt="" class="normal-logo logo-white"/>
					<img src="img/logo-black.png" alt="" class="normal-logo logo-black"/>
					<img src="img/logo-small.png" alt="" class="small-logo hidden-xs hidden-sm hidden"/>
				</a>
				
				<div class="clearfix">
				<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="fa fa-bars"></span>
				</button>
				
				<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
					<ul class="nav navbar-nav pull-left">
						<li>
							<a class="btn" id="make-small-nav">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>
				</div>
				
				<div class="nav-no-collapse pull-right" id="header-nav">
					<ul class="nav navbar-nav pull-right">
					
						<li class="dropdown profile-dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="img/samples/scarlet-159.png" alt=""/>
								<span class="hidden-xs"> Admin</span> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								
								<li><a href="#"><i class="fa fa-envelope-o"></i>Change Password</a></li>
								<li><a href="#"><i class="fa fa-power-off"></i>Logout</a></li>
							</ul>
						</li>
						
					</ul>
				</div>
				</div>
			</div>
		</header>
		<div id="page-wrapper" class="container">
			<div class="row">
				<div id="nav-col">
					<section id="col-left" class="col-left-nano">
						<div id="col-left-inner" class="col-left-nano-content">
							
							<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
								<ul class="nav nav-pills nav-stacked">
								
                                                                <li >
										<a href="users.php">
											<i class="fa fa-user"></i>
											<span>Users</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="customers.php">
											<i class="fa fa-male"></i>
											<span>Customers</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="pendingcustomers.php">
											<i class="fa fa-book"></i>
											<span>Pending customers</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        
                                                                        <li >
										<a href="enquiry.php">
											<i class="fa fa-dashboard"></i>
											<span>Inquiry</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li>
										<a href="#" class="dropdown-toggle">
											<i class="fa fa-shopping-cart"></i>
											<span>Order</span>
											<i class="fa fa-chevron-circle-right drop-icon"></i>
										</a>
										<ul class="submenu">
											<li>
												<a href="orders.php" >
													Order
												</a>
											</li>
											<li>
												<a href="delivery_orders.php">
													Delivery Order
												</a>
											</li>
                                                                                        <li>
												<a href="delivery_orders_challan.php"  >
													Delivery Challan
												</a>
											</li>
                                                                                        <li>
												<a href="daily_del_orders.php" >
													Pending Delivery Order Report
												</a>
											</li>
                                                                                         <li>
												<a href="delivery_orders_challan_report.php">
													Sales Daybook
												</a>
											</li>
											<li>
												<a href="pending_orders.php" class="active">
													Pending Order Report
												</a>
											</li>
										</ul>
									</li>
                                                                        <li class="active">
										<a href="#" class="dropdown-toggle">
											<i class="fa fa-shopping-cart"></i>
											<span>Purchase Order</span>
											<i class="fa fa-chevron-circle-right drop-icon"></i>
										</a>
										<ul class="submenu">
											<li>
												<a href="purchaseorders.php">
													Purchase Order
												</a>
											</li>
											<li>
												<a href="purchaseorder_advise.php">
													Purchase Advice
												</a>
											</li>
                                                                                        <li>
												<a href="purchaseorder_challan.php">
													Purchase Challan
												</a>
											</li>
											<li>
												<a href="purchaseorder_report.php" class="active">
													Purchase Order Report
												</a>
											</li>
                                                                                        <li>
												<a href="purchaseorder_advisereport.php">
													Pending Purchase Advise Report
												</a>
											</li>
                                                                                        <li>
												<a href="purchaseorder_challanreport.php">
													Purchase Daybook
												</a>
											</li>
										</ul>
									</li>
                                                                        <li >
										<a href="#" class="dropdown-toggle">
                                                                                    <i class="fa fa-codepen"></i>
											<span>Product</span>
											<i class="fa fa-chevron-circle-right drop-icon"></i>
										</a>
										<ul  class="submenu">
											<li>
												<a href="product_category.php" class="active">
													Product Category
												</a>
											</li>
											<li>
												<a href="product_sub_category.php">
													Product Sub Category
												</a>
											</li>
											
										</ul>
									</li>
                                                                        
                                                                        <li >
										<a href="#" class="dropdown-toggle">
											<i class="fa fa-thumb-tack"></i>
											<span>Masters Module</span>
											<i class="fa fa-chevron-circle-right drop-icon"></i>
										</a>
										<ul  class="submenu">
											<li>
												<a href="location.php" class="active">
													Delivery Location
												</a>
											</li>
											<li>
												<a href="city.php">
													City
												</a>
											</li>
											<li>
												<a href="state.php">
													State
												</a>
											</li>
                                                                                        <li>
												<a href="unit.php">
													Unit
												</a>
											</li>
										</ul>
									</li>
                                                                        
                                                                       
								</ul>
							</div>
						</div>
					</section>
				</div>
				<div id="content-wrapper"><div class="row">
						<div class="col-lg-12">
							
							<div class="row">
								<div class="col-lg-12">
									<ol class="breadcrumb">
										<li><a href="#">Home</a></li>
										<li class="active"><span>Edit Purchase Order</span></li>
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
                <h2><i class="fa fa-dashboard"></i> &nbsp; Edit Purchase Order </h2>
            </header>            

            <div class="main-box-body clearfix">
                <hr>
                                                
                <form method="POST" action="" accept-charset="UTF-8" >
                   
                    <div class="pendingorder ">
                     <b>Order:</b> Order1
                    </div>
                    
                    <div class="pendingorder">
                     <b>Date:</b> 04 May,2015
                    </div>
                                     <div class="row col-md-3">  
                 <div class="form-group">
  <label for="party">Party Name:</label>
  <select class="form-control">
    <option>Party1</option>
    <option>Party2</option>
   <option>Party3</option>
 <option>Party4</option>
  </select>
</div>
                        </div>
                    <div class="clearfix"></div>
                     <div class="pendingreport_table col-md-12">
                          
                                            <div class="table-responsive">
                                            <table id="table-example" class="table table-hover  ">
                                
                                        
                                            <tbody>  
                                                 <tr class="headingunderline">
                                                    <td><span>Select Category</span></td>
                                                     <td><span>Select Sub Category</span></td>
                                                      <td><span>Select Product(Alias)</span></td>
                                                       <td><span>Quantity</span></td>
                                                        <td><span>Unit</span></td> 
                                                        <td><span>Price</span></td> 
                                                        <td><span>Remark</span></td>
                                                        
                                                </tr>
                                                <tr>
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                     <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                     <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                     <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                     <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
                                                          </div>
                                                    </td>
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="remark" class="form-control" placeholder="Remark" name="remark" value="" type="text">
                                                          </div>
                                                    </td>
                                                    
                                                </tr>
                                                 <tr>
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
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
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
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
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                     <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
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
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
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
                                                    <td class="col-md-2">
                                                         <div class="form-group ">
                                                        <select class="form-control" id="user_filter" name="user_filter">
                         <option value="" selected="">Select Category</option>
                         <option value="2">Pipe</option>
                         <option value="2">Structure</option>
                              
                         </select> 
                                                         </div>
                                                        
                                                    </td>
                                                   <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Sub Category</option>
                                                <option value="2">CRP Pipe</option>
                                                <option value="3">CRP Structure</option>
                                            </select>
                                                          </div>   
                                                    </td>
                                                    <td class="col-md-2">
                                                        <div class="form-group">
                                                          <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Select Product(Alias)</option>
                                                 <option value="1">Other</option>
                                                <option value="2">CRP Pipe 3.0</option>
                                                <option value="3">CRP Structure 3.0</option>
                                            </select>
                                                        </div>
                                                    </td>
                                                       <td class="col-md-1">
                                                        <div class="form-group">
                       
                    <input id="quantity" class="form-control" placeholder="Qnty" name="quantity" value="" type="text">
              
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
                                                      <td class="col-md-2">
                                                          <div class="form-group">
                                                          <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
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
			<label for="inquiry_remark">Remark</label>
                        <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                    </div>

             
                
                <hr>
                <div >
                    <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                    
                    <a href="purchaseorder_report.php" class="btn btn-default form_button_footer">Back</a>
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
					
					<footer id="footer-bar" class="row">
						<p id="footer-copyright" class="col-xs-12">
							Powered by <a href="" target="_blank">AGS Technologies</a>.&copy; 2014
						</p>
					</footer>
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
   
      $('#loc1').change(function(){
         if($('#loc1').val()=='3'){
           $('.locationtext').toggle();
          
        }
       

});

});
</script>
</body>
</html>