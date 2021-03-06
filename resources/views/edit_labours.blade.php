@extends('layouts.master')
@section('title','Edit Labour')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('performance/labours')}}">Labours</a></li>
                    <li class="active"><span>Edit Labour</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                          
                        <form id="" method="POST" action="{{url('performance/labours/'.$labour->id)}}" accept-charset="UTF-8" >
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input name="_method" type="hidden" value="PUT">
                            @if (count($errors->all()) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </ul>
                            </div>
                          
                            @endif
                            @if(Session::has('success'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('success') }} </strong>
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="clearfix"> &nbsp;</div>
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <strong> {{ Session::get('error') }} </strong>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="first_name">First Name<span class="mandatory">*</span></label>
                                <input id="labour_name" class="form-control" placeholder="First Name" name="first_name" value="{{$labour->first_name}}" type="text" maxlength="30" oninput="validateAlpha(this);">
                            </div>
                            
                            <div class="form-group">
                                <label for="labour_name">Last Name<span class="mandatory">*</span></label>
                                <input id="last_name" class="form-control" placeholder="Last Name" name="last_name" value="{{$labour->last_name}}" type="text" maxlength="30" oninput="validateAlpha(this);">
                            </div>
                             
                            
                         

                            <div class="form-group">
                                <label for="phone_number1">Phone number<span class="mandatory">*</span></label>
                                <input id="phone_number" class="form-control" placeholder="Phone number " name="phone_number" value="{{$labour->phone_number}}" type="tel" onkeypress=" return numbersOnly(this,event,false,false);" maxlength="10" >
                            </div>
                            <div class="form-group">
                                <label for="labour_type">Type<span class="mandatory">*</span></label>
                                <select class="form-control" name="labour_type" id="labour_type">
                                  
                                    <option {{($labour->type=='sale'?'selected':'')}} value="sale">Sale</option>
                                    <option {{($labour->type=='purchase'?'selected':'')}} value="purchase">Purchase</option>
                                    <!--<option value="both">Both</option>-->
                                    
                                </select>
                            </div>
                             <hr>
                            <div>
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('performance/labours')}}" class="btn btn-default form_button_footer">Back</a>
                            </div>
                            <div class="clearfix"></div>
                           
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop