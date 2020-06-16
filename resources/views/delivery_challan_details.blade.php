@extends('layouts.master')
@section('title',$page_title)
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li><a href="{{url($url)}}">{{$page_title}}</a></li>
                    <li class="active"><span>View {{$page_title}}</span></li>
                </ol>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box">
                    <div class="main-box-body clearfix">                        
                        @if($allorder['delivery_order']->order_source == 'warehouse')                            
                            <div class="form-group">
                                <label><b>Order From:</b> Warehouse                                    
                                </label>
                            </div><hr>
                        @elseif($allorder['delivery_order']->order_source == 'supplier')
                        <div class="form-group">
                            <label><b>Order From:</b> 
                                @foreach($customers as $customer)
                                @if($customer->id == $allorder['delivery_order']->supplier_id)                                                                                                    
                                    {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                                @endif
                                @endforeach
                            </label>
                        </div><hr>                        
                        @endif
                        <div class="form-group">
                            <label><b>Order For:</b>
                                {{($allorder->customer->tally_name != "")? $allorder->customer->tally_name : $allorder->customer->owner_name}}
                            </label>
                        </div><hr>
                         <div class="form-group">
                            <label><b>Serial Number:</b>
                                {{($allorder->serial_number != '') ? $allorder->serial_number : $allorder->delivery_order->serial_no}}
                            </label>
                        </div><hr>
                           <?php
                             $is_allincludive = 0;
                             foreach ($allorder['all_order_products'] as $key=>$product){
                                 if($product->order_type =='delivery_challan'){
                                     if($product->vat_percentage>0){
                                         $is_allincludive = 1;
                                         break;
                                     }
                                 }
                             }
                           ?>
                        @if($is_allincludive)
                                <div class="form-group">
                                    <label><b>Empty Truck Weight:</b>
                                        {{($allorder->delivery_order->empty_truck_weight != '') ? $allorder->delivery_order->empty_truck_weight : '0'}}
                                    </label>
                                </div><hr>
                                <div class="form-group">
                                    <label><b>Final Truck Weight:</b>
                                        {{($allorder->delivery_order->final_truck_weight != '') ? $allorder->delivery_order->final_truck_weight : '0'}}
                                    </label>
                                </div><hr>
                        @endif



                        @if($allorder['delivery_order']->discount > 0)
                            <div class="form-group">
                                <label><b>Discount/Premium :</b> </label>
                                {{$allorder['delivery_order']->discount_type}}                                 
                            </div>
                            <div class="form-group">                                    
                                <label><b>Fixed/Percentage :</b> </label>
                                {{$allorder['delivery_order']->discount_unit}}                                    
                            </div>
                            <div class="form-group">                                    
                                <label><b>Amount :</b> </label>
                                {{$allorder['delivery_order']->discount}}                                   
                            </div>
                        @else
                            <div class="form-group">                                
                                <label><b>Discount/Premium :</b> </label>                                    
                            </div>
                            <div class="form-group">                                     
                                     <label><b>Fixed/Percentage :</b> </label>                                   
                            </div>
                            <div class="form-group">                                    
                                    <label><b>Amount :</b> </label>                                    
                            </div>
                        @endif
                       

                        <div class="form-group">
                            <label><b><span class="underline">Product Details</span></b></label>
                        </div>
                        <div class="table-responsive">
                            <table id="add_product_table" class="table customerview_table">
                                <tbody>
                                    <tr class="headingunderline">
                                        <td class="col-md-2"><span>Product Name(Alias)</span></td>
                                        <td class="col-md-1"><span>Actual Quantity</span></td>
                                        <td class="col-md-1"><span>Actual Pieces</span></td>
                                        <td class="col-md-1"><span>Unit</span></td>
                                        <td class="col-md-1"><span>Length</span></td>
                                        <td class="col-md-2"><span>Present Shipping</span></td>
                                        @if(Auth::user()->role_id == 5)
                                        <td class="col-md-2"><span>Total Order</span></td>
                                        @endif
                                        <td class="col-md-1"><span>Rate</span></td>
                                        <td class="col-md-1"><span>GST</span></td>
                                        <td class="col-md-1"><span>Amount</span></td>
                                    </tr>
                                    <?php $total_amount = 0; ?>
                                    <?php
                                        $cust_id = $allorder->customer_id;
                                        $order_id = $allorder->order_id;
                                        // $state = \App\Customer::where('id',$cust_id)->first()->state;
                                        // $local_state = App\States::where('id',$state)->first()->local_state;
                                        $loc_id = \App\DeliveryOrder::where('customer_id',$cust_id)->where('order_id',$order_id)->first();
                                        $state = \App\DeliveryLocation::where('id',isset($loc_id->delivery_location_id)?$loc_id->delivery_location_id:0)->first();
                                        $local = \App\States::where('id',isset($state->state_id)?$state->state_id:0)->first();
                                        $local_state = isset($local->local_state)?$local->local_state:0;
                                        $i = 1;
                                        $total_price = 0;
                                        $total_vat = 0;
                        //                $total_qty = 0;
                                        // $loading_vat_amount = ($allorder->loading_charge * $allorder->loading_vat_percentage) / 100;
                                        // $freight_vat_amount = ($allorder->freight * $allorder->freight_vat_percentage) / 100;
                                        // $discount_vat_amount = ($allorder->discount * $allorder->discount_vat_percentage) / 100;
                                        if(isset($allorder['all_order_products'][0]->vat_percentage) && $allorder['all_order_products'][0]->vat_percentage > 0){
                                            $loading_vat = 18;
                                        }else{
                                            $loading_vat = 0;
                                        }
                                        $loading_vat_amount = ((float)$allorder->loading_charge * (float)$loading_vat) / 100;
                                        $freight_vat_amount = ((float)$allorder->freight * (float)$loading_vat) / 100;
                                        $discount_vat_amount = ((float)$allorder->discount * (float)$loading_vat) / 100;
                                        $final_vat_amount = 0; 
                                        $final_total_amt = 0;
                                    ?>


                                    @foreach($allorder['all_order_products'] as $key=>$product) 
                                    @if($product->order_type =='delivery_challan')
                                    <tr id="add_row_{{$key}}" class="add_product_row">
                                        <td class="col-md-2">
                                            <div class="form-group searchproduct">{{isset($product->order_product_details->alias_name)?$product->order_product_details->alias_name:''}}</div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group">{{isset($product->quantity)?$product->quantity:''}}</div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group">{{isset($product->actual_pieces)?$product->actual_pieces:''}}</div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group ">{{isset($product->unit->unit_name)?$product->unit->unit_name:''}}</div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group ">{{isset($product->length)?$product->length:''}}</div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group">{{isset($product->present_shipping)?$product->present_shipping:''}}</div>
                                        </td>

                                        @if(Auth::user()->role_id == 5)
                                        <td>
                                            @foreach($order_product['all_order_products'] as $all_order_products)

                                            @if($all_order_products->product_category_id == $product->product_category_id)
                                            {{$all_order_products->quantity}}                                            
                                            @endif
                                            @endforeach


                                        </td>
                                        @endif


                                        <td class="col-md-1">
                                            <div class="form-group">{{isset($product->price)?$product->price:''}}</div>
                                        </td>
                                        <td class="col-md-1">
                                            <div class="form-group">
                                                <input type="checkbox" disabled="" {{($product->vat_percentage>0)?'checked':''}} >
                                            </div>
                                        </td>
                                        <td class="col-md-2">
                                            <div class="form-group">
                                                <?php
                                                $amount = (float)$product->actual_quantity * (float)$product->price;
                                                $total_amount = round($amount + $total_amount, 2);
                                                ?>
                                                 <?php
                                                    $productsub = \App\ProductSubCategory::where('id',$product['product_category_id'])->first();
                                                    $product_cat = \App\ProductCategory::where('id',$productsub->product_category_id)->first();
                                                    $sgst = 0;
                                                    $cgst = 0;
                                                    $igst = 0;
                                                    $rate = (float)$product->price;
                                                    $is_gst = false;
                                                    if(isset($product->vat_percentage) && $product->vat_percentage > 0){
                                                        if($product_cat->hsn_code){
                                                            
                                                            $is_gst = true;
                                                            $hsn_det = \App\Hsn::where('hsn_code',$product_cat->hsn_code)->first();
                                                            if(isset($hsn_det->gst))
                                                            $gst_det = \App\Gst::where('gst',$hsn_det->gst)->first();
                                                            // dd($hsn_det);
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
                                                    // $total_pr = $sgst + $cgst + $igst;
                                                    $total_vat_amount = $total_vat_amount1;
                                                    // $total_vat_amount = round($total_vat_amount1,2);
                                                    // $total_price += $total_vat_amount;
                                                    // $total_price += ($total_vat_amount + $loading_vat_amount + $freight_vat_amount) + $discount_vat_amount;
                                                    $total_price += ($total_vat_amount);
                                                    // print_r($total_pr);print_r($total_price);
                                                ?>
                                                {{round($amount, 2)}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label for="Total_actual_qty"><b class="challan">Total Actual Quantity: </b></label>
                            {{$allorder->all_order_products->sum('actual_quantity')}}&nbsp;&nbsp;
                            <label for="TOtal_amount"><b class="challan">Total Amount: </b></label> {{$total_amount}}
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-12 no_left_margin">
                                <label for="Loading"><b class="challan">Loading: </b></label> {{$allorder->loading_charge}}
                            </div>
                            <br>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-12 no_left_margin">
                                <label for="Discount"><b class="challan">Discount: </b></label> {{$allorder->discount}}
                            </div>
                            <br>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-12 no_left_margin">
                                <label for="Freight"><b class="challan">Freight: </b></label> {{$allorder->freight}}
                            </div>
                            <br>
                        </div>
                        <hr>
                        <div class="form-group">
                        <?php $total = (float)$total_amount + (float)$allorder->freight + (float)$allorder->loading_charge + (float)$allorder->discount?>
                            <label for="total"><b class="challan">Total: </b></label> <?php print_r((float)$total_amount + (float)$allorder->freight + (float)$allorder->loading_charge + (float)$allorder->discount); ?>
                        </div>                        
                        <hr>
                        <!-- @if($product_type['pipe'] == 1)
                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Loaded By (Pipe): </b></label> <?php
                            // if (isset($allorder['challan_loaded_by'])) {
                            //     foreach ($allorder['challan_loaded_by'] as $challan_loaded_by) {
                            //         foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                            //             if (isset($challan_loaded_by->product_type_id) && ($challan_loaded_by->product_type_id == 1 | $challan_loaded_by->product_type_id == 0)) {
                            //                 echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                            //             }
                            //         }
                            //     }
                            // }
                            ?>
                        </div>
                        <hr>
                        @endif -->
                        <!-- @if($product_type['structure'] == 1)
                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Loaded By (Structure): </b></label> <?php
                            // if (isset($allorder['challan_loaded_by'])) {
                            //     foreach ($allorder['challan_loaded_by'] as $challan_loaded_by) {
                            //         foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                            //             if (isset($challan_loaded_by->product_type_id) && ($challan_loaded_by->product_type_id == 2 | $challan_loaded_by->product_type_id == 0)) {
                            //             echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                            //             }
                            //         }
                            //     }
                            // }
                            ?>
                        </div>
                        <hr>
                        @endif
                        @if($product_type['sheet'] == 3)
                        <div class="form-group">
                            <label for="loadedby"><b class="challan">Loaded By (Profile): </b></label> <?php
                            // if (isset($allorder['challan_loaded_by'])) {
                            //     foreach ($allorder['challan_loaded_by'] as $challan_loaded_by) {
                            //         foreach ($challan_loaded_by['dc_loaded_by'] as $loadedby) {
                            //             if (isset($challan_loaded_by->product_type_id) && ($challan_loaded_by->product_type_id == 3 | $challan_loaded_by->product_type_id == 0)) {
                            //             echo ucfirst($loadedby->first_name) . " " . ucfirst($loadedby->last_name) . ", ";
                            //             }
                            //         }
                            //     }
                            // }
                            ?>
                        </div>
                        <hr>
                        @endif -->
                        <!-- @if($product_type['pipe'] == 1)
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour (Pipe): </b></label>

                            <?php
                            // if (isset($allorder['challan_labours']) && !empty($allorder['challan_labours'])) {
                            //     foreach ($allorder['challan_labours'] as $challan_labour) {
                            //         foreach ($challan_labour['dc_labour'] as $labour) {
                            //             if (isset($challan_labour->product_type_id) && ($challan_labour->product_type_id == 1 | $challan_labour->product_type_id == 0)) {
                            //                 echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                            //             }
                            //         }
                            //     }
                            // } else {
                            //     echo "N/A";
                            // }
                            ?>

                        </div>
                        <hr>
                        @endif
                        @if($product_type['structure'] == 1)
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour (Structure): </b></label>

                            <?php
                            // if (isset($allorder['challan_labours']) && !empty($allorder['challan_labours'])) {
                            //     foreach ($allorder['challan_labours'] as $challan_labour) {
                            //         foreach ($challan_labour['dc_labour'] as $labour) {
                            //             if (isset($challan_labour->product_type_id) && ($challan_labour->product_type_id == 2 | $challan_labour->product_type_id == 0)) {
                            //                 echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                            //             }
                            //         }
                            //     }
                            // } else {
                            //     echo "N/A";
                            // }
                            ?>

                        </div>
                        <hr>
                        @endif -->
                        <!-- @if($product_type['sheet'] == 3)
                        <div class="form-group">
                            <label for="labour"><b class="challan">Labour (Profile): </b></label>

                            <?php
                            // if (isset($allorder['challan_labours']) && !empty($allorder['challan_labours'])) {
                            //     foreach ($allorder['challan_labours'] as $challan_labour) {
                            //         foreach ($challan_labour['dc_labour'] as $labour) {
                            //             if (isset($challan_labour->product_type_id) && ($challan_labour->product_type_id == 3 | $challan_labour->product_type_id == 0)) {
                            //                 echo ucfirst($labour->first_name) . " " . ucfirst($labour->last_name) . ", ";
                            //             }
                            //         }
                            //     }
                            // } else {
                            //     echo "N/A";
                            // }
                            ?>

                        </div>
                        <hr>
                        @endif                         -->

                        <!-- <div class="form-group">
                            <label for="driver_contact"><b class="challan">GST Percentage: </b> 
                               @if($allorder->vat_percentage != "" || $allorder->vat_percentage != 0)
                                {{$allorder->vat_percentage}} %
                               @else
                                0 %
                               @endif
                            </label>
                        </div>
                        <hr> -->
                        
                        <!--                        <div class="form-group">
                                                    <label for="Plusvat"><b class="challan">GST: </b> No</label>
                                                </div>
                                                <hr>--> 

                       
                        @if(isset($product->vat_percentage) && $product->vat_percentage>0)                    
                        <div class="form-group">
                            <label for="total"><b class="challan">GST Amount: </b> <?php
                            $total_vat = $total_price + $loading_vat_amount + $freight_vat_amount + $discount_vat_amount;
                            ?> {{round($total_vat,5)}}</label>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="total"><b class="challan">Round Off: </b>
                            <?php $roundoff = $total_vat; ?> {{round($roundoff,2)}}</label>
                        </div>
                        <hr/>
                        @endif
                        <div class="form-group">
                            <label for="total"><b class="challan">Grand Total: </b>
                            <?php $tot = $total + $total_vat; ?> {{round($tot,2)}}</label>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="vehicle_number"><b class="challan">Vehicle Number: </b> {{$allorder->delivery_order->vehicle_number}}</label>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="driver_contact"><b class="challan">Driver Contact: </b> {{$allorder->delivery_order->driver_contact_no}}</label>
                        </div>
                        <hr>
                        @if($allorder->bill_number != "")
                        <div class="form-group">
                            <label for="billno"><b class="challan">Bill Number: </b></label> {{$allorder->bill_number}}
                        </div>
                        <hr>
                        @endif
                        @if($allorder->order_id > 0)
                        <div class="form-group">
                            <label for="orderby"><b class="challan">Order By: </b></label> {{(isset($allorder->order_details->createdby->first_name)?$allorder->order_details->createdby->first_name:"")." ".(isset($allorder->order_details->createdby->last_name)?$allorder->order_details->createdby->last_name:"")}}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="orderdatetime"><b class="challan">Order Time/Date: </b></label> {{$allorder->order_details->updated_at}}
                        </div>
                        <hr>
                        @else
                        <div class="form-group">
                            <label for="deliveryorderby"><b class="challan">Delivery Order By: </b></label> {{$allorder->delivery_order->user->first_name." ".$allorder->delivery_order->user->last_name}}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="deliveryorderdatetime"><b class="challan">Delivery Order Time/Date: </b></label> {{$allorder->delivery_order->updated_at}}
                        </div><hr>
                        @endif
                        <div class="form-group">
                            <label for="deliveryorderby"><b class="challan">Delivery Challan By: </b></label> {{(isset($allorder->user->first_name)?$allorder->user->first_name:"")." ".(isset($allorder->user->last_name)?$allorder->user->last_name:"")}}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="deliveryorderdatetime"><b class="challan">Delivery Challan Time/Date: </b></label> {{$allorder->updated_at}}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="challan_remark"><b class="challan">Remark: </b></label>
                            <textarea class="form-control" id="challan_remark" name="challan_remark" rows="3" readonly="readonly">{{$allorder->remarks}}</textarea>
                        </div>
                        <!--                        <a href="{{url('delivery_challan')}}" class="btn btn-default form_button_footer">Back</a>-->

                        @if( Auth::user()->role_id  <> 5)
                        <a href="{{URL::previous()}}" class="btn btn-default form_button_footer">Back</a>
                        @endif
                        @if( Auth::user()->role_id  == 5)
                        <a href="{{url('order/'.$allorder->order_id.'-track')}}" class="btn btn-default form_button_footer">Back</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
