<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsSeeder extends Seeder {

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
                    )
        ));               
    }

}
