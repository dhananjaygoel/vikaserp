@extends('layouts.master')
@section('title','Load Truck')
@section('content')
<style>
    .multiselect-container.dropdown-menu {
        max-height: 350px;
        overflow-y: scroll;    
    }
    .multiselect.dropdown-toggle.btn.btn-default{
        background: white none repeat scroll 0 0;
        border: 1px solid gray;
        color: #344644;
    }
    .caret{
        border-top-color: #344644 !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url('delivery_order')}}">Delivery Order</a></li>
                    <li class="active"><span>Load Truck</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">
                        @if (Session::has('validation_message'))
                        <div id="flash_error" class="alert alert-warning no_data_msg_container">{{ Session::get('validation_message') }}</div>
                        @endif
                        <div id="flash_error_present_shipping"></div>
                        @if (count($errors) > 0)
                        <div role="alert" class="alert alert-warning">
                            @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                            @endforeach
                        </div>
                        @endif
                        <div class="alert alert-success alert-success-empty-truck" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            Empty truck value successfully updated.
                        </div>
                        <div class="alert alert-success alert-success-truck-weight" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            Truck weight value successfully updated.
                        </div>
                        <div class="form-group">Date : {{date('d F, Y')}}</div>
                        <hr>
                        {!!Form::open(array('data-button'=>'btn_delorderto_deltruck','method'=>'POST','url'=>url('create_load_truck/'.$delivery_data['id']),'class'=>'load_truck_data','id'=>'onenter_prevent'))!!}
                        <input type="hidden" name="order_id" value="{{$delivery_data->order_id}}">
                        <input type="hidden" name="delivery_id" id ="delivery_id" value="{{$delivery_data->id}}">
                        <input type="hidden" name="form_key" value="frm{{rand(100,1000000)}}">
                        <input type="hidden" id="customer_id" name="customer_id" value="{{isset($delivery_data['customer']->id)?$delivery_data['customer']->id:''}}">
                        <div class="form-group">
                            <span>Serial Number: </span>{{($delivery_data->serial_no != "") ? $delivery_data->serial_no : '--'}}
                        </div>
                        <hr>
                        <input type="hidden" name="supplier_id" value="{{ $delivery_data->supplier_id }}"/>
                        <input type="hidden" id="order_source"  value="{{ $delivery_data->order_source }}"/>
                        <div class="form-group">
                            <td><span>Party:</span>
                                @if(isset($delivery_data['customer']->owner_name) && $delivery_data['customer']->owner_name != "" && $delivery_data['customer']->tally_name != "")
                                {{ $delivery_data['customer']->owner_name}}-{{$delivery_data['customer']->tally_name}}
                                @else
                                {{ isset($delivery_data['customer']->owner_name)?$delivery_data['customer']->owner_name:''}}
                                @endif
                                <input type="hidden" name="existing_customer_id" value="{{isset($delivery_data['customer']->id)?$delivery_data['customer']->id:''}}" id="existing_customer_id">
                            </td>
                            <input type="hidden" name="location_difference" value="{{$delivery_data->location_difference}}" id="location_difference">
                        </div>
                        @if($delivery_data->discount > 0)
                            <div class="form-group">
                                <span>Discount/Premium : </span>
                                {{$delivery_data->discount_type}}                                 
                            </div>
                            <div class="form-group">                                    
                                <span>Fixed/Percentage : </span>
                                {{$delivery_data->discount_unit}}                                
                            </div>
                            <div class="form-group">                                    
                                <span>Amount : </span>
                                {{$delivery_data->discount}}                                
                            </div>
                        @else
                            <div class="form-group">                                
                                <span>Discount/Premium : </span>                                    
                            </div>
                            <div class="form-group">                                     
                                <span>Fixed/Percentage :</span>                                    
                            </div>
                            <div class="form-group">                                    
                                 <span>Amount : </span>                                    
                            </div>
                        @endif
                        <hr>
                        <div class="form-group row">
                            <span class="col-md-2">Empty Truck Weight(Kg):</span> 
                            @if(isset($delivery_data->empty_truck_weight))
                            @if($delivery_data->empty_truck_weight > 0)
                            
                            <input type="text" name="empty_truck_weight" value="{{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control col-md-2" name="empty_truck_weight" onkeypress=" return numbersOnly(this, event, true, false);" style="width: 10.33%;" maxlength="10" >
                            <button type="button" value="empty_truck_save" id="btn_empty_truck" class="btn btn-sm btn-primary" style="position: relative;margin-left: 2em;">Save</button>
                           
                            @else
                            <input type="text" name="empty_truck_weight" value="{{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control col-md-2" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, true, false);" >
                            <button type="button" value="empty_truck_save" id="btn_empty_truck" class="btn btn-sm btn-primary" style="position: relative;margin-left: 2em;">Save</button>
                            @endif
                            @else
                            <input type="text" name="empty_truck_weight" value="0" id="empty_truck_weight" class="form-control col-md-2" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, true, false);" >
                            <button type="button" value="empty_truck_save" id="btn_empty_truck" class="btn btn-sm btn-primary" style="position: relative;margin-left: 2em;">Save</button>
                            @endif  
                        </div>
                        <hr>
                        <div class="form-group row">
                        @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                            @if($delivery_data->final_truck_weight > 0)
                                <span class="col-md-2">Final Truck Weight(Kg):</span>
                                <input type="text" class="form-control" id="final_truck_weight_load" name="final_truck_weight_load" placeholder="" value="{{ $delivery_data->final_truck_weight}}"  style="width:170px;">
                            @else
                                <span class="col-md-2">Final Truck Weight(Kg):</span>
                                <input type="text" class="form-control" id="final_truck_weight_load" name="final_truck_weight_load" placeholder="" readonly="readonly" style="width:170px;">
                            @endif
                        @else 
                            <span class="col-md-2">Final Truck Weight(Kg):</span>
                            <input type="text" class="form-control" id="final_truck_weight_load" name="final_truck_weight_load" placeholder="" value="<?php isset($delivery_data->final_truck_weight) && $delivery_data->final_truck_weight>0? print $delivery_data->final_truck_weight:''?>" readonly="readonly" style="width:170px;">
                        @endif 
            </div>
                          <?php
              $truckinformation =json_decode($truckdetails);
            //   dd($truckinformation); 
              if(!empty($truckinformation)){
                $truckvalue = array();
                foreach($truckinformation as $truck_info){
                  
                  $truckvalue[$truck_info->userid] = $truck_info->final_truck_weight;
                  $timevalue[$truck_info->userid] = $truck_info->updated_at;
                }
                // dd($timevalue);
              }
            //  dd($truckdetails);

                $delboy = json_decode($delboys);

                $load_labour = json_decode($load_labours);
                if(!empty($load_labour)){
                    // echo '<pre>';
                    foreach($load_labour as $load_lbr){
                        $ar[$load_lbr->del_boy_id][] = $load_lbr->labour_id;
                    }
                    // print_r($ar);
                }
                
                $total_avg = 0;
              ?>

                @if ($delivery_data->del_boy != "")
                         
                    @foreach($delboy as $key => $info) 
                        <?php
                        // dd($info);
                        //print_R($info->customer->owner_name);
                                        if($key ==0){
                                            $labelkey = "1";
                                        }
                                        else{
                                            $labelkey = $key+1;
                                        }
                        if(!empty($truckvalue[$info->del_boy])){
                            $tvalue = $truckvalue[$info->del_boy];
                            // dd($truckvalue);
                        }
                        else{
                        $tvalue =0;
                        }
                         if($delivery_data->final_truck_weight > 0){
                             $total_avg = $delivery_data->final_truck_weight - $delivery_data->empty_truck_weight;
                         }
                         else{
                             $total_avg = " ";
                         }
                        //  dd($tvalue);
                        $owner_name =$info->users->first_name .' '.$info->users->last_name;
                        if(!empty($timevalue[$info->del_boy])){
                            $datevalue = $timevalue[$info->del_boy];
                        }
                        if(isset($ar) && !empty($ar)){
                            $lbr_id = isset($ar[$info->del_boy])?$ar[$info->del_boy]:null;
                        }
                        // dd($ar);
                        $time = date('h:i a', strtotime(isset($datevalue)?$datevalue:'00:00:00'));
                        $date = date('d/m/Y', strtotime(isset($datevalue)?$datevalue:'01/01/0000'));
                        $label = '';
                        if($time == '12:00 am' || $tvalue == 0){
                            $label = "N/A";
                        }else{
                        $label = isset($info->updated_at)?" Loaded by ".$owner_name." at ".$time ." on ".$date:" Loaded by ".$owner_name;
                        }
                        // print($label);
                        ?>
                        <div class ="row form-group">
                        <span class="col-md-2"style="padding-top:8px;"> Truck Weight {{$labelkey}}(Kg):</span>
            
                        @if($info->del_boy == Auth::id() || Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                        
                         <span><input type="text" name="truck_weight{{$info->del_boy}}" value="{{$tvalue}}" id="truck_weight{{$info->del_boy}}" class="form-control " name="truck_weight{{$info->del_boy}}" style="width: 70px; display:inline;margin-right:1em;" maxlength="10" onkeypress=" return numbersOnly(this, event, true, false);" >
                         <button type="button" value="truck_weight_save" id="btn_truck_weight{{$info->del_boy}}" class="btn btn-sm btn-primary" style="position: relative;margin-right:1em;">Save</button>
                         <!-- <select class=form-control style="width:15%;display:inline-block; margin-right:1em;" multiple="multiple"> -->
                         <select id="labour_select{{$info->del_boy}}" name="labour[{{$info->del_boy}}][]" class="form-control labour_select" multiple="multiple">
                            <!-- <option value="">Please Select Labour</option> -->
                                @if(isset($labours))
                                    @foreach ($labours as $labour)
                                            <option value="{{$labour->id}}" <?php if(isset($lbr_id) && in_array($labour->id,$lbr_id)) echo 'selected="selected"'; ?> >{{$labour->first_name}} {{$labour->last_name}}</option>
                                    @endforeach
                                @endif
                            </select></span>
                            @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                                <span style="padding-top:8px;"><?php print isset($label)?$label:'' ?></span>
                            @else
                                <span style="padding-top:8px;"><?php isset($tvalue) && $tvalue>0 ? print isset($label)?$label:'' : ''?></span>
                            @endif
                        </div>
                         @else
                            @if($tvalue == 0 )
                                <span> <input type="text" readonly="readonly" name="truck_weight" value="{{$tvalue}}" id="truck_weight{{$info->del_boy}}" class="form-control" name="truck_weight{{$info->del_boy}}" style="width: 70px; display:inline;margin-right:1em;" maxlength="10" onkeypress=" return numbersOnly(this, event, true, false);" > 
                                </span><span style="padding-top:8px;"> N/A </span>
                            @else
                                <span> <input type="text" readonly="readonly" name="truck_weight{{$info->del_boy}}" value="{{$tvalue}}" id="truck_weight{{$info->del_boy}}" class="form-control" name="truck_weight{{$info->del_boy}}" style="width: 70px; display:inline;margin-right:1em;" maxlength="10" onkeypress=" return numbersOnly(this, event, true, false);" > 
                                </span><span style="padding-top:8px;">  {{$label}}</span>
                            @endif
                            </div>
                        @endif  
                        
                    @endforeach
                @else
                    <?php
                    $truckinfo =json_decode($truckdetails);
                    if(isset($truckinfo) && !empty($truckinfo)){
                        $truckvalue = array();
                        foreach($truckinfo as $truck_info){
                          
                          $truckvalue[$truck_info->userid] = $truck_info->final_truck_weight;
                        }
                        
                      
                    }
                    if(isset($truck_info) && !empty($truck_info)) {
                        $tvalue = isset($truck_info->final_truck_weight)?$truck_info->final_truck_weight:'';
                    } else {
                        $tvalue = 0;
                    }
                    if(isset($ar) && !empty($ar)){
                        $lbr_id = isset($ar[Auth::id()])?$ar[Auth::id()]:null;
                    }
                    
                        // dd($tvalue);
                    ?>
                    @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                    <div class ="row form-group">
                        <span class="col-md-2"style="padding-top:8px;"> Truck Weight (Kg):</span>
                        <span><input type="text" name="truck_weight" value="{{$tvalue}}" id="truck_weight{{Auth::id()}}" class="form-control " name="truck_weight{{Auth::id()}}" style="width: 70px; display:inline;margin-right:1em;" maxlength="10" onkeypress=" return numbersOnly(this, event, true, false);" ></span>
                        <button type="button" value="truck_weight_save" id="btn_truck_weight{{Auth::id()}}" class="btn btn-sm btn-primary" style="position: relative;margin-right:1em;">Save</button>
                            <select id="labour_select{{Auth::id()}}" name="labour[{{Auth::id()}}][]" class="form-control labour_select" multiple="multiple">
                                @if(isset($labours))
                                    @foreach ($labours as $labour)
                                        <option value="{{$labour->id}}" <?php if(isset($lbr_id) && in_array($labour->id,$lbr_id)) echo 'selected="selected"'; ?> >{{$labour->first_name}} {{$labour->last_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        @if($tvalue == 0 )
                        <span style="padding-top:8px;">N/A </span></div>
                        @endif
                    @endif
                
                
                @endif  
                        <hr>
                        <div class="form-group underline">Product Details</div>
                        <div class="inquiry_table col-md-12">
                        <div class="alert alert-success alert-success1" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            Product values successfully updated.
                        </div>
                        <div class="alert alert-warning alert-success2" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            Please Fill the values for product.
                        </div>
                        <div class="alert alert-warning error-success1" style="display:none;">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            You are not authorised to this order now, Please submit for redirect.
                        </div>
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_load_truck" class="table table-hover">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Actual Pieces</span></td>
                                            <td><span>Average Weight</span></td>
                                            <td><span></span></td>
                                            <td><span>Average Quantity</span></td>
                                            <td><span>Actual Quantity</span></td>    
                                            <td><span>Present Shipping</span></td>
                                            <td><span>Rate</span></td>
                                            @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)<td><span>GST</span></td> @endif  
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Length</span></td>
                                            @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)<td><span>Amount</span></td>@endif 
                                            
                                        </tr>
                                       <?php $key = 1; $actualsum =0; $actualtotal =0;?>
                                       @if(!$truck_load_prodcut_id->isEmpty())<?php 
                                        $truck_product_id = $truck_load_prodcut_id['0']['attributes']['product_id'];
                                        $truck_procudcts = unserialize($truck_product_id);
                                        $explodetruck_prodcuts = explode(',',$truck_procudcts); ?>
                                        @else
                                              <?php $explodetruck_prodcuts = array(); ?>
                                        @endif

                                            @foreach($delivery_data['delivery_product'] as $product)
                                        @if($product->order_type =='delivery_order')
                                        <?php
                                        $actual_quantity = $product->actual_pieces * $product->actual_quantity;              
                                        $actualsum =  $actualsum + $actual_quantity;
                                        $total_dc = $product->actual_quantity * $product->price; 
                                        $total_amt = $actual_quantity * $product->price;
                                        
                                        $actualtotal =  $actualtotal + $total_amt;

                                        // if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8) {
                                        //     if(in_array($product->product_category_id,$explodetruck_prodcuts)){
                                        //         $class = '';
                                        //     }
                                        // }

                                        if(Auth::user()->role_id ==9){
                                             if($product->actual_pieces >0){
                                                 $class = 'readonly="readonly"';
                                                 $class1 = 'disabled';
                                             }
                                             else{
                                                 $class = '';
                                                 $class1 = '';
                                             }
                                          }else {
                                                $class = '';
                                                $class1 = '';
                                            }
                                           
                                           $actual_quantity = $product->actual_pieces * $product->actual_quantity;
                                          
                                        ?>
                                        <tr id="add_row_{{$key}}" class="add_product_row">
                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    {{ $product['order_product_details']->alias_name}}
                                                    <input type="hidden" value="{{$product['order_product_details']->weight}}" id="product_weight_{{$key}}">
                                                    <input type="hidden" name="product[{{$key}}][name]" id="name_{{$key}}" value="{{$product['order_product_details']->alias_name}}">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}" value="{{$product['order_product_details']->id}}">
                                                    <input type="hidden" name="product[{{$key}}][order]" id="product_id{{$key}}" value="{{$product->id}}">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="actual_pieces_{{$key}}" <?php print isset($class) ? $class :''; ?> class="form-control " placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="{{$product->actual_pieces}}" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" maxlength="10" onblur="fetch_average_quantity();" onclick="clear_actual_qty();" >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="average_weight_{{$key}}" <?php print isset($class) ? $class :''; ?>  class="form-control" placeholder="Average Weight" name="product[{{$key}}][average_weight]" value="{{$product->actual_quantity}}" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" onblur="fetch_average_quantity();" maxlength="10" onclick="clear_actual_qty();">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" value="Save{{$key}}" id="btn_save_truck" <?php print isset($class1) ? $class1 :''; ?> class="btn btn-sm btn-primary btn_save" style="position: relative;top: -5px;">Save</button>
                                            </td>
                                            <td class="col-md-1">
                                              
                                                <div class="form-group"><div id="average_quantity_{{$key}}">{{$actual_quantity}}</div></div>
                                                </div>
                                            </td> 
                                            

                                            <td class="col-md-1 sfdsf">
                                                <div class="form-group"><div id="actual_quantity_readonly_{{$key}}" name="product[{{$key}}][actual_quantity]">{{$actual_quantity}}</div></div>
                                                <input id="actual_quantity_{{$key}}"  name="product[{{$key}}][actual_quantity]" value="{{$actual_quantity}}" type="hidden" >
                                              
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    {{ $product->present_shipping}}
                                                    <input id="present_shipping_{{$key}}" class="form-control text-center" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="{{ $product->present_shipping}}" type="hidden" >
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">{{$product->price}}<input type="hidden" class="form-control" id="product_price_{{$key}}" value="{{$product->price}}" name="product[{{$key}}][price]" placeholder="Price" onblur="fetch_price();"></div>
                                            </td>
                                            @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input class="vat_chkbox" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes">
                                                  
                                                </div>
                                            </td>
                                             @endif
                                            <td class="col-md-2">
                                                <div class="form-group ">
                                                    @foreach($units as $unit)
                                                    @if($unit->id == $product->unit_id)
                                                    <input class="form-control" name="product[{{$key}}][units]" id="units_{{$key}}" value="{{$unit->id}}" type="hidden">
                                                    {{$unit->unit_name}}
                                                    <input type="hidden" id="unit_name_{{$key}}" value="{{$unit->unit_name}}">
                                                    @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">{{$product->length}}
                                                <input type="hidden" class="form-control" id="product_length_{{$key}}" value="{{$product->length}}" name="product[{{$key}}][length]"></div>
                                            </td>
                                            @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                                            <td class="col-md-2">
                                                <div class="form-group"><div id="amount_{{$key}}">{{$total_amt}}</div></div>
                                            </td>
                                            @endif
                                        </tr>
                                        <?php $key++; ?>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <label for="total_actual_qty_truck">
                                <b class="load_truck">Actual Quantity*</b> 
                                <input type="text" value ="{{$actualsum}}" class="form-control" id="total_actual_qty_truck" name="total_actual_qty_truck" readonly=""  >  
                            </label>
                            &nbsp;&nbsp;
                            <label for="total_avg_qty">
                                <b class="load_truck">Total Avg Quantity*</b>
                                <input type="text" value ="{{$actualsum}}" class="form-control" id="total_avg_qty" name="total_avg_qty" placeholder="" readonly="">
                                <!--                                <div class="form-group"><div id="total_avg_qty"></div></div>-->
                            </label> 
                        </div>
                        <div class="form-group">  
                        @if(Auth::user()->role_id ==0 || Auth::user()->role_id ==8)
                            <label for="total">
                                <b class="load_truck">Total</b>
                                <span class="gtotal">
                                    <input type="text" value ="{{$actualtotal}}" class="form-control" id="total_price" name="total_price" placeholder="" readonly="">
                                </span>
                            </label>
                            &nbsp;&nbsp;
                        @endif
                            <label for="total">
                                <b class="load_truck">Total Actual Quantity</b>
                                <span class="gtotal">
                                    <input type="text" value ="{{$actualsum}}" class="form-control" id="total_actual_quantity_calc" name="total_actual_quantity_calc" placeholder="" readonly="readonly">
                                </span>
                            </label>
                        </div>
                                
                                <hr>
                                <div>
                                    
                                    
                                   
                                    <button type="submit" name="action" class="btn btn-primary form_button_footer btn_delorderto_delload_truck">Submit</button>
                                  
                                    <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                                </div>
                                <div class="clearfix"></div>
                                {!! Form::close() !!}
                                <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stop
