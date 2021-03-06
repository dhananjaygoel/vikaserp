<style>
/* Notifications */
.navbar-nav > li > .dropdown-menu:after {
    content: "";
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #fff;
    position: absolute;
    top: -9px;
    left: auto;
    right: 13px;
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    opacity: 1;
    overflow: visible;
    visibility: visible;
}

.navbar-nav .open .notification-nav-right {
  left: auto; right: 0; width:350px;
}

.notification-nav-right .notif_bar {
  height: auto;
  width: 100%;
  display: flex;
  justify-content: space-between;
  padding: 13px 14px;
  border-bottom: 1px solid #d5d5d5;
}

.notification-nav-right .notif_bar a {
  color: #344644;
  font-size: 12px; 
}

.notification-nav-right .notif_bar span {
  font-weight: 600; color:#000000a6;
}

#notify_id {
  overflow-y: scroll;
    min-height: 100px;
    max-height: 270px;
}

#notify_id ul {padding-left: 0px;}
#notify_id ul li {
  padding: 10px;
  display: flex;
  align-items: center;
}
#notify_id ul li .notify_icon {
  display: flex;
  width: 40px;
  font-size: 30px;
  height: 40px;
  color: #3498db;
  margin-right: 10px;
}

#notify_id ul li .notify_icon .icon {
  background-color: #3498db;
  border-radius: 50%;
  height: 40px;
  width: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}

#notify_id ul li .notify_icon .icon i {font-size: 22px;}

#notify_id ul li .notify-content {
  display: flex;
  justify-content: space-between;
}

#notify_id ul li .notify_msg {
  width: 100%;
  padding-right: 8px;
}

#notify_id ul li .notify_msg .title {
  color: #000000a6;
  font-weight: 600;
  font-size: 14px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: 170px;
}

#notify_id ul li .notify_msg .msg_body {
  color: #333;
  font-size: 12px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  width: 220px;
}

#notify_id ul li .notify_msg .msg_body a {
  color: #999;
}

#notify_id ul li .notify-content .notify-time {
  font-size: 12px;
  color: #999;
  display: flex;
  height: 15px;
}

#notify_id ul li .notify-content .notify-time i {
  color: #2ecc71;
  font-size: 9px;
  top: 3px;
  margin-left: 5px;
  margin-bottom:0px
}

.blank-notification {
  display: flex;
  align-items: center;
  flex-direction: column;
  justify-items: center;
  text-align: center;
  margin: 2em auto;
}

.blank-notification i {
  font-size: 28px;
  color: #999;
  -webkit-animation: ring 4s .7s ease-in-out;
  -webkit-transform-origin: 50% 4px;
  -moz-animation: ring 4s .7s ease-in-out;
  -moz-transform-origin: 50% 4px;
  animation: ring 4s .7s ease-in-out;
  transform-origin: 50% 4px;
}

.blank-notification p.title {
  font-weight: 600;
  font-size: 18px;
  margin: 1em auto 5px auto;
}

.blank-notification p {
  color: #999;
  font-size: 14px;
}




.notification {
    display: inline-block;
    position: relative;
    padding-top: 7px;
    /* background: #3498db; */
    border-radius: 0.2em;
    font-size: 1.5em !important;
    /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); */
}

.notification::before, 
.notification::after {
    color: #fff;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

.notification::before {
    display: block;
    content: "\f0f3";
    font-family: "FontAwesome";
    transform-origin: top center;
}

.notification::after {
    font-family: Arial;
    font-size: 10px;
    font-weight: 700;
    position: absolute;
    top: 5px;
    right: 5px;
    padding: 5% 10%;
    line-height: 100%;
    border: 2px #fff solid;
    border-radius: 60px;
    background: #e74c3c;
    opacity: 0;
    content: attr(data-count);
    opacity: 0;
    transform: scale(0.5);
    transition: transform, opacity;
    transition-duration: 0.3s;
    transition-timing-function: ease-out;
}

.notification.notify::before {
    animation: ring 1.5s ease;
}

.notification.show-count::after {
    transform: scale(1);
    opacity: 1;
}

 .notify_id {
    min-height: 100px;
    max-height: 300px;
    overflow-y: scroll;
}

.notif_bar {
    height: 30px;
    width: 300px;
    background: #fff;
    padding: 5px;
    border-bottom: 1px solid;
}


@keyframes ring {
    0% {
        transform: rotate(35deg);
    }
    12.5% {
        transform: rotate(-30deg);
    }
    25% {
        transform: rotate(25deg);
    }
    37.5% {
        transform: rotate(-20deg);
    }
    50% {
        transform: rotate(15deg);
    }
    62.5% {
        transform: rotate(-10deg);
    }
    75% {
        transform: rotate(5deg);
    }
    100% {
        transform: rotate(0deg);
    }
}
</style>
<?php 
$notif = '';
$count = 0;
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
$otp_validate = Session::has('otp_validate')?Session::has('otp_validate'):false;
?>
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
                @if(in_array($ipaddress, $ip_array) || Auth::user()->role_id == 0)
                    @if(Auth::user()->role_id == 0 || Auth::user()->role_id == 8 || Auth::user()->role_id == 9)
                    <li class="dropdown notify-dropdown">
                        
                    <?php 
                        if(Auth::user()->role_id == 0){
                            $notif = DB::table('notifications')->where('order_type','load_truck')
                                ->whereNotIn('id',function($query){
                                $query->select('notification_id')->from('notification_read_status')
                                ->where('read_by',Auth::user()->id);
                            })->where('assigned_by',Auth::user()->id)->orderBy('id', 'DESC')->get();
                            $count = DB::table('notifications')->where('order_type','load_truck')
                                ->whereNotIn('id',function($query){
                                $query->select('notification_id')->from('notification_read_status')
                                ->where('read_by',Auth::user()->id);
                            })->where('assigned_by',Auth::user()->id)->count();
                        }elseif(Auth::user()->role_id == 8 || Auth::user()->role_id == 9){
                            $notif = DB::table('notifications')->whereNotIn('id',function($query){
                                $query->select('notification_id')->from('notification_read_status')
                                ->where('read_by',Auth::user()->id);
                            })->where('assigned_to',Auth::user()->id)->where('assigned_by','<>',Auth::user()->id)->orderBy('id', 'DESC')->get();
                            $count = DB::table('notifications')->whereNotIn('id',function($query){
                                $query->select('notification_id')->from('notification_read_status')
                                ->where('read_by',Auth::user()->id);
                            })->where('assigned_to',Auth::user()->id)->where('assigned_by','<>',Auth::user()->id)->count();
                        }
                    
                    ?>
                    @if($count == 0)
                    <a href="#" class="dropdown-toggle notification" data-toggle="dropdown" ></a>
                        <ul class="dropdown-menu navbar_right notification-nav-right">
                            <div class="notif_bar">
                                <span>Notifications ({{$count}})</span>
                            </div>
                            <div class="blank-notification">
                                <i class="fa fa-bell-o" aria-hidden="true"></i>
                                <p class="title">No Notifications</p>
                                <p>You currently have no new notifications.</p>
                            </div>
                        </ul>
                    @else
                        <a href="#" class="dropdown-toggle notification show-count" data-count="{{$count}}" data-toggle="dropdown" onclick="return notification_msg();"></a>
                        <!-- <div class="notification" aria-hidden="true"></div> -->
                        <ul class="dropdown-menu navbar_right notification-nav-right">
                            <!-- <div class="notif_bar">
                                <span>Notifications ({{$count}})</span>
                                <a href="" class="float-right text-light" onclick="return read_notification(0);">Mark all as read</a>
                            </div>
                            <div id="notify_id">
                                <ul id="notify_id_ul">
                                    @foreach($notif as $notify)
                                    <li class="notification_msg notification_navbar">
                                        <div class="notify_icon">
                                            <span class="icon"><i class="fa fa-comments-o" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="notify-content">
                                            <div class="notify_msg">
                                                <div class="notify-time">
                                                    <p>Comment</p>
                                                    <span style="margin-left:65px;">{{date('d-m-Y h:i A', strtotime($notify->created_at))}}<i class="fa fa-circle"></i></span>
                                                </div>
                                                <div class="title">Order Assigned</div>
                                                <div class="msg_body">
                                                    <input type="hidden" id="notif_id" value="{{$notify->id}}">  
                                                    <a href="{{url('create_load_truck/'.$notify->order_id)}}" title="{{$notify->msg}}" onclick="return read_notification($notify->order_id);">{{$notify->msg}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div> -->
                            <!-- <li>Notify here 2</li> -->
                        </ul>
                    @endif
                    </li>
                    @endif
                @endif
                    <li class="dropdown profile-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{asset('assets/img/samples/scarlet-159.png')}}" alt=""/>
                            <input type ="hidden" value="{{Auth::user()->id}}" id="user">
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
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
<?php 
if(in_array($ipaddress, $ip_array) || Auth::user()->role_id == 0 ){ 
?>
$(document).ready(function () {
setTimeout(function(){get_fb();}, 2000);
});
<?php
}
?>

function notification_msg(){
    var del_boy =$("#user").val();
    var baseurl = $('#baseurl').attr('name');
    $.ajax({
        type: 'get',
        url: url + '/load_notification',
        dataType: 'json',
        data: {
            del_boy:del_boy,
        },
        success: function (data) {
            // alert(data);
            var view ='<div class="notif_bar">'
                +'          <span>Notifications ('+data['count']+')</span>'
                +'          <a href="" class="float-right text-light" onclick="return read_notification(0);">Mark all as read</a>'
                +'      </div>'
                +'      <div id="notify_id">'
                +'          <ul id="notify_id_ul">';
            $.each(data['notif'],function(index,element){
                if(element.order_type == 'load_truck'){
                    var title = 'Truck Loaded';
                }else if(element.order_type == 'delivery_order' || element.order_type == 'supervisor_assigned' || element.order_type == 'delboy_assigned'){
                    var title = 'Order Assigned';
                }
            view +='<li class="notification_msg notification_navbar">'
                +'      <div class="notify_icon" >'
                +'          <span class="icon"><i class="fa fa-comments-o" aria-hidden="true"></i></span>'
                +'      </div>'
                +'      <div class="notify-content">'
                +'          <div class="notify_msg">'
                +'              <div class="notify-time">'
                +'                  <p>Comment</p>'
                +'                  <span style="margin-left:65px;">'+setTimeTo12Hr(element.created_at)+'<i class="fa fa-circle"></i></span>'
                +'              </div>'
                +'              <div class="title">'+title+'</div>'
                +'              <div class="msg_body">'
                +'                  <input type="hidden" id="notif_id" value="'+element.id+'">'
                +'                  <a href="'+baseurl+'/create_load_truck/'+element.order_id+'" title="'+element.msg+'" onclick="return read_notification('+element.order_id+');">'+element.msg+'</a>'
                +'              </div>'
                +'          </div>'
                +'      </div>'
                +' </li>';
            });
            view += '</ul>'
                +'<div>';
            $(".notification-nav-right").html(view);
        }
    });
}
function setTimeTo12Hr(dateTime){
	const timeString = dateTime.split(" ")[1];
    const dateString = dateTime.split(" ")[0];
	const timeString12hr = new Date('1970-01-01T' + timeString + 'Z')
								.toLocaleTimeString({},{timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'});
    var newDateTime=dateString.split("-")[2]+"-"+dateString.split("-")[1]+"-"+dateString.split("-")[0]+" "+timeString12hr;
	// var newDateTime=timeString12hr;
    
	return newDateTime;
}
function read_notification(id){
    // alert(id);
    var user =$("#user").val();
    var notif_id =$("#notif_id").val();
    $.ajax({
        type: 'get',
        url: '/read_notification',
        data: {
            id:id,
            notif_id:notif_id
        },
        success: function(data){
            // alert(data);
        }
    });
}


</script>