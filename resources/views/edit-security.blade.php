@extends('layouts.master')
@section('title','Edit Security')
@section('content')

<div class="row">
    <div class="col-lg-12">

        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('security')}}">Security</a></li>
                    <li class="active"><span>Edit Security</span></li>
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
                        <h2><i class="fa fa-user"></i> &nbsp; Edit IP Address </h2>
                    </header>            

                    <div class="main-box-body clearfix">

                        <hr>

                        <form method="POST" action="{{url('security/'.$security->id)}}" accept-charset="UTF-8" >
                            @if (count($errors->all()) > 0)
                            <div role="alert" class="alert alert-warning">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <input name="_method" type="hidden" value="PUT">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">


                            <div class="form-group">
                                <label for="ip">IP Address</label>
                                <input id="ip_address" class="form-control" placeholder="IP Address" name="ip_address" value="{{$security->ip_address}}" type="tel">
                            </div>                                                    




                            <hr>
                            <div >
                                <button type="submit" class="btn btn-primary form_button_footer" >Submit</button>
                                <a href="{{url('security')}}" class="btn btn-default form_button_footer">Back</a>
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


