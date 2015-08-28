<!DOCTYPE html>
<html>
    <head>
        <title>Delivery Challan </title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <style>
            body{
                font-size: 8px;
                font-family: Bookman Old Style !important;
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
                width:15.2%;
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
                width:35%;
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

            .time{
                width: 100%;
                float: left;
                padding: 10px 0px 10px 5px;

            }

            .time-gen{
                width: 50%;
                float: left;
            }
            .time-prnt{
                width: 50%;
                float: left;
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
                height: 80px;
                padding: 5px 5px 0 10px;
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
                float: right;
                border-top: 1px solid rgb(204, 204, 204);
                border-left: 1px solid #ccc;
                /*padding: 0 5px 0 0px;*/
            }
        </style>
        <div class="invoice">
            <div class="title">
                Estimate
            </div>
            <div class="name-date">
                <div class="">
                    <div class="name">
                        Name:

                        @if($allorder['customer']->tally_name != "")
                        {{$allorder['customer']->tally_name}}
                        @else
                        {{$allorder['customer']->owner_name}}
                        @endif
                    </div>
                    <div class="date">
                        Date: {{date('d F, Y')}}
                    </div>
                </div>
            </div>
            <div class="delivery-details">
                <div class="delivery">
                    Delivery @: @if($allorder['delivery_order']->delivery_location_id!=0)
                    {{ $allorder['delivery_order']['location']->area_name }}
                    @else
                    {{ $allorder['delivery_order']->other_location }}
                    @endif
                </div>
                <div class="estmt-no">
                    Challan Serial: {{ $allorder->serial_number }}
                </div>
            </div>
            <div class="time">
                <div class="time-gen">
                    Time Created: {{ date("h:i:sa", strtotime($allorder->created_at))}}
                </div>
                <div class="time-prnt">
                    Time Print: {{ date("h:i:sa") }}
                </div>
            </div>
            <div class="divTable">
                <div class="headRow">
                    <div class="divCell2">Sr.</div>
                    <div class="divCell">Size</div>
                    <div class="divCell">Pcs</div>
                    <div class="divCell">Qty</div>
                    <div class="divCell">Rate</div>
                    <div class="divCell">Amount</div>
                </div>
                <?php
                $i = 1;
                $total_price = 0;
//                $total_qty = 0;
                ?>

                @foreach($allorder['delivery_challan_products'] as $prod)

                @if($prod->order_type == 'delivery_challan')
                <div class="divRow">
                    <div class="divCell2">{{ $i++ }}</div>
                    <div class="divCell">{{ $prod['order_product_details']->alias_name }}</div>
                    <div class="divCell">{{ $prod->actual_pieces }}</div>
                    <div class="divCell">{{ round($prod->actual_quantity) }}</div>
                    <div class="divCell"><?php echo $rate = $prod->price; ?></div>
                    <div class="divCell">
                        <?php $total_price += $rate * $prod->actual_quantity; ?>
                        {{ ($rate * $prod->actual_quantity) }}
                    </div>
                </div>
                <?php
//                if ($prod->unit_id == 1) {
//                    $total_qty += $prod->actual_quantity;
//                }
//
//                if ($prod->unit_id == 2) {
//                    echo $prod['order_product_details']['product_category']->weight;
//                    $total_qty += $prod->actual_quantity * $prod['order_product_details']->weight;
//                }
//
//                if ($prod->unit_id == 3) {
//                    echo $prod['order_product_details']['product_category']->standard_length;
//                    $total_qty += ($prod->actual_quantity / $prod['order_product_details']->standard_length) * $prod['order_product_details']->weight;
//                }
                ?>
                @endif
                @endforeach

            </div>
            <div class="footer">
                <div class="total-desc">
                    <div class="quantity">
                        Total Quantity: {{ round($allorder['delivery_challan_products']->sum('actual_quantity'), 2) }}
                    </div>
                    <div class="ruppes grand_price">
                        &nbsp;
                        <?php $gt = round($allorder->grand_price, 2) ?>
                        Rupees. <?php echo convert_number($gt); ?> Only
                    </div>
                </div>
                <div class="total">
                    <div class="">
                        <div class="label"> &nbsp; Total</div>
                        <div class="value bob"> {{ round($total_price, 2) }} &nbsp;</div>
                        <div class="label ">&nbsp; Loading</div>
                        <div class="value">
                            @if($allorder->loading_charge != "")
                            {{round($allorder->loading_charge,2)}}
                            @else
                            0
                            @endif
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Frt</div>
                        <div class="value">
                            @if($allorder->freight != "")
                            {{round($allorder->freight,2)}}
                            @else
                            0
                            @endif
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; disc.</div>
                        <div class="value">
                            @if($allorder->discount != "")
                            {{round($allorder->discount,2)}}
                            @else
                            0
                            @endif
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Total</div>
                        <div class="value">
                            <?php $with_total = $total_price + $allorder->loading_charge + $allorder->freight + $allorder->discount; ?>
                            {{ round($with_total, 2) }}
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Vat</div>
                        <div class="value">
                            @if($allorder->calculated_vat_price != "")
                            {{$allorder->calculated_vat_price}}
                            @else
                            0
                            @endif
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Round Off</div>
                        <div class="value">
                            @if($allorder->round_off != "")
                            {{round($allorder->round_off,2)}}
                            @else
                            0
                            @endif
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; GT</div>
                        <div class="value">
                            <?php
                            $gt = $total_price + $allorder->freight + $allorder->loading_charge + $allorder->round_off + $allorder->discount + ($allorder->vat_percentage / 100) * 100;
                            ?>
                            {{ round($allorder->grand_price, 2) }}
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php

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
            $points = ($point) ?
                    "." . $words[$point / 10] . " " .
                    $words[$point = $point % 10] : '';
//            return $result . "Rupees  " . $points . " Paise";
            return $result;
        }
        ?>
    </body>
</html>