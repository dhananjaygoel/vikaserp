<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;">#</th>
            <th style="height:20px;font-size:16px;color:#000080;">Territory name</th>           
            
        </tr>
        <?php
        ini_set('max_execution_time', 720);
        $k=1;
        foreach ($allterritory as $value) {
            ?>
            <tr>
                <td style="height:16px;">{{$k++}}</td>
                <td style="height:16px;">{{$value->teritory_name}}</td>               
            </tr>
            <?php
        }
        ?>
    </table>
</html>