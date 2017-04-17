<table id="day-wise" class="table table-bordered text-center complex-data-table">
    <tbody>
        <tr style="width:50px; height:50px;">
            <td class="crossout" colspan="1" rowspan="1"><span class="size-head">Size</span><span class="thickness-head">Thickness</span></td> 
            @if(isset($product_last))
                <?php //  dd($product_last[0]); ?>
                @foreach($product_last[0]['product_sub_categories'] as $sub_cat)
                   <td>{{$sub_cat->thickness}}</td>
                @endforeach
            @endif                                            
        </tr>

        @foreach($product_last[0]['product_sub_categories'] as $sub_cat)
        <tr>
            <?php // echo $sub_cat; ?>
            <td>{{$sub_cat->size}}</td>
            <td>
            <?php // print_r($sub_cat['product_inventory']); ?>
            @if(isset($sub_cat['product_inventory']))
                <?php $inventory= $sub_cat['product_inventory']; ?>                                                
                    <?php 
//                                                    dd($inventory);
                        $total_qnty=0;
                        if(isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)){
                            $total_qnty = $inventory->physical_closing_qty+$inventory->pending_purchase_advise_qty;
                        }else{
                            $total_qnty = "-";
                        }                                                    
                    ?>
                    {{$total_qnty}}                                                
            @endif 
            </td>
        </tr>
         @endforeach

    </tbody>
</table>