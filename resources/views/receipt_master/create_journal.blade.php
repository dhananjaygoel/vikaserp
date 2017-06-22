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
                    <h1 class="pull-left">
                        @if(isset($type)) 
                        <?php
                        $val = "Receipt Master";
                        if ($type == 1) {
                            $val = "JOURNAL";
                        } else if ($type == 2) {
                            $val = "Bank";
                        } else if ($type == 3) {
                            $val = "Cash";
                        }
                        ?>
                        @endif
                        {{ strtoupper($val) }}
                    </h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('id'=>'add_receipt','method'=>'post','action'=>'ReceiptMasterController@store'))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="receipt_type" value="{{$type}}">
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
                            <?php
                            $old_tally_user = Input::old('tally_users');
                            $old_settle_amount = Input::old('settle_amount');
                            ?>                                
                            <div class="row" id="st-settle-container">
                                <label for="credited_to">Credited To</label>
                                @if(isset($old_tally_user) && !empty($old_tally_user))
                                @foreach($old_tally_user as $key=>$otu)
                                <div class="st-settle-block">
                                    <div class="col-md-12" style="margin:10px 0;padding:0">
                                        <div class="col-md-3">
                                            <select data-lastsel="" class="st_select_tally_user form-control" name="tally_users[]">                                                    
                                                <option value="">Select Tally User</option>
                                                @if(isset($tally_users))
                                                @foreach($tally_users as $tally_user)
                                                <option value="{{$tally_user->id}}" {!! $otu== $tally_user->id ? 'selected':'' !!}>{{$tally_user->tally_name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-3 settle-input-elem">
                                            <input class="form-control" placeholder="Amount" onkeypress=" return numbersOnly(this, event, false, false);" name="settle_amount[{{$otu}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$otu])? $old_settle_amount[$otu] : '' ): '' !!}" type="text">
                                        </div>
                                        <div class="col-md-4 narration-input-elem">
                                            <input class="form-control" placeholder="Narration" name="narration[{{$otu}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$otu])? $old_settle_amount[$otu] : '' ): '' !!}" type="text">
                                        </div>
                                        <div class="col-md-1 action_btn">
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_u st-border-bottom-none"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
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
                                        <div class="col-md-3 settle-input-elem">
                                            <input class="form-control" placeholder="Amount" onkeypress=" return numbersOnly(this, event, false, false);" name="settle_amount[]" value="" type="text">
                                        </div>
                                        <div class="col-md-4 narration-input-elem">
                                            <input class="form-control" placeholder="Narration" name="narration[]" value="" type="text">
                                        </div>
                                        <div class="col-md-1 action_btn">
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_u st-border-bottom-none"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>                                
                        </div>  




                        <div class="form-group">
                            <div class="row" id="st-settle-container_d">
                                <label for="debited_to">Debited To</label>
                                <select style="display:none" id="st_select_tally_user_master_d" name="tally_users_d">
                                    <option value="">Select Tally User</option>
                                    @if(isset($tally_users))
                                    @foreach($tally_users as $tally_user)
                                    <option value="{{$tally_user->id}}">{{$tally_user->tally_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="st-settle-block_d" >
                                    <div class="col-md-12" style="margin:10px 0;padding:0">
                                        @if(isset($debited_to) && $type != 1)
                                        <div class="col-md-3">
                                            <select class="form-control" name="tally_users_d[]" id="debited_to">                                            
                                                @if(isset($tally_users) && $type == 1)
                                                <option value="">Select Tally User</option>
                                                @foreach($tally_users as $tally_user)
                                                <option value="{{$tally_user->id}}" {!! Input::old('debited_to') ? (Input::old('debited_to') == $tally_user->id ? 'selected' : '') : '' !!}>{{$tally_user->tally_name}}</option>
                                                @endforeach
                                                @endif
                                                @if(isset($debited_to) && $type != 1)
                                                <option value="">Select {{$val}} List</option>
                                                @foreach($debited_to as $debite)
                                                <option value="{{$debite->id}}" {!! Input::old('debited_to') ? (Input::old('debited_to') == $debite->id ? 'selected' : '') : '' !!}>{{$debite->debited_to}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>    
                                        @else
                                        <div class="col-md-3"> 
                                            <select class="form-control st_select_tally_user_d" name="tally_users_d[]">                                            
                                                @if(isset($tally_users) && $type == 1)
                                                <option value="">Select Tally User</option>
                                                @foreach($tally_users as $tally_user)
                                                <option value="{{$tally_user->id}}" {!! Input::old('debited_to') ? (Input::old('debited_to') == $tally_user->id ? 'selected' : '') : '' !!}>{{$tally_user->tally_name}}</option>
                                                @endforeach
                                                @endif
                                                @if(isset($debited_to) && $type != 1)
                                                <option value="">Select {{$val}} List</option>
                                                @foreach($debited_to as $debite)
                                                <option value="{{$debite->id}}" {!! Input::old('debited_to') ? (Input::old('debited_to') == $debite->id ? 'selected' : '') : '' !!}>{{$debite->debited_to}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>    
                                        <div class="col-md-3 settle-input-elem">
                                            <input class="form-control" placeholder="Amount" onkeypress=" return numbersOnly(this, event, false, false);" name="settle_amount_d[]" value="" type="text">
                                        </div>
                                        <div class="col-md-4 narration-input-elem">
                                            <input class="form-control" placeholder="Narration" name="narration_d[]" value="" type="text">
                                        </div>
                                        <div class="col-md-1 action_btn">
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_d st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_d st-border-bottom-none"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                        @endif
                                    </div>    
                                </div>    
                            </div>                          
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" id="add_receipt_btn" >Submit</button>
                                <a href="{{url()}}/receipt-master" class="btn btn-default form_button_footer">Back</a>
                            </div>
                            <div class="clearfix"></div>
                            <!--</form>-->
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection