<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class debitedToSeeder extends Seeder {

    public function run() {
        DB::table('debited_bies')->truncate();
        DB::table('debited_bies')->insert(
                array(
                    array('id' => '1', 'debited_by_type' => '1', 'debited_by' => 'User'),
                    array('id' => '2', 'debited_by_type' => '2', 'debited_by' => '12345'),
                    array('id' => '3', 'debited_by_type' => '2', 'debited_by' => '56789'),
                    array('id' => '4', 'debited_by_type' => '2', 'debited_by' => '01234'),
                    array('id' => '5', 'debited_by_type' => '3', 'debited_by' => 'Petty Cash'),
                    array('id' => '6', 'debited_by_type' => '3', 'debited_by' => 'Regular Cash'),
        ));
    }

}
