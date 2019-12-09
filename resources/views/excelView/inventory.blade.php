<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Alias Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Opening Stock</th>
            <th style="height:20px;font-size:16px;color:#000080;">Sales Challan</th>
            <th style="height:20px;font-size:16px;color:#000080;">Purchase Challan</th>
            <th style="height:20px;font-size:16px;color:#000080;">Physical Closing Stock</th>
            <th style="height:20px;font-size:16px;color:#000080;">Pending Sales Order</th>
            <th style="height:20px;font-size:16px;color:#000080;">Pending Delivery Order</th>
            <th style="height:20px;font-size:16px;color:#000080;">Pending Purchase Order</th>
            <th style="height:20px;font-size:16px;color:#000080;">Pending Purchase Advise</th>
            <th style="height:20px;font-size:16px;color:#000080;">Virtual Stock</th>
        </tr>
        <?php $i = 0; ?>
        @foreach ($inventorys as $inventorylist)
        <tr>
            <td style="height:16px;">{{isset($inventorylist->alias_name)?$inventorylist->alias_name:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->opening_qty)?$inventorylist->opening_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->sales_challan_qty)?$inventorylist->sales_challan_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->purchase_challan_qty)?$inventorylist->purchase_challan_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->physical_closing_qty)?$inventorylist->physical_closing_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->pending_sales_order_qty)?$inventorylist->pending_sales_order_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->pending_delivery_order_qty)?$inventorylist->pending_delivery_order_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->pending_purchase_order_qty)?$inventorylist->pending_purchase_order_qty:""}}</td>
            <td style="height:16px;">{{isset($inventorylist->pending_purchase_advise_qty)?$inventorylist->pending_purchase_advise_qty:""}}</td>
            <td style="height:16px;">{{isset($virtual_stock_qty[$i])?$virtual_stock_qty[$i]:""}}</td>
        </tr>
        <?php $i++; ?>
        @endforeach
    </table>
</html>