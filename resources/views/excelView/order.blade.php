<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">Sr No.</td>
            <td class="heading1">Warehouse/Supplier Name</td>
            <td class="heading1">Tally Name</td>
            <td class="heading1">Contact Person</td>
            <td class="heading1">Mobile Number</td>
            <td class="heading1">Credit Period(Days)</td>
            <td class="heading1">Delivery Location</td>
            <td class="heading1">Delivery Location Difference</td>

            <td class="heading1">Product(Alias)</td>
            <td class="heading1">Qty</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Price</td>
            <td class="heading1">Vat Percentage</td>
            <td class="heading1">Remark</td>

            <td class="heading1">Expected Delivery Date</td>
            <td class="heading1">Remark</td>
            <td class="heading1">Order By</td>
            <td class="heading1">Order Time/Date</td>
        </tr>
        <?php $counter = 1; ?>
        @foreach ($order_objects as $order)
        <tr>
            <td>{{$counter}}</td>
            <td>
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
            <td>
                {{($customer->owner_name != "" && $customer->tally_name != "" )?$customer->owner_name."-".$customer->tally_name : $customer->owner_name}}
            </td>

            <td>{{$customer->contact_person}}</td>
            <td>{{$customer->phone_number1}}</td>
            @if($customer->credit_period != "" || $customer->credit_period>0)
            <td>{{$customer->credit_period}}</td>
            @endif
            @endif
            @endforeach
            @if($order->delivery_location_id !=0)
            @foreach($delivery_location as $location)
            @if($order->delivery_location_id == $location->id)
            <td>{{$location->area_name}}</td>
            <td>{{$order->location_difference}}</td>
            @endif
            @endforeach
            @else
            <td>{{$order->other_location}}</td>
            <td>{{$order->location_difference}}</td>
            @endif
            <?php $product = isset($order['all_order_products']) && isset($order['all_order_products'][0]) ? $order['all_order_products'][0] : ''; ?>
            @if(isset($product) && $product!='' && $product->order_type =='order')
            <td>{{$product['order_product_details']->alias_name}}</td>
            <td>{{$product->quantity}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>{{$product->price}}</td>
            <td>{{($product->vat_percentage!='')?$product->vat_percentage:''}}</td>
            <td>{{$product->remarks}}</td>
            @endif
            <td>{{date("F jS, Y", strtotime($order->expected_delivery_date)) }}</td>
            <td>{{$order->remarks}}</td>
            <td>{{$order->createdby->first_name." ".$order->createdby->last_name}}</td>
            <td>{{$order->updated_at}}</td>
        </tr>
        <?php $count = 0; ?>
        @foreach($order['all_order_products'] as $product)
        @if($count!=0 && isset($product->order_type) &&  $product->order_type =='order')
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$product['order_product_details']->alias_name}}</td>
            <td>{{$product->quantity}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>{{$product->price}}</td>
            <td>{{($product->vat_percentage!='')?$product->vat_percentage:''}}</td>
            <td>{{$product->remarks}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endif
        <?php $count++; ?>
        @endforeach
        <?php $counter++; ?>
        @endforeach
    </table>
    <?php //exit; ?>
</html>
