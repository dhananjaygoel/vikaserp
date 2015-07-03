<html>
    <head>
        <title>Print</title>

        <!-- bootstrap -->
        {!! HTML::style('/resources/assets/css/bootstrap/bootstrap.min.css') !!}
        {!! HTML::script('/resources/assets/js/jquery.js') !!}

    </head>
    <body onload="window.print();">
        <table id="add_product_table_purchase" class="table table-hover">
            <tbody>
                <tr>
                    <td>#</td>
                    <td>Challan sr. No</td>
                    <td>Do. No</td>
                    <td>Name</td>
                    <td>Del Loc</td>
                    <td>Qty</td>
                    <td>Amount</td>
                    <td>bill No.</td>
                    <td>truck no</td>
                    <td>loaded by</td>
                    <td>labour</td>
                    <td>remarks</td>
                </tr>
                <?php $i = 1; ?>
                @foreach ($purchase_daybook as $obj)

                <?php
                $qty = 0;
                $amount = 0;
                ?>
                @foreach ($obj['all_purchase_products'] as $total_qty)
                <?php
                $qty += $total_qty->present_shipping;
                $amount += $total_qty->present_shipping * $total_qty->price;
                ?>
                @endforeach
                <tr>
                    <td>{{$i++ }}</td>
                    <td>{{ $obj->serial_number }}</td>
                    <td>dummy</td>
                    <td>{{ $obj['supplier']->owner_name }}</td>
                    <td>dummy</td>
                    <td>{{$qty }}</td>
                    <td>{{$amount }}</td>
                    <td>{{$obj->bill_number }}</td>
                    <td>{{$obj->vehicle_number }}</td>
                    <td>{{$obj->unloaded_by }}</td>
                    <td>{{$obj->labours }}</td>
                    <td>{{$obj->remarks }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </body>
</html>
