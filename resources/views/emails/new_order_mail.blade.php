<div class="invoice">
    <p>Dear <em>{{$order['customer_name']}}</em>, </p>
    <p>New order has been generated for your enquiry on <strong>{{$order['created_date']}}</strong></p>
    <p>Here are the details for your order,</p>
    <table border='1'>
        <tr>
            <th>Sr no.</th>
            <th>Product</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Rate</th>
        </tr>
        <?php $i = 1; ?>
        @foreach($order['order_product'] as $product)
        <tr>
            <td>{{$i++}}</td>
            <td>{{ $product['order_product_details']->alias_name }}</td>
            <td>{{ $product['order_product_details']->size }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->price }}</td>
        </tr>
        @endforeach
    </table>
    <p>You order is expected to be delivered By: <strong>{{$order['expected_delivery_date']}}</strong> at: <strong>{{$order['delivery_location']}}</strong></p>
</div>
<div style="color: #DDD; font-size: 12px;">
    <br>
    <p>--</p>
    <p>Thank you, </p>
    <p>Vikas Associates</p>
</div>