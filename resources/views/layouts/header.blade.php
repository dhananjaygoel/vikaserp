<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="{{URL::to('bookings')}}" id="logo" class="navbar-brand">
            {!! HTML::image('/resources/assets/frontend/img/logo1.png' , 'Logo', array('class' => 'normal-logo logo-white')) !!}
            {!! HTML::image('/resources/assets/frontend/img/logo-black.png' , 'Logo', array('class' => 'normal-logo logo-black')) !!}
            {!! HTML::image('/resources/assets/frontend/img/logo-small.png' , 'Logo', array('class' => 'small-logo hidden-xs hidden-sm hidden')) !!}
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
            @if(Auth::check())
            <div class="nav-no-collapse pull-right" id="header-nav">
                <ul class="nav navbar-nav pull-right">

                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="img/samples/scarlet-159.png" alt=""/>
                            <span class="hidden-xs"> Admin</span> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">

                            <li><a href="#"><i class="fa fa-envelope-o"></i>Change Password</a></li>
                            <li><a href="#"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

            @endif
        </div>
    </div>
</header>