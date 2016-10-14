<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}

    <table >
<!--        <tr>
            <td colspan="3" class="heading1">Vikash Associates ..(<?= date('Y') ?>)</td>
        </tr>
        <tr>
            <td colspan="3" class="border1">411014</td>
        </tr>
        <tr>
            <td colspan="3" class="heading1">Daybook</td>
        </tr>
        <tr>
            <td colspan="3">For: <?= date('d-m-Y') ?></td>
        </tr>-->


        <tr>
            <td class="heading1">Date</td>
            
            <td class="heading1">Ref NUM</td>
            <td class="heading1">Vch Type</td>

<!--            <td class="heading1">Code</td>-->
            <td class="heading1">Name</td>
            <!--<td class="heading1">Address1</td>-->
            <!--<td class="heading1">Address2</td>-->
            <!--<td class="heading1">State</td>-->
            <!--<td class="heading1">Pin Code</td>-->
            <!--<td class="heading1">Tin No</td>-->
            <td class="heading1">Account</td>
            <td class="heading1">Item Name</td>
            <td class="heading1">Product Name</td>
            <!--<td class="heading1">Godown</td>-->
            <td class="heading1">Pcs</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Qty</td>
            <td class="heading1">Rate</td>
            <td class="heading1">Amt</td>
<!--            <td class="heading1">Discount</td>
            <td class="heading1">Loading</td>
            <td class="heading1">Freight</td>-->
            <!--<td class="heading1">Tax Type</td>-->
            <!--<td class="heading1">Tax Rate</td>-->
            <!--<td class="heading1">Tax</td>-->
<!--            <td class="heading1">Round Off</td>
            <td class="heading1">Grand total</td>-->
            <td></td><td></td>
            <td class="heading1">Vehicle Number/Remark</td>
            <!--<td class="heading1">Remark</td>-->            
            <td class="heading1">Vch No</td>
        </tr>
        <?php
        $VchNo = 1;
        foreach ($allorders as $key => $value) {
            $next_cnt = count($value['delivery_challan_products']);
            $grand_vat_amt = 0;
            $current_number = 1;
            ?>



            <?php
            foreach ($value['delivery_challan_products'] as $key1 => $value1) {
                $order_quantity = 0;
                ?>
                <tr>
                    <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                    <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                    <!--<td>-->
                    <!--{{ isset($value->serial_number) ? $value->serial_number :'' }}-->
                    <?php
                    $type_of_bill = substr($value->serial_number, -1);
                    ?>
                    <!--</td>-->
                    <td>Sales</td>

        <!--<td></td>-->
                    <td>{{ ($value['customer']->tally_name != "") ? $value['customer']->tally_name : "Advance Sales" }}</td>
                            <!--<td>{{ isset($value['customer']->address1) ? $value['customer']->address1 : '' }}</td>-->
        <!--                    <td>{{ isset($value['customer']->address2) ? $value['customer']->address2 : '' }}</td>-->
                            <!--<td>{{ isset($value['customer']->states) ? $value->customer->states->state_name : '' }}</td>-->
                            <!--<td>{{ isset($value['customer']->zip) ? $value['customer']->zip : '' }}</td>-->
                            <!--<td>{{ isset($value['customer']->vat_tin_number) ? $value['customer']->vat_tin_number : '' }}</td>-->  
                    <td>Sales Account</td>
                    <td>{{ isset($value1['order_product_details']->alias_name) ? $value1['order_product_details']->alias_name : '' }}</td>
                    <td>{{ isset($value1['order_product_details']->product_category->product_category_name) ? $value1['order_product_details']->product_category->product_category_name : '' }}</td>
                            <!--<td></td>-->
                    @if($value1->actual_quantity != 0 )
                    <td>{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '' }}</td>
                    <td>Kg</td>
                    <td>
                        <?php
                        if ($value1->unit_id == 1) {
                            $order_quantity = $order_quantity + $value1->quantity;
                        }
                        if ($value1->unit_id == 2) {
                            if (isset($value1['order_product_details']->weight)) {
                                $order_quantity = $order_quantity + ($value1->quantity * $value1['order_product_details']->weight);
                            } else {
                                $order_quantity = $order_quantity + ($value1->quantity);
                            }
                        }
                        if ($value1->unit_id == 3) {
                            $order_quantity = $order_quantity + (($value1->quantity / $value1['order_product_details']->standard_length ) * $value1['order_product_details']->weight);
                        }
                        ?>
                        <?= round($value1->actual_quantity, 2) ?>
                    </td>
                    <td>{{ isset($value1->price) ? $value1->price : '0' }}</td>

                    <?php $value1['order_product_details']['product_category']['id'] ?>
                    <?php
                    if (!empty($value['customer']['customerproduct'])) {
                        foreach ($value['customer']['customerproduct'] as $customer_difference) {
                            if ($customer_difference['product_category_id'] == $value1['order_product_details']['product_category']['id']) {
                                $customer_diff = $customer_difference->difference_amount;
                                $customer_diff1 = $customer_difference->difference;
                            }
                        }
                    }
                    ?>
                    <td>
                        <?php
//                        Commented by Amit Gupta on 03-09-2015
//
//                        $total_amt = ($value1->price + $value1['order_product_details']['difference']);
//                        $total_amt = $total_amt + $value->delivery_order->location_difference;
//                        if (isset($customer_diff)) {
//                            $total_amt = $total_amt + $customer_diff;
//                        }
                        $total_amt = "";
                        if (isset($value1->quantity) && !empty($value1->quantity) && $value1->quantity != 0) {
                            $vat_amt = 0;
                            $tot_amt = $value1->price * $value1->quantity;

                            if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
                                $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['delivery_order']->vat_percentage / 100));
                            }

                            if ($next_cnt == $current_number) {
                                $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount + $value->round_off;
                                if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
                                    $vat_amt = ($total_amt * ($value['delivery_order']->vat_percentage / 100));
                                }
                                $total_amt = $vat_amt + $total_amt;
                            } else {
                                $total_amt = $tot_amt;
                            }
                        } else {
                            $vat_amt = 0;
                            $tot_amt = $value1->price * $value1->actual_pieces * $value1->order_product_details->weight;

                            if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
                                $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['delivery_order']->vat_percentage / 100));
                            }

                            if ($next_cnt == $current_number) {
                                $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount + $value->round_off;
                                $vat_amt = ($total_amt * ($value['delivery_order']->vat_percentage / 100));
                                $total_amt = $vat_amt + $total_amt;
                            } else {
                                $total_amt = $tot_amt;
                            }
                        }
                        echo number_format($tot_amt, 2, '.', '');
//                        if (isset($customer_diff)) {
//                            echo (($value1->price + $value1['order_product_details']['difference'] + $customer_diff + $value['delivery_location']['difference']) * $value1->quantity);
//                        } else {
//                            echo (($value1->price + $value1['order_product_details']['difference'] + $value['delivery_location']['difference']) * $value1->quantity);
//                        }
                        ?>
                    </td>
                    @else
                    <td>{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '0' }}</td>
                    <td>Pieces</td>
                    @if((isset($value1->actual_quantity)) && ($value1->actual_quantity!=""))
                    <td><?= round($value1->actual_quantity, 2) ?></td>
                    @else
                    <td></td>
                    @endif
                    <td>{{ isset($value1->price) ? $value1->price : '0' }}</td>
                    <td><?php
                        $total_amt_for_pices = ($value1['order_product_details']['weight'] * $value1->actual_pieces * $value1->price);
                        echo number_format(0, 2, '.', '');
                        ?></td>      
                    @endif 
                    <!--<td>{{  (($type_of_bill == "P") ? "VAT" : "All inclusive")  }}</td>-->
        <!--                    <td><?php
//                    echo  substr($value->serial_number, -1);
                    ?></td>-->


        <!--                    <td>
                    <?php
//                        if ($value->vat_percentage !== "" & $type_of_bill == "P")
//                            echo $value->vat_percentage . "%";
//                        else
//                            echo "0%"
                    ?>
               </td>-->
                    <td></td><td></td>
                    <td>
                    <?php
                    if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                        echo "[" . $value['delivery_order']->vehicle_number . "]";  
                    ?>
                    {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                </td> 
                    <td>{{$VchNo}}</td>
                    
                </tr>
    <?php } ?>
            <tr>
                <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                <td></td><td></td>
                <td>Discount</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
                <td>{{(isset($value->discount)&& !empty($value->discount))? $value->discount :'0.00'}}</td>
                <td></td><td></td>
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
                <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                <td></td><td></td>
                <td>Loading</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
                <td>{{isset($value->loading_charge) ? $value->loading_charge :'0.00'}}</td>
                <td></td><td></td>
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
                <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                <td></td><td></td>
                <td>Freight</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
                <td>{{isset($value->freight) ? $value->freight :'0.00'}}</td>
                <td></td><td></td>
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
                <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                <td></td><td></td>
                <td>Tax</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
                <td>   
                    <?php
                    if ($type_of_bill == "P") {
                        $discount = 0;
                        $loading_charge = 0;
                        $freight = 0;
                        $percent_overhead_total = 0;
                        if (isset($value->discount)) {
                            $discount = $value->discount;
                        }
                        if (isset($value->loading_charge)) {
                            $loading_charge = $value->loading_charge;
                        }
                        if (isset($value->freight)) {
                            $freight = $value->freight;
                        }

                        if (isset($value->vat_percentage)) {
                            $overhead_total = $loading_charge + $freight + $discount;

                            $percent_overhead_total = ($overhead_total * $value->vat_percentage) / 100;
                            $grand_vat_amt = $grand_vat_amt + $percent_overhead_total;
                        }
                        echo number_format($grand_vat_amt, 2, '.', '');
                    } else
                        echo "0.00";
                    ?>
                </td>
                <td></td><td></td>
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
                <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                <td></td><td></td>
                <td>Round Off</td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
                <td>{{  (isset($value->round_off) ? $value->round_off : '')  }}</td>
                <td></td><td></td>
                <td>
                    <?php
                    if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                        echo "[" . $value['delivery_order']->vehicle_number . "]";  
                    ?>
                    {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                </td> 
                <td>{{$VchNo}}</td>
            </tr>

            <tr style="border:2px solid black">    
                <td>{{ date("d/m/Y", strtotime($value->updated_at)) }}</td>
                <td>{{ isset($value->serial_number) ? $value->serial_number :'' }}</td>
                <td></td><td></td>
                <td> <b>Total</b></td>
                <td></td><td></td><td></td><td></td><td></td><td></td>
                <td><b>{{  (isset($value->grand_price) ? number_format($value->grand_price, 2, '.', '') : '')  }}</b></td>
                <td></td><td></td>
                <td>
                    <?php
                    if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
                        echo "[" . $value['delivery_order']->vehicle_number . "]";  
                    ?>
                    {{ (isset($value->remarks)&& $value->remarks!='')? '/ '.$value->remarks : '' }}
                </td>                
                <td>{{$VchNo}}</td>
            </tr>


            <?php
            $VchNo++;
        }
        ?>












<!--        <tr>
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
    <td class="heading1">Product Name</td>
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
    <td class="heading1">Vehicle Number</td>
    <td class="heading1">Remark</td>
</tr>-->
        <?php
//        $i = 1;
//        $j = 1;
        /*
          foreach ($allorders as $key => $value) {
          $next_cnt = count($value['delivery_challan_products']);
          $current_number = 1;
          $grand_vat_amt = 0;
          $type_of_bill = 0;
          foreach ($value['delivery_challan_products'] as $key1 => $value1) {
          $order_quantity = 0;
          $value_cnt = "";
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
          <td>
          {{ isset($value->serial_number) ? $value->serial_number :'' }}
          <?php
          $type_of_bill = substr($value->serial_number, -1);
          ?>
          </td>
          <td>Sales</td>
          <td>{{ date("m-d-Y", strtotime($value->updated_at)) }}</td>
          <td></td>
          <td>{{ ($value['customer']->tally_name != "") ? $value['customer']->tally_name : "Advance Sales" }}</td>
          <td>{{ isset($value['customer']->address1) ? $value['customer']->address1 : '' }}</td>
          <!--                    <td>{{ isset($value['customer']->address2) ? $value['customer']->address2 : '' }}</td>-->
          <td>{{ isset($value['customer']->states) ? $value->customer->states->state_name : '' }}</td>
          <!--<td>{{ isset($value['customer']->zip) ? $value['customer']->zip : '' }}</td>-->
          <td>{{ isset($value['customer']->vat_tin_number) ? $value['customer']->vat_tin_number : '' }}</td>
          <td>{{ isset($value1['order_product_details']->alias_name) ? $value1['order_product_details']->alias_name : '' }}</td>
          <td>{{ isset($value1['order_product_details']->product_category->product_category_name) ? $value1['order_product_details']->product_category->product_category_name : '' }}</td>
          <td></td>

          @if($value1->actual_quantity != 0 )
          <td>{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '' }}</td>
          <td>Kg</td>
          <td>
          <?php
          if ($value1->unit_id == 1) {
          $order_quantity = $order_quantity + $value1->quantity;
          }
          if ($value1->unit_id == 2) {
          if(isset($value1['order_product_details']->weight)){
          $order_quantity = $order_quantity + ($value1->quantity * $value1['order_product_details']->weight);
          }
          else{
          $order_quantity = $order_quantity + ($value1->quantity);
          }
          }
          if ($value1->unit_id == 3) {
          $order_quantity = $order_quantity + (($value1->quantity / $value1['order_product_details']->standard_length ) * $value1['order_product_details']->weight);
          }
          ?>
          <?= round($value1->actual_quantity, 2) ?>
          </td>
          <td>{{ isset($value1->price) ? $value1->price : '' }}</td>
          <?php $value1['order_product_details']['product_category']['id'] ?>
          <?php
          if (!empty($value['customer']['customerproduct'])) {
          foreach ($value['customer']['customerproduct'] as $customer_difference) {
          if ($customer_difference['product_category_id'] == $value1['order_product_details']['product_category']['id']) {
          $customer_diff = $customer_difference->difference_amount;
          $customer_diff1 = $customer_difference->difference;
          }
          }
          }
          ?>
          <td>
          <?php
          //                        Commented by Amit Gupta on 03-09-2015
          //
          //                        $total_amt = ($value1->price + $value1['order_product_details']['difference']);
          //                        $total_amt = $total_amt + $value->delivery_order->location_difference;
          //                        if (isset($customer_diff)) {
          //                            $total_amt = $total_amt + $customer_diff;
          //                        }
          $total_amt = "";
          if (isset($value1->quantity) && !empty($value1->quantity) && $value1->quantity != 0) {
          $vat_amt = 0;
          $tot_amt = $value1->price * $value1->quantity;

          if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
          $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['delivery_order']->vat_percentage / 100));
          }

          if ($next_cnt == $current_number) {
          $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount + $value->round_off;
          if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
          $vat_amt = ($total_amt * ($value['delivery_order']->vat_percentage / 100));
          }
          $total_amt = $vat_amt + $total_amt;
          } else {
          $total_amt = $tot_amt;
          }
          } else {
          $vat_amt = 0;
          $tot_amt = $value1->price * $value1->actual_pieces * $value1->order_product_details->weight;

          if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
          $grand_vat_amt = $grand_vat_amt + ($tot_amt * ($value['delivery_order']->vat_percentage / 100));
          }

          if ($next_cnt == $current_number) {
          $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount + $value->round_off;
          $vat_amt = ($total_amt * ($value['delivery_order']->vat_percentage / 100));
          $total_amt = $vat_amt + $total_amt;
          } else {
          $total_amt = $tot_amt;
          }
          }
          echo number_format($tot_amt, 2, '.', '');
          //                        if (isset($customer_diff)) {
          //                            echo (($value1->price + $value1['order_product_details']['difference'] + $customer_diff + $value['delivery_location']['difference']) * $value1->quantity);
          //                        } else {
          //                            echo (($value1->price + $value1['order_product_details']['difference'] + $value['delivery_location']['difference']) * $value1->quantity);
          //                        }
          ?>
          </td>
          @else
          <td>{{ isset($value1->actual_pieces) ? $value1->actual_pieces : '' }}</td>
          <td>Pieces</td>
          @if((isset($value1->actual_quantity)) && ($value1->actual_quantity!=""))
          <td><?= round($value1->actual_quantity, 2) ?></td>
          @else
          <td></td>
          @endif
          <td>{{ isset($value1->price) ? $value1->price : '' }}</td>
          <td><?php ($value1['order_product_details']['weight'] * $value1->actual_pieces * $value1->price) ?></td>
          @endif

          <td>{{ ($next_cnt == $current_number) ? (isset($value->discount) ? $value->discount : '') : '' }}</td>

          <td>{{ ($next_cnt == $current_number) ? (isset($value->loading_charge) ? $value->loading_charge : '') : '' }}</td>

          <td>{{ ($next_cnt == $current_number) ? (isset($value->freight) ? $value->freight : '') : '' }}</td>

          <td>{{ ($next_cnt == $current_number) ? (($type_of_bill == "P") ? "VAT" : "All inclusive") : '' }}</td>
          <!--                    <td><?php
          echo  substr($value->serial_number, -1);
          ?></td>-->


          @if($next_cnt == $current_number)
          <td>
          <?php
          if ($value->vat_percentage !== "" & $type_of_bill == "P")
          echo $value->vat_percentage . "%";
          else
          echo "0%"
          ?>
          </td>
          @else
          <td></td>
          @endif

          @if($next_cnt == $current_number)
          <td>
          <?php
          if ($type_of_bill == "P")
          echo number_format($grand_vat_amt, 2, '.', '');
          else
          echo "0";
          ?>
          </td>
          @else
          <td></td>
          @endif

          <td>{{ ($next_cnt == $current_number) ? (isset($value->round_off) ? $value->round_off : '') : '' }}</td>

          <td>{{ ($next_cnt == $current_number) ? (isset($value->grand_price) ? number_format($value->grand_price, 2, '.', '') : '') : '' }}</td>
          <td>
          <?php
          if ((isset($value['delivery_order']->vehicle_number)) && ($value['delivery_order']->vehicle_number != ""))
          echo "[" . $value['delivery_order']->vehicle_number . "]";
          if ((isset($value->remark)) && ($value->remark != ""))
          echo "[" . $value->remark . "]";
          if ((isset($value['delivery_location']->area_name)) && ($value['delivery_location']->area_name != ""))
          echo "[" . $value['delivery_location']->area_name . "]";
          ?>
          </td>
          <td>{{ isset($value->remarks) ? $value->remarks : '' }}</td>
          </tr>
          <?php
          $current_number++;
          }
          $i++;
          if ($next_cnt != 0) {
          $j++;
          }
          } */
        ?>
    </table>
</html>