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
        // dd(Input::all());
        $customers = '';
        $customer_filter = Input::get('customer_filter');
        $customers = Customer::orderBy('tally_name', 'asc');

        if (Input::get('search') != '') {
            $term = '%' . Input::get('search') . '%';

            $customers = $customers->where(function($query) use($term) {
                        $query->whereHas('city', function($q) use ($term) {
                            $q->where('city_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('deliverylocation', function($q) use ($term) {
                            $q->where('area_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function($query) use($term) {
                        $query->whereHas('manager', function($q) use ($term) {
                            $q->where('first_name', 'like', $term)
                            ->where('customer_status', '=', 'permanent');
                        });
                    })
                    ->orWhere(function ($query1) use($term){
                        $query1->Where('tally_name', 'like', $term)
                            ->orWhere('phone_number1', 'like', $term)
                            ->orWhere('phone_number2', 'like', $term);
                    })
                    ->where('customer_status', '=', 'permanent');

        }
        if (isset($customer_filter) && !empty($customer_filter)) {
            if($customer_filter=='supplier'){
                $customers = $customers->where('is_supplier', '=', 'yes');
            }
            elseif($customer_filter=='customer'){
                $customers = $customers->where('is_supplier', '!=', 'yes');
            }
        }
        $customers = $customers->where('customer_status', '=', 'permanent');
        // $customers = $customers->get();
        
        // if($customer_filter == "supplier"){
        //     $value = 'no';
        // } else {
        //     $value = '';
        // }
        $allcustomers = $customers->with('states', 'getcity', 'deliverylocation', 'manager')->get();
        return view('excelView.customer', array('allcustomers' => $allcustomers));

    }
}
