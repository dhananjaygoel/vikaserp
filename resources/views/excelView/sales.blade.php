<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}

    <table>
<!--        <tr>
            <td colspan="3" class="heading1">Vikash Associates ..(<?= date('Y') ?>)</td>
        </tr>
        <tr>
            <td colspan="3" class="border1">411014</td>
        </tr>
        <tr>
            <td colspan="3" class="heading1">Daybook</td>
        </tr>
        <tr>
            <td colspan="3">For: <?= date('d-m-Y') ?></td>
        </tr>-->
        <tr>
            <td class="heading1">Vch No</td>
            <td class="heading1">Ref NUM</td>
            <td class="heading1">Vch Type</td>
            <td class="heading1">Date</td>
            <td class="heading1">Code</td>
            <td class="heading1">Name</td>
            <td class="heading1">Address1</td>
            <td class="heading1">Address2</td>
            <td class="heading1">State</td>
            <td class="heading1">Pin Code</td>
            <td class="heading1">Tin No</td>
            <td class="heading1">Item Name</td>
            <td class="heading1">Godown</td>
            <td class="heading1">Pcs</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Qty</td>
            <td class="heading1">Rate</td>
            <td class="heading1">Amt</td>
            <td class="heading1">Discount</td>
            <td class="heading1">Loading</td>
            <td class="heading1">Freight</td>
            <td class="heading1">Tax Type</td>
            <td class="heading1">Tax Rate</td>
            <td class="heading1">Tax</td>
            <td class="heading1">Round Off</td>
            <td class="heading1">Grand total</td>
            <td class="heading1">Narration</td>
        </tr>
        <?php
        $i = 1;
        $j = 1;

        foreach ($allorders as $key => $value) {
            $next_cnt = count($value['delivery_challan_products']);
            $current_number = 1;

            foreach ($value['delivery_challan_products'] as $key1 => $value1) {
                $order_quantity = 0;
                $value_cnt = "";
                ?>
                <tr>
                    <?php
                    $value_cnt = "";
                    if ($current_number == 1) {
                        $value_cnt = $j;
                    } else {
                        $value_cnt = $j;
                    }
                    ?>
                    @if($current_number == 1)
                    <td>{{$value_cnt}}</td>
                    @else
                    <td>{{$value_cnt}}</td>
                    @endif

                    <td></td>
                    <td>Sales</td>
                    <td>{{ date("m-d-Y", strtotime($value->updated_at)) }}</td>
                    <td></td>
                    <td>{{isset($value['customer']->tally_name)?$value['customer']->tally_name:$value['customer']->owner_name}}</td>
                    <td>{{isset($value['customer']->address1)?$value['customer']->address1:''}}></td>
                    <td>{{isset($value['customer']->address2)?$value['customer']->address2:''}}</td>
                    <td>{{isset($value['customer']->states)?$value->customer->states->state_name:''}}</td>
                    <td>{{isset($value['customer']->zip)? $value['customer']->zip:'' }}</td>
                    <td>{{isset($value['customer']->vat_tin_number)?$value['customer']->vat_tin_number:''}}></td>
                    <td>{{isset($value1['order_product_details']->alias_name)?$value1['order_product_details']->alias_name :'' }}</td>
                    <td></td>

                    @if($value1->actual_quantity != 0 )
                    <td>{{isset($value1->actual_pieces)?$value1->actual_pieces:''}}</td>
                    <td>Kg</td>
                    <td>
                        <?php
                        if ($value1->unit_id == 1) {
                            $order_quantity = $order_quantity + $value1->quantity;
                        }
                        if ($value1->unit_id == 2) {
                            $order_quantity = $order_quantity + ($value1->quantity * $value1['order_product_details']->weight);
                        }
                        if ($value1->unit_id == 3) {
                            $order_quantity = $order_quantity + (($value1->quantity / $value1['order_product_details']->standard_length ) * $value1['order_product_details']->weight);
                        }
                        round($value1->actual_quantity, 2)
                        ?>
                    </td>
                    <td>{{isset($value1->price)?$value1->price:''}}</td>
                    <?php $value1['order_product_details']['product_category']['id'] ?>
                    <?php
                    if (!empty($value['customer']['customerproduct'])) {
                        foreach ($value['customer']['customerproduct'] as $customer_difference) {
                            if ($customer_difference['product_category_id'] == $value1['order_product_details']['product_category']['id']) {
                                $customer_diff = $customer_difference->difference_amount;
                                $customer_diff1 = $customer_difference->difference;
                            }
                        }
                    }
                    ?>
                    <td>
                        <?php
//                        Commented by 157 on 03-09-2015
//
//                        $total_amt = ($value1->price + $value1['order_product_details']['difference']);
//                        $total_amt = $total_amt + $value->delivery_order->location_difference;
//                        if (isset($customer_diff)) {
//                            $total_amt = $total_amt + $customer_diff;
//                        }
                        $total_amt = "";
                        if (isset($value1->quantity) && !empty($value1->quantity) && $value1->quantity != 0) {
                            $vat_amt = 0;
                            $tot_amt = $value1->price * $value1->quantity;
                            if ($next_cnt == $current_number) {
                                $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount + $value->round_off;
                                if (isset($value['delivery_order']->vat_percentage) && $value['delivery_order']->vat_percentage !== "") {
                                    $vat_amt = ($total_amt * ($value['delivery_order']->vat_percentage / 100));
                                }
                                $total_amt = $vat_amt + $total_amt;
                            } else {
                                $total_amt = $tot_amt;
                            }
                        } else {
                            $vat_amt = 0;
                            $tot_amt = $value1->price * $value1->actual_pieces * $value1->order_product_details->weight;

                            if ($next_cnt == $current_number) {
                                $total_amt = $tot_amt + $value->loading_charge + $value->freight + $value->discount + $value->round_off;
                                $vat_amt = ($total_amt * ($value['delivery_order']->vat_percentage / 100));
                                $total_amt = $vat_amt + $total_amt;
                            } else {
                                $total_amt = $tot_amt;
                            }
                        }
                        echo number_format($tot_amt, 2, '.', '');
//                        if (isset($customer_diff)) {
//                            echo (($value1->price + $value1['order_product_details']['difference'] + $customer_diff + $value['delivery_location']['difference']) * $value1->quantity);
//                        } else {
//                            echo (($value1->price + $value1['order_product_details']['difference'] + $value['delivery_location']['difference']) * $value1->quantity);
//                        }
                        ?>
                    </td>
                    @else
                    <td>{{isset($value1->actual_pieces)?$value1->actual_pieces:''}}</td>
                    <td>Pieces</td>
                    @if(isset($value1->actual_quantity) && $value1->actual_quantity!="")
                    <td><?php echo $roundedvalue = round($value1->actual_quantity, 2) ?></td>
                    @else
                    <td></td>
                    @endif
                    <td>{{isset($value1->price)?$value1->price:''}}</td>
                    <td><?php ($value1['order_product_details']['weight'] * $value1->actual_pieces * $value1->price) ?></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{isset($value->discount)?$value->discount:''}}</td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{isset($value->loading_charge)?$value->loading_charge:'' }}</td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>{{isser($value->freight)$value->freight:''}}</td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td><?php
                        if ($value->delivery_order->vat_percentage !== "")
                            echo "VAT";
                        else
                            echo "All inclusive";
                        ?>
                    </td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>
                        <?php
                        if ($value->delivery_order->vat_percentage !== "")
                            echo $value->delivery_order->vat_percentage . "%";
                        ?>
                    </td>
                    @else
                    <td></td>
                    @endif

                    @if($next_cnt == $current_number)
                    <td>
                        <?php
                        if ($value->delivery_order->vat_percentage !== "")
                            echo number_format($vat_amt, 2, '.', '');
                        ?>
                    </td>
                    @else
                    <td></td>
                    @endif
                    @if($next_cnt == $current_number)
                    <td>{{ $value->round_off }}</td>
                    @else
                    <td></td>
                    @endif


                    @if($next_cnt == $current_number)
                    <td>
                        {{(isset($total_amt)? number_format($total_amt, 2, '.', '') :'')}}
                    </td>
                    @else
                    <td></td>
                    @endif
                    <td>
                        <?php
                        if (isset($value['delivery_order']->vehicle_number) && $value['delivery_order']->vehicle_number != "")
                            echo "[" . $value['delivery_order']->vehicle_number . "]";
                        if (isset($value->remark) && $value->remark != "")
                            echo "[" . $value->remark . "]";
                        if (isset($value['delivery_location']->area_name) && $value['delivery_location']->area_name != "")
                            echo "[" . $value['delivery_location']->area_name . "]";
                        ?>
                    </td>
                </tr>
                <?php
                $current_number++;
            }
            $i++;
            if ($next_cnt != 0) {
                $j++;
            }
        }
        ?>
    </table>
</html>