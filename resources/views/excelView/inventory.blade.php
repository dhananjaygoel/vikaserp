<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}

    <table>
        <tr>
            <td class="heading1">Alias Name</td>
            <td class="heading1">Opening Stock</td>
            <td class="heading1">Sales Challan</td>
            <td class="heading1">Purchase Challan</td>
            <td class="heading1">Physical Closing Stock</td>
            <td class="heading1">Pending Sales Order</td>
            <td class="heading1">Pending Delivery Order</td>
            <td class="heading1">Pending Purchase Order</td>
            <td class="heading1">Pending Purchase Advise</td>
            <td class="heading1">Virtual Stock</td>
        </tr>
        @foreach ($inventorys as $inventorylist)
        <tr>
            <td>{{$inventorylist->product_sub_category->alias_name}}</td>
            <td>{{$inventorylist->opening_qty}}</td>
            <td>{{$inventorylist->sales_challan_qty}}</td>
            <td>{{$inventorylist->purchase_challan_qty}}</td>
            <td>{{$inventorylist->physical_closing_qty}}</td>
            <td>{{$inventorylist->pending_sales_order_qty}}</td>
            <td>{{$inventorylist->pending_delivery_order_qty}}</td>
            <td>{{$inventorylist->pending_purchase_order_qty}}</td>
            <td>{{$inventorylist->pending_purchase_advise_qty}}</td>
            <td>{{$inventorylist->virtual_qty}}</td>
        </tr>
        @endforeach
    </table>
</html>