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
                        'id' => '6',
                        'role_id' => '5',
                        'name' => 'Customer'
                    ),
                     array(
                        'id' => '7',
                        'role_id' => '6',
                        'name' => 'Collection User'
                    ),
        ));
        
        DB::table('debited_tos')->truncate();
        DB::table('debited_tos')->insert(
                array(
                    array('id' => '1', 'debited_to_type' => '1', 'debited_to' => 'User'),
                    array('id' => '2', 'debited_to_type' => '2', 'debited_to' => '12345'),
                    array('id' => '3', 'debited_to_type' => '2', 'debited_to' => '56789'),
                    array('id' => '4', 'debited_to_type' => '2', 'debited_to' => '01234'),
                    array('id' => '5', 'debited_to_type' => '3', 'debited_to' => 'Petty Cash'),
                    array('id' => '6', 'debited_to_type' => '3', 'debited_to' => 'Regular Cash'),
        ));
    }

}
