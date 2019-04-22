@extends('layouts.master')
@section('title','Edit GST')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <ol class="breadcrumb">
                        <li><a href="{{url('gst')}}">GST</a></li>
                        <li class="active"><span>Edit GST</span></li>
                    </ol>
                    <div class="clearfix">
                        <h1 class="pull-left">GST</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-box">
                        <header class="main-box-header clearfix">
                            <h2><i class="fa fa-money"></i> &nbsp; Edit GST </h2>
                        </header>
                        <div class="main-box-body clearfix">
                            <hr>
                            {!! Form::open(array('method'=>'PUT','url'=>url('gst',$gst->id), 'id'=>''))!!}
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

                            <input type="hidden" name="id" value="{{$gst->id}}">


                            <div class="form-group">
                                <label for="gst">GST<span class="mandatory">*</span></label>
                                <input id="gst" class="form-control" placeholder="GST" name="gst" value="{{$gst->gst}}" type="number">
                            </div>

                            <div class="form-group">
                                <label for="sgst">SGST<span class="mandatory">*</span></label>
                                <input id="sgst" class="form-control" placeholder="SGST" name="sgst" value="{{$gst->sgst}}" type="number">
                            </div>

                            <div class="form-group">
                                <label for="cgst">CGST<span class="mandatory">*</span></label>
                                <input id="cgst" class="form-control" placeholder="CGST" name="cgst" value="{{$gst->cgst}}" type="number">
                            </div>

                            <div class="form-group">
                                <label for="igst">IGST<span class="mandatory">*</span></label>
                                <input id="igst" class="form-control" placeholder="IGST" name="igst" value="{{$gst->igst}}" type="number">
                            </div>

                            <div class="form-group">
                                <label for="igst">Quickbook GST<span class="mandatory">*</span></label>
                                <select class="form-control" name="quick_gst_id" required>
                                    @foreach($quickgst as $gstq)
                                        <option value="{{$gstq->Id}}" {{($gstq->Id==$gst->quick_gst_id)?'selected':''}}>{{$gstq->Name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <hr>
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