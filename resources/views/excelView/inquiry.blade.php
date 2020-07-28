<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Sr No.</th>
            <th style="height:20px;font-size:16px;color:#000080;">Tally Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Contact Person</th>
            <th style="height:20px;font-size:16px;color:#000080;">Phone Number</th>
            <th style="height:20px;font-size:16px;color:#000080;">Credit Period(Days)</th>
            <th style="height:20px;font-size:16px;color:#000080;">Delivery Location</th>
            <th style="height:20px;font-size:16px;color:#000080;">Freight</th>
            <th style="height:20px;font-size:16px;color:#000080;">Product(Alias)</th>
            <th style="height:20px;font-size:16px;color:#000080;">Quantity</th>
            <th style="height:20px;font-size:16px;color:#000080;">Unit</th>
            <th style="height:20px;font-size:16px;color:#000080;">Price</th>
            <th style="height:20px;font-size:16px;color:#000080;">GST Percentage</th>
            <th style="height:20px;font-size:16px;color:#000080;">Update Price</th>
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>
            <th style="height:20px;font-size:16px;color:#000080;">Expected Delivery Date</th>
            <th style="height:20px;font-size:16px;color:#000080;">Remark</th>

        </tr>
        <?php  $counter = 1; ?>
        @foreach($inquiry_objects as $inquiry)
        <tr>
            <td>{{$counter}}</td>
            <td>
                @if(isset($inquiry['customer']->owner_name) && isset($inquiry['customer']->tally_name) && $inquiry['customer']->owner_name != "" && $inquiry['customer']->tally_name != "")
                {{$inquiry['customer']->owner_name}}{{'-'.$inquiry['customer']->tally_name}}
                @else
                {{isset($inquiry['customer']->owner_name) ? $inquiry['customer']->owner_nam :''}}
                @endif
            </td>
            <td>
                @if(isset($inquiry['customer']))
                {{$inquiry['customer']->contact_person}}
                @endif
            </td>
            <td>
                @if(isset($inquiry['customer']->phone_number1) && $inquiry['customer']->phone_number1 !='')
                {{$inquiry['customer']->phone_number1}}
                @endif
            </td>
            <td>
                @if($inquiry['customer']->credit_period !='' || $inquiry['customer']->credit_period > 0)
                {{$inquiry['customer']->credit_period}}
                @endif
            </td>
            @if($inquiry->delivery_location_id != 0)
                @foreach($delivery_location as $location)
                    @if($inquiry->delivery_location_id == $location->id)
                    <td>{{isset($location->area_name) ?$location->area_name :''}}</td>
                    <td>{{isset($inquiry->location_difference) ? $inquiry->location_difference:""}} </td>
                    @endif
                @endforeach
            @else
                <td>{{isset($inquiry->other_location) ? $inquiry->other_location:""}} </td>
                <td>{{isset($inquiry->location_difference) ? $inquiry->location_difference:""}} </td>
            @endif
            <?php $product_data = isset($inquiry['inquiry_products']) && isset($inquiry['inquiry_products'][0]) ? $inquiry['inquiry_products'][0] : ''; ?>
            @if(isset($product_data))
            <td>{{isset($product_data['inquiry_product_details'])?$product_data['inquiry_product_details']->alias_name: ''}}</td>
            <td>{{isset($product_data->quantity) ? $product_data->quantity:''}}</td>
            <td>{{isset($product_data['unit']->unit_name) ? $product_data['unit']->unit_name:''}}</td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($inquiry->vat_percentage) ? $inquiry->vat_percentage:''}}</td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($product_data->remarks) ? $product_data->remarks:''}}</td>
            @else
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @endif

            <td>{{isset($inquiry->expected_delivery_date) ? date('j F, Y',strtotime($inquiry->expected_delivery_date)):''}}</td>
            <td>{{isset($inquiry->remarks) ? $inquiry->remarks :''}}</td>
        </tr>
        <?php $count = 0; ?>
        @foreach($inquiry['inquiry_products'] as $product_data)
        @if($count!=0)
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{isset($product_data['inquiry_product_details'])?$product_data['inquiry_product_details']->alias_name: ''}}</td>
            <td>{{isset($product_data->quantity) ? $product_data->quantity:''}}</td>
            <td>{{isset($product_data['unit']->unit_name) ? $product_data['unit']->unit_name:''}}</td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($inquiry->vat_percentage) ? $inquiry->vat_percentage:''}}</td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($product_data->remarks) ? $product_data->remarks:''}}</td>
            <td></td>
            <td></td>
        </tr>
        @endif
        <?php $count++; ?>
        @endforeach
        <?php $counter++; ?>
        @endforeach
    </table>
    <?php // exit;?>
</html>
