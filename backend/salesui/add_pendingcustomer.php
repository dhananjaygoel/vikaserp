<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Customers - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Customers</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Pending Customers</h1>
										
										
									</div>
								</div>
							</div>
							<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2><i class="fa fa-user"></i> &nbsp; Add Pending Customer </h2>
            </header>            

              <div class="main-box-body clearfix">
                <hr>
                                                
                <form method="POST" action="" accept-charset="UTF-8" >
                <div class="form-group">
                    <label for="owner_name">Owner Name<span class="mandatory">*</span></label>
                    <input id="owner_name" class="form-control" placeholder="Owner Name" name="owner_name" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="company_name">Company  Name</label>
                    <input id="company_name" class="form-control" placeholder="Company Name" name="company_name" value="" type="text">
                </div>                                                    
                <div class="form-group">
                    <label for="address1">Address 1</label>
                    <input id="address1" class="form-control" placeholder="Address 1" name="address1" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="address2">Address 2</label>
                    <input id="address2" class="form-control" placeholder="Address 2" name="address2" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="city">City<span class="mandatory">*</span></label>
                    <input id="city" class="form-control" placeholder="City" name="city" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="state">State<span class="mandatory">*</span></label>
                    <input id="state" class="form-control" placeholder="State" name="state" value="" type="text">
                </div>
                   <div class="form-group">
                    <label for="state">Zip</label>
                    <input id="zip" class="form-control" placeholder="Zip" name="zip" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" class="form-control" placeholder="Email" name="email" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="tally">Tally Name<span class="mandatory">*</span></label>
                    <input id="tally" class="form-control" placeholder="Tally Name " name="tally" value="" type="text">
                </div>                           
                    <div class="form-group">
                    <label for="tallycat">Tally Category<span class="mandatory">*</span></label>
                    <input id="tally" class="form-control" placeholder="Tally Category " name="tallycat" value="" type="text">
                </div> 
                      <div class="form-group">
                    <label for="tallysubcat">Tally Subcategory<span class="mandatory">*</span></label>
                    <input id="tally" class="form-control" placeholder="Tally Subcategory " name="tallysubcat" value="" type="text">
                </div> 
                <div class="form-group">
                    <label for="Phone_number">Phone number 1<span class="mandatory">*</span></label>
                    <input id="Phone_number" class="form-control" placeholder="Phone number " name="telephone_number" value="" type="text">
                </div>
                    
                <div class="form-group">
                    <label for="mobile_number">Phone Number 2</label>
                    <input id="mobile_number" class="form-control" placeholder="Phone Number 2" name="mobile_number" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="vat_number">VAT-TIN Number</label>
                    <input id="vat_number" class="form-control" placeholder="VAT-TIN Number" name="vat_number" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="excise_number">Excise Number</label>
                    <input id="excise_number" class="form-control" placeholder="Excise Number" name="excise_number" value="" type="text">
                </div>
                    <div class="form-group col-md-4 del_loc ">
                    <label for="del_loc">Delivery Location:<span class="mandatory">*</span></label>
                        <select class="form-control" id="del_loc">
                          <option>Ex-warehouse</option>
                          <option>Location2</option>
                          <option>Location3</option>
                          <option>Location4</option>
                        </select>
                </div>
                    <div class="clearfix"></div>
                     <div class="form-group">
                    <label for="user_name">User Name</label>
                    <input id="user_name" class="form-control" placeholder="User Name" name="user_name" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="password">Password</label>
                   
                    <input id="password" class="form-control" placeholder=" Password" name="password" value="" type="password">
                </div> 
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                   
                    <input id="password_confirmation" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="" type="password">
                </div>
                     <div class="form-group">
                    <label for="period">Credit Period</label>
                    <input id="period" class="form-control" placeholder="Credit Period" name="period" value="" type="text">
                </div>
                        <div class="form-group col-md-4 del_loc ">
                    <label for="manager">Relationship Manager:</label>
                        <select class="form-control" id="manager">
                          <option>Super Admin</option>
                          <option>Admin1</option>
                          <option>Admin2</option>
                          <option>Admin3</option>
                          <option>Admin4</option>
                          <option>Admin5</option>
                          <option>Admin6</option>
                          <option>Admin7</option>
                        </select>
                </div>
                    <div class="clearfix"></div>
                    <div class="form-group">
				<label>Set Prices</label>
				<br>
					<div class="checkbox-nice">
                                            <input id="checkbox-inl-1" type="checkbox">
					<label for="checkbox-inl-1"> </label>
					  </div>
                                <br>
                                            <div class="category_div col-md-12">
                          
                                            <div class="table-responsive">
                                            <table id="table-example" class="table table-hover ">
                                
                                            <thead>
                                                <tr>

                                                    <th>Category</th>
                                                    <th>Difference</th>

                                                </tr>
                                            </thead>
                                            <tbody>       
                                                <tr>
                                                    <td>Name1</td>
                                                    <td><input class="setprice" type="text" value="" ></td>
                                                </tr>
                                                  <tr>
                                                    <td>Name2</td>
                                                    <td><input class="setprice" type="text" value="" ></td>
                                                </tr>
                                                  <tr>
                                                    <td>Name3</td>
                                                    <td><input class="setprice" type="text" value="" ></td>
                                                </tr>
                                                  <tr>
                                                    <td>Name4</td>
                                                    <td><input class="setprice" type="text" value="" ></td>
                                                </tr>
                                            </tbody>
                                        </table>
                        </div>
                    
                                              
                                        
                                               
					</div>
            
												
					</div>
                    

                                    
               
                    <div class="clearfix"></div>
                    <button type="button" class="btn btn-primary form_button_footer" >Save and Send SMS</button>
                <hr>
                <div>
                    <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                    <a href="customers.php" class="btn btn-default form_button_footer">Back</a>
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
	
	<!-- this page specific inline scripts -->
	<script>
$(document).ready(function(){
    $("#checkbox-inl-1").click(function(){
       
        $(".category_div").toggle("slow");
        
    });
});
</script>	
</body>
</html>