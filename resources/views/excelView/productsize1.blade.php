<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}

    <table>
        <tr>
            <td class="heading1">Product/Service Name</td>
            <td class="heading1">Sales Description</td>
            <td class="heading1">Sales Price / Rate</td>
            <td class="heading1">Purchase Cost</td>
            <td class="heading1">HSN/SAC</td>
            <td class="heading1">Type</td>
            
        </tr>
        @foreach ($product_size_list as $key => $value)
        <tr>
            <td>{{$value->alias_name}}</td>
            <td>{{$value->alias_name}}</td>
            <td>
                @if(substr($value->difference, 0, 1) == '-')
                {{ $value->product_category->price - substr($value->difference,1) }}
                @else
                {{ $value->product_category->price + $value->difference }}
                @endif
            </td>
            <td>
                @if(substr($value->difference, 0, 1) == '-')
                {{ $value->product_category->price - substr($value->difference,1) }}
                @else
                {{ $value->product_category->price + $value->difference }}
                @endif
            </td>
            <td>{{$value->hsn_code}}</td>
            <td>Noninventory</td>
         
        </tr>
        @endforeach
    </table>
</html>