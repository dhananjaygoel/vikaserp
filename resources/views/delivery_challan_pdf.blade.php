<!DOCTYPE html>
<html>
    <body>
        <style>
            body{
                font-size: 10px;
                font-family: Helvetica !important;
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
            .divCell2{
                float:left;
                display:table-column;
                width:6%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell{
                float:left;
                display:table-column;
                width:13.2%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .bob{
                border-top: none !important;
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
            .total-desc{
                width: 65%;
                float: left;

            }
            .total{
                width:100%;
                float: left;
            }
            .invoice{
                width:80%;
                margin-left: 10%;
                border: 1px solid #ccc;
                float: left;
                padding: 0px;
                overflow: hidden;
            }
            .detail_table{
                border: 1px solid #ccc;
                margin-left: 80px;
                margin-top: 5px;
                margin-bottom: 10px;
                width:500px;
                padding: 0 !important;
            }
            .detail_table th{
                border-bottom: 1px solid #ccc;
                border-right: 1px solid #ccc;
                line-height: 12px;
                /*                padding-left: 5px;*/
                width: 70px;
                margin-left:0 !important;
                padding-left:0 !important;
            } 
            .detail_table td{
                line-height: 12px;
                padding-left: 5px;
                border-right: 1px solid #ccc;
                text-align: center;
            } 
            .detail_table td:last-child{
                border-right: none;
            }
            .detail_table th:last-child{
                border-right: none;
            }
            .time td{
                width: 100%;
                float: left;
                padding: 10px 0px 10px 5px;
            }
            .time-gen{
                width: 50%;
                float: left;
                line-height: 20px;
                margin-left: 5px;
            }
            .time-prnt{
                width: 48%;
                float: left;
                line-height: 20px;
                margin-left:6px;
            }
            .delivery-details{
                width: 100%;
                padding: 10px 0px 10px 5px;
                float: left;
                border-bottom: 1px solid #ccc;
            }
            .delivery{
                width: 50%;
                float: left;
                position: relative;
            }
            .estmt-no {
                width: 50%;
                float: left;
                position: relative;
            }
            .name-date{
                width: 100%;
                padding: 10px 0px 10px 5px;
                float: left;
                position: relative;
                border-bottom: 1px solid #ccc;
            }
            .name{
                width: 50%;
                float: left;
                position: relative;
            }
            .date{
                width: 50%;
                float: left;
                position: relative;
            }

            .title{
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #ccc;
                padding: 10px 0px 10px 0px;
            }
            dt{
                width: 50%;
                float: left;
                text-align: left;
            }
            dl{
                text-align: right;
                margin: 0px;
            }
            .quantity{
                height: 10px;
                padding: 5px 5px 0 7px;
            }
            .label{
                width: 50%;
                text-align: left;
                float: left;
                border-top: 1px solid rgb(204, 204, 204);
                border-left: 1px solid rgb(204, 204, 204);
                /*padding: 0 0px 0 5px;*/
            }
            .label:first-child{
                border-top: none;
            }
            .value:first-child {
                border: none;
            }
            .value{
                width: 48%;
                text-align: right;
                float: left;
                border-top: 1px solid rgb(204, 204, 204);
                border-left: 1px solid #ccc;
                /*padding: 0 5px 0 0px;*/
            }
            .details_table{
                width:500px;
                border: 1px solid rgb(204, 204, 204);
                margin: 10px 10px 10px 20px;
            }
            .details_table th{
                border-bottom: 1px solid rgb(204, 204, 204);
                margin-left: -10px !important;
            }
            .details_table td{
                text-align: center;
            }
            .table_label{
                width: 40% !important;
                float: left;
                padding: 5px 0;
            }
            .table_value{
                width: 40% !important;
                float: left;
                padding: 5px 0;
            }
            .total_table{
                width: 100%;
                float: left;
            }
            .row{
                width: 100%;
                border-bottom: 1px solid rgb(204, 204, 204);
            }
            .row:last-child{
                border-bottom: 0;
            }
            .calculation_table{
                width:300px;
                border: 1px solid rgb(204, 204, 204);
                margin:10px; 
            }
            .calculation_table tr td{
                border-bottom: 1px solid rgb(204, 204, 204);
                margin-left: -5px;
            }
            .no_border td{
                border-bottom: 0 !important;
            }
            
        </style>
        <div class="invoice">
            <div class="title">Estimate</div>
            <div class="name-date">
                <div>
                    <div class="name">
                        Name: {{(isset($allorder->customer->tally_name) && $allorder->customer->tally_name != "") ? $allorder->customer->tally_name : $allorder->customer->owner_name}}
                    </div>
                    <div class="date">Date: {{date('F d, Y')}}</div>
                </div>
            </div>
            <div class="delivery-details">
                <div class="delivery">
                    Delivery @: {{($allorder->delivery_order->delivery_location_id!=0) ? $allorder->delivery_order->location->area_name : $allorder->delivery_order->other_location }}
                </div>
                <div class="estmt-no">Challan Serial: {{ $allorder->serial_number }}</div>
            </div>
            <div class="time">
                <div class="time-gen"> Time Created: {{ date("h:i:sa", strtotime($allorder->created_at))}}</div>
                <div class="time-prnt">Time Print: {{ date("h:i:sa") }}</div>
            </div>
            <div class="divTable">
                <div class="headRow">
                    <div class="divCell2">Sr.</div>
                    <div class="divCell">Size</div>
                    <div class="divCell">Pcs</div>
                    <div class="divCell">Qty</div>
                    <div class="divCell">GST</div>
                    <div class="divCell">Rate</div>
                    <div class="divCell">Amount</div>
                </div>
                <?php
                $i = 1;
                $total_price = 0;
//                $total_qty = 0;
                $loading_vat_amount = ($allorder->loading_charge * $allorder->loading_vat_percentage) / 100;
                $freight_vat_amount = ($allorder->freight * $allorder->freight_vat_percentage) / 100;
                $discount_vat_amount = ($allorder->discount * $allorder->discount_vat_percentage) / 100;
                ?>
                @foreach($allorder['delivery_challan_products'] as $prod)
                @if($prod->order_type == 'delivery_challan')
                <div class="divRow">
                    <div class="divCell2">{{ $i++ }}</div>
                    <div class="divCell">{{ $prod->order_product_all_details->alias_name }}</div>
                    <div class="divCell">{{ $prod->actual_pieces }}</div>
                    <div class="divCell">{{ round($prod->actual_quantity) }}</div>
                    <div class="divCell">{{(isset($prod->vat_percentage) && $prod->vat_percentage!='')?round($allorder->vat_percentage):''}}</div>
                    <div class="divCell"><?php echo $rate = $prod->price; ?></div>
                    <div class="divCell">
                        <?php $total_price += $rate * $prod->actual_quantity; ?>
                        {{ ($rate * $prod->actual_quantity) }}
                    </div>
                </div>
                <?php
                $total_vat_amount = ($total_price * $allorder->vat_percentage) / 100;

                $final_vat_amount = ($total_vat_amount + $loading_vat_amount + $freight_vat_amount) + $discount_vat_amount;
                ?>
                @endif
                @endforeach
            </div>
            <div class="footer">
                <div class="total-desc">
                    <div class="quantity">
                        Total Quantity: {{ round($allorder->delivery_challan_products->sum('actual_quantity'), 2) }}
                    </div>
                    <table class="table-responsive details_table">
                        <tr>
                            <th> Total Amount </th>
                            <th> Total GST </th>
                            <th> Total Inc. GST </th>
                        </tr>
                        <tr class="secondrow">
                            <td> {{ round($total_price+$allorder->loading_charge+ $allorder->freight + $allorder->discount, 2) }}  </td>
                            <td> <?php
                                $vat = $final_vat_amount;
                                ?>
                                {{ round($vat,2) }} </td>
                            <td> {{ round($allorder->grand_price, 2) }} </td>
                        </tr>
                    </table>
                    <div>
                        &nbsp; Remarks : {{$allorder->remarks}}
                    </div>
                    <!--                    <div class="ruppes grand_price">
                                            &nbsp; <?php $gt = round($allorder->grand_price, 2) ?>
                                            Rupees <?php //echo ucwords(str_replace(".", "", convert_number($allorder->grand_price)));        ?> Only.
                                        </div>-->
                </div>
                <table class="calculation_table">
                    <tr>
                        <td class="total_table_label">Total</td>
                        <td>{{ round($total_price, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="total_table_label">Loading</td>
                        <?php
                        $loading_charge = $allorder->loading_charge;
                        $loading_vat = $allorder->loading_vat_percentage;
                        ?>
                        <td>{{($loading_charge != "")?round($loading_charge,2):0}}</td>
                    </tr>
                    <tr>
                        <td class="total_table_label">Freight</td>
                        <td>{{($allorder->freight != "")?round($allorder->freight,2):0}}</td>
                    </tr>
                    <tr>
                        <td class="total_table_label">Discount</td>
                        <td>{{($allorder->discount != "")?round($allorder->discount,2):0}}</td>
                    </tr>
                    <tr>
                        <td class="total_table_label">Total</td>
                        <?php $with_total = $total_price + $loading_charge + $allorder->freight + $allorder->discount; ?>
                        <td>{{ round($with_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="total_table_label">Vat</td>
                        <?php $vat = $final_vat_amount; ?>
                        <td>{{ round($vat,2) }}</td>
                    </tr>
                    <tr>
                        <td class="total_table_label">Round Off</td>
                        <?php
                        if (isset($allorder->round_off) && ($allorder->round_off != "")) {
                            $roundoff = $allorder->round_off;
                        } else {
                            $roundoff = 0;
                        }
                        ?>
                        <td>{{ round($roundoff,2) }}</td>
                    </tr>
                    <tr class="no_border">
                        <td class="total_table_label">GT</td>
                        <?php
                        if (isset($allorder->grand_price) && ($allorder->grand_price != "")) {
                            $grand_price = $allorder->grand_price;
                        } else {
                            $grand_price = 0;
                        }
                        ?>
                        <td>{{ round($grand_price, 2) }}</td>
                        </div>
                </table>
            </div>
        </div>
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