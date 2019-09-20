<!deliveri-orderCTYPE html>
<html>
    <body>
        <style>
            body {font-size: 10px; font-family: Arial !important; font-weight: bold !important;}

            table {width: 100%; margin:0; padding:0; border-collapse: collapse; border-spacing: 0;}
            table tr {padding: 0px;}
            table thead tr th {text-align:left;}
            table thead tr th.title-name {text-align:center;}
            table th, table td {padding: 0px; text-align: center; border-left: 1px solid #ccc; border-right: 1px solid #ccc;}

            .deliveri-order-details th, .deliveri-order-details td {padding: 10px;}
            .deliveri-order-details thead tr {border: 1px solid #ccc;}
            .deliveri-order-details thead tr th {border-left: none; border-right: none; border-bottom: none !important;}

            .deliveri-order-data {border: none !important;}
            .deliveri-order-data th, .deliveri-order-data td {padding: 10px;}
            .deliveri-order-data thead tr:first-child {border-top: none;}
            .deliveri-order-data thead tr, .deliveri-order-data tbody tr {border: 1px solid #ccc;}
            .deliveri-order-data tr td {text-align: left;}
            .deliveri-order-data tr td.index {width: 25px;}
            .deliveri-order-data tr td.product-size {width: 90px;}

            .deliveri-order-total tbody tr td {text-align: left; border: none;}
            .deliveri-order-total th, .deliveri-order-total td {padding: 10px;}
            .deliveri-order-total thead tr {border: 1px solid #ccc; border-top: none; border-bottom: none;}
            .deliveri-order-total thead tr:last-child {border-bottom: 1px solid #ccc;}

        </style>

        <table class="deliveri-order-details">
            <thead>
                <tr>
                    <th class="title-name" colspan="3">Delivery Order</th>
                </tr>
                <tr>
                    <th>deliveri-order Number: {{isset($delivery_data->serial_no)?$delivery_data->serial_no:'' }}</th>
                    <th>Date: {{date('F d, Y')}}</th>
                    <th>Time: <?php
                        echo '<script type="text/javascript">
                            var x = new Date()
                            var current_time = x.getHours()+":"+x.getMinutes()+":"+x.getSeconds()
                            deliveri-ordercument.write(current_time)
                            </script>';
                        ?>
                    </th>
                </tr>
                <tr>
                    <th>Vehicle No: {{ isset($delivery_data->vehicle_number)?$delivery_data->vehicle_number :'' }}</th>
                    <th>Driver Mob: {{ isset($delivery_data->driver_contact_no)?$delivery_data->driver_contact_no:'' }}</th>
                    @if($customer_type!="supplier")
                    <th>Empty Truck Weight: {{ isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0' }} Kg</th>
                    @endif
                </tr>
                <tr>
                    <th colspan="1">Name: 
                        @if(isset($delivery_data['customer']->tally_name) && $delivery_data['customer']->tally_name != "")
                            {{$delivery_data['customer']->tally_name}}
                        @else
                            {{isset($delivery_data['customer']->owner_name)?$delivery_data['customer']->owner_name:'N/A'}}
                        @endif 
                    </th>
                    <th colspan="2">Delivery @: {{(isset($delivery_data->delivery_location_id) && $delivery_data->delivery_location_id!=0) ? $delivery_data['location']->area_name : (isset($delivery_data->other_location)?$delivery_data->other_location:'') }}</th>
                </tr>
            </thead>
        </table>

        <table class="deliveri-order-data">
            <thead>
                <tr>
                    <td>Sr.</td>
                    <td>Size</td>
                    <td>Qty</td>
                    <td>Unit</td>
                    <td>Pcs</td>
                    <td>Present Shipping</td>
                    <td>GST</td>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @if($delivery_data['delivery_product'])
                @foreach($delivery_data['delivery_product'] as $product)
                @if(isset($product['order_type']) && $product['order_type'] == 'delivery_order')
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $product['order_product_details']->alias_name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>@foreach($units as $u)
                        @if($product->unit_id == $u->id)
                        {{$u->unit_name}}
                        @endif
                        @endforeach
                    </td>
                    <td>{{ $product->actual_pieces }}</td>
                    <td>{{ $product->present_shipping }}</td>
                    <td><?php
                            if($product->vat_percentage > 0){
                            $state = \App\DeliveryLocation::where('id',$delivery_data->delivery_location_id)->first()->state_id;
                            $local_state = \App\States::where('id',$state)->first()->local_state;
                            $hsn_code = $product->product_sub_category->product_category->hsn_code;
                            $is_gst = false;
                            if($hsn_code){
                                $is_gst = true;
                                $hsn_det = \App\Hsn::where('hsn_code',$hsn_code)->first();
                                $gst_det = \App\Gst::where('gst',$hsn_det->gst)->first();
                            }
                            ?>
                            @if($is_gst)
                                @if($local_state)
                                    {{$gst_det->sgst + $gst_det->cgst}} %
                                @else
                                    {{$gst_det->igst}} %
                                @endif
                            @else
                                @if($product->vat_percentage > 0){{$delivery_data->vat_percentage}}@else{{"0"}}@endif{{"%"}}
                            @endif
                            <?php }else{ ?>
                                @if($product->vat_percentage > 0){{$delivery_data->vat_percentage}}@else{{"0"}}@endif{{"%"}}
                            <?php } ?>
                    </td>
                </tr>
                @endif
                @endforeach
                @endif
            </tbody>
        </table>
        <table class="deliveri-order-total">
            <thead>
                <tr>
                    <th colspan="3">Total Quantity: {{ isset($delivery_data->total_quantity)?$delivery_data->total_quantity :''}} (Ton)</th>
                </tr>
                <tr>
                    <th colspan="3">Remark: {{ isset($delivery_data->remarks) ? $delivery_data->remarks : ''}}</th>
                </tr>
            </thead>
        </table>
        
    </body>
</html>