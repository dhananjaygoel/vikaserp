<?php
//  

$today = date("d", strtotime($enddate));
if (date('m') == date("m", strtotime($enddate)) && date('y') == date("y", strtotime($enddate))) {
    $today = date("d");
}
?> 
<div class="table-responsive" >
    <table id="month-wise" class="table table-bordered complex-data-table">
        <tbody>
            <?php
//            $today = date("d", strtotime($enddate));

            $month = date('m', strtotime($enddate));
            if (date('y') == date("y", strtotime($enddate))) {
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

                    foreach ($data as $key => $value) {
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

                    foreach ($data as $key => $value) {
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