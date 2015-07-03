<html>
    <head>
        <title>Print</title>
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
                    <td>Delivery Location</td>
                    <td>Qty</td>
                    <td>Amount</td>
                    <td>bill No.</td>
                    <td>truck no</td>
                    <td>loaded by</td>
                    <td>labour</td>
                    <td>remarks</td>
                </tr>
                <?php $i = 1; ?>
                @foreach ($allorders as $obj)
                <?php
                $qty = 0;
                $amount = 0;
                ?>
                @foreach ($obj['all_order_products'] as $total_qty)
                <?php
                $qty += $total_qty->present_shipping;
                $amount += $total_qty->present_shipping * $total_qty->price;
                ?>
                @endforeach
                <tr>
                    <td>{{$i++ }}</td>
                    <td>{{ $obj->serial_number }}</td>
                    <td>dummy</td>
                    <td>{{$obj['customer']->owner_name }}</td>
                    <td>{{$obj['delivery_location']->area_name}}</td>
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
