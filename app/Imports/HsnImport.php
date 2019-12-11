<?php

namespace App\Imports;

use App\Hsn;
use Maatwebsite\Excel\Concerns\ToModel;

class HsnImport implements ToModel
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
        
        return new Hsn([
            'hsn_code' => $row[0],false,
            'hsn_desc' => $row[1],false,
            'gst' => $row[2],false
        ]);
    }
}
