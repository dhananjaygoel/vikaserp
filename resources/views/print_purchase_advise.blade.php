<!DOCTYPE html>
<html>
    <body>
        <style>
            body {font-size: 10px; font-family: Arial !important; font-weight: bold !important;}

            table {width: 100%; margin:0; padding:0; border-collapse: collapse; border-spacing: 0;}
            table tr {padding: 0px;}
            table thead tr th {text-align:left;}
            table thead tr th.title-name {text-align:center;}
            table th, table td {padding: 0px; text-align: center; border-left: 1px solid #ccc; border-right: 1px solid #ccc;}

            .purchase-advise-details th, .purchase-advise-details td {padding: 10px;}
            .purchase-advise-details thead tr {border: 1px solid #ccc;}
            .purchase-advise-details thead tr th {border-left: none; border-right: none; border-bottom: none !important;}

            .purchase-advise-data {border: none !important;}
            .purchase-advise-data th, .purchase-advise-data td {padding: 10px;}
            .purchase-advise-data thead tr:first-child {border-top: none;}
            .purchase-advise-data thead tr, .purchase-advise-data tbody tr {border: 1px solid #ccc;}
            .purchase-advise-data tr td {text-align: left;}
            .purchase-advise-data tr td.index {width: 25px;}
            .purchase-advise-data tr td.product-size {width: 90px;}

            .purchase-advise-total tbody tr td {text-align: left; border: none;}
            .purchase-advise-total th, .purchase-advise-total td {padding: 10px;}
            .purchase-advise-total thead tr {border: 1px solid #ccc; border-top: none; border-bottom: none;}
            .purchase-advise-total thead tr:last-child {border-bottom: 1px solid #ccc;}

        </style>

        <table class="purchase-advise-details">
            <thead>
                <tr>
                    <th class="title-name" colspan="3">Purchase Advice</th>
                </tr>
                <tr>
                    <th>PA Number: {{ $purchase_advise->serial_number}}</th>
                    <th>Date: {{date('d F, Y')}}</th>
                    <th>Time: {{date('h:i A')}}
                    <?php
                        // echo '<script type="text/javascript">
                        //     var x = new Date()
                        //     var current_time = x.getHours()+":"+x.getMinutes()+":"+x.getSeconds()
                        //     document.write(current_time)
                        //     </script>';
                        ?>
                    </th>
                </tr>
                <tr>
                    <th colspan="1">Trk No: {{ $purchase_advise->vehicle_number}}</th>
                    <th colspan="2">Driver Mob: {{ isset($purchase_advise->driver_contact_no)?$purchase_advise->driver_contact_no:'' }}</th>
                </tr>
                <tr>
                    <th colspan="1">Name: {{ $purchase_advise['supplier']->owner_name}}
                    </th>
                    <th colspan="2">Delivery @: @if($purchase_advise->delivery_location_id > 0)
                                            {{$purchase_advise['location']->area_name}}
                                            @else
                                            {{$purchase_advise->other_location}}
                                            @endif
                    </th>
                </tr>
            </thead>
        </table>

        <table class="purchase-advise-data">
            <thead>
                <tr>
                    <td>Sr.</td>
                    <td>Size</td>
                    <td>Pcs</td>
                    <td>Qty</td>
                    <td>Act Pcs</td>
                    <td>Act Qty</td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach($purchase_advise['purchase_products'] as $prod)
                @if($prod->order_type == 'purchase_advice')
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $prod['purchase_product_details']->alias_name }}</td>
                    <td>{{ $prod->actual_pieces }}</td>
                    <td>{{ $prod->present_shipping }}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
        <table class="purchase-advise-total">
            <thead>
                <tr>
                    <th colspan="3"> Remark: {{$purchase_advise->remarks}}</th>
                </tr>
            </thead>
        </table>
        
    </body>
</html>