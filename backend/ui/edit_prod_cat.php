<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>Edit Product Category - Vikas Associate Order Automation System</title>

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
                                            <li class="active"><span>Edit Product Category</span></li>
                                        </ol>

                                        <div class="clearfix">



                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="main-box">


                                            <div class="main-box-body clearfix">


                                                <form method="POST" action="" accept-charset="UTF-8" >
                                                    <div class="form-group">
                                                        <label>Product Category Type</label>
                                                        <div class="radio">
                                                            <input checked="" value="pipe" id="optionsRadios1" name="status" type="radio">
                                                            <label for="optionsRadios1">Pipe</label>
                                                            <input value="structure" id="optionsRadios2" name="status" type="radio">
                                                            <label for="optionsRadios2">Structure</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cat_name">Product Category Name</label>
                                                        <input id="cat_name" class="form-control" placeholder="Product Category Name" name="cat_name" value="" type="text">
                                                    </div>


                                                    <div class="form-group">
                                                        <label for="price">Price</label>
                                                        <input id="price" class="form-control" placeholder="Price" name="price" value="" type="text">
                                                    </div>

                                                    <hr>
                                                    <div >
                                                        <button type="button" class="btn btn-primary form_button_footer" >Submit</button>
                                                        <a href="product_category.php" class="btn btn-default form_button_footer">Back</a>
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

    </body>
</html>