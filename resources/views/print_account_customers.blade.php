<style>
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
.table-bordered {
    border: 1px solid #ddd;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
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
<table id="table-example" class="table table-hover">
    <thead>
        <tr>
            <th class="col-md-1">#</th>
            <th>Customer Name</th>
            <th>Due Amount</th>
            <th>Unsettled Amount</th>
            <th>Location</th>
            @if(Auth::user()->role_id ==0)
                <th>Collection User</th>
            @endif
        </tr>
    </thead>
    <tbody>
        <?php
        $i =1;
        ?>
        @foreach($customers as $c)
        <?php                                        
            $total_due_amount=0;
            $credit_period = $c->credit_period;
            foreach($c['delivery_challan'] as $challan){
                $challan_date = $challan->created_at;
                $due_date = date('Y-m-d', strtotime($challan_date. " + ".$credit_period." days"));
            }
            $current_date = date('Y-m-d'); 
            $limit_date = date('Y-m-d');
//                                        if(Input::get('date_filter')!=""){
//                                            $days = Input::get('date_filter');
//                                            $limit_date = date('Y-m-d', strtotime($current_date. " + ".$days." days"));
//                                        }                                        
        ?>                                    
        <tr>
            <td class="col-md-1">{{$i++}}</td>
            <td>@if(isset($c->tally_name) && !empty($c->tally_name)){{$c->tally_name}}@endif</td>
               <?php
                    $total_due_amount=0;
                    $unsettled_amount=0;
                    foreach($c['delivery_challan'] as $challan){
                        $total_due_amount=$total_due_amount+$challan->grand_price;                                                                                                       
                    }
                    foreach($c['customer_receipt'] as $receipt){
                       $unsettled_amount=$unsettled_amount+$receipt->settled_amount;
                    }
                ?>
            <td>{{$total_due_amount}}</td>
            <td>
                {{$unsettled_amount}}
            </td>
            <td>                                            
                @foreach($delivery_location as $location)
                    @if($c->delivery_location_id==$location->id)
                            {{$location->area_name}}                                                         
                    @endif
                @endforeach
            </td>
            @if(Auth::user()->role_id ==0)
            <td>
                <?php 
                    if(isset($c['collection_user_location'])){
                        $del_locations = $c['collection_user_location'];
                        foreach($del_locations as $del_loc){
                            if(isset($del_loc['collection_user'][0])){
                                echo $del_loc['collection_user'][0]->first_name;
                                echo " ";
                                echo $del_loc['collection_user'][0]->last_name;
                            }
                        }
                    }
                ?>
            </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>