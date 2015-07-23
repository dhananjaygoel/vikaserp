<div class="invoice">
    <p>Dear <em>{{$order['customer_name']}}</em>, </p>
    <p>Your order has been completed on <strong>{{$order['created_date']}}</strong></p>
    <p>Here are the details for your order,</p>
    <table border='1'>
        <tr>
            <th>Sr no.</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Units</th>
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
</div>
<div style="font-size: 12px;">
    <br>
    <p>--</p>
    <p>Thank you, </p>
    <p>Vikas Associates</p>
</div>