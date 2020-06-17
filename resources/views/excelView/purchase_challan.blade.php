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
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>
            <!--<td class="heading1">Order By</td>-->
            <th style="height:20px;font-size:16px;color:#000080;">Order Time/Date</th>
        </tr>
        <?php $counter = 1; ?>
        @foreach ($order_objects as $order)
        <tr>
            <td style="height:16px;">{{$counter}}</td>
            <td style="height:16px;">
                {{$order['supplier']->owner_name}} 
            </td>

            <td style="height:16px;">
                {{$order['supplier']->tally_name}} 
            </td>

            <td style="height:16px;">{{$order['supplier']->contact_person}}</td>
            <td style="height:16px;">{{$order['supplier']->phone_number1}}</td>
            @if($order['supplier']->credit_period != "" || $order['supplier']->credit_period>0)
            <td style="height:16px;">{{$order['supplier']->credit_period}}</td>
            @endif

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
            
            <td style="height:16px;">{{$order['all_purchase_products']->purchase_product_details->alias_name}}</td>
            <td style="height:16px;">{{$order['all_purchase_products'][0]->quantity}}</td>
            <td style="height:16px;">
                @foreach($units as $unit)
                {{($unit->id == $order['all_purchase_products'][0]->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td style="height:16px;">{{$order['all_purchase_products'][0]->price}}</td>
            <td style="height:16px;">{{$order->vat_percentage}}</td>
            <td style="height:16px;">{{$order['all_purchase_products'][0]->remarks}}</td>
            <?php $product = isset($order['all_purchase_products']) && isset($order['all_purchase_products'][0]) ? $order['all_purchase_products'][0]['all_purchase_products'] : ''; ?>
<!--            @if(isset($product) && $product!='' && $product->order_type =='purchase_advice')
            <td>{{$product->alias_name}}</td>
            <td>{{$product->quantity}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>{{$product->price}}</td>
            <td>{{($product->vat_percentage!='')?$product->vat_percentage:''}}</td>
            <td>{{$product->remarks}}</td>
            @endif-->
            <td style="height:16px;">{{date("F jS, Y", strtotime($order->expected_delivery_date)) }}</td>
            <td style="height:16px;">{{$order->remarks}}</td>
            
            <td style="height:16px;">{{$order->updated_at}}</td>
        </tr>

        <?php $count = 0; ?>
        @foreach($order['all_purchase_products'] as $product)
        @if($count!=0 && isset($product->order_type) &&  $product->order_type =='purchase_challan')
        <tr>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;">{{$product['purchase_product_details']->alias_name}}</td>
            <td style="height:16px;">{{$product->quantity}}</td>
            <td style="height:16px;">
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td style="height:16px;">{{$product->price}}</td>
            <td style="height:16px;">{{$order->vat_percentage}}</td>
            <td style="height:16px;">{{$product->remarks}}</td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
            <td style="height:16px;"></td>
        </tr>
        @endif
        <?php $count++; ?>
        @endforeach
        <?php $counter++; ?>
        @endforeach
    </table>
    <?php //exit; ?>
</html>
