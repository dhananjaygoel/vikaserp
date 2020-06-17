<?php

use Illuminate\Database\Seeder;
use App\ProductSubCategory;

class UpdateProductSize extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $update_item = ProductSubCategory::all();
        foreach($update_item as $data){
            $prod_name = str_replace('  ', ' ', $data->alias_name);
            DB::table('product_sub_category')->where('id',$data->id)->update([
                'alias_name' => trim($prod_name)
            ]);
        }
    }
}
