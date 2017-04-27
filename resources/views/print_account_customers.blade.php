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
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
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
        ?>
        @if($due_date>=$current_date)
        <tr>
            <td class="col-md-1">{{$i++}}</td>
            <td>@if(isset($c->tally_name) && !empty($c->tally_name)){{$c->tally_name}}@endif</a></td>
               <?php
                    $total_due_amount=0;
                    foreach($c['delivery_challan'] as $challan){
                        $total_due_amount=$total_due_amount+$challan->grand_price;
                        $settled_amount=0;
                        foreach($challan['challan_receipt'] as $receipt){
                            $settled_amount=$settled_amount+$receipt->settled_amount;
                        }
                    }
                ?>
            <td>{{$total_due_amount}}</td>
            <td>
                {{$total_due_amount-$settled_amount}}
            </td>
            <td>                                            
                @foreach($delivery_location as $location)
                    @if($c->delivery_location_id==$location->id)
                            {{$location->area_name}}                                                         
                    @endif
                @endforeach
            </td>                                        
        </tr>
        @endif
    @endforeach
    </tbody>
</table>