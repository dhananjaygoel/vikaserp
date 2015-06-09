@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-lg-12">

                    <div class="row">
                        <div class="col-lg-12">
                            <ol class="breadcrumb">
                                <li><a href="#">Home</a></li>
                                <li class="active"><span>Security</span></li>
                            </ol>

                            <div class="clearfix">
                                <h1 class="pull-left">Security</h1>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-box">
                                <header class="main-box-header clearfix">
                                    <h2><i class="fa fa-user"></i> &nbsp; Add IP Address </h2>
                                </header>            

                                <div class="main-box-body clearfix">
                                    <hr>

                                    <form method="POST" action="{{url('security')}}" accept-charset="UTF-8" >
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                                        <div class="form-group">
                                            <label for="ip">IP Address</label>
                                            <input id="ip_address" class="form-control" placeholder="IP Address" name="ip_address" value="" type="text">
                                        </div>                                                    




                                        <hr>
                                        <div >
                                            <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                            <a href="security.php" class="btn btn-default form_button_footer">Back</a>
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
        </div>
    </div>
</div>
@endsection


