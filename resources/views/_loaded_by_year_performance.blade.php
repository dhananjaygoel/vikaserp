<div class="table-responsive" >
    <table id="month-wise" class="table table-bordered complex-data-table">
        <tbody>
            <tr>
                <td colspan="2" rowspan="1"></td>
                <td colspan="31"><b>Month</b></td>
            </tr>
            
            <tr class="text-bold">
                <td colspan="2"></td>
                <?php for ($m=1; $m<=12; $m++) {
                    $month = date('F', mktime(0,0,0,$m, 1, date('Y'))); ?>
                    <td>{{ $month }}</td>
                <?php } ?>
            </tr>
            @if(isset($loaded_by))
            
                <?php 
//                $date = date("Y-m",  strtotime($date));
//                echo $date;
//                $date_val = substr($date,0,8); 
//                $date_val = substr($date,0,8); ?>
                @foreach($loaded_by as $loader_val)
                <tr>
                    <td rowspan="2"><b>{{$loader_val->first_name}}</B></td>
                    <td><b>Tonnage</b></td>
                    @for($i = 1; $i<= 12 ; $i++ )
                        <?php 
                        $k=0;
                        $tangage=0;
                            foreach ($final_array as $key => $value) {
                                if($value['date']== $date){
                                    if($value['loader_id'] == $loader_val->id){
                                        $k++;
                                        $tangage +=$value['tonnage'];
                                    }
                                }
                            }
                        ?>
                    <td>{{ number_format((float)$tangage, 2, '.', '') }}</td>
                    @endfor
                    <tr>
                        <td><b>Delivery</b></td>
                    </tr>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>