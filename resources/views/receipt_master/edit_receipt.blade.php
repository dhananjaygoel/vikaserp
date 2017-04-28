@extends('layouts.master')
@section('title','Edit Receipt')
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
                                if($type == 1){ 
                                    $val = "JOURNAL";
                                }else if($type == 2){
                                    $val = "Bank";
                                }else if($type == 3){
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
                    @if(Session::has('error'))
                    <div class="clearfix"> &nbsp;</div>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong> {{ Session::get('error') }} </strong>
                    </div>
                    @endif
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('id'=>'edit_receipt','method'=>'put','action'=>array('ReceiptMasterController@update',$receipt_id)))!!}
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
                                    $edit_key = [];
                                ?>
                                <div class="row edit_receipt" id="st-settle-container">
                                @if(isset($customer_arr))
                                    @foreach($customer_arr as $key=>$customer)
                                        <?php array_push($edit_key,$key);  ?>
                                        <div class="st-settle-block">
                                            <div class="col-md-12" style="margin:10px 0;padding:0">
                                                <div class="col-md-3">
                                                    <select data-lastsel="" class="st_select_tally_user form-control" name="tally_users[]">
                                                        @if(isset($tally_users))
                                                        @foreach($tally_users as $tally_user)
                                                            @if($tally_user->id == $key)
                                                                <option value="{{$tally_user->id}}" >{{$tally_user->tally_name}}</option>
                                                            @endif
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4 settle-input-elem">
                                                    <input class="form-control" placeholder="Settle Amount" name="settle_amount[{{$key}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$key])? $old_settle_amount[$key] : $customer ): $customer !!}" type="text">
                                                </div>
                                                <div class="col-md-1 action_btn">
                                                    <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                                    <a href="javascript:void(0)" style="border-bottom:none" class="btn st-border-bottom-none delete_customer_receipts" data-receipt_id='{{$receipt_id}}' data-customer_id='{{$key}}'><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                @if(isset($old_tally_user) && !empty($old_tally_user))
                                    @foreach($old_tally_user as $key=>$otu)
                                    @if(false === $key = array_search($otu, $edit_key))
                                        @if(!isset($old_settle_amount[$otu]))
                                            <?php $old_settle_amount = $customer_arr; ?>
                                        @endif
                                        <div class="st-settle-block">
                                            <div class="col-md-12" style="margin:10px 0;padding:0">
                                                <div class="col-md-3">
                                                    <select data-lastsel="{{$otu}}" class="st_select_tally_user form-control" name="tally_users[]">
                                                        <option value="">Select Tally User</option>
                                                        @if(isset($tally_users))
                                                        @foreach($tally_users as $key_val=>$tally_user)
                                                           <option value="{{$tally_user->id}}" {!! $otu== $tally_user->id ? 'selected':'' !!}>{{$tally_user->tally_name}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4 settle-input-elem">
                                                    <input class="form-control" placeholder="Settle Amount" name="settle_amount[{{$otu}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$otu])? $old_settle_amount[$otu] : '' ): '' !!}" type="text">
                                                </div>
                                                <div class="col-md-1 action_btn">
                                                    <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_u st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                                    <a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_u st-border-bottom-none " data-receipt_id='{{$receipt_id}}' data-customer_id='{{$key}}'><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </div>
                                        </div>   
                                       @endif 
                                    @endforeach
                                @endif                                
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="debited_to">Debited To</label>
                                        <select class="form-control" name="debited_to" id="debited_to">                                            
                                            @if(isset($tally_users) && $type == 1)
                                                <option value="">Select Tally User</option>
                                                @foreach($tally_users as $tally_user)
                                                    @if(isset($old_settle_amount))
                                                        <option value="{{$tally_user->id}}" {!! Input::old('debited_to') ? (Input::old('debited_to') == $tally_user->id ? 'selected' : '') : '' !!}>{{$tally_user->tally_name}}</option>
                                                    @else
                                                        <option value="{{$tally_user->id}}" @if(isset($debited_id) && $debited_id == $tally_user->id) selected @endif>{{$tally_user->tally_name}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(isset($debited_to) && $type != 1)
                                                <option value="">Select {{$val}} List</option>
                                                @foreach($debited_to as $debite)
                                                    @if(isset($old_settle_amount))
                                                        <option value="{{$debite->id}}" {!! Input::old('debited_to') ? (Input::old('debited_to') == $debite->id ? 'selected' : '') : '' !!}>{{$debite->debited_to}}</option>
                                                    @else
                                                        <option value="{{$debite->id}}" @if(isset($debited_id) && $debited_id == $debite->id) selected @endif>{{$debite->debited_to}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
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
                        <!--</form>-->
                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_customer_receipt_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                {!! Form::open(array('id'=>'delete_customer_receipt_form'))!!}
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="customer_id" value="" id="customer_id">
                <input type="hidden" name="_method" value="DELETE" id="method">
                <div class="delete">
                    <?php
                    $us = Auth::user();
                    $us['mobile_number']
                    ?>
                    <div><b>Mobile:</b>
                        {{$us['mobile_number']}}
                        <input type="hidden" name="mobile" value="{{$us['mobile_number']}}"/>
                        <input type="hidden" name="receipt_id" value=""/>
                    </div>
                    <div class="pwd">
                        <div class="pwdl"><b>Password:</b></div>
                        <div class="pwdr"><input class="form-control" id="model_pass" name="model_pass" placeholder="" required="required" type="password"></div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-default submit_customer_receipts_button" data-receipt_id="" id="yes" >Yes</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection