<?php

namespace App\Exports;

use App\Labour;
use Auth;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class LoboursExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view() : View
    {
        $data = Input::all();
        $alllabours = '';

        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';

            $alllabours = Labour::orderBy('first_name', 'asc')
                    ->where(function($query) use ($term) {
                        $query->where('first_name', 'like', $term)
                        ->orWhere('last_name', 'like', $term)
                        ->orWhere('phone_number', 'like', $term);
                    })
                    ->get();
        } else {
            $alllabours = Labour::orderBy('updated_at', 'desc')->get();
        }

        return view('excelView.labours', array('alllabours' => $alllabours));
    }
}
