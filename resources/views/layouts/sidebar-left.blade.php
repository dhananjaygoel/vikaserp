<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">

            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <?php
                    $full_name = $_SERVER['PHP_SELF'];
                    $name_array = explode('/', $full_name);
                    $count = count($name_array);
                    $page_name = $name_array[$count - 1];
                    ?>
                    <li class="<?php echo ($page_name == '') ? 'active' : ''; ?>">
                        <a href="{{url()}}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*users*') ? 'active' : '') }}">
                        <a href="{{url()}}/users">
                            <i class="fa fa-user"></i>
                            <span>Users</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*customers*') ? 'active' : '') }}">
                        <a href="{{url('customers')}}">
                            <i class="fa fa-male"></i>
                            <span>Customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="<?php echo ($page_name == 'pendingcustomers.php') || ($page_name == 'edit_pendingcustomer.php') ? 'active' : ''; ?>">
                        <a href="pendingcustomers.php">
                            <i class="fa fa-book"></i>
                            <span>Pending customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>

                    <li class="{{ (Request::is('*inquiry*') ? 'active' : '') }}">
                        <a href="{{url("inquiry")}}">
                            <i class="fa fa-info"></i>
                            <span>Inquiry</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="<?php echo ($page_name == 'orders.php') || ($page_name == 'delivery_orders.php') || ($page_name == 'delivery_orders_challan.php') || ($page_name == 'daily_del_orders.php') || ($page_name == 'delivery_orders_challan_report.php') || ($page_name == 'pending_orders.php') || ($page_name == 'order_view.php') || ($page_name == 'edit_orders.php') || ($page_name == 'createdelivery_order.php') || ($page_name == 'view_deliveryorder.php') || ($page_name == 'edit_deliveryorder.php') || ($page_name == 'delivery_orders_challanbutton.php') || ($page_name == 'add_delivery_order.php') || ($page_name == 'view_deliverychallan.php') || ($page_name == 'edit_deliverychallan.php') || ($page_name == 'add_order.php') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Order</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li class="<?php echo ($page_name == 'orders.php') ? 'active' : ''; ?>">
                                <a href="orders.php" >
                                    Order
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'delivery_orders.php') ? 'active' : ''; ?>">
                                <a href="delivery_orders.php">
                                    Delivery Order
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'delivery_orders_challan.php') ? 'active' : ''; ?>">
                                <a href="delivery_orders_challan.php">
                                    Delivery Challan
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'daily_del_orders.php') ? 'active' : ''; ?>">
                                <a href="daily_del_orders.php">
                                    Pending Delivery Order Report
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'delivery_orders_challan_report.php') ? 'active' : ''; ?>">
                                <a href="delivery_orders_challan_report.php">
                                    Sales Daybook
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'pending_orders.php') ? 'active' : ''; ?>">
                                <a href="pending_orders.php">
                                    Pending Order Report
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class=" <?php echo ($page_name == 'purchaseorders.php') || ($page_name == 'create_purchase_advise.php') || ($page_name == 'purchaseorder_advise.php') || ($page_name == 'purchaseorder_challan.php') || ($page_name == 'purchaseorder_report.php') || ($page_name == 'purchaseorder_advisereport.php') || ($page_name == 'purchaseorder_challanreport.php') || ($page_name == 'add_placeorder.php') || ($page_name == 'purchaseorder_view.php') || ($page_name == 'edit_purchaseorders.php') || ($page_name == 'createpurchase_order.php') || ($page_name == 'view_purchaseadvice.php') || ($page_name == 'edit_purchaseadvice.php') || ($page_name == 'purchaseorder_challanbutton.php') || ($page_name == 'view_purchasechallan.php') || ($page_name == 'edit_purchasechallan.php') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Purchase Order</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li class="<?php echo ($page_name == 'purchaseorders.php') ? 'active' : ''; ?>">
                                <a href="purchaseorders.php" >
                                    Purchase Order
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'purchaseorder_advise.php') ? 'active' : ''; ?>">
                                <a href="purchaseorder_advise.php">
                                    Purchase Advice
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'purchaseorder_challan.php') ? 'active' : ''; ?>">
                                <a href="purchaseorder_challan.php">
                                    Purchase Challan
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'purchaseorder_report.php') ? 'active' : ''; ?>" >
                                <a href="purchaseorder_report.php">
                                    Purchase Order Report
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'purchaseorder_advisereport.php') ? 'active' : ''; ?>">
                                <a href="purchaseorder_advisereport.php">
                                    Pending Purchase Advise Report
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'purchaseorder_challanreport.php') ? 'active' : ''; ?>">
                                <a href="purchaseorder_challanreport.php">
                                    Purchase Daybook
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ (Request::is('*product_category*' || '*product_sub_category*') ? 'active' : '') }}">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-codepen"></i>
                            <span>Product</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul  class="submenu">
                            <li class="{{ (Request::is('*product_category*') ? 'active' : '') }}">
                                <a href="{{url()}}/product_category" >
                                    Product Category
                                </a>
                            </li>
                            <li class="{{ (Request::is('*product_sub_category*') ? 'active' : '') }}">
                                <a href="{{url()}}/product_sub_category">
                                    Product Sub Category
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="<?php echo ($page_name == 'location.php') || ($page_name == 'edit_location.php') || ($page_name == 'add_location.php') || ($page_name == 'city.php') || ($page_name == 'add_city.php') || ($page_name == 'edit_city.php') || ($page_name == 'state.php') || ($page_name == 'add_state.php') || ($page_name == 'edit_state.php') || ($page_name == 'unit.php') || ($page_name == 'add_unit.php') || ($page_name == 'edit_unit.php') ? 'active' : ''; ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-thumb-tack"></i>
                            <span>Masters Module</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul  class="submenu">
                            <li class="{{ (Request::is('*location*') ? 'active' : '') }}">
                                <a href="{{url("location")}}">
                                    Delivery Location
                                </a>
                            </li>
                            <li class="{{ (Request::is('*city*') ? 'active' : '') }}">
                                <a href="{{url("city")}}">
                                    City
                                </a>
                            </li>
                            <li class="{{ (Request::is('*states*') ? 'active' : '') }}">
                                <a href="{{url("states")}}">
                                    State
                                </a>
                            </li>
                            <li class="{{ (Request::is('*unit*') ? 'active' : '') }}">
                                <a href="{{url("unit")}}">
                                    Unit
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ (Request::is('*security*') ? 'active' : '') }}">
                        <a href="{{url("security")}}">
                            <i class="fa fa-lock"></i>
                            <span>Security</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </section>
</div>

