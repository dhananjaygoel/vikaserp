<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">Sr No.</td>
            <td class="heading1">Tally Name</td>
            <td class="heading1">Serial Number</td>

            <td class="heading1">Product Name(Alias)</td>
            <td class="heading1">Actual Quantity</td>
            <td class="heading1">Actual Pieces</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Present Shipping</td>
            <td class="heading1">Rate</td>
            <td class="heading1">GST Percentage</td>
            <td class="heading1">Amount</td>

            <td class="heading1">Total Actual Quantity</td>
            <td class="heading1">Total Amount</td>
            <td class="heading1">Discount</td>
            <td class="heading1">Discount GST Percentage</td>
            <td class="heading1">Total Freight Charges</td>
            <td class="heading1">Freight</td>
            <td class="heading1">Freight GST Percentage</td>
            <td class="heading1">Total Freight Charges</td>
            <td class="heading1">Loading</td>
            <td class="heading1">Loading GST Percentage</td>
            <td class="heading1">Total Loading Charges</td>
            <td class="heading1">Total</td>
            <td class="heading1">Loaded By</td>
            <td class="heading1">Labour</td>
            <td class="heading1">Round Off</td>
            <td class="heading1">Grand Total</td>
            <td class="heading1">Vehicle Number</td>
            <td class="heading1">Driver Contact</td>
            <td class="heading1">Order By</td>
            <td class="heading1">Order Time/Date</td>
            <td class="heading1">Delivery Challan By</td>
            <td class="heading1">Delivery Challan Time/Date</td>
            <td class="heading1">Remark</td>

        </tr>
        <?php $counter = 1; ?>
        @foreach ($delivery_challan_objects as $allorder)
        <tr>
            <td>{{$counter}}</td>
            <td>
                @if($allorder->customer->tally_name != "" && $allorder->customer->owner_name != "")
                {{ $allorder->customer->owner_name }}-{{$allorder->customer->tally_name}}
                @else 
                {{ $allorder->customer->owner_name }}
                @endif
            </td>
            <td>
                {{($allorder->serial_number != '') ? $allorder->serial_number :  $allorder->delivery_order->serial_no}}
            </td>

            <?php
            $total_amount = 0;

            $product = isset($allorder['all_order_products']) && isset($allorder['all_order_products'][0]) ? $allorder['all_order_products'][0] : '';
            ?>
            @foreach ($allorder['all_order_products'] as $key => $product)
            @if ($product->order_type == 'delivery_challan')
            <?php
            $amount = $product->actual_quantity * $product->price;
            $amount = $amount + (($amount * (($product->vat_percentage != '') ? $product->vat_percentage : 0)) / 100);
            $total_amount = round($amount + $total_amount, 2);
            ?>
            @endif
            @endforeach
            @if(isset($product) && $product!='' && $product->order_type =='delivery_challan')
            <?php $id_stored = $product->id; ?>
            <td>{{isset($product->order_product_details->alias_name) ? $product->order_product_details->alias_name:''}}<</td>
            <td>{{isset($product->quantity) ? $product->quantity:''}}</td>
            <td>{{isset($product->actual_pieces) ? $product->actual_pieces:''}} </td>
            <td>{{isset($product->unit->unit_name) ? $product->unit->unit_name:''}}</td>
            <td>{{isset($product->present_shipping) ? $product->present_shipping:''}}</td>
            <td>{{isset($product->price) ? $product->price:''}}</td>
            <td>{{(isset($product->vat_percentage) && $product->vat_percentage!='')?$product->vat_percentage:''}}</td>
            <td><?php
                $amount = $product->actual_quantity * $product->price;
                $amount = $amount + (($amount * (($product->vat_percentage != '') ? $product->vat_percentage : 0)) / 100);
                ?>
                {{round($amount, 2)}}</td>
            @endif

            <td>{{isset($allorder->all_order_products) ? $allorder->all_order_products->sum('actual_quantity'):0}}</td>
            <td>{{$total_amount}}</td>
            <td>{{isset($allorder->discount) ?$allorder->discount :''}}</td>
            <td>{{ isset($allorder->discount_vat_percentage)? $allorder->discount_vat_percentage:''}}</td>
            <?php
            $total_discount_charges = $allorder->discount + (($allorder->discount * $allorder->discount_vat_percentage) / 100);
            $total_amount = $total_amount - $total_discount_charges;
            ?>
            <td>{{$total_discount_charges}}</td>
            <td>{{isset($allorder->freight) ?$allorder->freight : ''}}</td>
            <td>{{isset($allorder->freight_vat_percentage) ?$allorder->freight_vat_percentage:''}}</td>
            <?php
            $total_freight_charges = $allorder->freight + (($allorder->freight * $allorder->freight_vat_percentage) / 100);
            $total_amount = $total_amount + $total_freight_charges;
            ?>
            <td>{{$total_freight_charges}}</td>
            <td>{{isset($allorder->loading_charge) ? $allorder->loading_charge :''}}</td>
            <td>{{isset($allorder->loading_vat_percentage) ? $allorder->loading_vat_percentage:''}}</td>
            <?php
            $total_loading_charges = $allorder->loading_charge + (($allorder->loading_charge * $allorder->loading_vat_percentage) / 100);
            $total_amount = $total_amount + $total_loading_charges;
            ?>
            <td>{{$total_loading_charges}}</td>
            <td>{{$total_amount}}</td>
            <td>{{isset($allorder->loaded_by) ?$allorder->loaded_by:'' }}</td>
            <td>{{isset($allorder->labours) ? $allorder->labours:''}}</td>
            <td>{{isset($allorder->round_off) ? $allorder->round_off:''}}</td>
            <td>{{isset($allorder->grand_price) ? $allorder->grand_price:''}}</td>
            <td>{{isset($allorder->delivery_order->vehicle_number) ? $allorder->delivery_order->vehicle_number:''}}</td>
            <td>{{isset($allorder->delivery_order->driver_contact_no) ? $allorder->delivery_order->driver_contact_no:''}}</td>
            <td>{{isset($allorder->order_details->createdby->first_name) ? $allorder->order_details->createdby->first_name." ".$allorder->order_details->createdby->last_name:''}}</td>
            <td>{{isset($allorder->order_details->updated_at) ? $allorder->order_details->updated_at:''}}</td>
            <td>{{isset($allorder->delivery_order->user->first_name) ? $allorder->delivery_order->user->first_name." ".$allorder->delivery_order->user->last_name:''}}</td>
            <td>{{isset($allorder->delivery_order->updated_at) ? $allorder->delivery_order->updated_at:''}}</td>

            <td>{{isset($allorder->remarks) ? $allorder->remarks:''}}</td>
        </tr>

        <?php $count = 0; ?>
        @foreach($allorder['all_order_products'] as $key => $product)
        @if(isset($id_stored))
        @if($id_stored != $product->id  && $count!= 0 && ($product->order_type) &&  $product->order_type =='delivery_challan')

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>{{isset($product->order_product_details->alias_name) ? $product->order_product_details->alias_name:''}}<</td>
            <td>{{isset($product->quantity) ? $product->quantity:''}}</td>
            <td>{{isset($product->actual_pieces) ? $product->actual_pieces:''}} </td>
            <td>{{isset($product->unit->unit_name) ? $product->unit->unit_name:''}}</td>
            <td>{{isset($product->present_shipping) ? $product->present_shipping:''}}</td>
            <td>{{isset($product->price) ? $product->price:''}}</td>
            <td>{{(isset($product->vat_percentage) && $product->vat_percentage!='')?$product->vat_percentage:''}}</td>
            <td><?php
                $amount = $product->actual_quantity * $product->price;
                $amount = $amount + (($amount * (($product->vat_percentage != '') ? $product->vat_percentage : 0)) / 100);
                ?>
                {{round($amount, 2)}}
            </td>
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

        @endif
        @endif
        <?php $count++; ?>
        @endforeach
        <?php $counter++; ?>
        @endforeach
    </table>
    <?php //exit;   ?>
</html>
