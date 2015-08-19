<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
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