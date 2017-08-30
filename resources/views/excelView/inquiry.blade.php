<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">Sr No.</td>
            <td class="heading1">Tally Name</td>
            <td class="heading1">Contact Person</td>
            <td class="heading1">Phone Number</td>
            <td class="heading1">Credit Period(Days)</td>
            <td class="heading1">Delivery Location</td>
            <td class="heading1">Freight</td>

            <td class="heading1">Product(Alias)</td>
            <td class="heading1">Quantity</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Price</td>
            <td class="heading1">GST Percentage</td>
            <td class="heading1">Update Price</td>

            <td class="heading1">Remark</td>
            <td class="heading1">Expected Delivery Date</td>
            <td class="heading1">Remark</td>

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
            <td>{{isset($product_data['unit']->unit_name) ? $product_data['unit']->unit_name:''}}
            </td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($inquiry->vat_percentage) ? $inquiry->vat_percentage:''}}</td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($product_data->remarks) ? $product_data->remarks:''}}</td>
            @endif

            <td>{{isset($inquiry->expected_delivery_date) ? date('F jS, Y',strtotime($inquiry->expected_delivery_date)):''}}</td>
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
            <td>{{isset($product_data['unit']->unit_name) ? $product_data['unit']->unit_name:''}}
            </td>
            <td>{{isset($product_data->price) ? $product_data->price:''}}</td>
            <td>{{isset($inquiry->vat_percentage) ? $inquiry->vat_percentage:''}}</td>
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
