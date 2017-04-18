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
        <tr>                                            
            <td>{{$key}}</td>                                                                                        
            @if(isset($record))
                @foreach($record as $value)
                <td>    
                   @if(isset($value))
                        {{$value}}
                   @else
                        {{"-"}}     
                   @endif
                @endforeach
                </td>
            @endif                                             
        </tr>
        @endforeach                                        
    </tbody>
</table>