<?php

namespace App\Exports;

use App\ProductCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryPriceExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ProductCategory::all();
    // }

    public function view(): View
    {
        $request = Input::all();
        $product_id = isset($request['product_id'])?$request['product_id']:"";
        $size = isset($request['size'])?$request['size']:"";
        $thickness = isset($request['thickness'])?$request['thickness']:"";
        $new_price = isset($request['new_price'])?$request['new_price']:"";
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $product_price = $product_last[0]->price;
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_type = $product_last[0]->product_type_id;
        if ($product_type == 1 || $product_type == 3) {
            $product_column = "Size";
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->size, $size_array)) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    if ($sub_cat->thickness == $thickness) {
                        $inventory = $sub_cat['product_inventory'];
                        $total_price = $product_price + $sub_cat->difference;

                        $report_arr[$sub_cat->size][$sub_cat->thickness] = $total_price;
                    }
                }
            }
        }
        if ($product_type == 2) {
            $product_column = "Product Alias";
            array_push($thickness_array, "NA");
            foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                if (!in_array($sub_cat->alias_name, $size_array)) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            foreach ($thickness_array as $thickness) {
                foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                    $total_price = 0;
                    $inventory = $sub_cat['product_inventory'];
                    $total_price = $product_price + $sub_cat->difference;
                    $report_arr[$sub_cat->alias_name][$thickness] = $total_price;
                }
            }
        }

        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
                    $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }

        $report_arr = $final_arr;
        return view('excelView.inventory_price_list', array('product_last' => $product_last, 'thickness_array' => $thickness_array, 'report_arr' => $report_arr, 'product_column' => $product_column));
    }
}
