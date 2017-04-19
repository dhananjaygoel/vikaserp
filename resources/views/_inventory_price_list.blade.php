<table id="day-wise" class="table table-bordered text-center complex-data-table">
    <tbody>
        <tr style="width:50px; height:50px;">
            <td class="crossout" colspan="1" rowspan="1"><span class="size-head">Size</span><span class="thickness-head">Thickness</span></td> 
            @if(isset($thickness_array))
                @foreach($thickness_array as $thickness)
                   <td>{{$thickness}}</td>
                @endforeach
            @endif                                            
        </tr>                                        
        @foreach($report_arr as $key=>$record)
        <tr class="text-center">                                            
            <td>{{$key}}</td>                                                                                        
            @if(isset($record))
                @foreach($record as $key1=>$value)
                <td class="text-center">
                   @if(isset($value) && $value!="-")
                   <input class="form-control inventory-price-value" type="text" value="{{$value}}" data-size="{{$key}}"  data-thickness="{{$key1}}" data-product="@if(isset($product_id)){{$product_id}}@endif">
                   @else
                        {{$value}}     
                   @endif
                </td>
                @endforeach                                         
            @endif                                            
        </tr>
        @endforeach                                        
    </tbody>
</table>