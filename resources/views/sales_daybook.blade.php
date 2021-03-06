@extends('layouts.master')
@section('title','Sales Daybook')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('dashboard')}}">Home</a></li>
                    <li class="active"><span>Sales Daybook</span></li>
                </ol>
                <div class="filter-block">
                    <h1 class="pull-left">Sales Daybook</h1>
                    <div class="pull-right top-page-ui col-md-8">
                        @if(sizeof($allorders) > 0)
                        <div class="pull-right col-md-1">
                            <a class="btn btn-primary form_button_footer print_sales_order_daybook" >Print</a>
                        </div>
                        @endif
                        <div class="search_form_wrapper sales_book_search_form_wrapper pull-right" style="display: flex;">
                            <form class="search_form" method="GET" action="{{URL::action('SalesDaybookController@index')}}" style="display: flex;">
                                <input type="text" placeholder="From" name="export_from_date" class="form-control export_from_date" id="export_from_date" <?php
                                if (Input::get('export_from_date') != "") {
                                    echo "value='" . Input::get('export_from_date') . "'";
                                }
                                ?>>
                                <input type="text" placeholder="To" name="export_to_date" class="form-control export_to_date" id="export_to_date" <?php
                                if (Input::get('export_to_date') != "") {
                                    echo "value='" . Input::get('export_to_date') . "'";
                                }
                                ?>>
                                <input type="submit" disabled="" name="search_data" value="Search" class="search_button btn btn-primary pull-right export_btn">
                            </form>
                            <form class="pull-left" method="POST" action="{{URL::action('SalesDaybookController@export_sales_daybook')}}">
                                <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="export_from_date" id="export_from_date" <?php
                                if (Input::get('export_to_date') != "") {
                                    echo "value='" . Input::get('export_from_date') . "'";
                                }
                                ?>>
                                <input type="hidden" name="export_to_date" id="export_to_date" <?php
                                if (Input::get('export_to_date') != "") {
                                    echo "value='" . Input::get('export_to_date') . "'";
                                }
                                ?>>
                                <input type="submit"  name="export_data" value="Export" class="btn btn-primary pull-right " style=" float: left !important; margin-left: 2% !important;">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="main-box-body main_contents clearfix">
                        @if(sizeof($allorders)==0)
                        <div class="alert alert-info no_data_msg_container">
                            Currently no orders have been added to Delivery Challan.
                        </div>
                        @else
                        @if (Session::has('flash_message'))
                        <div id="flash_error" class="alert alert-info no_data_msg_container">{{ Session::get('flash_message') }}</div>
                        @endif
                        @if (Session::has('error'))
                        <div id="flash_error" class="alert alert-danger no_data_msg_container">{{ Session::get('error') }}</div>
                        @endif

                        @if (count($errors->all()) > 0)
                        <div role="alert" class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                        <div class="table-responsive">
                            <form action="{{url('delete_multiple_challan_sales_daybook')}}" method="POST">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <table id="add_product_table_delivery_challan" class="table table-hover">
                                    <thead>
                                        <tr>
                                            @if( Auth::user()->role_id == 0 || Auth::user()->role_id == 4)
                                            <th><input type="checkbox" class="table-link" id ="select_all_button" onclick="select_all_checkbox();" all_checked="allunchecked" ></th>
                                            <th>#</th>
                                            @endif
                                            @if( Auth::user()->role_id == 1 )
                                            <th>#</th>
                                            @endif
                                            <th>Date</th>
                                            <th>Serial</th>
                                            <th>Tally Name</th>
                                            <th>Truck Number</th>
                                            <th>Fullfilled By</th>
                                            <th>Order By </th>
                                            <!-- <th>Loaded By </th>
                                            <th>Labors </th> -->
                                            <th>Actual Quantity</th>
                                            <th>Amount</th>
                                            <th>Bill Number</th>
                                            <th>Remarks </th>
                                            <th>Edited</th>
                                            @if( Auth::user()->role_id == 0)
                                            <th>Action </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="challan_data" >
                                        <?php
                                        $k = ($allorders->currentPage() - 1 ) * $allorders->perPage() + 1;
                                        ?>
                                        @foreach($allorders as $challan)
                                        <?php $total_amount = 0; ?>
                                    <?php
                                        $cust_id = $challan->customer_id;
                                        $order_id = $challan->order_id;
                                        $loc_id = \App\DeliveryOrder::where('customer_id',$cust_id)->where('order_id',$order_id)->first();
                                        $state = \App\DeliveryLocation::where('id',isset($loc_id->delivery_location_id)?$loc_id->delivery_location_id:0)->first();
                                        $local = \App\States::where('id',isset($state->state_id)?$state->state_id:0)->first();
                                        $local_state = isset($local->local_state)?$local->local_state:0;
                                        $i = 1;
                                        $total_price = 0;
                                        $total_vat = 0;
                                        if(isset($challan['all_order_products'][0]->vat_percentage) && $challan['all_order_products'][0]->vat_percentage > 0){
                                            $loading_vat = 18;
                                        }else{
                                            $loading_vat = 0;
                                        }
                                        $loading_vat_amount = ((float)$challan->loading_charge * (float)$loading_vat) / 100;
                                        $freight_vat_amount = ((float)$challan->freight * (float)$loading_vat) / 100;
                                        $discount_vat_amount = ((float)$challan->discount * (float)$loading_vat) / 100;
                                        $final_vat_amount = 0; 
                                        $final_total_amt = 0;
                                    ?>
                                        <?php $total_amount=0;$total_price = 0;$price = 0;$lb_arr = []; $lbr_arr=[];?>
                                        <tr class="add_product_row">
                                            @if( Auth::user()->role_id == 0 || Auth::user()->role_id == 4)
                                            <td><input type="checkbox" id ="checkbox_{{$k}}" name="challan_id[{{$k}}][checkbox]" value="{{$challan->id}}" > </td>
                                            <td>{{$k++}}</td>
                                            @endif
                                            @if( Auth::user()->role_id == 1)
                                            <th>{{$k}}</th>
                                            @endif
                                            <td>{{ date('j F, Y',strtotime($challan['updated_at']))}}</td>
                                            <td>
                                                @if($challan->serial_number == '')
                                                --
                                                @else
                                                {{$challan->serial_number}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($challan['customer']->tally_name) && $challan['customer']->tally_name!="")
                                                {{ $challan['customer']->tally_name}}
                                            @elseif(isset($challan['customer']->owner_name))
                                                {{ $challan['customer']->owner_name}}
                                            @else
                                                Anonymous User
                                            @endif
                                            </td>
                                            <td>{{isset($challan["delivery_order"])?$challan["delivery_order"]->vehicle_number: ''}}</td>
                                            <td>
                                                @if(isset($challan["delivery_order"]->supplier_id) && $challan["delivery_order"]->supplier_id != 0)
                                                @foreach($supplier as $sup)
                                                @if($sup->id == $challan["delivery_order"]->supplier_id)
                                                {{$sup->tally_name}}
                                                @endif
                                                @endforeach
                                                @else
                                                {{isset($challan["delivery_order"]->order_source) ? $challan["delivery_order"]->order_source:''}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($challan->order_id) && $challan->order_id > 0)
                                                {{(isset($challan->order_details->createdby['first_name'])&&isset($challan->order_details->createdby['last_name']))?$challan->order_details->createdby['first_name']." ".$challan->order_details->createdby['last_name']:''}}
                                                @else
                                                {{$challan->delivery_order->user['first_name']." ".$challan->delivery_order->user['last_name']}}
                                                @endif
                                            </td>
                                            
                                            <!-- <td>
                                                @if(isset($challan['challan_loaded_by']))
                                                @foreach($challan['challan_loaded_by'] as $load)
                                                    <?php
                                                        if(!in_array($load->loaded_by_id,$lb_arr) && ($load->loaded_by_id!=0) && ($load->total_qty>0)){
                                                            array_push($lb_arr, $load->loaded_by_id);
                                                        } 
                                                    ?>                                                    
                                                @endforeach
                                                @endif
                                                {{count((array)$lb_arr)}}
                                            </td>
                                            <td>
                                                @if(isset($challan['challan_labours']))
                                                @foreach($challan['challan_labours'] as $labour)                                                    
                                                    <?php 
                                                        if(!in_array($labour->labours_id,$lbr_arr) && ($labour->labours_id!=0) && ($labour->total_qty>0)){
                                                            array_push($lbr_arr, $labour->labours_id);
                                                        } 
                                                    ?>                                                    
                                                @endforeach
                                                @endif
                                                {{count((array)$lbr_arr)}}                                                
                                            </td> -->
                                            <td>{{ (round($challan->actual_quantity, 2)>0)? round($challan->actual_quantity, 2)  :0 }}
                                                <?php
                                                //$total_qunatity = 0;
//                                                foreach ($challan["delivery_challan_products"] as $products) {
//                                                    if ($products['unit']->id == 1) {
//                                                        $total_qunatity += $products->quantity;
//                                                    }
//                                                    if ($products['unit']->id == 2) {
//                                                        $total_qunatity +=($products->quantity * $products['order_product_details']->weight);
//                                                    }
//                                                    if ($products['unit']->id == 3) {
//                                                        $total_qunatity +=(($products->quantity / $products['order_product_details']->standard_length ) * $products['order_product_details']->weight);
//                                                    }
//                                                }
//                                                if(isset($challan['delivery_challan_products']) && isset($products['actual_quantity'])){
//                                                   
//                                                  echo round($challan['delivery_challan_products']->sum('actual_quantity'), 2);  
//                                                }else{
//                                                    echo round(0,2);
//                                                }
                                                //echo round($challan['delivery_challan_products']->sum('actual_quantity'), 2);
                                                ?>
                                            </td>

                                            @foreach($challan['delivery_challan_products'] as $product)
                                            <?php //$amnt = ($prod->quantity * $prod->price); $price = $price + $amnt; ?>
                                            <?php
                                                $productsub = \App\ProductSubCategory::where('id',$product['product_category_id'])->first();
                                                $product_cat = \App\ProductCategory::where('id',$productsub->product_category_id)->first();
                                                $sgst = 0;
                                                $cgst = 0;
                                                $igst = 0;
                                                $rate = (float)((isset($product->price) && $product->price != '0.00') ? $product->price : $product['product_sub_category']->product_category['price']);
                                                $is_gst = false;
                                                $amount = (float)$product->actual_quantity * (float)$rate;
                                                $total_amount = round($amount + $total_amount, 2);
                                                if(isset($product->vat_percentage) && $product->vat_percentage > 0){
                                                    if($product_cat->hsn_code){
                                                        
                                                        $is_gst = true;
                                                        $hsn_det = \App\Hsn::where('hsn_code',$product_cat->hsn_code)->first();
                                                        if(isset($hsn_det->gst))
                                                        $gst_det = \App\Gst::where('gst',$hsn_det->gst)->first();
                                                        if($local_state == 1){
                                                            $sgst = isset($gst_det->sgst)?$gst_det->sgst:0;
                                                            $cgst = isset($gst_det->cgst)?$gst_det->cgst:0;
                                                        }
                                                        else{
                                                            $igst = isset($gst_det->igst)?$gst_det->igst:0;
                                                        }
                                                    }
                                                }
                                                else{
                                                    $igst = 0;
                                                }
                                                if(isset($product->vat_percentage) && $product->vat_percentage > 0){
                                                    if($local_state == 1){
                                                        $total_sgst_amount = ((float)$amount * (float)$sgst) / 100;
                                                        $total_cgst_amount = ((float)$amount * (float)$cgst) / 100;
                                                        $total_vat_amount1 = (round($total_sgst_amount,2) + round($total_cgst_amount,2));
                                                    } else {
                                                        $total_igst_amount = ((float)$amount * (float)$igst) / 100;
                                                        $total_vat_amount1 = round($total_igst_amount,2);
                                                    }
                                                } else{
                                                    $total_gst_amount = ((float)$amount * (float)$igst) / 100;
                                                    $total_vat_amount1 = round($total_gst_amount,2);
                                                }
                                                $total_vat_amount = $total_vat_amount1;
                                                $total_price += ($total_vat_amount);
                                                
                                            ?>
                                            @endforeach

                                            <!-- <td >{{round(isset($challan->grand_price)?$challan->grand_price:0, 2)}}</td> -->
                                            <td>
                                            <?php 
                                                $total = (float)$total_amount + (float)$challan->freight + (float)$challan->loading_charge + (float)$challan->discount;
                                                $total_vat = $total_price + $loading_vat_amount + $freight_vat_amount + $discount_vat_amount;
                                                $tot = $total + $total_vat;
                                                $tcs_amount = $tot * $challan->tcs_percentage / 100;
                                                $final_tot = $tot + $tcs_amount;
                                            ?>
                                            {{round($final_tot,0)}}
                                            </td>
                                            <td >{{$challan->bill_number}}</td>
                                            <td>
                                                @if((strlen(trim($challan->remarks))) > 50)
                                                {{ substr(trim($challan->remarks),0,50)}} ..
                                                @else
                                                {{trim($challan->remarks)}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($challan->is_editable > 0)
                                                    <span class="text-success">Yes</span>
                                                @else
                                                    <span class="text-info">No</span>
                                                @endif
                                            </td>
                                            @if( Auth::user()->role_id == 0)
                                            <td>
                                                <a href="#" class="table-link danger delete-sales-day-book" data-toggle="modal" data-target="#delete-sales-day-book" title="delete" data-url='{{url("delete_sales_daybook",$challan->id)}}'>
                                                    <span class="fa-stack">
                                                        <i class="fa fa-square fa-stack-2x"></i>
                                                        <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
                                        @if( Auth::user()->role_id == 0  )

                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                @if( Auth::user()->role_id == 0  )
                                <div class="modal fade" id="delete_challan_selected" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                <h4 class="modal-title" id="myModalLabel"></h4>
                                            </div>
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="modal-body">
                                                <div class="delete">
                                                    <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                                    <div class="pwd">
                                                        <div class="pwdl"><b>Password:</b></div>
                                                        <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>

                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                                <button type="submit" class="btn btn-default" id="delete_selected_chllan_button">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right deletebutton">
                                    <a href="#" class="table-link danger" data-toggle="modal" data-target="#delete_challan_selected" ><button type="button" class="btn btn-primary form_button_footer" >Delete All</button></a>
                                </div>
                                @endif
                            </form>
                            <div class="clearfix"></div>
                            <span class="pull-right">
                                <?php
                                echo $allorders->appends(Input::except('page'))->render();
                                ?>
                            </span>
                            <div class="clearfix"></div>
                            @if($allorders->lastPage() > 1)
                            <span style="margin-top:0px; margin-right: 0; padding-right: 0;" class="small pull-right">
                                <form class="form-inline" method="GET" action="{{url('sales_daybook')}}" id="filter_search">
                                    <div class="form-group">
                                        <label for="exampleInputName2"><b>Go To</b></label>
                                        &nbsp;
                                        <input style="width: 50px;" type="text" class="form-control" placeholder="" value="{{Input::get('page')}}" name="page" type="text">
                                        &nbsp;
                                        <label for="exampleInputName2"><b>of {{ $allorders->lastPage()}} </b></label>
                                        <a onclick="this.form.submit()"></a>
                                    </div>
                                </form>
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="modal fade" id="delete-sales-day-book" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                    <h4 class="modal-title" id="myModalLabel"></h4>
                                </div>
                                {!! Form::open(array("method"=>"POST", "id"=>"delete-sales-day-book-form"))!!}
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="modal-body">
                                    <div class="delete">
                                        <div><b>UserID:</b> {{Auth::user()->mobile_number}}</div>
                                        <div class="pwd">
                                            <div class="pwdl"><b>Password:</b></div>
                                            <div class="pwdr"><input class="form-control" placeholder="" name="password" type="password"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="delp">Are you sure you want to <b>cancel </b> order?</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                                    <button type="button" class="btn btn-default" id="yes" onclick="this.form.submit();">Yes</button>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop