@extends('layouts.master')
@section('title','Territory')
@section('content')
<div class="row">
    <div class="col-lg-12">        
        <div class="row">
            <?php //  dd($territories)?>
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Territory</span></li>
                </ol>

                <div class="clearfix">
                    <h1 class="pull-left">Territory</h1>
                    @if( Auth::user()->role_id == 0  )
                    <div class="pull-right top-page-ui">
                        <a href="{{URL::action('TerritoryController@create')}}" title="Add New Territory" class="btn btn-primary pull-right">
                            <i class="fa fa-plus-circle fa-lg"></i> Add New Territory
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($territories) ==0)                        
                        <div class="alert alert-info no_data_msg_container">
                            Currently no territories have been added.
                        </div>
                        @else
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if (Session::has('flash_success_message'))
                        <div id="flash_error" class="alert alert-success no_data_msg_container">{{ Session::get('flash_success_message') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table id="table-example" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="col-md-1">#</th>
                                        <th>Territory Name</th>                                        
                                        @if( Auth::user()->role_id == 0 )
                                        <th class="text-center">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>          
                                    <?php $i = 0 ?>
                                    @foreach($territories as $territory)
                                    <tr>                            
                                        <td class="col-md-1">{{$i++}}</td>
                                        <td>{{$territory->teritory_name}}</td>
                                        
                                        @if( Auth::user()->role_id == 0 )
                                        <td class="text-center">
                                            <a href="{{ Url::action('TerritoryController@show', ['id' => $territory->id]) }}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a title="Edit" href="{{ Url::action('TerritoryController@edit', ['id' => $territory->id]) }}" class="table-link">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                            <a title="Delete"  href="" class="table-link danger delete-territory" data-id="{{$territory->id}}">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                                                        
                                    @endforeach
                                </tbody>
                            </table>
                            <span class="pull-right">
                                <?php echo $territories->render(); ?>
                            </span>
                            <div class="clearfix"></div>                          
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        

    </div>
</div>

<div class="modal fade" id="delete_location_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                {!! Form::open(array('method'=>'DELETE', 'id'=>'delete_teritory_form'))!!}
                <div class="delete">                                                   
                    <div class="delp">Are you sure you want to <b>delete </b>?</div>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-default" id="yes">Yes</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.delete-territory', function (event) {
       event.preventDefault(); 
       var territory_id = $(this).data('id');
       var url=$('#baseurl').attr('name')+"/territory/"+territory_id;
       $('#delete_teritory_form').attr('action',url);
       $('#delete_location_modal').modal('show');       
    });
</script>
@stop