<!DOCTYPE html>
<html>
    <head>
        <title>Sales-Daybook</title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
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
                width:7%;         
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell2{
                float:left;
                display:table-column;         
                width:5%;         
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
                text-align: center;
            }        
            .invoice {
                width:100%;
                border: 1px solid #ccc;
                float: left;
                padding: 0px;
                overflow: hidden;
            }
            .title{
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #ccc;
                padding: 10px 0px 10px 5px;
                font-weight: 600;
            }
            .center{
                text-align: center;
            }
        </style>
        <div class="invoice">
            <div class="title">
                Vikas Associate Order Automation System
            </div>            
            <div class="title">
                Sales-Daybook({{ date('d F, Y')}})
            </div>            
            <div class="divTable">
                <div class="headRow">
                    <div  class="divCell2">#</div>
                    <div  class="divCell">Challan sr. No</div>
                    <!--<div  class="divCell">Do. No</div>-->
                    <div  class="divCell">Name</div>
                    <div  class="divCell">Del Loc</div>
                    <div  class="divCell">Qty</div>
                    <div  class="divCell">Amount</div>  
                    <div  class="divCell">Bill No.</div>
                    <div  class="divCell">Truck No</div>
                    <div  class="divCell">Loaded By</div>
                    <div  class="divCell">Labour</div>
                    <div  class="divCell">Remarks</div>
                </div>

                <?php
                $i = 1;
                ?>
                @foreach($allorders as $obj)
                <?php
                $qty = 0;
                $amount = 0;
                ?>

                <?php
                $total_qunatity = 0;
                $total_qty = 0;
                foreach ($obj["all_order_products"] as $products) {
                    if ($products['unit']->id == 1) {
                        $total_qunatity += $products->present_shipping;
                    }
                    if ($products['unit']->id == 2) {
                        $total_qunatity += ($products->present_shipping * $products['order_product_details']->weight);
                    }
                    if ($products['unit']->id == 3) {
                        $total_qunatity += (($products->present_shipping / $products['order_product_details']->standard_length ) * $products['order_product_details']->weight);
                    }

                    $total_qty = $products->price;
                }
                ?>

                <div class="divRow">
                    <div class="divCell2 center">{{$i++ }}</div>
                    <div class="divCell">{{ $obj->serial_number }}</div>
                    <!--<div class="divCell">xxx</div>-->
                    <div class="divCell">{{ $obj['customer']->owner_name }}</div>
                    <div class="divCell">{{ isset($obj['delivery_order']['location'])?$obj['delivery_order']['location']->area_name: '' }}</div>
                    <div class="divCell">{{ $total_qunatity }}</div> 
                    <div class="divCell">{{ $obj->grand_price }}</div>
                    <div class="divCell">{{ $obj->bill_number }}</div>
                    <div class="divCell">{{ $obj->vehicle_number }}</div>
                    <div class="divCell">{{ $obj->loaded_by }}</div>
                    <div class="divCell">{{ $obj->labours }}</div> 
                    <div class="divCell">{{ $obj->remarks }}</div> 
                </div>
                @endforeach
            </div>
        </div>    
    </body>
</html>

