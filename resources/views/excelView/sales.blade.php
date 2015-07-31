<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
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
        </tr>
        <tr>
            <td class="border2">Date</td>
            <td class="heading2 border2">Particulars</td>
            <td class="border2">Time</td>
            <td class="border2">Vch Type</td>
            <td class="border2">Vch No.</td>
            <td>Inwards Qty/Outwards Qty</td>
            <td>Rate</td>
            <td>Amount</td>
            <td class="heading2 border3">Credit Amount</td>
        </tr>
        <?php
        foreach ($allorders as $key => $value) {
            ?>
            <tr>
                <td><?= date("jS F, Y", strtotime($value->updated_at)) ?></td>
                <td class="heading2"><?= $value['customer']->owner_name ?></td>
                <td></td>
                <td class="heading2">Sales</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="heading2"><?= $value->grand_price ?></td>
            </tr>
            <?php
            foreach ($value['delivery_challan_products'] as $key1 => $value1) {
                $order_quantity = 0;
                ?>
                <tr>
                    <td></td>
                    <td><?= $value1['order_product_details']->alias_name ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
                        <?= round($value1->actual_quantity, 2) . ' Kg' ?>
                    </td>
                    <td><?= $value1->price ?>/Kg</td>
                    <td><?= ($value1->quantity * $value1->price) ?></td>
                    <td></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td></td>
                <td class="heading2">3loading</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="heading2"><?= $value->loading_charge ?></td>
            </tr>
            <tr>
                <td></td>
                <td class="text2"><?= $value['delivery_order']->vehicle_number ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
            if ($value->freight != '') {
                ?>
                <tr>
                    <td></td>
                    <td class="heading2">Frieght</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="heading2"><?= $value->freight ?></td>
                </tr>
                <?php
            }
            ?>
            <?php
            if ($value->round_off != '') {
                ?>
                <tr>
                    <td></td>
                    <td class="heading2">Round Off</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="heading2"><?= $value->round_off ?></td>
                </tr>
                <?php
            }
            ?>
            <?php
            if ($value->vat_percentage != '') {
                ?>
                <tr>
                    <td></td>
                    <td class="heading2">Vat %</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="heading2"><?= $value->vat_percentage ?></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td></td>
            </tr>
            <?php
        }
        ?>

    </table>
</html>