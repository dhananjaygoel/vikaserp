<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">owner_name</td>
            <td class="heading1">company_name</td>
            <td class="heading1">contact_person</td>
            <td class="heading1">address1</td>
            <td class="heading1">address2</td>
            <td class="heading1">state_name</td>
            <td class="heading1">city_name</td>
            <td class="heading1">zip</td>
            <td class="heading1">email</td>
            <td class="heading1">tally_name</td>
            <td class="heading1">phone_number_1</td>
            <td class="heading1">phone_number_2</td>
            <td class="heading1">excise_number</td>
            <td class="heading1">delivery_location</td>
            <td class="heading1">user_name</td>
            <td class="heading1">password</td>
            <td class="heading1">credit_period</td>
            <td class="heading1">relationship_manager</td>
        </tr>
        <?php
        ini_set('max_execution_time', 720);
        foreach ($allcustomers as $value) {
            ?>
            <tr>
                <td>{{$value->owner_name}}</td>
                <td>{{$value->company_name}}</td>
                <td>{{$value->contact_person}}</td>
                <td>{{$value->address1}}</td>
                <td>{{$value->address2}}</td>
                <td>{{$value->states->state_name}}</td>
                <td>{{$value->getcity->city_name}}</td>
                <td>{{$value->zip}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->tally_name}}</td>
                <td>{{$value->phone_number1}}</td>
                <td>{{$value->phone_number2}}</td>
                <td>{{$value->excise_number}}</td>
                <td>{{$value->deliverylocation->area_name}}</td>
                <td>{{$value->username}}</td>
                <td></td>
                <td>{{$value->credit_period}}</td>
                <td>{{$value->manager->first_name}}</td>
            </tr>
            <?php
        }
        ?>
    </table>
</html>