<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">#</td>
            <td class="heading1">First Name</td>           
            <td class="heading1">Last Name</td>           
            <td class="heading1">Phone Number</td>           
            
        </tr>
        <?php
        ini_set('max_execution_time', 720);
        $k=1;
        foreach ($all_loaded_bies as $value) {
            ?>
            <tr>
                <td>{{$k++}}</td>
                <td>{{$value->first_name}}</td>
                <td>{{$value->last_name}}</td>
                <td>{{$value->phone_number}}</td>
            </tr>
            <?php
        }
        ?>
    </table>
</html>