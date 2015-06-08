<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
        <title>@yield('title')</title>
        @yield('meta')
        <!-- bootstrap -->
        {!! HTML::style('/resources/assets/css/bootstrap/bootstrap.min.css') !!}

        <!-- libraries -->
        {!! HTML::style('/resources/assets/css/libs/font-awesome.css') !!}
        {!! HTML::style('/resources/assets/css/libs/nanoscroller.css') !!}

        <!-- global styles -->
        {!! HTML::style('/resources/assets/css/compiled/theme_styles.css') !!}



        {!! HTML::style('/resources/assets/css/libs/datepicker.css') !!}

        {!! HTML::style('/resources/assets/css/libs/bootstrap-timepicker.css') !!}
        {!! HTML::style('/resources/assets/css/libs/daterangepicker.css') !!}


        <!--Custom styles-->
        {!! HTML::style('/resources/assets/css/custom_style/style.css') !!}

        <!-- google font libraries -->
        <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>

        <!-- Favicon -->
        <link href="{{ asset('/resources/assets/img/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

        <!--[if lt IE 9]>
                <script src="js/html5shiv.js"></script>
                <script src="js/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>
        <div id="theme-wrapper">
            @include('admin.masters.menu')
            <div id="page-wrapper" class="container">
                <div class="row">
                    @include('admin.masters.sidebar-left')
                    <div id="content-wrapper">
                        @yield('content')
                        @include('admin.masters.footer')
                    </div>
                </div>
            </div>
        </div>
        <!-- global scripts -->
        {!! HTML::script('/resources/assets/js/demo-skin-changer.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.js') !!}
        {!! HTML::script('/resources/assets/js/bootstrap.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.nanoscroller.min.js') !!}
        {!! HTML::script('/resources/assets/js/demo.js') !!}
        <!-- this page specific scripts -->


        {!! HTML::script('/resources/assets/js/jquery.maskedinput.min.js') !!}
        {!! HTML::script('/resources/assets/js/bootstrap-datepicker.js') !!}
        {!! HTML::script('/resources/assets/js/moment.min.js') !!}
        {!! HTML::script('/resources/assets/js/daterangepicker.js') !!}
        {!! HTML::script('/resources/assets/js/bootstrap-timepicker.js') !!}
        {!! HTML::script('/resources/assets/js/select2.min.js') !!}
        {!! HTML::script('/resources/assets/js/hogan.js') !!}
        {!! HTML::script('/resources/assets/js/typeahead.min.js') !!}
        {!! HTML::script('/resources/assets/js/jquery.pwstrength.js') !!}

        <!-- Bootbox Js -->
        {!! HTML::script('/resources/assets/js/bootbox.min.js') !!}

        <!-- theme scripts -->
        {!! HTML::script('/resources/assets/js/scripts.js') !!}
        {!! HTML::script('/resources/assets/js/pace.min.js') !!}
        <!-- this page specific inline scripts -->

        <!-- RTL support - for demo only -->
        {!! HTML::script('/resources/assets/js/demo-rtl.js') !!}
        {!! HTML::script('/resources/assets/js/custom_js/custom_script.js') !!}
        {!! HTML::script('/resources/assets/custom_js/sortable.js') !!}
        {!! HTML::script('/resources/assets/custom_js/aman.js') !!}
    </body>
</html>