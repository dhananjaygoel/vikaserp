<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Edit Product Size - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Edit Product Size</span></li>
									</ol>
									
								</div>
							</div>
							
							<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
                     

            <div class="main-box-body clearfix">
               
                                                
                <form method="POST" action="" accept-charset="UTF-8" >
                <div class="form-group col-md-4 pending_location">
                    <label for="status">Select Product(Alias) Category</label>
                    
                    <select class="form-control" name="type" id="add_status_type">
                       
                        <option value="2" id="pip"> Pipe</option>
                                                <option value="3" id="struct"> Structure</option>
                                            </select>
                </div>
                    <div class="clearfix"></div>
                    <div class="form-group col-md-4 pending_location">
                    <label for="status">Sub Product Name</label>
                    
                    <select class="form-control" name="type" id="add_status_type">
                       
                        <option value="2" id="sp1"> Sub Product 1</option>
                                                <option value="3" id="sp2"> Sub Product 2</option>
                                            </select>
                </div>
                    <div class="clearfix"></div>
                        <div class="form-group">
                    <label for="size">Alias Name</label>
                    <input id="alias_name" class="form-control" placeholder="Alias Name" name="name" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="size">Product Standard Length (meter)</label>
                    <input id="length" class="form-control" placeholder="Product Standard Length" name="length" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="size">Product Size (meter)</label>
                    <input id="size" class="form-control" placeholder="Product Size" name="size" value="" type="text">
                </div>
               
                    <div class="thick">   
                <div class="form-group ">
                    <label for="thickness">Product Thickness</label>
                    <input id="thickness" class="form-control" placeholder="Product Thickness" name="thickness" value="" type="text">
                </div>
            </div>
                <div class="form-group">
                    <label for="weight">Product Weight (kg)</label>
                    <input id="weight" class="form-control" placeholder="Product Weight" name="weight" value="" type="text">
                </div>
                    <div class="form-group">
                    <label for="difference">Difference</label>
                    <input id="difference" class="form-control" placeholder="Difference" name="difference" value="" type="text">
                </div>
                    

                                    
              
                
                <hr>
                <div >
                    <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                    <a href="product_sub_category.php" class="btn btn-default form_button_footer">Back</a>
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
   $('#add_status_type').change(function(){
         if($('#add_status_type').val()=='2'){
           $(".thick").show();
                     
        }
    });
});
          $(document).ready(function(){     
   $('#add_status_type').change(function(){
         if($('#add_status_type').val()=='3'){
       
            $('.thick').hide();
                      
        }
    });
});



        </script>
</body>
</html>