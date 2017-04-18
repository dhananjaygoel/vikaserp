<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">#</td>
            <td class="heading1">Territory name</td>           
            
        </tr>
        <?php
        ini_set('max_execution_time', 720);
        $k=1;
        foreach ($allterritory as $value) {
            ?>
            <tr>
                <td>{{$k++}}</td>
                <td>{{$value->teritory_name}}</td>               
            </tr>
            <?php
        }
        ?>
    </table>
</html>