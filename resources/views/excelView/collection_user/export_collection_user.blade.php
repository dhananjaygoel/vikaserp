<div class="table-responsive">
    <table id="table-example" class="table table-hover">
        <thead>
            <tr>
                <th class="col-md-1">SR No.</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Mobile</th>              
                <th>Location</th>                              
            </tr>
        </thead>
        <tbody>    
            @if(isset($users) && !empty($users) && count($users)>0) 
            @foreach($users as $key => $user)
            <tr>
                <td class="col-md-1">{{ $key+1 }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>                
                <td>{{ $user->mobile_number }}</td>
                <td>
                <?php $tarr = []; ?>
                @foreach($user->locations as $loc)
                    <?php array_push($tarr, $loc->location_data->area_name) ?>
                @endforeach
                {{ implode(',',$tarr)}}
                </td>                
            </tr> 
            @endforeach            
            @endif            
        </tbody>
    </table>
</div> 