<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title></title>
	
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

	<!-- google font libraries -->
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
	
	<!-- Favicon -->
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon"/>

	<!--[if lt IE 9]>
		<script src="js/html5shiv.js"></script>
		<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body id="login-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="login-box">
					<div id="login-box-holder">
						<div class="row">
							<div class="col-xs-12">
								<header id="login-header">
									<div id="login-logo">
										<img src="img/logo.png" alt=""/>
									</div>
								</header>
								<div id="login-box-inner">
									<form role="form" action="index.php">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<input class="form-control" type="text" placeholder="Mobile Number">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-key"></i></span>
											<input type="password" class="form-control" placeholder="Password">
										</div>
										<div id="remember-me-wrapper">
											<div class="row">
												<div class="col-xs-6">
													<div class="checkbox-nice">
														<input type="checkbox" id="remember-me" checked="checked" />
														<label for="remember-me">
															Remember me
														</label>
													</div>
												</div>
												<a href="forgot-password.php" id="login-forget-link" class="col-xs-6">
													Forgot password?
												</a>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<button type="submit" class="btn btn-success col-xs-12" onClick="location.href='index.html'">Login</button>
											</div>
										</div>
										
									</form>
								</div>
							</div>
						</div>
					</div>
					
					<div id="login-box-footer">
						<div class="row">
							<div class="col-xs-12">
								Do not have an account? 
								<a href="registration.php">
									Register now
								</a>
							</div>
						</div>
					</div>
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
	
	<!-- this page specific inline scripts -->
	
</body>
</html>