<!DOCTYPE html>
<html>
    <head>
        <title>Purchase Advice</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            body{
                font-size: 8px;
                font-family: Arial !important;
                font-weight: bold !important;
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
                width:13%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell2{
                float:left;
                display:table-column;
                width:4%;
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

            .divCell:last-child{
                border: none;
            }
            .divRow:last-child{
                border-top: none;
                border-bottom:  1px solid #ccc;
            }
            .headRow{
                display:table-row;
            }
            .footer{
                width: 100%;
                float: left;
            }
            .remark{
                width: 95%;
                float: left;
                padding: 15px;
            }
            .content{
                width: 88%;
                float: left;
                padding-top: 20px;
            }
            .invoice{
                width:80%;
                margin-left: 10%;
                border: 1px solid #ccc;
                float: left;
                padding: 0px;
                overflow: hidden;
            }
            .del{
                width: 100%;
                float: left;
                padding: 10px 0px 10px 5px;
            }
            .trk-mobile{
                width: 100%;
                float: left;
                padding: 10px 0px 10px 5px;
                border-bottom: 1px solid #ccc;
            }

            .trk-no{
                width: 50%;
                float: left;
            }
            .mob-no{
                width: 50%;
                float: left;
            }
            .name{
                width: 100%;
                padding: 10px 0px 10px 5px;
                float: left;
                position: relative;
                border-bottom: 1px solid #ccc;
            }
            .delivery-details{
                width: 100%;
                padding: 10px 0px 10px 5px;
                float: left;
                border-bottom: 1px solid #ccc;
            }
            .do-no {
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
                Purchase Advice
            </div>
            <div class="delivery-details">
                <div class="do-no">
                    PA Number:
                    {{ $purchase_advise->serial_number}}
                </div>
                <div class="date">
                    Date: {{ date('F d, Y')}}
                </div>
                <div class="time">
                    <!--Time: {{ date("h:i:sa") }}-->
                    Time:  <?php
                    echo '<script type="text/javascript">
                        var x = new Date()
                        var current_time = x.getHours()+":"+x.getMinutes()+":"+x.getSeconds()
                        document.write(current_time)
                        </script>';
                    ?>
                </div>
            </div>
            <div class="name">
                Name: {{ $purchase_advise['supplier']->owner_name}}
            </div>
            <div class="trk-mobile">
                <div class="trk-no">
                    Trk No: {{ $purchase_advise->vehicle_number}}
                </div>
                <div class="mob-no">
                    Driver Mob:
                </div>
            </div>
            <div class="del">
                Del @:

                @if($purchase_advise->delivery_location_id > 0)
                {{$purchase_advise['location']->area_name}}
                @else
                {{$purchase_advise->other_location}}
                @endif

            </div>
            <div class="divTable">
                <div class="headRow">
                    <div class="divCell2">Sr.</div>
                    <div class="divCell3">Size</div>
                    <div class="divCell">Pcs</div>
                    <div class="divCell">Qty</div>
                    <div class="divCell">Act pcs</div>
                    <div class="divCell">Act Qty</div>
                </div>
                <?php
                $i = 1;
                ?>
                <!-- purchase advise product details-->
                @foreach($purchase_advise['purchase_products'] as $prod)
                @if($prod->order_type == 'purchase_advice')
                <div class="divRow">
                    <div class="divCell2">{{ $i++ }}</div>
                    <div class="divCell3">{{ $prod['purchase_product_details']->alias_name }}</div>
                    <div class="divCell"> {{ $prod->actual_pieces }} </div>
                    <div class="divCell"> {{ $prod->present_shipping }} </div>
                    <div class="divCell"> &nbsp; </div>
                    <div class="divCell"> &nbsp; </div>
                </div>
                @endif
                @endforeach
            </div>
            <div class="footer">
                <div class="remark">
                    Remark: {{$purchase_advise->remarks}}
                </div>
                <!--                <div class="content">

                                </div>-->
            </div>
        </div>
    </body>
</html>