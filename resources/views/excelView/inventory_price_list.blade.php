<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    
    <table id="day-wise" class="table table-bordered text-center complex-data-table">
        <tbody>
            <tr style="width:50px; height:50px;">                
                <td class="crossout" colspan="1" rowspan="1"><span class="size-head">{{$product_column}}</span><span class="thickness-head">Thickness</span></td> 
                @if(isset($thickness_array))
                    @foreach($thickness_array as $thickness)
                       <td>{{$thickness}}</td>
                    @endforeach
                @endif                                            
            </tr>        
            <?php
            ini_set('max_execution_time', 720);                        
            ?>
            @foreach($report_arr as $key=>$record)
            <tr class="text-center">                
                <td>{{$key}}</td>                                                                                        
                @if(isset($record))
                    @foreach($record as $key1=>$value)
                    <td class="text-center">
                       @if(isset($value) && $value!="-")
                            {{$value}}
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
</html>