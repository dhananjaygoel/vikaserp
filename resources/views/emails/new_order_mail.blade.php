<div class="invoice">
    <p>Dear <em>{{$order['customer_name']}}</em>, </p>
    @if($order['source'] == 'inquiry')
    <p>New order has been generated for your enquiry on <strong>{{$order['created_date']}}</strong></p>
    @endif
    @if($order['source'] == 'create_order')
    <p>New order has been generated on <strong>{{$order['created_date']}}</strong></p>
    @endif
    @if($order['source'] == 'update_order')
    <p>Purchase order has been updated on <strong>{{$order['created_date']}}</strong></p>
    @endif
    <p>Here are the details for your order,</p>
    <table border='1'>
        <tr>
            <th>Sr no.</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit</th>
            <th>Rate</th>
        </tr>
        <?php $i = 1; ?>
        @foreach($order['order_product'] as $product)
        <tr>
            <td>{{$i++}}</td>
            <td>{{ $product['order_product_details']->alias_name }}</td>
            <td>{{ $product->quantity }}</td>
            <td>
                @if($product['order_product_details']->unit_id == 1)
                {{ 'KG' }}
                @else if($product['order_product_details']->unit_id == 2)
                {{ 'Pieces' }}
                @else if($product['order_product_details']->unit_id == 3)
                {{ 'Meter' }}
                @endif
            </td>
            <td>{{ $product->price }}</td>
        </tr>
        @endforeach
    </table>
    <p>You order is expected to be delivered By: <strong>{{$order['expected_delivery_date']}}</strong> at: <strong>{{$order['delivery_location']}}</strong></p>
</div>
<div style="font-size: 12px;">
    <br>
    <p>--</p>
    <p>Thank you, </p>
    <p>Vikas Associates</p>
</div>