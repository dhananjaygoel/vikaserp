<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Vikas Associate Order Automation System</title>
	
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
    <link rel="stylesheet" href="css/libs/fullcalendar.css" type="text/css" />
    <link rel="stylesheet" href="css/libs/fullcalendar.print.css" type="text/css" media="print" />
    <link rel="stylesheet" href="css/compiled/calendar.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="css/libs/morris.css" type="text/css" />
	<link rel="stylesheet" href="css/libs/daterangepicker.css" type="text/css" />
	<link rel="stylesheet" href="css/libs/jquery-jvectormap-1.2.2.css" type="text/css" />
	
	<!-- Favicon -->
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon" />

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
				<div id="content-wrapper" class="dashboardwrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<ol class="breadcrumb">
										<li><a href="#">Home</a></li>
										<li class="active"><span>Dashboard</span></li>
									</ol>
									<div class="clearfix">
									<h1 class="pull-left">Dashboard</h1>
                                                                        
                                                                        </div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="orders.php"><div class="main-box infographic-box">
										<i class="fa fa-user red-bg"></i>
                                                                                <span class="headline">Total Order </span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												2562
											</span>
										</span>
									</div></a>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="orders.php"><div class="main-box infographic-box">
										<i class="fa fa-shopping-cart emerald-bg"></i>
                                                                                <span class="headline">Pending Order</span>
										<span class="value">
											<span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
												658
											</span>
										</span>
									</div></a>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="enquiry.php"><div class="main-box infographic-box">
										<i class="fa fa-money green-bg"></i>
                                                                                <span class="headline">Pending Inquiries</span>
										<span class="value">
											<span class="timer" data-from="30" data-to="658" data-speed="800" data-refresh-interval="30">
												123
											</span>
										</span>
									</div></a>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="enquiry.php"><div class="main-box infographic-box">
										<i class="fa fa-eye yellow-bg"></i>
                                                                                <span class="headline">Total Inquiries </span>
										<span class="value">
											<span class="timer" data-from="539" data-to="12526" data-speed="1100">
												12526
											</span>
										</span>
                                    					</div></a>
								</div>
                                                            	<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="delivery_orders.php"><div class="main-box infographic-box">
										<i class="fa  fa-tasks red-bg"></i>
                                                                                <span class="headline">Total Delivery Order</span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												2Ton
											</span>
										</span>
									</div></a>
								</div>
                                                            	<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="delivery_orders.php"><div class="main-box infographic-box">
										<i class="fa fa-archive emerald-bg"></i>
                                                                                <span class="headline">Pending Delivery Order</span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												1Ton
                                                                                               

											</span>
										</span>
									</div></a>
								</div>
                                                            	<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="delivery_orders_challan.php"><div class="main-box infographic-box">
										<i class="fa fa-desktop green-bg"></i>
                                                                                <span class="headline">Total Challan</span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												3Ton

											</span>
										</span>
									</div></a>
								</div>
                                                            	<div class="col-lg-3 col-sm-6 col-xs-12">
									<a class="indexlink" href="purchaseorders.php"><div class="main-box infographic-box">
										<i class="fa fa-file-text-o yellow-bg"></i>
                                                                                <span class="headline">Total Purchase Order</span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												4Ton
                                                                                                

											</span>
										</span>
									</div></a>
								</div>
							</div>
							<br/>
                                                    <div class="row text-center ">
                                                        <div class="col-md-12">
                                                            
											<a href="add_order.php" class="btn btn-primary btn-lg text-center button_indexright">
												<i class="fa fa-plus-circle fa-lg"></i> Place Order
											</a>
										
                                                                   
											<a href="add_inquiry.php" class="btn btn-primary btn-lg text-center ">
												<i class="fa fa-plus-circle fa-lg"></i> Add Inquiry
											</a>
										
                                                        </div>
                                                    </div>
							<br/>
                                                        	<br/>
				
					
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
	<script src="js/jquery-ui.custom.min.js"></script>
	<script src="js/fullcalendar.min.js"></script>
	<script src="js/jquery.slimscroll.min.js"></script>
	<script src="js/raphael-min.js"></script>
	<script src="js/morris.min.js"></script>
	<script src="js/moment.min.js"></script>
	<script src="js/daterangepicker.js"></script>
	<script src="js/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="js/jquery-jvectormap-world-merc-en.js"></script>
	<script src="js/gdp-data.js"></script>
	<script src="js/flot/jquery.flot.js"></script>
	<script src="js/flot/jquery.flot.min.js"></script>
	<script src="js/flot/jquery.flot.pie.min.js"></script>
	<script src="js/flot/jquery.flot.stack.min.js"></script>
	<script src="js/flot/jquery.flot.resize.min.js"></script>
	<script src="js/flot/jquery.flot.time.min.js"></script>
	<script src="js/flot/jquery.flot.threshold.js"></script>
	<script src="js/jquery.countTo.js"></script>
	
        <script src="js/moment.min.js"></script>
         <script src="js/bootstrap-datepicker.js"></script>
	<!-- theme scripts -->
	<script src="js/scripts.js"></script>
	<script src="js/pace.min.js"></script>
	  <script>
            $(function($) {
		$('#datepickerDate').datepicker({
		  format: 'dd-mm-yyyy'
		});

		$('#datepickerDateComponent').datepicker();
                });
                  $(function($) {
		$('#datepickerDate1').datepicker({
		  format: 'dd-mm-yyyy'
		});

		$('#datepickerDateComponent').datepicker();
                });

        </script>
	<!-- this page specific inline scripts -->
	<script>
	$(document).ready(function() {
		
		/* initialize the external events
		-----------------------------------------------------------------*/
	
		$('#external-events div.external-event').each(function() {
		
			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};
			
			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);
			
			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});
			
		});
	
	
		/* initialize the calendar
		-----------------------------------------------------------------*/
		
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		var calendar = $('#calendar').fullCalendar({
			header: {
				left: '',
				center: 'title',
				right: 'prev,next'
			},
			isRTL: $('body').hasClass('rtl'), //rtl support for calendar
			selectable: true,
			selectHelper: true,
			select: function(start, end, allDay) {
				var title = prompt('Event Title:');
				if (title) {
					calendar.fullCalendar('renderEvent',
						{
							title: title,
							start: start,
							end: end,
							allDay: allDay
						},
						true // make the event "stick"
					);
				}
				calendar.fullCalendar('unselect');
			},
			editable: true,
			droppable: true, // this allows things to be dropped onto the calendar !!!
			drop: function(date, allDay) { // this function is called when something is dropped
			
				// retrieve the dropped element's stored Event Object
				var originalEventObject = $(this).data('eventObject');
				
				// we need to copy it, so that multiple events don't have a reference to the same object
				var copiedEventObject = $.extend({}, originalEventObject);
				
				// assign it the date that was reported
				copiedEventObject.start = date;
				copiedEventObject.allDay = allDay;
				
				// copy label class from the event object
				var labelClass = $(this).data('eventclass');
				
				if (labelClass) {
					copiedEventObject.className = labelClass;
				}
				
				// render the event on the calendar
				// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
				$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
				
				// is the "remove after drop" checkbox checked?
				if ($('#drop-remove').is(':checked')) {
					// if so, remove the element from the "Draggable Events" list
					$(this).remove();
				}
				
			},
			buttonText: {
				prev: '<i class="fa fa-chevron-left"></i>',
				next: '<i class="fa fa-chevron-right"></i>'
			},
			events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1),
					className: 'label-success'
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false,
					className: 'label-danger'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 30),
					allDay: false,
					className: 'label-info'
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 12, 0),
					end: new Date(y, m, d, 14, 0),
					allDay: false,
					className: 'label-success'
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false,
					className: 'label-info'
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/',
					className: 'label-danger'
				}
			]
		});
		
	    $('.conversation-inner').slimScroll({
	        height: '332px',
	        alwaysVisible: false,
	        railVisible: true,
	        wheelStep: 5,
	        allowPageScroll: false
	    });
		
	    //CHARTS
		graphLine = Morris.Line({
			element: 'graph-line',
			data: [
				{period: '2014-01-01', iphone: 2666, ipad: null, itouch: 2647},
				{period: '2014-01-02', iphone: 9778, ipad: 2294, itouch: 2441},
				{period: '2014-01-03', iphone: 4912, ipad: 1969, itouch: 2501},
				{period: '2014-01-04', iphone: 3767, ipad: 3597, itouch: 5689},
				{period: '2014-01-05', iphone: 6810, ipad: 1914, itouch: 2293},
				{period: '2014-01-06', iphone: 5670, ipad: 4293, itouch: 1881},
				{period: '2014-01-07', iphone: 4820, ipad: 3795, itouch: 1588},
				{period: '2014-01-08', iphone: 15073, ipad: 5967, itouch: 5175},
				{period: '2014-01-09', iphone: 10687, ipad: 4460, itouch: 2028},
				{period: '2014-01-10', iphone: 8432, ipad: 5713, itouch: 1791}
			],
			lineColors: ['#ffffff'],
			xkey: 'period',
			ykeys: ['iphone'],
			labels: ['iPhone'],
			pointSize: 3,
			hideHover: 'auto',
			gridTextColor: '#ffffff',
			gridLineColor: 'rgba(255, 255, 255, 0.3)',
			resize: true
		});
	    
		//WORLD MAP
		$('#world-map').vectorMap({
			map: 'world_merc_en',
			backgroundColor: '#ffffff',
			zoomOnScroll: false,
			regionStyle: {
				initial: {
					fill: '#e1e1e1',
					stroke: 'none',
					"stroke-width": 0,
					"stroke-opacity": 1
				},
				hover: {
					"fill-opacity": 0.8
				},
				selected: {
					fill: '#8dc859'
				},
				selectedHover: {
				}
			},
			series: {
				regions: [{
					values: gdpData,
					scale: ['#6fc4fe', '#2980b9'],
					normalizeFunction: 'polynomial'
				}]
			},
			onRegionLabelShow: function(e, el, code){
				el.php(el.php()+' ('+gdpData[code]+')');
			}
		});
		
		$('.infographic-box .value .timer').countTo({});
		
	});
	</script>
	
</body>
</html>