@extends('layouts.master')
@section('title','Edit Territory')
@section('content')
<style>
    .multiselect-container.dropdown-menu {
    max-height: 350px;
    overflow-y: scroll;
}
.multiselect.dropdown-toggle.btn.btn-default{
    background: white none repeat scroll 0 0;
    border: 1px solid gray;
    color: #344644;
}
.caret{
    border-top-color: #344644 !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('territory')}}">Territory</a></li>
                    <li class="active"><span>Edit Territory</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Territory</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <header class="main-box-header clearfix">
                        <h2><i class="fa fa-user"></i> &nbsp; Edit Territory </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        {!! Form::open(array('method'=>'PUT','url'=>url('territory',$territory->id), 'id'=>''))!!}

                        <input type="hidden" name="id" value="{{$territory->id}}">
                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-warning">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif

                        <div class="form-group">
                            <label for="area_name">Territory Name<span class="mandatory">*</span></label>
                            <input id="territory_name" class="form-control" placeholder="Territory Name" name="territory_name" value="{{$territory->teritory_name}}" type="text">
                        </div>
                        <div class="form-group ">
                            <label>Location <span class="req">*</span></label><br>
                            <select multiple="multiple" class="form-control" name="location[]" id="multi-territory-location">
                                @foreach($locations as $key => $location)                                    
                                    <?php $flag=0; ?>
                                    @foreach($territory['territorylocation'] as $territorylocation)
                                        <?php 
                                           if($location->id == $territorylocation->location_id){
                                               $flag=1;
                                           }
                                        ?>    
                                    @endforeach
                                    
                                    @if($flag==1)
                                        <option value="{{$location->id}}" selected="selected">{{$location->area_name}}</option>
                                    @else
                                        <option value="{{$location->id}}">{{$location->area_name}}</option>
                                    @endif
                                @endforeach
                            </select>                                                                         
                        </div>
                        <hr>
                        <div>
                            <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                            <a href="{{URL::to('territory')}}" class="btn btn-default form_button_footer">Back</a>
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