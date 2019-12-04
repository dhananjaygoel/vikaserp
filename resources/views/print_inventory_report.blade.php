<html>
    <head>
        <title>Delivery Order</title>
        <meta charset="windows-1252">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    </head>
    <body>
<style>
.crossout{
    /*width: 120px;*/
   min-width: 150px;
   min-width: 150px;
   width: 150px;
   background-image: linear-gradient(to bottom left,  transparent calc(50% - 1px), #DDDDDD, transparent calc(50% + 1px));
}
.thickness-head{
   float: right;
   margin-top: -10px;
}
.size-head{
    float: left;
    margin-top: 20px;
}

.table {
    margin-bottom: 6px !important;
}
.table-bordered {
    border: 1px solid #ddd;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
}
.text-center {
    text-align: center;
}
.table-bordered {
    border: 1px solid #ddd;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
}
.text-center {
    text-align: center;
}
table {
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
.table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
    border-top: 1px solid #ddd;
    line-height: 1.42857;
    padding: 8px;
    vertical-align: top;
}
</style>
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
                    </td>
                    @endforeach                                         
                @endif                                            
            </tr>
            @endforeach                                        
        </tbody>
    </table>
</body>
</html>