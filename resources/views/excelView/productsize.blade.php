<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('assets/css/custom_style/excel-export-table.css') !!}

    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">Category Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Price</th>
            <th style="height:20px;font-size:16px;color:#000080;">Unit</th>
            <th style="height:20px;font-size:16px;color:#000080;">Alias Name</th>
            <th style="height:20px;font-size:16px;color:#000080;">Size</th>
            <th style="height:20px;font-size:16px;color:#000080;">Weight</th>
            <th style="height:20px;font-size:16px;color:#000080;">Thickness</th>
            <th style="height:20px;font-size:16px;color:#000080;">Standard Length</th>
            <th style="height:20px;font-size:16px;color:#000080;">Difference</th>
            <th style="height:20px;font-size:16px;color:#000080;">HSN</th>
            <th style="height:20px;font-size:16px;color:#000080;">GST</th>
        </tr>
        @foreach ($product_size_list as $key => $value)
        <tr>
            <td style="height:16px;">{{$value->product_category->product_category_name}}</td>
            <td style="height:16px;">
                @if(substr($value->difference, 0, 1) == '-')
                {{ $value->product_category->price - substr($value->difference,1) }}
                @else
                {{ $value->product_category->price + $value->difference }}
                @endif
            </td>
            <td style="height:16px;">{{$value->product_unit->unit_name}}</td>
            <td style="height:16px;">{{$value->alias_name}}</td>
            <td style="height:16px;">{{$value->size}}</td>
            <td style="height:16px;">{{$value->weight}}</td>
            <td style="height:16px;">{{$value->thickness}}</td>
            <td style="height:16px;">{{$value->standard_length}}</td>
            <td style="height:16px;">{{$value->difference}}</td>
            <td style="height:16px;">{{$value->hsn_code}}</td>
            <?php
                if(isset($value->hsn_code) && $value->hsn_code != ''){
                    $hsn_det = \App\Hsn::where('hsn_code',$value->hsn_code)->first();
                    if(isset($hsn_det->gst)){
                        $gstvalue = $hsn_det->gst;
                    }else{
                        $gstvalue = '';
                    }
                }
            ?>
            <td style="height:16px;">{{$gstvalue}}.0% GST</td>
        </tr>
        @endforeach
        exit;
    </table>
</html>