<style>
/* Notifications */

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
<?php 
$notif = '';
$count = 0;

$ip = App\Security::all();
$ip_array = [];
if (count((array)$ip) > 0) {
    foreach ($ip as $key => $value) {
        $ip_array[$key] = $value->ip_address;
    }

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
}    
?>
</style>
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
                    <li class="dropdown notify-dropdown">
                        
                    <?php 
                        if(Auth::user()->role_id == 0){
                            $notif = DB::table('notifications')->whereNotIn('id',function($query){
                                $query->select('notification_id')->from('notification_read_status')
                                ->where('read_by',Auth::user()->id);
                            })->where('assigned_by','<>',Auth::user()->id)->orderBy('id', 'DESC')->get();
                            $count = DB::table('notifications')->whereNotIn('id',function($query){
                                $query->select('notification_id')->from('notification_read_status')
                                ->where('read_by',Auth::user()->id);
                            })->where('assigned_by','<>',Auth::user()->id)->count();
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
                        <ul class="dropdown-menu" style="height:200px;width:300px;right:0;left:auto;">
                        <div class="notif_bar">
                                <span style="padding:5px;">Notifications ({{$count}})</span>
                            </div>
                            <div style="text-align:center">
                                <i class="fa fa-bell-o" aria-hidden="true" style="font-size:4em;color:#3498db;padding:18px 0"></i>
                            </div>
                            <div style="text-align:center">
                                <li style="font-size:2em;">No Notifications</li>
                                <li style="font-size:15px;">You currently have no new notifications.</li>
                            </div>
                        </ul>
                    @else
                        <a href="#" class="dropdown-toggle notification show-count" data-count="{{$count}}" data-toggle="dropdown" onclick="return notification_msg();"></a>
                        <!-- <div class="notification" aria-hidden="true"></div> -->
                        <ul class="dropdown-menu navbar_right" style="right:0;left:auto;width:300px;padding:0">
                            <div class="notif_bar">
                                <span style="padding:5px;">Notifications ({{$count}})</span>
                                <a href="" class="float-right text-light" style="font-size:12px;padding-left:60px;" onclick="return read_notification(0);">Mark all as read</a>
                            </div>
                            <div id="notify_id" style="overflow-y: scroll;min-height:100px;max-height:270px;">
                                @foreach($notif as $notify)
                                <li class="notification_msg notification_navbar" style="padding:10px 10px;display:flex;align-items:center;">
                                    <div class="notify_icon" style="display:flex;width: 40px;font-size: 30px;height: 45px;color:#3498db;">
                                        <span class="icon"><i class="fa fa-comments-o" aria-hidden="true"></i></span>
                                    
                                    </div>
                                    <div class="notify_msg" style="width:230px;">
                                        <div class="title" style="color:#000;font-weight:600;font-size:13px;">Order assigned<small class="date"style="float:right;font-size:8px;">{{date('d-m-Y h:i A', strtotime($notify->created_at))}}</small></div>
                                        <div class="msg_body" style="font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            <input type="hidden" id="notif_id" value="{{$notify->id}}">  
                                            <a href="{{URL::action('DeliveryOrderController@show',['delivery_order' => $notify->order_id])}}" data-original-title="{{$notify->msg}}" onclick="return read_notification($notify->order_id);">{{$notify->msg}}</a>
                                        </div>
                                    </div>
                                </li>
                                
                                @endforeach
                            </div>
                            <!-- <li>Notify here 2</li> -->
                        </ul>
                    @endif
                    </li>
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
<script type="text/javascript">

function get_fb(){
    var feedback = $.ajax({
        type: 'GET',
        url: url + '/supervisor_count'
        }).success(function (data) {
            $('.notification').attr('data-count',data);
            // alert(data);
        })
}      

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
            var view='';
            $.each(data,function(index,element){
            view +='<li class="notification_msg navbar_right" style="padding:10px 10px;display:flex;align-items:center;">'
                     +'           <div class="notify_icon" style="display:flex;width: 40px;font-size: 30px;height: 45px;color:#3498db;">'
                     +'               <span class="icon"><i class="fa fa-comments-o" aria-hidden="true"></i></span>'
                                
                     +'           </div>'
                     +'           <div class="notify_msg" style="width:230px;">'
                     +'               <div class="title" style="color:#000;font-weight:600;font-size:13px;">Order assigned<small class="date"style="float:right;font-size:8px;">'+setTimeTo12Hr(element.created_at)+'</small></div>'
                     +'               <div class="msg_body" style="font-size:11px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'
                     +'                   <input type="hidden" id="notif_id" value="'+element.id+'">'
                     +'                   <a href="'+baseurl+'/delivery_order/'+element.order_id+'" data-original-title="'+element.msg+'" onclick="return read_notification('+element.order_id+');">'+element.msg+'</a>'
                     +'               </div>'
                     +'           </div>'
                     +'       </li>';
            });
            $("#notify_id").html(view);
        }
    });
}
function setTimeTo12Hr(dateTime){
	const timeString = dateTime.split(" ")[1];
    const dateString = dateTime.split(" ")[0];
	const timeString12hr = new Date('1970-01-01T' + timeString + 'Z')
								.toLocaleTimeString({},{timeZone:'UTC',hour12:true,hour:'numeric',minute:'numeric'});
	var newDateTime=dateString.split("-")[2]+"-"+dateString.split("-")[1]+"-"+dateString.split("-")[0]+" "+timeString12hr;
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
            alert(data);
        }
    });
}

setTimeout(function(){get_fb();}, 2000);
</script>