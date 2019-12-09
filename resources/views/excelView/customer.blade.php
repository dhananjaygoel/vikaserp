<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <table>
        <tr>
            <th style="height:20px;font-size:16px;color:#000080;"><b>owner_name</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>company_name</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>contact_person</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>address1</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>address2</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>state_name</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>city_name</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>zip</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>email</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>tally_name</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>phone_number_1</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>phone_number_2</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>excise_number</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>delivery_location</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>user_name</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>password</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>credit_period</b></th>
            <th style="height:20px;font-size:16px;color:#000080;"><b>relationship_manager</b></th>
        </tr>
        <?php
        ini_set('max_execution_time', 720);
//         echo "<pre>";
//            print_r($allcustomers);die;
        foreach ($allcustomers as $value) {
           
            ?>
            <tr>
                <td>{{$value->owner_name}}</td>
                <td>{{$value->company_name}}</td>
                <td>{{$value->contact_person}}</td>
                <td>{{$value->address1}}</td>
                <td>{{$value->address2}}</td>
                <td>{{(isset($value->states) && $value->states->state_name!='')?$value->states->state_name:''}}</td>
                <td>{{(isset($value->getcity) && $value->getcity->city_name!='')?$value->getcity->city_name:''}}</td>
                <td>{{$value->zip}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->tally_name}}</td>
                <td>{{$value->phone_number1}}</td>
                <td>{{$value->phone_number2}}</td>
                <td>{{$value->excise_number}}</td>
                <td>{{(isset($value->deliverylocation) && $value->deliverylocation->area_name!='')?$value->deliverylocation->area_name:''}}</td>
                <td>{{$value->username}}</td>
                <td>-</td>
                <td>{{$value->credit_period}}</td>
                <td>{{(isset($value->manager) && $value->manager->first_name!='' && $value->manager->last_name!='')?$value->manager->last_name:''}}</td>

            </tr>
            <?php
        }
        ?>
    </table>
</html>