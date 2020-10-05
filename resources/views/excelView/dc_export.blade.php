<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Sr No.</th>
            <th style="height:20px;font-size:16px;color:#000080;">Tally Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Serial Number</th>

            <th style="height:20px;font-size:16px;color:#000080;">Product Name(Alias)</th>
            <th style="height:20px;font-size:16px;color:#000080;">Actual Quantity</th>
            <th style="height:20px;font-size:16px;color:#000080;">Actual Pieces</th>
            <th style="height:20px;font-size:16px;color:#000080;">Unit</th>
            <th style="height:20px;font-size:16px;color:#000080;">Present Shipping</th>
            <th style="height:20px;font-size:16px;color:#000080;">Rate</th>
            <th style="height:20px;font-size:16px;color:#000080;">GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Amount</th>
            <th style="height:20px;font-size:16px;color:#000080;">Product Remark</th>

            <th style="height:20px;font-size:16px;color:#000080;">Total Actual Quantity</th>
            <th style="height:20px;font-size:16px;color:#000080;">Total Amount</th>
            <th style="height:20px;font-size:16px;color:#000080;">Discount</th>
            <th style="height:20px;font-size:16px;color:#000080;">Discount GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Total Discount Charges</th>
            <th style="height:20px;font-size:16px;color:#000080;">Freight</th>
            <th style="height:20px;font-size:16px;color:#000080;">Freight GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Total Freight Charges</th>
            <th style="height:20px;font-size:16px;color:#000080;">Loading</th>
            <th style="height:20px;font-size:16px;color:#000080;">Loading GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Total Loading Charges</th>
            <th style="height:20px;font-size:16px;color:#000080;">Total</th>
            <th style="height:20px;font-size:16px;color:#000080;">Loaded By</th>
            <th style="height:20px;font-size:16px;color:#000080;">Labour</th>
            <th style="height:20px;font-size:16px;color:#000080;">TCS Amount</th>
            <!-- <th style="height:20px;font-size:16px;color:#000080;">Round Off</th> -->
            <th style="height:20px;font-size:16px;color:#000080;">Grand Total</th>
            <th style="height:20px;font-size:16px;color:#000080;">Vehicle Number</th>
            <th style="height:20px;font-size:16px;color:#000080;">Driver Contact</th>
            <th style="height:20px;font-size:16px;color:#000080;">Order By</th>
            <th style="height:20px;font-size:16px;color:#000080;">Order Time/Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Challan By</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Challan Time/Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>

        </tr>
        <?php $counter = 1; ?>
        @foreach ($delivery_challan_objects as $allorder)
        <tr>
            <td style="height:16px;">{{$counter}}</td>
            @if($allorder->customer->tally_name != "" && $allorder->customer->owner_name != "")
            <td>{{ $allorder->customer->owner_name }}-{{$allorder->customer->tally_name}}</td>
            @else 
            <td>{{ $allorder->customer->owner_name }}</td>
            @endif
            
            <td>{{($allorder->serial_number != '') ? $allorder->serial_number :  $allorder->delivery_order->serial_no}}</td>
            

            <?php $total_amount = 0;
            $product = isset($allorder['all_order_products']) && isset($allorder['all_order_products'][0]) ? $allorder['all_order_products'][0]['order_product_details'] : ''; 
            
            $amount = $product->actual_quantity * $product->price;
            $amount = $amount + (($amount * (($product->vat_percentage != '') ? $product->vat_percentage : 0)) / 100);
            $total_amount = round($amount + $total_amount, 2);
            
            ?>
            <td style="height:16px;">{{isset($product->alias_name)?$product->alias_name:''}}</td>
            <!--<td>{{$allorder['all_order_products'][0]->quantity}}</td>-->
           <td style="height:16px;">{{(isset($allorder['all_order_products'][0]->quantity))?$allorder['all_order_products'][0]->quantity:''}}</td>
           <td style="height:16px;">{{(isset($allorder['all_order_products'][0]->actual_pieces))?$allorder['all_order_products'][0]->actual_pieces:''}}</td>
           <td style="height:16px;">{{(isset($allorder['all_order_products'][0]->unit) && isset($allorder['all_order_products'][0]->unit->unit_name))?$allorder['all_order_products'][0]->unit->unit_name:''}}</td>
           <td style="height:16px;">{{(isset($allorder['all_order_products'][0]->present_shipping))?$allorder['all_order_products'][0]->present_shipping:''}}</td>
           <td style="height:16px;">{{(isset($allorder['all_order_products'][0]->price))?$allorder['all_order_products'][0]->price:''}}</td>
           <td style="height:16px;">{{(isset($allorder->vat_percentage))?$allorder->vat_percentage:''}}</td>
           <?php
                $amount = $product->actual_quantity * $product->price;
                $amount = $amount + (($amount * (($product->vat_percentage != '') ? $product->vat_percentage : 0)) / 100);
            ?>
            <td style="height:16px;">{{round($amount, 2)}}</td>
            <td style="height:16px;">{{(isset($allorder['all_order_products'][0]->remarks))?$allorder['all_order_products'][0]->remarks:''}}</td>

            <td style="height:16px;">{{isset($allorder->all_order_products) ? $allorder->all_order_products->sum('actual_quantity'):0}}</td>
            <td style="height:16px;">{{$total_amount}}</td>
            <td style="height:16px;">{{isset($allorder->discount) ?$allorder->discount :''}}</td>
            <td style="height:16px;">{{isset($allorder->discount_vat_percentage)? $allorder->discount_vat_percentage:''}}</td>
            <?php
            $total_discount_charges = (float)$allorder->discount + (((float)$allorder->discount * (float)$allorder->discount_vat_percentage) / 100);
            $total_amount = $total_amount - $total_discount_charges;
            ?>
            <td style="height:16px;">{{$total_discount_charges}}</td>
            <td style="height:16px;">{{isset($allorder->freight) ?$allorder->freight : ''}}</td>
            <td style="height:16px;">{{isset($allorder->freight_vat_percentage) ?$allorder->freight_vat_percentage:''}}</td>
            <?php
            $total_freight_charges = $allorder->freight + (($allorder->freight * $allorder->freight_vat_percentage) / 100);
            $total_amount = $total_amount + $total_freight_charges;
            ?>
            <td style="height:16px;">{{$total_freight_charges}}</td>
            <td style="height:16px;">{{isset($allorder->loading_charge) ? $allorder->loading_charge :''}}</td>
            <td style="height:16px;">{{isset($allorder->loading_vat_percentage) ? $allorder->loading_vat_percentage:''}}</td>
            <?php
            $total_loading_charges = $allorder->loading_charge + (($allorder->loading_charge * $allorder->loading_vat_percentage) / 100);
            $total_amount = $total_amount + $total_loading_charges;
            if($allorder->tcs_applicable == 1 && isset($allorder->vat_percentage) && !empty($allorder->vat_percentage)){
                $tcs_amount = $allorder->grand_price * $allorder->tcs_percentage / 100;
                $amount = $allorder->grand_price + round($tcs_amount,2);
            }else{
                $tcs_amount = 0;
                $amount = $allorder->grand_price;
            }
            ?>
            <td style="height:16px;">{{$total_loading_charges}}</td>
            <td style="height:16px;">{{$total_amount}}</td>
            <td style="height:16px;">{{isset($allorder->loaded_by) ?$allorder->loaded_by:'' }}</td>
            <td style="height:16px;">{{isset($allorder->labours) ? $allorder->labours:''}}</td>
            <td style="height:16px;">{{ round($tcs_amount,2) }}</td>
            <!-- <td style="height:16px;">{{isset($allorder->round_off) ? $allorder->round_off:''}}</td> -->
            <td style="height:16px;">{{ round($amount,0) }}</td>
            <td style="height:16px;">{{isset($allorder->delivery_order->vehicle_number) ? $allorder->delivery_order->vehicle_number:''}}</td>
            <td style="height:16px;">{{isset($allorder->delivery_order->driver_contact_no) ? $allorder->delivery_order->driver_contact_no:''}}</td>
            <td style="height:16px;">{{isset($allorder->order_details->createdby->first_name) ? $allorder->order_details->createdby->first_name." ".$allorder->order_details->createdby->last_name:''}}</td>
            <td style="height:16px;">{{isset($allorder->order_details->updated_at) ? date('j F, Y h:i A',strtotime($allorder->order_details->updated_at)):''}}</td>
            <td style="height:16px;">{{isset($allorder->delivery_order->user->first_name) ? $allorder->delivery_order->user->first_name." ".$allorder->delivery_order->user->last_name:''}}</td>
            <td style="height:16px;">{{isset($allorder->updated_at) ? date('j F, Y h:i A',strtotime($allorder->updated_at)):''}}</td>
            <td style="height:16px;">{{isset($allorder->remarks) ? $allorder->remarks:''}}</td>

        </tr>
        <?php $count = 0; ?>
        @foreach($allorder['all_order_products'] as $product)
        
        <?php if($count!=0 && isset($product->order_type) && $product->order_type =='delivery_challan') {?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="height:16px;">{{isset($product['order_product_details']->alias_name)?$product['order_product_details']->alias_name:''}}</td>
            <td style="height:16px;">{{isset($product->quantity)?$product->quantity:'0'}}</td>
            <td style="height:16px;">{{isset($product->actual_pieces)?$product->actual_pieces:'0'}}</td>
            <td style="height:16px;">{{(isset($product->unit) && $product->unit->unit_name!='')?$product->unit->unit_name:''}}</td>
            <td style="height:16px;">{{isset($product->present_shipping)?$product->present_shipping:'0'}}</td>
            <td style="height:16px;">{{(isset($product->price))?$product->price:''}}</td>
            <!--<td>{{$product->price}}</td>-->
            <td style="height:16px;">{{($allorder->vat_percentage!='')?$allorder->vat_percentage:''}}</td>
            <?php
                $amount = $product->actual_quantity * $product->price;
                $amount = $amount + (($amount * (($product->vat_percentage != '') ? $product->vat_percentage : 0)) / 100);
            ?>
            <td style="height:16px;">{{round($amount, 2)}}</td>
            <td style="height:16px;">{{isset($product->remarks)?$product->remarks:''}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            
        </tr>
        <?php } ?>
        <?php $count++; ?>
        @endforeach
        <?php $counter++; ?>
        @endforeach
    </table>
    <?php //exit; ?>
</html>
