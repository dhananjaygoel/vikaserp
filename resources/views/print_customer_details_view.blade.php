<style>
    .customer-detail-table b{
        margin-right: 10px;
    }
    #settle_due_modal .modal-content{
        margin: 210px auto;
        width: 400px;
    }
    .modal-settle-div{
         width: 40%;
         margin-left: 30%
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
    <tbody>
        <?php
            $total_due_amount=0;
            $unsettled_amount=0;
            $credit_period = $customer->credit_period; 
        ?>                                                                        
        <tr>
            <td><b>Customer Name:</b>
                @if(isset($customer->tally_name) && !empty($customer->tally_name)){{$customer->tally_name}} @endif
            </td>
        </tr>
        <tr>
            <td><b>Location: </b>
                @foreach($delivery_location as $location)
                    @if($customer->delivery_location_id==$location->id)
                            {{$location->area_name}}                                                         
                    @endif
                @endforeach
            </td>
        </tr>                                    
        <tr>
            <?php
                foreach($customer['customer_receipt'] as $receipt){
                    $unsettled_amount=$unsettled_amount+$receipt->settled_amount;
                }
            ?>
            <?php
                $total_due_amount=0;
                $settled_challan_amount=0;
                foreach($customer['delivery_challan'] as $challan){
                    $total_due_amount=$total_due_amount+$challan->grand_price;
                    $settled_challan_amount= $settled_challan_amount+$challan->settle_amount;
                }
                $total_due_amount=$total_due_amount-$settled_challan_amount;
                $unsettled_amount= $unsettled_amount-$settled_challan_amount;
            ?>
            <td><b>Unsettled Amount:</b> 
                @if(isset($unsettled_amount))
                    {{$unsettled_amount}}
                @endif
            </td>
        </tr>                                    
        <tr>
            <td><b>@if(Auth::user()->role_id ==6 )
                        Total Due Amount: 
                    @endif
                    @if(Auth::user()->role_id ==0 )
                        Total Due Payment: 
                    @endif
                </b>
                {{$total_due_amount}}
            </td>
        </tr>
        <tr>
            <td><b>Credit Period:</b> {{$credit_period}} days</td>
        </tr>
    </tbody>
</table>                            
<span class="clearfix"></span><br>
<span class="clearfix"></span>                            

<table id="table-example2" class="table table-hover">
    <thead>
        <tr>
            <th class="col-md-1">#</th>
            <th>Delivery Challan</th>
            <th>Due date</th>
            <th>Total Amount</th>
            <th>Settled Amount</th>
            @if(Input::get('settle_filter')!='Settled')
                <th>Due Payment</th>
            @endif                                           
        </tr>
    </thead>
    <?php //dd($delivery_challans); ?>
    <tbody>                                        
            @if(isset($delivery_challans) && count($delivery_challans)>0 && $delivery_challans!="")
            @foreach($delivery_challans as $challan)
                <?php
                    $i=1;$total_due_amount=0; $settled_amount=0;                                            
                ?>
                <?php    
                    $total_due_amount=$total_due_amount+$challan->grand_price;                                                
                    if(isset($challan->settle_amount) && $challan->settle_amount!=""){
                        $settled_amount=$challan->settle_amount;
                    }                                              
                ?>                                        
            <tr>
                <td>{{$i++}}</td>
                <td>{{$challan->serial_number}}</td>
                <td><?php
                    $challan_date = $challan->created_at;
                    $due_date = date('Y-m-d', strtotime($challan_date. " + ".$credit_period." days"));                                            
                    ?>
                    {{$due_date}}
                </td>                                            
                <td>
                    {{$total_due_amount}}
                </td>
                <td>
                     {{$settled_amount}}
                </td>
                @if(Input::get('settle_filter')!='Settled')
                <td>
                    {{$total_due_amount-$settled_amount}}
                </td>
                @endif
                @if(Auth::user()->role_id ==6 )
                <td>
                    <button class="btn btn-primary settle-payment"  data-serial_no="{{$challan->serial_number}}" data-challan_id="{{$challan->id}}" data-due_amount="{{$total_due_amount-$settled_amount}}" >
                        Settle
                    </button>
                </td>
                @endif                
            </tr>                                        
            @endforeach
            @else
            <tr><td></td><td>No records Available</td></tr>
            @endif
    </tbody>
</table>