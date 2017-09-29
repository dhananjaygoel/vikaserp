@extends('layouts.master')
@section('title','Vehicle List')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url()}}/dashboard">Home</a></li>
                    <li class="active"><span>Vehicle List</span></li>
                </ol>
                <div class="clearfix">
                    <h1 class="pull-left">Vehicle List</h1>                    
                </div>
            </div>
        </div>
        <div class="tab">
             <a href="{{url('vehicle-list')}}"><button class="tablinks" onclick="openCity(event, 'London')">Delivery Order Vehicle List</button></a>
            <a href="{{url('pa-vehicle-list')}}"><button class="tablinks active">Purchase Advise Vehicle List</button></a>
        </div>
        <div id="Paris" class="tabcontent" style="display: block;">
          <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-12">                        
                                <form method="GET" id="searchCustomerForm">                       
                                    <div class="input-group col-md-2 pull-right">
                                        <input type="text" class="form-control" name="search" id="search" placeholder="Vehicle Number" value="{{Request::get('search')}}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>                       
                                </form>
                            </div>    
                        </div>
                        <div class="main-box clearfix">            
                            <div class="main-box-body main_contents clearfix">                        

                                @if(sizeof($pa_vehicle_list) != 0)
                                <div class="table-responsive">
                                    <table id="table-example" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-md-1">#</th>
                                                <th>Vehicle Number</th>                                        
                                            </tr>
                                        </thead>
                                        <tbody>                 
                                            <?php
                                            $i = ($pa_vehicle_list->currentPage() - 1 ) * $pa_vehicle_list->perPage() + 1;
                                            ?>
                                            @foreach($pa_vehicle_list as $vehicle)                                    
                                            <tr>
                                                <td class="col-md-1">{{ $i }}</td>
                                                <td>{{$vehicle->vehicle_number}}</td>                                        
                                            </tr>
                                        <?php $i++; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <span class="pull-right">
                                        <ul class="pagination pull-right">
                                            <?php echo $pa_vehicle_list->render(); ?>
                                        </ul>
                                    </span>
                                    <div class="clearfix"></div>  
                                    @if($pa_vehicle_list->lastPage() > 1)
                                    <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                        <form class="form-inline" method="GET" action="{{url('pa-vehicle-list')}}" id="filter_search">
                                            <div class="form-group">
                                                <label for="exampleInputName2"><b>Go To</b></label>
                                                &nbsp;
                                                <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                                &nbsp;
                                                <label for="exampleInputName2"><b>of {{ $pa_vehicle_list->lastPage()}} </b></label>
                                                <a onclick="this.form.submit()"></a>
                                            </div>
                                        </form>
                                    </span> 
                                    @endif
                                </div>
                                @else
                                <div class="alert alert-info no_data_msg_container">
                                    Currently no Vehicle Number available.
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>            
                </div>
        </div>
        
    </div>
</div>     
<style>
body {font-family: "Lato", sans-serif;}

/* Style the tab */
div.tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
div.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 16px;
    transition: 0.3s;
    font-size: 17px;
}

/* Change background color of buttons on hover */
div.tab button:hover {
    background-color: #ddd;
}

/* Create an active/current tablink class */
div.tab button.active {
    background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
}
</style>


<script>
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
@endsection
