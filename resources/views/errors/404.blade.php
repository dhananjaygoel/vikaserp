@extends('layouts.master')
@section('title','Page Not Found')
@section('content')

<div class="row">
    <div class="col-lg-12 error_page_div">
        <span class="erro_page_heading">
            We can't find the page you are looking for. Back to
            <a class="error_page_link" href="{{url('dashboard')}}">Dashboard</a>
        </span>
    </div>
</div>
@stop