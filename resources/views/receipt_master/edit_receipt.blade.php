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
                    @if(Session::has('error'))
                    <div class="clearfix"> &nbsp;</div>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong> {{ Session::get('error') }} </strong>
                    </div>
                    @endif
                    @if(Session::has('flash_message'))
                    <div class="clearfix"> &nbsp;</div>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <strong> {{ Session::get('flash_message') }} </strong>
                    </div>
                    @endif
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('id'=>'edit_receipt','action'=>array('ReceiptMasterController@update',$receipt_id)))!!}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="receipt_type" value="{{$type}}">
                        <input type="hidden" name="user_type" value="{{$user_type}}" id="user_type">
                        <input type="hidden" name="receipt_id" value="{{$receipt_id}}" id="receipt_id">
                        <input type="hidden" name="customer_ids_array" value='' id="customer_ids_array">
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
                            $old_narration = Input::old('narration');
                            $edit_key = [];
//                                    echo $amount;
                            ?>
                            <label for="credited_to">Credited To</label>
                            <div class="row edit_receipt" id="st-settle-container">
                                @if(isset($customer_arr))
                                @foreach($customer_arr as $key=>$customer)
                                <?php array_push($edit_key, $key); ?>
                                <div class="st-settle-block temp_tally_user">
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
                                        <div class="col-md-3 settle-input-elem">
                                            <input class="form-control" placeholder="Amount" name="settle_amount[{{$key}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$key])? $old_settle_amount[$key] : $customer ): $customer !!}" type="text" onkeypress=" return numbersOnly(this, event, false, false);">
                                        </div>
                                        <div class="col-md-4 narration-input-elem">
                                            <input class="form-control" placeholder="Narration" name="narration[{{$key}}]" value="{!! isset($old_narration)?(isset($old_narration[$key])? $old_narration[$key] : $customer_arr_narration[$key] ): $customer_arr_narration[$key] !!}" type="text">
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
                                @if(!isset($old_narration[$otu]))
                                <?php $old_narration = $customer_arr_narration; ?>
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
                                        <div class="col-md-3 settle-input-elem">
                                            <input class="form-control" placeholder="Amount" name="settle_amount[{{$otu}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$otu])? $old_settle_amount[$otu] : '' ): '' !!}" type="text" onkeypress=" return numbersOnly(this, event, false, false);">
                                        </div>
                                        <div class="col-md-4 narration-input-elem">
                                            <input class="form-control" placeholder="Narration" name="narration[{{$otu}}]" value="{!! isset($old_narration)?(isset($old_narration[$otu])? $old_narration[$otu] : '' ): '' !!}" type="text">
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
                                <?php
                                $old_tally_user = Input::old('tally_users_d');
                                $old_settle_amount = Input::old('settle_amount_d');
                                $old_narration = Input::old('narration_d');
                                $edit_key = [];
//                                    echo $amount;
                                ?>
                                @if(isset($customer_debit_arr))
                                @foreach($customer_debit_arr as $key=>$customer)
                                <?php array_push($edit_key, $key); ?>
                                <div class="st-settle-block_d temp_tally_user_d" >
                                    <div class="col-md-12" style="margin:10px 0;padding:0">

                                        @if(isset($debited_to) && $type != 1)
                                        <div class="col-md-3">                                       
                                            <select class="form-control" name="tally_users_d" id="debited_to">                                            
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
                                        @else 
                                        <div class="col-md-3"> 

                                            <select class="form-control st_select_tally_user_d" name="tally_users_d[]">                                            
                                                @if(isset($tally_users) && $type == 1)

                                                @foreach($tally_users as $tally_user)
                                                @if($tally_user->id == $key)

                                                <option value="{{$tally_user->id}}" >{{$tally_user->tally_name}}</option>
                                                @endif  
                                                @endforeach
                                                @endif                                               
                                            </select>
                                        </div> 
                                        <div class="col-md-3 settle-input-elem">

                                            <input class="form-control" placeholder="Amount" name="settle_amount_d[{{$key}}]" value="{!! isset($old_settle_amount)?(isset($old_settle_amount[$key])? $old_settle_amount[$key] : $customer ): $customer !!}" type="text" onkeypress=" return numbersOnly(this, event, false, false);">
                                        </div>
                                        <div class="col-md-4 narration-input-elem">

                                            <input class="form-control" placeholder="Narration" name="narration_d[{{$key}}]" value="{!! isset($old_narration)?(isset($old_narration[$key])? $old_narration[$key] : $customer_debit_arr_narration[$key] ): $customer_debit_arr_narration[$key] !!}" type="text">
                                        </div>
                                        <div class="col-md-1 action_btn">
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn add-tally_d st-border-bottom-none"><i class="fa fa-plus"></i></a>
                                            <a href="javascript:void(0)" style="border-bottom:none" class="btn del-tally_d st-border-bottom-none"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>                          
                        <hr>
                        <div >
                            <button type="submit" class="btn btn-primary form_button_footer" id="edit_receipt_btn" >Submit</button>
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
<div class="modal fade" id="delete_customer_receipt_modal_new" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                {!! Form::open(array('id'=>'confirm_customer_receipt_form'))!!}
                <input type="hidden" name="_token" value="{{csrf_token()}}" id="token">
                <input type="hidden" name="customer_id" value="" id="customer_id">
                <input type="hidden" name="receipt_id" value="" id="receipt_id">
                <input type="hidden" name="_method" value="delete" id="method">
                <input type="hidden" name="amount" value="" id="amount">
                <div class="delete">
                    <div class="clearfix"></div>
                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-default confirm_customer_receipt_form_btn" id="yes" >Yes</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection