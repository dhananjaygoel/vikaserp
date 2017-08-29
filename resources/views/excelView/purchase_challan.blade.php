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
            <td class="heading1">Delivery Freight</td>

            <td class="heading1">Product(Alias)</td>
            <td class="heading1">Quantity</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Price</td>
            <td class="heading1">GST Percentage</td>
            <td class="heading1">Remark</td>

            <td class="heading1">Expected Delivery Date</td>
            <td class="heading1">Remark</td>
            <!--<td class="heading1">Order By</td>-->
            <td class="heading1">Order Time/Date</td>
        </tr>
        <?php $counter = 1; ?>
        @foreach ($order_objects as $order)
        <tr>
            <td>{{$counter}}</td>
            <td>
                {{$order['supplier']->owner_name}} 
            </td>

            <td>
                {{$order['supplier']->tally_name}} 
            </td>

            <td>{{$order['supplier']->contact_person}}</td>
            <td>{{$order['supplier']->phone_number1}}</td>
            @if($order['supplier']->credit_period != "" || $order['supplier']->credit_period>0)
            <td>{{$order['supplier']->credit_period}}</td>
            @endif

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
            
            <td>{{$order['all_purchase_products'][0]['purchase_product_details']->alias_name}}</td>
            <td>{{$order['all_purchase_products'][0]->quantity}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $order['all_purchase_products'][0]->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>{{$order['all_purchase_products'][0]->price}}</td>
            <td>{{$order->vat_percentage}}</td>
            <td>{{$order['all_purchase_products'][0]->remarks}}</td>
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
            <td>{{date("F jS, Y", strtotime($order->expected_delivery_date)) }}</td>
            <td>{{$order->remarks}}</td>
            
            <td>{{$order->updated_at}}</td>
        </tr>
        
        
        
        
        
        
        
        
        <?php $count = 0; ?>
        @foreach($order['all_purchase_products'] as $product)
        @if($count!=0 && isset($product->order_type) &&  $product->order_type =='purchase_challan')
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$product['purchase_product_details']->alias_name}}</td>
            <td>{{$product->quantity}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>{{$product->price}}</td>
            <td>{{$order->vat_percentage}}</td>
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
