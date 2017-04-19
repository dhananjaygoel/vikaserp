<div class="table-responsive report_table">
                                <table id="day-wise" class="table table-bordered complex-data-table">
                                    <tbody>
                                            <?php  $today = date("t", strtotime($date)); ?>
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
                                            <?php $date_val = substr($date,0,8); ?>
                                            @foreach($loaded_by as $loader_val)
                                            <tr>
                                                <td rowspan="2"><b>{{$loader_val->first_name}}</B></td>                                                
                                                <td><b>Tonnage</b></td>    
                                                @for($i = 1; $i<= $today ; $i++ )
                                                <?php 
                                                $k=0;
                                                $tangage=0;
                                                    foreach ($final_array as $key => $value) {
                                                        if($value['date']=="$date_val".$i){
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
                                                    @for($i = 1; $i<= $today ; $i++ )
                                                    <?php 
                                                   $k=0;
                                                   $tangage=0;
                                                       foreach ($final_array as $key => $value) {
                                                           if($value['date']=='2017-04-'.$i){
                                                               if($value['loader_id'] == $loader_val->id){
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