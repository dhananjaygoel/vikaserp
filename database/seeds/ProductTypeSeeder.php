<?php

use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void product_type
     */

    public function run() {
        DB::table('product_type')->truncate();
        DB::table('product_type')->insert(
                array(
                    array('id' => '1','name' =>'Pipe' ),
                    array('id' => '2','name' =>'Structure'),
                    array('id' => '3','name' =>'Sheets')
                ));
    }
}