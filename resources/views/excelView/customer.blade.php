<html>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {!! HTML::style('/resources/assets/css/custom_style/excel-export-table.css') !!}
    <table>
        <tr>
            <td class="heading1">Name</td>
            <td class="heading1">Company</td>
            <td class="heading1">Customer Type</td>
            <td class="heading1">Email</td>
            <td class="heading1">Phone</td>
            <td class="heading1">Mobile</td>
            <td class="heading1">Street</td>
            <td class="heading1">City</td>
            <td class="heading1">State</td>
            <td class="heading1">PIN Code</td>
            <td class="heading1">Country</td>
            <td class="heading1">Date</td>
            <td class="heading1">GSTIN</td>
        </tr>
        <?php
        ini_set('max_execution_time', 720);
        foreach ($allcustomers as $value) {
            ?>
            <tr>
                <td>{{$value->tally_name}}</td>
                <td>{{$value->company_name}}</td>
                <td>Retail Trade</td>
                <td>{{$value->email}}</td>
                <td>{{$value->phone_number1}}</td>
                <td>{{$value->phone_number2}}</td>
                <td>{{$value->address1}}</td>
                <td>{{(isset($value->getcity) && $value->getcity->city_name!='')?$value->getcity->city_name:''}}</td>
                <td>{{(isset($value->states) && $value->states->state_name!='')?$value->states->state_name:''}}</td>
                <td>{{$value->zip}}</td>
                <td>India</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->gstin_number}}</td>
            </tr>
            <?php
        }
        ?>
    </table>
</html>