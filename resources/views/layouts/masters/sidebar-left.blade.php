<div id="nav-col">
    <section id="col-left" class="col-left-nano">
        <div id="col-left-inner" class="col-left-nano-content">
            <div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">
                <ul class="nav nav-pills nav-stacked">
                    <li class="{{ (Request::is('*users*') ? 'active' : '') }}">
                        <a href="{{URL::to('admin/users')}}" title="Users">
                            <i class="fa fa-user"></i>
                            <span>Users</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*master_scripts*') ? 'active' : '') }}">
                        <a href="{{url('admin/master_scripts')}}" title="Master Script">
                            <i class="fa fa-file"></i>
                            <span>Master Scripts</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*running_scripts*') ? 'active' : '') }}">
                        <a href="{{url('admin/running_scripts')}}" title="Running Script">
                            <i class="fa fa-file"></i>
                            <span>Running Scripts</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*special_scripts*') ? 'active' : '') }}">
                        <a href="{{url('admin/special_scripts')}}" title="Special Script">
                            <i class="fa fa-file"></i>
                            <span>Special Scripts</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*city*') ? 'active' : '') }}">
                        <a href="{{url('admin/city')}}" title="City">
                            <i class="fa fa-map-marker"></i>
                            <span>City</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*request*') ? 'active' : '') }}">
                        <a href="{{url('admin/request')}}" title="Requests">
                            <i class="fa fa-envelope-o"></i>
                            <span>Requests</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*trade_history*') ? 'active' : '') }}">
                        <a href="{{url("admin/trade_history")}}">
                            <i class="fa fa-history"></i>
                            <span>Trade History</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*commission_reports*') ? 'active' : '') }}">
                        <a href="{{url('admin/commission_reports')}}" title="Commissions">
                            <i class="fa fa-flag-o"></i>
                            <span>Commission Reports</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*admin_financials*') ? 'active' : '') }}">
                        <a href="{{url('admin/admin_financials')}}" title="Admin Financials">
                            <i class="fa fa-money"></i>
                            <span>Admin Financials</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*global_exception*') ? 'active' : '') }}">
                        <a href="{{url('admin/global_exception')}}" title="Global Exceptions">
                            <i class="fa fa-globe"></i>
                            <span>Global Exception</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                    <li class="{{ (Request::is('*notifications*') ? 'active' : '') }}">
                        <a href="{{url('admin/notifications')}}" title="Notifications">
                            <i class="fa fa-bell"></i>
                            <span>Notifications</span>
                            <span class="label label-info label-circle pull-right"></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</div>
