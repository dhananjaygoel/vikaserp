<?php
    $k = ($inventory_list->currentPage() - 1 ) * $inventory_list->perPage() + 1;
?>
    <thead class="inventory_table_head">
        <tr>
            <th><span>#</span></th>
            <th class="opening"><span>Alias Name</span></th>
            <th class="opening"><span>Minimal</span></th>
            <th class="inventory-size"><span>Opening</span></th>
            <th><span>Delivery<br/>Challan</span></th>
            <th><span>Purchase<br/>Challan</span></th>
            <th><span>Physical<br/>Closing</span></th>
            <th><span>P SO</span></th>
            <th><span>P DO</span></th>
            <th><span>P PO</span></th>
            <th><span>P PA</span></th>
            <th><span>Virtual<br />Stock</span></th>
            @if(auth()->user()->role_id == 0)
            <th><span>Action</span></th>
            @endif
        </tr>
    </thead>
    <form method="POST" action=" {{route('inventory.store')}} " id="frm_inventory_save_all">
        <input type="hidden" name="pagenumber" value="{{(Input::get('page')!= '')?Input::get('page') : 1 }}"  />

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <tbody>
            <?php
            $i = 1;
            ?>
            @foreach($inventory_list as $inventory)
            <?php
            $total = ($inventory->physical_closing_qty - $inventory->pending_delivery_order_qty - $inventory->pending_sales_order_qty + $inventory->pending_purchase_advise_qty);

            ?>
            @if((Input::has('inventory_filter')) && (Input::get('inventory_filter')=='minimal'))
            @if($inventory->minimal < $total)
            <tr class="smallinput datadisplay_{{$inventory->id}}">
                <td>{{$k++}}</td>
                <td>{{$inventory->product_sub_category->alias_name}}</td>
                @if(auth()->user()->role_id == 0)
                <td class="{{ ($inventory->minimal < $total) ?'minimum_reach': '' }}">
                    <div class="form-group">
                        <input type="text" name="minimal_{{$inventory->id}}" id="minimal_{{$inventory->id}}" value="{{$inventory->minimal}}" maxlength="9" class="form-control no_alphabets" onkeypress=" return numbersOnly(this, event, true, true);"/>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" name="{{$inventory->id}}" placeholder="Stock in(kg)" value="{{$inventory->opening_qty}}" maxlength="9" class="form-control no_alphabets txt_open_stock" onkeypress=" return numbersOnly(this, event, true, true);"/>
                    </div>
                </td>
                @else
                <td class="{{ ($inventory->minimal < $total) ?'minimum_reach': '' }}">{{$inventory->minimal}}</td>
                <td>{{$inventory->opening_qty}}</td>
                @endif
                <td id="sales_challan_{{$inventory->id}}">{{($inventory->sales_challan_qty <= 0 )? 0: $inventory->sales_challan_qty}}</td>
                <td id="purchase_challan_{{$inventory->id}}">{{($inventory->purchase_challan_qty <= 0) ? 0 : $inventory->purchase_challan_qty}}</td>
                <td id="physical_closing_{{$inventory->id}}">{{$inventory->physical_closing_qty}}</td>
                <td id="pending_order_{{$inventory->id}}">{{($inventory->pending_sales_order_qty <= 0) ? 0 : $inventory->pending_sales_order_qty}}</td>
                <td id="pending_deliver_order_{{$inventory->id}}">{{($inventory->pending_delivery_order_qty <= 0) ? 0 : $inventory->pending_delivery_order_qty}}</td>
                <td id="pending_purchase_order_{{$inventory->id}}">{{($inventory->pending_purchase_order_qty <= 0) ? 0 : $inventory->pending_purchase_order_qty }}</td>
                <td id="pending_purchase_advise_{{$inventory->id}}">{{($inventory->pending_purchase_advise_qty <= 0) ? 0 : $inventory->pending_purchase_advise_qty}}</td>
                <td id="virtual_qty_{{$inventory->id}}">{{$virtual_qty[$i-1]}}</td>
                @if(auth()->user()->role_id == 0)
                <td>
                    <div class="row product-price">
                        <div class="form-group col-md-2 difference_form">
                            <input class="btn btn-primary" type="button" value="save" data-id="{{$inventory->id}}" onclick="update_inventory(this,{{$inventory->id}});">
                        </div>
                    </div>
                </td>
                @endif
            </tr>
            @endif
            @else
            <tr class="smallinput datadisplay_{{isset($inventory->id) ? $inventory->id:''}}">
                <td>{{$k++}}</td>
                <td>{{isset($inventory->product_sub_category->alias_name) ? $inventory->product_sub_category->alias_name:''}}</td>

                @if(auth()->user()->role_id == 0)
                <td class="{{ ($inventory->minimal < $total) ?'minimum_reach': '' }}">
                    <div class="form-group">
                        <input type="text" name="minimal_{{$inventory->id}}" id="minimal_{{$inventory->id}}" value="{{$inventory->minimal}}" maxlength="9" class="form-control no_alphabets" onkeypress=" return numbersOnly(this, event, true, true);"/>
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="text" name="{{$inventory->id}}" placeholder="Stock in(kg)" value="{{$inventory->opening_qty}}" maxlength="9" class="form-control no_alphabets txt_open_stock" onkeypress=" return numbersOnly(this, event, true, true);"/>
                    </div>
                </td>
                @else
                <td class="{{ ($inventory->minimal < $total) ?'minimum_reach': '' }}">{{$inventory->minimal}}</td>
                <td>{{$inventory->opening_qty}}</td>
                @endif
                <td id="sales_challan_{{$inventory->id}}">{{($inventory->sales_challan_qty <= 0 )? 0: $inventory->sales_challan_qty}}</td>
                <td id="purchase_challan_{{$inventory->id}}">{{($inventory->purchase_challan_qty <= 0) ? 0 : $inventory->purchase_challan_qty}}</td>
                <td id="physical_closing_{{$inventory->id}}">{{$inventory->physical_closing_qty}}</td>
                <td id="pending_order_{{$inventory->id}}">{{($inventory->pending_sales_order_qty <= 0) ? 0 : $inventory->pending_sales_order_qty}}</td>
                <td id="pending_deliver_order_{{$inventory->id}}">{{($inventory->pending_delivery_order_qty <= 0) ? 0 : $inventory->pending_delivery_order_qty}}</td>
                <td id="pending_purchase_order_{{$inventory->id}}">{{($inventory->pending_purchase_order_qty <= 0) ? 0 : $inventory->pending_purchase_order_qty }}</td>
                <td id="pending_purchase_advise_{{$inventory->id}}">{{($inventory->pending_purchase_advise_qty <= 0) ? 0 : $inventory->pending_purchase_advise_qty}}</td>
                <td id="virtual_qty_{{$inventory->id}}">{{$virtual_qty[$i-1]}}</td>
                @if(auth()->user()->role_id == 0)
                <td>
                    <div class="row product-price">
                        <div class="form-group col-md-2 difference_form">
                            <input class="btn btn-primary" type="button" value="save" data-id="{{$inventory->id}}" onclick="update_inventory(this,{{$inventory->id}});">
                        </div>
                    </div>
                </td>
                @endif
            </tr>
            @endif
            <?php
            $i++;
            ?>
            @endforeach
        </tbody>
    </form>
