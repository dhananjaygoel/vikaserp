<!DOCTYPE html>
<html>
    <head>
        <title>Purchase Challan </title>
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
                width:15.2%;         
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell2{
                float:left;
                display:table-column;         
                width:10%;         
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .bob
            {
                border-top: none !important;
            }
            .divCell:last-child{
                border: none;
            }
            .divRow:last-child            {
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
                width: 70%;
                float: left;
            }
            .total
            {
                width:30%;
                float: left;
            }
            .invoice
            {
                width:95%;
                /*margin-left: 20%;*/
                border: 1px solid #ccc;
                float: left;
                padding: 0px;
                overflow: hidden;
            }

            .time
            {
                width: 100%;
                float: left;
                padding: 10px 0px 10px 0px;
            }

            .time-gen
            {
                width: 50%;
                float: left;
            }
            .time-prnt
            {
                width: 50%;
                float: left;
            }

            .delivery-details
            {
                width: 100%;
                padding: 10px 0px 10px 0px;
                float: left;
                border-bottom: 1px solid #ccc;
            }
            .delivery
            {
                width: 50%;
                float: left;
                position: relative;
            }
            .estmt-no
            {
                width: 50%;
                float: left;
                position: relative;
            }
            .name-date
            {
                width: 100%;            
                padding: 10px 0px 10px 0px;
                float: left;
                position: relative;
                border-bottom: 1px solid #ccc;
            }
            .name
            {
                width: 50%;
                float: left;
                position: relative;
            }
            .date
            {
                width: 50%;
                float: left;
                position: relative;
            }

            .title
            {
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
            .quantity
            {
                height: 80px;
            }
            .label
            {
                width: 50%;
                text-align: left;
                float: left;
                border-top: 1px solid rgb(204, 204, 204);
                border-left: 1px solid rgb(204, 204, 204);
            }
            .label:first-child
            {
                border-top: none;
            }
            .value:first-child
            {
                border: none;
            }
            .value
            {
                width: 48%;
                text-align: right;
                float: left;
                border-top: 1px solid rgb(204, 204, 204);
                border-left: 1px solid #ccc;
            }

        </style>
        <div class="invoice">
            <div class="title">
                Purchase Challan
            </div>
            <div class="name-date">
                <div class="">
                    <div class="name">
                        Tally Name: {{ $purchase_challan['supplier']->owner_name }}
                    </div>
                    <div class="date">
                        Date: {{ date('d F, Y') }}
                    </div>
                </div>

            </div>
            <div class="delivery-details">
                <div class="delivery">
                    Delivery @: {{ $purchase_challan['delivery_location']->area_name }}
                </div>

                <div class="estmt-no">
                    Estmt No: 
                </div>
            </div>

            <div class="time">
                <div class="time-gen">
                    Time Gen:  {{ date("h:i:sa") }}
                </div>
                <div class="time-prnt">
                    Time Prnt: {{ date("h:i:sa") }}
                </div>
            </div>

            <div class="divTable">
                <div class="headRow">
                    <div  class="divCell2">Sr.</div>
                    <div  class="divCell">Size</div>
                    <div  class="divCell">Pcs</div>
                    <div  class="divCell">Qty</div>
                    <div  class="divCell">Rate</div>
                    <div  class="divCell">Amount</div>                
                </div>
                <?php
                $i = 1;
                $total_qty = 0;
                $total_price = 0;
                ?>

                @foreach($purchase_challan['all_purchase_products'] as $prod)
                <div class="divRow">
                    <div class="divCell2">{{ $i++ }}</div>
                    <div class="divCell">{{ $prod['purchase_product_details']->alias_name }}</div>
                    <div class="divCell">{{ $prod->actual_pieces}}</div>
                    <div class="divCell">{{ $prod->quantity }}</div>
                    <div class="divCell">{{ $prod->price }}</div>
                    <div class="divCell">{{ $prod->price * $prod->quantity }}</div>                
                </div>
                <?php
                $total_price = $prod->price * $prod->quantity;
                if ($prod['unit']->id == 1) {
                    $total_qty += $prod->quantity;
                }

                if ($prod['unit']->id == 2) {
                    $total_qty += ($prod->quantity * $prod['purchase_product_details']->weight);
                }

                if ($prod['unit']->id == 3) {
                    $total_qty += (($prod->present_shipping / $prod['purchase_product_details']->standard_length ) * $prod['purchase_product_details']->weight);
                }

//                $total_price = $total_price + ($prod->price * $prod->quantity);
                ?>
                @endforeach

            </div>
            <div class="footer">
                <div class="total-desc">
                    <div class="quantity">
                        Total Quantity: {{ round($total_qty, 2) }}
                    </div>
                    <div class="ruppes">
                        Rupees <?php echo convert_number_to_words(round($purchase_challan->grand_total, 2)); ?> Only.
                    </div>
                </div>
                <div class="total">                 
                    <div class="">
                        <div class="label">Total</div>
                        <div class="value bob">{{ $total_price }}</div>
                        <div class="label">Frt</div>
                        <div class="value">
                            @if($purchase_challan->freight != "")
                            {{round($purchase_challan->freight, 2)}}
                            @else
                            0
                            @endif 
                        </div>
                        <div class="label">disc.</div>
                        <div class="value">
                            @if($purchase_challan->discount != "")
                            {{round($purchase_challan->discount,2)}}
                            @else
                            0
                            @endif 
                        </div>
                        <div class="label">Total</div>
                        <div class="value">{{ round($total_price + $purchase_challan->freight + $purchase_challan->discount, 2) }}</div>
                        <div class="label">Vat</div>
                        <div class="value">
                            @if($purchase_challan->vat_percentage != "")
                            {{round($purchase_challan->vat_percentage, 2)}} %
                            @else
                            0
                            @endif 
                        </div>
                        <div class="label">Round Off</div>
                        <div class="value">
                            @if($purchase_challan->round_off != "")
                            {{round($purchase_challan->round_off,2)}}
                            @else
                            0
                            @endif 
                        </div>
                        <div class="label">GT</div>
                        <div class="value">
                            {{ round($purchase_challan->grand_total, 2)}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php

        function convert_number_to_words($number) {

            $hyphen = '-';
            $conjunction = ' and ';
            $separator = ', ';
            $negative = 'negative ';
            $decimal = ' point ';
            $dictionary = array(
                0 => 'zero',
                1 => 'one',
                2 => 'two',
                3 => 'three',
                4 => 'four',
                5 => 'five',
                6 => 'six',
                7 => 'seven',
                8 => 'eight',
                9 => 'nine',
                10 => 'ten',
                11 => 'eleven',
                12 => 'twelve',
                13 => 'thirteen',
                14 => 'fourteen',
                15 => 'fifteen',
                16 => 'sixteen',
                17 => 'seventeen',
                18 => 'eighteen',
                19 => 'nineteen',
                20 => 'twenty',
                30 => 'thirty',
                40 => 'fourty',
                50 => 'fifty',
                60 => 'sixty',
                70 => 'seventy',
                80 => 'eighty',
                90 => 'ninety',
                100 => 'hundred',
                1000 => 'thousand',
                1000000 => 'million',
                1000000000 => 'billion',
                1000000000000 => 'trillion',
                1000000000000000 => 'quadrillion',
                1000000000000000000 => 'quintillion'
            );

            if (!is_numeric($number)) {
                return false;
            }

            if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
                // overflow
                trigger_error(
                        'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
                );
                return false;
            }

            if ($number < 0) {
                return $negative . convert_number_to_words(abs($number));
            }

            $string = $fraction = null;

            if (strpos($number, '.') !== false) {
                list($number, $fraction) = explode('.', $number);
            }

            switch (true) {
                case $number < 21:
                    $string = $dictionary[$number];
                    break;
                case $number < 100:
                    $tens = ((int) ($number / 10)) * 10;
                    $units = $number % 10;
                    $string = $dictionary[$tens];
                    if ($units) {
                        $string .= $hyphen . $dictionary[$units];
                    }
                    break;
                case $number < 1000:
                    $hundreds = $number / 100;
                    $remainder = $number % 100;
                    $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                    if ($remainder) {
                        $string .= $conjunction . convert_number_to_words($remainder);
                    }
                    break;
                default:
                    $baseUnit = pow(1000, floor(log($number, 1000)));
                    $numBaseUnits = (int) ($number / $baseUnit);
                    $remainder = $number % $baseUnit;
                    $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                    if ($remainder) {
                        $string .= $remainder < 100 ? $conjunction : $separator;
                        $string .= convert_number_to_words($remainder);
                    }
                    break;
            }

            if (null !== $fraction && is_numeric($fraction)) {
                $string .= $decimal;
                $words = array();
                foreach (str_split((string) $fraction) as $number) {
                    $words[] = $dictionary[$number];
                }
                $string .= implode(' ', $words);
            }

            return $string;
        }
        ?>
    </body>
</html>