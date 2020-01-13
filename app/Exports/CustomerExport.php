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
        $customer_filter = Input::get('customer_filter');
        if($customer_filter == "supplier"){
            $value = 'no';
        } else {
            $value = '';
        }
        $allcustomers = Customer::where('customer_status', 'permanent')->where('is_supplier', $value)->with('states', 'getcity', 'deliverylocation', 'manager')->get();
        return view('excelView.customer', array('allcustomers' => $allcustomers));

    }
}
