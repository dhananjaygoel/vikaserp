<?php

namespace App\Exports;

use App\ProductSubCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductCatHSNcode implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $product_size_list = ProductSubCategory::with('product_category')->get();
        return view('excelView.productsize', array('product_size_list' => $product_size_list));
    }
    // public function query()
    // {
    //     return ProductSubCategory::all();
    // }
    // public function headings(): array
    // {
    //     return ["id", "product_category_id", "alias_name", "size", "hsn_code", "unit_id", "weight", "thickness", "standard_length", "difference", "created_at", "updated_at", "length_unit", "quickbook_item_id", "quickbook_a_item_id"];
    // }
}
