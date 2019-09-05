<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
       $subcategories = App\ProductSubCategory::all();
       foreach ($subcategories as $subcat) {
        $hsn = App\ProductCategory::where('id', $subcat->product_category_id)->get()->first();
        print $hsn;
        die();
       }

    }

}
