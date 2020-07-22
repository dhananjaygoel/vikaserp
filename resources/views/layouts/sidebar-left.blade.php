<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">

            <?php
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if (getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if (getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if (getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if (getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if (getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';

            $ip = App\Security::all();
            if (isset($ip) && !$ip->isEmpty()) {
                foreach ($ip as $key => $value) {
                    $ip_array[$key] = $value->ip_address;
                }
            } else {
                $ip_array = array($ipaddress);
            }
            // print_r($ip_array);
            // exit;
            ?>
            @if(Auth::check() && !Auth::user()->hasOldPassword())
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked" id="menuulbox">
                    <?php
                    $full_name = $_SERVER['PHP_SELF'];
                    $name_array = explode('/', $full_name);
                    $count = count((array)$name_array);
                    $page_name = $name_array[$count - 1];

                    ?>

                    @if(Auth::user())
                    @if(Request::is('*performance*'))
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <li class="{{ (Request::is('*dashboard*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Dashboard">
                        <a href="{{url('dashboard')}}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>

                    @if(isset($performance_index) && $performance_index==true)
                    <li class="{{Request::is('performance/labours/*') || Request::is('performance/') ? 'active' : '' }} ">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-users"></i>
                            <span>Labors</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li class="{{ (Request::is('*performance/labours/*') ? 'active' : '') }}">
                                <a href="{{url('performance/labours')}}" > Labours </a>
                            </li>
                            <li class="{{ (Request::is('*performance/labours/labour-performance*') ? 'active' : '') }}">
                                <a href="{{url('performance/labours/labour-performance')}}" > Performance </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{Request::is('performance/loaded-by/*') ? 'active' : '' }} ">
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-male"></i>
                            <span>Loaded By</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            <li class="{{ (Request::is('*performance/loaded-by/*') ? 'active' : '') }}">
                                <a href="{{url('performance/loaded-by')}}" > Loaded-by </a>
                            </li>
                            <li class="{{ (Request::is('*performance/loaded-by/loaded-by-performance*') ? 'active' : '') }}">
                                <a href="{{url('performance/loaded-by/loaded-by-performance')}}" > Performance </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @endif
                    @elseif(Request::is('*account*') || Request::is('*receipt-master*') || Request::is('*due-payment*') || Request::is('*customer_details*'))
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <li class="{{ (Request::is('account') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Collection Users">
                        <a href="{{url('account')}}">
                            <i class="fa fa-users"></i>
                            <span>Collection Users</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role_id == 0|| Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                    <li class="{{ (Request::is('receipt-master') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Receipt Master">
                        <a href="{{url('receipt-master')}}">
                            <i class="fa fa-print"></i>
                            <span>Receipt Master</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    <!--                        @if(Auth::user()->role_id == 0|| Auth::user()->role_id == 1)
                                            <li class="{{ (Request::is('due-payment') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Due Payment">
                                                <a href="{{url('due-payment')}}">
                                                    <i class="fa fa-money"></i>
                                                    <span>Due Payment</span>
                                                    <span class="label label-info label-circle pull-right"></span>
                                                </a>
                                            </li>
                                            @endif-->
                    @if(Auth::user()->role_id == 0|| Auth::user()->role_id == 1)
                    <li class="{{ (Request::is('due-payment') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Due Payment">
                        <a href="{{url('due-payment')}}">
                            <i class="fa fa-money"></i>
                            <span>Customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @else
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 2)
                    <li class="{{ (Request::is('*dashboard*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Dashboard">
                        <a href="{{url('dashboard')}}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <li class="{{ (Request::is('*users*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Users">
                        <a href="{{url('/')}}/users">
                            <i class="fa fa-user"></i>
                            <span>Users</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 )
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <li class="{{ (Request::is('customers*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Customers">
                        <a href="{{url('customers')}}">
                            <i class="fa fa-male"></i>
                            <span>Customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 )
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <li class="{{ (Request::is('pending_customers*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Pending Customers">
                        <a href="{{url('pending_customers')}}">
                            <i class="fa fa-book"></i>
                            <span>Pending customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 5)
                    <li class="{{ (Request::is('*inquiry*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Inquiry">
                        <a href="{{url("inquiry")}}">
                            <i class="fa fa-info"></i>
                            <span>Inquiry</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 5 || Auth::user()->role_id == 8 || Auth::user()->role_id == 9 ||  Auth::user()->role_id == 2 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 5)
                    <li class="<?php
                    if (Request::is('orders*') || Request::is('*delivery_order*') || Request::is('*delivery_challan*') || Request::is('*pending_delivery_order*') || Request::is('*pending_order_report*') || Request::is('*sales_daybook*')|| Request::is('*daily_pro_forma_invoice*')) {
                        echo 'active';
                    }
                    ?>">
                        @if(Auth::user()->role_id != 6 && Auth::user()->role_id != 7 )
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Order</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        <ul class="submenu">
                            @if(Auth::user()->role_id == 5)
                            <li class="{{ (Request::is('*orders*') ? 'active' : '') }}">
                                <a href="{{url('orders')}}" >
                                    Order
                                </a>
                            </li>

                            @endif
                            
                            @if(Auth::user()->role_id == 9 ||  Auth::user()->role_id == 2 || Auth::user()->role_id == 8 || Auth::user()->role_id == 0 ||Auth::user()->role_id == 1  || Auth::user()->role_id == 3 || Auth::user()->role_id == 4 )
                                @if(Auth::user()->role_id != 8 && Auth::user()->role_id != 9)
                                @if(Auth::user()->role_id != 2 && Auth::user()->role_id != 3 && Auth::user()->role_id != 4)
                                <li class="{{ (Request::is('*orders*') ? 'active' : '') }}">
                                    <a href="{{url('orders')}}" >
                                        Order
                                    </a>
                                </li>
                                @endif
                                @endif
                            <li class="{{ (Request::is('delivery_order*') ? 'active' : '') }}">
                                <a href="{{url('delivery_order')}}">
                                    Delivery Order
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->role_id == 0 ||Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4 )

                            <li class="{{ (Request::is('*delivery_challan*') ? 'active' : '') }}">
                                <a href="{{url('delivery_challan')}}">
                                    Delivery Challan
                                </a>
                            </li>

                            @endif
                            @if(Auth::user()->role_id == 0 ||Auth::user()->role_id == 1 )
                            <li class="{{ (Request::is('*pending_delivery_order*') ? 'active' : '') }}">
                                <a href="{{url('pending_delivery_order')}}">
                                    Pending Delivery Order Report
                                </a>
                            </li>
                            @endif
                            @if((Auth::user()->role_id == 0 ||Auth::user()->role_id == 1) && ( Auth::user()->role_id != 3))
                            <li class="{{ (Request::is('*sales_daybook*') ? 'active' : '') }}">
                                <a href="{{url('sales_daybook')}}">
                                    Sales Daybook
                                </a>
                            </li>
                            @endif
                                @if((Auth::user()->role_id == 0 ||Auth::user()->role_id == 1) && ( Auth::user()->role_id != 3))
                                    <li class="{{ (Request::is('*daily_pro_forma_invoice*') ? 'active' : '') }}">
                                        <a href="{{url('daily_pro_forma_invoice')}}">
                                            Daily Pro Forma Invoice
                                        </a>
                                    </li>
                            @endif

                            <!--                            <li class="{{ (Request::is('*pending_order_report*') ? 'active' : '') }}">
                                                            <a href="{{url('pending_order_report')}}">
                                                                Pending Order Report
                                                            </a>
                                                        </li>-->
                        </ul>
                        @endif
                    </li>
                    @endif
                    @endif
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0)
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4)
                    <li class="<?php
                    if (Request::is('*purchase_orders*') || Request::is('*purchaseorder_advise*') || Request::is('*purchase_challan*') || Request::is('*purchase_order_report*') || Request::is('*purchase_order_daybook*') || Request::is('*pending_purchase_advice*') || Request::is('*purchase_estimate*')) {
                        echo 'active';
                    }
                    ?>">
                        @if(Auth::user()->role_id != 6 && Auth::user()->role_id != 7)
                        <a href="#" class="dropdown-toggle">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Purchase Order</span>
                            <i class="fa fa-chevron-circle-right drop-icon"></i>
                        </a>
                        @endif
                        <ul class="submenu">
                            @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 4)
                            <li class="{{ (Request::is('*purchase_orders*') ? 'active' : '') }}">
                                <a href="{{url('purchase_orders')}}">
                                    Purchase Order
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->role_id == 0 ||Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4)
                            <li class="{{ (Request::is('*purchaseorder_advise*') ? 'active' : '') }}">
                                <a href="{{url('purchaseorder_advise')}}">
                                    Purchase Advice
                                </a>
                            </li>

                            @endif
                            @if(Auth::user()->role_id == 0 ||Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3 || Auth::user()->role_id == 4)
                            <li class="{{ (Request::is('*purchase_challan*') ? 'active' : '') }}">
                                <a href="{{url("purchase_challan")}}">
                                    Purchase Challan
                                </a>
                            </li>
                            @endif

                            <!--                            <li class="{{ (Request::is('*purchase_order_report*') ? 'active' : '') }}">
                                                            <a href="{{url('purchase_order_report')}}" >
                                                                Purchase Order Report
                                                            </a>
                                                        </li>-->
                            @if(Auth::user()->role_id == 0 ||Auth::user()->role_id == 1 || Auth::user()->role_id == 2 || Auth::user()->role_id == 3)
                            <li class="{{ (Request::is('*pending_purchase_advice*') ? 'active' : '') }}">
                                <a href="{{url('pending_purchase_advice')}}">
                                    Pending Purchase Advise Report
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                            <li class="{{ (Request::is('*purchase_order_daybook*') ? 'active' : '') }}">
                                <a href="{{url('purchase_order_daybook')}}">
                                    Purchase Daybook
                                </a>
                            </li>
                            @endif

                                @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1 || Auth::user()->role_id == 4)
                                    <li class="{{ (Request::is('*purchase_estimate*') ? 'active' : '') }}">
                                        <a href="{{url('purchase_estimate')}}">
                                            Purchase Estimate
                                        </a>
                                    </li>
                                @endif
                        </ul>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <li class="<?php
                    if (Request::is('*product_category*') || Request::is('*product_sub_category*') || Request::is('*thickness*')) {
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
                                <a href="{{url('/')}}/product_category" >
                                    Product Category
                                </a>
                            </li>
                            <li class="{{ (Request::is('*product_sub_category*') ? 'active' : '') }}">
                                <a href="{{url('/')}}/product_sub_category">
                                    Product Size
                                </a>
                            </li>
                            <li class="{{ (Request::is('*thickness*') ? 'active' : '') }}">
                                <a href="{{url('/')}}/thickness">
                                    Product Thickness
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 ||Auth::user()->role_id == 1)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
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
                    @if(Auth::user()->role_id == 0)
                    <li class="{{ (Request::is('territory*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Territory">
                        <a href="{{url("territory")}}">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>Territory</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ (Request::is('inventory') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Inventory">
                        <a href="{{url("inventory")}}">
                            <i class="fa fa-cubes" aria-hidden="true"></i>
                            <span>Inventory</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('inventory_report*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Inventory Report">
                        <a href="{{url("inventory_report")}}">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            <span>Inventory Report</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('inventory_price_list*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Inventory Price List">
                        <a href="{{url("inventory_price_list")}}">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            <span>Inventory Price List</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 7)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0)
                    <li class="<?php if (Request::is('vehicle-list*') || Request::is('pa-vehicle-list*')) {
                        echo 'active';
                        } ?> menutooltip" data-placement='right' data-original-title="Truck List">
                        <a href="{{url('vehicle-list')}}">
                            <i class="fa fa-truck"></i>
                            <span>Truck List</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0 || Auth::user()->role_id == 1)
                    <div style="display:none">
                        <!--                        Do not display customer Module-->
                        <li class="{{ (Request::is('*customer_manager*') ? 'active' : '') }}">
                            <a href="{{url("customer_manager")}}">
                                <i class="fa fa-asterisk"></i>
                                <span>Customer Manager</span>
                                <span class="label label-info label-circle pull-right"></span>
                            </a>
                        </li>
                    </div>
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 0)
                    <li class="{{ (Request::is('*security*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Security">
                        <a href="{{url("security")}}">
                            <i class="fa fa-lock"></i>
                            <span>Security</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 10)
                    @if((isset($ip_array) && in_array($ipaddress, $ip_array)) || Auth::user()->role_id == 0)
                    <li class="{{ (Request::is('bulk-delete') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Bulk Delete">
                        <a href="{{url('bulk-delete')}}">
                            <i class="fa fa-trash-o"></i>
                            <span>Bulk Delete</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @endif
                    @endif
                    @endif
                    @if(Auth::user()->role_id == 6)
                    <li class="{{ (Request::is('due-payment*') ? 'active' : '') }} menutooltip" data-placement='right' data-original-title="Due Payment">
                        <a href="{{url('due-payment')}}">
                            <i class="fa fa-money"></i>
                            <span>Customers</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif


                        @if(Auth::user()->role_id == 0)
                            <li class="{{ (Request::is('gst*') || Request::is('hsn*')) ? 'active' : '' }} menutooltip" data-placement='right' data-original-title="">



                                <a href="#" class="dropdown-toggle">
                                    <i class="fa fa-inr"></i>
                                    <span>Tax</span>
                                    <i class="fa fa-chevron-circle-right drop-icon"></i>
                                </a>
                                <ul  class="submenu">
                                    <li class="{{ (Request::is('*gst*') ? 'active' : '') }}">
                                        <a href="{{url('gst')}}">
                                            GST
                                        </a>
                                    </li>
                                    <li class="{{ (Request::is('*hsn*') ? 'active' : '') }}">
                                        <a href="{{url("hsn")}}">
                                            HSN
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                </ul>
            </div>
            @endif
        </div>
    </section>
</div>
