<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
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
                    <th class="title-name" colspan="2"><?php echo $title; ?>({{ date('F d, Y')}})</th>
                </tr>
            </thead>
        </table>

        <table class="sales-daybook-data">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Challan No</th>
                    <th>Tally Name</th>
                    <th>Del Loc</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>Bill No.</th>
                    <th>Truck No.</th>
                    <th>Unloaded By</th>
                    <th>Labour</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <?php
                $i = 1;
            ?>
            <tbody>
            @foreach($purchase_daybook as $obj)
            <?php
                $qty = 0;
                $amount = 0;
                $lb_arr = [];
                $lbr_arr = [];
            ?>                                                    
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ isset($obj->serial_number) ? $obj->serial_number : '' }}</td>
                    <td> @if($obj['supplier']->tally_name != "" && $obj['supplier']->owner_name != "")
                            {{ $obj['supplier']->owner_name }}-{{$obj['supplier']->tally_name}}
                        @else 
                            {{ $obj['supplier']->owner_name }}
                        @endif
                    </td>
                    <td>{{ ($obj->delivery_location_id == 0) ? $obj['purchase_advice']->other_location : $obj['delivery_location']->area_name }}
                    <td>{{ round($obj['all_purchase_products']->sum('quantity'), 2) }}</td>
                    <td>{{ isset($obj->grand_total) ? $obj->grand_total : '' }}</td>
                    <td>{{ isset($obj->bill_number) ? $obj->bill_number : '' }}</td>
                    <td>{{ isset($obj->vehicle_number) ? $obj->vehicle_number : '' }}</td>
                    <td>@if(isset($obj['challan_loaded_by']))
                            @foreach($obj['challan_loaded_by'] as $load)
                                <?php 
                                    if(!in_array($load->loaded_by_id,$lb_arr) && ($load->loaded_by_id!=0)){
                                        array_push($lb_arr, $load->loaded_by_id);
                                    } 
                                ?>                                                    
                            @endforeach
                        @endif
                        {{count((array)$lb_arr)}}
                    </td>
                    <td> @if(isset($obj['challan_labours']))
                            @foreach($obj['challan_labours'] as $labour)                                                    
                                <?php 
                                    if(!in_array($labour->labours_id,$lbr_arr) && ($labour->labours_id!=0)){
                                        array_push($lbr_arr, $labour->labours_id);
                                    } 
                                ?>                                                    
                            @endforeach
                        @endif
                        {{count((array)$lbr_arr)}}
                    </td>
                    <td>{{ isset($obj->remarks) ? $obj->remarks : '' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </body>
</html>
