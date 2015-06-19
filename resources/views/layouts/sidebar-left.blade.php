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
                    @if(Auth::user()->id == 0)
                    <li class="{{ (Request::is('*dashboard*') ? 'active' : '') }}">
                        <a href="{{url('dashboard')}}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->id == 0 || Auth::user()->id == 1 )
                    <li class="{{ (Request::is('*users*') ? 'active' : '') }}">
                        <a href="{{url()}}/users">
                            <i class="fa fa-user"></i>
                            <span>Users</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->id == 0 || Auth::user()->id == 1 )
                    <li class="{{ (Request::is('customers*') ? 'active' : '') }}">
                        <a href="{{url('customers')}}">
                            <i class="fa fa-male"></i>
                            <span>Customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->id == 0 || Auth::user()->id == 1 )
                    <li class="{{ (Request::is('pending_customers*') ? 'active' : '') }}">
                        <a href="{{url('pending_customers')}}">
                            <i class="fa fa-book"></i>
                            <span>Pending customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->id == 0 || Auth::user()->id == 1 || Auth::user()->id == 2)
                    <li class="{{ (Request::is('*inquiry*') ? 'active' : '') }}">
                        <a href="{{url("inquiry")}}">
                            <i class="fa fa-info"></i>
                            <span>Inquiry</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    
                    <li class="<?php
                    if (Request::is('*orders*') || Request::is('*delivery_order*') || Request::is('*delivery_challan*') || Request::is('*pending_delivery_order*') || Request::is('*pending_order_report*')) {
                        echo 'active';
                    }
                    ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Order</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li class="{{ (Request::is('*orders*') ? 'active' : '') }}">
                                <a href="{{url('orders')}}" >
                                    Order
                                </a>
                            </li>
                            <li class="{{ (Request::is('*delivery_order*') ? 'active' : '') }}">
                                <a href="{{url('delivery_order')}}">
                                    Delivery Order
                                </a>
                            </li>
                            <li class="{{ (Request::is('*delivery_challan*') ? 'active' : '') }}">
                                <a href="{{url('delivery_challan')}}">
                                    Delivery Challan
                                </a>
                            </li>

                            <li class="{{ (Request::is('*pending_delivery_order*') ? 'active' : '') }}">
                                <a href="{{'pending_delivery_order'}}">
                                    Pending Delivery Order Report
                                </a>
                            </li>
                            @if(Auth::user()->id == 0 ||Auth::user()->id == 1 || Auth::user()->id == 2)
                            <li class="{{ (Request::is('*sales_daybook*') ? 'active' : '') }}">
                                <a href="{{url('sales_daybook')}}">
                                    Sales Daybook
                                </a>
                            </li>
                            @endif
                            
                            <li class="{{ (Request::is('*pending_order_report*') ? 'active' : '') }}">
                                <a href="{{url('pending_order_report')}}">
                                    Pending Order Report
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="<?php
                    if (Request::is('*purchase_orders*') || Request::is('*purchaseorder_advise*') || Request::is('*purchase_challan*') || Request::is('*purchase_order_report*') || Request::is('*purchase_order_daybook*')) {
                        echo 'active';
                    }
                    ?>">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Purchase Order</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            @if(Auth::user()->id == 0 ||Auth::user()->id == 1)
                            <li class="{{ (Request::is('*purchase_orders*') ? 'active' : '') }}">
                                <a href="{{url("purchase_orders")}}">
                                    Purchase Order
                                </a>
                            </li>
                            @endif
                            <li class="{{ (Request::is('*purchaseorder_advise*') ? 'active' : '') }}">
                                <a href="{{url('purchaseorder_advise')}}">
                                    Purchase Advice
                                </a>
                            </li>
                            <li class="{{ (Request::is('*purchase_challan*') ? 'active' : '') }}">
                                <a href="{{url("purchase_challan")}}">
                                    Purchase Challan
                                </a>
                            </li>

                            <li class="{{ (Request::is('*purchase_order_report*') ? 'active' : '') }}">
                                <a href="{{url('purchase_order_report')}}" >
                                    Purchase Order Report
                                </a>
                            </li>
                            <li class="<?php echo ($page_name == 'purchaseorder_advisereport.php') ? 'active' : ''; ?>">
                                <a href="{{url('pending_purchase_advice')}}">
                                    Pending Purchase Advise Report
                                </a>
                            </li>
                            @if(Auth::user()->id == 0 || Auth::user()->id == 1)
                            <li class="{{ (Request::is('*purchase_order_daybook*') ? 'active' : '') }}">
                                <a href="{{url('purchase_order_daybook')}}">
                                    Purchase Daybook
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    
                    <li class="<?php
                    if (Request::is('*product_category*') || Request::is('*product_sub_category*')) {
                        echo 'active';
                    }
                    ?>">
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

                    <li class="<?php
                    if (Request::is('*states*') || Request::is('*city*') || Request::is('*unit*') || Request::is('*location*')) {
                        echo 'active';
                    }
                    ?>">
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
                    @if(Auth::user()->id == 0)
                    <li class="{{ (Request::is('*customer_manager*') ? 'active' : '') }}">
                        <a href="{{url("customer_manager")}}">
                            <i class="fa fa-asterisk"></i>
                            <span>Customer Manager</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->id == 0)
                    <li class="{{ (Request::is('*security*') ? 'active' : '') }}">
                        <a href="{{url("security")}}">
                            <i class="fa fa-lock"></i>
                            <span>Security</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </section>
</div>

