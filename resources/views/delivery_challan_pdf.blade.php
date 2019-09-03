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
            .user-invoice-details thead tr {border: 1px solid #ddd;}
            .user-invoice-details thead tr th {border-left: none; border-right: none; border-bottom: none !important;}

            .user-invoice-data {border: none !important;}
            .user-invoice-data th, .user-invoice-data td {padding: 10px;}
            .user-invoice-data thead tr:first-child {border-top: none;}
            .user-invoice-data thead tr, .user-invoice-data tbody tr {border: 1px solid #ddd;}
            .user-invoice-data tr td {text-align: left;}
            .user-invoice-data tr td.index {width: 25px;}
            .user-invoice-data tr td.product-size {width: 90px;}

            .user-invoice-cal-total tbody tr td {text-align: left; border: none;}
            .user-invoice-cal-total tbody tr:first-child {border-top: none;}
            .user-invoice-cal-total tbody tr {border: 1px solid #ddd;}
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
                    <th class="title-name" colspan="2">Estimate</th>
                </tr>
                <tr>
                    <th>Name: {{(isset($allorder->customer->tally_name) && $allorder->customer->tally_name != "") ? $allorder->customer->tally_name : $allorder->customer->owner_name}}</th>
                    <th>Date: {{date('F d, Y')}}</th>
                </tr>
                <tr>
                    <th>Delivery @: {{($allorder->delivery_order->delivery_location_id!=0) ? $allorder->delivery_order->location->area_name : $allorder->delivery_order->other_location }}</th>
                    <th>Challan Serial: {{ $allorder->serial_number }}</th>
                </tr>
                <tr>
                    <th>Time Created: {{ date("h:i:sa", strtotime($allorder->created_at))}}</th>
                    <th>Time Print: {{ date("h:i:sa") }}</th>
                </tr>
                <?php
                    $is_allincludive = 0;
                    foreach($allorder['delivery_challan_products'] as $prod){
                        if(isset($prod->vat_percentage) && $prod->vat_percentage>0){
                            $is_allincludive = 1;
                        }
                    }
                ?>
                @if($is_allincludive)
                <tr>
                    <th>Empty Truck Weight: {{isset($allorder->delivery_order->empty_truck_weight)?$allorder->delivery_order->empty_truck_weight:'0'}}</th>
                    <th>Final Truck Weight: {{isset($allorder->delivery_order->final_truck_weight)?$allorder->delivery_order->final_truck_weight:'0' }}</th>
                </tr>
                @endif
            </thead>
        </table>

            <?php
            $cust_id = $allorder->customer_id;
            $order_id = $allorder->order_id;
            // $state = \App\Customer::where('id',$cust_id)->first()->state;
            // $local_state = App\States::where('id',$state)->first()->local_state;
            $loc_id = \App\DeliveryOrder::where('customer_id',$cust_id)->where('order_id',$order_id)->first();
            $state = \App\DeliveryLocation::where('id',$loc_id->delivery_location_id)->first();
            $local = \App\States::where('id',$state->state_id)->first();
            $local_state = $local->local_state;
            ?>

        <table class="user-invoice-data">
            <thead>
                <tr>
                    <td class="index">Sr.</td>
                    <td class="product-size">Size</td>
                    <td>HSN</td>
                    <td>Pcs</td>
                    <td>Qty</td>
                    @if(isset($allorder['delivery_challan_products'][0]->vat_percentage) && $allorder['delivery_challan_products'][0]->vat_percentage > 0)
                        @if($local_state == 1)
                            <td>SGST</td>
                            <td>CGST</td>
                        @else
                            <td>IGST</td>
                        @endif   
                    @else  
                        <td>GST</td>
                    @endif   
                    <td>Rate</td>
                    <td>Amount</td>
                </tr>
            </thead>
            <?php
                $i = 1;
                $total_price = 0;
//                $total_qty = 0;
                $loading_vat_amount = ($allorder->loading_charge * $allorder->loading_vat_percentage) / 100;
                $freight_vat_amount = ($allorder->freight * $allorder->freight_vat_percentage) / 100;
                $discount_vat_amount = ($allorder->discount * $allorder->discount_vat_percentage) / 100;
                $final_vat_amount = 0; 
                $final_total_amt = 0;
            ?>
            @foreach($allorder['delivery_challan_products'] as $prod)
                @if($prod->order_type == 'delivery_challan')
                <tbody>
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $prod->order_product_all_details->alias_name }}</td>
                        <td>{{ $prod->order_product_all_details->hsn_code }}</td>
                        <td>{{ $prod->actual_pieces }}</td>
                        <td>{{ round($prod->actual_quantity) }}</td>
                        <?php

                        $productsub = \App\ProductSubCategory::where('id',$prod['product_category_id'])->first();
                        $product_cat = \App\ProductCategory::where('id',$productsub->product_category_id)->first();
                        $sgst = 0;
                        $cgst = 0;
                        $igst = 0;
                        $rate = $prod->price;
                        if(isset($prod->vat_percentage) && $prod->vat_percentage > 0){
                            if($product_cat->hsn_code){
                                $hsn_det = \App\Hsn::where('hsn_code',$product_cat->hsn_code)->first();
                                $gst_det = \App\Gst::where('gst',$hsn_det->gst)->first();
                                if($local_state == 1){
                                    $sgst = $gst_det->sgst;
                                    $cgst = $gst_det->cgst;
                                }
                                else{
                                    $igst = $gst_det->igst;
                                }
                            }
                        }
                        else{
                            $igst = 0;
                        }
                        ?>
                        @if(isset($prod->vat_percentage) && $prod->vat_percentage!='')
                            @if($local_state == 1)
                                <td>{{$sgst}}</td>
                                <td>{{$cgst}}</td>
                            @else
                                <td>{{$igst}}</td>
                            @endif
                        @else
                            <td>{{$igst}}</td>
                        @endif
                        <td><?php echo $rate = $prod->price; ?></td>
                        <td><?php $total_price = $rate * $prod->actual_quantity; 
                            $final_total_amt += $total_price;
                            ?>
                            {{ ($rate * $prod->actual_quantity) }}</td>
                    </tr>
                </tbody>
                <?php
                $total_pr = $sgst + $cgst + $igst;
                
                $total_vat_amount = ($total_price * $total_pr) / 100;
                // $total_price += $total_price;
                $final_vat_amount += ($total_vat_amount + $loading_vat_amount + $freight_vat_amount) + $discount_vat_amount;

                ?>
                @endif
            @endforeach
        </table>
        
        <table class="user-invoice-cal-total">
            <tbody>
                <tr>
                    <td  class="spacing" valign="top">
                        <div>Total Quantity: <span class="total-qty">{{ round($allorder->delivery_challan_products->sum('actual_quantity'), 2) }}</span></div>
                        <div>Remarks: <span class="remarks">{{$allorder->remarks}}</span></div>
                    </td>
                    <td>
                        <table class="sm-table-data">
                            <tbody>
                                <tr>
                                    <td class="lable">Total</td>
                                    <td class="total-count">{{ round($final_total_amt, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Loading</td>
                                    <td class="total-count">
                                    <?php
                                    $loading_charge = $allorder->loading_charge;
                                    $loading_vat = $allorder->loading_vat_percentage;
                                    ?>
                                    {{($loading_charge != "")?round($loading_charge,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Freight</td>
                                    <td class="total-count">
                                    {{($allorder->freight != "")?round($allorder->freight,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Discount</td>
                                    <td class="total-count">
                                    {{($allorder->discount != "")?round($allorder->discount,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Total</td>
                                    <td class="total-count">
                                    <?php 
                                    $with_total = $final_total_amt + $loading_charge + $allorder->freight + $allorder->discount; 
                                    ?>
                                    {{ round($with_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Total GST = @if($local_state == 1)
                                        SGST + CGST
                                    @else
                                        IGST
                                    @endif</td>
                                    <td class="total-count">
                                    <?php
                                        $vat = $final_vat_amount;
                                    ?>
                                    {{ round($vat,5) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Round Off</td>
                                    <td class="total-count">
                                    <?php
                                        $roundoff = $vat;
                                    ?>
                                    {{ round($roundoff,2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">GT</td>
                                    <td class="total-count">
                                    <?php

                                        $grand_price = $final_total_amt;

                                        if($loading_charge != ""){
                                            $grand_price = $grand_price + $loading_charge;
                                        }

                                        if($allorder->freight != ""){
                                            $grand_price = $grand_price + $allorder->freight;
                                        }

                                        if($allorder->discount != ""){
                                            $grand_price = $grand_price + $allorder->discount;
                                        }
                                    ?>
                                    {{ round($grand_price + $final_vat_amount, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
            if (!function_exists('convert_number')) {

                function convert_number($number) {

                    $number = $number;
                    $no = round($number);
                    $point = round($number - $no, 2) * 100;
                    $hundred = null;
                    $digits_1 = strlen($no);
                    $i = 0;
                    $str = array();
                    $words = array('0' => '', '1' => 'one', '2' => 'two',
                        '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
                        '7' => 'seven', '8' => 'eight', '9' => 'nine',
                        '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
                        '13' => 'thirteen', '14' => 'fourteen',
                        '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
                        '18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
                        '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
                        '60' => 'sixty', '70' => 'seventy',
                        '80' => 'eighty', '90' => 'ninety');
                    $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
                    while ($i < $digits_1) {
                        $divider = ($i == 2) ? 10 : 100;
                        $number = floor($no % $divider);
                        $no = floor($no / $divider);
                        $i += ($divider == 10) ? 1 : 2;
                        if ($number) {
                            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                            $str [] = ($number < 21) ? $words[$number] .
                                    " " . $digits[$counter] . $plural . " " . $hundred :
                                    $words[floor($number / 10) * 10]
                                    . " " . $words[$number % 10] . " "
                                    . $digits[$counter] . $plural . " " . $hundred;
                        } else
                            $str[] = null;
                    }
                    $str = array_reverse($str);
                    $result = implode('', $str);
                    if (($point % 10) == 0) {
                        $points = $words[$point];
                    } else {
                        $points = ($point) ? "." . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
                    }
                    echo "<pre>";
                    print_r($points);
                    echo "<pre>";
                    exit();
                    if (strlen($points) > 0) {
                        return $result . "Rupees  " . ucwords($points) . " Paise";
                    } else {
                        return $result . "Rupees  ";
                    }
                }

            }
        ?>
    </body>
</html>