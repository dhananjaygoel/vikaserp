<?php

namespace App\Exports;

use App\Inventory;
use Input;
use App\ProductSubCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InventoryExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Inventory::all();
    // }

    public function view(): View
    {
        $query = Inventory::query();
        if (Input::has('inventory_filter') && Input::get('inventory_filter') == 'minimal') {
            $query->whereRaw('minimal < physical_closing_qty-pending_delivery_order_qty-pending_sales_order_qty+pending_purchase_advise_qty');
        }
        if (Input::has('product_category_filter') && Input::get('product_category_filter') != '') {
            $categoryid = Input::get('product_category_filter');
            $query->whereHas('product_sub_category', function($q) use($categoryid) {
                $q->where('product_category_id', '=', $categoryid);
            });
        }

        if (Input::has('search_inventory') && Input::get('search_inventory') != '') {
            $alias_name = '%' . trim(Input::get('search_inventory')) . '%';
            $product_sub_id = ProductSubCategory::where('alias_name', 'LIKE', $alias_name)->first();
            if (count((array)$product_sub_id)) {
                $query->where('product_sub_category_id', '=', $product_sub_id->id);
            }
        }

        $inventorys = $query->with('product_sub_category')
                ->join('product_sub_category', 'inventory.product_sub_category_id', '=', 'product_sub_category.id')
                ->orderBy('product_sub_category.alias_name', 'ASC')
                ->get();

        $virtual_stock_qty = array();
        foreach ($inventorys as $inventory) {
            $virtual_qty = ($inventory->physical_closing_qty + $inventory->pending_purchase_order_qty + $inventory->pending_purchase_advise_qty) - ($inventory->pending_sales_order_qty + $inventory->pending_delivery_order_qty);
            array_push($virtual_stock_qty,number_format($virtual_qty,2,'.', ''));
        }

        return view('excelView.inventory', array('inventorys' => $inventorys,'virtual_stock_qty' => $virtual_stock_qty));
    }
}
