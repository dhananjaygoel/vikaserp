<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Purchase Advise - Vikas Associate Order Automation System</title>
	
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
										<li class="active"><span>Purchase Advice</span></li>
									</ol>
									
									<div class="clearfix">
										<h1 class="pull-left">Purchase Advice</h1>
										
										<div class="pull-right top-page-ui">
											<a href="create_purchase_advise.php" class="btn btn-primary pull-right">
												<i class="fa fa-plus-circle fa-lg"></i> Create Purchase Advice Independently
											</a>
                                                                                       <div class="form-group pull-right">
                                                                                        <div class="col-md-12">
                                                                                        <select class="form-control" id="user_filter" name="user_filter">
                                                                                    <option value="" selected="">Status</option>
                                                                                    <option value="2">Delivered</option>
                                                                                    <option value="2">Inprocess</option>
                                                                                    
                                                                                     
                                                                                    
                                                                                                                
                                                                                </select>
                                                                                        </div>
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
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        
                                        <th>Serial Number</th>
                                       
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>                    


                                        <tr>
                                        <td>1</td>
                                        <td>09 Apr 2015</td>
                                     
                                        <td>PO/Apr15/04/01</td>                                        
                                        
                                        <td class="text-center">
                                            
                                             <a href="view_purchaseadvice.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                              <a href="edit_purchaseadvice.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                            
                                              <a href="purchaseorder_challanbutton.php" class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="" class="table-link" title="print" data-toggle="modal" data-target="#myModal1">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        
                                    </tr>
                                                                        <tr>
                                        <td>2</td>
                                        <td>08 Apr 2015</td>
                                 
                                        <td>PO/Apr15/04/01</td>                                        
                                       
                                        <td class="text-center">
                                            
                                           <a href="view_purchaseadvice.php" class="table-link" title="view">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                              <a href="edit_purchaseadvice.php" class="table-link" title="edit">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            
                                           
                                             <a href="purchaseorder_challanbutton.php" class="table-link" title="purchase challan" >
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                              <a href="" class="table-link" title="print" data-toggle="modal" data-target="#myModal1">
                                                <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
							<i class="fa fa-print fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a href="#" class="table-link danger" data-toggle="modal" data-target="#myModal" title="delete">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        
                                    </tr>
                                    
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                    
                                   <div class="modal-body">
                                         <div class="delete">
                                             <div><b>UserID:</b> 9988776655</div>
                                             <div class="pwd">
                                                 <div class="pwdl"><b>Password:</b></div>
                                                 <div class="pwdr"><input class="form-control" placeholder="" type="text"></div>
                                             
                                             </div>
                                             <div class="clearfix"></div>
                                             <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                         
                                           
                                         </div>
                                         
                                    </div>          
                                    <div class="modal-footer">
                                    
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                    </div>
                                    </div>
                                    </div>
                                    </div>    
                                
                                <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                    
                                                
                                                
                                     <div class="modal-body">
                                         <form method="POST" action="" accept-charset="UTF-8" >
                
                   
                        <div class="row print_time"> 
                     <div class="col-md-12"> Print By <br> 05:00 PM</div> 
                 </div>
                <div class="checkbox">
                    <label><input type="checkbox" value="" ><span title="SMS would be sent to Relationship Manager" class="checksms smstooltip">Send SMS</span></label>
                </div>
            
             
                 <div class="clearfix"></div>
                
                <hr>
                <div >
                    <button type="button" class="btn btn-primary form_button_footer" >Print</button>
                    
                    <a href="purchaseorder_advise.php" class="btn btn-default form_button_footer">Cancel</a>
                </div>
                
                <div class="clearfix"></div>
                </form>
                                        
                                         
                                    </div>           
                                <!--    <div class="modal-footer">
                                    
                                    <button type="button" class="btn btn-primary">No</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Yes</button>
                                    </div>-->
                                    </div>
                                    </div>
                                    </div> 
                                                                        
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
       <script>
$(function() {
    $('.smstooltip').tooltip();
});
</script>  
</body>
</html>