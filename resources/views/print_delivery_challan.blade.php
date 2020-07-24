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

            .sm-table-data-left {width: 350px; float: left;margin-top:1em;}
            .sm-table-data-left tbody tr td {text-align: left;}
            .sm-table-data-left tbody tr:last-child {border-bottom: 1px solid #ddd;}
            .sm-table-data-left tbody tr td {border: 1px solid #ddd;}
            .sm-table-data-left th, .sm-table-data-left td {padding: 10px;}
        </style>

        <table class="user-invoice-details">
            <thead>
                <?php
                    if(isset($allorder->is_gst) && $allorder->is_gst == 1){
                        $title = 'Delivery Challan';
                    }else{
                        $title = 'Estimate';
                    }
                ?>
                <tr>
                    <th class="title-name" colspan="2">{{ $title }}</th>
                </tr>
                <tr>
                    <th>Name: {{(isset($allorder->customer->tally_name) && $allorder->customer->tally_name != "") ? $allorder->customer->tally_name : $allorder->customer->owner_name}}</th>
                    <th>Date: {{date('F d, Y')}}</th>
                </tr>
                <tr>
                    <th>Delivery @: {{isset($allorder->delivery_order->delivery_location_id)&&($allorder->delivery_order->delivery_location_id!=0) ? $allorder->delivery_order->location->area_name : (isset($allorder->delivery_order->other_location)?$allorder->delivery_order->other_location:"") }}</th>
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
                    if($is_allincludive == 1) {
                ?>
                
                <tr>
                    <th>Empty Truck Weight: {{isset($allorder->delivery_order->empty_truck_weight)?$allorder->delivery_order->empty_truck_weight:'0'}} KG</th>
                    <th>Final Truck Weight: {{isset($allorder->delivery_order->final_truck_weight)?$allorder->delivery_order->final_truck_weight:'0' }} KG</th>
                </tr>
                    <?php } ?> 
                    
            </thead>
        </table>

            <?php
            $cust_id = $allorder->customer_id;
            $order_id = $allorder->order_id;
            // $state = \App\Customer::where('id',$cust_id)->first()->state;
            // $local_state = App\States::where('id',$state)->first()->local_state;
            $loc_id = \App\DeliveryOrder::where('customer_id',$cust_id)->where('order_id',$order_id)->first();
            $state = \App\DeliveryLocation::where('id',isset($loc_id->delivery_location_id)?$loc_id->delivery_location_id:0)->first();
            $local = \App\States::where('id',isset($state->state_id)?$state->state_id:0)->first();
            $local_state = isset($local->local_state)?$local->local_state:0;
            ?>

        <table class="user-invoice-data">
            <thead>
                <tr>
                    <td class="index">Sr.</td>
                    <td class="product-size">Size</td>
                    <td>HSN</td>
                    <td>Pcs</td>
                    <td>Qty</td>
                    @if((isset($allorder['delivery_challan_products'][0]->vat_percentage) && $allorder['delivery_challan_products'][0]->vat_percentage > 0) && empty($allorder['delivery_order']->vat_percentage))
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
                // $loading_vat_amount = ($allorder->loading_charge * $allorder->loading_vat_percentage) / 100;
                // $freight_vat_amount = ($allorder->freight * $allorder->freight_vat_percentage) / 100;
                // $discount_vat_amount = ($allorder->discount * $allorder->discount_vat_percentage) / 100;
                if(isset($allorder['delivery_challan_products'][0]->vat_percentage) && $allorder['delivery_challan_products'][0]->vat_percentage > 0){
                    $loading_vat = 18;
                }else{
                    $loading_vat = 0;
                }
                $loading_vat_amount = ((float)$allorder->loading_charge * (float)$loading_vat) / 100;
                $freight_vat_amount = ((float)$allorder->freight * (float)$loading_vat) / 100;
                $discount_vat_amount = ((float)$allorder->discount * (float)$loading_vat) / 100;
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
                        <td>{{ round($prod->actual_quantity) }} KG</td>
                        <?php

                        $productsub = \App\ProductSubCategory::where('id',$prod['product_category_id'])->first();
                        $product_cat = \App\ProductCategory::where('id',$productsub->product_category_id)->first();
                        $sgst = 0;
                        $cgst = 0;
                        $igst = 0;
                        $gst = 0;
                        $rate = $prod->price;
                        if((isset($prod->vat_percentage) && $prod->vat_percentage > 0) && empty($allorder['delivery_order']->vat_percentage)){
                            if($product_cat->hsn_code){
                                $hsn_det = \App\Hsn::where('hsn_code',$product_cat->hsn_code)->first();
                                $gst_det = \App\Gst::where('gst',$hsn_det->gst)->first();
                                if($local_state == 1){
                                    $sgst = (float)$gst_det->sgst;
                                    $cgst = (float)$gst_det->cgst;
                                }
                                else{
                                    $igst = (float)(isset($gst_det->igst)?$gst_det->igst:0);
                                }
                            }
                        }
                        else{
                            $gst = isset( $allorder['delivery_order']->vat_percentage)?$allorder['delivery_order']->vat_percentage:0;
                        }
                        ?>
                        @if((isset($prod->vat_percentage) && $prod->vat_percentage>0) && empty($allorder['delivery_order']->vat_percentage))
                            @if($local_state == 1)
                                <td>{{$sgst}}</td>
                                <td>{{$cgst}}</td>
                            @else
                                <td>{{$igst}}</td>
                            @endif
                        @else
                            <td>{{$gst}}</td>
                        @endif
                        <td>₹ <?php echo $rate = (float)(isset($prod->price) && $prod->price !=0) ? $prod->price : $prod['order_product_all_details']->product_category['price']; ?></td>
                        <td><?php $total_price = (float)$rate * (float)$prod->actual_quantity; 
                            $final_total_amt += (float)$total_price;
                            ?>
                            ₹ {{ ($rate * (float)$prod->actual_quantity) }}</td>
                    </tr>
                </tbody>
                <?php
                // $total_pr = $sgst + $cgst + $igst + $gst;
                if((isset($prod->vat_percentage) && $prod->vat_percentage > 0) && empty($allorder['delivery_order']->vat_percentage)){
                    if($local_state == 1){
                        $total_sgst_amount = ((float)$total_price * (float)$sgst) / 100;
                        $total_cgst_amount = ((float)$total_price * (float)$cgst) / 100;
                        $total_vat_amount1 = (round((float)$total_sgst_amount,2) + round((float)$total_cgst_amount,2));
                    } else {
                        $total_igst_amount = ((float)$total_price * (float)$igst) / 100;
                        $total_vat_amount1 = round((float)$total_igst_amount,2);
                    }
                } else{
                    $total_gst_amount = ((float)$total_price * (float)$gst) / 100;
                    $total_vat_amount1 = round((float)$total_gst_amount,2);
                }
                // $total_vat_amount1 = ($total_price * $total_pr) / 100;
                $total_vat_amount = $total_vat_amount1;
                // $total_price += $total_price;
                // $final_vat_amount += ($total_vat_amount + $loading_vat_amount + $freight_vat_amount) + $discount_vat_amount;
                $final_vat_amount += ($total_vat_amount);

                ?>
                @endif
            @endforeach
        </table>
        
        <table class="user-invoice-cal-total">
            <tbody>
                <tr>
                    <td  class="spacing" valign="top">
                        <div>Total Quantity: <span class="total-qty">{{ round($allorder->delivery_challan_products->sum('actual_quantity'), 2) }} KG</span></div>
                        <div>Remarks: <span class="remarks">{{$allorder->remarks}}</span></div>
                        <table class="sm-table-data-left">
                            <tbody>
                                <tr>
                                    <td class="lable">HSN CODE</td>
                                    <td class="label">Qty</td>
                                    <td class="label">Amount</td>
                                    <td class="label">GST</td>
                                    <td class="label">Total Inc GST</td>
                                </tr>
                                <?php
                                    $gst_percentage=0;
                                    $total_amount=0;
                                    $total_qty=0;
                                    $total_inc_gst=0;
                                ?>
                                @foreach($allorder['hsn'] as $hsn)
                                <tr>
                                    <td>{{ $hsn['id'] }}</td>
                                    <td>{{ round($hsn['actual_quantity'], 2) }} KG</td>
                                    <td>₹ {{ round($hsn['amount'], 2) }}</td>
                                    <td>{{ round($hsn['vat_percentage'], 2) }}</td>
                                    <td>₹ {{ round(($hsn['amount'] +$hsn['vat_amount']), 2) }}</td>
                                </tr>
                                <?php
                                    $gst_percentage = $hsn['vat_percentage'];
                                    $total_amount += $hsn['amount'];
                                    $total_qty += $hsn['actual_quantity'];
                                    $total_inc_gst += $hsn['amount'] +$hsn['vat_amount'];
                                ?>
                                @endforeach
                                <tr>
                                    <td><b>Total</b></td>
                                    <td><b>{{ round($total_qty, 2) }} KG</b></td>
                                    <td><b>₹ {{ round($total_amount, 2) }}</b></td>
                                    <td><b>{{ round($gst_percentage, 2) }}</b></td>
                                    <td><b>₹ {{ round($total_inc_gst, 2) }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>
                        <table class="sm-table-data">
                            <tbody>
                                <tr>
                                    <td class="lable">Total</td>
                                    <td class="total-count">₹ {{ round($final_total_amt, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Loading</td>
                                    <td class="total-count">
                                    <?php
                                    $loading_charge = $allorder->loading_charge;
                                    $loading_vat = $allorder->loading_vat_percentage;
                                    ?>
                                    ₹ {{($loading_charge != "")?round($loading_charge,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Freight</td>
                                    <td class="total-count">
                                    ₹ {{($allorder->freight != "")?round($allorder->freight,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Discount</td>
                                    <td class="total-count">
                                    ₹ {{($allorder->discount != "")?round($allorder->discount,2):0}}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Total</td>
                                    <td class="total-count">
                                    <?php 
                                    $with_total = (float)$final_total_amt + (float)$loading_charge + (float)$allorder->freight + (float)$allorder->discount; 
                                    ?>
                                    ₹ {{ round($with_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">
                                    Total GST
                                    @if((isset($prod->vat_percentage) && $prod->vat_percentage>0) && empty($allorder->delivery_order->vat_percentage))
                                        @if($local_state == 1)
                                            = SGST + CGST
                                        @else
                                            = IGST
                                        @endif
                                    @else
                                            
                                    @endif
                                    </td>
                                    <td class="total-count">
                                    <?php
                                        $vat = $final_vat_amount + round($loading_vat_amount,2) + round($freight_vat_amount,2) + round($discount_vat_amount,2);
                                    ?>
                                    ₹ {{ round($vat,2) }}</td>
                                </tr>
                                <tr>
                                    <td class="lable">Round Off</td>
                                    <td class="total-count">
                                    <?php
                                        $roundoff = round($vat,2) + round($with_total,2);
                                        $roundoff = round($roundoff,0) - $roundoff;
                                    ?>
                                    ₹ {{ round($roundoff,2) }}</td>
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
                                    ₹ {{ round($grand_price + $vat, 0) }}</td>
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
                            $plural = (($counter = count((array)$str)) && $number > 9) ? 's' : null;
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