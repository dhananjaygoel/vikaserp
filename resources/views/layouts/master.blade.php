<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="_token" content="<?php echo csrf_token(); ?>" id="csrf_token" />
        <base id="baseurl" name="{{url()}}">
        <title>@yield('title')</title>
        @yield('meta')

        <!-- bootstrap -->
        {!! HTML::style('/resources/assets/css/bootstrap/bootstrap.min.css') !!}
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">-->

        <!-- RTL support - for demo only -->
        {!! HTML::style('/resources/assets/css/bootstrap/bootstrap.min.css') !!}
        <!--<script src="js/demo-rtl.js"></script>-->
        <!--
        If you need RTL support just include here RTL CSS file <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
        And add "rtl" class to <body> element - e.g. <body class="rtl">
        -->

        <!-- libraries -->
        {!! HTML::style('/resources/assets/css/libs/font-awesome.css') !!}
        {!! HTML::style('/resources/assets/css/libs/nanoscroller.css') !!}


        <!-- global styles -->
        {!! HTML::style('/resources/assets/css/compiled/theme_style.css') !!}

        <!-- this page specific styles -->
        {!! HTML::style('/resources/assets/css/libs/fullcalendar.css') !!}
        {!! HTML::style('/resources/assets/css/libs/fullcalendar.print.css') !!}
        {!! HTML::style('/resources/assets/css/compiled/calendar.css') !!}
        {!! HTML::style('/resources/assets/css/libs/morris.css') !!}
        {!! HTML::style('/resources/assets/css/libs/datepicker.css') !!}
        {!! HTML::style('/resources/assets/css/libs/daterangepicker.css') !!}
        {!! HTML::style('/resources/assets/css/libs/jquery-jvectormap-1.2.2.css') !!}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">                   
        {!! HTML::style('/resources/assets/css/custom_style/custom_styles.css') !!}
        {!! HTML::style('/resources/assets/css/custom_style/custom_media_query.css') !!}

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js" ></script>-->
        
        <!-- google font libraries -->
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
        <!-- Favicon -->
        <link href="{{ asset('/resources/assets/img/favicon.png') }}" rel="shortcut icon" type="image/x-icon">
        <!--[if lt IE 9]>
                <script src="js/html5shiv.js"></script>
                <script src="js/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">
            .select2-container--default .select2-selection--single .select2-selection__rendered{
                color: #555;
                line-height: 33px;
            }
            .select2-container--default .select2-selection--single{
                border: 1px solid #e7ebee;
                border-radius: 2px;
            }
            .select2-container .select2-selection--single{
                height: 35px;
            }
        </style>

    </head>
    <body>
        <div id="theme-wrapper">
            <span id="baseurl" style="display: none;">{{url()}}</span>
            @include('layouts.header')
            <div id="page-wrapper" class="container nav-small">

                <div class="row">
                    @include('layouts.sidebar-left')
                    <div id="content-wrapper">
                        @yield('content')
                        @include('layouts.footer')
                    </div>
                </div>
            </div>
        </div>



        <!-- this page specific scripts -->

<!--        <script src="js/bootstrap-datepicker.js"></script>
 theme scripts
<script src="js/scripts.js"></script>
<script src="js/pace.min.js"></script>-->
        <!-- global scripts -->
        {!! HTML::script('/resources/assets/js/demo-skin-changer.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.js') !!}
        {!! HTML::script('/resources/assets/js/bootstrap.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.nanoscroller.min.js') !!}
        {!! HTML::script('/resources/assets/js/demo.js') !!}
        <!-- this page specific scripts -->

        <!-- Bootbox Js -->
        <!--{!! HTML::script('/resources/assets/js/bootbox.min.js') !!}-->
        {!! HTML::script('/resources/assets/js/jquery_block_UI.js') !!}

        <!-- Bootstrap spinner Js -->
        {!! HTML::script('/resources/assets/js/bootstrap-datepicker.js') !!}

        {!! HTML::script('/resources/assets/js/jquery-ui.custom.min.js') !!}
        <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
        {!! HTML::script('/resources/assets/js/fullcalendar.min.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.slimscroll.min.js') !!}
        {!! HTML::script('/resources/assets/js/raphael-min.js') !!}
        {!! HTML::script('/resources/assets/js/morris.min.js') !!}

        {!! HTML::script('/resources/assets/js/jquery-jvectormap-1.2.2.min.js') !!}
        {!! HTML::script('/resources/assets/js/jquery-jvectormap-world-merc-en.js') !!}
        {!! HTML::script('/resources/assets/js/gdp-data.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.min.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.pie.min.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.stack.min.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.resize.min.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.time.min.js') !!}
        {!! HTML::script('/resources/assets/js/flot/jquery.flot.threshold.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.countTo.js') !!}





        <!--{!! HTML::script('/resources/assets/js/jquery.spinner.min.js') !!}-->



        {!! HTML::script('/resources/assets/js/moment.min.js') !!}
        {!! HTML::script('/resources/assets/js/daterangepicker.js') !!}

        <!-- theme scripts -->
        {!! HTML::script('/resources/assets/js/scripts.js') !!}
<!--        {!! HTML::script('/resources/assets/js/pace.min.js') !!}-->
        <!-- this page specific inline scripts -->

        <!-- RTL support - for demo only -->
        <!--{!!  HTML::script('/resources/assets/js/demo-rtl.js') !!}-->
        <!-- Confirm Exit JS -->
        {!! HTML::script('/resources/assets/js/jquery.confirmExit.min.js') !!}

        <!-- Sortable Script Support -->
        <script src="{{url()."/resources/assets/custom_js/my_script.js?".time()}}"></script>

        <!-- Custom Script Support -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> 
        <script src="{{url()."/resources/assets/custom_js/custom1.js"}}"></script>
        <script src="{{url()."/resources/assets/custom_js/custom_script.js?".time()}}"></script>
        <script src="{{url()."/resources/assets/custom_js/custom_script_js.js?".time()}}"></script>
        <script src="{{url()."/resources/assets/custom_js/custom.js?".time()}}"></script>
        <?php if (Route::getCurrentRoute()->getPath() == "dashboard" ) { ?>
            <script src="{{url()."/resources/assets/custom_js/graph.js?".time()}}"></script>
        <?php } ?>
        <script src="{{url()."/resources/assets/custom_js/laravel.js?".time()}}"></script>    

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
            <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>-->
                    
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
            
<!--            {!! HTML::style('/resources/assets/css/custom_style/bootstrap-multiselect.css') !!}
            <script type="text/javascript" src="{{url()."/js/bootstrap-multiselect.js"}}"></script>-->

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
                        revert: true, // will cause the event to go back to its
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
                            start: new Date(y, m, d - 5),
                            end: new Date(y, m, d - 2)
                        },
                        {
                            id: 999,
                            title: 'Repeating Event',
                            start: new Date(y, m, d - 3, 16, 0),
                            allDay: false,
                            className: 'label-danger'
                        },
                        {
                            id: 999,
                            title: 'Repeating Event',
                            start: new Date(y, m, d + 4, 16, 0),
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
                            start: new Date(y, m, d + 1, 19, 0),
                            end: new Date(y, m, d + 1, 22, 30),
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
                    onRegionLabelShow: function(e, el, code) {
                        el.php(el.php() + ' (' + gdpData[code] + ')');
                    }
                });

                $('.infographic-box .value .timer').countTo({});

            });
        </script>

        <input type="hidden" id="site_url" value="{{url()}}"
    </body>
</html>