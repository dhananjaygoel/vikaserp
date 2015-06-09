<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Purchase challan - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Edit Purchase challan</span></li>
									</ol>
									
									
								</div>
							</div>
                                                    					
							<div  class="row">
    <div class="col-lg-12">
        <div class="main-box">
                   

            <div class="main-box-body clearfix">
         
                                                
                <form method="POST" action="" accept-charset="UTF-8" >
                 <div class="form-group">
                        <label><b>Bill Date:</b> Edited Date </label>
                    
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
                                                        
                                                        <td class="col-md-2 text-center"><span>Present Shipping</span></td>
                                                        <td class="col-md-1"><span>Unit</span></td>
                                                        <td class="col-md-2"><span>Rate</span></td>
                                                        <td class="col-md-2"><span>Amount</span></td>
                                                      
                                                    </tr>
                                                     <tr>
                                                         <td>
                                                              <div class="form-group">
          
                   Product1
                </div>
                                                         </td>
                                                         <td>
                                                              <div class="form-group">
          
                   <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                </div>
                                                         </td>
                                                                 <td>  <div class="form-group text-center">
                  
                    Shipping1
                </div></td>
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                           <td class="shippingcolumn">
                                                            <div class="row ">
                                            <div class="form-group col-md-12">
                                            <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                            <!--                    
                                            <div class="form-group col-md-2 difference_form">
                                           
                                           <input class="btn btn-primary" type="submit" class="form-control" value="save" >     
                                            </div>-->
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                    Amount1
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
          
                  <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                </div>
                                                         </td>
                                                               <td>  <div class="form-group text-center">
                  
                   Shipping2
                </div></td>
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                            <td class="shippingcolumn">
                                                            <div class="row ">
                                            <div class="form-group col-md-12">
                                            <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                           
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                   Amount2
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
          
                  <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                </div>
                                                         </td>
                                                                  <td>  <div class="form-group text-center">
                  
                   Shipping3
                </div></td>
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                             <td class="shippingcolumn">
                                                            <div class="row ">
                                            <div class="form-group col-md-12">
                                         <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                           
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                   Amount3
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
          
                  <input id="qty" class="form-control" placeholder="Actual Quantity" name="qty" value="" type="text">
                </div>
                                                         </td>
                                                                <td>  <div class="form-group text-center">
                  
                    Shipping4
                </div></td>
                                               <td> <div class="form-group">
                 
                   Unit
                </div></td>
                              <td class="shippingcolumn">
                                                            <div class="row ">
                                            <div class="form-group col-md-12">
                                            <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                            
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                    Amount4
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
                                                                                         <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                            
                                <td>
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                     
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>                         <td class="shippingcolumn">
                                                            <div class="row ">
                                            <div class="form-group col-md-12">
                                        <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                           
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
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
                                                                                     <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                             
                              <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                     
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                             <td class="shippingcolumn">
                                                            <div class="row ">
                                            <div class="form-group col-md-12">
                                         <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                            
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
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
                                                                                      <td>  <div class="form-group">
                  
                    <input id="shipping" class="form-control" placeholder="Present Shipping" name="shipping" value="" type="text">
                </div></td>
                           
                             <td class="col-md-2">
                                                          <div class="form-group ">
                                                        <select class="form-control" name="type" id="add_status_type">
                     
                                                <option value="2">Kg</option>
                                                <option value="3">mm</option>
                                                <option value="3">cm</option>
                                            </select>
                                                          </div>
                                                    </td>
                               <td class="shippingcolumn">
                                                            <div class="row">
                                            <div class="form-group col-md-12">
                                          <input type="text" class="form-control" id="difference" value="" placeholder="Rate">
                                               
                                            </div>
                                           
                                            </div>
                                                    </td>
                                              <td>   <div class="form-group">
                   
                    <input id="amount" class="form-control" placeholder="Amount" name="Amount" value="" type="text">
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
                        <label for="total"><b class="challan">Total</b> $15000</label>
                    
                </div>
                    <div class="form-group">
                        <label for="driver_contact"><b class="challan">Unloading</b></label>
                    <input id="driver_contact" class="form-control" placeholder="unloading" name="loading" value="" type="text">
                </div>
                  
                    <div class="form-group">
                        <label for="loadedby"><b class="challan">Unloaded By</b></label>
                    <input id="loadedby" class="form-control" placeholder="unloaded By" name="loadedby" value="" type="text">
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
                  
                                             <label for="Plusvat"><b class="challan">Plus VAT</b> Yes/No</label>
                </div>
                                                            
                                             <div class="form-group">
                                                 <label for="driver_contact"><b class="challan">VAT Percentage</b> 5%</label>
                   
            
                                                 <!--
                                             <div class="form-group">
                                                 <label for="driver_contact"><b class="challan">VAT</b></label>
                    <input id="driver_contact" class="form-control" placeholder="VAT" name="VAT" value="" type="text">
                </div>
                                                 <div class="form-group">
                    <label for="grandtotal"><b class="challan">Grand Total</b></label>
                    <input id="grandtotal" class="form-control" placeholder="Grand Total" name="grandtotal" value="" type="text">
                </div>-->
                   
                                             </div>                     
                 <div class="form-group">
                        <label for="total"><b class="challan">Grand Total</b> $25000</label>
                   
                </div>
                     <div class="form-group">
                     <label for="inquiry_remark"><b class="challan">Remark</b></label>
                        <textarea class="form-control" id="inquiry_remark" name="inquiry_remark"  rows="3"></textarea>
                    </div>
                                      
                 <button title="SMS would be sent to Relationship Manager" type="button" class="btn btn-primary smstooltip" >Save and Send SMS</button> 

                
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
	<script src="js/bootstrap-editable.min.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/moment.min.js"></script>
	<!-- theme scripts -->
	<script src="js/scripts.js"></script>
	<script src="js/pace.min.js"></script>
	
	<!-- this page specific inline scripts -->
	<script>
	$(document).ready(function(){
		//toggle `popup` / `inline` mode
		$.fn.editable.defaults.mode = 'popup';     
		
		//make username editable
		$('#username').editable();
		$('#username1').editable();
		
		//make username editable
		$('#firstname').editable({
			validate: function(value) {
				if($.trim(value) == '') {
					return 'This field is required';
				}
			}
		});
		
		$('#sex').editable({
			prepend: "not selected",
			source: [
				{value: 1, text: 'Male'},
				{value: 2, text: 'Female'}
			],
			select2: {
				width: 200,
				placeholder: 'Select your sex',
				allowClear: true
			}
		});
		
		$('#status').editable();
		
		$('#group').editable({
			showbuttons: false 
		});   
		
		$('#vacation').editable({
			datepicker: {
				todayBtn: 'linked'
			}
		});
		
		$('#dob').editable();
		
		$('#event').editable({
			placement: 'right',
			combodate: {
				firstItem: 'name'
			}
		});
		
		$('#meeting_start').editable({
			format: 'yyyy-mm-dd hh:ii',    
			viewformat: 'dd/mm/yyyy hh:ii',
			validate: function(v) {
				if(v && v.getDate() == 10) return 'Day cant be 10!';
			},
			datetimepicker: {
				todayBtn: 'linked',
				weekStart: 1
			}
		});
		
		$('#comments').editable({
			showbuttons: 'bottom'
		});
		
		$('#note').editable(); 
		
		$('#pencil').click(function(e) {
			e.stopPropagation();
			e.preventDefault();
			$('#note').editable('toggle');
		});
		
		$('#state').editable({
			source: ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"]
		});
		
		$('#fruits').editable({
			pk: 1,
			limit: 3,
			source: [
				{value: 1, text: 'banana'},
				{value: 2, text: 'peach'},
				{value: 3, text: 'apple'},
				{value: 4, text: 'watermelon'},
				{value: 5, text: 'orange'}
			]
		});
		
		$('#tags').editable({
			inputclass: 'input-large',
			select2: {
				tags: ['html', 'javascript', 'css', 'ajax'],
				tokenSeparators: [",", " "]
			}
		});
		
		var countries = [];
		$.each({"BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BG": "Bulgaria", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BL": "Saint Bartelemey", "BM": "Bermuda", "BN": "Brunei Darussalam", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BR": "Brazil", "BS": "Bahamas", "JE": "Jersey", "BY": "Belarus", "O1": "Other Country", "LV": "Latvia", "RW": "Rwanda", "RS": "Serbia", "TL": "Timor-Leste", "RE": "Reunion", "LU": "Luxembourg", "TJ": "Tajikistan", "RO": "Romania", "PG": "Papua New Guinea", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GG": "Guernsey", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "BZ": "Belize", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "TM": "Turkmenistan", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "RU": "Russian Federation", "EE": "Estonia", "EG": "Egypt", "TK": "Tokelau", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "EU": "Europe", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova, Republic of", "MG": "Madagascar", "MF": "Saint Martin", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "IM": "Isle of Man", "UG": "Uganda", "TZ": "Tanzania, United Republic of", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "FX": "France, Metropolitan", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands (Malvinas)", "FM": "Micronesia, Federated States of", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "CI": "Cote d'Ivoire", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos (Keeling) Islands", "CA": "Canada", "CG": "Congo", "CF": "Central African Republic", "CD": "Congo, The Democratic Republic of the", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syrian Arab Republic", "KG": "Kyrgyzstan", "KE": "Kenya", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "Korea, Republic of", "SI": "Slovenia", "KP": "Korea, Democratic People's Republic of", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "Virgin Islands, British", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Lao People's Democratic Republic", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "A1": "Anonymous Proxy", "TO": "Tonga", "LT": "Lithuania", "A2": "Satellite Provider", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libyan Arab Jamahiriya", "VA": "Holy See (Vatican City State)", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "Virgin Islands, U.S.", "IS": "Iceland", "IR": "Iran, Islamic Republic of", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AN": "Netherlands Antilles", "AQ": "Antarctica", "AP": "Asia/Pacific Region", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique"}, function(k, v) {
			countries.push({id: k, text: v});
		});
		
		$('#country').editable({
			source: countries,
			select2: {
				width: 200,
				placeholder: 'Select country',
				allowClear: true
			}
		});
		
		$('#user .editable').on('hidden', function(e, reason){
			if(reason === 'save' || reason === 'nochange') {
				var $next = $(this).closest('tr').next().find('.editable');
					setTimeout(function() {
						$next.editable('show');
					}, 300); 
			}
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
<script>
$(function() {
    $('.smstooltip').tooltip();
});
</script>
</body>
</html>
