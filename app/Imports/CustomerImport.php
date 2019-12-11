<?php

namespace App\Imports;

use App\Customer;
use App\DeliveryLocation;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!isset($row)){
            return null;
        }
        
        return ([
            'owner_name' => $row[0],
            'company_name' => $row[1],false,
            'contact_person' => $row[2],false,
            'address1' => $row[3],false,
            'address2' => $row[4],false,
            'state_name'=> $row[5],false,
            'city_name' => $row[6],false,
            'zip' => $row[7],false,
            'email' => $row[8],false,
            'tally_name' => $row[9],
            'phone_number1' => $row[10],
            'phone_number2' => $row[11],false,
            'excise_number' => $row[12],false,
            'delivery_location' => $row[13],false,
            'user_name' => $row[14],false,
            'password' => Hash::make((string) $row[15]),false,
            'credit_period' => $row[16],false,
            'relationship_manager' => $row[17],false,
        ]);
    }
    public function rules(): array
    {
        return [
            '0' => function($attribute, $value, $onFailure) {
                if ($value == "") {
                     $onFailure('owner_name is invalid please check excel file.');
                }
            }
        ];
    }
    
}
