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
                        'id' => '1',
                        'first_name' => 'Super',
                        'last_name' => 'Admin',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989890',
                        'email' => 'sadmin@admin.com',
                        // 'password' => '$2y$10$vkxXDsOalnfS.ANuYhDIkunjjtZGzvVD497Tvl0/vCnBAMKxkcaYC',
                        'password' => '$2y$10$jpxgWw.w0OgC6yT2/DIycOO/VeeNfxus6FFiZuiglNcPAtirgDTOm',
                        'role_id' => '0'
                    ),
                    //Admin
                    array(
                        'id' => '2',
                        'first_name' => 'Admin',
                        'last_name' => 'User',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989891',
                        'email' => 'admin@admin.com',
                        'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia',
                        'role_id' => '0'
                    ),
                    //Sales Staff
                    array(
                        'id' => '3',
                        'first_name' => 'Ajay Sales',
                        'last_name' => 'Wagh',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989892',
                        'email' => 's1@s1.com',
                        'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia',
                        'role_id' => '2'
                    ),
                    // Delivery Staff
                    array(
                        'id' => '4',
                        'first_name' => 'Vijay Delivery',
                        'last_name' => 'Dighe',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989893',
                        'email' => 'd1@d1.com',
                        'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia',
                        'role_id' => '3'
                    ),
                    //Account User
                    array(
                        'id' => '5',
                        'first_name' => 'Dinesh',
                        'last_name' => 'Ingale',
                        'phone_number' => '1234567890',
                        'mobile_number' => '9898989894',
                        'email' => 'd1@d1.com',
                        'password' => '$2y$10$CscOJeTOQHM3cLOBmIRFT.7owVhR7NUjT/UTlo3Z9PR3SS9w3m.Ia',
                        'role_id' => '4'
                    )
        ));
    }

}
