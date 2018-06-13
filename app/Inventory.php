<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Inventory extends Model {

    use SoftDeletes;

    protected $table = 'inventory';

    public function product_sub_category() {
        return $this->belongsTo('App\ProductSubCategory', 'product_sub_category_id', 'id');
    }

//    public function update_opening_stock() {
//        $inventory_list = Inventory::all();
//        if (count($inventory_list) > 0) {
//
//            foreach ($inventory_list as $inventory) {
//                $inventory->opening_qty = $inventory->physical_closing_qty;
//                $inventory->purchase_challan_qty = 0;
//                $inventory->physical_closing_qty = 0;
//                $inventory->opening_qty_date = date('Y-m-d H:i:s');
//                $inventory->save();
//            }
//        }
//    }
    public function update_opening_stock() {
        $inventory_list = Inventory::update([
            'opening_qty' => DB::raw("`physical_closing_qty`"),
            'purchase_challan_qty' => 0,
            'sales_challan_qty' => 0,
            'physical_closing_qty' => DB::raw("`physical_closing_qty`"),
            'opening_qty_date' => date('Y-m-d H:i:s'),
            ]);
        return $inventory_list;
    }

}
