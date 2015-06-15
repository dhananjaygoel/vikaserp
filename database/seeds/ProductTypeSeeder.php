<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder {

    public function run() {
        DB::table('product_type')->truncate();
        DB::table('product_type')->insert(
                array(
                    array('id' => '1','name' =>'Pipe' ),
                    array('id' => '2','name' =>'Structure')                    
                ));
    }
}
