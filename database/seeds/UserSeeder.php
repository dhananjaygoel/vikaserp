<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
        DB::table('users')->truncate();
        DB::table('users')->insert(
                array(
                    //Super Admin
                    array(
                        'id' => '13',
                        'first_name' => 'Super',
                        'last_name' => 'Admin',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989890',
                        'email' => 'sadmin@admin.com',
                        
                        'password' => '$2y$10$wraJ4uNWGMrftZnjpQcnuu.ar8OQQGntL2Hgol/eFT4Vp6V34Oaay',
                        'role_id' => '0'
                    ),
                    
        ));
    }

}
