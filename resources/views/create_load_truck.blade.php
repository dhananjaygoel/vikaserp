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
                        <div class="form-group">Date : {{date('d F, Y')}}</div>
                        <hr>
                        {!!Form::open(array('data-button'=>'btn_delorderto_deltruck','method'=>'POST','url'=>url('create_load_truck/'.$delivery_data['id']),'id'=>'onenter_prevent'))!!}
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
                            {{ $delivery_data->empty_truck_weight}}
                            <input type="hidden" name="empty_truck_weight" value="{{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control" name="empty_truck_weight">
                            @else
                            <input type="text" name="empty_truck_weight" value="{{isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0'}}" id="empty_truck_weight" class="form-control" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                            @endif
                            @else
                            <input type="text" name="empty_truck_weight" value="0" id="empty_truck_weight" class="form-control" name="empty_truck_weight" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                            @endif  
                        </div>
                        <hr>
                        <div class="form-group row">
                           @if($delivery_data->final_truck_weight > 0)
                            <span class="col-md-2">Final Truck Weight(Kg):</span>
                           <input type="text" class="form-control" id="final_truck_weight_load" name="final_truck_weight_load" placeholder="" value="{{ $delivery_data->final_truck_weight}}"  style="width:170px;">
                            @else
                                 <span class="col-md-2">Final Truck Weight(Kg):</span>
                           <input type="text" class="form-control" id="final_truck_weight_load" name="final_truck_weight_load" placeholder="" readonly="readonly" style="width:170px;">
                         @endif  
                        </div>
                          <?php
                          $truckinformation =json_decode($truckdetails);
                          if(!empty($truckinformation)){
                              $truckvalue = array();
                              foreach($truckinformation as $truck_info){
                                  
                                  $truckvalue[$truck_info->userid] = $truck_info->final_truck_weight;
                              }
                              
                          }
                         // print_r($truckvalue);
                         
                          $delboy = json_decode($delboys);
 
                          $total_avg = 0;
                          ?>

                           @if ($delivery_data->del_boy != "")
                         
                        @foreach($delboy as $key => $info) 
                        <?php
                            if($key ==0){
                                $label = 1;
                            }
                            else{
                                $label = $key+1;
                            }
                         if(!empty($truckvalue[$info->del_boy])){
                              $tvalue = $truckvalue[$info->del_boy];
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
                         ?>
                        <div class ="row form-group">
                        <span class="col-md-2"> Truck Weight{{$label}}(Kg):</span>
                        
                        @if($info->del_boy == Auth::id() )
                        
                        
                         <input type="text" name="truck_weight{{$info->del_boy}}" value="{{$tvalue}}" id="truck_weight{{$info->del_boy}}" class="form-control " name="truck_weight{{$info->del_boy}}" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                         @else
                          <input type="text" readonly="readonly" name="truck_weight{{$info->del_boy}}" value="{{$tvalue}}" id="truck_weight{{$info->del_boy}}" class="form-control" name="truck_weight{{$info->del_boy}}" style="width: 10.33%;" maxlength="10" onkeypress=" return numbersOnly(this, event, false, false);" >
                          </div>
                          @endif  
                         
                          @endforeach
                        @endif    
                       
                         
                       
                       
                        
                        

                        <hr>
                        <div class="form-group underline">Product Details</div>
                        <div class="inquiry_table col-md-12">
                            <div class="table-responsive">
                                <table id="add_product_table_delivery_load_truck" class="table table-hover">
                                    <tbody>
                                        <tr class="headingunderline">
                                            <td><span>Select Product(Alias)</span><span class="mandatory">*</span></td>
                                            <td><span>Actual Pieces</span></td>
                                            <td><span>Average Weight</span></td>
                                            <td><span>Average Quantity</span></td>
                                            <td><span>Actual Quantity</span></td>    
                                            <td><span>Present Shipping</span></td>
                                            <td><span>Rate</span></td>
                                            <td><span>GST</span></td>
                                            <td><span>Unit</span><span class="mandatory">*</span></td>
                                            <td><span>Amount</span></td>
                                        </tr>
                                        <?php $key = 1; 
                                         
                                        ?>
                                        @foreach($delivery_data['delivery_product'] as $product)
                                        @if($product->order_type =='delivery_order')
                                        <?php
                                           if($product->actual_pieces >0){
                                               $class = 'readonly="readonly"';
                                           }
                                           else{
                                                $class = '';
                                           }
                                           $actual_quantity = $product->actual_pieces * $product->actual_quantity;
                                        ?>
                                        <tr id="add_row_{{$key}}" class="add_product_row" {{($product->present_shipping==0)?'style = display:none':''}}>
                                            <td class="col-md-2">
                                                <div class="form-group searchproduct">
                                                    {{ $product['order_product_details']->alias_name}}
                                                    <input type="hidden" value="{{$product['order_product_details']->weight}}" id="product_weight_{{$key}}">
                                                    <input type="hidden" name="product[{{$key}}][name]" id="name_{{$key}}" value="{{$product['order_product_details']->alias_name}}">
                                                    <input type="hidden" name="product[{{$key}}][id]" id="add_product_id_{{$key}}" value="{{$product['order_product_details']->id}}">
                                                    <input type="hidden" name="product[{{$key}}][order]" value="{{$product->id}}">
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="actual_pieces_{{$key}}" <?php print $class; ?> class="form-control " placeholder="Actual Pieces" name="product[{{$key}}][actual_pieces]" value="{{$product->actual_pieces}}" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" maxlength="10" onblur="fetch_average_quantity();" onclick="clear_actual_qty();" >
                                                </div>
                                            </td>
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    <input id="average_weight_{{$key}}" <?php print $class; ?> class="form-control" placeholder="Average Weight" name="product[{{$key}}][average_weight]" value="{{$product->actual_quantity}}" type="tel" onkeypress=" return numbersOnly(this, event, true, false);" onblur="fetch_average_quantity();" maxlength="10" onclick="clear_actual_qty();">
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                              
                                                <div class="form-group"><div id="average_quantity_{{$key}}">{{$actual_quantity}}</div></div>
                                                </div>
                                            </td>

                                            <td class="col-md-1 sfdsf">
                                                <div class="form-group"><div id="actual_quantity_readonly_{{$key}}" name="product[{{$key}}][actual_quantity]"></div></div>
                                                <input id="actual_quantity_{{$key}}"  name="product[{{$key}}][actual_quantity]" value="" type="hidden" >
                                              
                                            <td class="col-md-2">
                                                <div class="form-group">
                                                    {{ $product->present_shipping}}
                                                    <input id="present_shipping_{{$key}}" class="form-control text-center" placeholder="Present Shipping" name="product[{{$key}}][present_shipping]" value="{{ $product->present_shipping}}" type="hidden" >
                                                </div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">{{$product->price}}<input type="hidden" class="form-control" id="product_price_{{$key}}" value="{{$product->price}}" name="product[{{$key}}][price]" placeholder="Price" onblur="fetch_price();"></div>
                                            </td>
                                            <td class="col-md-1">
                                                <div class="form-group">
                                                    <input class="vat_chkbox" type="checkbox" {{($product->vat_percentage>0)?'checked':''}} name="product[{{$key}}][vat_percentage]" value="yes">
                                                  
                                                </div>
                                            </td>
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
                                                <div class="form-group"><div id="amount_{{$key}}"></div></div>
                                            </td>
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
                                <input type="text" class="form-control" id="total_actual_qty_truck" name="total_actual_qty_truck" readonly=""  >  
                            </label>
                            &nbsp;&nbsp;
                            <label for="total_avg_qty">
                                <b class="load_truck">Total Avg Quantity*</b>
                                <input type="text" class="form-control" id="total_avg_qty" name="total_avg_qty" placeholder="" readonly="readonly" value ="{{$total_avg}}">
                                <!--                                <div class="form-group"><div id="total_avg_qty"></div></div>-->
                                </div>
                                <div class="form-group">    
                                    <label for="total">
                                        <b class="load_truck">Total</b>
                                        <span class="gtotal">
                                            <input type="text" class="form-control" id="total_price" name="total_price" placeholder="" readonly="readonly">
                                        </span>
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="total">
                                        <b class="load_truck">Total Actual Quantity</b>
                                        <span class="gtotal">
                                            <input type="text" class="form-control" id="total_actual_quantity_calc" name="total_actual_quantity_calc" placeholder="" readonly="readonly">
                                        </span>
                                    </label>
                                </div>
                                
                                <hr>
                                <div>
                                    <button type="submit" class="btn btn-primary form_button_footer btn_save_truck">Save</button>
                                    @if ($delivery_data->final_truck_weight == "0")
                                    <button type="submit" class="btn btn-primary form_button_footer btn_delorderto_delload_truck disabled">Submit</button>
                                    @else
                                    <button type="submit" class="btn btn-primary form_button_footer btn_delorderto_delload_truck">Submit</button>
                                    @endif
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
