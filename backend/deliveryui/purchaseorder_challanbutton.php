<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Purchase Challan - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Purchase Challan</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left"></h1>
										
										
									</div>
								</div>
							</div>
							
							<div class="row">
    <div class="col-lg-12">
        <div class="main-box">
            <header class="main-box-header clearfix">
                <h2><i class="fa fa-dashboard"></i> &nbsp;Purchase Challan</h2>
            </header>            

                <div class="main-box-body clearfix">
                <hr>
                                                
                <form method="POST" action="" accept-charset="UTF-8" >
                 <div class="form-group">
                        <label><b>Bill Date:</b> 12 May,2015 </label>
                    
                </div>
                      <div class="form-group">
                        <label><b>Bill Number:</b> Mum01 </label>
                    
                </div>
                    <div class="form-group">
                        <label><b>Party Name:</b> Party1 </label>
                    
                </div>
                    <div class="form-group">
                        <label><b>Serial Number:</b>PO/ May05/02/01/01</label>
                    
                </div>
               <div class="table-responsive">
                                                  <table id="table-example" class="table table_deliverchallan serial">
                                                <tbody>
                                                    <tr>
                                                       <td class="col-md-2"><span>Product Name</span></td>
                                                        <td class="col-md-2"><span>Actual Quantity</span></td>
                                                        
                                                 
                                                        <td><span>Unit</span></td>
                                                        <td class="col-md-2"><span>Rate</span></td>
                                                        <td class="col-md-2"><span>Amount</span></td>
                                                        <td class="col-md-2"><span>Present Shipping</span></td>
                                                    </tr>
                                                     <tr>
                                                         <td>
                                                              <div class="form-group">
          
                   Product1
                </div>
                                                         </td>
                                                         <td>
                                                              <div class="form-group">
          
                   Actual Quantity
                </div>
                                                         </td>
                               
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                                                        <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                                                    </tr>
                                                       <tr>
                                                         <td>
                                                              <div class="form-group">
          
                   Product1
                </div>
                                                         </td>
                                                         <td>
                                                              <div class="form-group">
          
                   Actual Quantity
                </div>
                                                         </td>
                               
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                                                        <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                                                    </tr>
                                                   
                                                 <tr>
                                                         <td>
                                                              <div class="form-group">
          
                   Product1
                </div>
                                                         </td>
                                                         <td>
                                                              <div class="form-group">
          
                   Actual Quantity
                </div>
                                                         </td>
                               
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                                                        <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                                                    </tr>
                                                   
                                                       <tr>
                                                         <td>
                                                              <div class="form-group">
          
                   Product1
                </div>
                                                         </td>
                                                         <td>
                                                              <div class="form-group">
          
                   Actual Quantity
                </div>
                                                         </td>
                               
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                                                        <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
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
                                                         <td>
                                                   <div class=" form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                         <td>
                                                              <div class="form-group">
          
                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
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
                                                    </td>                         <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
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
                                                          <td>
                                                   <div class=" form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                         <td>
                                                              <div class="form-group">
          
                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                </div>
                                                         </td>
                             
                              <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                        <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
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
                                                          <td>
                                                   <div class=" form-group searchproduct">
                                                    <input class="form-control" placeholder="Product name " type="text">
                                                        <i class="fa fa-search search-icon"></i>
                                                        </div>
                                                    </td>
                                                         <td>
                                                              <div class="form-group">
          
                    <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                </div>
                                                         </td>
                           
                             <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                       <option value="" selected="">Unit</option>
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                                                        <td>   <div class="form-group">
                   
                    <input id="rate" class="form-control" placeholder="Rate" name="rate" value="" type="text">
                </div></td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
                </div></td>
                                                        <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                 </div>
                
                 <div class="form-group">
                        <label><b>Total Actual Quantity:</b> 500</label>
                    
                </div>
                    <div class="form-group">
                        <label for="vehicle_name"><b class="challan">Vehicle Name</b></label>
                    <input id="vehicle_name" class="form-control" placeholder="Vehicle Name" name="Discount" value="" type="text">
                </div>
                  <div class="form-group">
                        <label for="vehicle_name"><b class="challan">Discount</b></label>
                    <input id="vehicle_name" class="form-control" placeholder="Discount" name="Discount" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="driver_name"><b class="challan">Freight</b></label>
                    <input id="driver_name" class="form-control" placeholder="Freight " name="Freight" value="" type="text">
                </div>
                    <div class="form-group">
                        <label for="driver_contact"><b class="challan">Loading</b></label>
                    <input id="driver_contact" class="form-control" placeholder="loading" name="loading" value="" type="text">
                </div>
                  
                    <div class="form-group">
                        <label for="loadedby"><b class="challan">Loaded By</b></label>
                    <input id="loadedby" class="form-control" placeholder="Loaded By" name="loadedby" value="" type="text">
                </div>
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour </b></label>
                    <input id="labour" class="form-control" placeholder="Labour" name="labour" value="" type="text">
                </div>
                <div class="form-group">
                    <label for="billno"><b class="challan">Bill Number</b></label>
                    <input id="billno" class="form-control" placeholder="Bill Number" name="billno" value="" type="text">
</div>
                    <div class="form-group">
                        <label for="total"><b class="challan">Total</b></label>
                    <input id="total" class="form-control" placeholder="Total" name="total" value="" type="text">
                </div>
                 
                                             <div class="form-group">
                  
                                                 <input id="includevat" type="checkbox" name="Include VAT" value="includevat"><b class="challan">Include VAT</b>
                </div>
                                             <div id="vatdetails">                     
                                             <div class="form-group">
                                                 <label for="driver_contact"><b class="challan">VAT Percentage</b></label>
                    <input id="driver_contact" class="form-control" placeholder="VAT Percentage" name="VAT Percentage" value="" type="text">
                </div>
                           
                   
                                             </div>                     
                     
                                      
             <button type="button" class="btn btn-primary form_button_footer" >Save and Send SMS</button>

                
                <hr>
                 
                      <div>
                    <button type="button" class="btn btn-primary" >Submit</button>
                
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

 <script type="text/javascript">
        $(document).ready(function () {
    $('#includevat').change(function () {
        if (this.checked) 
        //  ^
           $('#vatdetails').show();
        else 
            $('#vatdetails').hide();
    });
});
        </script>
<script>
            $(document).ready(function(){
		//toggle `popup` / `inline` mode
		$.fn.editable.defaults.mode = 'popup';     
		
		//make username editable
		$('#bill').editable();
		$('#user').editable();
		$('#edit').editable();
                $('#labours').editable();
                $('#rate').editable();
		
                });
              
        </script> 
        <script src="js/bootstrap-editable.min.js"></script>
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
        
    });
     
   
  

});
</script> 
</body>
</html>