<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>BookOurTrucks-Commission</title>
	
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
					<!--	<li class="mobile-search">
							<a class="btn">
								<i class="fa fa-search"></i>
							</a>
							
							<div class="drowdown-search">
								<form role="search">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Search...">
										<i class="fa fa-search nav-search-icon"></i>
									</div>
								</form>
							</div>
							
						</li>
						<li class="dropdown hidden-xs">
							<a class="btn dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-warning"></i>
								<span class="count">8</span>
							</a>
							<ul class="dropdown-menu notifications-list">
								<li class="pointer">
									<div class="pointer-inner">
										<div class="arrow"></div>
									</div>
								</li>
								<li class="item-header">You have 6 new notifications</li>
								<li class="item">
									<a href="#">
										<i class="fa fa-comment"></i>
										<span class="content">New comment on ‘Awesome P...</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item">
									<a href="#">
										<i class="fa fa-plus"></i>
										<span class="content">New user registration</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item">
									<a href="#">
										<i class="fa fa-envelope"></i>
										<span class="content">New Message from George</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item">
									<a href="#">
										<i class="fa fa-shopping-cart"></i>
										<span class="content">New purchase</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item">
									<a href="#">
										<i class="fa fa-eye"></i>
										<span class="content">New order</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item-footer">
									<a href="#">
										View all notifications
									</a>
								</li>
							</ul>
						</li>
						<li class="dropdown hidden-xs">
							<a class="btn dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-envelope-o"></i>
								<span class="count">16</span>
							</a>
							<ul class="dropdown-menu notifications-list messages-list">
								<li class="pointer">
									<div class="pointer-inner">
										<div class="arrow"></div>
									</div>
								</li>
								<li class="item first-item">
									<a href="#">
										<img src="img/samples/messages-photo-1.png" alt=""/>
										<span class="content">
											<span class="content-headline">
												George Clooney
											</span>
											<span class="content-text">
												Look, just because I don't be givin' no man a foot massage don't make it 
												right for Marsellus to throw...
											</span>
										</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item">
									<a href="#">
										<img src="img/samples/messages-photo-2.png" alt=""/>
										<span class="content">
											<span class="content-headline">
												Emma Watson
											</span>
											<span class="content-text">
												Look, just because I don't be givin' no man a foot massage don't make it 
												right for Marsellus to throw...
											</span>
										</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item">
									<a href="#">
										<img src="img/samples/messages-photo-3.png" alt=""/>
										<span class="content">
											<span class="content-headline">
												Robert Downey Jr.
											</span>
											<span class="content-text">
												Look, just because I don't be givin' no man a foot massage don't make it 
												right for Marsellus to throw...
											</span>
										</span>
										<span class="time"><i class="fa fa-clock-o"></i>13 min.</span>
									</a>
								</li>
								<li class="item-footer">
									<a href="#">
										View all messages
									</a>
								</li>
							</ul>
						</li>
						<li class="hidden-xs">
							<a class="btn">
								<i class="fa fa-cog"></i>
							</a>
						</li>-->
						<li class="dropdown profile-dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<img src="img/samples/scarlet-159.png" alt=""/>
								<span class="hidden-xs">Admin</span> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								
								<li><a href="#"><i class="fa fa-envelope-o"></i>Change Password</a></li>
								<li><a href="#"><i class="fa fa-power-off"></i>Logout</a></li>
							</ul>
						</li>
						<li class="hidden-xxs">
							<a class="btn">
								<i class="fa fa-power-off"></i>
							</a>
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
								<!--	<li class="active">
										<a href="index.php">
											<i class="fa fa-dashboard"></i>
											<span>Dashboard</span>
											<span class="label label-info label-circle pull-right">28</span>
										</a>
									</li>-->
                                                                <li class="active">
										<a href="users.php">
											<i class="fa fa-user"></i>
											<span>Users</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="index.php">
											<i class="fa fa-file"></i>
											<span>Scripts</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="city.php">
											<i class="fa fa-map-marker"></i>
											<span>City</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="index.php">
											<i class="fa fa-envelope-o"></i>
											<span>Requests</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="index.php">
											<i class="fa fa-history"></i>
											<span>Trade History</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
									</li>
                                                                        <li >
										<a href="commission.php">
											<i class="fa fa-flag-o"></i>
											<span>Commission Reports</span>
											<span class="label label-info label-circle pull-right"></span>
										</a>
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
										<li class="active"><span>Commission Reports</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Commission Reports</h1>
										<div class="filter-block pull-right">
												<div class="form-group pull-left">
													<input class="form-control" placeholder="Search..." type="text">
													<i class="fa fa-search search-icon"></i>
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
                                <thead >
                                    <tr >
                                        <th class="text-center" class="col-md-1">#</th>
                                        <th class="text-center">Date of Transaction</th>                                        
                                        <th class="text-center">Transaction Id</th>
                                        <th class="text-center">Amount Of Commission</th>                                                           
                                        
                                    </tr>
                                </thead>
                                <tbody class="text-center">                    


                                        <tr>
                                        <td class="col-md-1">1</td>
                                        <td>03/04/2015</td>
                                        <td>Id001</td>
                                        <td><i class="fa fa-inr"></i>
 550</td>
                                       
                                        
                                    </tr>
                                                                        <tr>
                                        <td class="col-md-1">2</td>
                                        <td>03/04/2015</td>
                                        <td>Id002</td>
                                        <td><i class="fa fa-inr"></i>
 550</td>
                                       
                                        
                                    </tr>
                                    
                                    <tr>
                                        <td class="col-md-1">3</td>
                                        <td>03/04/2015</td>
                                        <td>Id003</td>
                                        <td><i class="fa fa-inr"></i>
 550</td>
                                       
                                        
                                    </tr>
                                    
                                    <tr>
                                        <td class="col-md-1">4</td>
                                        <td>03/04/2015</td>
                                        <td>Id004</td>
                                        <td><i class="fa fa-inr"></i>
 550</td>
                                       
                                        
                                    </tr>
                                    
                                    <tr>
                                        <td class="col-md-1">5</td>
                                        <td>03/04/2015</td>
                                        <td>Id005</td>
                                        <td><i class="fa fa-inr"></i>
 550</td>
                                       
                                        
                                    </tr>
                                                                        
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
	
</body>
</html>