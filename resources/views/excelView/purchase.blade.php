<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
         <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Vch No</th>
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
            <th style="height:20px;font-size:16px;color:#000080;">Ref NUM</th>
        </tr>
        <?php
         $VchNo=1;
         foreach ($purchase_orders as $key => $value) {
            $next_cnt = count((array)$value['delivery_challan_products']);
            $grand_vat_amt = 0;
            $current_number = 1;
            $total_quantity = 0;
            $amount = 0;
            $total_amount = 0;
            $rate = 0;
            $total = 0;$freight_vat = 0;$discount_vat=0;$roundoff=0;
            $total_gst_amount=0;
            ?>
            <?php foreach ($value['all_purchase_products'] as $key1 => $value1) {
                  $order_quantity = 0;
            ?>
        <tr>
            <td style="height:16px;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:16px;text-align: left;">{{$VchNo}}</td>
            <td style="height:16px;">Purchase</td>
           <td>
               <!-- @if($value['supplier']->tally_name != "" && $value['supplier']->owner_name != "")
                {{ $value['supplier']->owner_name }}-{{$value['supplier']->tally_name}}
                @else 
                {{ $value['supplier']->owner_name }}
                @endif -->
                {{ isset($value['supplier']->tally_name) ? $value['supplier']->tally_name : 'N/A' }}
            </td>
            <td style="height:16px;">Purchase Account</td>
            <td style="height:16px;">{{ isset($value1['purchase_product_details']->alias_name) ? $value1['purchase_product_details']->alias_name : '' }}</td>
            <td style="height:16px;">{{ isset($value1['purchase_product_details']->product_category->product_category_name) ? $value1['purchase_product_details']->product_category->product_category_name : '' }}</td>
            <td style="height:16px;text-align: left;">{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '' }}</td>
            @if(isset($value1->unit_id) && $value1->unit_id == 1)
                <td style="height:16px;text-align: left;">Kg</td>
            @elseif(isset($value1->unit_id) && $value1->unit_id == 2)
                <td style="height:16px;text-align: left;">Pieces</td>
            @elseif(isset($value1->unit_id) && $value1->unit_id == 3)
                <td style="height:16px;text-align: left;">Meter</td>
            @elseif(isset($value1->unit_id) && $value1->unit_id == 4)
                <td style="height:16px;text-align: left;">ft</td>
            @elseif(isset($value1->unit_id) && $value1->unit_id == 5)
                <td style="height:16px;text-align: left;">mm</td>
            @else
                <td style="height:16px;text-align: left;">Kg</td>
            @endif
            <!-- <td style="height:16px;text-align: left;">{{ isset($value1->unit->unit_name) ? $value1->unit->unit_name : '' }}</td> -->
            <td style="height:16px;text-align: left;">{{ round($value1->quantity,2) }}</td>
            <td style="height:16px;text-align: left;">{{ ((isset($value1->price) && $value1->price != '0.00') ? $value1->price : $value1['purchase_product_details']->product_category['price']) }}</td>
            <td style="height:16px;text-align: left;">
                        <?php
                        $rate = (float)((isset($value1->price) && $value1->price != '0.00') ? $value1->price : $value1['purchase_product_details']->product_category['price']);
                        $amount = (float)$value1->quantity * (float)$rate;
                        $total_amount = round($amount + $total_amount, 2);
                        // Calculation Updated by 157 on 03-09-2015
//                         $total_amt = "";

//                         if (isset($value1->quantity) && !empty($value1->quantity) && $value1->quantity != 0) {

//                             $vat_amt = 0;
//                             $tot_amt = $value1->price * $value1->quantity;

//                             if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
//                                 $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['purchase_advice']->vat_percentage / 100));
//                             }

//                             if ($next_cnt == $current_number) {

//                                 $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount = $value->round_off;
//                                 if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
//                                     $vat_amt = ($total_amt * ($value['purchase_advice']->vat_percentage / 100));
//                                 }
//                                 $total_amt = $vat_amt + $total_amt;
//                             } else {
//                                 $total_amt = $tot_amt;
//                             }
//                         } else {
//                             $vat_amt = 0;
// //                            $total_amt = $total_amt * $value1->actual_pieces * $value1->order_product_details->weight;
//                             if(isset($value1->order_product_details->weight))
//                             $tot_amt = $value1->price * $value1->actual_pieces * $value1->order_product_details->weight;
//                         else {
//                              $tot_amt = $value1->price * $value1->actual_pieces;
//                         }
                            
                            
//                             if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
//                                 $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['purchase_advice']->vat_percentage / 100));
//                             }
//                             if ($next_cnt == $current_number) {
//                                 $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount = $value->round_off;
//                                 if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
//                                     $vat_amt = ($total_amt * ($value['purchase_advice']->vat_percentage / 100));
//                                 }
//                                 $total_amt = $total_amt + $vat_amt;
//                             } else {
//                                 $total_amt = $tot_amt;
//                             }
//                         }
//                         echo number_format($tot_amt, 2, '.', '');
                        ?>
                        {{ round($amount,2) }}
            </td>
            <td style="height:16px;">
                <?php if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                        echo "[" . $value['purchase_advice']->vehicle_number . "]";
                ?>
                    {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:16px;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
            <?php }
            ?>
            <?php                             
                if(isset($value->vat_percentage) && !empty($value->vat_percentage)){
                    $total_gst_amount = ((float)$total_amount * (float)$value->vat_percentage) / 100;
                    if((isset($value->freight) && $value->freight != '')){
                        $freight_vat = $value->freight * $value->vat_percentage / 100;
                    }
                    if((isset($value->discount) && $value->discount != '')){
                        $discount_vat = $value->discount * $value->vat_percentage / 100;
                    }
                }
                $grand_total = $total_gst_amount + $total_amount + (isset($value->freight)?$value->freight:0) + (isset($value->discount)?$value->discount:0) + $freight_vat + $discount_vat ;
                $roundoff = round($grand_total,0) - $grand_total;
            ?>
   
        <tr>
            <td style="height:16px;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:16px;text-align: left;">{{$VchNo}}</td>            
            <td style="height:16px;"></td><td></td>
            <td style="height:16px;">Discount</td>
            <td style="height:16px;"></td><td></td><td></td><td></td><td></td><td></td>
            <td style="height:16px;text-align: left;">{{ isset($value->discount) ? $value->discount : '0.00' }}</td>
            <td style="height:16px;">
                <?php
                 if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                            echo "[" . $value['purchase_advice']->vehicle_number . "]";                  ?>
                {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:16px;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
        </tr> 
        <tr> 
            <td style="height:16px;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:16px;text-align: left;">{{$VchNo}}</td>           
            <td style="height:16px;"></td><td></td>
            <td style="height:16px;">Loading</td>
            <td style="height:16px;"></td><td></td><td></td><td></td><td></td><td></td>
            <td style="height:16px;text-align: left;">{{ isset($value->loading_charge) ? $value->loading_charge : '0.00' }}</td>
            <td style="height:16px;">
                <?php
                 if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                            echo "[" . $value['purchase_advice']->vehicle_number . "]";                  ?>
                {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:16px;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
        </tr> 
        <tr>  
            <td style="height:16px;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:16px;text-align: left;">{{$VchNo}}</td>
            <td style="height:16px;"></td><td></td>
            <td style="height:16px;">Freight</td>
            <td style="height:16px;"></td><td></td><td></td><td></td><td></td><td></td>
            <td style="height:16px;text-align: left;">{{isset($value->freight) ? $value->freight : '0.00'}}</td>
            <td style="height:16px;">
                <?php
                 if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                            echo "[" . $value['purchase_advice']->vehicle_number . "]";                  ?>
                {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:16px;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
        </tr>
        <tr>    
            <td style="height:16px;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:16px;text-align: left;">{{$VchNo}}</td>
            <td style="height:16px;"></td><td></td>
            <td style="height:16px;">Tax</td>
            <td style="height:16px;"></td><td></td><td></td><td></td><td></td><td></td>
            <td style="height:16px;text-align: left;">
                <!-- <?php
                        if($value->purchase_advice->vat_percentage != ""){
                            $discount =0;
                            $loading_charge =0;
                            $freight =0;
                            $percent_overhead_total=0;
                            if(isset($value->discount)){
                                $discount = $value->discount;
                            }
                            if(isset($value->loading_charge)){
                                $loading_charge = $value->loading_charge;
                            }
                            if(isset($value->freight)){
                                $freight = $value->freight;
                            }
                            
                            if(isset($value->vat_percentage)){
                                $overhead_total =  $loading_charge +$freight+$discount;
                                
                                $percent_overhead_total = ($overhead_total * $value->vat_percentage)/100;
                                $grand_vat_amt = $grand_vat_amt +$percent_overhead_total;
                            }
                               
                ?>
                {{ (!empty($grand_vat_amt) ) ? number_format($grand_vat_amt, 2, '.', '') : '0.00' }}
                
                        <?php } 
                        else {
                            echo "0.00";
                        }
                ?> -->
                <?php
                    $grand_vat_amt = $total_gst_amount + $freight_vat + $discount_vat ;
                ?>
                {{ round($grand_vat_amt,2) }}
            
            </td>
            <td style="height:16px;">
                <?php
                 if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                            echo "[" . $value['purchase_advice']->vehicle_number . "]";                  ?>
                {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:16px;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
           
        </tr>
         
         <tr>    
            <td style="height:16px;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:16px;text-align: left;">{{$VchNo}}</td>
            <td style="height:16px;"></td><td></td>
            <td style="height:16px;">Round Off</td>
            <td style="height:16px;"></td><td></td><td></td><td></td><td></td><td></td>
            <td style="height:16px;text-align: left;">{{ round($roundoff,2) }}</td>
            <td style="height:16px;">
                <?php
                 if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                            echo "[" . $value['purchase_advice']->vehicle_number . "]";                  ?>
                {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:16px;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
        </tr>
                    
        <tr style="border:2px solid black">    
            <td style="height:18px;border:2px solid #4fe24f;">{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
            <td style="height:18px;border:2px solid #4fe24f;">{{$VchNo}}</td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"> <b>Total</b></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"></td>
            <td style="height:18px;border:2px solid #4fe24f;"><b>{{  round($grand_total, 0) }}</b></td>
            <td style="height:18px;border:2px solid #4fe24f;">
                <?php
                 if ((isset($value['purchase_advice']->vehicle_number)) && ($value['purchase_advice']->vehicle_number != ""))
                            echo "[" . $value['purchase_advice']->vehicle_number . "]";                  ?>
                {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
            </td>
            <td style="height:18px;border:2px solid #4fe24f;">{{isset($value->serial_number)?$value->serial_number:''}}</td>
        </tr>
        <?php
            $VchNo++;
        }
        // dd('end');
        ?>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
          <?php /* ?>
        
        <tr>
            <td class="heading1">Vch No</td>
            <td class="heading1">Ref NUM</td>
            <td class="heading1">Vch Type</td>
            <td class="heading1">Date</td>
            <td class="heading1">Code</td>
            <td class="heading1">Name</td>
            <td class="heading1">Address1</td>
            <td class="heading1">Address2</td>
            <td class="heading1">State</td>
            <td class="heading1">Pin Code</td>
            <td class="heading1">Tin No</td>
            <td class="heading1">Item Name</td>
            <td class="heading1">Godown</td>
            <td class="heading1">Pcs</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Qty</td>
            <td class="heading1">Rate</td>
            <td class="heading1">Amt</td>
            <td class="heading1">Discount</td>
            <td class="heading1">Loading</td>
            <td class="heading1">Freight</td>
            <td class="heading1">Tax Type</td>
            <td class="heading1">Tax Rate</td>
            <td class="heading1">Tax</td>
            <td class="heading1">Round Off</td>
            <td class="heading1">Grand total</td>
            <td class="heading1">Narration</td>
        </tr>
        <?php
        $i = 1;
        $j = 1;
        foreach ($purchase_orders as $key => $value) {
            $next_cnt = count((array)$value['all_purchase_products']);
            $current_number = 1;
            $grand_vat_amt = 0;
            foreach ($value['all_purchase_products'] as $key1 => $value1) {
                $order_quantity = 0;
                $value_cnt = "";
                $vacant = " ";
                ?>
                <tr>
                    <?php
                    $value_cnt = "";
                    if ($current_number == 1) {
                        $value_cnt = $j;
                    } else {
                        $value_cnt = $j;
                    }
                    ?>
                    @if($current_number == 1)
                    <td>{{$value_cnt}}</td>
                    @else
                    <td>{{$value_cnt}}</td>
                    @endif

                    <td>{{isset($value->serial_number)?$value->serial_number:''}}</td>
                    <td>Purchase</td>
                    <td><?= date("m-d-Y", strtotime($value->updated_at)) ?></td>
                    <td></td>


                    <td>{{ ($value['supplier']->tally_name != "") ? $value['supplier']->tally_name : 'Advance Sales' }}</td>
                    <td>{{ isset($value['supplier']->address1) ? $value['supplier']->address1 : '' }}</td>
                    <td>{{ isset($value['supplier']->address2) ? $value['supplier']->address2 : '' }}</td>
                    <td>{{ isset($value['supplier']->states) ? $value->supplier->states->state_name : '' }}</td>
                    <td>{{ isset($value['supplier']->zip) ? $value['supplier']->zip : '' }}</td>
                    <td>{{ isset($value['supplier']->vat_tin_number) ? $value['supplier']->vat_tin_number : '' }}</td>
                    <td>{{ isset($value1['purchase_product_details']->alias_name) ? $value1['purchase_product_details']->alias_name : '' }}</td>
                    <td></td>
                    <td>{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '' }}</td>
                    <td>{{ isset($value1->unit->unit_name) ? $value1->unit->unit_name : '' }}</td>
                    <td>
                        <?php
                        if ($value1->unit_id == 1) {
                            $order_quantity = $order_quantity + $value1->quantity;
                        }
                        if ($value1->unit_id == 2) {
                            $order_quantity = $order_quantity + ($value1->quantity * $value1['purchase_product_details']->weight);
                        }
                        if ($value1->unit_id == 3) {
                            $order_quantity = $order_quantity + (($value1->quantity / $value1['purchase_product_details']->standard_length ) * $value1['purchase_product_details']->weight);
                        }
                        ?>
                        <?= round($value1->quantity, 2) ?>
                    </td>
                    <td>{{ isset($value1->price) ? $value1->price : '' }}</td>
                    <td>
                        <?php
                        // Calculation Updated by 157 on 03-09-2015
                        $total_amt = "";

                        if (isset($value1->quantity) && !empty($value1->quantity) && $value1->quantity != 0) {

                            $vat_amt = 0;
                            $tot_amt = $value1->price * $value1->quantity;

                            if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
                                $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['purchase_advice']->vat_percentage / 100));
                            }

                            if ($next_cnt == $current_number) {

                                $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount = $value->round_off;
                                if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
                                    $vat_amt = ($total_amt * ($value['purchase_advice']->vat_percentage / 100));
                                }
                                $total_amt = $vat_amt + $total_amt;
                            } else {
                                $total_amt = $tot_amt;
                            }
                        } else {
                            $vat_amt = 0;
//                            $total_amt = $total_amt * $value1->actual_pieces * $value1->order_product_details->weight;
                            $tot_amt = $value1->price * $value1->actual_pieces * $value1->order_product_details->weight;
                            if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
                                $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['purchase_advice']->vat_percentage / 100));
                            }
                            if ($next_cnt == $current_number) {
                                $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount = $value->round_off;
                                if (isset($value['purchase_advice']->vat_percentage) && $value['purchase_advice']->vat_percentage !== "") {
                                    $vat_amt = ($total_amt * ($value['purchase_advice']->vat_percentage / 100));
                                }
                                $total_amt = $total_amt + $vat_amt;
                            } else {
                                $total_amt = $tot_amt;
                            }
                        }
                        echo number_format($tot_amt, 2, '.', '');
                        ?>
                    </td>

                    @if($next_cnt == $current_number)
                    <td>{{ isset($value->discount) ? $value->discount : '' }}</td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{ isset($value->loading_charge) ? $value->loading_charge : '' }}</td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{isset($value->freight) ? $value->freight : ''}}</td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{ ($value->purchase_advice->vat_percentage != "") ? "VAT" : "All inclusive" }}
                        <?php
//                        if ($value->purchase_advice->vat_percentage !== "")
//                            echo "VAT";
//                        else
//                            echo "All inclusive";
                        ?>
                    </td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{ ($value->purchase_advice->vat_percentage != "") ? $value->purchase_advice->vat_percentage . "%" : '' }}
                        <?php
//                        if ($value->purchase_advice->vat_percentage !== "")
//                            echo $value->purchase_advice->vat_percentage . "%";
                        ?>
                    </td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{ ($value->purchase_advice->vat_percentage != "") ? number_format($grand_vat_amt, 2, '.', '') : '' }}
                        <?php
//                        if ($value->purchase_advice->vat_percentage !== "")
//                            echo number_format($vat_amt, 2, '.', '');
                        ?>
                    </td>
                    @else
                    <td></td>
                    @endif

                    <td>{{ isset($value->round_off) ? $value->round_off : '' }}</td>
                    @if($next_cnt == $current_number)
                    <td>
                        {{ isset($total_amt) ? number_format($total_amt, 2, '.', '') : '' }}
                    </td>
                    @else
                    <td></td>
                    @endif
                    <td><?= "[" . $value['purchase_advice']->vehicle_number . "][" . $value->remark . "]" ?></td>
                </tr>
                <?php
                $current_number++;
            }
            if ($next_cnt != 0) {
                $j++;
            }
            $i++;
        }
        ?> 
                <?php */ ?>
    </table>
</html>