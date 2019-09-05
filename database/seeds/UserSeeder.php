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
                        'id' => '12',
                        'first_name' => 'Super',
                        'last_name' => 'Admin',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989890',
                        'email' => 'sadmin@admin.com',
                        // 'password' => '$2y$10$vkxXDsOalnfS.ANuYhDIkunjjtZGzvVD497Tvl0/vCnBAMKxkcaYC',
                        'password' => 'E7KKc6hOMXITXWU7WSBhhfW7sImxcmvrVo2t62OCSg2o1uBNG68A6RLCxGyI',
                        'role_id' => '0'
                    ),
                    
        ));
    }

}
