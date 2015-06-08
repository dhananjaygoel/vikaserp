<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="{{URL::to('admin/users')}}" id="logo" class="navbar-brand">
            {!! HTML::image('/resources/assets/img/logo1.png' , 'Logo', array('class' => 'normal-logo logo-white')) !!}
            {!! HTML::image('/resources/assets/img/logo-black.png' , 'Logo', array('class' => 'normal-logo logo-black')) !!}
            {!! HTML::image('/resources/assets/img/logo-small.png' , 'Logo', array('class' => 'small-logo hidden-xs hidden-sm hidden')) !!}
        </a>

        <div class="clearfix">
            <button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="fa fa-bars"></span>
            </button>

            <div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
                <ul class="nav navbar-nav pull-left">
                    <li>
                        <a class="btn" id="make-small-nav">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="nav-no-collapse pull-right" id="header-nav">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="show_nav_dropdown();" id="navbar_dropdown">
                            {!! HTML::image('/resources/assets/img/user1.png' , 'User') !!}
                            <?php if (Auth::check()) { ?>
                                <span class="hidden-xs">{{Auth::user()->first_name}}</span> <b class="caret"></b>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu" id="nav_menu_dropdown">
                            <li><a href="{{url('admin/change_password/create')}}"><i class="fa fa-envelope-o"></i>Change Password</a></li>
                            <li><a href="{{url('logout')}}"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </li>
                    <li class="hidden-xxs">
                        <a class="btn" href="{{url('logout')}}">
                            <i class="fa fa-power-off"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>