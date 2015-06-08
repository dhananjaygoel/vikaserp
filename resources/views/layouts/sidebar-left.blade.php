<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">

            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <!--	<li class="active">
                                    <a href="index.html">
                                            <i class="fa fa-dashboard"></i>
                                            <span>Dashboard</span>
                                            <span class="label label-info label-circle pull-right">28</span>
                                    </a>
                            </li>-->
                    <li class="{{ (Request::is('*bookings*') ? 'active' : '') }}">
                        <a href="{{url("bookings")}}">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Booking</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @if(Auth::check())
                    <li class="{{ (Request::is('*booking_history*') ? 'active' : '') }}">
                        <a href="{{url("booking_history")}}">
                            <i class="fa fa-history"></i>
                            <span>My Booking History</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*booking_limits*') ? 'active' : '') }}">
                        <a href="{{url("booking_limits")}}">
                            <i class="fa fa-codepen"></i>
                            <span>Booking In Process</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ (Request::is('*operations*') ? 'active' : '') }}">
                        <a href="{{url("operations")}}">
                            <i class="fa fa-tachometer"></i>
                            <span>Operation</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @if(Auth::check())
                    <li class="{{ (Request::is('*user_request*') ? 'active' : '') }}">
                        <a href="{{url("user_request")}}">
                            <i class="fa fa-map-marker"></i>
                            <span>Request New Location</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*balance*') ? 'active' : '') }}">
                        <a href="{{url("balance")}}">
                            <i class="fa fa-inr"></i>
                            <span>Balance</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>

                    <li class="{{ (Request::is('*show_notification*') ? 'active' : '') }}">
                        <a href="{{url("show_notification")}}">
                            <i class="fa fa-bell"></i>
                            <span>Notification</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                    @if(Auth::check())
                    <li >
                        <a href="{{url("logout")}}">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="{{url("signin")}}">
                            <i class="fa fa-sign-in"></i>
                            <span>Login</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </section>
</div>