<?php

namespace App\Imports;

use App\Thickness;
use Maatwebsite\Excel\Concerns\ToModel;

class ThicknessImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Thickness([
            'thickness' => $row[0],false,
            'difference' => $row[1],false
        ]);
    }
}
