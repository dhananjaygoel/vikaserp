@extends('layouts.master')
@section('title','Edit HSN')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('hsn')}}">HSN</a></li>
                        <li class="active"><span>Edit HSN</span></li>
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
                            <h2><i class="fa fa-money"></i> &nbsp; Edit HSN </h2>
                        </header>
                        <div class="main-box-body clearfix">
                            <hr>
                            {!! Form::open(array('method'=>'PUT','url'=>url('hsn',$hsn->id), 'id'=>''))!!}
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
                            @if (Session::has('flash_message'))
                                <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                            @endif

                            <input type="hidden" name="id" value="{{$hsn->id}}">
                            <div class="form-group">
                                <label for="hsn_code">HSN Code<span class="mandatory">*</span></label>
                                <input id="hsn_code" class="form-control" placeholder="HSN Code" name="hsn_code" value="{{ $hsn->hsn_code }}" type="text">
                            </div>
                            <div class="form-group">
                                <label for="hsn_desc">HSN Description<span class="mandatory">*</span></label>
                                <textarea name="hsn_desc" id="hsn_desc" class="form-control">{{$hsn->hsn_desc}}</textarea>
                            </div>

                            <div class="form-group" >
                                <label for="">GST<span class="mandatory">*</span></label>
                                <select name="gst" class="form-control">
                                    @foreach(\App\Gst::orderBy('id','DESC')->get() as $gst)
                                        <option value="{{$gst->gst}}" {{($gst->gst==$hsn->gst)?'selected':''}}>{{$gst->gst}} %</option>
                                    @endforeach
                                </select>
                            </div>






                            <hr>
                            <div>
                                <input type="submit" class="btn btn-primary form_button_footer" value="Submit">
                                <a href="{{URL::to('hsn')}}" class="btn btn-default form_button_footer">Back</a>
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