<div class="invoice">
    <p>Dear {{$order['customer_name']}}, </p>
    <p>New order has been generated for your enquiry on {{$order['created_date']}}</p>
    <p>Here are the details for your order,</p>
    <div class="divTable">                
        <div class="headRow">
            <div  class="divCell">Sr.</div>
            <div  class="divCell">Product</div>
            <div  class="divCell">Size</div>
            <div  class="divCell">Quantity</div>
            <div  class="divCell">Rate</div>                
        </div>
<?php $i = 1; ?>
        @foreach($order['order_product'] as $product)
        <div class="divRow">
            <div class="divCell">{{ $i++ }}</div>
            <div class="divCell">{{ $product['order_product_details']->alias_name }}</div>
            <div class="divCell">{{ $product['order_product_details']->size }}</div>
            <div class="divCell">{{ $product->quantity }}</div>
            <div class="divCell">{{ $product->price }}</div>
        </div>
        @endforeach
    </div>
    <p>You order will be delivered By: {{$order['expected_delivery_date']}}</p>
</div>