<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
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
//        echo '<pre>';
//        print_r($purchase_orders->toArray());
//        echo '<pre>';
//        exit();
//        foreach ($purchase_orders as $key => $value) {
//            echo '<pre>';
//            print_r($value->grand_total);
//            echo '<pre>';
//        }
//        exit;
        foreach ($purchase_orders as $key => $value) {
            ?>
            <?php
            foreach ($value['all_purchase_products'] as $key1 => $value1) {
                $order_quantity = 0;
                ?>
                <tr>
                    <td>{{$key}}</td>
                    <td></td>
                    <td>Purchase</td>
                    <td><?= date("jS F, Y", strtotime($value->updated_at)) ?></td>
                    <td></td>
                    <td><?= $value['supplier']->owner_name ?></td>
                    <td><?= $value['supplier']->address1 ?></td>
                    <td><?= $value['supplier']->address2 ?></td>
                    <td><?= $value->supplier->states->state_name ?></td>
                    <td><?= $value['supplier']->zip ?></td>
                    <td><?= $value['supplier']->vat_tin_number ?></td>
                    <td><?= $value1['purchase_product_details']->alias_name ?></td>
                    <td></td>
                    <td><?= $value1->actual_pieces ?></td>
                    <td><?= $value1->unit->unit_name ?></td>
                    <td>
                        <?php
                        if ($value1->unit_id == 1) {
                            $order_quantity = $order_quantity + $value1->quantity;
                        }
                        if ($value1->unit_id == 2) {
                            $order_quantity = $order_quantity + ($value1->quantity * $value1['purchase_product_details']->weight);
                        }
                        if ($value1->unit_id == 3) {
                            $order_quantity = $order_quantity + (($value1->quantity / $value1['purchase_product_details']->standard_length ) * $value1['purchase_product_details']->weight);
                        }
                        ?>
                        <?= round($value1->quantity, 2) ?>
                    </td>
                    <td><?= $value1->price ?></td>
                    <td><?= ($value1->quantity * $value1->price) ?></td>
                    <td><?= $value->discount ?></td>
                    <td><?= $value->loading_charge ?></td>
                    <td><?= $value->freight ?></td>
                    <td><?php
                        if ($value->purchase_advice->vat_percentage !== "")
                            echo "VAT";
                        else
                            echo "All inclusive";
                        ?></td>
                    <td>
                        <?php
                        if ($value->purchase_advice->vat_percentage !== "")
                            echo $value->purchase_advice->vat_percentage;
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($value->purchase_advice->vat_percentage !== "")
                            echo $value->grand_total * ($value['purchase_advice']->vat_percentage / 100);
                        ?>
                    </td>
                    <td><?= $value->round_off ?></td>
                    <td></td>
                    <td><?= "[" . $value['purchase_advice']->vehicle_number . "][" . $value->remark . "]" ?></td>
                </tr>
                <?php
            }
            ?>
            <?php
        }
        ?>
    </table>
</html>