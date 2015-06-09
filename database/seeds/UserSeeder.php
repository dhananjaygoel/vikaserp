<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder {

    public function run() {
        DB::table('users')->truncate();
        DB::table('users')->insert(
                array(
                    array('id' => '1', 'first_name' => 'Super_admin', 'last_name' => 'Super_admin', 'phone_number' => '1234567890', 'mobile_number' => '9898989890', 'email' => 'sadmin@admin.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia', 'role_id' => '0'),
                    array('id' => '2', 'first_name' => 'Admin_firstname', 'last_name' => 'Admin_last_name', 'phone_number' => '1234567890', 'mobile_number' => '9898989891', 'email' => 'admin@admin.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia', 'role_id' => '1'),
                    array('id' => '3', 'first_name' => 'sales_staff_first_name', 'last_name' => 'sales_staff_last_name', 'phone_number' => '1234567890', 'mobile_number' => '989898993', 'email' => 's1@s1.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia', 'role_id' => '2'),
                    array('id' => '4', 'first_name' => 'delivery_staff_first_name', 'last_name' => 'delivery_staff_last_name', 'phone_number' => '1234567890', 'mobile_number' => '989898994', 'email' => 'd1@d1.com', 'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia', 'role_id' => '3')
        ));
    }

}
