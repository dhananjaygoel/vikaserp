@extends('layouts.master')
@if(isset($data))
@section('title','Edit User')
@else
@section('title','Add User')
@endif
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/users">Collection Users</a></li>
                    <li class="active"><span><?php echo(isset($data) ? 'Edit' : 'Add'); ?> Collection User</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Collection User</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp;<?php echo(isset($data) ? 'Edit' : 'Add'); ?> User</h2>
                    </header>             
                    <div class="main-box-body clearfix">
                        <hr>
                        @if(isset($data))
                        <form method="post" action="{{URL::action('CollectionUserController@update',$data[0]->id)}}" accept-charset="UTF-8" >   
                        <input type="hidden" name="_method" value="PUT">
                        @else
                        <form method="POST" action="{{URL::action('CollectionUserController@store')}}" accept-charset="UTF-8" >                        
                        @endif
                            @if (count($errors) > 0)
                            <div class="alert alert-warning">
                                @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                                @endforeach
                            </div>
                            @endif                            
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label for="first_name">First Name<span class="mandatory">*</span></label>
                                <input id="first_name" class="form-control" placeholder="First Name" name="first_name" value="<?php echo(isset($data) ? $data[0]->first_name : old('first_name') )?>" type="text">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name<span class="mandatory">*</span></label>
                                <input id="last_name" class="form-control" placeholder="Last Name" name="last_name" value="<?php echo(isset($data) ? $data[0]->last_name : old('last_name') )?>" type="text">
                            </div>                                              
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number<span class="mandatory">*</span></label>
                                <input id="mobile_number" class="form-control" placeholder="Mobile Number" name="mobile_number" value="<?php echo(isset($data) ? $data[0]->mobile_number : old('mobile_number') )?>" type="tel" onkeypress=" return numbersOnly(this,event,false,false);" maxlength="10">
                            </div>
                            <div class="form-group">
                                <label for="email">Email<span class="mandatory">*</span></label>
                                <input id="email" class="form-control" placeholder="Email Id" name="email" value="<?php echo(isset($data) ? $data[0]->email : old('email') )?>" type="email">
                            </div>   
                            <?php if(!isset($data)){ ?>                          
                            <div class="form-group">
                                <label for="password">Password<span class="mandatory">*</span></label>
                                <input id="password" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}" type="password">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password<span class="mandatory">*</span></label>
                                <input id="password_confirmation" class="form-control" placeholder="Confirm Password" name="password_confirmation" value="{{ old('password_confirmation') }}" type="password">
                            </div>
                            <?php }?>
                            <div class="form-group">
                                <label for="assign_location">Assign Location<span class="mandatory">*</span></label>
                                <select id="assign_location" class="form-control" placeholder="Assign Location" name="location[]" multiple="">
                                    <?php if(isset($data)){
                                        $ex_loc = array_column($data[0]->locations->toArray(), 'location_id');
                                        print_r($ex_loc);
                                    }                                    
                                    ?>

                                    @if(isset($locations))
                                    <?php $old_locations = Input::old('location');?>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" <?php echo(isset($ex_loc)? (in_array($loc->id,$ex_loc) ? 'selected' : '') :( Input::old('location') ? (in_array($loc->id, Input::old('location'))? 'selected' : '') : '')) ?>>{{ $loc->area_name }}</option>
                                    @endforeach
                                    @endif  
                                </select>                                
                            </div>    
                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" ><?php echo(isset($data) ? 'Update' : 'Submit'); ?></button>
                                <a href="{{url()}}/collectionusers" class="btn btn-default form_button_footer">Back</a>
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