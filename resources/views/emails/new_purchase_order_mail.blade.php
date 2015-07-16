<div class="invoice">
    <p>Dear <em>{{$purchase_order['customer_name']}}</em>, </p>
    @if($purchase_order['source'] == 'create_order')
    <p>New purchase order has been generated on <strong>{{$purchase_order['created_date']}}</strong></p>
    @endif
    @if($purchase_order['source'] == 'update_order')
    <p>Purchase order has been updated on <strong>{{$purchase_order['created_date']}}</strong></p>
    @endif
    <p>Here are the details for your order,</p>
    <table border='1'>
        <tr>
            <th>Sr no.</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Rate</th>
        </tr>
        <?php $i = 1; ?>
        @foreach($purchase_order['order_product'] as $product)
        <tr>
            <td>{{$i++}}</td>
            <td>{{ $product['purchase_product_details']->alias_name }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->price }}</td>
        </tr>
        @endforeach
    </table>
    <p>You order is expected to be delivered By: <strong>{{$purchase_order['expected_delivery_date']}}</strong> at: <strong>{{$purchase_order['delivery_location']}}</strong></p>
</div>
<div style="font-size: 12px;">
    <br>
    <p>--</p>
    <p>Thank you, </p>
    <p>Vikas Associates</p>
</div>