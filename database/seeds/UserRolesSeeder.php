<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
//        DB::table('user_roles')->truncate();
        DB::table('user_roles')->insert(
                array(
                    array('id' => '1','role_id' => '0','name' =>'Super Admin'),
                    array('id' => '2','role_id' => '1','name' =>'Admin'),
                    array('id' => '3','role_id' => '2','name' =>'Sales Staff'),
                    array('id' => '4','role_id' => '3','name' =>'Delivery Staff'),                    
                ));
    }
}
