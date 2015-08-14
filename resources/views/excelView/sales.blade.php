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
            <td>Vch No</td>
            <td>Ref NUM</td>
            <td class="border2">Vch Type</td>
            <td class="border2">Date</td>
            <td class="border2">Code</td>
            <td class="border2">Name</td>
            <td class="border2">Address1</td>
            <td class="border2">Address2</td>
            <td class="border2">State</td>
            <td class="border2">Pin Code</td>
            <td class="border2">Tin No</td>
            <td class="border2">Item Name</td>
            <td class="border2">Godown</td>
            <td class="border2">Pcs</td>
            <td class="border2">Unit</td>
            <td class="border2">Qty</td>
            <td class="border2">Rate</td>
            <td class="border2">Amt</td>
            <td class="border2">Discount</td>
            <td class="border2">Loading</td>
            <td class="border2">Frieght</td>
            <td class="border2">Tax Type</td>
            <td class="border2">Tax Rate</td>
            <td class="border2">Tax</td>
            <td class="border2">Round Off</td>
            <td class="border2">Other Charges</td>
            <td class="border2">Narration</td>
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
                    <td><?= $value['customer']->owner_name ?></td>
                    <td><?= $value['customer']->address1 ?></td>
                    <td><?= $value['customer']->address2 ?></td>
                    <td><?= $value->customer->states->state_name ?></td>
                    <td><?= $value['customer']->zip ?></td>
                    <td><?= $value['customer']->vat_tin_number ?></td>
                    <td><?= $value1['order_product_details']->alias_name ?></td>
                    <td></td>
                    <td><?= $value1->actual_pieces ?></td>
                    <td><?= $value1->unit->unit_name ?></td>
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
                    <td><?= ($value1->price * $value1->quantity) ?></td>
                    <td><?= $value->discount ?></td>
                    <td><?= $value->loaded_by ?></td>
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
                    <td><?= "[" . $value['delivery_order']->vehicle_number . "][" . $value->remark . "]" ?></td>
                </tr>
                <?php
            }
        }
        ?>

    </table>
</html>