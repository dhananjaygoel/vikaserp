<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
       DB::table('user_roles')->truncate();
        DB::table('user_roles')->insert(
                array(
                    array(
                        'id' => '1',
                        'role_id' => '0',
                        'name' => 'Super Admin'
                    ),
                    array(
                        'id' => '2',
                        'role_id' => '0',
                        'name' => 'Admin'
                    ),
                    array(
                        'id' => '3',
                        'role_id' => '2',
                        'name' => 'Sales Staff'
                    ),
                    array(
                        'id' => '4',
                        'role_id' => '3',
                        'name' => 'Delivery Staff'
                    ),
                    array(
                        'id' => '5',
                        'role_id' => '4',
                        'name' => 'Account User'
                    ),
                    array(
                        'id' => '8',
                        'role_id' => '7',
                        'name' => 'Security User'
                    ),
                     array(
                        'id' => '9',
                        'role_id' => '8',
                        'name' => 'Delivery Supervisor'
                    ),
                      array(
                        'id' => '10',
                        'role_id' => '9',
                        'name' => 'Delivery Boy'
                    ),
                    array(
                        'id' => '11',
                        'role_id' => '10',
                        'name' => 'Bulk Delete User'
                    ),
        ));
    }

}
