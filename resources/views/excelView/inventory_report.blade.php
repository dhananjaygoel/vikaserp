<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <table>
        <tbody>
            <tr>                
                <th style="height:20px;font-size:16px;color:#000080;" colspan="1" rowspan="1"><span style="float: left;margin-top: 20px;">{{$product_column}}</span><span style="float: right;margin-top: -10px;">Thickness</span></th> 
                @if(isset($thickness_array))
                    @foreach($thickness_array as $thickness)
                       <th style="height:20px;font-size:16px;color:#000080;">{{$thickness}}</th>
                    @endforeach
                @endif                                            
            </tr>        
            <?php
            ini_set('max_execution_time', 720);                        
            ?>
            @foreach($report_arr as $key=>$record)
            <tr>                
                <td style="height:16px;">{{$key}}</td>                                                                                        
                @if(isset($record))
                    @foreach($record as $key1=>$value)
                    <td style="height:16px;text-align: center;">
                       @if(isset($value) && $value!="-")
                            {{$value}}
                       @else
                            0
                       @endif
                    </td>
                    @endforeach                                         
                @endif                                            
            </tr>
            @endforeach                                        
        </tbody>
    </table>
</html>