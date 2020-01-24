<?php

use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void unit
     */

    public function run() {
        DB::table('unit')->truncate();
        DB::table('unit')->insert(
                array(
                    array(
                        'id' => '1',
                        'unit_name' => 'KG'
                    ),
                    array(
                        'id' => '2',
                        'unit_name' => 'Pieces'
                    ),
                    array(
                        'id' => '3',
                        'unit_name' => 'Meter'
                    ),
                    array(
                        'id' => '4',
                        'unit_name' => 'ft'
                    ),
                    array(
                        'id' => '5',
                        'unit_name' => 'mm'
                    )
        ));               
    }

}