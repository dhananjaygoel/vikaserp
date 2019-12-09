<?php

namespace App\Exports;

use App\ProductCategory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Input;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryReportExport implements FromView, ShouldAutoSize
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
        $product_id = $request['product_id'];
        $product_cat = ProductCategory::orderBy('created_at', 'asc')->get();
        $product_last = ProductCategory::where('id', '=', $product_id)->with('product_sub_categories.product_inventory')->get();
        $size_array = [];
        $thickness_array = [];
        $report_arr = [];
        $final_arr = [];
        $product_id = $product_last[0]->id;
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
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                            } else {
                                $total_qnty = "-";
                            }
                            $report_arr[$size][$thickness] = $total_qnty;
                        }
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
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->alias_name) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                            } else {
                                $total_qnty = "-";
                            }
                            $report_arr[$size][$thickness] = $total_qnty;
                        }
                    }
                }
            }
        }
        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
//                        $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                    $final_arr[$size][$thickness] = round($report_arr[$size][$thickness] / 1000, 2);
                } else {
                    $final_arr[$size][$thickness] = "-";
                }
            }
        }
        // sort($thickness_array);
        $report_arr = $final_arr;
        return view('excelView.inventory_report', array('product_last' => $product_last, 'thickness_array' => $thickness_array, 'report_arr' => $report_arr, 'product_column' => $product_column));
    }
}
