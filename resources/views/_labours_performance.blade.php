<?php
//  

$today = date("d", strtotime($enddate));
if (date('m') == date("m", strtotime($enddate)) && date('y') == date("y", strtotime($enddate))) {
    $today = date("d");
}
$today_year = date("Y", strtotime($enddate));
$today_month = date("m", strtotime($enddate));
?> 

<div class="table-responsive day-wise report_table"  id="day-wise" style="display:{{($filter == 'Days')?'block':  'none'}}">
    <table class="table table-bordered complex-data-table ">
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
            <?php foreach ($labours as $labour) { ?>
                <tr>
                    <td rowspan="2"><b>{{$labour->first_name}} {{$labour->last_name}}</B></td>
                    <td><b>Tonnage</b></td>
                    @for($i = 1; $i<= $today ; $i++ )
                    <?php
                    if ($i < 10) {
                        $temp_date = '0' . $i;
                    } else {
                        $temp_date = $i;
                    }
                    $k = 0;
                    $tangage = 0;
                    foreach ($data as $key => $value) {
                        if ($value['date'] == $today_year . '-' . $today_month . '-' . $temp_date) {
                            if ($value['labour_id'] == $labour->id) {
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
                    @for($i = 1; $i<= $today ; $i++ )
                    <?php
                    $dc_id_list = array();
//                    $k = 0;
                    $tangage = 0;
                    if ($i < 10) {
                        $temp_date = '0' . $i;
                    } else {
                        $temp_date = $i;
                    }
                    foreach ($data as $key => $value) {
                        if ($value['date'] == $today_year . '-' . $today_month . '-' . $temp_date) {
                            if ($value['labour_id'] == $labour->id) {
                                array_push($dc_id_list, $value['delivery_id']);
//                                $k++;
                                $tangage +=$value['tonnage'];
                            }
                        }
//                                                       
                    }
                    ?>
                    <td><?php
                        $dc_id_list = array_unique($dc_id_list);
                        ?>{{count($dc_id_list)}}</td>
                    @endfor
                </tr>
            <?php } ?>

        </tbody>
    </table>

</div>


<div class="table-responsive month-wise" id="month-wise" style="display:{{($filter == 'Months')?'block':  'none'}}">
    <table  class="table table-bordered complex-data-table" >
        <tbody>
            <?php
            $month = date('m', strtotime($enddate));
            $year = date('Y', strtotime($enddate));

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
            <?php foreach ($labours as $labour) { ?>
                <tr>
                    <td rowspan="2"><b>{{$labour->first_name}} {{$labour->last_name}}</B></td>
                    <td><b>Tonnage</b></td>
                    @for($i = 1; $i<= $month ; $i++ )
                    <?php
                    $k = 0;
                    $tangage = 0;
                    if ($i < 10) {
                        $temp_month = '0' . $i;
                    } else {
                        $temp_month = $i;
                    }
                    $start_limit = $year . '-' . $temp_month . '-01';
                    $end_limit = $year . '-' . $temp_month . '-31';

                    foreach ($data as $key => $value) {

                        if ($value['date'] >= $start_limit && $value['date'] <= $end_limit) {
                            if ($value['labour_id'] == $labour->id) {

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
                    $dc_id_list = array();
//                    $k = 0;
                    $tangage = 0;
                    if ($i < 10) {
                        $temp_month = '0' . $i;
                    }
                    $start_limit = $year . '-' . $temp_month . '-1';
                    $end_limit = $year . '-' . $temp_month . '-31';

                    foreach ($data as $key => $value) {
                        if ($value['date'] >= $start_limit && $value['date'] <= $end_limit) {
                            if ($value['labour_id'] == $labour->id) {
                                array_push($dc_id_list, $value['delivery_id']);
//                                $k++;
                                $tangage +=$value['tonnage'];
                            }
                        }
                    }
                    ?>
                    <td><?php
                        $dc_id_list = array_unique($dc_id_list);
                        ?>{{count($dc_id_list)}}</td>
                    @endfor
                </tr>
            <?php } ?>
        </tbody>
    </table>          


</div>
