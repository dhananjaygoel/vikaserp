<!DOCTYPE html>
<html>
    <body>
        <style>
            body{
                font-size: 10px;
                font-family: Arial !important;
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
            .detail_table{
                border: 1px solid #ccc;
                margin-left: 80px;
                margin-top: 5px;
                margin-bottom: 10px;
            }
            .detail_table th{
                border-bottom: 1px solid #ccc;
                border-right: 1px solid #ccc;
                line-height: 12px;
                padding-left: 5px;
                min-width: 70px;
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
            head{
                display: none;
            }
        </style>
        <div class="invoice">
            <div class="title">Estimate</div>
            <div class="name-date">
                <div class="">
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
            <div class="name-date">
                <div class="">
                    <div class="time-gen" style="margin-left: 0px;">Time Created: {{ date("h:i:sa", strtotime($allorder->created_at))}}</div>
                    <div class="time-prnt">Time Print: {{ date("h:i:sa") }}</div>
                </div>
            </div>
            <?php
            $is_allincludive = 0;
            foreach($allorder['delivery_challan_products'] as $prod){
                if(isset($prod->vat_percentage) && $prod->vat_percentage>0){
                    $is_allincludive = 1;
                }
            }
            ?>
            @if($is_allincludive)
                <div class="time">
                    <div class="time-gen">  Empty Truck Weight: {{ isset($allorder->delivery_order->empty_truck_weight)?$allorder->delivery_order->empty_truck_weight:'0'}}</div>
                    <div class="time-prnt">Final Truck Weight: {{isset($allorder->delivery_order->final_truck_weight)?$allorder->delivery_order->final_truck_weight:'0' }}</div>
                </div>
            @endif



            <br>
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
            <div class="divTable">
                <div class="headRow">
                    <div class="divCell2">Sr.</div>
                    <!--<div class="divCell2">Product</div>-->
                    <div class="divCell">Size</div>
                    <div class="divCell2">HSN</div>
                    <div class="divCell2">Pcs</div>
                    <div class="divCell2">Qty</div>
                    @if(isset($allorder['delivery_challan_products'][0]->vat_percentage) && $allorder['delivery_challan_products'][0]->vat_percentage > 0)
                        @if($local_state == 1)
                            <div  class="divCell2">SGST</div>
                            <div  class="divCell2">CGST</div>
                        @else
                            <div  class="divCell">IGST</div>
                        @endif
                    @else
                        <div  class="divCell">GST</div>
                    @endif

                    <div class="divCell2">Rate</div>
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
                    <!--                    <div class="divCell2">{{$prod->order_product_all_details->product_category->product_type->name}} </div>-->
                    <div class="divCell">{{ $prod->order_product_all_details->alias_name }}</div>
                    <div class="divCell2">{{ $prod->order_product_all_details->hsn_code }}</div>
                    <div class="divCell2">{{ $prod->actual_pieces }}</div>
                    <div class="divCell2">{{ round($prod->actual_quantity) }}</div>

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
                            if($local_state){
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
                        @if($local_state)
                            <div class="divCell2">{{$sgst}}</div>
                            <div class="divCell2">{{$cgst}}</div>
                        @else
                            <div class="divCell">{{$igst}}</div>
                        @endif
                    @else
                        <div class="divCell">{{$igst}}</div>
                    @endif

                    <div class="divCell2"><?php echo $rate = $prod->price; ?></div>
                    <div class="divCell">
                        <?php $total_price += $rate * $prod->actual_quantity; ?>
                        {{ ($rate * $prod->actual_quantity) }}
                    </div>
                </div>
                <?php
                $total_pr = $sgst + $cgst + $igst;

                $total_vat_amount = ($total_price * $total_pr) / 100;


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
                    <br>
<!--                    <table class="table-responsive detail_table">
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
                                {{  round($vat,2) }} </td>
                            <td> {{ round($allorder->grand_price, 2) }} </td>
                        </tr>
                    </table>-->
<!--                    <table class="table-responsive detail_table">
                        <tr>
                            <th> Product </th>
                            <th> Qty </th>
                            <th> Amount </th>
                            <th> GST </th>
                            <th> Total Inc GST </th>
                        </tr>
                        <tr class="secondrow">
                            <td>Pipe</td>
                            <td> {{ round($allorder->pipe_qty, 2) }}  </td>
                            <td> {{ round($allorder->pipe_amount, 2) }}</td>
                            <td> {{ round($allorder->pipe_vat, 2) }} </td>
                            <td> {{ round($allorder->pipe_vat_amount, 2) }}</td> 
                        </tr>
                        <tr class="secondrow">
                            <td>Structure</td>
                            <td> {{ round($allorder->structure_qty, 2) }} </td>
                            <td> {{ round($allorder->structure_amount, 2) }} </td>
                            <td> {{ round($allorder->structure_vat, 2) }} </td>
                            <td> {{ round($allorder->structure_vat_amount, 2) }} </td>
                        </tr>
                        <tr class="secondrow">
                            <td>Total</td>
                            <td> {{ round($allorder->structure_qty, 2) + round($allorder->pipe_qty, 2) }} </td>
                            <td> {{ round($allorder->structure_amount, 2) + round($allorder->pipe_amount, 2)}} </td>
                            <td> {{ round($allorder->structure_vat, 2) }} </td>
                            <td> {{ round($allorder->structure_vat_amount, 2) + round($allorder->pipe_vat_amount, 2)}} </td>
                        </tr>
                        
                    </table>-->

<!--                    <div class="divTable" style="width: 85%;border-left: 1px solid #ccc; border-right: 1px solid #ccc;">
                        <div class="headRow">
                            <div class="divRow">

                                <div class="divCell"><b>Product</b></div>
                                <div class="divCell"><b>Qty</b></div>
                                <div class="divCell"><b>Amount</b></div>
                                <div class="divCell"><b>GST</b></div>
                                <div class="divCell" style="display: inline-block; white-space: nowrap;"><b>Total Inc GST</b></div>
                            </div>
                            <td>   </td>
                            <td> </td>
                            <td>  </td>
                            <td> </td> 
                            <div class="divRow">
                                <div class="divCell">Pipe</div>
                                <div class="divCell">{{ round($allorder->pipe_qty, 2) }}</div>
                                <div class="divCell">{{ round($allorder->pipe_amount, 2) }}</div>
                                <div class="divCell">{{ round($allorder->pipe_vat, 2) }}</div>
                                <div class="divCell">{{ round($allorder->pipe_vat_amount, 2) }}
                                </div>
                            </div>                           
                            <div class="divRow">
                                <div class="divCell">Structure</div>
                                <div class="divCell">{{ round($allorder->structure_qty, 2) }}</div>
                                <div class="divCell">{{ round($allorder->structure_amount, 2) }}</div>
                                <div class="divCell">{{ round($allorder->structure_vat, 2) }}</div>
                                <div class="divCell">{{ round($allorder->structure_vat_amount, 2) }}</div>
                            </div>
                            <?php
                            if (isset($allorder->pipe_vat) && !empty($allorder->pipe_vat)) {
                                $gst_percentage = $allorder->pipe_vat;
                            } else if (isset($allorder->structure_vat) && !empty($allorder->structure_vat)) {
                                $gst_percentage = $allorder->structure_vat;
                            } else {
                                $gst_percentage = 0;
                            }
                            ?>
                            <div class="divRow">
                                <div class="divCell"><b>Total</b></div>
                                <div class="divCell"><b>{{ round($allorder->pipe_qty, 2) + round($allorder->structure_qty, 2) }}</b></div>
                                <div class="divCell"><b>{{ round($allorder->pipe_amount, 2) + round($allorder->structure_amount, 2) }}</b></div>
                                <div class="divCell"><b>{{ round($gst_percentage, 2) }}</b></div>
                                <div class="divCell"><b>{{ round($allorder->pipe_vat_amount, 2) + round($allorder->structure_vat_amount, 2) }}</b></div>
                            </div>
                        </div>
                    </div>-->
                    <br>

                    <div>
                        &nbsp; Remarks : {{$allorder->remarks}}
                    </div>
                    <!--                    <div class="ruppes grand_price">
                                            &nbsp; <?php $gt = round($allorder->grand_price, 2) ?>
                                            Rupees <?php //echo ucwords(str_replace(".", "", convert_number($allorder->grand_price)));     ?> Only.
                                        </div>-->
                </div>
                <div class="total">
                    <div class="">
                        <div class="label"> &nbsp; Total</div>
                        <div class="value bob"> {{ round($total_price, 2) }} &nbsp;</div>

                        <div class="label ">&nbsp; Loading</div>
                        <div class="value">
<?php
$loading_charge = $allorder->loading_charge;
$loading_vat = $allorder->loading_vat_percentage;
?>
                            {{($loading_charge != "")?round($loading_charge,2):0}} &nbsp;
                        </div>
                        <div class="label">&nbsp; Freight</div>
                        <div class="value">
                            {{($allorder->freight != "")?round($allorder->freight,2):0}} &nbsp;
                        </div>
                        <div class="label">&nbsp; Discount</div>
                        <div class="value">
                            {{($allorder->discount != "")?round($allorder->discount,2):0}}
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Total</div>
                        <div class="value">
<?php $with_total = $total_price + $loading_charge + $allorder->freight + $allorder->discount; ?>
                            {{ round($with_total, 2) }}
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Total GST = @if($local_state)
                                SGST + CGST
                            @else
                                IGST
                            @endif</div>
                        <div class="value">
<?php
$vat = $final_vat_amount;
// $vat = (isset($allorder->vat_percentage) && ($with_total != "")) ? round(($with_total * $allorder->vat_percentage) / 100, 2) : 0; 
?>
                            {{ round($vat,5) }}
                            &nbsp;
                        </div>
                        <div class="label">&nbsp; Round Off</div>
                        <div class="value">
<?php
//if (isset($allorder->round_off) && ($allorder->round_off != "")) {
//    $roundoff = $allorder->round_off;
//} else {
//    $roundoff = 0;
//}*/
                            $roundoff = $vat;
?>
                            {{ round($roundoff,2) }}
                            &nbsp;
                        </div>
                        <div class="label" style="border-bottom: 1px solid #ccc;">&nbsp; GT</div>
                        <div class="value" style="border-bottom: 1px solid #ccc;">
<?php

                            $grand_price = $total_price;

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
                            {{ round($grand_price + $final_vat_amount, 2) }}
                            &nbsp;
                        </div>
                    </div>
                </div>
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