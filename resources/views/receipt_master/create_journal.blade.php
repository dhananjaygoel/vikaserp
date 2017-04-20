@extends('layouts.master')
@section('title','Add Receipt')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}">Home</a></li>
                    <li class="active">Receipt Master</li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">JOURNAL</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <hr>
                        <form method="post" action="{{url()}}/receipt-master/store-journal" accept-charset="UTF-8" >
                            @if (count($errors) > 0)
                            <div class="alert alert-warning">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: relative;"><span aria-hidden="true">&times;</span></button>
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif                            
                            <div class="form-group">
                                <select style="display:none" id="st_select_tally_user_master" name="tally_users">
                                    <option value="">Select Tally User</option>
                                    @if(isset($tally_users))
                                        @foreach($tally_users as $tally_user)
                                            <option value="{{$tally_user->id}}">{{$tally_user->tally_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="row" id="st-settle-container">
                                    <div class="st-settle-block">
                                        <div class="col-md-12" style="margin:10px 0;padding:0">
                                            <div class="col-md-3">
                                                <select data-lastsel="" class="st_select_tally_user form-control" name="tally_users[]">
                                                    <option value="">Select Tally User</option>
                                                    @if(isset($tally_users))
                                                        @foreach($tally_users as $tally_user)
                                                            <option value="{{$tally_user->id}}">{{$tally_user->tally_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-md-4 settle-input-elem">
                                                <input class="form-control" placeholder="Settle Amount" name="settle_amount" value="" type="text">
                                            </div>
                                            <div class="col-md-1 action_btn">
                                                <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>  
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="debited_to">Debited To</label>
                                        <select class="form-control" id="debited_to">
                                            <option value="">Select Tally User</option>
                                            <option value="1"> Alice </option>
                                            <option value="2"> Bob </option>
                                        </select>
                                    </div>    
                                </div>    
                            </div>                          
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url()}}/receipt-master" class="btn btn-default form_button_footer">Back</a>
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
@endsection