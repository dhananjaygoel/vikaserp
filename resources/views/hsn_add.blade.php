@extends('layouts.master')
@section('title','Add HSN Code')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('gst')}}">HSN</a></li>
                        <li class="active"><span>Add HSN</span></li>
                    </ol>
                    <div class="clearfix">
                        <h1 class="pull-left">HSN</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <header class="main-box-header clearfix">
                            <h2><i class="fa fa-money"></i> &nbsp; Add HSN Code </h2>
                        </header>
                        <div class="main-box-body clearfix">
                            <hr>
                            <form id="" method="POST" action="{{URL::action('HsnController@store')}}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                @if (count($errors) > 0)
                                    <div role="alert" class="alert alert-warning">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="hsn_code">HSN Code<span class="mandatory">*</span></label>
                                    <input id="hsn_code" class="form-control" placeholder="HSN Code" name="hsn_code" value="{{ old('hsn_code') }}" type="text">
                                </div>

                                <div class="form-group">
                                    <label for="hsn_desc">HSN Description<span class="mandatory">*</span></label>
                                    <textarea name="hsn_desc" id="hsn_desc" class="form-control">{{old('hsn_desc')}}</textarea>
                                </div>

                                <div class="form-group" >
                                    <label for="">GST<span class="mandatory">*</span></label>
                                    <select name="gst" class="form-control">
                                        @foreach(\App\Gst::orderBy('id','DESC')->get() as $gst)
                                            <option value="{{$gst->gst}}">{{$gst->gst}} %</option>
                                        @endforeach
                                    </select>
                                </div>






                                <hr/>

                                <div>
                                    <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                                    <a href="{{URL::to('gst')}}" class="btn btn-default form_button_footer">Back</a>
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