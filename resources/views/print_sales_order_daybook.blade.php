<!DOCTYPE html>
<html>
    <head>
        <title>Sales-Daybook</title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            body {font-size: 10px; font-family: Arial !important; font-weight: bold !important;}

            table {width: 100%; margin:0; padding:0; border-collapse: collapse; border-spacing: 0;}
            table thead tr th {text-align:center;}
            table thead tr th.title-name {text-align:center;}
            table th, table td {text-align: center; border: 1px solid #ccc;}
            
            .sales-daybook-details th, .sales-daybook-data th, .sales-daybook-details td, .sales-daybook-data td {padding: 10px;}
            .sales-daybook-data thead tr {border: 1px solid #ccc;}
            .sales-daybook-data tbody tr td {border-bottom: 1px solid #ccc;}
        </style>
        
        <table class="sales-daybook-details">
            <thead>
                <tr>
                    <th class="title-name" colspan="2">Vikas Associate Order Automation System</th>
                </tr>
                <tr>
                    <th class="title-name" colspan="2">Sales-Daybook({{ date('F d, Y')}})</th>
                </tr>
            </thead>
        </table>

        <table class="sales-daybook-data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Challan No</th>
                    <th>Name</th>
                    <th>Del Loc</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Bill No.</th>
                    <th>Truck No.</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <?php
                $i = 1;
            ?>
            @foreach($allorders as $obj)
            <?php
                $qty = 0;
                $amount = 0;
//                $total_qunatity = 0;
                $total_qty = 0;
                foreach ($obj["delivery_challan_products"] as $products) {
//                    if ($products['unit']->id == 1) {
//                        $total_qunatity += $products->quantity;
//                    }
//                    if ($products['unit']->id == 2) {
//                        $total_qunatity += ($products->quantity * $products['order_product_details']->weight);
//                    }
//                    if ($products['unit']->id == 3) {
//                        $total_qunatity += (($products->quantity / $products['order_product_details']->standard_length ) * $products['order_product_details']->weight);
//                    }
//
//                    $total_qty = $products->price;
                }
            ?>
            <tbody>
                <tr>
                    <td>{{$i++ }}</td>
                    <td>{{ isset($obj->serial_number) ? $obj->serial_number : '' }}</td>
                    <td>
                         @if($obj->customer->tally_name != "" && $obj->customer->owner_name != "")
                {{ $obj->customer->owner_name }}-{{$obj->customer->tally_name}}
                @else 
                {{ $obj->customer->owner_name }}
                @endif
                    </td>
                    <td>{{ isset($obj['delivery_order']['location']) ? $obj['delivery_order']['location']->area_name : '' }}</td>
                    <td>{{ round($obj["delivery_challan_products"]->sum('actual_quantity'), 2) }}</td>
                    <td>{{ isset($obj->grand_price) ? round($obj->grand_price, 2) : '' }}</td>
                    <td>{{ isset($obj->bill_number) ? $obj->bill_number : '' }}</td>
                    <td>{{ isset($obj['delivery_order']->vehicle_number) ? $obj['delivery_order']->vehicle_number : '' }}</td>
                    <td>{{ isset($obj->remarks) ? $obj->remarks : '' }}</td>
                </tr>
            </tbody>
            @endforeach
        </table>
    </body>
</html>
