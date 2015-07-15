<style>
    .divTable{
        display:table;         
        width:100%;         
        background-color:#fff;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
    }
    .divRow{

        width:auto;
        clear:both; 
        border-top: 1px solid #ccc;
    }
    .divCell{
        float:left;
        display:table-column;         
        width:15.2%;         
        padding: 5px;
        border-right: 1px solid #ccc;
    }
    .divCell:last-child
    {
        border: none;
    }
    .divRow:last-child
    {
        border-top: none;
        border-bottom:  1px solid #ccc;
    }
    .headRow{
        display:table-row;
    }        
    .footer
    {
        width: 100%;        

        float: left;

    }
    .remark
    {
        width: 10%;
        float: left;

        padding: 30px 5px ;
    }
    .content
    {
        width: 88%;
        float: left;
        padding-top: 30px;
    }
    .invoice
    {
        width:60%;
        margin-left: 20%;
        border: 1px solid #ccc;
        float: left;
        padding: 0px;
        overflow: hidden;
    }
    .del
    {
        width: 100%;
        float: left;        
        padding: 10px 0px 10px 5px;
    }
    .trk-mobile
    {
        width: 100%;
        float: left;
        padding: 10px 0px 10px 5px;
        border-bottom: 1px solid #ccc;
    }
    .trk-no
    {
        width: 50%;
        float: left;
    }
    .mob-no
    {
        width: 50%;
        float: left;
    }
    .name
    {
        width: 100%;            
        padding: 10px 0px 10px 5px;
        float: left;
        position: relative;
        border-bottom: 1px solid #ccc;
    }
    .delivery-details
    {
        width: 100%;
        padding: 10px 0px 10px 5px;
        float: left;
        border-bottom: 1px solid #ccc;
    }
    .do-no
    {
        width: 33%;
        float: left;
        position: relative;
    }
    .date
    {
        width: 33%;
        float: left;
        position: relative;
    }
    .time
    {
        width: 33%;
        float: left;
        position: relative;
    }
    .title
    {
        width: 100%;
        text-align: center;
        border-bottom: 1px solid #ccc;
        padding: 10px 0px 10px 5px;
        font-weight: 600;
    }
</style>
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
    <div class="footer">
        <div class="remark"> Remark </div>
        <div class="content"> {{ $order['remarks'] }} </div>
    </div>
    <p>You order will be delivered By: {{$order['expected_delivery_date']}}</p>
</div>