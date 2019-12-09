<?php

namespace App\Exports;

use Input;
use App\ProductSubCategory;
use App\ProductType;
use App\Units;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductSizeExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ProductSubCategory::all();
    // }

    public function view(): View
    {
        $product_type = ProductType::all();
        $units = Units::all();
        $product_sub_cat = "";
        $input_data = Input::all();

        $q = ProductSubCategory::query();
        $q->with('product_category');
        if (Input::get('product_filter') != "") {
            $q->whereHas('product_category', function($query) {
                $query->where('product_type_id', Input::get('product_filter'));
            });
        }
        if (Input::get('search_text') != "") {

            $q->whereHas('product_category', function($query) {
                $query->where('product_category_name', 'like', '%' . Input::get('search_text') . '%');
            });
        }
        if (Input::get('product_size') != "") {
            if (strpos(Input::get('product_size'), '-') !== false) {
                $size_ar = explode("-", Input::get('product_size'));
                $size = $size_ar[0];
                $size2 = $size_ar[1];
                $q->whereHas('product_category', function($query) use ($size, $size2) {
//                    $query->where('size', 'like', '%' . trim($size) . '%')->orWhere('alias_name', 'like', '%' . trim($size2) . '%');
                    $query->where('alias_name', 'like', '%' . trim($size2) . '%');
                });
            } else {
                $blanck = Input::get('product_size');
                $q->whereHas('product_category', function($query) use ($blanck) {
                    $query->where('size', 'like', '%' . $blanck . '%')
                            ->orWhere('alias_name', 'like', '%' . $blanck . '%');
                });
            }
        }
        if (Input::has('export_data') && Input::get('export_data') == 'Export') {
            set_time_limit(0);
             $product_size_list = $q->orderBy('id', 'asc')->get();

        return view('excelView.productsize', array('product_size_list' => $product_size_list));
        }
    }
}
