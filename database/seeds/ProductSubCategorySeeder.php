<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSubCategorySeeder extends Seeder {

    public function run() {
        // DB::table('users')->truncate();
        DB::table('users')->insert(
                array(
                    array(
                        'id' => '3705',
                        'product_category_id' => '85',
                        'alias_name' => 'Freight Charges',
                        'size' => '20',
                        'hsn_code' => '1345',
                        'unit_id' => '1',
                        'weight' => '20',
                        'thickness' => '20',
                        'standard_length' => '20',
                        'difference' => '20',
                        'length_unit' => 'ft',
                        'quickbook_item_id' => null,
                        'quickbook_a_item_id' => '1278'
                    ),                    
                    array(
                        'id' => '3706',
                        'product_category_id' => '85',
                        'alias_name' => 'Loading Charges',
                        'size' => '20',
                        'hsn_code' => '1345',
                        'unit_id' => '1',
                        'weight' => '20',
                        'thickness' => '20',
                        'standard_length' => '20',
                        'difference' => '20',
                        'length_unit' => 'ft',
                        'quickbook_item_id' => null,
                        'quickbook_a_item_id' => '1279'
                    ),
                    array(
                        'id' => '3707',
                        'product_category_id' => '85',
                        'alias_name' => 'Discount',
                        'size' => '20',
                        'hsn_code' => '1345',
                        'unit_id' => '1',
                        'weight' => '20',
                        'thickness' => '20',
                        'standard_length' => '20',
                        'difference' => '20',
                        'length_unit' => 'ft',
                        'quickbook_item_id' => null,
                        'quickbook_a_item_id' => '1280'
                    )
        ));
    }

}
