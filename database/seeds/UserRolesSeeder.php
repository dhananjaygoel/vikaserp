<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder {

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
        
        DB::table('debited_tos')->truncate();
        DB::table('debited_tos')->insert(
                array(
                    array('id' => '1', 'debited_to_type' => '1', 'debited_to' => 'User'),
                    array('id' => '2', 'debited_to_type' => '2', 'debited_to' => 'Bank 1'),
                    array('id' => '3', 'debited_to_type' => '2', 'debited_to' => 'Bank 2'),
                    array('id' => '4', 'debited_to_type' => '2', 'debited_to' => 'Bank 3'),
                    array('id' => '5', 'debited_to_type' => '3', 'debited_to' => 'Petty Cash'),
                    array('id' => '6', 'debited_to_type' => '3', 'debited_to' => 'Regular Cash'),
        ));
    }

}
