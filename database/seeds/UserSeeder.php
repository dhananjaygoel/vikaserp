<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
       /*$subcategories = App\ProductSubCategory::all();
       foreach ($subcategories as $subcat) {
        $hsn = App\ProductCategory::where('id', $subcat->product_category_id)->get()->first();
         DB::table('product_sub_category')->where('product_category_id', $subcat->product_category_id)->update([
                'hsn_code' => $hsn->hsn_code,
            ]);*/
             DB::table('product_type')->truncate();
        DB::table('product_type')->insert(
                array(
                    array('id' => '1','name' =>'Pipe' ),
                    array('id' => '2','name' =>'Structure'),
                    array('id' => '3','name' =>'Sheets')
                ));
       }

    }

}
