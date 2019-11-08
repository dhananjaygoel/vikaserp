<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}

    <table>
        <tr>
            <td class="heading1">Category Name</td>
            <td class="heading1">Price</td>
            <td class="heading1">Unit</td>
            <td class="heading1">Alias Name</td>
            <td class="heading1">Size</td>
            <td class="heading1">Weight</td>
            <td class="heading1">Thickness</td>
            <td class="heading1">Standard Length</td>
            <td class="heading1">Difference</td>
            <td class="heading1">HSN</td>
            <td class="heading1">GST</td>
        </tr>
        @foreach ($product_size_list as $key => $value)
        <tr>
            <td>{{$value->product_category->product_category_name}}</td>
            <td>
                @if(substr($value->difference, 0, 1) == '-')
                {{ $value->product_category->price - substr($value->difference,1) }}
                @else
                {{ $value->product_category->price + $value->difference }}
                @endif
            </td>
            <td>{{$value->product_unit->unit_name}}</td>
            <td>{{$value->alias_name}}</td>
            <td>{{$value->size}}</td>
            <td>{{$value->weight}}</td>
            <td>{{$value->thickness}}</td>
            <td>{{$value->standard_length}}</td>
            <td>{{$value->difference}}</td>
            <td>{{$value->hsn_code}}</td>
            <?php
                if(isset($value->hsn_code) && $value->hsn_code != ''){
                    $hsn_det = \App\Hsn::where('hsn_code',$value->hsn_code)->first();
                    if(isset($hsn_det->gst)){
                        $gstvalue = $hsn_det->gst;
                    }else{
                        $gstvalue = '';
                    }
                    // echo '<pre>';print_r($gst);
                }
            ?>
            <td>{{$gstvalue}}</td>
        </tr>
        @endforeach
        exit;
    </table>
</html>