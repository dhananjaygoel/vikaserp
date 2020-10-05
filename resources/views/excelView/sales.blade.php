<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Ref NUM</th>
            <th style="height:20px;font-size:16px;color:#000080;">Vch Type</th>
            <th style="height:20px;font-size:16px;color:#000080;">Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Account</th>
            <th style="height:20px;font-size:16px;color:#000080;">Item Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Product Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Pcs</th>
            <th style="height:20px;font-size:16px;color:#000080;">Unit</th>
            <th style="height:20px;font-size:16px;color:#000080;">Qty</th>
            <th style="height:20px;font-size:16px;color:#000080;">Rate</th>
            <th style="height:20px;font-size:16px;color:#000080;">Amt</th>
            <th style="height:20px;font-size:16px;color:#000080;">Vehicle Number/Remark</th>
            <th style="height:20px;font-size:16px;color:#000080;">Vch No</th>
        </tr>
        <?php
        $VchNo = 1;
        // dd($allorders);
        if($allorders != null){
            foreach ($allorders as $key => $value) {
                $total_amount = 0;
                $cust_id = $value->customer_id;
                $order_id = $value->order_id;
                $loc_id = \App\DeliveryOrder::where('customer_id',$cust_id)->where('order_id',$order_id)->first();
                $state = \App\DeliveryLocation::where('id',isset($loc_id->delivery_location_id)?$loc_id->delivery_location_id:0)->first();
                $local = \App\States::where('id',isset($state->state_id)?$state->state_id:0)->first();
                $local_state = isset($local->local_state)?$local->local_state:0;
                $i = 1;
                $total_price = 0;
                $total_vat = 0;
                $round_off = 0;
                if(isset($value->vat_percentage) && $value->vat_percentage > 0){
                    $loading_vat = 18;
                }else{
                    $loading_vat = 0;
                }
                $loading_vat_amount = ((float)$value->loading_charge * (float)$loading_vat) / 100;
                $freight_vat_amount = ((float)$value->freight * (float)$loading_vat) / 100;
                $discount_vat_amount = ((float)$value->discount * (float)$loading_vat) / 100;
                $final_vat_amount = 0; 
                $final_total_amt = 0;
                $total_amount=0;
                $total_price = 0;
                $price = 0;

                foreach ($value['delivery_challan_products'] as $key1 => $value1) {
                    $productsub = \App\ProductSubCategory::where('id',$value1['product_category_id'])->first();
                    $product_cat = \App\ProductCategory::where('id',$productsub->product_category_id)->first();
                    $sgst = 0;
                    $cgst = 0;
                    $igst = 0;
                    $rate = (float)((isset($value1->price) && $value1->price != '0.00') ? $value1->price : $product_cat->price);
                    $is_gst = false;
                    $amount = (float)$value1->actual_quantity * (float)$rate;
                    $total_amount = round($amount + $total_amount, 2);
                    if(isset($value1->vat_percentage) && $value1->vat_percentage > 0){
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
                    if(isset($value1->vat_percentage) && $value1->vat_percentage > 0){
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
                <tr>
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td style="height:16px;">Sales</td>
                    <td style="height:16px;">{{ isset($value['customer']->tally_name) ? (($value['customer']->tally_name != "") ? $value['customer']->tally_name : "Advance Sales"):'' }}</td>
                    <td style="height:16px;">Sales Account</td>
                    <td style="height:16px;">{{ isset($value1['order_product_details']->alias_name) ? $value1['order_product_details']->alias_name : '' }}</td>
                    <td style="height:16px;">{{ isset($value1['order_product_details']->product_category->product_category_name) ? $value1['order_product_details']->product_category->product_category_name : '' }}</td>
                    <td style="height:16px;">{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '' }}</td>
                    @if(isset($value1->unit_id) && $value1->unit_id == 1)
                        <td style="height:16px;">Kg</td>
                    @elseif(isset($value1->unit_id) && $value1->unit_id == 2)
                        <td style="height:16px;">Pieces</td>
                    @elseif(isset($value1->unit_id) && $value1->unit_id == 3)
                        <td style="height:16px;">Meter</td>
                    @elseif(isset($value1->unit_id) && $value1->unit_id == 4)
                        <td style="height:16px;">ft</td>
                    @elseif(isset($value1->unit_id) && $value1->unit_id == 5)
                        <td style="height:16px;">mm</td>
                    @else
                        <td style="height:16px;">Kg</td>
                    @endif
                    <td style="height:16px;">{{ isset($value1->actual_quantity) ? $value1->actual_quantity : '' }}</td>
                    <td style="height:16px;">{{ ((isset($value1->price) && $value1->price != '0.00') ? $value1->price : $product_cat->price) }}</td>
                    <?php $tot_amt = $value1->price * $value1->quantity;
                    ?>
                    <td style="height:16px;">{{ round($tot_amt,2) }}</td>
                    <td style="height:16px;">
                    <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                    ?>
                    {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}</td>
                    <td style="height:16px;">{{$VchNo}}</td>
                </tr>
            <?php }?>
                <tr>
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td style="height:16px;"></td><td></td>
                    <td style="height:16px;">Discount</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td style="height:16px;">{{(isset($value->discount)&& !empty($value->discount))? $value->discount :'0.00'}}</td>
                    <td style="height:16px;">
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td>
                    <td>{{$VchNo}}</td>
                </tr>
                <tr> 
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td></td><td></td>
                    <td style="height:16px;">Loading</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td style="height:16px;">{{isset($value->loading_charge) ? $value->loading_charge :'0.00'}}</td>
                    <td style="height:16px;">
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td> 
                    <td>{{$VchNo}}</td> 
                </tr> 
                <tr>  
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td></td><td></td>
                    <td style="height:16px;">Freight</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td style="height:16px;">{{isset($value->freight) ? $value->freight :'0.00'}}</td>
                    <td>
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td> 
                    <td>{{$VchNo}}</td>                
                </tr>
                <tr>    
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td></td><td></td>
                    <td style="height:16px;">Tax</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                      
                        <?php
                            $total = (float)$total_amount + (float)$value->freight + (float)$value->loading_charge + (float)$value->discount;
                            $total_vat = $total_price + $loading_vat_amount + $freight_vat_amount + $discount_vat_amount;
                            $tot = $total + $total_vat;
                            if($value->tcs_applicable == 1 && isset($value->vat_percentage) && $value->vat_percentage > 0){
                                $tcs_amount = $tot * $value->tcs_percentage / 100;
                                $tot = $tot + round($tcs_amount,2);
                            }

                            
                            $round_off = round($tot,0) - $tot;
                        ?>
                    <td style="height:16px;">{{ round($total_vat,2) }}
                    </td>
                    <td style="height:16px;">
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td> 
                    <td style="height:16px;">{{$VchNo}}</td>                
                </tr>
                <!-- <tr>    
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td></td><td></td>
                    <td style="height:16px;">Round Off</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td style="height:16px;">{{ round($round_off,2) }}</td>
                    <td>
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td> 
                    <td style="height:16px;">{{$VchNo}}</td>
                </tr> -->
                @if($value->tcs_applicable == 1)
                <tr>    
                    <td style="height:16px;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:16px;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td></td><td></td>
                    <td style="height:16px;">TCS</td>
                    <td></td><td></td><td></td><td></td><td></td><td></td>
                    <td style="height:16px;">{{ round($tcs_amount,2) }}</td>
                    <td>
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td> 
                    <td style="height:16px;">{{$VchNo}}</td>
                </tr>
                @endif
                <tr>    
                    <td style="height:18px;border:2px solid #4fe24f;">{{ date("j F, Y", strtotime($value->updated_at)) }}</td>
                    <td style="height:18px;border:2px solid #4fe24f;">{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"> <b>Total</b></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"></td>
                    <td style="height:18px;border:2px solid #4fe24f;"><b>{{ round($tot,0) }}</b></td>
                    <td style="height:18px;border:2px solid #4fe24f;">
                        <?php
                        if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                            echo "[" . $value['delivery_order']->vehicle_number . "]";  
                        ?>
                        {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                    </td>                
                    <td style="height:18px;border:2px solid #4fe24f;">{{$VchNo}}</td>
                </tr>
<?php 
        $VchNo++;
        }
    }
    // dd('end');
?>
    </table>
</html>