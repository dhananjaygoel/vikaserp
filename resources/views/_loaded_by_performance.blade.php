<?php
$today = date("t", strtotime($date));
if (date('m') == date("m", strtotime($date)) && date('y') == date("y", strtotime($date))) {
    $today = date("d");
}
?>
<div class="table-responsive report_table" style="display:{{($filter_with == 'days')?'block':  'none'}}">
    <table id="day-wise" class="table table-bordered complex-data-table">
        <tbody>            
            <tr>
                <td colspan="2" rowspan="1"></td>
                <td colspan="{{$today}}"><b>Date</b></td>
            </tr>
            <tr class="text-bold">
                <td colspan="2"></td>
                @for($i = 1; $i<= $today ; $i++ )
                <td>{{ $i }}</td>
                @endfor
            </tr>
            @if(isset($loaded_by))
            <?php $date_val = substr($date, 0, 8); ?>
            @foreach($loaded_by as $loader_val)
            <tr>
                <td rowspan="2"><b>{{$loader_val->first_name}} {{$loader->last_name}}</B></td>                                                
                <td><b>Tonnage</b></td>    
                @for($i = 1; $i<= $today ; $i++ )
                <?php
                $k = 0;
                $tangage = 0;
                foreach ($final_array as $key => $value) {
                    if ($value['date'] == "$date_val" . $i) {
                        if ($value['loader_id'] == $loader_val->id) {
                            $k++;
                            $tangage +=$value['tonnage'];
                        }
                    }
                }
                ?>
                <td>{{ $tangage }}</td>
                @endfor
            <tr>
                <td><b>Delivery</b></td>
                @for($i = 1; $i<= $today ; $i++ )
                <?php
                $k = 0;
                $tangage = 0;
                foreach ($final_array as $key => $value) {
                    if ($value['date'] == "$date_val" . $i) {
                        if ($value['loader_id'] == $loader_val->id) {
                            $k++;
                            $tangage +=$value['tonnage'];
                        }
                    }
                }
                ?>
                <td>{{$k}}</td>
                @endfor
            </tr>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<div class="table-responsive" style="display:{{($filter_with == 'months')?'block':  'none'}}">
    <table id="month-wise" class="table table-bordered complex-data-table">
        <tbody>
            <?php
                $month = date('m', strtotime($date));
                if (date('Y') == date("Y", strtotime($date))) {
                    $month = date('m');
                }
            ?>
            <tr>
                <td colspan="2" rowspan="1"></td>
                <td colspan="{{$month}}"><b>Month</b></td>
            </tr>
            <tr class="text-bold">
                <td colspan="2"></td>
                @for($i = 1; $i<= $month ; $i++ )
                <td>{{ date('F', mktime(0, 0, 0, $i)) }}</td>
                @endfor
            </tr>
            <?php foreach ($loaded_by as $loader) { ?>
                <tr>
                    <td rowspan="2"><b>{{$loader->first_name}} {{$loader->last_name}}</B></td>
                    <td><b>Tonnage</b></td>
                    @for($i = 1; $i<= $month ; $i++ )
                    <?php
                    $k = 0;
                    $tangage = 0;
                    if ($i < 10) {
                        $temp_month = '0' . $i;
                    }
                    $start_limit = '2017-' . $temp_month . '-1';
                    $end_limit = '2017-' . $temp_month . '-31';

                    foreach ($final_array as $key => $value) {
                        if ($value['date'] >= $start_limit && $value['date'] <= $end_limit) {
                            if ($value['loader_id'] == $loader->id) {

                                $k++;
                                $tangage +=$value['tonnage'];
                            }
                        }
//                                                       
                    }
                    ?>
                    <td>{{$tangage}}</td>
                    @endfor
                </tr>
                <tr>
                    <td><b>Delivery</b></td>
                    @for($i = 1; $i<= $month ; $i++ )
                    <?php
                    $k = 0;
                    $tangage = 0;
                    if ($i < 10) {
                        $temp_month = '0' . $i;
                    }
                    $start_limit = '2017-' . $temp_month . '-1';
                    $end_limit = '2017-' . $temp_month . '-31';

                    foreach ($final_array as $key => $value) {
                        if ($value['date'] >= $start_limit && $value['date'] <= $end_limit) {
                            if ($value['loader_id'] == $loader->id) {

                                $k++;
                                $tangage +=$value['tonnage'];
                            }
                        }
                    }
                    ?>
                    <td>{{$k}}</td>
                    @endfor
                </tr>
        <?php } ?>
        </tbody>
    </table>
</div>