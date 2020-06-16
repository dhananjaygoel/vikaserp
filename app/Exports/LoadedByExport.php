<?php

namespace App\Exports;

use App\LoadedBy;
use Auth;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;

class LoadedByExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view() : View
    {
        $data = Input::all();

        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';
            $all_loaded_bies = LoadedBy::where('first_name', 'like', $term)->orWhere('last_name', 'like', $term)->orderBy('created_at', 'DESC')->get();
        } else {
            $all_loaded_bies = LoadedBy::orderBy('created_at', 'DESC')->get();
        }

        return view('excelView.loaded_by', array('all_loaded_bies' => $all_loaded_bies));
    }
}
