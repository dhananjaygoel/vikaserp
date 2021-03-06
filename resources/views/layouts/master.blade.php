<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="_token" content="<?php echo csrf_token(); ?>" id="csrf_token" />
        <?php
        // echo "slkhfkj";
        // exit;
        ?>
        <base id="baseurl" name="{{url("/")}}">
        <title>@yield("title")</title>
        @yield("meta")

        <!-- bootstrap -->
        {{ HTML::style("assets/css/bootstrap/bootstrap.min.css") }}

        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <!--        {{ HTML::style("assets/css/libs/jquery-ui.css") }}-->
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">-->

        <!-- RTL support - for demo only -->
        {{ HTML::style("assets/css/bootstrap/bootstrap.min.css") }}
        <!--<script src="js/demo-rtl.js"></script>-->
        <!--
        If you need RTL support just include here RTL CSS file <link rel="stylesheet" type="text/css" href="css/libs/bootstrap-rtl.min.css" />
        And add "rtl" class to <body> element - e.g. <body class="rtl">
        -->

        <!-- libraries -->
        {{ HTML::style("assets/css/libs/font-awesome.css") }}
        {{ HTML::style("assets/css/libs/nanoscroller.css") }}


        <!-- global styles -->
        {{ HTML::style("assets/css/compiled/theme_style.css") }}

        <!-- this page specific styles -->
        {{ HTML::style("assets/css/libs/fullcalendar.css") }}
        {{ HTML::style("assets/css/libs/fullcalendar.print.css") }}
        {{ HTML::style("assets/css/compiled/calendar.css") }}
        {{ HTML::style("assets/css/libs/morris.css") }}
        {{ HTML::style("assets/css/libs/datepicker.css") }}
        {{ HTML::style("assets/css/libs/daterangepicker.css") }}
        {{ HTML::style("assets/css/libs/jquery-jvectormap-1.2.2.css") }}
        {{ HTML::style("assets/css/libs/select2.min.css") }}


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
        {{ HTML::style("assets/css/custom_style/custom_styles.css") }}
        {{ HTML::style("assets/css/custom_style/custom_media_query.css") }}

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>-->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js" ></script>-->

        <!-- google font libraries -->
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400" rel="stylesheet" type="text/css">
        <!-- Favicon -->
        <link href="{{ asset("assets/img/favicon.png") }}" rel="shortcut icon" type="image/x-icon">
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

            .dt_grey::first-letter {
                color: grey;
                font-size: 18px;
            }

            .dt_green::first-letter {
                color: green;
                font-size: 18px;
            }

            .dt_green::first-letter {
                color: red;
                font-size: 18px;
            }
        </style>
        <!--define method before load page-->
        <script>
            product_autocomplete = function () {
            };
            numbersOnly = function () {
            };
            clear_actual_qty = function () {
            };
            setQty = function () {
            };
            change_quantity2 = function () {
            };
            fetch_average_quantity = function () {
            };
            calutate_pending_order = function () {
            };
            delete_delivery_order = function () {
            };
            product_autocomplete_purchase = function () {
            };
            delete_purchase_order = function () {
            };
            print_purchase_challan = function () {
            };
            print_purchase_advice = function () {
            };
            print_challan = function () {
            };
            unitType = function () {
            };
            error_check = function () {
            };
            purchase_challan_calculation = function () {
            };
            print_delivery_challan = function () {
            };
            var gdpData;
        </script>
<!--        <script>
            var _rollbarConfig = {
                accessToken: "e9b8125f7584473784540f13c939e75e",
                captureUncaught: true,
                captureUnhandledRejections: true,
                payload: {
                    environment: "production"
                }
            };
// Rollbar Snippet
            !function (r) {
                function e(n) {
                    if (o[n])
                        return o[n].exports;
                    var t = o[n] = {exports: {}, id: n, loaded: !1};
                    return r[n].call(t.exports, t, t.exports, e), t.loaded = !0, t.exports
                }
                var o = {};
                return e.m = r, e.c = o, e.p = "", e(0)
            }([function (r, e, o) {
                    "use strict";
                    var n = o(1).Rollbar, t = o(2);
                    _rollbarConfig.rollbarJsUrl = _rollbarConfig.rollbarJsUrl || "{{url("/")."assets/js/rollbar.min.js"}}";
//                    _rollbarConfig.rollbarJsUrl = _rollbarConfig.rollbarJsUrl || "https://cdnjs.cloudflare.com/ajax/libs/rollbar.js/1.9.4/rollbar.min.js";
                    var a = n.init(window, _rollbarConfig), i = t(a, _rollbarConfig);
                    a.loadFull(window, document, !_rollbarConfig.async, _rollbarConfig, i)
                }, function (r, e) {
                    "use strict";
                    function o(r) {
                        return function () {
                            try {
                                return r.apply(this, arguments)
                            } catch (r) {
                                try {
                                    console.error("[Rollbar]: Internal error", r)
                                } catch (r) {
                                }
                            }
                        }
                    }
                    function n(r, e, o) {
                        window._rollbarWrappedError && (o[4] || (o[4] = window._rollbarWrappedError), o[5] || (o[5] = window._rollbarWrappedError._rollbarContext), window._rollbarWrappedError = null), r.uncaughtError.apply(r, o), e && e.apply(window, o)
                    }
                    function t(r) {
                        var e = function () {
                            var e = Array.prototype.slice.call(arguments, 0);
                            n(r, r._rollbarOldOnError, e)
                        };
                        return e.belongsToShim = !0, e
                    }
                    function a(r) {
                        this.shimId = ++c, this.notifier = null, this.parentShim = r, this._rollbarOldOnError = null
                    }
                    function i(r) {
                        var e = a;
                        return o(function () {
                            if (this.notifier)
                                return this.notifier[r].apply(this.notifier, arguments);
                            var o = this, n = "scope" === r;
                            n && (o = new e(this));
                            var t = Array.prototype.slice.call(arguments, 0), a = {shim: o, method: r, args: t, ts: new Date};
                            return window._rollbarShimQueue.push(a), n ? o : void 0
                        })
                    }
                    function l(r, e) {
                        if (e.hasOwnProperty && e.hasOwnProperty("addEventListener")) {
                            var o = e.addEventListener;
                            e.addEventListener = function (e, n, t) {
                                o.call(this, e, r.wrap(n), t)
                            };
                            var n = e.removeEventListener;
                            e.removeEventListener = function (r, e, o) {
                                n.call(this, r, e && e._wrapped ? e._wrapped : e, o)
                            }
                        }
                    }
                    var c = 0;
                    a.init = function (r, e) {
                        var n = e.globalAlias || "Rollbar";
                        if ("object" == typeof r[n])
                            return r[n];
                        r._rollbarShimQueue = [], r._rollbarWrappedError = null, e = e || {};
                        var i = new a;
                        return o(function () {
                            if (i.configure(e), e.captureUncaught) {
                                i._rollbarOldOnError = r.onerror, r.onerror = t(i);
                                var o, a, c = "EventTarget,Window,Node,ApplicationCache,AudioTrackList,ChannelMergerNode,CryptoOperation,EventSource,FileReader,HTMLUnknownElement,IDBDatabase,IDBRequest,IDBTransaction,KeyOperation,MediaController,MessagePort,ModalWindow,Notification,SVGElementInstance,Screen,TextTrack,TextTrackCue,TextTrackList,WebSocket,WebSocketWorker,Worker,XMLHttpRequest,XMLHttpRequestEventTarget,XMLHttpRequestUpload".split(",");
                                for (o = 0; o < c.length; ++o)
                                    a = c[o], r[a] && r[a].prototype && l(i, r[a].prototype)
                            }
                            return e.captureUnhandledRejections && (i._unhandledRejectionHandler = function (r) {
                                var e = r.reason, o = r.promise, n = r.detail;
                                !e && n && (e = n.reason, o = n.promise), i.unhandledRejection(e, o)
                            }, r.addEventListener("unhandledrejection", i._unhandledRejectionHandler)), r[n] = i, i
                        })()
                    }, a.prototype.loadFull = function (r, e, n, t, a) {
                        var i = function () {
                            var e;
                            if (void 0 === r._rollbarPayloadQueue) {
                                var o, n, t, i;
                                for (e = new Error("rollbar.js did not load"); o = r._rollbarShimQueue.shift(); )
                                    for (t = o.args, i = 0; i < t.length; ++i)
                                        if (n = t[i], "function" == typeof n) {
                                            n(e);
                                            break
                                        }
                            }
                            "function" == typeof a && a(e)
                        }, l = !1, c = e.createElement("script"), p = e.getElementsByTagName("script")[0], s = p.parentNode;
                        c.crossOrigin = "", c.src = t.rollbarJsUrl, c.async = !n, c.onload = c.onreadystatechange = o(function () {
                            if (!(l || this.readyState && "loaded" !== this.readyState && "complete" !== this.readyState)) {
                                c.onload = c.onreadystatechange = null;
                                try {
                                    s.removeChild(c)
                                } catch (r) {
                                }
                                l = !0, i()
                            }
                        }), s.insertBefore(c, p)
                    }, a.prototype.wrap = function (r, e) {
                        try {
                            var o;
                            if (o = "function" == typeof e ? e : function () {
                                return e || {}
                            }, "function" != typeof r)
                                return r;
                            if (r._isWrap)
                                return r;
                            if (!r._wrapped) {
                                r._wrapped = function () {
                                    try {
                                        return r.apply(this, arguments)
                                    } catch (e) {
                                        throw"string" == typeof e && (e = new String(e)), e._rollbarContext = o() || {}, e._rollbarContext._wrappedSource = r.toString(), window._rollbarWrappedError = e, e
                                    }
                                }, r._wrapped._isWrap = !0;
                                for (var n in r)
                                    r.hasOwnProperty(n) && (r._wrapped[n] = r[n])
                            }
                            return r._wrapped
                        } catch (e) {
                            return r
                        }
                    };
                    for (var p = "log,debug,info,warn,warning,error,critical,global,configure,scope,uncaughtError,unhandledRejection".split(","), s = 0; s < p.length; ++s)
                        a.prototype[p[s]] = i(p[s]);
                    r.exports = {Rollbar: a, _rollbarWindowOnError: n}
                }, function (r, e) {
                    "use strict";
                    r.exports = function (r, e) {
                        return function (o) {
                            if (!o && !window._rollbarInitialized) {
                                var n = window.RollbarNotifier, t = e || {}, a = t.globalAlias || "Rollbar", i = window.Rollbar.init(t, r);
                                i._processShimQueue(window._rollbarShimQueue || []), window[a] = i, window._rollbarInitialized = !0, n.processPayloads()
                            }
                        }
                    }
                }]);
// End Rollbar Snippet
        </script>-->

    </head>
    <body>
        <div id="theme-wrapper">
            <span id="baseurl" style="display: none;">{{url("/")}}</span>
            @include("layouts.header")
            <div id="page-wrapper" class="container dashboard-nav nav-small">

                <div class="row">
                    @include("layouts.sidebar-left")
                    <div id="content-wrapper">
                        @yield("content")
                        @include("layouts.footer")
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
        {{ HTML::script("assets/js/demo-skin-changer.js") }}
        {{ HTML::script("assets/js/jquery.js") }}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        {{ HTML::script("assets/js/bootstrap.js") }}
        {{ HTML::script("assets/js/jquery.nanoscroller.min.js") }}
        {{ HTML::script("assets/js/demo.js") }}
        <!-- this page specific scripts -->

        <!-- Bootbox Js -->
        <!--{{ HTML::script("assets/js/bootbox.min.js") }}-->
        {{ HTML::script("assets/js/jquery_block_UI.js") }}

        <!-- Bootstrap spinner Js -->
        {{ HTML::script("assets/js/bootstrap-datepicker.js") }}

        {{ HTML::script("assets/js/jquery-ui.custom.min.js") }}
        <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
        {{ HTML::script("assets/js/moment.min.js") }}
        {{ HTML::script("assets/js/fullcalendar.min.js") }}
        {{ HTML::script("assets/js/jquery.slimscroll.min.js") }}
        {{ HTML::script("assets/js/raphael-min.js") }}
        {{ HTML::script("assets/js/morris.min.js") }}

        {{ HTML::script("assets/js/jquery-jvectormap-1.2.2.min.js") }}
        {{ HTML::script("assets/js/jquery-jvectormap-world-merc-en.js") }}
        {{ HTML::script("assets/js/gdp-data.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.min.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.pie.min.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.stack.min.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.resize.min.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.time.min.js") }}
        {{ HTML::script("assets/js/flot/jquery.flot.threshold.js") }}
        {{ HTML::script("assets/js/jquery.countTo.js") }}
        <!--{{ HTML::script("assets/js/jquery.spinner.min.js") }}-->

        {{ HTML::script("assets/js/daterangepicker.js") }}

        <!-- theme scripts -->
        {{ HTML::script("assets/js/scripts.js") }}

        <!-- Confirm Exit JS -->
        {{ HTML::script("assets/js/jquery.confirmExit.min.js") }}

        <!-- Sortable Script Support -->
        {{ HTML::script("assets/custom_js/my_script.js") }}

        <!-- Custom Script Support -->
        {{ HTML::script("assets/js/select2.v.4.0.3.min.js") }}

        {{ HTML::script("assets/custom_js/custom_script.js") }}
        {{ HTML::script("assets/custom_js/custom_script_js.js") }}
        {{ HTML::script("assets/custom_js/custom.js") }}

        <?php
        $currentRoute = Route::current();
        $currentPath= Route::getFacadeRoot()->current()->uri();
        // var_dump ($currentPath);
        // var_dump (Route::current());
        if (Route::current()) {
            
            $path = Route::getFacadeRoot()->current()->uri();
            
            if ($path == "dashboard") {
                ?>
                 {{ HTML::script("assets/custom_js/graph.js") }}
                <!-- <script src="{{url("/")."assets/custom_js/graph.js"}}"></script> -->
                <?php
            }
        }
        ?>


        {{ HTML::script("assets/js/jquery.validate.min.js") }}
        {{ HTML::script("assets/js/bootstrap-multiselect.min.js") }}

        {{ HTML::script("assets/custom_js/laravel.js") }}
        {{ HTML::script("assets/custom_js/common.js") }}
        <!-- <script src="{{url("/")."/assets/custom_js/laravel.js"}}"></script>
        <script src="{{url("/")."/assets/custom_js/common.js"}}"></script> -->

        <?php

        if (Route::current()) {
            $path = Route::getFacadeRoot()->current()->uri();
            // print_r($path);
            // exit;
            // dd($path);
            if ($path == "inquiry/create" ||
                    $path == "inquiry/{inquiry}/edit" ||
                    $path == "orders/create" ||
                    $path == "place_order/{id}" ||
                    $path == "orders/{order}/edit" ||
                    $path == "create_delivery_order/{id}" ||
                    $path == "delivery_order/{delivery_order}/edit" ||
                    $path == "delivery_challan/{delivery_challan}/edit" ||
                    $path == "purchase_orders/create" ||
                    $path == "create_purchase_advice/{create_purchase_advice}" ||
                    $path == "purchaseorder_advise/{purchaseorder_advise}/edit" ||
                    $path == "purchase_orders/{purchase_order}/edit"
            ) {
                ?>
                {{ HTML::script("assets/custom_js/custom_new.js") }}
                {{ HTML::script("assets/custom_js/custom_autoload_logic.js") }}
                <!-- <script src="{{url("/")."assets/custom_js/custom_autoload_logic.js"}}"></script> -->
            <?php
            }
        }
        ?>

        <!--            {{ HTML::style("assets/css/custom_style/bootstrap-multiselect.css") }}
                    <script type="text/javascript" src="{{url("/")."/js/bootstrap-multiselect.js"}}"></script>-->

        <script>
            var token = "{{csrf_token()}}";
            var url = "{{url("/")}}";
            $(function ($) {
                $("#datepickerDate").datepicker({
                    format: "dd/mm/yyyy"
                });

                $("#datepickerDateComponent").datepicker();
            });
            $(function ($) {
                $("#datepickerDate1").datepicker({
                    format: "dd-mm-yyyy"
                });

                $("#datepickerDateComponent").datepicker();
            });

        </script>
        <!-- this page specific inline scripts -->
        <script>
            $(document).ready(function () {

                /* initialize the external events
                 -----------------------------------------------------------------*/

                $("#external-events div.external-event").each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn"t need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element"s text as the event title
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data("eventObject", eventObject);

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

                var calendar = $("#calendar").fullCalendar({
                    header: {
                        left: "",
                        center: "title",
                        right: "prev,next"
                    },
                    isRTL: $("body").hasClass("rtl"), //rtl support for calendar
                    selectable: true,
                    selectHelper: true,
                    select: function (start, end, allDay) {
                        var title = prompt("Event Title:");
                        if (title) {
                            calendar.fullCalendar("renderEvent",
                                    {
                                        title: title,
                                        start: start,
                                        end: end,
                                        allDay: allDay
                                    },
                            true // make the event "stick"
                                    );
                        }
                        calendar.fullCalendar("unselect");
                    },
                    editable: true,
                    droppable: true, // this allows things to be dropped onto the calendar !!!
                    drop: function (date, allDay) { // this function is called when something is dropped

                        // retrieve the dropped element"s stored Event Object
                        var originalEventObject = $(this).data("eventObject");

                        // we need to copy it, so that multiple events don"t have a reference to the same object
                        var copiedEventObject = $.extend({}, originalEventObject);

                        // assign it the date that was reported
                        copiedEventObject.start = date;
                        copiedEventObject.allDay = allDay;

                        // copy label class from the event object
                        var labelClass = $(this).data("eventclass");

                        if (labelClass) {
                            copiedEventObject.className = labelClass;
                        }

                        // render the event on the calendar
                        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                        $("#calendar").fullCalendar("renderEvent", copiedEventObject, true);

                        // is the "remove after drop" checkbox checked?
                        if ($("#drop-remove").is(":checked")) {
                            // if so, remove the element from the "Draggable Events" list
                            $(this).remove();
                        }

                    },
                    buttonText: {
                        prev: "<i class='fa fa-chevron-left'></i>",
                        next: "<i class='fa fa-chevron-right'></i>"
                    },
                    events: [
                        {
                            title: "All Day Event",
                            start: new Date(y, m, 1),
                            className: "label-success"
                        },
                        {
                            title: "Long Event",
                            start: new Date(y, m, d - 5),
                            end: new Date(y, m, d - 2)
                        },
                        {
                            id: 999,
                            title: "Repeating Event",
                            start: new Date(y, m, d - 3, 16, 0),
                            allDay: false,
                            className: "label-danger"
                        },
                        {
                            id: 999,
                            title: "Repeating Event",
                            start: new Date(y, m, d + 4, 16, 0),
                            allDay: false
                        },
                        {
                            title: "Meeting",
                            start: new Date(y, m, d, 10, 30),
                            allDay: false,
                            className: "label-info"
                        },
                        {
                            title: "Lunch",
                            start: new Date(y, m, d, 12, 0),
                            end: new Date(y, m, d, 14, 0),
                            allDay: false,
                            className: "label-success"
                        },
                        {
                            title: "Birthday Party",
                            start: new Date(y, m, d + 1, 19, 0),
                            end: new Date(y, m, d + 1, 22, 30),
                            allDay: false,
                            className: "label-info"
                        },
                        {
                            title: "Click for Google",
                            start: new Date(y, m, 28),
                            end: new Date(y, m, 29),
                            url: "http://google.com/",
                            className: "label-danger"
                        }
                    ]
                });

                $(".conversation-inner").slimScroll({
                    height: "332px",
                    alwaysVisible: false,
                    railVisible: true,
                    wheelStep: 5,
                    allowPageScroll: false
                });




                //WORLD MAP
                $("#world-map").vectorMap({
                    map: "world_merc_en",
                    backgroundColor: "#ffffff",
                    zoomOnScroll: false,
                    regionStyle: {
                        initial: {
                            fill: "#e1e1e1",
                            stroke: "none",
                            "stroke-width": 0,
                            "stroke-opacity": 1
                        },
                        hover: {
                            "fill-opacity": 0.8
                        },
                        selected: {
                            fill: "#8dc859"
                        },
                        selectedHover: {
                        }
                    },
                    series: {
                        regions: [{
                                values: gdpData,
                                scale: ["#6fc4fe", "#2980b9"],
                                normalizeFunction: "polynomial"
                            }]
                    },
                    onRegionLabelShow: function (e, el, code) {
                        el.php(el.php() + " (" + gdpData[code] + ")");
                    }
                });

                $(".infographic-box .value .timer").countTo({});

            });

            $("#product_sub_category_select").change(function () {
               if(this.value == 3){
                   $("#length_u").show();
               }
               else{
                   $("#length_u").hide();
               }
            });

            $(".length_unit").change(function(){
                var val = $("#length").val();
                var l_u = $("#length_u .length_unit:checked").val();

                if(l_u == "mm"){
                    val = val * 305;
                    $("#length").val(val);
                }
                else{
                    val = val / 305;
                    $("#length").val(val);
                }
            });

            function setDiffrence(val) {
                var diff = val.split(":")[1];
                if(diff){
                    $("#difference").val(diff);
                }
                else{
                    $("#difference").val(0);
                }
            }

            $("#hsn_desc_get_desc").on("change",function () {
               var hsncode = this.value;
               if(hsncode){
                    var hsndesc = hsncode.split(":")[1];
                    $("#hsn_desc").val(hsndesc);
               }
            });

            $("#del_supervisor").on("change",function () {
                var del_supervisor = this.value;
                if(del_supervisor){
                    $("#truck_load").prop("disabled", false);
                }
                else{
                    $("#truck_load").prop("disabled", true);
                }
            });

            $("#del_boy").on("change",function () {
                var del_boy = this.value;
                if(del_boy){
                    $("#truck_load").prop("disabled", false);
                }
                else{
                    $("#truck_load").prop("disabled", true);
                }
            });

        </script>






        <input type="hidden" id="site_url" value="{{url("/")}}"/>
    </body>
</html>
