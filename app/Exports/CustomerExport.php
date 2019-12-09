<?php

namespace App\Exports;

use App\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class CustomerExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Customer::all();
    // }

    public function view(): View
    {
        $allcustomers = Customer::where('customer_status', 'permanent')->with('states', 'getcity', 'deliverylocation', 'manager')->get();
        return view('excelView.customer', array('allcustomers' => $allcustomers));

    }
}
