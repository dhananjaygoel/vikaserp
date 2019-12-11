<?php

namespace App\Imports;

use App\Gst;
use Maatwebsite\Excel\Concerns\ToModel;

class GstImport implements ToModel
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

        return new Gst([
            'gst' => $row[0],false,
            'sgst' => $row[1],false,
            'cgst' => $row[2],false,
            'igst' => $row[3],false,
            'quick_gst_id' => $row[4],false,
            'quick_igst_id' => $row[5],false,
        ]);
    }
}
