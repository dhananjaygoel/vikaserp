<header class="navbar" id="header-navbar">
    <div class="container">
        <a href="{{url('/')}}" id="logo" class="navbar-brand">
            {!! HTML::image('assets/img/logo1.png' , 'Logo', array('class' => 'normal-logo logo-white')) !!}
           
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
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{asset('assets/img/samples/scarlet-159.png')}}" alt=""/>
                            <span class="hidden-xs"> {{(Auth::user()) ? Auth::user()->first_name : ''}}&nbsp;{{(Auth::user())?Auth::user()->last_name:''}}</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('change_password') }}"><i class="fa fa-envelope-o"></i>Change Password</a></li>
                            <li><a href="{{ url('logout') }}"><i class="fa fa-power-off"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
