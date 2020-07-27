<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Sr No.</th>
            <th style="height:20px;font-size:16px;color:#000080;">Tally Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Contact Person</th>
            <th style="height:20px;font-size:16px;color:#000080;">Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Serial Number</th>
            <th style="height:20px;font-size:16px;color:#000080;">Mobile Number</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Location</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Freight</th>
            <th style="height:20px;font-size:16px;color:#000080;">Product(Alias)</th>
            <th style="height:20px;font-size:16px;color:#000080;">Present shipping</th>
            <th style="height:20px;font-size:16px;color:#000080;">Unit</th>
            <th style="height:20px;font-size:16px;color:#000080;">Price</th>
            <th style="height:20px;font-size:16px;color:#000080;">GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>
            <th style="height:20px;font-size:16px;color:#000080;">Vehicle No.</th>
            <th style="height:20px;font-size:16px;color:#000080;">Driver Contact</th>
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>
            <th style="height:20px;font-size:16px;color:#000080;">Order By</th>
            <th style="height:20px;font-size:16px;color:#000080;">Order Time/Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Order By</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Order Time/Date</th>
        </tr>
        <?php $counter=1;?>
        @foreach ($delivery_order_objects as $delivery_data)
        <tr>
            <td>{{$counter}}</td>
            <td>
                @if(isset($delivery_data['customer']->owner_name) && isset($delivery_data['customer']->tally_name) &&  $delivery_data['customer']->owner_name != "" && $delivery_data['customer']->tally_name != "")
                {{ $delivery_data['customer']->owner_name }}-{{$delivery_data['customer']->tally_name}}
                @else 
                {{ isset ($delivery_data['customer']->owner_name)?$delivery_data['customer']->owner_name:'N/A' }}
                @endif
            </td>
            <td>
                @if(isset($delivery_data['customer']->contact_person) && $delivery_data['customer']->contact_person != "")
                {{ $delivery_data['customer']->contact_person}}
                @endif
            </td>
            <td>
                @if(isset($delivery_data['created_at']) && $delivery_data['created_at']!='')
                {{date('j F, Y', strtotime ($delivery_data['created_at']))}}
                @endif
            </td>
            <td>
                {{(isset($delivery_data->serial_no) && $delivery_data->serial_no != "") ? $delivery_data->serial_no : '--'}}
            </td>
            <td>
                {{(isset($delivery_data['customer']->phone_number1) && $delivery_data['customer']->phone_number1 != "") ? $delivery_data['customer']->phone_number1: ''}}
            </td>
            <td>
                @if(isset($delivery_data->delivery_location_id) && $delivery_data->delivery_location_id == 0)
                {{$delivery_data->other_location}}
                @else
                @foreach($delivery_locations as $location)
                @if($location->id == $delivery_data->delivery_location_id)
                {{$location->area_name}}
                @endif
                @endforeach
                @endif
            </td>
            <td>
                {{(isset($delivery_data->location_difference) && $delivery_data->location_difference != "") ? $delivery_data->location_difference : ''}}
            </td>
            <?php $product = isset($delivery_data['delivery_product']) && isset($delivery_data['delivery_product'][0]) ? $delivery_data['delivery_product'][0] : ''; ?>
            @if(isset($product) && $product!='' && $product->order_type =='delivery_order')
            <td> {{ $product['order_product_details']->alias_name}}</td>
            <td>{{$product->present_shipping}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>₹ {{$product->price}}</td>
            <td>{{($delivery_data->vat_percentage!='')?$delivery_data->vat_percentage:''}}</td>
            <td>{{$product->remarks}}</td>
            @endif
            
            <td>{{(isset($delivery_data->vehicle_number) && $delivery_data->vehicle_number != "") ? $delivery_data->vehicle_number : ''}}</td>
            <td>{{(isset($delivery_data->driver_contact_no) && $delivery_data->driver_contact_no != "") ? $delivery_data->driver_contact_no : ''}}</td>
            <td>{{(isset($delivery_data->remarks) && $delivery_data->remarks != "") ? $delivery_data->remarks : ''}}</td>
            <td>{{(isset($delivery_data->order_details->createdby->first_name) && $delivery_data->order_details->createdby->first_name != "" && isset($delivery_data->order_details->createdby->last_name) && $delivery_data->order_details->createdby->last_name != "") ? $delivery_data->order_details->createdby->first_name." ".$delivery_data->order_details->createdby->last_name : ''}}</td>
            <td>{{(isset($delivery_data->order_details->updated_at) && $delivery_data->order_details->updated_at != "") ? date('j F, Y h:i A' ,strtotime($delivery_data->order_details->updated_at)) : ''}}</td>
            <td>{{(isset($delivery_data->user->first_name) && isset($delivery_data->user->last_name)) ? $delivery_data->user->first_name." ".$delivery_data->user->last_name : ''}}</td>
            <td>{{(isset($delivery_data->updated_at)) ? date('j F, Y h:i A',strtotime($delivery_data->updated_at)) : ''}}</td>
        </tr>
        <?php $count = 0; ?>
        @foreach($delivery_data['delivery_product'] as $product)
        @if($count!=0 && isset($product->order_type) &&  $product->order_type =='delivery_order')
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$product['order_product_details']->alias_name}}</td>
            <td>{{$product->present_shipping}}</td>
            <td>
                @foreach($units as $unit)
                {{($unit->id == $product->unit_id)? $unit->unit_name:''}}
                @endforeach
            </td>
            <td>₹ {{$product->price}}</td>
            <td>{{($delivery_data->vat_percentage!='')?$delivery_data->vat_percentage:''}}</td>
            <td>{{$product->remarks}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endif
        <?php $count++;?>
        @endforeach
        <?php $counter++;?>
        @endforeach
    </table>
    <?php // exit;?>
</html>
