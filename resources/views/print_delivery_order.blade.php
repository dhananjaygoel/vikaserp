<!DOCTYPE html>
<html><head>
        <title>Delivery Order</title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head><body>
        <style>
            body{
                font-size: 10px;
                font-family: Arial !important;
                font-weight: bold !important;
                /*font-family: monospace !important;*/
            }
            .divTable{
                display:table;
                width:100%;
                background-color:#fff;
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            .divRow{
                width:auto;
                clear:both;
                border-top: 1px solid #ccc;
            }
            .divCell{
                float:left;
                display:table-column;
                width:10%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell:last-child{
                float:left;
                display:table-column;
                width:auto;
                padding: 5px;
                border-right: none;
            }
            .divCell2{
                float:left;
                display:table-column;
                width:4.2%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            .divCell3{
                float:left;
                display:table-column;
                width:30%;
                padding: 5px;
                border-right: 1px solid #ccc;
            }
            /*            .divCell:last-child
                        {
                            border: none;
                        }*/
            .divRow:last-child{
                border-top: none;
                border-bottom: 1px solid #ccc;
            }
            .headRow{
                display:table-row;
            }
            .footer{
                width: 100%;
                float: left;
            }
            .remark{
                width: 8%;
                float: left;
                padding: 20px 5px ;
            }
            .content{
                width: 88%;
                float: left;
                padding-top: 20px;
            }
            .invoice{
                width:70%;
                margin-left: 15%;
                border: 1px solid #ccc;
                float: left;
                padding: 0px;
                overflow: hidden;
            }
            .del{
                width: 100%;
                float: left;
                padding: 10px 0px 10px 5px;
            }
            .trk-mobile{
                width: 100%;
                float: left;
                padding: 10px 0px 10px 5px;
                border-bottom: 1px solid #ccc;
            }
            .trk-no{
                width: 50%;
                float: left;
            }
            .mob-no{
                width: 50%;
                float: left;
            }
            .name{
                width: 100%;
                padding: 10px 0px 10px 5px;
                float: left;
                position: relative;
                border-bottom: 1px solid #ccc;
            }
            .delivery-details{
                width: 100%;
                padding: 10px 0px 10px 5px;
                float: left;
                border-bottom: 1px solid #ccc;
            }
            .do-no{
                width: 33%;
                float: left;
                position: relative;
            }
            .date{
                width: 33%;
                float: left;
                position: relative;
            }
            .time{
                width: 33%;
                float: left;
                position: relative;
            }
            .title{
                width: 100%;
                text-align: center;
                border-bottom: 1px solid #ccc;
                padding: 10px 0px 10px 5px;
                font-weight: 600;
            }
        </style>
        <div class="invoice">
            <div class="title">Delivery Order</div>
            <div class="delivery-details">
                <div class="do-no">DO Number: {{isset($delivery_data->serial_no)?$delivery_data->serial_no:'' }}</div>
                <div class="date">Date: {{ date('F d, Y')}}</div>
                <div class="time">
                    <!--Time: {{ date("h:i:sa") }}-->
                    Time: <?php
                    echo '<script type="text/javascript">
                        var x = new Date()
                        var current_time = x.getHours()+":"+x.getMinutes()+":"+x.getSeconds()
                        document.write(current_time)
                        </script>';
                    ?>
                </div>
            </div>
            <div class="name">
                Name: 
                @if(isset($delivery_data['customer']->tally_name) && $delivery_data['customer']->tally_name != "")
                                            {{$delivery_data['customer']->tally_name}}
                                            @else
                                            {{isset($delivery_data['customer']->owner_name)?$delivery_data['customer']->owner_name:'N/A'}}

                                            @endif
            </div>
            <div class="trk-mobile">
                <div class="trk-no">Vehicle No: {{ isset($delivery_data->vehicle_number)?$delivery_data->vehicle_number :''}}</div>
                <div class="mob-no">Driver Mob: {{ isset($delivery_data->driver_contact_no)?$delivery_data->driver_contact_no:'' }}</div>
                @if($customer_type!="supplier")<div class="empty-truck-weight">Empty Truck Weight: {{ isset($delivery_data->empty_truck_weight)?$delivery_data->empty_truck_weight:'0' }} Kg</div>@endif
            </div>
            <div class="del">
                Delivery @: {{(isset($delivery_data->delivery_location_id) && $delivery_data->delivery_location_id!=0) ? $delivery_data['location']->area_name : (isset($delivery_data->other_location)?$delivery_data->other_location:'') }}
            </div>
            <div class="divTable">
                <div class="headRow">
                    <div class="divCell2">Sr.</div>
                    <div class="divCell">Size</div>
                    <div class="divCell">Qty</div>
                    <div class="divCell">Unit</div>
                    <div class="divCell">Pcs</div>
                    <div class="divCell">Present Shipping</div>
                    <div class="divCell">GST</div>
                </div>
                <?php $i = 1; ?>
                @if($delivery_data['delivery_product'])
                @foreach($delivery_data['delivery_product'] as $product)
                @if(isset($product['order_type']) && $product['order_type'] == 'delivery_order')
                <div class="divRow">
                    <div class="divCell2">{{ $i++ }}</div>
                    <div class="divCell">{{ $product['order_product_details']->alias_name }}</div>
                    <div class="divCell">{{ $product->quantity }}</div>
                    <div class="divCell">
                        @foreach($units as $u)
                        @if($product->unit_id == $u->id)
                        {{$u->unit_name}}
                        @endif
                        @endforeach
                    </div>
                    <div class="divCell">{{ $product->actual_pieces }}</div>
                    <div class="divCell">{{ $product->present_shipping }}</div>

                    <div class="divCell">
                        @if($product->vat_percentage > 0)
                            @if($delivery_data->customer->states)
                                <?php
                                $hsn_code = $product->product_sub_category->product_category->hsn_code;
                                $is_gst = false;
                                if($hsn_code){
                                    $is_gst = true;
                                    $hsn_det = \App\Hsn::where('hsn_code',$hsn_code)->first();
                                    $gst_det = \App\Gst::where('gst',$hsn_det->gst)->first();

                                }
                                ?>
                                @if($is_gst)
                                    @if($delivery_data->customer->states->local_state == 1)
                                        {{$gst_det->sgst + $gst_det->cgst}} %
                                    @else
                                        {{$gst_det->igst}} %
                                    @endif
                                @else
                                    @if($product->vat_percentage > 0){{$delivery_data->vat_percentage}}@else{{"0"}}@endif{{"%"}}
                                @endif
                            @else
                                @if($product->vat_percentage > 0){{$delivery_data->vat_percentage}}@else{{"0"}}@endif{{"%"}}
                            @endif
                        @else
                            @if($product->vat_percentage > 0){{$delivery_data->vat_percentage}}@else{{"0"}}@endif{{"%"}}
                        @endif

                    </div>
                </div>
                @endif
                @endforeach
                @endif
            </div>
            <div class="footer">
                <div class="remark">Total Quantity:</div>               
                <div class="content">{{ isset($delivery_data->total_quantity)?$delivery_data->total_quantity :''}} (Ton)</div>
            </div>
            <div class="footer">
                <div class="remark">Remark:</div>
                <div class="content">{{ isset($delivery_data->remarks) ? $delivery_data->remarks : ''}}</div>
            </div>
        </div>
    </body></html>