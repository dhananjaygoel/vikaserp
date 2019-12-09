<?php

namespace App\Exports;

use App\Territory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TerritoryExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Territory::all();
    // }

    public function view(): View
    {
        $allterritory = \App\Territory::orderBy('created_at', 'DESC')->get();
        return view('excelView.territory', array('allterritory' => $allterritory));
    }
}
