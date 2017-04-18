@extends('layouts.master')
@section('title','Add Territory')
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
                    <li class="active"><span>Add Territory</span></li>
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
                        <h2><i class="fa fa-user"></i> Add Territory </h2>
                    </header>
                    <div class="main-box-body clearfix">
                        <hr>
                        <form id="" method="POST" action="{{URL::action('TerritoryController@store')}}">

                            @if (count($errors) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label for="area_name">Territory Name<span class="mandatory">*</span></label>
                                <input  class="form-control" placeholder="Territory Name" name="territory_name" type="text">
                            </div>                            
                            <div class="form-group ">
                                <label>Location <span class="req">*</span></label><br>
                                <select multiple="multiple" class="form-control" name="location[]" id="multi-territory-location">
                                    @foreach($locations as $key => $location)
                                        <option value="{{$location->id}}">{{$location->area_name}}</option>
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
<!--<script type="text/javascript">
    $(document).ready(function() {
        $('#multi-territory-location').multiselect();
    });
</script>-->
@stop