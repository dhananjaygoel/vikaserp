@extends('layouts.master')
@section('title','Orders')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">

            <?php
//                echo "<pre>";
//                print_r($order_status_responase);
//                echo "</pre>";
//                exit;
            ?>
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        <div id="flash_message" class="alert no_data_msg_container"></div>
                        @if(Session::has('error'))
                        <div class="clearfix"> &nbsp;</div>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong> {{ Session::get('error') }} </strong>
                        </div>
                        @endif

                        @if (Session::has('success'))
                        <div class="alert alert-success alert-success1">{{Session::get('success')}}</div>
                        @endif
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if(sizeof($order_status_responase)==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no orders have been added.
                        </div>
                        @else                        
                        <div class="table-responsive tablepending">

                            <div>Order -{{$order_status_responase['order_details'][0]->id}} </div>
                            <table id="table-example" class="table table-hover">

                                <?php
                                $flag =0;
                                
                                foreach ($order_status_responase['order_details'] as $delivery_order) {
                                    if ($delivery_order->order_status == 'pending') {
                                        $status = 'pending';
                                    } elseif ($delivery_order->order_status == 'completed') {
                                        foreach ($order_status_responase['delivery_order_details'] as $delivery_order_details) {
                                            if ($delivery_order_details->order_status == 'pending') {
                                                $status = 'pending';
                                            } elseif ($delivery_order->order_status == 'completed') {
                                                foreach($order_status_responase['delivery_challan_details'] as $delivery_challan_details) {
                                                   
                                                    if($delivery_challan_details->challan_status == 'pending'){
                                                        $flag = $flag +1;
                                                    }
                                                }
                                                
                                                if($flag == 0)
                                                {
                                                   $status = 'completed'; 
                                                }
                                                else
                                                {
                                                    $status = 'pending'; 
                                                }
                                                
                                            }
                                        }
                                    }
                                }
                                ?>


                   
                                <?php
                                $k = 1;
                                $qty = 0;
                                $qty_do = 0;
                                $qty_co = 0;
                                ?>   
                                <thead>
                                    <tr>
                                        <!--<th class='col-md-1'>#</th>-->
                                        <th class='col-md-1'>SERIAL NUMBER</th>
                                        <th class='col-md-1'>QTY</th>
                                        <th class='col-md-1'>STATUS</th>
                                        <th class='col-md-1'>Date</th>
                                        <th class="text-center col-md-1">View Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order_status_responase['order_details'] as $order_details) 
                                    @foreach($order_details->all_order_products as $all_order_products) 
                                    <?php $qty = $qty + $all_order_products->quantity ?>

                                    @endforeach

                                    <tr >
                                        <!--<td>{{$k++}}</td>-->
                                        <td>N/A</td>
                                        <td>{{$qty}}</td>
                                        <td>
                                            @if($order_details->order_status == 'cancelled')
                                            {{'Cancelled'}}
                                            @else
                                            @if($status == 'completed') 
                                            {{'Completed'}}
                                            </span>
                                            @endif
                                            @if($status == 'pending') 
                                            {{'In Process'}}
                                            </span>
                                            @endif
                                            @endif  
                                        </td> 
                                        <td>{{$order_details->created_at->format('d/m/Y')}}</td>
                                        <td class="text-center col-md-1">
                                         <a href="{{url('orders/'.$order_details->id)}}" class="table-link" title="view">
                                            <span class="fa-stack">
                                                <i class="fa fa-square fa-stack-2x"></i>
                                                <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                            </span>
                                             
                                        </a>
                                            </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <br>
                            <br>
                            <br>


                            @if(!empty($order_status_responase['delivery_order_details'][0]))                
                            <div>Delivery  Order </div>

                            <table id="table-example" class="table table-hover">


                                <?php
                                $k = 1;
                                $qty = 0;
                                $qty_do = 0;
                                $qty_co = 0;
                                ?>   
                                <thead>
                                    <tr>
                                        <!--<th class='col-md-1'>#</th>-->
                                        <th class='col-md-1'>SERIAL NUMBER</th>
                                        <th class='col-md-1'>QTY</th>
                                        <th class='col-md-1'>STATUS</th>
                                        <th class='col-md-1'>Date</th>
                                        <th class="text-center col-md-1">View Detail</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($order_status_responase['delivery_order_details'] as $delivery_order_details) 
                                    <?php $qty_do = 0; ?>
                                    @foreach($delivery_order_details->delivery_product as $all_order_products) 
                                    <?php $qty_do = $qty_do + $all_order_products->quantity ?>
                                    @endforeach

                                    <tr >
                                       <!--<td>{{$k++}}</td>--> 
                                        <td>
                                            @if(!empty($delivery_order_details->serial_no))
                                            {{$delivery_order_details->serial_no}}
                                            @else
                                            {{'N/A'}}
                                            @endif

                                        </td>
                                        <td>{{$qty_do}}</td>
                                        <td>
                                            @if($delivery_order_details->order_status == 'completed')                                 {{'Completed'}}
                                            </span>
                                            @endif
                                            @if($delivery_order_details->order_status == 'pending') 
                                            {{'In Process'}}
                                            </span>
                                            @endif

                                        </td>
                                        <td>{{$delivery_order_details->created_at->format('d/m/Y')}}</td>
                                        <td class="text-center">
                                            <a href="{{URL::action('DeliveryOrderController@show',['id'=> $delivery_order_details->id])}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
                                         </td>
                                    </tr>
                                    @endforeach
                                </tbody>


                            </table>
                            @endif 
                            <br>
                            <br>
                            <br>
                            @if(isset($order_status_responase['delivery_challan_details'][0]))              
                            <div>Delivery  Challan </div>
                            <table id="table-example" class="table table-hover"> 

                                <?php
                                $k = 1;
                                $qty = 0;
                                $qty_do = 0;
                                $qty_co = 0;
                                ?>   
                                <thead>
                                    <tr>
                                        <!--<th class='col-md-1'>#</th>-->
                                        <th class='col-md-1'>SERIAL NUMBER</th>
                                        <th class='col-md-1'>DO SERIAL NO</th>
                                        <th class='col-md-1'>QTY</th>
                                        <th class='col-md-1'>STATUS</th>
                                        <th class='col-md-1'>Date</th>
                                        <th class="text-center col-md-1">View Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order_status_responase['delivery_challan_details'] as $delivery_challan_details) 


                                    <?php $qty_co = 0; ?>
                                    @foreach($delivery_challan_details->delivery_challan_products as $all_order_products) 
                                    <?php $qty_co = $qty_co + $all_order_products->present_shipping ?>
                                    @endforeach


                                    <tr >
                                       <!--<td>{{$k++}}</td>-->
                                        <td>
                                            @if(!empty($delivery_challan_details->serial_number))
                                            {{$delivery_challan_details->serial_number}}</td>
                                        @else
                                        {{'N/A'}}
                                        @endif


                                        <td>
                                            @foreach($order_status_responase['delivery_order_details'] as $delivery_order_details)  
                                            <?php
                                            if ($delivery_order_details->id == $delivery_challan_details->delivery_order_id) {
                                                print_r($delivery_order_details->serial_no);
                                            }
                                            ?>
                                            @endforeach
                                        </td>

                                        <td>{{$qty_co}}</td>
                                        <td>
                                            @if($delivery_challan_details->challan_status == 'completed') 
                                            {{'Completed'}}
                                            </span>
                                            @endif
                                            @if($delivery_challan_details->challan_status == 'pending') 
                                            {{'In Process'}}

                                            </span>
                                            @endif
                                        </td>
                                        <td>{{$delivery_challan_details->created_at->format('d/m/Y')}}</td>                  
                                        
                                        <td class="text-center col-md-1">
                                           <a href="{{url('delivery_challan/'.$delivery_challan_details->id)}}" class="table-link" title="view">
                                                <span class="fa-stack">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-search fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a> 
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>


                            </table>
                            @endif 
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop