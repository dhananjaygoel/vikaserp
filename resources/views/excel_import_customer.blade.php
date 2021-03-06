@extends('layouts.master')
@section('title',' Excel Import Customer')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="clearfix">
                    <h1 class="pull-left"> Excel Import Customer</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        @if (Session::has('wrong'))
                        <div class="alert alert-danger">
                            {{Session::get('wrong')}}                            
                        </div>
                        @endif

                        @if (Session::has('success'))
                        <div class="alert alert-success">
                            {{Session::get('success')}}                            
                        </div>
                        @endif  

                        <form action="{{URL::action('WelcomeController@upload_customer_excel')}}" method="post" role="form" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" id="exampleInputFile" name="excel_file">
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
                             <a href="{{url('customers')}}" class="btn btn-default form_button_footer">Back</a>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop