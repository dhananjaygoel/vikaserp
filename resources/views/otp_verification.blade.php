<style>

    input {
        margin: 0 5px !important;
        text-align: center;
        line-height: 50px !important;
        font-size: 30px !important;
        border: solid 1px #ccc;
        box-shadow: 0 0 5px #ccc inset;
        outline: none;
        width: 10%;
        transition: all .2s ease-in-out;
        border-radius: 3px;
        
        &:focus {
          border-color: purple;
          box-shadow: 0 0 5px purple inset;
        }
        &::selection {
          background: transparent;
        }
    }

    .prompt {
        margin-bottom: 20px;
        font-size: 20px;
        color: black;
        text-align: center;
    }
    .otp-group {
        
        margin: auto;
        text-align: center;
        position: relative;
        display: table;
        border-collapse: separate;
    }
    .wrapper {
        margin: 20px;
        text-align: center;
    }
    .verify-btn {
        /* position: absolute; */
        font-weight: 600 !important;
        text-transform: uppercase;
        text-align: center;
        line-height: 20px !important;
        padding: 10px 20px !important;
        margin: 6px 50px;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    /* Firefox */
    input[type=number] {
    -moz-appearance: textfield;
    }
</style>
@extends('app')
@section('title','OTP Verification')
@include('layouts.includes')

<!-- Favicon -->
<meta http-equiv="cache-control" content="private, max-age=0, no-cache">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="expires" content="0">
<body id="login-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="login">
                    <div class="row">
                        <div class="col-md-12">
                            <header id="login-header">
                                <div id="login-logo">
                                    {!! HTML::image('assets/img/logo.png' , 'Logo') !!}
                                </div>
                            </header>
                            <div id="login-box-inner">
                                <div class="prompt">
                                <?php 
                                    $user = App\User::find(Auth::user()->id);
                                    if ($user) {
                                        $phone_number = $user->mobile_number;
                                    }else{
                                        $phone_number = '0123456789';
                                    }
                                    $masked =  str_pad(substr($phone_number, -4), strlen($phone_number), 'x', STR_PAD_LEFT);
                                ?>
                                    Please enter the OTP you’ve received on {{ $masked }}
                                    <!-- Enter the code sent to your registered mobile number below to log in! -->
                                </div>
                                {!!Form::open(array('method'=>'POST','url'=>url('validate_otp/')))!!}
                                <!-- <form action="{{ URL::action('DashboardController@validate_otp') }}" method="POST"> -->
                                    @if (Session::has('flash_message'))
                                        <div class="alert alert-success alert-success1">
                                            <i class="fa fa-check-circle fa-fw fa-lg"></i>
                                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            {{Session::get('flash_message')}}
                                        </div><br/>
                                    @endif
                                    @if (Session::has('errors'))
                                    <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                        <strong>Whoops!</strong> {{ Session::get('errors') }}                                        
                                    </div>
                                    @endif
                                    <div class="otp-group">
                                        <input maxLength="1" min="0" max="9" pattern="[0-9]{1}" id="digit-1" name="digit-1" data-next="digit-2" type="tel">
                                        <input maxLength="1" min="0" max="9" pattern="[0-9]{1}" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" type="tel">
                                        <input maxLength="1" min="0" max="9" pattern="[0-9]{1}" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" type="tel">
                                        <span > &ndash; </span>
                                        <input maxLength="1" min="0" max="9" pattern="[0-9]{1}" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" type="tel" >
                                        <input maxLength="1" min="0" max="9" pattern="[0-9]{1}" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" type="tel">
                                        <input maxLength="1" min="0" max="9" pattern="[0-9]{1}" id="digit-6" name="digit-6" data-previous="digit-5" type="tel">
                                    </div>
                                    <div class="wrapper">
                                        <button type="submit" class="btn btn-success verify-btn">Verify</button>
                                    </div>
                                    <div class="wrapper">
                                        Didn't receive the code?
                                        <a href="{{ url('resend_otp') }}">Send code again</a><br />
                                        <a href="{{ route('logout') }}">Go Back</a><br />
                                        </div>
                                <!-- </form> -->
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                


                </div>
            </div>
        </div>
    </div>




</body>
</html>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
// $(function() {
//   'use strict';

//   var body = $('body');

//   function goToNextInput(e) {
//     var key = e.which,
//       t = $(e.target),
//       sib = t.next('input');

//     if (key != 9 && (key < 48 || key > 57)) {
//       e.preventDefault();
//       return false;
//     }

//     if (key === 9) {
//       return true;
//     }

//     if (!sib || !sib.length) {
//       sib = body.find('input').eq(0);
//     }
//     sib.select().focus();
//   }

//   function onKeyDown(e) {
//     var key = e.which;

//     if (key === 9 || (key >= 48 && key <= 57)) {
//       return true;
//     }

//     e.preventDefault();
//     return false;
//   }
  
//   function onFocus(e) {
//     $(e.target).select();
//   }

//   body.on('keyup', 'input', goToNextInput);
//   body.on('keydown', 'input', onKeyDown);
//   body.on('click', 'input', onFocus);

// });
$('.otp-group').find('input').each(function() {
    'use strict';
	$(this).attr('maxlength', 1);
	$(this).on('keyup', function(e) {
		var parent = $($(this).parent());
		
		if(e.keyCode === 8 || e.keyCode === 37) {
			var prev = parent.find('input#' + $(this).data('previous'));
			
			if(prev.length) {
				$(prev).select();
			}
		} else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
			var next = parent.find('input#' + $(this).data('next'));
			
			if(next.length) {
				$(next).select();
			} else {
				if(parent.data('autosubmit')) {
					parent.submit();
				}
			}
		}
	});
});

</script>
