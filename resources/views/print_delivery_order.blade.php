<!DOCTYPE html>
<html>
    <head>
        <title>Delivery Order</title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            body{
                font-size: 10px;
                font-family: Arial !important;
                font-weight: bold !important;
                /*font-family: monospace !important;*/
            }
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
                width:14%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell:last-child{
                float:left;
                display:table-column;
                width:14%;
                padding: 5px;
                border-right: none;
            }
            .divCell2{
                float:left;
                display:table-column;
                width:4.2%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell3{
                float:left;
                display:table-column;
                width:30%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            /*            .divCell:last-child
                        {
                            border: none;
                        }*/
            .divRow:last-child
            {
                border-top: none;
                border-bottom: 1px solid #ccc;
            }
            .headRow{
                display:table-row;
            }
            .footer
            {
                width: 100%;
                float: left;
            }
            .remark{
                width: 8%;
                float: left;
                padding: 20px 5px ;
            }
            .content{
                width: 88%;
                float: left;
                padding-top: 20px;
            }
            .invoice
            {
                width:70%;
                margin-left: 15%;
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
            <div class="title">
                Delivery Order
            </div>
            <div class="delivery-details">
                <div class="do-no">
                    DO Number: {{ $delivery_data->serial_no }}
                </div>
                <div class="date">
                    Date: {{ date('F d, Y')}}
                </div>
                <div class="time">
                    <!--Time: {{ date("h:i:sa") }}-->
                    Time: <?php
                    echo '<script type="text/javascript">
                        var x = new Date()
                        var current_time = x.getHours()+":"+x.getMinutes()+":"+x.getSeconds()
                        document.write(current_time)
                        </script>';
                    ?>
                </div>
            </div>
            <div class="name">
                Name:
                @if($delivery_data['customer']->tally_name != "")
                {{ $delivery_data['customer']->tally_name }}
                @else
                {{ $delivery_data['customer']->owner_name }}
                @endif

            </div>
            <div class="trk-mobile">
                <div class="trk-no">
                    Vehicle No: {{ $delivery_data->vehicle_number }}
                </div>
                <div class="mob-no">
                    Driver Mob: {{ $delivery_data->driver_contact_no }}
                </div>
            </div>
            <div class="del">
                Delivery @:
                @if($delivery_data->delivery_location_id!=0)
                {{ $delivery_data['location']->area_name }}
                @else
                {{ $delivery_data->other_location }}
                @endif
            </div>
            <div class="divTable">
                <div class="headRow">
                    <div class="divCell2">Sr.</div>
                    <div class="divCell3">Size</div>
                    <div class="divCell">Qty</div>
                    <div class="divCell">Unit</div>
                    <div class="divCell">Pcs</div>
                    <div class="divCell">Qty</div>
                </div>

                <?php
                $i = 1;
                ?>
                @foreach($delivery_data['delivery_product'] as $product)
                @if($product['order_type'] == 'delivery_order')
                <div class="divRow">
                    <div class="divCell2">{{ $i++ }}</div>
                    <div class="divCell3">{{ $product['order_product_details']->alias_name }}</div>
                    <div class="divCell">{{ $product->quantity }}</div>
                    <div class="divCell">
                        @foreach($units as $u)
                        @if($product->unit_id == $u->id)
                        {{$u->unit_name}}
                        @endif
                        @endforeach
                    </div>
                    <div class="divCell"> &nbsp; </div>
                    <div class="divCell"> &nbsp; </div>
                </div>
                @endif
                @endforeach
            </div>
            <div class="footer">
                <div class="remark">
                    Remark:
                </div>
                <div class="content">
                    {{ $delivery_data->remarks }}
                </div>
            </div>
        </div>
    </body>
</html>