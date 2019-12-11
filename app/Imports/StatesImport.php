<?php

namespace App\Imports;

use App\States;
use Maatwebsite\Excel\Concerns\ToModel;

class StatesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new States([
            'state_name' => $row[0],false,
            'local_state' => $row[1],false
        ]);
    }
}
