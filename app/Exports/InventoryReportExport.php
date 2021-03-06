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
        $dropdown_filter = $request['dropdown_filter'];
        $size_value = $request['size_filter'];
        
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
            foreach ($product_last[0]['product_sub_categories']->sortBy('thickness') as $sub_cat) {
                if (!in_array($sub_cat->thickness, $thickness_array)) {
                    array_push($thickness_array, $sub_cat->thickness);
                }
            }
            foreach ($product_last[0]['product_sub_categories']->sortBy('size') as $sub_cat) {
                $valid=1;
                $size_tmp=$sub_cat->size;
                $size_tmp=preg_replace('/[^0-9]/', ' ', $size_tmp);
                $size_tmp=trim($size_tmp);
                $size_tmp=substr($size_tmp,0,3);
                $size_tmp=trim($size_tmp);
                $size_tmp=(int)$size_tmp;
                if($size_value=='small'){
                    if($size_tmp < 100){
                        $valid=1;
                    } else {
                        $valid=0;
                    }
                } else if($size_value=='large'){
                    if($size_tmp >= 100){
                        $valid=1;
                    } else {
                        $valid=0;
                    }
                }
                if (!in_array($sub_cat->size, $size_array) && $valid==1) {
                    array_push($size_array, $sub_cat->size);
                }
            }
            foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        if ($sub_cat->thickness == $thickness && $size == $sub_cat->size) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if(isset($dropdown_filter) && $dropdown_filter == 'physical_closing'){
                                if (isset($inventory->physical_closing_qty)) {
                                    $total_qnty = $inventory->physical_closing_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'opening'){
                                if (isset($inventory->opening_qty)) {
                                    $total_qnty = $inventory->opening_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'pending_sales_order'){
                                if (isset($inventory->pending_sales_order_qty)) {
                                    $total_qnty = $inventory->pending_sales_order_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'pending_delivery_order'){
                                if (isset($inventory->pending_delivery_order_qty)) {
                                    $total_qnty = $inventory->pending_delivery_order_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'pending_purchase_advice'){
                                if (isset($inventory->pending_purchase_advise_qty)) {
                                    $total_qnty = $inventory->pending_purchase_advise_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }else{
                                if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                    $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                                } else {
                                    $total_qnty = "-";
                                }
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
            foreach ($product_last[0]['product_sub_categories']->sortBy('alias_name') as $sub_cat) {
                $valid=1;
                $size_tmp=$sub_cat->size;
                $size_tmp=preg_replace('/[^0-9]/', ' ', $size_tmp);
                $size_tmp=trim($size_tmp);
                $size_tmp=substr($size_tmp,0,3);
                $size_tmp=trim($size_tmp);
                $size_tmp=(int)$size_tmp;
                if($size_value=='small'){
                    //dd($size_value);
                    if($size_tmp < 100){
                        $valid=1;
                    } else {
                        $valid=0;
                    }
                } else if($size_value=='large'){
                    if($size_tmp >= 100){
                        $valid=1;
                    } else {
                        $valid=0;
                    }
                }
                if (!in_array($sub_cat->alias_name, $size_array) && $valid==1) {
                    array_push($size_array, $sub_cat->alias_name);
                }
            }
            // foreach ($size_array as $size) {
                foreach ($thickness_array as $thickness) {
                    foreach ($product_last[0]['product_sub_categories'] as $sub_cat) {
                        // if ($sub_cat->thickness == $thickness && $size == $sub_cat->alias_name) {
                            $inventory = $sub_cat['product_inventory'];
                            $total_qnty = 0;
                            if(isset($dropdown_filter) && $dropdown_filter == 'physical_closing'){
                                if (isset($inventory->physical_closing_qty)) {
                                    $total_qnty = $inventory->physical_closing_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'opening'){
                                if (isset($inventory->opening_qty)) {
                                    $total_qnty = $inventory->opening_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'pending_sales_order'){
                                if (isset($inventory->pending_sales_order_qty)) {
                                    $total_qnty = $inventory->pending_sales_order_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'pending_delivery_order'){
                                if (isset($inventory->pending_delivery_order_qty)) {
                                    $total_qnty = $inventory->pending_delivery_order_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }elseif(isset($dropdown_filter) && $dropdown_filter == 'pending_purchase_advice'){
                                if (isset($inventory->pending_purchase_advise_qty)) {
                                    $total_qnty = $inventory->pending_purchase_advise_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }else{
                                if (isset($inventory->physical_closing_qty) && isset($inventory->pending_purchase_advise_qty)) {
                                    $total_qnty = $inventory->physical_closing_qty + $inventory->pending_purchase_advise_qty;
                                } else {
                                    $total_qnty = "-";
                                }
                            }
                            // $report_arr[$size][$thickness] = $total_qnty;
                            $report_arr[$sub_cat->alias_name][$thickness] = $total_qnty;
                        // }
                    }
                }
            // }
        }
        foreach ($size_array as $size) {
            foreach ($thickness_array as $thickness) {
                if (isset($report_arr[$size][$thickness])) {
//                        $final_arr[$size][$thickness] = $report_arr[$size][$thickness];
                    $final_arr[$size][$thickness] = round((float)$report_arr[$size][$thickness] / 1000, 2);
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
