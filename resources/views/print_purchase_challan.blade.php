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

            .user-invoice-details th, .user-invoice-details td {padding: 10px;}
            .user-invoice-details thead tr {border: 1px solid #ccc;}
            .user-invoice-details thead tr th {border-left: none; border-right: none; border-bottom: none !important;}

            .user-invoice-data {border: none !important;}
            .user-invoice-data th, .user-invoice-data td {padding: 10px;}
            .user-invoice-data thead tr:first-child {border-top: none;}
            .user-invoice-data thead tr, .user-invoice-data tbody tr {border: 1px solid #ccc;}
            .user-invoice-data tr td {text-align: left;}
            .user-invoice-data tr td.index {width: 25px;}
            .user-invoice-data tr td.product-size {width: 90px;}

            .user-invoice-cal-total tbody tr td {text-align: left; border: none;}
            .user-invoice-cal-total tbody tr:first-child {border-top: none;}
            .user-invoice-cal-total tbody tr {border: 1px solid #ccc;}
            .user-invoice-cal-total tbody tr td.lable {text-align: left;}
            .user-invoice-cal-total tbody tr td.total-count {text-align: right;}
            .user-invoice-cal-total .spacing {padding: 15px;}

            .sm-table-data {width: 250px; float: right;}
            .sm-table-data tbody tr td {text-align: left;}
            .sm-table-data tbody tr:last-child {border-bottom: none;}
            .sm-table-data tbody tr td {border-left: 1px solid #ddd;}
            .sm-table-data th, .sm-table-data td {padding: 10px;}
            .total-qty {display: inline-block; margin-bottom: 1.8em;}
        </style>

        <table class="user-invoice-details">
            <thead>
                <tr>
                    <th class="title-name" colspan="2">Purchase Challan</th>
                </tr>
                <tr>
                    <th>Tally Name: {{ $purchase_challan['supplier']->owner_name }}</th>
                    <th>Date: {{date('F d, Y')}}</th>
                </tr>
                <tr>
                    <th>Delivery @: {{ ($purchase_challan->delivery_location_id == 0)?$purchase_challan['purchase_advice']->other_location : $purchase_challan['delivery_location']->area_name }}</th>
                    <th>Estimate No: {{ $purchase_challan->serial_number }}</th>
                </tr>
                <tr>
                    <th>Time Generated: {{ date("h:i:s a" , strtotime($purchase_challan->created_at)) }}</th>
                    <th>Time Print: <?php
                        echo '<script type="text/javascript">
                            var x = new Date()
                            var current_time = x.getHours()+":"+x.getMinutes()+":"+x.getSeconds()
                            document.write(current_time)
                            </script>';
                        ?>
                    </th>
                </tr>
            </thead>
        </table>

        <table class="user-invoice-data">
            <thead>
                <tr>
                    <td class="index">Sr.</td>
                    <td class="product-size">Size</td>
                    <td>Pcs</td>
                    <td>Qty</td>
                    <td>Rate</td>
                    <td>Amount</td>
                </tr>
            </thead>
            <?php
                $i = 1;
                $total_qty = 0;
                $total_price = 0;
                $final_total_amt = 0;
                $freight_vat = 0;
                $discount_vat = 0;
            ?>
                @foreach($purchase_challan['all_purchase_products'] as $prod)
                <tbody>
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $prod['purchase_product_details']->alias_name }}</td>
                        <td>{{ $prod->actual_pieces}}</td>
                        <td>{{ $prod->quantity }}</td>
                        <td>{{ ((isset($prod->price) && $prod->price != '0.00') ? $prod->price : $prod['purchase_product_details']->product_category['price']) }}</td>
                        <td>{{ ((isset($prod->price) && $prod->price != '0.00') ? $prod->price : $prod['purchase_product_details']->product_category['price']) * $prod->quantity }}</td>
                    </tr>
                </tbody>
                <?php 
                $total_price = (float)$total_price + (float)(((isset($prod->price) && $prod->price != '0.00') ? $prod->price : $prod['purchase_product_details']->product_category['price']) * $prod->quantity);
                // $final_total_amt += (float)$total_price;
                ?>
                @endforeach
        </table>

            
        <table class="user-invoice-cal-total">
            <tbody>
                <tr>
                    <td  class="spacing" valign="top">
                        <div>Total Quantity: <span class="total-qty">{{ round($purchase_challan['all_purchase_products']->sum('quantity'), 2) }}</span></div>
                        <div>Remarks: <span class="remarks">{{ isset($purchase_challan['remarks']) ? $purchase_challan['remarks'] : ''}}</span></div>
                    </td>
                    <td>
                        <table class="sm-table-data">
                            <tbody>
                                <tr>
                                    <td class="lable">Total</td>
                                    <td class="total-count">{{ round($total_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Freight</td>
                                    <td class="total-count">
                                    {{($purchase_challan->freight != "")?round($purchase_challan->freight, 2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Discount</td>
                                    <td class="total-count">
                                    {{($purchase_challan->discount != "")?round($purchase_challan->discount,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Total</td>
                                    <td class="total-count">
                                    <?php 
                                        $total = $total_price + $purchase_challan->freight + $purchase_challan->discount;
                                    ?>
                                    {{ round($total_price + $purchase_challan->freight + $purchase_challan->discount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">GST</td>
                                    <td class="total-count">
                                    {{($purchase_challan->vat_percentage != "")?round($purchase_challan->vat_percentage, 2):0}} %</td>
                                </tr>
                                <tr>
                                    <td class="lable">Round Off</td>
                                    <td class="total-count">
                                    <?php 
                                        if((isset($purchase_challan->vat_percentage) && $purchase_challan->vat_percentage != '')){
                                            if((isset($purchase_challan->freight) && $purchase_challan->freight != '0.00')){
                                                $freight_vat = $purchase_challan->freight * $purchase_challan->vat_percentage / 100;
                                            }
                                            if((isset($purchase_challan->discount) && $purchase_challan->discount != '0.00')){
                                                $discount_vat = $purchase_challan->discount * $purchase_challan->vat_percentage / 100;
                                            }
                                        }
                                        $vat = ($total_price * (($purchase_challan->vat_percentage != "")?round($purchase_challan->vat_percentage, 2):0) / 100 );
                                        $grand_total = (float)$vat + (float)$total + $freight_vat + $discount_vat;
                                        $roundoff = round($grand_total,0) - $grand_total;
                                    ?>
                                    {{ round($roundoff, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">GT</td>
                                    <td class="total-count">
                                    {{ round($grand_total, 0) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>