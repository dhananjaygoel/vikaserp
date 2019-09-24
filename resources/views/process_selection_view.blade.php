@extends('layouts.master')
@section('title','DB Process')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('process')}}">Process</a></li>
                    <!--<li class="active"><span>Select Process</span></li>-->
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Select Process</h1>
                </div>
            </div>
        </div>
        @if(Session::has('success'))
         <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <strong> {{ Session::get('success') }} </strong>
            </div>
        @endif
                @if(Session::has('error'))
                <div class="clearfix"> &nbsp;</div>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <strong> {{ Session::get('error') }} </strong>
                </div>
                @endif
        <div class="row">
            <div class="col-lg-6">
                <div class="main-box panel panel-default">
                    <header class="main-box-header clearfix">
                        <h2> &nbsp; Upload HSN Sheet</h2>
                    </header>
                    <div class="main-box-body clearfix panel-body">
                        <form action="{{URL::action('DBController@store')}}" method="post" role="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" id="exampleInputFile" name="excel_file">
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
              <div class="col-lg-6">
                <div class="main-box panel panel-default">
                    <header class="main-box-header clearfix">
                        <h2> &nbsp; Upload Thikness Sheet</h2>
                    </header>
                    <div class="main-box-body clearfix panel-body">
                        <form action="{{URL::action('DBController@store')}}" method="post" role="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" id="exampleInputFile" name="excel_file">
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="main-box panel panel-default">
                    <header class="main-box-header clearfix">
                        <h2> &nbsp; Upload GST Sheet</h2>
                    </header>
                    <div class="main-box-body clearfix panel-body">
                        <form action="{{URL::action('DBController@store')}}" method="post" role="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" id="exampleInputFile" name="excel_file">
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
              <div class="col-lg-6">
                <div class="main-box panel panel-default">
                    <header class="main-box-header clearfix">
                        <h2> &nbsp; Upload State Sheet</h2>
                    </header>
                    <div class="main-box-body clearfix panel-body">
                        <form action="{{URL::action('DBController@store')}}" method="post" role="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputFile">File input</label>
                                <input type="file" id="exampleInputFile" name="excel_file">
                            </div>
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop