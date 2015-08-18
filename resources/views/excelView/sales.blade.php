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
            <td class="heading1">Frieght</td>
            <td class="heading1">Tax Type</td>
            <td class="heading1">Tax Rate</td>
            <td class="heading1">Tax</td>
            <td class="heading1">Round Off</td>
            <td class="heading1">Other Charges</td>
            <td class="heading1">Narration</td>
        </tr>
        <?php
        foreach ($allorders as $key => $value) {
            foreach ($value['delivery_challan_products'] as $key1 => $value1) {
                $order_quantity = 0;
                ?>
                <tr>
                    <td>{{$key}}</td>
                    <td></td>
                    <td>Sales</td>
                    <td><?= date("jS F, Y", strtotime($value->updated_at)) ?></td>
                    <td></td>
                    <td><?= $value['customer']->tally_name ?></td>
                    <td><?= $value['customer']->address1 ?></td>
                    <td><?= $value['customer']->address2 ?></td>
                    <td><?= $value->customer->states->state_name ?></td>
                    <td><?= $value['customer']->zip ?></td>
                    <td><?= $value['customer']->vat_tin_number ?></td>
                    <td><?= $value1['order_product_details']->alias_name ?></td>
                    <td></td>
                    @if($value1->actual_quantity !=0)
                    <td><?= $value1->actual_pieces ?></td>
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
                        ?>
                        <?= round($value1->actual_quantity, 2) ?>
                    </td>
                    <td><?= $value1->price ?></td>
                    <?php $value1['order_product_details']['product_category']['id'] ?>
                    <?php
                    if (!empty($value['customer']['customerproduct'])) {
                        foreach ($value['customer']['customerproduct'] as $customer_difference) {
                            if ($customer_difference['product_category_id'] == $value1['order_product_details']['product_category']['id']) {
                                $customer_diff = $customer_difference->difference_amount;
                            }
                        }
                    }
                    ?>
                    <td><?php
                        if (isset($customer_diff)) {
                            echo (($value1->price + $value1['order_product_details']['difference'] + $customer_diff + $value['delivery_location']['difference']) * $value1->quantity);
                        } else {
                            echo (($value1->price + $value1['order_product_details']['difference'] + $value['delivery_location']['difference']) * $value1->quantity);
                        }
                        ?>
                    </td>
                    @elseif($value1->actual_quantity == 0)
                    <td><?= $value1->actual_pieces ?></td>
                    <td>Pieces</td>
                    <td><?= round($value1->actual_quantity, 2) ?></td>
                    <td><?= $value1->price ?></td>
                    <td><?= ($value1['order_product_details']['weight'] * $value1->actual_pieces) ?></td>
                    <td><?= (($value1->price + $value1['order_product_details']['difference'] + $value['delivery_location']['difference']) * $value1->quantity) ?></td>
                    @endif
                    <td><?= $value->discount ?></td>
                    <td><?= $value->loading ?></td>
                    <td><?= $value->freight ?></td>
                    <td><?php
                        if ($value->delivery_order->vat_percentage !== "")
                            echo "VAT";
                        else
                            echo "All inclusive";
                        ?></td>
                    <td>
                        <?php
                        if ($value->delivery_order->vat_percentage !== "")
                            echo $value->delivery_order->vat_percentage;
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($value->delivery_order->vat_percentage !== "")
                            echo $value->grand_price * ($value['delivery_order']->vat_percentage / 100);
                        ?>
                    </td>
                    <td><?= $value->round_off ?></td>
                    <td></td>
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
            }
        }
        ?>

    </table>
</html>