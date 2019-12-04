@extends('layouts.master')
@section('title','View Territory')
@section('content')
<div class="row">
    <div class="col-lg-12"> 
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('territory')}}">Territory</a></li>
                    <li class="active"><span>View Territory</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">View Territory</h1>
                    <div class="pull-right top-page-ui">                             
                        <a href="{{URL::action('TerritoryController@edit', ['id' => $territory->id])}}" class="btn btn-primary pull-right">
                            Edit Territory
                        </a>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div class="table-responsive">
                            <table id="table-example" class="table customerview_table">
                                <tbody>
                                    <tr>
                                        <td><span>Territory Name:</span></td>
                                        <td>{{$territory->teritory_name}}</td>                                       
                                    </tr> 
                                     <tr>
                                        <td><span>Location</span></td>
                                        <td>
                                            <?php $count=1; ?>
                                            @foreach($locations as $key => $location) 
                                                <?php $flag=0;?>
                                                @foreach($territory['territorylocation'] as $territorylocation)
                                                    <?php 
                                                        if($territorylocation->location_id==$location->id){
                                                            $flag=1;                                                            
                                                        }                                                        
                                                    ?>                                                    
                                                @endforeach
                                                <?php 
                                                    if($flag==1 && $count>1){                                                        
                                                        echo ",";
                                                        echo  $location->area_name;                                                        
                                                        $count=$count+1;
                                                    }elseif($flag==1 && $count==1){
                                                        echo  $location->area_name;
                                                        $count=$count+1;
                                                    }
                                                ?>
                                            @endforeach
                                        </td>                                       
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop