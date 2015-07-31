<!DOCTYPE html>
<html>
    <head>
        <title>Purchase-Daybook</title>
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
                font-size: 8px;
            }
            .divCell2{
                float:left;
                display:table-column;         
                width:5%;         
                padding: 5px;
                border-right: 1px solid #ccc;
                font-size: 8px;
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
                text-align: center;
            }        


            .invoice{
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
                Purchase Daybook
            </div>            
            <div class="divTable">
                <div class="headRow">
                    <div  class="divCell2">#</div>
                    <div  class="divCell">Challan sr. No</div>
                    <!--<div  class="divCell">Do. No</div>-->
                    <div  class="divCell">Tally Name</div>
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
                $total_qunatity = 0;
                ?>
                @foreach ($purchase_daybook as $obj)
                <?php
                $qty = 0;
                $amount = 0;
                ?>
                @foreach ($obj['all_purchase_products'] as $total_qty)
                <?php
                if ($total_qty->unit_id == 1) {
                    $total_qunatity += $total_qty->present_shipping;
                }
                if ($total_qty->unit_id == 2) {
                    $total_qunatity += ($total_qty->present_shipping * $total_qty['purchase_product_details']->weight);
                }
                if ($total_qty->unit_id == 3) {
                    $total_qunatity += ($total_qty->present_shipping / $total_qty['purchase_product_details']->standard_length) * $total_qty['product_category']['product_sub_category']->weight;
                }
                ?>
                @endforeach
                <div class="divRow">
                    <div class="divCell2 center">{{ $i++ }}</div>
                    <div class="divCell">{{ $obj->serial_number }}</div>
                    <!--<div class="divCell">xxx</div>-->
                    <div class="divCell">{{ $obj['supplier']->owner_name }}</div>
                    <div class="divCell">{{ $obj['delivery_location']->area_name}}</div>
                    <div class="divCell">{{ round($total_qunatity, 2) }}</div> 
                    <div class="divCell">{{ $obj->grand_total }}</div>
                    <div class="divCell">{{ $obj->bill_number }}</div>
                    <div class="divCell">{{ $obj->vehicle_number }}</div>
                    <div class="divCell">{{ $obj->unloaded_by }}</div>
                    <div class="divCell">{{ $obj->labours }}</div> 
                    <div class="divCell">{{ $obj->remarks }}</div> 
                </div>
                @endforeach
            </div>
        </div>
    </body>
</html>
