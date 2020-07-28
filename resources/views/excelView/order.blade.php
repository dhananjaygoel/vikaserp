<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Sr No.</th>
            <th style="height:20px;font-size:16px;color:#000080;">Warehouse/Supplier Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Tally Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Contact Person</th>
            <th style="height:20px;font-size:16px;color:#000080;">Mobile Number</th>
            <th style="height:20px;font-size:16px;color:#000080;">Credit Period(Days)</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Location</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Freight</th>
            <th style="height:20px;font-size:16px;color:#000080;">Product(Alias)</th>
            <th style="height:20px;font-size:16px;color:#000080;">Quantity</th>
            <th style="height:20px;font-size:16px;color:#000080;">Unit</th>
            <th style="height:20px;font-size:16px;color:#000080;">Price</th>
            <th style="height:20px;font-size:16px;color:#000080;">GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>
            <th style="height:20px;font-size:16px;color:#000080;">Expected Delivery Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Order By</th>
            <th style="height:20px;font-size:16px;color:#000080;">Order Time/Date</th>
        </tr>
        <?php $counter = 1; ?>
        @foreach ($order_objects as $order)
        <tr>
            <td style="height:16px;">{{$counter}}</td>
            <td style="height:16px;">
                @if(isset($order->order_source) && $order->order_source == 'warehouse')
                {{'yes'}}
                @elseif(isset($order->order_source) && $order->order_source == 'supplier')                                        
                @foreach($customers as $customer)
                @if($customer->id == $order->supplier_id)
                {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
                @endif
                @endforeach
                @endif
            </td>
            @foreach($customers as $customer)
            @if(isset($order->customer_id) && $customer->id == $order->customer_id)
            <td style="height:16px;">{{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}</td>
            <td style="height:16px;">{{$customer->contact_person}}</td>
            <td style="height:16px;">{{$customer->phone_number1}}</td>
            @if($customer->credit_period != "" || $customer->credit_period>0)
            <td style="height:16px;">{{$customer->credit_period}}</td>
            @endif
            @endif
            @endforeach
            @if($order->delivery_location_id !=0)
            @foreach($delivery_location as $location)
            @if($order->delivery_location_id == $location->id)
            <td style="height:16px;">{{$location->area_name}}</td>
            <td style="height:16px;">{{$order->location_difference}}</td>
            @endif
            @endforeach
            @else
            <td style="height:16px;">{{$order->other_location}}</td>
            <td style="height:16px;">{{$order->location_difference}}</td>
            @endif
            <?php $product = isset($order['all_order_products']) && isset($order['all_order_products'][0]) ? $order['all_order_products'][0]['order_product_details'] : ''; ?>
            <td style="height:16px;">{{isset($product->alias_name)?$product->alias_name:''}}</td>

           <td style="height:16px;">{{(isset($order['all_order_products'][0]->quantity))?$order['all_order_products'][0]->quantity:0}}</td>
           <td style="height:16px;">{{(isset($order['all_order_products'][0]->unit) && isset($order['all_order_products'][0]->unit->unit_name))?$order['all_order_products'][0]->unit->unit_name:''}}</td>
           <td style="height:16px;">{{(isset($order['all_order_products'][0]->price))?$order['all_order_products'][0]->price:0}}</td>
           <td style="height:16px;">{{(isset($order->vat_percentage))?$order->vat_percentage:''}}</td>
           <td style="height:16px;">{{(isset($order['all_order_products'][0]->remarks))?$order['all_order_products'][0]->remarks:''}}</td>
            
            <td style="height:16px;">{{date("j F, Y", strtotime($order->expected_delivery_date)) }}</td>
            <td style="height:16px;">{{isset($order->createdby->first_name) && isset($order->createdby->last_name)?$order->createdby->first_name." ".$order->createdby->last_name:''}}</td>
            <td style="height:16px;">{{isset($order->updated_at)? date('j F, Y h:i A', strtotime($order->updated_at)):''}}</td>
        </tr>
        <?php $count = 0; ?>
        @foreach($order['all_order_products'] as $product)
        
        <?php if($count!=0 && isset($product->order_type) && $product->order_type =='order') {?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="height:16px;">{{isset($product['order_product_details']->alias_name)?$product['order_product_details']->alias_name:''}}</td>
            <td style="height:16px;">{{isset($product->quantity)?$product->quantity:'0'}}</td>
            <td style="height:16px;">{{(isset($product->unit) && $product->unit->unit_name!='')?$product->unit->unit_name:''}}</td>
            <td style="height:16px;">{{(isset($product->price))?$product->price:0}}</td>
            
            <td style="height:16px;">{{($order->vat_percentage!='')?$order->vat_percentage:''}}</td>
            <td style="height:16px;">{{isset($product->remarks)?$product->remarks:''}}</td>
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
</html>
